<?php

namespace App\Livewire;

use App\Services\GeminiSqlService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Throwable;

class AiQueryTable extends Component
{
    public const EXAMPLES = [
        'Tampilkan 10 data karyawan terbaru',
        'Hitung jumlah karyawan per departemen',
        'Tampilkan karyawan yang masuk tahun ini',
    ];

    public string $prompt = '';
    public string $sql = '';
    public string $explanation = '';
    public array $columns = [];
    public ?string $error = null;
    public ?string $warning = null;
    public bool $asked = false;

    // Hasil query disimpan di cache server (bukan di state Livewire), supaya data besar
    // tidak ikut dikirim bolak-balik ke browser pada setiap interaksi.
    #[Locked]
    public ?string $resultKey = null;
    public int $totalRows = 0;

    // Tabel: pagination, pencarian, sorting
    public int $perPage = 10;
    public int $page = 1;
    public string $search = '';
    public ?string $sortBy = null;
    public string $sortDir = 'asc';

    public function ask(GeminiSqlService $ai): void
    {
        $this->validate(['prompt' => 'required|string|min:5|max:500']);
        $this->resetResult();
        $this->asked = true;

        try {
            $result            = $ai->generate($this->prompt);
            $this->explanation = $result['explanation'];
            $this->sql         = $result['sql'];
            $this->warning     = $result['missing']
                ? 'Tabel berikut tidak terbaca oleh user database (cek nama tabel di config atau GRANT SELECT): ' . implode(', ', $result['missing'])
                : null;

            $this->execute($ai);
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
            Log::warning('AI query gagal', ['prompt' => $this->prompt, 'sql' => $this->sql, 'error' => $e->getMessage()]);
        }
    }

    public function useExample(int $index, GeminiSqlService $ai): void
    {
        $this->prompt = self::EXAMPLES[$index] ?? '';
        $this->ask($ai);
    }

    /** Jalankan ulang SQL yang diedit manual (tetap divalidasi). */
    public function rerun(GeminiSqlService $ai): void
    {
        $this->forgetResult();
        $this->reset('columns', 'error', 'search', 'sortBy', 'page');
        $this->sortDir   = 'asc';
        $this->totalRows = 0;

        try {
            $this->execute($ai);
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
        }
    }

    public function sort(string $column): void
    {
        if (! in_array($column, $this->columns, true)) {
            return;
        }

        $this->sortDir = ($this->sortBy === $column && $this->sortDir === 'asc') ? 'desc' : 'asc';
        $this->sortBy  = $column;
        $this->page    = 1;
    }

    public function gotoPage(int $page): void
    {
        $this->page = max(1, $page);
    }

    public function updatedSearch(): void
    {
        $this->page = 1;
    }

    public function updatedPerPage(): void
    {
        $this->perPage = in_array($this->perPage, [10, 25, 50, 100], true) ? $this->perPage : 10;
        $this->page    = 1;
    }

    private function resetResult(): void
    {
        $this->forgetResult();
        $this->reset('sql', 'explanation', 'columns', 'error', 'warning', 'search', 'sortBy', 'page', 'asked');
        $this->sortDir   = 'asc';
        $this->totalRows = 0;
    }

    private function execute(GeminiSqlService $ai): void
    {
        $data = $ai->run($this->sql);

        $this->resultKey = $this->keyPrefix() . Str::uuid();
        Cache::put($this->resultKey, $data['rows'], now()->addMinutes(30));

        $this->columns   = $data['columns'];
        $this->totalRows = count($data['rows']);
    }

    private function keyPrefix(): string
    {
        return 'ai-query:' . (auth()->id() ?? 'guest') . ':';
    }

    private function loadRows(): array
    {
        if (! $this->resultKey || ! str_starts_with($this->resultKey, $this->keyPrefix())) {
            return [];
        }

        return Cache::get($this->resultKey, []);
    }

    private function forgetResult(): void
    {
        if ($this->resultKey && str_starts_with($this->resultKey, $this->keyPrefix())) {
            Cache::forget($this->resultKey);
        }
        $this->resultKey = null;
    }

    /** @return array<string,int> nama kolom nominal uang (sebagai key) */
    private function moneyColumns(): array
    {
        return array_flip(array_filter($this->columns, fn ($c) => GeminiSqlService::isMoney($c)));
    }

    private function filtered(array $rows): array
    {
        if ($this->search !== '') {
            $q     = mb_strtolower($this->search);
            $money = $this->moneyColumns();

            // Pencarian juga cocok dengan angka uang yang tampil (mis. "5,000,000")
            $rows = array_filter($rows, function ($r) use ($q, $money) {
                $parts = [];
                foreach ($r as $key => $value) {
                    $parts[] = (string) $value;
                    if (isset($money[$key]) && is_numeric($value)) {
                        $parts[] = number_format((float) $value, 0, '.', ',');
                    }
                }

                return str_contains(mb_strtolower(implode(' ', $parts)), $q);
            });
        }

        if ($this->sortBy) {
            $col = $this->sortBy;
            $dir = $this->sortDir === 'asc' ? 1 : -1;

            usort($rows, function ($a, $b) use ($col, $dir) {
                $x = $a[$col] ?? null;
                $y = $b[$col] ?? null;

                return $dir * (is_numeric($x) && is_numeric($y)
                    ? $x <=> $y
                    : strnatcasecmp((string) $x, (string) $y));
            });
        }

        return array_values($rows);
    }

    public function render()
    {
        $rows = $this->loadRows();

        // Cache sudah kedaluwarsa (30 menit)
        if ($this->resultKey && $this->totalRows > 0 && $rows === []) {
            $this->resultKey = null;
            $this->totalRows = 0;
            $this->columns   = [];
            $this->error     = 'Hasil query sudah kedaluwarsa. Silakan jalankan ulang.';
        }

        $all   = $this->filtered($rows);
        $total = count($all);
        $pages = max(1, (int) ceil($total / $this->perPage));

        $this->page = min(max(1, $this->page), $pages);

        return view('livewire.ai-query-table', [
            'items'    => array_slice($all, ($this->page - 1) * $this->perPage, $this->perPage),
            'hasRows'  => $this->totalRows > 0,
            'money'    => $this->moneyColumns(),
            'labels'   => collect($this->columns)->mapWithKeys(fn ($c) => [$c => GeminiSqlService::label($c)])->all(),
            'total'    => $total,
            'pages'    => $pages,
            'from'     => $total ? ($this->page - 1) * $this->perPage + 1 : 0,
            'to'       => min($this->page * $this->perPage, $total),
            'start'    => max(1, $this->page - 2),
            'end'      => min($pages, $this->page + 2),
            'examples' => self::EXAMPLES,
        ]);
    }
}
