<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\JenisSimpanan;
use Illuminate\Http\Request;

class JenisSimpananCont extends Controller
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
        if(UserAkses::cek_akses('jenis_simpanan','lihat')==false)return redirect(route('noakses')) ;

        $data = JenisSimpanan::all();
        $tag = ['menu'=> 'Master Data','submenu'=>'Jenis Simpanan','judul'=>'Daftar Jenis Simpanan', 'menuurl'=>'jenis_simpanan','modal'=>'true'];
        return view('data_jenis_simpanan.index', compact('tag','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(UserAkses::cek_akses('jenis_simpanan','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_jenis_simpanan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(UserAkses::cek_akses('jenis_simpanan','cud')==false)return redirect(route('noakses')) ;

        $data = new JenisSimpanan;
        $data->nama = strtoupper($request->nama);
        $data->nilai =  str_replace('.','',$request->nilai);
        $data->status = $request->status;
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
        $data = JenisSimpanan::find($id);
        return view('data_jenis_simpanan.edit', compact('data'));
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
        if(UserAkses::cek_akses('jenis_simpanan','cud')==false)return redirect(route('noakses')) ;

        $data = JenisSimpanan::find($request->id);
        $data->nama = strtoupper($request->nama);
        $data->nilai =  str_replace('.','',$request->nilai);
        $data->status = $request->status;
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
        if(UserAkses::cek_akses('jenis_simpanan','cud')==false)return redirect(route('noakses')) ;

        JenisSimpanan::destroy($id);
        return redirect()->back();

    }
 
}
