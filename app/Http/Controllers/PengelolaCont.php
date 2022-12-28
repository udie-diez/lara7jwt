<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Pengelola;
use Illuminate\Http\Request;

class PengelolaCont extends Controller
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
        if(UserAkses::cek_akses('pengelola','lihat')==false)return redirect(route('noakses')) ;

        $data = Pengelola::all();
        $tag = ['menu'=> 'Master Data','submenu'=>'Pengelola','judul'=>'DAFTAR PENGELOLA', 'menuurl'=>'pengelola','modal'=>'true'];
        return view('data_pengelola.index', compact('tag','data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(UserAkses::cek_akses('pengelola','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_pengelola.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(UserAkses::cek_akses('pengelola','cud')==false)return redirect(route('noakses')) ;

        $data = new Pengelola;
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->phone = $request->phone;
        // $data->jk = $request->jk; 
        $data->nik = $request->nik; 
        $data->jabatan = $request->jabatan;
        $data->status = $request->status;

        $data->npwp = $request->npwp;
        $data->alamat = $request->alamat;
        $data->kota = $request->kota;
        $data->tanggal_lahir= $this->EnglishTgl($request->tgllahir);
        $data->nomor_ktp    = $request->ktp;
        $data->bank         = $request->bank;
        $data->atasnama_rekening     = $request->atasnama;
        $data->nomor_rek     = $request->norek;

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
        $data = Pengelola::find($id);
        return view('data_pengelola.edit', compact('data'));
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
        if(UserAkses::cek_akses('pengelola','cud')==false)return redirect(route('noakses')) ;

        $data = Pengelola::find($request->id);
        $data->nama         = $request->nama;
        $data->email        = $request->email;
        $data->phone        = $request->phone; 
        $data->jabatan      = $request->jabatan;
        $data->status       = $request->status;
        $data->nik          = $request->nik; 
        $data->npwp         = $request->npwp;
        $data->alamat         = $request->alamat;
        $data->kota         = $request->kota;
        $data->tanggal_lahir= $this->EnglishTgl($request->tgllahir);
        $data->nomor_ktp    = $request->ktp;
        $data->bank         = $request->bank;
        $data->atasnama_rekening     = $request->atasnama;
        $data->nomor_rek     = $request->norek;

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
        if(UserAkses::cek_akses('pengelola','cud')==false)return redirect(route('noakses')) ;

        Pengelola::destroy($id);
        
        return redirect()->back();

    }

    function EnglishTgl($tanggal){
        $tgl = substr($tanggal,0,2);
        $bln = substr($tanggal,3,2);
        $thn = substr($tanggal,6,4);
        if($tgl=='' || $tgl=='00'){
            $awal=null;
        }else{
            $awal = "$thn-$bln-$tgl";
    
        }
        return $awal;	
    }
}
