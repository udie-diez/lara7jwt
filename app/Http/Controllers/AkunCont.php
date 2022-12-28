<?php

namespace App\Http\Controllers;

use App\Akun;
use App\Helpers\UserAkses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AkunCont extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (UserAkses::cek_akses('data_akun', 'lihat') == false) return redirect(route('noakses'));

        // $data = Akun::orderBy('kode')->get();
        $data = Akun::select('akun.*', DB::raw('(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0)) from transaksi where akunid = akun.id group by akunid) as saldo'))->orderBy('akun.kode')
            ->get();
        $tag = ['menu' => 'Master Data', 'submenu' => 'Akun', 'judul' => 'DAFTAR AKUN', 'menuurl' => 'akun', 'modal' => 'true'];
        return view('data_akun.index', compact('tag', 'data'));
    }

    public function create()
    {
        if (UserAkses::cek_akses('data_akun', 'cud') == false) return 'Maaf, Anda tidak memiliki akses !';

        return view('data_akun.form');
    }

    public function show($id)
    {
        if (UserAkses::cek_akses('data_akun', 'cud') == false) return 'Maaf, Anda tidak memiliki akses !';

        $data = Akun::find($id);
        return view('data_akun.form', compact('data'));
    }

    public function update(Request $request)
    {
        if (UserAkses::cek_akses('data_akun', 'cud') == false) return redirect(route('noakses'));

        if ($request->id == "") {
            $data = new Akun;
        } else {
            $data = Akun::find($request->id);
        }

        $data->nama = $request->nama;
        $data->kode = $request->kode;
        $data->jenis = $request->jenis;
        $data->saldo = $this->Angkapolos($request->saldo);
        $data->deskripsi = $request->deskripsi;

        $data->save();
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('data_akun', 'cud') == false) return redirect(route('noakses'));

        Akun::destroy($id);
        return redirect()->back();
    }
    function Angkapolos($nilai)
    {
        return str_replace('.', '', $nilai);
    }
}
