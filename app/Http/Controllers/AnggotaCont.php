<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Helpers\UserAkses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AnggotaCont extends Controller
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
        if(UserAkses::cek_akses('anggota','lihat')==false)return redirect(route('noakses')) ;

        $rekap = DB::select('SELECT count(id) as jumlah, status FROM anggota group by status');
         
        $data = Anggota::all();
        $tag = ['menu'=> 'Master Data','submenu'=>'Anggota','judul'=>'DAFTAR ANGGOTA', 'menuurl'=>'anggota','modal'=>'true'];
        return view('data_anggota.index', compact('tag','data','rekap'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $kota = DB::table('kota')->get();
        if(UserAkses::cek_akses('anggota','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_anggota.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(UserAkses::cek_akses('anggota','cud')==false)return redirect(route('noakses')) ;

        $data = new Anggota;
        $data->nomor = $this->buatKode('anggota','nomor', 'AT');  //STS=setoran simpanan  , STP
        $data->nama = $request->nama;
        $data->nik = $request->nik;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->jk = $request->jk;
        $data->lokasikerja = $request->lokasi;
        $data->jabatan = $request->jabatan;
        $data->bank = $request->bank;
        $data->norekening = $request->norekening;
        $data->atasnama = $request->atasnama;
        
        $data->tempat_lahir = $request->tempatlahir;

        $data->tgl_lahir = $this->EnglishTgl($request->tgllahir);
        $data->tgl_daftar = $this->EnglishTgl($request->tgldaftar);
        $data->status = $request->status ?? 1;
        $data->tanggal_refund = $this->EnglishTgl($request->tglnonaktif);

        $data->save();
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $nik = Auth::user()->email;
        $nik = substr($nik,0,6);
        $data = Anggota::where('nik',$nik)->first();
        $kota = DB::table('kota')->get();

        $tag = ['menu'=> 'Master Data','submenu'=>'Anggota','judul'=>'PROFILE ANGGOTA', 'menuurl'=>'anggota','modal'=>'false'];
        return view('data_anggota.profile', compact('data','tag','kota'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $data = Anggota::find($id);
        $kota = DB::table('kota')->get();
        return view('data_anggota.edit', compact('data','kota'));
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
        if(UserAkses::cek_akses('anggota','cud')==false)return redirect(route('noakses')) ;

        $data = Anggota::find($request->id);
        $data->nama = $request->nama;
        $data->nik = $request->nik;
        $data->email = $request->email;
        $data->phone = $request->phone; 
        $data->jk = $request->jk;
        // $data->alamat = $request->alamat;
        // $data->kota = $request->kota;
        $data->tempat_lahir = $request->tempatlahir;
        $data->tgl_lahir = $this->EnglishTgl($request->tgllahir);
        $data->tgl_daftar = $this->EnglishTgl($request->tgldaftar);
        $data->status = $request->status;
        $data->lokasikerja = $request->lokasi;
        $data->jabatan = $request->jabatan;
        $data->bank = $request->bank;
        $data->norekening = $request->norekening;
        $data->atasnama = $request->atasnama;
        $data->tanggal_refund = $this->EnglishTgl($request->tglnonaktif);

        $data->save();

        return redirect()->back();

    }

    public function filter($kode){

        $rekap = DB::select('SELECT count(id) as jumlah, status FROM anggota group by status');

        $data = Anggota::where('status',$kode)->get();
        $str = $kode == 0 ? 'TIDAK AKTIF' : 'AKTIF' ;
        $tag = ['menu'=> 'Master Data','submenu'=>'Anggota','judul'=>'DAFTAR ANGGOTA '.$str, 'menuurl'=>'anggota','modal'=>'true'];
        return view('data_anggota.index', compact('tag','data','rekap'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(UserAkses::cek_akses('anggota','cud')==false)return redirect(route('noakses')) ;

        Anggota::destroy($id);
        return redirect()->back();

    }

    function buatKode($tabel,$kolom, $inisial){
        
        $row = DB::table($tabel)->selectRaw("max($kolom) as kode")->first();
        $nourut = substr($row->kode, -5);
        $nourut++;
        $nourut = sprintf("%05s", $nourut);
         
        return $inisial.$nourut;
    }

    function EnglishTgl($tanggal){

        $tgl = explode('/',$tanggal);
        if($tanggal=='' || $tanggal=='00'){
            $awal=null;
        }else{
            $awal = "$tgl[2]-$tgl[1]-$tgl[0]";
        }
        return $awal;	
    }
}
