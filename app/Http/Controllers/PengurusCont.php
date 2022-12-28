<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Pengurus;
use Illuminate\Http\Request;

class pengurusCont extends Controller
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
        if(UserAkses::cek_akses('pengurus','lihat')==false)return redirect(route('noakses')) ;

        $data = Pengurus::all();
        $tag = ['menu'=> 'Master Data','submenu'=>'Pengurus','judul'=>'DAFTAR PENGURUS', 'menuurl'=>'pengurus','modal'=>'true'];
        return view('data_pengurus.index', compact('tag','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(UserAkses::cek_akses('pengurus','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_pengurus.create');
         

        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(UserAkses::cek_akses('pengurus','cud')==false)return redirect(route('noakses')) ;

        $data = new pengurus;
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->jk = $request->jk; 
        $data->jabatan = $request->jabatan;
        $data->status = $request->status;
        $data->nik = $request->nik;
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
        $data = Pengurus::find($id);
        return view('data_pengurus.edit', compact('data'));
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
        if(UserAkses::cek_akses('pengurus','cud')==false)return redirect(route('noakses')) ;

        $data = Pengurus::find($request->id);
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->jk = $request->jk; 
        $data->jabatan = $request->jabatan;
        $data->nik = $request->nik;
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
        if(UserAkses::cek_akses('pengurus','cud')==false)return redirect(route('noakses')) ;
        
            pengurus::destroy($id);
            return redirect()->back();
    }
}
