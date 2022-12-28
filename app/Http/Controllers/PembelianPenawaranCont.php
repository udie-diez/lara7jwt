<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Pajak;
use App\Pembelian_penawaran;
use App\Pembelian_penawaran_produk;
use App\Produk;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Barryvdh\DomPDF\Facade as PDF;

class PembelianPenawaranCont extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        if (UserAkses::cek_akses('penawaran', 'lihat') == false) return redirect(route('noakses'));

        $data = Pembelian_penawaran::join('vendor', 'vendor.id', 'pembelian_penawaran.vendorid')
            ->select('pembelian_penawaran.*', 'vendor.nama as vendor')
            ->selectRaw('(select sum(jumlah+pajak) from pembelian_penawaran_produk where pembelian_penawaranid = pembelian_penawaran.id group by pembelian_penawaranid) as total')
            ->get();
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Penawaran Pembelian ', 'judul' => 'DAFTAR PENAWARAN PEMBELIAN (PQ)', 'menuurl' => 'pq', 'modal' => 'true'];
        return view('pembelian_penawaran.index', compact('tag', 'data'));
    }

    public function create()
    {
        if (UserAkses::cek_akses('penawaran', 'cud') == false) return redirect(route('noakses'));

        $vendor = Vendor::select('id', 'nama')->get();
        $pajak = Pajak::all();
        $produk = Produk::select('id', 'nama')->get();
        $kodeubah = 1;
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Penawaran Pembelian', 'judul' => 'INPUT PENAWARAN PEMBELIAN (PQ)', 'menuurl' => 'pq', 'modal' => 'true'];

        return view('pembelian_penawaran.create', compact('vendor', 'kodeubah', 'tag', 'pajak', 'produk'));
    }

    public function show($id, $kodeubah = 2, $kode = 0)
    {
        if ($kode == 1) {
            if (UserAkses::cek_akses('penawaran', 'cud') == false) return redirect(route('noakses'));
        }
        $data = Pembelian_penawaran::find($id);
        $vendor = Vendor::select('id', 'nama')->get();
        $produk = Produk::select('id', 'nama')->get();
        $pajak = Pajak::all();
        $itemproduk = Pembelian_penawaran_produk::where('pembelian_penawaranid', $id)->get();

        $tag = ['menu' => 'Pembelian', 'submenu' => 'Data Penawaran Pembelian', 'judul' => 'PENAWARAN PEMBELIAN (PQ)', 'menuurl' => 'pq', 'modal' => 'true'];
        return view('pembelian_penawaran.create', compact('data', 'vendor', 'produk', 'itemproduk', 'tag', 'kodeubah', 'pajak'));
    }

    public function edit($id)
    {
        return $this->show($id, 3, 1);
    }

    public function update(Request $request)
    {
        if (UserAkses::cek_akses('penawaran', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id == "") {
            $id = Pembelian_penawaran::insertGetId([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'kode' => $this->buatKode('pembelian_penawaran', 'kode', 'PQ'),
                'vendorid' => $request->vendor,
                'status' => 0
            ]);
        } else {
            $data = Pembelian_penawaran::find($id);
            $data->tanggal = $this->EnglishTgl($request->tanggal);
            $data->vendorid = $request->vendor;
            $data->save();
        }

        if ($id > 0) {
            //lanjut
        } else {
            Session::flash('warning', 'Data Penawaran Pembelian gagal disimpan');
            return redirect()->back();
        }

        $jmlbaris = $request->txbaris;

        Pembelian_penawaran_produk::where('pembelian_penawaranid', $id)->delete();
        for ($i = 1; $i <= $jmlbaris; $i++) {
            $arr = array();
            $produkid = 'produk' . $i;
            $qty = 'tx2_' . $i;
            $satuan = 'tx3_' . $i;
            $harga = 'tx4_' . $i;
            $jumlah = 'tx5_' . $i;
            $arr += ['pembelian_penawaranid' => $id];
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

            $insert = Pembelian_penawaran_produk::insert($arr);
        }

        return redirect(route('show_pq', $id));
    }

    public function destroyProduk($id)
    {
        Pembelian_penawaran_produk::destroy($id);
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('penawaran', 'cud') == false) return redirect(route('noakses'));

        Pembelian_penawaran::destroy($id);
        return redirect()->back();
    }

    public function createPemesanan($id)
    {
        if (UserAkses::cek_akses('penawaran', 'cud') == false) return redirect(route('noakses'));

        $pq = Pembelian_penawaran::find($id);
        $data = (object) [
            "vendorid" => $pq->vendorid,
            "pq_id" => $id

        ];
        $vendor = Vendor::select('id', 'nama')->get();
        $pajak = Pajak::all();
        $produk = Produk::select('id', 'nama')->get();
        $itemproduk = Pembelian_penawaran_produk::where('pembelian_penawaranid', $id)->get();
        $kodeubah = 3;
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Pemesanan Pembelian (PO)', 'judul' => 'INPUT PEMESANAN PEMBELIAN (PO)', 'menuurl' => 'po', 'modal' => 'true'];

        return view('pembelian_pemesanan.create', compact('vendor', 'kodeubah', 'tag', 'pajak', 'produk', 'itemproduk', 'data'));
    }

    public function cetak($id)
    {
        if (UserAkses::cek_akses('penawaran', 'cetak') == false) return redirect(route('noakses'));

        $data = Pembelian_penawaran::join('vendor', 'vendor.id', 'pembelian_penawaran.vendorid')
            ->where('pembelian_penawaran.id', $id)
            ->select('pembelian_penawaran.*', 'vendor.nama as vendor', 'vendor.alamat', 'vendor.kota', 'vendor.phone', 'vendor.email')
            ->first();

        $pajak = Pajak::all();
        $itemproduk = Pembelian_penawaran_produk::join('produk', 'produk.id', 'pembelian_penawaran_produk.produkid')
            ->where('pembelian_penawaran_produk.pembelian_penawaranid', $id)
            ->select('pembelian_penawaran_produk.*', 'produk.nama')
            ->get();

        $koperasi = DB::table('koperasi')->first();
        $pdf = PDF::loadview('pembelian_penawaran.cetak', ['data' => $data,  'itemproduk' => $itemproduk, 'koperasi' => $koperasi])->setPaper('A4', 'potrait');
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
