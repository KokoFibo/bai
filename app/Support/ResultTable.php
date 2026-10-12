<?php

namespace App\Support;

use App\Services\GeminiSqlService;

/** Pencarian + pengurutan hasil query. Dipakai bersama oleh tabel di layar dan export Excel. */
class ResultTable
{
    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  string[]  $columns
     * @return array<int, array<string, mixed>>
     */
    public static function filter(array $rows, array $columns, string $search = '', ?string $sortBy = null, string $sortDir = 'asc'): array
    {
        if ($search !== '') {
            $q     = mb_strtolower($search);
            $money = array_flip(array_filter($columns, fn ($c) => GeminiSqlService::isMoney($c)));

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

        if ($sortBy && in_array($sortBy, $columns, true)) {
            $dir = $sortDir === 'desc' ? -1 : 1;

            usort($rows, function ($a, $b) use ($sortBy, $dir) {
                $x = $a[$sortBy] ?? null;
                $y = $b[$sortBy] ?? null;

                return $dir * (is_numeric($x) && is_numeric($y)
                    ? $x <=> $y
                    : strnatcasecmp((string) $x, (string) $y));
            });
        }

        return array_values($rows);
    }
}
