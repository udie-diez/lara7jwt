<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukCont extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if(UserAkses::cek_akses('produk','lihat')==false)return redirect(route('noakses')) ;

        $data = Produk::all();
        $tag = ['menu' => 'Master Data', 'submenu' => 'Produk', 'judul' => 'DAFTAR PRODUK BARANG/JASA', 'menuurl' => 'produk', 'modal' => 'true'];
        return view('data_produk.index', compact('tag', 'data'));
    }

    public function create()
    {
        if(UserAkses::cek_akses('produk','cud')==false)return 'Maaf, Anda tidak memiliki akses !' ;

        return view('data_produk.form');
    }

    public function edit($id)
    {
        $data = Produk::find($id);
        return view('data_produk.form', compact('data'));
    }

    public function update(Request $request)
    {
        if(UserAkses::cek_akses('produk','cud')==false)return redirect(route('noakses')) ;

        if ($request->id == "") {
            $data = new Produk;
        } else {
            $data = Produk::find($request->id);
        }

        $nama = str_replace("'","`", str_replace('"','``',$request->nama));
        $data->nama = $nama;
        $data->kode = $this->buatKode('produk','kode', 'P');  
        $data->satuan = $request->satuan;
        $data->jenis = $request->jenis;
        $data->keterangan = str_replace("'","`",$request->keterangan);

        $data->save();

        $tag = $request->tag ?? '';
        if($tag=='pembelian'){

            return $nama . '|' .$data->id;

        }else{

            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        if(UserAkses::cek_akses('produk','cud')==false)return redirect(route('noakses')) ;

        Produk::destroy($id);
        return redirect()->back();
    }

    function buatKode($tabel,$kolom, $inisial){
        
        $row = DB::table($tabel)->selectRaw("max($kolom) as kode")->first();
        $nourut = substr($row->kode, -5);
        $nourut++;
        $nourut = sprintf("%05s", $nourut);
         
        return $inisial.$nourut;
    }
}
