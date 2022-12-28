<?php

namespace App\Http\Controllers;

use App\Akun;
use App\Biaya;
use App\Biaya_detail;
use App\Helpers\UserAkses;
use App\Lampiran;
use App\Pajak;
use App\Project;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;


class BiayaCont extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (UserAkses::cek_akses('biaya', 'lihat') == false) return redirect(route('noakses'));

        $data = Biaya::leftjoin('project', 'project.id', 'biaya.projectid')
            ->select('biaya.*', 'project.no_po', DB::raw('(select a.nama from biaya_detail bd inner join akun a on a.id = bd.akunid  where bd.biayaid = biaya.id limit 0,1) as nama'))
            ->orderByRaw('biaya.nomor DESC')
            ->get();


        $tag = ['menu' => 'Keuangan', 'submenu' => 'Biaya', 'judul' => 'DAFTAR BIAYA', 'menuurl' => 'biaya', 'modal' => 'true'];
        return view('biaya.index', compact('tag', 'data'));
    }

    public function create()
    {
        if (UserAkses::cek_akses('biaya', 'cud') == false) return redirect(route('noakses'));

        $project = Project::where('status', 1)->where('no_spk', '<>', '')->select('id','nama')->orderByRaw('id DESC')->get();

        $akun = Akun::all();
        $pajak = Pajak::all();

        $kodeubah = 1;
        $tag = ['menu' => 'Keuangan', 'submenu' => 'Input Biaya', 'judul' => 'INPUT BIAYA', 'menuurl' => 'biaya', 'modal' => 'true'];

        return view('biaya.create', compact('kodeubah', 'tag', 'project', 'akun', 'pajak'));
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('biaya', 'cud') == false) return redirect(route('noakses'));

        Biaya::destroy($id);
        Biaya_detail::where('biayaid', $id)->delete();
        Transaksi::where('item', 'biaya')->where('itemid', $id)->delete();
        return redirect()->back();
    }

    public function destroyAkun($id)
    {
        if (UserAkses::cek_akses('biaya', 'lihat') == false) return redirect(route('noakses'));

        Biaya_detail::destroy($id);
        return redirect()->back();
    }


    public function update(Request $request)
    {
        if (UserAkses::cek_akses('biaya', 'cud') == false) return redirect(route('noakses'));


        $id = $request->id;
        if ($id == "") {
            $id = Biaya::insertGetId([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'nomor' => $this->buatKode('biaya', 'nomor', 'BA'),
                'projectid' => $request->project,
                'catatan' => $request->catatan,
                'carabayar' => $request->cara,
                'akunbayarid' => $request->akunid,
                'kodepajak' => $request->ck_pajak,
                'status' => 0
            ]);
        } else {
            $data = Biaya::find($id);
            $data->tanggal = $this->EnglishTgl($request->tanggal);
            $data->projectid = $request->project;
            $data->catatan = $request->catatan;
            $data->carabayar = $request->cara;
            $data->akunbayarid = $request->akunid;
            $data->kodepajak = $request->ck_pajak;
            $data->save();
        }

        if ($id > 0) {
            //lanjut


        } else {
            Session::flash('warning', 'Data Biaya gagal disimpan');
            return redirect()->back();
        }

        $jmlbaris = $request->txbaris;
        $kodepajak = $request->ck_pajak;

        $total = 0;
        Biaya_detail::where('biayaid', $id)->delete();
        Transaksi::where('itemid', $id)->where('item', 'biaya')->delete();
        for ($i = 1; $i <= $jmlbaris; $i++) {
            $arr = array();
            $akunid = 'akun' . $i;
            $catatan = 'tx2_' . $i;
            $nilai = 'tx3_' . $i;
            $arr += ['biayaid' => $id];
            $arr += ['akunid' => $request->$akunid];
            $arr += ['catatan' => $request->$catatan];

            $pajakid = 'pajak' . ($i);
            $pajaknilaix = Pajak::where('id', $request->$pajakid)->get();

            $pajaknilai = 0;
            $pajak_akuninid = '';
            if ($pajaknilaix !== null) {
                foreach ($pajaknilaix as $p) {
                    $pajaknilai = $p->nilai;
                    $pajak_akuninid = $p->akuninid;
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

            $insert = Biaya_detail::insert($arr);

            //transaksi per item
            $transaksi = Transaksi::insert([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'item' => 'biaya',
                'itemid' => $id,
                'akunid' => $request->$akunid,
                'debit' => $nilaijumlah
            ]);

            //transaksi akun pajak
            if ($pajak_akuninid) {
                $transaksi = Transaksi::insert([
                    'tanggal' => $this->EnglishTgl($request->tanggal),
                    'item' => 'biaya',
                    'itemid' => $id,
                    'akunid' => $pajak_akuninid,
                    'debit' => $pajakjumlah
                ]);
            }
        }

        //transaksi akun bayar
        $transaksi = Transaksi::insert([
            'tanggal' => $this->EnglishTgl($request->tanggal),
            'item' => 'biaya',
            'itemid' => $id,
            'akunid' => $request->akunid,
            'kredit' => $total
        ]);

        Biaya::where('id', $id)->update(['total' => $total]);

        return redirect(route('showBiaya', $id));
    }

    public function edit($id)
    {
        return $this->show($id, 3, 1);
    }

    public function show($id, $kodeubah = 2, $kode = 0)
    {
        if ($kode == 1) {
            if (UserAkses::cek_akses('biaya', 'cud') == false) return redirect(route('noakses'));
        }
        $data = Biaya::find($id);
        $project = Project::select('id', 'no_po')->get();
        $pajak = Pajak::all();
        $akun = Akun::all();
        $lampiran = Lampiran::where('jenis', 'biaya')->where('itemid', $id)->get();
        $detail = Biaya_detail::where('biayaid', $id)->get();
        $tag = ['menu' => 'Keuangan', 'submenu' => 'Data Biaya', 'judul' => 'BIAYA', 'menuurl' => 'biaya', 'modal' => 'true'];
        return view('biaya.create', compact('data', 'project', 'detail', 'tag', 'kodeubah', 'pajak', 'akun', 'lampiran'));
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
