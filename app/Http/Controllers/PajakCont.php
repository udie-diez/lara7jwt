<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Akun;
use App\Pajak;
use Illuminate\Http\Request;

class PajakCont extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        // $this->authorize('isAdmin');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //if (!FacadesGate::allows('isAdmin')) return 'Maaf, Anda tidak memiliki hak akses';
        if (UserAkses::cek_akses('pajak', 'lihat') == false) return redirect(route('noakses'));

        $data = Pajak::leftjoin('akun as a', 'pajak.akuninid', 'a.id')->leftjoin('akun as b', 'pajak.akunoutid', 'b.id')
            ->select('pajak.*', 'a.kode as kodein', 'a.nama as namain', 'b.nama as namaout', 'b.kode as kodeout')
            ->get();
        $tag = ['menu' => 'Master Data', 'submenu' => 'Pajak', 'judul' => 'Daftar Pajak', 'menuurl' => 'pajak', 'modal' => 'true'];
        return view('data_pajak.index', compact('tag', 'data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (UserAkses::cek_akses('pajak', 'cud') == false) return 'Maaf, Anda tidak memiliki akses !';
        $akun = Akun::all();
        return view('data_pajak.create', compact('akun'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (UserAkses::cek_akses('pajak', 'cud') == false) return redirect(route('noakses'));

        $data = new Pajak;
        $data->nama = $request->nama;
        $data->akuninid = $request->akunin;
        $data->akunoutid = $request->akunout;
        $data->nilai = str_replace(',', '.', $request->nilai);
        $data->save();
        return redirect()->back();
    }

    public function edit($id)
    {
        $data = Pajak::find($id);
        $akun = Akun::all();

        return view('data_pajak.edit', compact('data', 'akun'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id = 0)
    {
        if (UserAkses::cek_akses('pajak', 'cud') == false) return redirect(route('noakses'));

        $data = Pajak::find($request->id);
        $data->nama = $request->nama;
        $data->akuninid = $request->akunin;
        $data->akunoutid = $request->akunout;
        $data->nilai = str_replace(',', '.', $request->nilai);
        $data->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (UserAkses::cek_akses('pajak', 'cud') == false) return redirect(route('noakses'));

        Pajak::destroy($id);
        return redirect()->back();
    }
}
