<?php
namespace App\Helpers;

use App\Akun;
use App\Mapping;
use App\Pajak;
use App\Transaksi;

class MyHelpers {

    public static function inputTransaksi($tgl, $item, $itemid,$akunid,$debit,$kredit,$hapus=0){
        if($hapus==1)Transaksi::where('item',$item)->where('itemid',$itemid)->delete();
        $transaksi = Transaksi::insert([
            'tanggal' => $tgl,
            'item' => $item,
            'itemid' => $itemid,
            'akunid' => $akunid,
            'debit' => $debit,
            'kredit' => $kredit
        ]);
    } 

    public static function akunMapping($jenis){
        return Mapping::where('jenis',$jenis)->first()->akunid;
    }

    public static function akundetail(){
        return Akun::where('jenis',1)->get();
    }

    public static function nilaiPpn(){
        return  Pajak::where('nama','PPN')->first()->nilai;
        
    }

    
}