<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelpers;
use App\Helpers\UserAkses;
use App\Perusahaan;
use Faker\Provider\ar_JO\Person;
use Illuminate\Http\Request;

class PerusahaanCont extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){

        if(UserAkses::cek_akses('perusahaan','lihat')==false)return redirect(route('noakses')) ;

        $data = Perusahaan::all();
        $tag = ['menu'=> 'Master Data','submenu'=>'Perusahaan','judul'=>'DAFTAR MITRA / PERUSAHAAN ', 'menuurl'=>'perusahaan','modal'=>'true'];
        return view('data_perusahaan.index', compact('tag','data'));
    }

    public function create()
    {
        if(UserAkses::cek_akses('perusahaan','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;
        $akun = MyHelpers::akundetail();
        return view('data_perusahaan.create',compact('akun'));
    }

    public function store(Request $request){
        if(UserAkses::cek_akses('perusahaan','cud')==false)return redirect(route('noakses')) ;

        $data = new Perusahaan;
        $data->nama     = $request->nama;
        $data->alamat   = $request->alamat;
        $data->kota     = $request->kota;
        $data->kodepos  = $request->kodepos;
        $data->email    = $request->email;
        $data->unitkerja= $request->unitkerja ?? $data->nama = $request->nama;
        $data->phone    = $request->phone;
        $data->alias    = $request->alias;
        $data->npwp     = $request->npwp;
        $data->status   = $request->status;
        $data->akunid   = $request->akun;

        $data->save();
        return redirect()->back();
    }

    public function edit($id)
    {
        $akun = MyHelpers::akundetail();
        $data = Perusahaan::find($id);
        return view('data_perusahaan.edit', compact('data','akun'));
    }

    public function update(Request $request){
        if(UserAkses::cek_akses('perusahaan','cud')==false)return redirect(route('noakses')) ;

        $data = Perusahaan::find($request->id);
        $data->nama     = $request->nama;
        $data->alamat   = $request->alamat;
        $data->kota     = $request->kota;
        $data->kodepos  = $request->kodepos;
        $data->unitkerja= $request->unitkerja ?? $data->nama = $request->nama;
        $data->email    = $request->email;
        $data->phone    = $request->phone;
        $data->alias    = $request->alias;
        $data->npwp     = $request->npwp;
        $data->status   = $request->status;
        $data->akunid   = $request->akun;
        
        $data->save();
        return redirect()->back();
    }

    public function destroy($id)
    {
        if(UserAkses::cek_akses('perusahaan','cud')==false)return redirect(route('noakses')) ;

        Perusahaan::destroy($id);
        return redirect()->back();

    }

}
