<?php

namespace App\Imports;

use App\Models\Presensi;
use Illuminate\Support\Collection;

use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportPresensi implements ToCollection, WithHeadingRow
{
    protected $data;
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $this->data = $rows;
        foreach ($rows as $row) {
            $data = $row->toArray();
        }
    }

    public function getData()
    {
        return $this->data;
    }
}
