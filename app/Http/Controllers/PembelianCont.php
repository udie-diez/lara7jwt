<?php

namespace App\Http\Controllers;

use App\Akun;
use App\Helpers\MyHelpers;
use App\Helpers\UserAkses;
use App\Mapping;
use App\Pajak;
use App\Pembelian;
use App\Pembelian_pembayaran;
use App\Pembelian_pemesanan;
use App\Pembelian_produk;
use App\Pengelola;
use App\Perusahaan;
use App\Produk;
use App\Project;
use App\Transaksi;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PembelianCont extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (UserAkses::cek_akses('pembelian', 'lihat') == false) return redirect(route('noakses'));

        // $data = Pembelian::join('vendor', 'vendor.id', 'pembelian.vendorid')
        //     ->select('pembelian.*', 'vendor.nama as vendor')
        //     ->selectRaw('(select sum(jumlah+pajak) from pembelian_produk where pembelianid = pembelian.id group by pembelianid) as total')
        //     ->get();

        $data = DB::select('SELECT p.*, perusahaan.alias as perusahaan, perusahaan.unitkerja ,
        (select sum(ifnull(jumlah,0) + ifnull(pajak,0)) from pembelian_produk where pembelianid = pb.id group by pembelianid) as total 
        from project p inner join perusahaan on p.perusahaanid = perusahaan.id left join pembelian pb on pb.po_id = p.id  order by p.id DESC');

        $perusahaan = Perusahaan::all();

        $tag = ['menu' => 'Pembelian', 'submenu' => 'Daftar Pembelian', 'judul' => 'DAFTAR PEMBELIAN', 'menuurl' => 'pembelian', 'modal' => 'true'];
        return view('pembelian.index', compact('tag', 'data', 'perusahaan'));
    }

    public function create($id)
    {
        if (UserAkses::cek_akses('pembelian', 'cud') == false) return redirect(route('noakses'));

        $pembelian = Pembelian::where('po_id', $id)->first();

        if ($pembelian) {
            return redirect(route('showPembelian', $pembelian->id));
        }

        $data = Project::join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->where('project.id', $id)
            ->select('project.*', 'perusahaan.nama as perusahaan')
            ->first();

        // $vendor = Vendor::select('id', 'nama')->get();
        $pajak = Pajak::all();
        $produk = Produk::select('id', 'nama')->get();
        $vendor = Vendor::select('id', 'nama' ,'alias')->get();
        $kodeubah = 1;
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Input Pembelian', 'judul' => 'INPUT PEMBELIAN', 'menuurl' => 'pembelian', 'modal' => 'true'];

        return view('pembelian.create', compact('data',   'kodeubah', 'tag', 'pajak', 'produk', 'vendor'));
    }

    public function show($id, $kodeubah = 2, $kode = 0)
    {
        if ($kode == 1) {
            if (UserAkses::cek_akses('pembelian', 'cud') == false) return redirect(route('noakses'));
        }

        $data = Project::join('perusahaan', 'project.perusahaanid', 'perusahaan.id')->join('pembelian', 'pembelian.po_id', 'project.id')
            ->where('pembelian.id', $id)
            ->select('project.*', 'perusahaan.nama as perusahaan', 'pembelian.id as pembelianid','pembelian.status as statuspembelian','pembelian.file')
            ->first();
        // $vendor = Vendor::select('id', 'nama')->get();
        $produk = Produk::select('id', 'nama')->get();
        $vendor = Vendor::select('id', 'nama', 'alias')->get();

        $pajak = Pajak::all();
        $itemproduk = Pembelian_produk::where('pembelianid', $id)->get();
        $pembayaran = Pembelian_pembayaran::join('pembelian', 'pembelian.id', 'pembelian_pembayaran.pembelianid')
            ->select('pembelian_pembayaran.*', 'pembelian.kode as kodepembelian')
            ->where('pembelianid', $id)
            ->get();
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Data Pembelian', 'judul' => 'PEMBELIAN', 'menuurl' => 'pembelian', 'modal' => 'true'];
        return view('pembelian.create', compact('data', 'vendor','produk', 'itemproduk', 'tag', 'kodeubah', 'pajak', 'pembayaran'));
    }

    public function edit($id)
    {
        return $this->show($id, 3, 1);
    }

    public function update(Request $request)
    {

        if (UserAkses::cek_akses('pembelian', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id == "") {
            $id = Pembelian::create([
                'kode' => $this->buatKode('pembelian', 'kode', 'PB'),
                'po_id' => $request->txprojectid,
                'status' => 0
                // 'tanggal' => $this->EnglishTgl($request->tanggal),
                // 'tanggal_jt' => $this->EnglishTgl($request->tanggal_jt),
                // 'vendorid' => $request->vendor,
                // 'nomor' => $request->referensi,
            ]);
            $id = $id->id;
        } else {
            $data = Pembelian::find($id);
            // $data->tanggal = $this->EnglishTgl($request->tanggal);
            // $data->tanggal_jt = $this->EnglishTgl($request->tanggal_jt);
            // $data->vendorid = $request->vendor;
            // $data->nomor = $request->referensi;
            // $data->po_id = $request->txprojectid;
            $data->save();
        }

        if ($id > 0) {
            //lanjut
        } else {
            Session::flash('warning', 'Data Pembelian gagal disimpan');
            return redirect()->back();
        }

        $jmlbaris = $request->txbaris;

        Pembelian_produk::where('pembelianid', $id)->delete();

        for ($i = 1; $i <= $jmlbaris; $i++) {
            $arr = array();
            $produkid = 'produk' . $i;
            $vendorid = 'vendor' . $i;
            if ($request->$produkid) {
                $qty = 'tx2_' . $i;
                $satuan = 'tx3_' . $i;
                $harga = 'tx4_' . $i;
                $jumlah = 'tx5_' . $i;
                $arr += ['pembelianid' => $id];
                $arr += ['vendorid' => $request->$vendorid];
                $arr += ['produkid' => $request->$produkid];
                $arr += ['qty' => $this->Angkapolos($request->$qty)];
                $arr += ['satuan' => $request->$satuan];
                $arr += ['harga' => $this->Angkapolos($request->$harga)];
                $arr += ['jumlah' => $this->Angkapolos($request->$jumlah)];

                $pajakid = 'pajak' . ($i);
                if ($request->$pajakid) {   //cek apa ada pajak ?

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
                }
            }

            $insert = Pembelian_produk::create($arr);
        }

        if ($request->po_id > 0) {
            $pp = Pembelian_pemesanan::find($request->po_id);
            $pp->update(['status' => 1]);
        }

        return redirect(route('showPembelian', $id));
    }

    public function destroyProduk($id)
    {
        Pembelian_produk::destroy($id);
        return redirect()->back();
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('pembelian', 'cud') == false) return redirect(route('noakses'));

        Pembelian::destroy($id);
        return redirect()->back();
    }


    public function createPembayaran($id)
    {
        if (UserAkses::cek_akses('pembelian', 'cud') == false) return redirect(route('noakses'));

        // $data = Pembelian::join('vendor', 'vendor.id', 'pembelian.vendorid')->where('pembelian.id', $id)
        //     ->select('pembelian.*', 'vendor.nama as vendor')
        //     ->selectRaw('(select sum(jumlah) from pembelian_produk where pembelianid = pembelian.id) as total')
        //     ->first();

        $data = Pembelian::where('pembelian.id', $id)
            ->select('pembelian.*')
            ->selectRaw('(select sum(jumlah) from pembelian_produk where pembelianid = pembelian.id) as total')
            ->first();

        if ($data->status == 1) {
            Session::flash('warning', 'Invoice Pembelian ini sudah Lunas');
            return redirect()->back();
        }
        $akun = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();

        $kodeubah = 1;
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Pembayaran', 'judul' => 'PEMBAYARAN', 'menuurl' => 'pembelian', 'modal' => 'true'];
        return view('pembelian.pembayaran', compact('tag', 'data', 'kodeubah', 'akun'));
    }

    public function updatePembayaran(Request $request)
    {
        if (UserAkses::cek_akses('pembelian', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id == '') {
            $id = Pembelian_pembayaran::create([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'kode' => $this->buatKode('pembelian_pembayaran', 'kode', 'PBY'),
                'pembelianid' => $request->pembelianid,
                'akunid' => $request->akunid,
                'catatan' => $request->catatan,
                'cara' => $request->cara,
                'nilai' => $this->Angkapolos($request->td_nilai)
            ]);

            $id = $id->id;

        } else {
            $data = Pembelian_pembayaran::find($id);
            $data->tanggal = $this->EnglishTgl($request->tanggal);
            $data->kode = $this->buatKode('pembelian_pembayaran', 'kode', 'PBY');
            $data->pembelianid = $request->pembelianid;
            $data->akunid = $request->akunid;
            $data->catatan = $request->catatan;
            $data->cara =  $request->cara;
            $data->nilai = $this->Angkapolos($request->td_nilai);
            $data->save();
        }
        if ($id > 0) {
            //jurnal
            $akunbayar = $request->akunid;
            $akunbarang = Mapping::where('jenis', 'pembelian_barang')->first()->akunid;
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal), 'pembayaranpembelian', $id, $akunbayar, 0, $this->Angkapolos($request->td_nilai), 1);
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal), 'pembayaranpembelian', $id, $akunbarang, $this->Angkapolos($request->td_nilai), 0);

            $ceklunas = Pembelian::where('pembelian.id', $request->pembelianid)
                ->select('pembelian.id')
                ->selectRaw('(select sum(jumlah) from pembelian_produk where pembelianid = pembelian.id) as totalpembelian')
                ->selectRaw('(select sum(nilai) from pembelian_pembayaran where pembelianid = pembelian.id) as totalpembayaran')
                ->first();
            if ($ceklunas->totalpembelian <= $ceklunas->totalpembayaran) {
                $p = Pembelian::find($request->pembelianid);
                $p->update(['status' => 1]);
            } else if ($ceklunas->totalpembayaran == 0) {
                $p = Pembelian::find($request->pembelianid);
                $p->update(['status' => 0]);
            } else if ($ceklunas->totalpembelian > $ceklunas->totalpembayaran) {
                $p = Pembelian::find($request->pembelianid);
                $p->update(['status' => 2]);
            }
            Session::flash('sukses', ' Data Pembayaran berhasil disimpan.');
        } else {
            Session::flash('warning', ' Data Pembayaran gagal disimpan.');
        }

        return $this->showPembayaran($id);
    }

    public function showPembayaran($id, $kodeubah = 3)
    {
        if (UserAkses::cek_akses('pembelian', 'cud') == false) return redirect(route('noakses'));

        $pembayaran = Pembelian_pembayaran::join('pembelian', 'pembelian.id', 'pembelian_pembayaran.pembelianid')
            ->select('pembelian_pembayaran.*')
            ->where('pembelian_pembayaran.id', $id)
            ->first();
        $akun = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();

        $data = Pembelian::where('pembelian.id', $pembayaran->pembelianid)
            ->select('pembelian.*')
            ->selectRaw('(select sum(jumlah) from pembelian_produk where pembelianid = pembelian.id) as total')
            ->first();
        $tag = ['menu' => 'Pembelian', 'submenu' => 'Pembayaran', 'judul' => 'DATA PEMBAYARAN', 'menuurl' => 'pembelian', 'modal' => 'true'];
        return view('pembelian.pembayaran', compact('tag', 'pembayaran', 'kodeubah', 'data', 'akun'));
    }

    public function editPembayaran($id)
    {

        return $this->showPembayaran($id, 2);
    }

    public function destroyPembayaran($id)
    {
        $ceklunas = Pembelian::join('pembelian_pembayaran','pembelian.id','pembelian_pembayaran.pembelianid')->where('pembelian_pembayaran.id', $id)
        ->select('pembelian.id')
        ->selectRaw('(select sum(jumlah) from pembelian_produk where pembelianid = pembelian.id) as totalpembelian')
        ->selectRaw('(select sum(nilai) from pembelian_pembayaran where pembelianid = pembelian.id) as totalpembayaran')
        ->first();
        dd($ceklunas);
    if ($ceklunas->totalpembelian <= $ceklunas->totalpembayaran) {
        Pembelian::where('id', $ceklunas->id)->update(['status' => 1]);
    } else if ($ceklunas->totalpembayaran == 0) {
        Pembelian::where('id', $ceklunas->id)->update(['status' => 0]);
    } else if ($ceklunas->totalpembelian > $ceklunas->totalpembayaran) {
        Pembelian::where('id', $ceklunas->id)->update(['status' => 2]);
    }
        
    Pembelian_pembayaran::destroy($id);
    Transaksi::where('item', 'pembayaranpembelian')->where('itemid', $id)->delete();

        return redirect()->back();
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
