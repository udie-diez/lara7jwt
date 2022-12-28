<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Pemesan;
use Illuminate\Http\Request;

class PemesanCont extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        // $this->authorize('isAdmin');

    }

    public function index()
    {
        if(UserAkses::cek_akses('pemesan','lihat')==false)return redirect(route('noakses')) ;

        $data = Pemesan::all();
        $tag = ['menu'=> 'Master Data','submenu'=>'Pemesan/User','judul'=>'Daftar Pemesan/User Dan Atasannya', 'menuurl'=>'pemesan','modal'=>'true'];
        return view('data_pemesan.index', compact('tag','data'));
    }

    public function create()
    {
        if(UserAkses::cek_akses('pemesan','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_pemesan.form');
    }

    public function show($id)
    {
        if($id>0){
            $data = Pemesan::find($id);
            $kode='';
        }else{
            $data='';
            $kode = $id; //pemberi/mengetahui
        }
        return view('data_pemesan.form', compact('data','kode'));
    }

    public  function update(Request $request){
        if(UserAkses::cek_akses('pemesan','cud')==false)return redirect(route('noakses')) ;

        $id = $request->id;
        if($id==''){
            $id = Pemesan::create([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'jabatan' => $request->jabatan,
                'lokasikerja' => $request->lokasi,
                'status' => $request->status
            ]);
            $id = $id->id;

        }else{
            $id1 = Pemesan::find($id);
            $id1->nama = $request->nama;
            $id1->nik = $request->nik;
            $id1->email = $request->email;
            $id1->telepon = $request->telepon;
            $id1->jabatan = $request->jabatan;
            $id1->lokasikerja = $request->lokasi;
            $id1->status = $request->status;
            $id1->save();
        }

        return redirect()->back();

    }

    public function update1(Request $request)
    {

        $id = $request->id;
        if ($id == '') {

            $kode=$request->kode;  //pemberi/mengetahui
            $id = Pemesan::insertGetId([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'email' => $request->email,
                'telepon' => $request->telepon,
                'jabatan' => $request->jabatan,
                'lokasikerja' => $request->lokasikerja
            ]);
        } else {
            $kode=1;
            $id1 = Pemesan::find($id);
            $id1->nama = $request->nama;
            $id1->nik = $request->nik;
            $id1->email = $request->email;
            $id1->telepon = $request->telepon;
            $id1->jabatan = $request->jabatan;
            $id1->lokasikerja = $request->lokasikerja;
            $id1->save();
        }
        return ['id'=>$id,'nama'=>$request->nama,'kode'=>$kode];
    }

    public function destroy($id){
        if(UserAkses::cek_akses('pemesan','cud')==false)return redirect(route('noakses')) ;

        Pemesan::destroy($id);
        return redirect()->back();
    }

}
