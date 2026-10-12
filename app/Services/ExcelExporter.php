<?php

namespace App\Services;

use DateTime;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

/**
 * Membuat file Excel yang rapi dari hasil query:
 * banner judul, header berwarna, baris selang-seling, format uang/tanggal, filter, freeze header, baris total.
 */
class ExcelExporter
{
    private const HEADER_ROW = 5;      // baris 1-3: banner, 4: spasi, 5: judul kolom
    private const SAMPLE     = 200;    // jumlah baris yang diperiksa untuk menentukan tipe & lebar kolom

    public function fileName(): string
    {
        return config('ai-query.export.filename', 'hasil-query') . '-' . date('Ymd-His') . '.xlsx';
    }

    /**
     * @param  array<int, array<string, mixed>>  $rows
     * @param  string[]  $columns
     * @param  array{prompt?:string, user?:?string, search?:string, total?:int}  $meta
     */
    public function build(array $rows, array $columns, array $meta = []): Spreadsheet
    {
        $cfg    = (array) config('ai-query.export', []);
        $color  = array_merge([
            'banner' => '1E3A8A', 'header' => '2563EB', 'stripe' => 'EFF6FF', 'border' => 'CBD5E1', 'total' => 'DBEAFE',
        ], (array) ($cfg['colors'] ?? []));
        $numbering = (bool) ($cfg['numbering'] ?? true);
        $wantTotal = (bool) ($cfg['totals'] ?? true);

        $spreadsheet = new Spreadsheet();
        $spreadsheet->getDefaultStyle()->getFont()->setName('Calibri')->setSize(10);
        $spreadsheet->getProperties()
            ->setCreator($meta['user'] ?? 'AI Query')
            ->setTitle($cfg['title'] ?? 'Laporan Data');

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Hasil Query');
        $sheet->setShowGridlines(false);
        $sheet->getTabColor()->setARGB('FF' . $color['header']);

        // ---- Tipe, label, dan lebar tiap kolom ----
        $sample = array_slice($rows, 0, self::SAMPLE);
        $cols   = [];
        foreach ($columns as $name) {
            $type = $this->detect($name, $sample);
            $cols[] = [
                'name'  => $name,
                'label' => GeminiSqlService::label($name),
                'type'  => $type,
                'width' => $this->width($name, $type, $sample),
            ];
        }
        if ($numbering) {
            array_unshift($cols, ['name' => null, 'label' => 'No', 'type' => 'no', 'width' => 6]);
        }

        $nCols    = count($cols);
        $lastCol  = Coordinate::stringFromColumnIndex($nCols);
        $hr       = self::HEADER_ROW;
        $first    = $hr + 1;
        $count    = count($rows);
        $last     = $hr + $count;                       // baris data terakhir (= $hr jika kosong)
        $letters  = [];
        foreach ($cols as $i => $_) {
            $letters[$i] = Coordinate::stringFromColumnIndex($i + 1);
        }

        // ---- Lebar kolom ----
        foreach ($cols as $i => $c) {
            $sheet->getColumnDimension($letters[$i])->setWidth($c['width']);
        }
        $totalWidth = array_sum(array_column($cols, 'width'));

        // ---- Banner (baris 1-3) ----
        $title   = (string) ($cfg['title'] ?? 'Laporan Data');
        $prompt  = trim((string) ($meta['prompt'] ?? ''));
        $infoBits = [
            'Diekspor ' . date('d/m/Y H:i'),
            number_format($count, 0, '.', ',') . ' baris',
        ];
        if (! empty($meta['search'])) {
            $infoBits[] = 'filter pencarian: "' . $meta['search'] . '"';
        }
        if (! empty($meta['user'])) {
            $infoBits[] = 'oleh ' . $meta['user'];
        }
        $line2 = $prompt !== '' ? 'Pertanyaan: ' . $prompt : '';
        $line3 = implode('  •  ', $infoBits);

        $sheet->setCellValueExplicit("A1", $title, DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("A2", $line2, DataType::TYPE_STRING);
        $sheet->setCellValueExplicit("A3", $line3, DataType::TYPE_STRING);
        foreach ([1, 2, 3] as $r) {
            $sheet->mergeCells("A{$r}:{$lastCol}{$r}");
        }
        $sheet->getStyle("A1:{$lastCol}3")->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $color['banner']);
        $sheet->getStyle("A1:{$lastCol}3")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER)->setIndent(1);
        $sheet->getStyle("A1")->getFont()->setSize(16)->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle("A2")->getFont()->setItalic(true)->getColor()->setARGB('FFDBEAFE');
        $sheet->getStyle("A2")->getAlignment()->setWrapText(true);
        $sheet->getStyle("A3")->getFont()->setSize(9)->getColor()->setARGB('FFBFDBFE');
        $sheet->getRowDimension(1)->setRowHeight(34);
        $lines2 = min(5, max(1, (int) ceil(mb_strlen($line2) / max($totalWidth * 1.05, 30))));
        $sheet->getRowDimension(2)->setRowHeight($line2 === '' ? 4 : 16 * $lines2 + 6);
        $sheet->getRowDimension(3)->setRowHeight(20);
        $sheet->getRowDimension(4)->setRowHeight(8);

        // ---- Header kolom ----
        foreach ($cols as $i => $c) {
            $sheet->setCellValueExplicit($letters[$i] . $hr, $c['label'], DataType::TYPE_STRING);
        }
        $headRange = "A{$hr}:{$lastCol}{$hr}";
        $sheet->getStyle($headRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $color['header']);
        $sheet->getStyle($headRange)->getFont()->setBold(true)->getColor()->setARGB('FFFFFFFF');
        $sheet->getStyle($headRange)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER)
            ->setWrapText(true);
        $sheet->getRowDimension($hr)->setRowHeight(30);

        // ---- Data ----
        foreach ($rows as $n => $row) {
            $r = $first + $n;
            foreach ($cols as $i => $c) {
                $cell = $letters[$i] . $r;

                if ($c['type'] === 'no') {
                    $sheet->setCellValueExplicit($cell, $n + 1, DataType::TYPE_NUMERIC);
                    continue;
                }

                $v = $row[$c['name']] ?? null;
                if ($v === null || $v === '') {
                    continue;
                }

                switch ($c['type']) {
                    case 'money':
                    case 'number':
                        $sheet->setCellValueExplicit($cell, $v + 0, DataType::TYPE_NUMERIC);
                        break;
                    case 'date':
                    case 'datetime':
                        $serial = $this->excelDate((string) $v);
                        if ($serial === null) {
                            $sheet->setCellValueExplicit($cell, (string) $v, DataType::TYPE_STRING);
                        } else {
                            $sheet->setCellValueExplicit($cell, $serial, DataType::TYPE_NUMERIC);
                        }
                        break;
                    default: // text: string eksplisit, jadi "081234..." tidak kehilangan 0 dan "=..." tidak dianggap rumus
                        $sheet->setCellValueExplicit($cell, (string) $v, DataType::TYPE_STRING);
                }
            }
        }

        // ---- Format per kolom (satu style untuk seluruh rentang kolom) ----
        if ($count > 0) {
            foreach ($cols as $i => $c) {
                $range = "{$letters[$i]}{$first}:{$letters[$i]}{$last}";
                $style = $sheet->getStyle($range);
                switch ($c['type']) {
                    case 'money':
                        $style->getNumberFormat()->setFormatCode('#,##0');
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setIndent(1);
                        break;
                    case 'number':
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        break;
                    case 'date':
                        $style->getNumberFormat()->setFormatCode('dd/mm/yyyy');
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        break;
                    case 'datetime':
                        $style->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm');
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        break;
                    case 'no':
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
                        $style->getFont()->getColor()->setARGB('FF64748B');
                        break;
                    default:
                        $style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT)->setIndent(1);
                }
            }
            $sheet->getStyle("A{$first}:{$lastCol}{$last}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

            // Header kolom uang rata kanan agar sejajar dengan angkanya
            foreach ($cols as $i => $c) {
                if ($c['type'] === 'money') {
                    $sheet->getStyle("{$letters[$i]}{$hr}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setIndent(1);
                }
            }

            // Baris selang-seling. <= 5.000 baris: hanya menghitung baris yang terlihat (tetap rapi saat difilter);
            // lebih besar: pakai nomor baris sheet agar file tetap ringan.
            $zebra = new Conditional();
            $zebra->setConditionType(Conditional::CONDITION_EXPRESSION);
            $zebra->addCondition(
                ($numbering && $count <= 5000)
                    ? "MOD(SUBTOTAL(103,\$A\${$first}:\$A{$first}),2)=0"
                    : 'MOD(ROW(),2)=1'
            );
            $zebra->getStyle()->getFill()->setFillType(Fill::FILL_SOLID);
            $zebra->getStyle()->getFill()->getStartColor()->setARGB('FF' . $color['stripe']);
            $zebra->getStyle()->getFill()->getEndColor()->setARGB('FF' . $color['stripe']);
            $sheet->getStyle("A{$first}:{$lastCol}{$last}")->setConditionalStyles([$zebra]);
        }

        // ---- Baris total untuk kolom uang ----
        $tableEnd = $last;
        $moneyIdx = array_keys(array_filter($cols, fn ($c) => $c['type'] === 'money'));
        if ($wantTotal && $count > 0 && $moneyIdx) {
            $t = $last + 1;
            $sheet->setCellValueExplicit("A{$t}", 'Total', DataType::TYPE_STRING);
            foreach ($moneyIdx as $i) {
                $sheet->setCellValue("{$letters[$i]}{$t}", "=SUBTOTAL(109,{$letters[$i]}{$first}:{$letters[$i]}{$last})");
                $sheet->getStyle("{$letters[$i]}{$t}")->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle("{$letters[$i]}{$t}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT)->setIndent(1);
            }
            $totalRange = "A{$t}:{$lastCol}{$t}";
            $sheet->getStyle($totalRange)->getFont()->setBold(true);
            $sheet->getStyle($totalRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF' . $color['total']);
            $sheet->getStyle($totalRange)->getBorders()->getTop()->setBorderStyle(Border::BORDER_MEDIUM)->getColor()->setARGB('FF' . $color['header']);
            $sheet->getStyle($totalRange)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getRowDimension($t)->setRowHeight(22);
            $tableEnd = $t;
        }

        // ---- Garis tabel ----
        $sheet->getStyle("A{$hr}:{$lastCol}{$tableEnd}")->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN)->getColor()->setARGB('FF' . $color['border']);

        // ---- Filter, freeze, cetak ----
        if ($count > 0) {
            $sheet->setAutoFilter("A{$hr}:{$lastCol}{$last}");
        }
        $sheet->freezePane('A' . $first);

        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0)
            ->setRowsToRepeatAtTopByStartAndEnd($hr, $hr);
        $sheet->getPageSetup()->setFitToPage(true);
        $sheet->getHeaderFooter()->setOddFooter('&L&8' . $title . '&R&8Halaman &P dari &N');
        $sheet->getPageMargins()->setTop(0.5)->setBottom(0.6)->setLeft(0.4)->setRight(0.4);

        return $spreadsheet;
    }

    /** Tentukan tipe kolom dari contoh nilainya: money | number | date | datetime | text. */
    private function detect(string $name, array $sample): string
    {
        $vals = array_values(array_filter(
            array_column($sample, $name),
            fn ($v) => $v !== null && $v !== ''
        ));
        if (! $vals) {
            return 'text';
        }

        $all = fn (callable $f) => count(array_filter($vals, $f)) === count($vals);

        if (GeminiSqlService::isMoney($name) && $all('is_numeric')) {
            return 'money';
        }
        if ($all(fn ($v) => is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}$/', $v) && ! str_starts_with($v, '0000'))) {
            return 'date';
        }
        if ($all(fn ($v) => is_string($v) && preg_match('/^\d{4}-\d{2}-\d{2}[ T]\d{2}:\d{2}(:\d{2})?$/', $v) && ! str_starts_with($v, '0000'))) {
            return 'datetime';
        }
        // Angka asli (int/float) atau desimal bertanda titik ("8.5"). String angka bulat ("0812...") tetap teks.
        if ($all(fn ($v) => is_int($v) || is_float($v) || (is_string($v) && preg_match('/^-?\d+\.\d+$/', $v) && ! preg_match('/^-?0\d/', $v)))) {
            return 'number';
        }

        return 'text';
    }

    private function width(string $name, string $type, array $sample): float
    {
        $header = min(mb_strlen(GeminiSqlService::label($name)) + 4, 28);

        $min = match ($type) {
            'money'    => 14,
            'date'     => 12,
            'datetime' => 17,
            'number'   => 9,
            default    => 10,
        };

        $longest = 0;
        foreach ($sample as $row) {
            $v = $row[$name] ?? null;
            if ($v === null || $v === '') {
                continue;
            }
            $len = $type === 'money' && is_numeric($v) ? mb_strlen(number_format((float) $v, 0, '.', ',')) : mb_strlen((string) $v);
            $longest = max($longest, $len);
        }

        return (float) min(max($min, $header, $longest + 3), 45);
    }

    private function excelDate(string $value): ?float
    {
        try {
            return (float) Date::PHPToExcel(new DateTime($value));
        } catch (\Throwable) {
            return null;
        }
    }
}
