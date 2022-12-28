<?php

namespace App\Http\Controllers;

use App\Akun;
use App\Biaya;
use App\Helpers\UserAkses;
use App\Invoice;
use App\Jurnalumum;
use App\Kirim_uang;
use App\Kirim_uang_detail;
use App\Lampiran;
use App\Pajak;
use App\Pembayaran;
use App\Pembelian_pembayaran;
use App\Saldoawal;
use App\Terima_uang;
use App\Terima_uang_detail;
use App\Transaksi;
use App\Transfer;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class KeuanganCont extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return redirect()->back();
    }

    public function saldoawal()
    {
        if (UserAkses::cek_akses('saldo_awal', 'lihat') == false) return redirect(route('noakses'));

        // $data = Akun::orderBy('kode')->get();
        $data = Akun::leftjoin('saldoawal', 'akun.id', 'saldoawal.akunid')
            ->select('akun.*', 'saldoawal.debit', 'saldoawal.kredit')
            ->orderBy('akun.kode')
            ->get();

        $tag = ['menu' => 'Keuangan', 'submenu' => 'Saldo Awal', 'judul' => 'Saldo Awal', 'menuurl' => 'saldoawal', 'modal' => 'true'];
        return view('keuangan.saldoawal', compact('tag', 'data'));
    }

    public function saldoawalupdate(Request $request)
    {
        if (UserAkses::cek_akses('saldo_awal', 'cud') == false) return redirect(route('noakses'));

        $maxid = $request->maxid;
        $tgl = "2021-7-1";
        Saldoawal::where('tanggal', $tgl)->delete();
        Transaksi::where('item', 'saldoawal')->where('tanggal', $tgl)->delete();

        for ($i = 1; $i <= $maxid; $i++) {
            $akundebet = "d_" . $i;
            $akunkredit = "k_" . $i;

            if ($request->$akundebet > 0 || $request->$akunkredit > 0) {
                $id = Saldoawal::create([
                    'akunid' => $i,
                    'tanggal' => $tgl,
                    'debit' => $this->cekNum($request->$akundebet),
                    'kredit' => $this->cekNum($request->$akunkredit)
                ]);
                $id = $id->id;
                if ($id > 0) {
                    $transaksi = Transaksi::insert([
                        'tanggal' => $tgl,
                        'item' => 'saldoawal',
                        'itemid' => $id,
                        'akunid' => $i,
                        'debit' => $this->cekNum($request->$akundebet),
                        'kredit' => $this->cekNum($request->$akunkredit)
                    ]);
                }
            }
        }
        return redirect()->back();
    }

    public function showJurnalBiaya($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'biaya')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('transaksi.kredit, transaksi.debit, akun.kode')
            ->get();

        $nomor = Biaya::find($id)->nomor;
        $jenis = 'Biaya';
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }

    public function showJurnalJurnal($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'jurnal')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('transaksi.kredit, transaksi.debit, akun.kode')
            ->get();

        $nomor = Jurnalumum::find($id)->nomor;
        $jenis = 'Jurnal';
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }

    public function showJurnalTerimauang($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'terimauang')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('transaksi.kredit, transaksi.debit, akun.kode')
            ->get();

        $jenis = 'TerimaUang';
        $nomor = Terima_uang::find($id)->nomor;
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }
    public function showJurnalKirimuang($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'kirimuang')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('transaksi.kredit, transaksi.debit, akun.kode')
            ->get();

        $jenis = 'KirimUang';
        $nomor = Kirim_uang::find($id)->nomor;
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }
   
    public function showJurnalTransfer($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'transfer')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('transaksi.kredit, transaksi.debit, akun.kode')
            ->get();

        $jenis = 'Transfer';
        $nomor = Terima_uang::find($id)->nomor;
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }

    public function showJurnalInvoice($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'penjualan')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('akun.kode,transaksi.kredit, transaksi.debit')
            ->get();

        $jenis = 'Invoice ';
        $nomor = Invoice::find($id)->nomor;
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }

    public function showJurnalPembayaran($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'pembayaran')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('akun.kode,transaksi.kredit, transaksi.debit')
            ->get();

        $jenis = 'Pembayaran ';
        $nomor = Pembayaran::find($id)->nomor;
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }
    public function showJurnalPembayaranPembelian($id)
    {
        $data = Transaksi::leftjoin('akun', 'akun.id', 'transaksi.akunid')
            ->where('transaksi.item', 'pembayaranpembelian')
            ->where('transaksi.itemid', $id)
            ->select('transaksi.*', 'akun.nama', 'akun.kode')
            ->orderByRaw('akun.kode,transaksi.kredit, transaksi.debit')
            ->get();

        $jenis = 'Pembayaran ';
        $nomor = Pembelian_pembayaran::find($id)->kode;
        return view('keuangan.jurnal', compact('data', 'nomor', 'jenis'));
    }
    
    public function laporanPajak()
    {
        if (UserAkses::cek_akses('daftar_pajak', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('daftar_pajak', 'cetak');

        $data = Pajak::leftjoin('akun as a', 'pajak.akuninid', 'a.id')->leftjoin('akun as b', 'pajak.akunoutid', 'b.id')
            ->select('pajak.*', 'a.kode as kodein', 'a.nama as namain', 'b.nama as namaout', 'b.kode as kodeout')
            ->get();

        $tag = ['menu' => 'Laporan', 'submenu' => 'Pajak', 'judul' => 'Laporan Pajak', 'menuurl' => 'pajak', 'modal' => 'true'];
        return view('keuangan.pajak', compact('tag', 'data', 'cetak'));
    }


    public function laporanJurnalCreate()
    {
        
        if (UserAkses::cek_akses('jurnal', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('jurnal', 'cetak');

        $tag = ['menu' => 'Laporan', 'submenu' => 'Jurnal', 'judul' => 'Laporan Jurnal', 'menuurl' => 'index', 'modal' => 'true'];
        return view('keuangan.laporan_jurnal', compact('tag', 'cetak'));
    }

    public function laporanJurnal(Request $request)
    {
        if (UserAkses::cek_akses('jurnal', 'lihat') == false) return redirect(route('noakses'));

        $cetak = UserAkses::cek_akses('jurnal', 'cetak');

        $tgl = $request->tglFilter;
        $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
        $tgl2 =  $this->EnglishTgl(substr($tgl, -10));

        $order = $request->f_order;
        $data = Transaksi::join('akun', 'akun.id', 'transaksi.akunid')
            ->whereBetween('tanggal', array($tgl1, $tgl2))
            ->select('transaksi.*', 'akun.nama', 'akun.kode', DB::raw('concat(item,itemid) as kodeitem'))
            ->orderByRaw('tanggal ' . $order . ', item,itemid,id,kredit, debit, akun.kode')
            ->get();
        $tag = ['menu' => 'Laporan', 'submenu' => 'Jurnal', 'judul' => 'Laporan Jurnal', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_jurnal', compact('data', 'tag', 'tgl', 'order', 'cetak'));
    }

    public function laporanBukubesarCreate()
    {
        if (UserAkses::cek_akses('buku_besar', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('buku_besar', 'cetak');

        $tag = ['menu' => 'Laporan', 'submenu' => 'Buku Besar', 'judul' => 'Laporan Buku Besar', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_bukubesar', compact('tag', 'cetak'));
    }

    public function laporanBukubesar(Request $request)
    {
        if (UserAkses::cek_akses('buku_besar', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('buku_besar', 'cetak');

        $tgl = $request->tglFilter;
        $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
        $tgl2 =  $this->EnglishTgl(substr($tgl, -10));
        $tglmulai = '2021/1/1';

        $tgl11 = new DateTime($tgl1);
        $tgl1_1 = $tgl11->modify('-1 day');
        $tgl_str = DATE_FORMAT($tgl1_1, 'd-m-Y');
        $datasaldo = Transaksi::join('akun', 'akun.id', 'transaksi.akunid')
            ->whereBetween('tanggal', array($tglmulai, $tgl1_1))
            ->groupby('transaksi.akunid')
            ->select('akun.id',  DB::raw('SUM(IFNULL(debit,0)) as debit'), DB::raw('SUM(IFNULL(kredit,0)) as kredit'), DB::raw('(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0))) as saldo'))
            ->get();
        $data = DB::select("SELECT `akun`.*,`transaksi`.`tanggal`, `transaksi`.`debit`, `transaksi`.`kredit`,`transaksi`.`item`,`transaksi`.`itemid`, 
                (select kode from saldoawal where id = transaksi.itemid and transaksi.item = 'saldoawal') as nosaldoawal,
                (select nomor from biaya where id = transaksi.itemid and transaksi.item = 'biaya') as nobiaya,
                (select akun.nama from biaya_detail bd inner join akun on bd.akunid = akun.id where bd.biayaid = transaksi.itemid and transaksi.item = 'biaya' limit 0,1) as namabiaya,  
                (select kode from transfer where id = transaksi.itemid and transaksi.item = 'transfer') as notransfer,
                (select akun.nama from transfer t inner join akun on t.akunsumberid = akun.id where t.id = transaksi.itemid and transaksi.item = 'transfer' limit 0,1) as namatransfer,
                (select nomor from terimauang where id = transaksi.itemid and transaksi.item = 'terimauang') as noterimauang,
                (select akun.nama from terimauang_detail bd inner join akun on bd.akunid = akun.id where bd.terimauangid = transaksi.itemid and transaksi.item = 'terimauang' limit 0,1) as namaterimauang,
                (select nomor from kirimuang where id = transaksi.itemid and transaksi.item = 'kirimuang') as nokirimuang,
                (select nomor from setoran where id = transaksi.itemid and transaksi.item = 'setoran_WAJIB') as nosetoranwajib,
                (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'setoran_WAJIB' limit 0,1) as namasetoranwajib,
                (select nomor from setoran where id = transaksi.itemid and transaksi.item = 'setoran_POKOK') as nosetoranpokok,
                 (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'setoran_POKOK' limit 0,1) as namasetoranpokok,
                 (select id from bayar_angsuran where id = transaksi.itemid and transaksi.item = 'angsuran_pinjaman') as noangsuranpinjaman,
        (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'angsuran_pinjaman' limit 0,1) as namaangsuranpinjaman,
        (select id from pelunasan where id = transaksi.itemid and transaksi.item = 'pelunasan_pinjaman') as nopelunasan,
        (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'pelunasan_pinjaman' limit 0,1) as namapelunasan,
        (select nomor from invoice where id = transaksi.itemid and transaksi.item = 'penjualan') as nopenjualan,
        (select akun.nama from akun where akun.id = transaksi.akunid and transaksi.item = 'penjualan' limit 0,1) as namapenjualan,

        (select nomor from pembayaran where id = transaksi.itemid and transaksi.item = 'pembayaran') as nopembayaran,
        (select akun.nama from akun where akun.id = transaksi.akunid and transaksi.item = 'pembayaran' limit 0,1) as namapembayaran,

        (select akun.nama from kirimuang_detail bd inner join akun on bd.akunid = akun.id where bd.kirimuangid = transaksi.itemid and transaksi.item = 'kirimuang' limit 0,1) as namakirimuang,
                (select nomor from jurnalumum where id = transaksi.itemid and transaksi.item = 'jurnal') as nojurnalumum,
        (select akun.nama from jurnalumum_detail bd inner join akun on bd.akunid = akun.id where bd.jurnalumumid = transaksi.itemid and transaksi.item = 'jurnal' limit 0,1) as namajurnalumum
                from `akun` inner join `transaksi` on `transaksi`.`akunid` = `akun`.`id` where tanggal between '" . $tgl1 . "' and '" . $tgl2 . "' order by akun.kode, transaksi.tanggal, transaksi.created_at");
        $tag = ['menu' => 'Laporan', 'submenu' => 'Buku Besar', 'judul' => 'Laporan Buku Besar', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_bukubesar', compact('tag', 'data', 'datasaldo', 'tgl_str', 'tgl','cetak'));
    }

    public function laporanNeracaCreate()
    {
        if (UserAkses::cek_akses('neraca', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('neraca', 'cetak');

        $tag = ['menu' => 'Laporan', 'submenu' => 'Neraca', 'judul' => 'Laporan Neraca', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_neraca', compact('tag', 'cetak'));
    }

    public function laporanNeraca(Request $request)
    {
        if (UserAkses::cek_akses('neraca', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('neraca', 'cetak');

        $tanggal = $request->tanggal;
        $tglmulai = '2021/1/1';

        $data = Akun::leftjoin('transaksi', 'akun.id', 'transaksi.akunid')
            ->select('akun.id', 'akun.kode', 'akun.nama', 'akun.jenis', DB::raw("(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0)) from transaksi where akunid = akun.id and tanggal between '" . $tglmulai . "' and '" . $this->EnglishTgl($tanggal) . "' group by akunid) as saldo"))
            ->groupby('akun.id')
            ->get();
        $tag = ['menu' => 'Laporan', 'submenu' => 'Neraca', 'judul' => 'Laporan Neraca', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_neraca', compact('tag', 'data', 'tanggal','cetak'));
    }

    public function laporanLabarugiCreate()
    {
        if (UserAkses::cek_akses('laba_rugi', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('laba_rugi', 'cetak');

        $tag = ['menu' => 'Laporan', 'submenu' => 'Laba Rugi', 'judul' => 'Laporan Laba Rugi', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_labarugi', compact('tag','cetak'));
    }

    public function laporanLabarugi(Request $request)
    {
        if (UserAkses::cek_akses('laba_rugi', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('laba_rugi', 'cetak');

        $tgl = $request->tglFilter;
        $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
        $tgl2 =  $this->EnglishTgl(substr($tgl, -10));
        $tglmulai = '2021/1/1';
        $tgl11 = new DateTime($tgl1);
        $tgl1_1 = $tgl11->modify('-1 day');
        $tgl_str = DATE_FORMAT($tgl1_1, 'd-m-Y');

        $datasaldo = Transaksi::join('akun', 'akun.id', 'transaksi.akunid')
            ->where(DB::raw('MID(akun.kode,1,1)'), '>=', 4)
            ->whereBetween('tanggal', array($tglmulai, $tgl1_1))
            ->groupby('transaksi.akunid')
            ->select('akun.id', 'akun.kode', DB::raw('(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0))) as saldo'))
            ->get();

        $data = Akun::leftjoin('transaksi', 'akun.id', 'transaksi.akunid')
            ->select('akun.id', 'akun.kode', 'akun.nama', 'akun.jenis', DB::raw("(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0)) from transaksi where akunid = akun.id and tanggal between '" . $tgl1 . "' and '" . $tgl2 . "' group by akunid) as saldo"))
            ->where(DB::raw('MID(akun.kode,1,1)'), '>=', 4)
            ->groupby('akun.id')
            ->get();

        $tag = ['menu' => 'Laporan', 'submenu' => 'Laba Rugi', 'judul' => 'Laporan Laba Rugi', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_labarugi', compact('tag', 'data', 'tgl', 'tgl_str', 'datasaldo', 'cetak'));
    }

    public function laporanPerubahanmodal(Request $request)
    {
        if (UserAkses::cek_akses('perubahan_modal', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('perubahan_modal', 'cetak');

        $tgl = $request->tglFilter;
        $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
        $tgl2 =  $this->EnglishTgl(substr($tgl, -10));
        $tglmulai = '2021/1/1';
        $tgl11 = new DateTime($tgl1);
        $tgl1_1 = $tgl11->modify('-1 day');
        $tgl_str = DATE_FORMAT($tgl1_1, 'd-m-Y');

        $datasaldo = Transaksi::join('akun', 'akun.id', 'transaksi.akunid')
            ->where(DB::raw('MID(akun.kode,1,1)'), '>=', 3)
            ->whereBetween('tanggal', array($tglmulai, $tgl1_1))
            ->groupby('transaksi.akunid')
            ->select('akun.id', 'akun.kode', DB::raw('(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0))) as saldo'))
            ->get();

        $data = Akun::leftjoin('transaksi', 'akun.id', 'transaksi.akunid')
            ->select('akun.id', 'akun.kode', 'akun.nama', 'akun.jenis', DB::raw("(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0)) from transaksi where akunid = akun.id and tanggal between '" . $tgl1 . "' and '" . $tgl2 . "' group by akunid) as saldo"))
            ->where(DB::raw('MID(akun.kode,1,1)'), '>=', 3)
            ->groupby('akun.id')
            ->get();

        $tag = ['menu' => 'Laporan', 'submenu' => 'Laba Rugi', 'judul' => 'Laporan Laba Rugi', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_perubahanmodal', compact('tag', 'data', 'tgl', 'tgl_str', 'datasaldo', 'cetak'));
    }


    public function laporanPerubahanmodalCreate()
    {
        if (UserAkses::cek_akses('perubahan_modal', 'lihat') == false) return redirect(route('noakses'));
        $cetak = UserAkses::cek_akses('perubahan_modal', 'cetak');

        $tag = ['menu' => 'Laporan', 'submenu' => 'Perubahan Modal', 'judul' => 'Laporan Perubahan Modal', 'menuurl' => 'index', 'modal' => 'true'];

        return view('keuangan.laporan_perubahanmodal', compact('tag','cetak'));
    }

    public function saldokas()
    {
        if (UserAkses::cek_akses('kas_bank', 'lihat') == false) return redirect(route('noakses'));

        $data = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)
            ->select('akun.*', DB::raw('(select SUM(IFNULL(debit,0)) - SUM(IFNULL(kredit,0)) from transaksi where akunid = akun.id group by akunid) as saldo'))
            ->get();

        $tag = ['menu' => 'Kas / Bank', 'submenu' => 'Saldo', 'judul' => 'SALDO KAS/BANK', 'menuurl' => 'saldoKas', 'modal' => 'true'];
        return view('kas.index', compact('tag', 'data'));
    }

    public function transferkas($id)
    {
        $akun = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();
        return view('kas.transfer', compact('akun', 'id'));
    }

    public function showtransferkas($id)
    {
        $data = Transfer::find($id);
        $akun = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();

        return view('kas.transfer', compact('akun', 'id', 'data'));
    }

    public function destroytransferkas($id)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        Transfer::destroy($id);
        Transaksi::where('item', 'transfer')->where('itemid', $id)->delete();

        return redirect()->back();
    }

    public function transferkasupdate(Request $request)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id) {
            $trans = Transfer::find($id);
            $trans->akunsumberid = $request->akunsumberid;
            $trans->akuntujuanid = $request->akuntujuanid;
            $trans->catatan = $request->catatan;
            $trans->nilai = $this->cekNum($request->nilai);
            $trans->tanggal = $this->EnglishTgl($request->tanggal);
            $trans->save();
        } else {
            $id = Transfer::create([
                'akunsumberid' => $request->akunsumberid,
                'akuntujuanid' => $request->akuntujuanid,
                'catatan' => $request->catatan,
                'nilai' => $this->cekNum($request->nilai),
                'kode' => $this->buatKode('transfer', 'kode', 'TRF'),
                'tanggal' => $this->EnglishTgl($request->tanggal)
            ]);
            $id = $id->id;
        }

        Transaksi::where('itemid', $id)->where('item', 'transfer')->delete();
        $transaksi = Transaksi::insert([
            'tanggal' => $this->EnglishTgl($request->tanggal),
            'item' => 'transfer',
            'itemid' => $id,
            'akunid' => $request->akuntujuanid,
            'debit' => $this->cekNum($request->nilai)
        ]);
        $transaksi = Transaksi::insert([
            'tanggal' => $this->EnglishTgl($request->tanggal),
            'item' => 'transfer',
            'itemid' => $id,
            'akunid' => $request->akunsumberid,
            'kredit' => $this->cekNum($request->nilai)
        ]);

        return redirect()->back();
    }

    public function detailAkun($id)
    {
        // $data = Akun::join('transaksi', 'transaksi.akunid', 'akun.id')
        //     ->select('akun.*', 'transaksi.tanggal', 'transaksi.debit', 'transaksi.kredit', DB::raw("(select nomor from biaya where id = transaksi.itemid and transaksi.item = 'biaya') as nobiaya"),  DB::raw("(select kode from transfer where id = transaksi.itemid and transaksi.item = 'transfer') as notransfer"))
        //     ->where('akun.id', $id)
        //     ->get();


        $data = DB::select("SELECT `akun`.*, `transaksi`.`tanggal`, `transaksi`.`debit`, `transaksi`.`kredit`,`transaksi`.`item`,`transaksi`.`itemid`, 
        (select kode from saldoawal where id = transaksi.itemid and transaksi.item = 'saldoawal') as nosaldoawal,
        (select nomor from biaya where id = transaksi.itemid and transaksi.item = 'biaya') as nobiaya,
        (select akun.nama from biaya_detail bd inner join akun on bd.akunid = akun.id where bd.biayaid = transaksi.itemid and transaksi.item = 'biaya' limit 0,1) as namabiaya,  
        (select kode from transfer where id = transaksi.itemid and transaksi.item = 'transfer') as notransfer,
        (select akun.nama from transfer t inner join akun on t.akunsumberid = akun.id where t.id = transaksi.itemid and transaksi.item = 'transfer' limit 0,1) as namatransfer,
        (select nomor from terimauang where id = transaksi.itemid and transaksi.item = 'terimauang') as noterimauang,
        (select akun.nama from terimauang_detail bd inner join akun on bd.akunid = akun.id where bd.terimauangid = transaksi.itemid and transaksi.item = 'terimauang' limit 0,1) as namaterimauang,

        (select nomor from kirimuang where id = transaksi.itemid and transaksi.item = 'kirimuang') as nokirimuang,
        (select akun.nama from kirimuang_detail bd inner join akun on bd.akunid = akun.id where bd.kirimuangid = transaksi.itemid and transaksi.item = 'kirimuang' limit 0,1) as namakirimuang,

        (select nomor from setoran where id = transaksi.itemid and transaksi.item = 'setoran_WAJIB') as nosetoranwajib,
        (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'setoran_WAJIB' limit 0,1) as namasetoranwajib,

        (select nomor from setoran where id = transaksi.itemid and transaksi.item = 'setoran_POKOK') as nosetoranpokok,
        (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'setoran_POKOK' limit 0,1) as namasetoranpokok,

        (select id from bayar_angsuran where id = transaksi.itemid and transaksi.item = 'angsuran_pinjaman') as noangsuranpinjaman,
        (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'angsuran_pinjaman' limit 0,1) as namaangsuranpinjaman,

        (select id from pelunasan where id = transaksi.itemid and transaksi.item = 'pelunasan_pinjaman') as nopelunasan,
        (select akun.nama from akun  where akun.id = transaksi.akunid and transaksi.item = 'pelunasan_pinjaman' limit 0,1) as namapelunasan,

        (select nomor from invoice where id = transaksi.itemid and transaksi.item = 'penjualan') as nopenjualan,
        (select akun.nama from akun where akun.id = transaksi.akunid and transaksi.item = 'penjualan' limit 0,1) as namapenjualan,

        (select nomor from pembayaran where id = transaksi.itemid and transaksi.item = 'pembayaran') as nopembayaran,
        (select akun.nama from akun where akun.id = transaksi.akunid and transaksi.item = 'pembayaran' limit 0,1) as namapembayaran,


        (select nomor from jurnalumum where id = transaksi.itemid and transaksi.item = 'jurnal') as nojurnalumum,
        (select akun.nama from jurnalumum_detail bd inner join akun on bd.akunid = akun.id where bd.jurnalumumid = transaksi.itemid and transaksi.item = 'jurnal' limit 0,1) as namajurnalumum
        from `akun` inner join `transaksi` on `transaksi`.`akunid` = `akun`.`id` where `akun`.`id` = " . $this->cekNum($id) . " order by transaksi.tanggal,transaksi.id, transaksi.created_at;
        ");

        $akun = Akun::find($this->cekNum($id));
        if ($akun) {

            $akun = 'Akun : (' . $akun->kode . ') - ' . $akun->nama;
        }

        $tag = ['menu' => 'Akun', 'submenu' => 'Transaksi Akun', 'judul' => $akun, 'menuurl' => 'akun', 'modal' => 'true'];
        return view('keuangan.detail_akun', compact('tag', 'data'));
    }

    public function kirimuang($id)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        Session::flash('backUrl', request()->headers->get('referer'));
        $akunx = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();
        $akun = Akun::where('jenis', 1)->get();
        $pajak = Pajak::all();

        $kodeubah = 1;
        $tag = ['menu' => 'Kas / Bank', 'submenu' => 'Kirim Uang', 'judul' => 'KIRIM UANG', 'menuurl' => 'saldoKas', 'modal' => 'true'];

        return view('kas.kirim_uang', compact('kodeubah', 'tag', 'akun', 'pajak', 'id', 'akunx'));
    }

    public function destroyKirimuang($id)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        Kirim_uang::destroy($id);
        Kirim_uang_detail::where('kirimuangid', $id)->delete();
        Transaksi::where('item', 'kirimuang')->where('itemid', $id)->delete();
        return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::route('saldoKas');

        // return redirect()->back();

    }

    public function destroyTerimauang($id)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        Terima_uang::destroy($id);
        Terima_uang_detail::where('terimauangid', $id)->delete();
        Transaksi::where('item', 'terimauang')->where('itemid', $id)->delete();
        return ($url = Session::get('backUrl')) ? Redirect::to($url) : Redirect::route('saldoKas');
    }

    public function terimauang($id)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        $akunx = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();
        $akun = Akun::where('jenis', 1)->get();
        $pajak = Pajak::all();

        $kodeubah = 1;
        $tag = ['menu' => 'Kas / Bank', 'submenu' => 'Terima Uang', 'judul' => 'TERIMA UANG', 'menuurl' => 'saldoKas', 'modal' => 'true'];

        return view('kas.terima_uang', compact('kodeubah', 'tag', 'akun', 'pajak', 'id', 'akunx'));
    }

    public function kirimuangupdate(Request $request)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id == "") {
            $id = Kirim_uang::create([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'nomor' => $this->buatKode('kirimuang', 'nomor', 'KU'),
                'catatan' => $request->catatan,
                'untuk' => $request->untuk,
                'akunsumberid' => $request->akunsumberid,
                'kodepajak' => $request->ck_pajak,
                'status' => 0
            ]);
            $id = $id->id;
        } else {
            $data = Kirim_uang::find($id);
            $data->tanggal = $this->EnglishTgl($request->tanggal);
            $data->catatan = $request->catatan;
            $data->untuk = $request->untuk;
            $data->akunsumberid = $request->akunsumberid;
            $data->kodepajak = $request->ck_pajak;
            $data->save();
        }

        if ($id > 0) {
            //lanjut
        } else {
            Session::flash('warning', 'Data Pengiriman Uang gagal disimpan');
            return redirect()->back();
        }

        $jmlbaris = $request->txbaris;
        $kodepajak = $request->ck_pajak;

        $total = 0;
        Kirim_uang_detail::where('kirimuangid', $id)->delete();
        Transaksi::where('itemid', $id)->where('item', 'kirimuang')->delete();
        for ($i = 1; $i <= $jmlbaris; $i++) {
            $arr = array();
            $akunid = 'akun' . $i;
            $catatan = 'tx2_' . $i;
            $nilai = 'tx3_' . $i;
            $arr += ['kirimuangid' => $id];
            $arr += ['akunid' => $request->$akunid];
            $arr += ['catatan' => $request->$catatan];

            $pajakid = 'pajak' . ($i);
            $pajaknilaix = Pajak::where('id', $request->$pajakid)->get();

            $pajaknilai = 0;
            $pajak_akunoutid = '';
            if ($pajaknilaix !== null) {
                foreach ($pajaknilaix as $p) {
                    $pajaknilai = $p->nilai;
                    $pajak_akunoutid = $p->akunoutid;
                }
            }
            $pajakjumlah = 0;
            if ($kodepajak == 'on') {
                $pajakjumlah = $this->cekNum($request->$nilai ?? 0) / ($pajaknilai);
                $nilaijumlah = $this->cekNum($request->$nilai ?? 0) - $pajakjumlah;
                $total += $this->cekNum($request->$nilai ?? 0);
            } else {
                $pajakjumlah = $this->cekNum($request->$nilai ?? 0) * ($pajaknilai / 100);
                $nilaijumlah = $this->cekNum($request->$nilai);
                $total += $nilaijumlah + $pajakjumlah;
            }

            $arr += ['pajakid' => $request->$pajakid];
            $arr += ['pajak' => $pajakjumlah];
            $arr += ['nilai' => $nilaijumlah];

            $insert = Kirim_uang_detail::create($arr);

            //transaksi per item
            $transaksi = Transaksi::insert([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'item' => 'kirimuang',
                'itemid' => $id,
                'akunid' => $request->$akunid,
                'debit' => $nilaijumlah
            ]);

            //transaksi akun pajak
            if ($pajak_akunoutid) {
                $transaksi = Transaksi::insert([
                    'tanggal' => $this->EnglishTgl($request->tanggal),
                    'item' => 'kirimuang',
                    'itemid' => $id,
                    'akunid' => $pajak_akunoutid,
                    'debit' => $pajakjumlah
                ]);
            }
        }

        //transaksi akun sumber
        $transaksi = Transaksi::insert([
            'tanggal' => $this->EnglishTgl($request->tanggal),
            'item' => 'kirimuang',
            'itemid' => $id,
            'akunid' => $request->akunsumberid,
            'kredit' => $total
        ]);

        Kirim_uang::find($id)->update(['nilai' => $total]);

        return redirect(route('showKirimUang', $id));
    }

    public function editkirimUang($id)
    {
        return $this->showKirimUang($id, 3, 1);
    }

    public function showKirimUang($id, $kodeubah = 2, $kode = 0)
    {
        if ($kode == 1) {
            if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));
        }
        Session::flash('backUrl', request()->headers->get('referer'));

        $data = Kirim_uang::find($id);
        $pajak = Pajak::all();
        $akunx = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();
        $akun = Akun::where('jenis', 1)->get();
        $detail = Kirim_uang_detail::where('kirimuangid', $id)->get();
        $lampiran = Lampiran::where('jenis', 'kirim')->where('itemid', $id)->get();
        $tag = ['menu' => 'Kas / Bank', 'submenu' => 'Kirim Uang', 'judul' => 'KIRIM UANG', 'menuurl' => 'saldoKas', 'modal' => 'true'];
        return view('kas.kirim_uang', compact('data', 'detail', 'tag', 'kodeubah', 'pajak', 'akun', 'akunx', 'lampiran'));
    }

    public function terimauangupdate(Request $request)
    {
        if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id == "") {
            $id = Terima_uang::create([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'nomor' => $this->buatKode('terimauang', 'nomor', 'TU'),
                'catatan' => $request->catatan,
                'dari' => $request->dari,
                'akunsetorid' => $request->akunsetorid,
                'kodepajak' => $request->ck_pajak,
                'status' => 0
            ]);
            $id = $id->id;
            
        } else {
            $data = Terima_uang::find($id);
            $data->tanggal = $this->EnglishTgl($request->tanggal);
            $data->catatan = $request->catatan;
            $data->dari = $request->dari;
            $data->akunsetorid = $request->akunsetorid;
            $data->kodepajak = $request->ck_pajak;
            $data->save();
        }

        if ($id > 0) {
            //lanjut
        } else {
            Session::flash('warning', 'Data Terima Uang gagal disimpan');
            return redirect()->back();
        }

        $jmlbaris = $request->txbaris;
        $kodepajak = $request->ck_pajak;

        $total = 0;
        Terima_uang_detail::where('terimauangid', $id)->delete();
        Transaksi::where('itemid', $id)->where('item', 'terimauang')->delete();
        for ($i = 1; $i <= $jmlbaris; $i++) {
            $arr = array();
            $akunid = 'akun' . $i;
            $catatan = 'tx2_' . $i;
            $nilai = 'tx3_' . $i;
            $arr += ['terimauangid' => $id];
            $arr += ['akunid' => $request->$akunid];
            $arr += ['catatan' => $request->$catatan];

            $pajakid = 'pajak' . ($i);
            $pajaknilaix = Pajak::where('id', $request->$pajakid)->get();

            $pajaknilai = 0;
            $pajak_akunoutid = '';
            if ($pajaknilaix !== null) {
                foreach ($pajaknilaix as $p) {
                    $pajaknilai = $p->nilai;
                    $pajak_akunoutid = $p->akunoutid;
                }
            }
            $pajakjumlah = 0;
            if ($kodepajak == 'on') {
                $pajakjumlah = $this->cekNum($request->$nilai ?? 0) / ($pajaknilai);
                $nilaijumlah = $this->cekNum($request->$nilai ?? 0) - $pajakjumlah;
                $total += $this->cekNum($request->$nilai ?? 0);
            } else {
                $pajakjumlah = $this->cekNum($request->$nilai ?? 0) * ($pajaknilai / 100);
                $nilaijumlah = $this->cekNum($request->$nilai);
                $total += $nilaijumlah + $pajakjumlah;
            }

            $arr += ['pajakid' => $request->$pajakid];
            $arr += ['pajak' => $pajakjumlah];
            $arr += ['nilai' => $nilaijumlah];

            $insert = Terima_uang_detail::insert($arr);

            //transaksi per item
            $transaksi = Transaksi::insert([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'item' => 'terimauang',
                'itemid' => $id,
                'akunid' => $request->$akunid,
                'kredit' => $nilaijumlah
            ]);

            //transaksi akun pajak
            if ($pajak_akunoutid) {
                $transaksi = Transaksi::insert([
                    'tanggal' => $this->EnglishTgl($request->tanggal),
                    'item' => 'terimauang',
                    'itemid' => $id,
                    'akunid' => $pajak_akunoutid,
                    'kredit' => $pajakjumlah
                ]);
            }
        }

        //transaksi akun setor
        $transaksi = Transaksi::insert([
            'tanggal' => $this->EnglishTgl($request->tanggal),
            'item' => 'terimauang',
            'itemid' => $id,
            'akunid' => $request->akunsetorid,
            'debit' => $total
        ]);

        Terima_uang::where('id', $id)->update(['nilai' => $total]);

        return redirect(route('showTerimaUang', $id));
    }

    public function editTerimaUang($id)
    {
        return $this->showTerimaUang($id, 3, 1);
    }

    public function showTerimaUang($id, $kodeubah = 2, $kode = 0)
    {
        if ($kode == 1) {
            if (UserAkses::cek_akses('kas_bank', 'cud') == false) return redirect(route('noakses'));
        }
        Session::flash('backUrl', request()->headers->get('referer'));
        $data = Terima_uang::find($id);
        $pajak = Pajak::all();
        $akunx = Akun::where(DB::raw('MID(kode,1,2)'), "10")->where('jenis', 1)->get();
        $akun = Akun::where('jenis', 1)->get();
        $lampiran = Lampiran::where('jenis', 'terimauang')->where('itemid', $id)->get();

        $detail = Terima_uang_detail::where('terimauangid', $id)->get();
        $tag = ['menu' => 'Kas / Bank', 'submenu' => 'Terima Uang', 'judul' => 'TERIMA UANG', 'menuurl' => 'saldoKas', 'modal' => 'true'];
        return view('kas.terima_uang', compact('data', 'detail', 'tag', 'kodeubah', 'pajak', 'akun', 'akunx', 'lampiran'));
    }

    public function destroyLampiran($id)
    {
        Lampiran::destroy($id);
        return redirect()->back();
    }

    function cekNum($nilaix)
    {
        $nilai = str_replace('.', '', $nilaix);
        $nilai = str_replace(',', '.', $nilai);
        if (is_numeric($nilai) && $nilai > 0) {
            return $nilai;
        } else {
            return 0;
        }
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

    function buatKode($tabel, $kolom, $inisial)
    {

        $row = DB::table($tabel)->selectRaw("max($kolom) as kode")->first();
        $nourut = substr($row->kode, -5);
        $nourut++;
        $nourut = sprintf("%05s", $nourut);

        return $inisial . $nourut;
    }
}
