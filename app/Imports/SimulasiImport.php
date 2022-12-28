<?php

namespace App\Imports;

use App\Simulasi;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithStartRow;

class SimulasiImport implements ToModel, WithStartRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        

        return new Simulasi([
            'bulan' => $row[2],
            'pokok' => $row[2],
            'margin' => $row[2],
            'angsuran' => $row[2],
            'outstanding' => $row[2]
            
        ]);
    }

     

    public function startRow(): int
    {
        return 13;
    }
}
