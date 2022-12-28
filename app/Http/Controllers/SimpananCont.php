<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Helpers\UserAkses;
use App\JenisSimpanan;
use App\Simpanan;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SimpananCont extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (Auth::user()->role == 'anggota') {
            $akses = '';
            $data = Simpanan::join('anggota', 'anggota.id', 'simpanan.anggotaid')
                ->select('simpanan.*', 'anggota.nama as nama_anggota', 'anggota.nik', 'anggota.nomor')
                ->where('anggota.nik', substr(Auth::user()->email, 0, 6))
                ->orderByRaw('anggota.nomor ASC, simpanan.tahun desc, simpanan.bulan ASC')
                ->get();
        } else {
            if (UserAkses::cek_akses('simpanan', 'lihat') == false) return redirect(route('noakses'));
            $akses = UserAkses::cek_akses('simpanan', 'cetak');
            $data = Simpanan::join('anggota', 'anggota.id', 'simpanan.anggotaid')
                ->select('simpanan.*', 'anggota.nama as nama_anggota', 'anggota.nik', 'anggota.nomor')
                ->orderByRaw('anggota.nomor ASC, simpanan.bulan ASC')
                ->get();
        }
        $anggota = Anggota::orderBy('nama')->get();
        $jenis_simpanan = JenisSimpanan::where('status', 1)->get();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Simpanan', 'judul' => 'Daftar Simpanan', 'menuurl' => 'simpanan', 'modal' => 'true'];
        return view('simpanan.index', compact('data', 'tag', 'jenis_simpanan', 'anggota', 'akses'));
    }

    public function saldo()
    {
        if (UserAkses::cek_akses('simpanan', 'lihat') == false) return redirect(route('noakses'));

        //rekap
         //simpanan
         $str = "STR_TO_DATE(concat(tahun,'/',bulan,'/1'), '%Y/%m/%d') <= STR_TO_DATE('".date('Y/m/d')."', '%Y/%m/%d') ";

         $str = "select 
         (select sum(nilai) from simpanan where (".$str.") and jenis_simpanan = 'wajib')  as wajib,
         (select sum(nilai)  from simpanan where (".$str.") and jenis_simpanan = 'pokok')  as pokok,
         sum(a.wajib) as saldowajib,
         sum(a.pokok) as saldopokok from anggota a where status <> 2 or (tanggal_refund > STR_TO_DATE('".date('Y/m/d')."', '%Y-%m-%d') )";

         $rekap = DB::select($str);
        
        $str = "STR_TO_DATE(concat(tahun,'/',bulan,'/1'), '%Y/%m/%d') <= STR_TO_DATE('".date('Y/m/d')."', '%Y/%m/%d') ";

        $str = "select a.nomor, a.nik, a.nama, 
        (select sum(nilai) from simpanan where (".$str.") and jenis_simpanan = 'wajib' and anggotaid = a.id)  as wajib,
        (select sum(nilai)  from simpanan where (".$str.") and jenis_simpanan = 'pokok' and anggotaid = a.id)  as pokok,
        a.wajib as saldowajib,
        a.pokok as saldopokok,
        '".date('m') .'/'.date('Y')."' as periode from anggota a where status <> 2 or (tanggal_refund > STR_TO_DATE('".date('Y/m/d')."', '%Y-%m-%d') )";
         
        $data = DB::select($str);

        //$data = DB::select('select a.nomor, a.nik, a.nama, a.wajib as saldowajib, a.pokok as saldopokok, "07/2021" as periode from anggota a');
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Saldo Simpanan', 'judul' => 'Saldo Simpanan', 'menuurl' => 'saldosimpanan', 'modal' => 'true'];
        return view('simpanan.saldo', compact('data', 'tag', 'rekap'));
    }

    public function filtersaldo(Request $request)
    {
        $bulan_f = $request->f_bulan;
        $tahun_f = $request->f_tahun;
        $tahun_f = $tahun_f == '' ? date('Y') : $tahun_f;

        $tgl = date('Y-m-d', strtotime($tahun_f . '-' . $bulan_f . '-1'));
        $date = new DateTime($tgl);
        $date = $date->modify('last day of this month');
        $tgl = $date->format('Y-m-d');

        if($bulan_f < 7 && $tahun_f <= 2021){
            $w_wajib = "0";
            $w_pokok = "0";
            $w_rekapwajib = "0";
            $w_rekappokok = "0";
        }else{
            $w_wajib = "a.wajib";
            $w_pokok = "a.pokok";
            $w_rekapwajib = "sum(a.wajib)";
            $w_rekappokok = "sum(a.pokok)";
        }

        // $data = DB::select("select a.nomor, a.nik, a.nama, 
        // (select nilai  from simpanan where bulan = ".$bulan_f." and tahun = '".$tahun_f."' and jenis_simpanan = 'wajib' and anggotaid = a.id)  as wajib,
        // (select nilai  from simpanan where bulan = 9 and tahun = '2021' and jenis_simpanan = 'pokok' and anggotaid = a.id)  as pokok,
        // ".$w_wajib." as saldowajib,
        // ".$w_pokok." as saldopokok,
        // '".$bulan_f .'/'.$tahun_f."' as periode from anggota a");
        
        $str = "STR_TO_DATE(concat(tahun,'/',bulan,'/1'), '%Y/%m/%d') <= STR_TO_DATE('".$tgl."', '%Y-%m-%d') ";

        $str = "select a.nomor, a.nik, a.nama, 
        (select sum(nilai) from simpanan where (".$str.") and jenis_simpanan = 'wajib' and anggotaid = a.id)  as wajib,
        (select sum(nilai)  from simpanan where (".$str.") and jenis_simpanan = 'pokok' and anggotaid = a.id)  as pokok,
        ".$w_wajib." as saldowajib,
        ".$w_pokok." as saldopokok,
        '".$bulan_f .'/'.$tahun_f."' as periode from anggota a where status <> 2 or (tanggal_refund > STR_TO_DATE('".$tgl."', '%Y-%m-%d') )";
        $data = DB::select($str);

        //rekap 
        $str = "STR_TO_DATE(concat(tahun,'/',bulan,'/1'), '%Y/%m/%d') <= STR_TO_DATE('".$tgl."', '%Y-%m-%d') ";

         $str = "select 
         (select sum(nilai) from simpanan where (".$str.") and jenis_simpanan = 'wajib')  as wajib,
         (select sum(nilai)  from simpanan where (".$str.") and jenis_simpanan = 'pokok')  as pokok,
         ".$w_rekapwajib." as saldowajib,
        ".$w_rekappokok." as saldopokok from anggota a where status <> 2 or (tanggal_refund > STR_TO_DATE('".$tgl."', '%Y-%m-%d') )";
         $rekap = DB::select($str);


        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Saldo Simpanan', 'judul' => 'Saldo Simpanan', 'menuurl' => 'saldosimpanan', 'modal' => 'true'];
        return view('simpanan.saldo', compact('data', 'tag','bulan_f', 'tahun_f', 'rekap' ));
        
    }

    public function filter(Request $request)
    {
        $tgl = $request->tglFilter;
        $anggotaid_f = $request->f_anggota;
        $ck_tanggal = $request->ck_tanggal;

        $tgl1 =  (substr($tgl, 0, 10));
        $tgl2 =  (substr($tgl, -10));

        $jenis_f = $request->f_jenissimpanan;
        $arrjenis_f = ($jenis_f == '' || $jenis_f == 'all') ? array('WAJIB', 'POKOK') : array($jenis_f);

        $arr_q = array();
        $w = '';
        if ($jenis_f == '' || $jenis_f == 'all') {
            //1
        } else {
            $arr_q[] = array('simpanan.jenis_simpanan', '=', $jenis_f);
        }
        if ($anggotaid_f == '' || $anggotaid_f == 'all') {
            //1
        } else {
            $arr_q[] = array('simpanan.anggotaid', $anggotaid_f);
        }
        if ($ck_tanggal) {
            //periode
            $arr_q[] = array(function ($query) use ($tgl1, $tgl2) {
                $query->where('simpanan.bulan', '>=', substr($tgl1, 3, 2))
                    ->where('simpanan.tahun', substr($tgl1, -4));
            });
            $arr_q[] = array(function ($query) use ($tgl1, $tgl2) {
                $query->where('simpanan.bulan', '<=', substr($tgl2, 3, 2))
                    ->where('simpanan.tahun', substr($tgl2, -4));
            });
        }

        $data = $data = Simpanan::join('anggota', 'anggota.id', 'simpanan.anggotaid')
            ->where($arr_q)
            ->select('simpanan.*', 'anggota.nama as nama_anggota', 'anggota.nik', 'anggota.nomor')
            ->orderByRaw('anggota.nomor ASC, simpanan.bulan ASC')
            ->get();

        $anggota = Anggota::orderBy('nama')->get();
        $jenis_simpanan = JenisSimpanan::where('status', 1)->get();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Simpanan', 'judul' => 'Daftar Simpanan', 'menuurl' => 'simpanan', 'modal' => 'true'];
        return view('simpanan.index', compact('data', 'tag', 'jenis_simpanan', 'jenis_f', 'tgl', 'anggotaid_f', 'anggota', 'ck_tanggal'));
    }
}
