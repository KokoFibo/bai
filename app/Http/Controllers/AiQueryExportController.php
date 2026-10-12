<?php

namespace App\Http\Controllers;

use App\Services\ExcelExporter;
use App\Support\ResultTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AiQueryExportController
{
    public function __invoke(Request $request, ExcelExporter $exporter): StreamedResponse
    {
        // Hasil query disimpan di cache per user; kunci milik user lain ditolak
        $key = (string) $request->query('key', '');
        abort_unless(str_starts_with($key, 'ai-query:' . (auth()->id() ?? 'guest') . ':'), 403);

        $payload = Cache::get($key);
        abort_unless(
            is_array($payload) && isset($payload['rows'], $payload['columns']),
            404,
            'Hasil query sudah kedaluwarsa. Jalankan ulang pertanyaan lalu export kembali.'
        );

        $columns = $payload['columns'];
        $sort    = $request->query('sort');
        $sort    = in_array($sort, $columns, true) ? $sort : null;
        $dir     = $request->query('dir') === 'desc' ? 'desc' : 'asc';
        $search  = (string) $request->query('search', '');

        // Export mengikuti pencarian & urutan yang sedang aktif di layar (semua halaman, bukan hanya halaman ini)
        $rows = ResultTable::filter($payload['rows'], $columns, $search, $sort, $dir);

        @ini_set('memory_limit', (string) config('ai-query.export.memory_limit', '1024M'));
        @set_time_limit(0);

        $spreadsheet = $exporter->build($rows, $columns, [
            'prompt' => $payload['prompt'] ?? '',
            'user'   => auth()->user()?->name,
            'search' => $search,
        ]);

        return new StreamedResponse(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->setPreCalculateFormulas(false);
            $writer->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 200, [
            'Content-Type'        => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="' . $exporter->fileName() . '"',
            'Cache-Control'       => 'no-store, max-age=0',
        ]);
    }
}
