<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Pajak;
use App\Pembelian_pemesanan;
use App\Pembelian_pemesanan_produk;
use App\Pembelian_penawaran;
use App\Produk;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade as PDF;


class PembelianPemesananCont extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (UserAkses::cek_akses('pemesanan', 'lihat') == false) return redirect(route('noakses'));

        $data = Pembelian_pemesanan::join('vendor', 'vendor.id', 'pembelian_pemesanan.vendorid')
            ->select('pembelian_pemesanan.*', 'vendor.nama as vendor')
            ->selectRaw('(select sum(jumlah+pajak) from pembelian_pemesanan_produk where Pembelian_pemesananid = pembelian_pemesanan.id group by pembelian_pemesananid) as total')
            ->get();
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Pemesanan Pembelian ', 'judul' => 'DAFTAR PEMESANAN PEMBELIAN (PO)', 'menuurl' => 'po', 'modal' => 'true'];
        return view('pembelian_pemesanan.index', compact('tag', 'data'));
    }

    public function create()
    {
        if (UserAkses::cek_akses('pemesanan', 'cud') == false) return redirect(route('noakses'));

        $vendor = Vendor::select('id', 'nama')->get();
        $pajak = Pajak::all();
        $produk = Produk::select('id', 'nama')->get();
        $kodeubah = 1;
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Pemesanan Pembelian (PO)', 'judul' => 'INPUT PEMESANAN PEMBELIAN (PO)', 'menuurl' => 'po', 'modal' => 'true'];

        return view('pembelian_pemesanan.create', compact('vendor', 'kodeubah', 'tag', 'pajak', 'produk'));
    }

    public function show($id, $kodeubah = 2, $kode=0)
    {
        if($kode==1){

            if (UserAkses::cek_akses('pemesanan', 'cud') == false) return redirect(route('noakses'));
        }


        $data = Pembelian_pemesanan::leftjoin('pembelian_penawaran', 'pembelian_penawaran.id', 'pembelian_pemesanan.pq_id')
            ->where('pembelian_pemesanan.id', $id)->select('pembelian_pemesanan.*', 'pembelian_penawaran.kode as kode_pq')
            ->first();
        $vendor = Vendor::select('id', 'nama')->get();
        $produk = Produk::select('id', 'nama')->get();
        $pajak = Pajak::all();
        $itemproduk = Pembelian_pemesanan_produk::where('Pembelian_pemesananid', $id)->get();

        $tag = ['menu' => 'Pembelian', 'submenu' => 'Data Pemesanan Pembelian', 'judul' => 'PEMESANAN PEMBELIAN (PO)', 'menuurl' => 'po', 'modal' => 'true'];
        return view('Pembelian_pemesanan.create', compact('data', 'vendor', 'produk', 'itemproduk', 'tag', 'kodeubah', 'pajak'));
    }

    public function edit($id)
    {
        return $this->show($id, 3,1);
    }

    public function update(Request $request)
    {
        if (UserAkses::cek_akses('pemesanan', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id == "") {
            $id = Pembelian_pemesanan::insertGetId([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'kode' => $this->buatKode('Pembelian_pemesanan', 'kode', 'PO'),
                'vendorid' => $request->vendor,
                'pq_id' => $request->pq_id,


                'status' => 0
            ]);
        } else {
            $data = Pembelian_pemesanan::find($id);
            $data->tanggal = $this->EnglishTgl($request->tanggal);
            $data->vendorid = $request->vendor;
            $data->pq_id = $request->pq_id;
            $data->save();
        }

        if ($id > 0) {
            //lanjut
        } else {
            Session::flash('warning', 'Data Pemesanan Pembelian gagal disimpan');
            return redirect()->back();
        }

        $jmlbaris = $request->txbaris;

        Pembelian_pemesanan_produk::where('Pembelian_pemesananid', $id)->delete();

        for ($i = 1; $i <= $jmlbaris; $i++) {
            $arr = array();
            $produkid = 'produk' . $i;
            $qty = 'tx2_' . $i;
            $satuan = 'tx3_' . $i;
            $harga = 'tx4_' . $i;
            $jumlah = 'tx5_' . $i;
            $arr += ['Pembelian_pemesananid' => $id];
            $arr += ['produkid' => $request->$produkid];
            $arr += ['qty' => $this->Angkapolos($request->$qty)];
            $arr += ['satuan' => $request->$satuan];
            $arr += ['harga' => $this->Angkapolos($request->$harga)];
            $arr += ['jumlah' => $this->Angkapolos($request->$jumlah)];

            $pajakid = 'pajak' . ($i);
            $pajaknilaix = Pajak::where('id', $request->$pajakid)->get();

            $pajaknilai = 0;
            if ($pajaknilaix !== null) {
                foreach ($pajaknilaix as $p) {
                    $pajaknilai = $p->nilai;
                }
            }
            $pajakjumlah = 0;
            $pajakjumlah = $this->cekNum($this->Angkapolos($request->$jumlah)) * ($pajaknilai / 100);
            $arr += ['pajakid' => $request->$pajakid];
            $arr += ['pajak' => $pajakjumlah];

            $insert = Pembelian_pemesanan_produk::insert($arr);
        }

        if ($request->pq_id > 0) {
            Pembelian_penawaran::where('id', $request->pq_id)->update(['status' => 1]);
        }

        return redirect(route('show_po', $id));
    }

    public function createPembelian($id){
        if (UserAkses::cek_akses('pemesanan', 'cud') == false) return redirect(route('noakses'));

        $po = Pembelian_pemesanan::find($id); 
        $data = (object) [
            "vendorid" => $po->vendorid,
            "po_id" => $id
        ];

        $vendor = Vendor::select('id', 'nama')->get();
        $pajak = Pajak::all();
        $produk = Produk::select('id', 'nama')->get();
        $itemproduk = Pembelian_pemesanan_produk::where('Pembelian_pemesananid', $id)->get();
        $kodeubah = 3;
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Input Pembelian', 'judul' => 'INPUT PEMBELIAN', 'menuurl' => 'pembelian', 'modal' => 'true'];

        return view('pembelian.create', compact('vendor', 'kodeubah', 'tag', 'pajak', 'produk','data','itemproduk'));
    }

    public function destroyProduk($id)
    {
        if (UserAkses::cek_akses('pemesanan', 'cud') == false) return redirect(route('noakses'));

        Pembelian_pemesanan_produk::destroy($id);
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('pemesanan', 'cud') == false) return redirect(route('noakses'));

        Pembelian_pemesanan::destroy($id);
        return redirect()->back();
    }

    public function cetak($id)
    {
        if (UserAkses::cek_akses('pemesanan', 'cetak') == false) return redirect(route('noakses'));

        $data = Pembelian_pemesanan::join('vendor','vendor.id','pembelian_pemesanan.vendorid')
        ->where('pembelian_pemesanan.id',$id)
        ->select('pembelian_pemesanan.*', 'vendor.nama as vendor', 'vendor.alamat', 'vendor.kota', 'vendor.phone','vendor.email')
        ->first();

        $pajak = Pajak::all();
        $itemproduk = Pembelian_pemesanan_produk::join('produk', 'produk.id','pembelian_pemesanan_produk.produkid')
        ->where('pembelian_pemesanan_produk.pembelian_pemesananid', $id)
        ->select('pembelian_pemesanan_produk.*','produk.nama')
        ->get();

        $koperasi = DB::table('koperasi')->first();
        $pdf = PDF::loadview('pembelian_pemesanan.cetak', ['data' => $data,  'itemproduk' => $itemproduk, 'koperasi' => $koperasi])->setPaper('A4', 'potrait');
        return $pdf->stream();
    }

    function Angkapolos($nilai)
    {
        return str_replace('.', '', $nilai);
    }
    function cekNum($nilaix)
    {
        $nilai = $this->Angkapolos($nilaix);
        $nilai = str_replace(',', '.', $nilai);
        if (is_numeric($nilai) && $nilai > 0) {
            return $nilai;
        } else {
            return $nilaix;
        }
    }
    function buatKode($tabel, $kolom, $inisial)
    {

        $row = DB::table($tabel)->selectRaw("max($kolom) as kode")->first();
        $nourut = substr($row->kode, -5);
        $nourut++;
        $nourut = sprintf("%05s", $nourut);

        return $inisial . $nourut;
    }
    function EnglishTgl($tanggal)
    {

        $tgl = str_replace('/', '-', $tanggal);
        $tgl = explode('-', $tgl);
        if ($tanggal == '' || $tanggal == '00') {
            $awal = null;
        } else {
            $awal = "$tgl[2]-$tgl[1]-$tgl[0]";
        }
        return $awal;
    }
}
