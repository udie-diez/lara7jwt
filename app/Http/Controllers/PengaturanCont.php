<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Akun;
use App\Helpers\MyHelpers;
use App\Mapping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PengaturanCont extends Controller
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
    public function mappingAkun()
    {
        if (UserAkses::cek_akses('mapping', 'lihat') == false) return redirect(route('noakses'));
 
        $akun = MyHelpers::akundetail();
        $data = Mapping::all();
        $tag = ['menu' => 'Pengaturan', 'submenu' => 'Mapping Akun', 'judul' => 'Mapping Akun', 'menuurl' => 'mappingAkun', 'modal' => 'true'];
        return view('pengaturan.mapping', compact('tag', 'data', 'akun'));
    }
 
    public function updateMapping(Request $request)
    {
            $data[] = ['jenis'=>'wajib','tag'=>'Akun Simpanan Wajib','akunid'=>$request->wajib];
            $data[] = ['jenis'=>'pokok','tag'=>'Akun Simpanan Pokok','akunid'=>$request->pokok];
            $data[] = ['jenis'=>'ppn_penjualan','tag'=>'Akun PPN Penjualan','akunid'=>$request->ppn_penjualan];
            $data[] = ['jenis'=>'angsuran_karyawan','tag'=>'Akun Piutang Karyawan','akunid'=>$request->angsuran_karyawan];
            $data[] = ['jenis'=>'angsuran_anggota','tag'=>'Akun Piutang Anggota','akunid'=>$request->angsuran_anggota];
            $data[] = ['jenis'=>'penjualan_barang','tag'=>'Akun Penjualan Barang','akunid'=>$request->penjualan_barang];
            $data[] = ['jenis'=>'penjualan_jasa','tag'=>'Akun Penjualan Jasa','akunid'=>$request->penjualan_jasa];
            $data[] = ['jenis'=>'simpanan','tag'=>'Akun Kas/Bank Simpanan','akunid'=>$request->simpanan];
            $data[] = ['jenis'=>'terima_pembayaran','tag'=>'Akun Kas/Bank Penerima Pembayaran','akunid'=>$request->terima_pembayaran];
            $data[] = ['jenis'=>'angsuran_pinjaman','tag'=>'Akun Kas/Bank Angsuran Pinjaman','akunid'=>$request->angsuran_pinjaman];
            $data[] = ['jenis'=>'pembelian_barang','tag'=>'Akun Pembelian Barang Dagangan','akunid'=>$request->pembelian_barang];
            Mapping::truncate();
            for($i=1;$i<count($data);$i++){
                Mapping::create($data[$i]);
            }

            Session::flash('sukses', 'Data Mapping Akun berhasil disimpan');
            return redirect()->back();
            
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
 
    }

    public function edit($id)
    {
    
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
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
    }
}
