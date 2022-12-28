<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Vendor;
use Illuminate\Http\Request;

class VendorCont extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(UserAkses::cek_akses('vendor','lihat')==false)return redirect(route('noakses')) ;

        $data = Vendor::all();
        $tag = ['menu' => 'Master Data', 'submenu' => 'Vendor', 'judul' => 'DAFTAR VENDOR', 'menuurl' => 'vendor', 'modal' => 'true'];
        return view('data_vendor.index', compact('tag', 'data'));
    }

    public function create()
    {
        if(UserAkses::cek_akses('vendor','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_vendor.form');
    }

    public function edit($id)
    {
        
        $data = Vendor::find($id);
        return view('data_vendor.form', compact('data'));
    }

    public function update(Request $request)
    {
        if(UserAkses::cek_akses('vendor','cud')==false)return redirect(route('noakses')) ;

        if ($request->id == "") {
            $data = new Vendor;
        } else {
            $data = Vendor::find($request->id);
        }

        $data->nama = $request->nama;
        $data->alamat = $request->alamat;
        $data->kota = $request->kota;
        $data->email = $request->email;
        $data->phone = $request->phone;
        $data->alias = $request->alias;
        $data->kontak = $request->kontak;
        $data->bank = $request->bank;
        $data->cabang = $request->cabang;
        $data->atasnama = $request->atasnama;
        $data->norek = $request->norek;
        $data->npwp = $request->npwp;

        $data->save();

        $tag = $request->tag ?? '';
        if($tag=='pembelian'){
            return $request->alias . '|' .$data->id;
        } 

        return redirect()->back();

    }

    public function destroy($id)
    {
        if(UserAkses::cek_akses('vendor','cud')==false)return redirect(route('noakses')) ;

        Vendor::destroy($id);
        return redirect()->back();
    }
}
