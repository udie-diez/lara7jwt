<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\SumberPinjaman;
use Illuminate\Http\Request;

class SumberPinjamanCont extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(UserAkses::cek_akses('sumber_pinjaman','lihat')==false)return redirect(route('noakses')) ;

        $data = SumberPinjaman::all();
        $tag = ['menu'=> 'Master Data','submenu'=>'Sumber Pinjaman','judul'=>'Daftar Sumber Pinjaman', 'menuurl'=>'sumber_pinjaman','modal'=>'true'];
        return view('data_sumber_pinjaman.index', compact('tag','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(UserAkses::cek_akses('sumber_pinjaman','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_sumber_pinjaman.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(UserAkses::cek_akses('sumber_pinjaman','lihat')==false)return redirect(route('noakses')) ;

        $data = new SumberPinjaman;
        $data->nama = strtoupper($request->nama);
        $data->keterangan = $request->keterangan;
        $data->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = SumberPinjaman::find($id);
        return view('data_sumber_pinjaman.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id=0)
    {
        if(UserAkses::cek_akses('sumber_pinjaman','lihat')==false)return redirect(route('noakses')) ;

        $data = SumberPinjaman::find($request->id);
        $data->nama = strtoupper($request->nama);
        $data->keterangan = $request->keterangan;
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
        if(UserAkses::cek_akses('sumber_pinjaman','lihat')==false)return redirect(route('noakses')) ;

        SumberPinjaman::destroy($id);
        return redirect()->back();

    }
 
}


