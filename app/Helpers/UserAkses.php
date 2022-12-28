<?php
namespace App\Helpers;

use App\Akses;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserAkses {
    public static function cek_akses($modul, $item) {
        if(Auth::user()->role=='admin')return true;
        $akses = Akses::where('userid',Auth::user()->id)->where('modul',$modul)->first();
        // $akses = Akses::where('userid',DB::raw("(select userid from users where id  = ".Auth::user()->id.")"))->where('modul',$modul)->first();
        if(isset($akses)) if($akses->$item == 'on'){
            return true;
        }
        return false;
         
    }
}