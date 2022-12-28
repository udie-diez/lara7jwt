<?php

namespace App\Http\Controllers;

use App\Akun;
use App\Helpers\UserAkses;
use App\Jurnalumum;
use App\Jurnalumum_detail;
use App\Lampiran;
use App\Pajak;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use SebastianBergmann\Environment\Console;

class JurnalUmumCont extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (UserAkses::cek_akses('jurnal_umum', 'lihat') == false) return redirect(route('noakses'));

        $data = Jurnalumum::orderByRaw('nomor DESC')->get();


        $tag = ['menu' => 'Keuangan', 'submenu' => 'Jurnal Umum', 'judul' => 'DAFTAR JURNAL UMUM', 'menuurl' => 'jurnalumum', 'modal' => 'true'];
        return view('jurnalumum.index', compact('tag', 'data'));
    }

    public function create()
    {
        if (UserAkses::cek_akses('jurnal_umum', 'cud') == false) return redirect(route('noakses'));

        $akun = Akun::where('jenis', 1)->get();
        $pajak = Pajak::all();

        $kodeubah = 1;
        $tag = ['menu' => 'Keuangan', 'submenu' => 'Input Jurnal Umum', 'judul' => 'INPUT JURNAL UMUM', 'menuurl' => 'biaya', 'modal' => 'true'];

        return view('jurnalumum.create', compact('kodeubah', 'tag', 'akun', 'pajak'));
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('jurnal_umum', 'cud') == false) return redirect(route('noakses'));

        Jurnalumum::destroy($id);
        Jurnalumum_detail::where('jurnalumumid', $id)->delete();
        Transaksi::where('item', 'jurnal')->where('itemid', $id)->delete();
        return redirect('jurnalumum');
    }


    public function update(Request $request)
    {
        if (UserAkses::cek_akses('jurnal_umum', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id == "") {
            $id = Jurnalumum::insertGetId([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'nomor' => $this->buatKode('jurnalumum', 'nomor', 'JU'),
                'catatan' => $request->catatan
            ]);
        } else {
            $data = Jurnalumum::find($id);
            $data->tanggal = $this->EnglishTgl($request->tanggal);
            $data->catatan = $request->catatan;
            $data->save();
        }

        if ($id > 0) {
            //lanjut
        } else {
            Session::flash('warning', 'Data Jurnal Umum gagal disimpan');
            return redirect()->back();
        }

        $jmlbaris = $request->txbaris;
        $total = 0;
        Jurnalumum_detail::where('jurnalumumid', $id)->delete();
        Transaksi::where('itemid', $id)->where('item', 'jurnal')->delete();
        for ($i = 1; $i <= $jmlbaris; $i++) {
            $arr = array();
            $akunid = 'akun' . $i;
            if ($request->$akunid > 0) {
                $catatan = 'catatan_' . $i;
                $debit = 'debit_' . $i;
                $kredit = 'kredit_' . $i;
                $arr += ['jurnalumumid' => $id];
                $arr += ['akunid' => $request->$akunid];
                $arr += ['catatan' => $request->$catatan];
                $arr += ['debit' => $this->cekNum($request->$debit)];
                $arr += ['kredit' => $this->cekNum($request->$kredit)];
                $total += $this->cekNum($request->$debit);

                $insert = Jurnalumum_detail::insert($arr);

                //transaksi per item
                $transaksi = Transaksi::insert([
                    'tanggal' => $this->EnglishTgl($request->tanggal),
                    'item' => 'jurnal',
                    'itemid' => $id,
                    'akunid' => $request->$akunid,
                    'debit' => $this->cekNum($request->$debit),
                    'kredit' => $this->cekNum($request->$kredit),
                ]);
            }
        }

        Jurnalumum::where('id', $id)->update(['nilai' => $total]);

        return redirect(route('showJurnalumum', $id));
    }

    public function edit($id)
    {
        return $this->show($id, 3, 1);
    }

    public function show($id, $kodeubah = 2, $kode = 0)
    {
        if ($kode == 1) {
            if (UserAkses::cek_akses('jurnal_umum', 'cud') == false) return redirect(route('noakses'));
        }
        $data = Jurnalumum::find($id);
        $pajak = Pajak::all();
        $akun = Akun::where('jenis', 1)->get();
        $detail = Jurnalumum_detail::where('jurnalumumid', $id)->get();
        $lampiran = Lampiran::where('jenis', 'jurnal')->where('itemid', $id)->get();

        $tag = ['menu' => 'Keuangan', 'submenu' => 'Data Jurnal Umum', 'judul' => 'JURNAL UMUM', 'menuurl' => 'jurnalumum', 'modal' => 'true'];
        return view('jurnalumum.create', compact('data', 'detail', 'tag', 'kodeubah', 'pajak', 'akun', 'lampiran'));
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
