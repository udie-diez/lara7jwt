<?php

namespace App\Imports;

use App\TempPayroll;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithStartRow;

class PayrollImport implements ToModel, WithStartRow, WithCalculatedFormulas
{
    protected $periode;
    public function __construct($periode)
    {
        $this->periode = $periode;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $tgl_awal=$tgl_akhir = null;

        if($row[8]>0) {
            $UNIX_DATE = ($row[8] - 25569) * 86400;
            $tgl_awal = gmdate("Y-m-d", $UNIX_DATE);
        }

        if(strlen($row[9])==5){
            $UNIX_DATE = ($row[9] - 25569) * 86400;
            $tgl_akhir = gmdate("Y-m-d", $UNIX_DATE);
        }else if(strlen($row[9])==10){
            $tgl_akhir = substr($row[9],-4).'-'.substr($row[9],3,2).'-'.substr($row[9],0,2);
        }else{
            $tgl_akhir = null;
        }
        // $tgl_akhir =$row[9];
        // $tgl_akhir = DateTime::createFromFormat('y/m/d', $row[9]);
        // $tgl_akhir = $tgl_akhir == 0 ? null : $tgl_akhir;
        // if($row[9]>0) {
            
        //     $UNIX_DATE = ($row[9] - 25569) * 86400;

        //     $tgl_akhir = gmdate("Y-m-d", $UNIX_DATE);
        // }else{
        //     $tgl_akhir =  null;
        // }

        return new TempPayroll([
            'nik' => $row[0],
            'nama' => $row[1], 
            'simpanan' => $row[4],
            'py_simpanan' => $row[5],
            'angsuran' => $row[6],
            'py_angsuran' => $row[7],
            'lainlain' => $row[10],
            'py_lainlain' => $row[11],
            'tgl_awal' => $tgl_awal,
            'tgl_akhir' => $tgl_akhir,
            'total_kopeg' => $row[12],
            'total_py' => $row[13],
            'deviasi' => $row[14],
            'keterangan' => $row[15],
            'periode' => $this->periode

        ]);
    }

    public function startRow(): int
    {
        return 6;
    }

    public function prepareForValidation(array $row)
    {
        // return manipulated row from here
    }
}
