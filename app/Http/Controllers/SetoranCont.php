<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Helpers\MyHelpers;
use App\Helpers\UserAkses;
use App\JenisSimpanan;
use App\Setoran;
use App\Simpanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

date_default_timezone_set('Asia/Jakarta');

class SetoranCont extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        if (UserAkses::cek_akses('setoran', 'lihat') == false) return redirect(route('noakses'));

        $data = Setoran::join('anggota', 'anggota.id', 'setoran.anggotaid')
            ->join('simpanan', 'simpanan.setoranid', 'setoran.id')
            ->select('setoran.*', 'anggota.nama as nama_anggota', 'anggota.nik', 'simpanan.bulan', 'simpanan.tahun')
            ->orderByRaw('setoran.nomor DESC')
            ->get();

        $anggota = Anggota::orderBy('nama')->get();
        $jenis_simpanan = JenisSimpanan::where('status', 1)->get();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Setoran', 'judul' => 'Daftar Setoran', 'menuurl' => 'simpanan', 'modal' => 'true'];
        return view('setoran.index', compact('data', 'tag', 'jenis_simpanan', 'anggota'));
    }

    public function filter(Request $request)
    {
        $tgl = $request->tglFilter;
        $anggotaid_f = $request->f_anggota;

        $tgl1 =  $this->EngTgl(substr($tgl, 0, 10));
        $tgl2 =  $this->EngTgl(substr($tgl, -10));

        $jenis_f = $request->f_jenissimpanan;
        $arrjenis_f = ($jenis_f == '' || $jenis_f == 'all') ? array('WAJIB', 'POKOK') : array($jenis_f);

        $arr_q = array();
        $w = '';
        if ($jenis_f == '' || $jenis_f == 'all') {
            //1
        } else {
            $arr_q[] = array('setoran.jenis_simpanan', '=', $jenis_f);
        }
        if ($anggotaid_f == '' || $anggotaid_f == 'all') {
            //1
        } else {
            $arr_q[] = array('setoran.anggotaid', $anggotaid_f);
        }
        $arr_q[] = array(function ($query) use ($tgl1, $tgl2) {
            $query->whereBetween('setoran.tgl_transaksi', array($tgl1, $tgl2));
        });

        $data = Setoran::join('anggota', 'anggota.id', 'setoran.anggotaid')
            ->join('simpanan', 'simpanan.setoranid', 'setoran.id')
            ->where($arr_q)
            ->select('setoran.*', 'anggota.nama as nama_anggota', 'anggota.nik', 'simpanan.bulan', 'simpanan.tahun')
            ->orderByRaw('setoran.nomor DESC')
            ->get();

        $anggota = Anggota::orderBy('nama')->get();
        $jenis_simpanan = JenisSimpanan::where('status', 1)->get();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Setoran', 'judul' => 'Daftar Setoran', 'menuurl' => 'simpanan', 'modal' => 'true'];
        return view('setoran.index', compact('data', 'tag', 'jenis_simpanan', 'jenis_f', 'tgl', 'anggotaid_f', 'anggota'));
    }

    public function getSimpanan($id)
    {
    }

    public function rekon()
    {
        $anggota = DB::select("select id, ((TIMESTAMPDIFF(MONTH, tgl_daftar, '2021-07-01')*100000) + 250000) as jmlrekon from anggota");

        foreach ($anggota as $row) {

            $id = Setoran::insertGetId([
                'anggotaid' => $row->id,
                'nomor' => $this->buatKode('setoran', 'nomor', 'STS'),
                'userid' => Auth::user()->id,
                'nilai' => $row->jmlrekon ? $row->jmlrekon : 0,
                'jenis_simpanan' => 'POKOK+WAJIB',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'tgl_transaksi' => '2021-07-01'
            ]);

            $simpananid = Simpanan::insertGetId([

                'anggotaid' => $row->id,
                'setoranid' => $id,
                'nilai' => $row->jmlrekon ? $row->jmlrekon : 0,
                'jenis_simpanan' => 'POKOK+WAJIB',
                'tahun' => '2021',
                'bulan' => 7,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        echo 'oke';
    }

    public function create()
    {
        if (UserAkses::cek_akses('setoran', 'cud') == false) return 'Maaf, Anda tidak memiliki akses';

        $jenis_simpanan = JenisSimpanan::where('status', 1)->get();
        $anggota = Anggota::where('status', 1)->get();
        $route = url('/setoran/store');

        return view('setoran.create', compact('anggota', 'jenis_simpanan', 'route'));
    }

    public function store(Request $request)
    {
        if (UserAkses::cek_akses('setoran', 'cud') == false) return redirect(route('noakses'));

        $simpananid = 0;

        $id = Setoran::create([
            'anggotaid' => $request->anggotaid,
            'nomor' => $this->buatKode('setoran', 'nomor', 'STS'),
            'userid' => Auth::user()->id,
            'nilai' => $request->nilai,
            'jenis_simpanan' => $request->jenis_simpanan,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'tgl_transaksi' => $this->EngTgl($request->tgl_transaksi)
        ]);

        $id = $id->id;

        if ($id > 0) {

            $simpanandb = JenisSimpanan::where('nama', $request->jenis_simpanan)->first();
            $nilaisimpanan = $simpanandb->nilai;
            $pembayaran = $request->nilai;

            if ($pembayaran >= $nilaisimpanan) {
                $jumlahbulan = ceil($pembayaran / $nilaisimpanan);
                $bulan = $request->bulan;
                $tahun = $request->tahun;

                for ($i = 1; $i <= $jumlahbulan; $i++) {

                    if (($bulan + 1) > 12) {
                        $bulan = 1;
                        $tahun = $tahun + 1;
                    }

                    $simpananid = Simpanan::create([

                        'anggotaid' => $request->anggotaid,
                        'setoranid' => $id,
                        'nilai' => $pembayaran >= $nilaisimpanan ? $nilaisimpanan : $pembayaran,
                        'jenis_simpanan' => $request->jenis_simpanan,
                        'tahun' => $tahun,
                        'bulan' => $request->jenis_simpanan == 'POKOK' ? 0 : $bulan,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    $simpananid = $simpananid->id;
                    
                    $pembayaran -= $nilaisimpanan;
                    $bulan++;
                }
            }
        }
        // Session::flash('sukses','Produk berhasil ditambahkan');
        // return redirect('/produk/'.$id); 
        if ($simpananid > 0 && $id > 0) {

            $akun =  $request->jenis_simpanan == 'WAJIB' ? MyHelpers::akunMapping('wajib') : MyHelpers::akunMapping('pokok');
            $akunbank = MyHelpers::akunMapping('simpanan');
            //insert transaksi
            MyHelpers::inputTransaksi($this->EngTgl($request->tgl_transaksi), 'setoran_' . $request->jenis_simpanan, $id, $akun, 0, $request->nilai);
            MyHelpers::inputTransaksi($this->EngTgl($request->tgl_transaksi), 'setoran_' . $request->jenis_simpanan, $id, $akunbank, $request->nilai, 0);

            $msg = 'Data berhasil disimpan ';
            echo $msg;
        } else {
            $msg = 'Data gagal disimpan <br> ';
            echo $msg;
        }
    }

    public function update(Request $request)
    {
        if (UserAkses::cek_akses('setoran', 'cud') == false) return redirect(route('noakses'));

        $setoran = Setoran::find($request->id);
        $setoran->anggotaid = $request->anggotaid;
        $setoran->userid = Auth::user()->id;
        $setoran->nilai = $request->nilai;
        $setoran->jenis_simpanan = $request->jenis_simpanan;
        $setoran->tgl_transaksi = $this->EngTgl($request->tgl_transaksi);
        $setoran->updated_at = date('Y-m-d H:i:s');
        $setoran->save();

        $akun =  $request->jenis_simpanan == 'WAJIB' ? MyHelpers::akunMapping('wajib') : MyHelpers::akunMapping('pokok');
        $akunbank = MyHelpers::akunMapping('simpanan');
        //insert transaksi
        MyHelpers::inputTransaksi($this->EngTgl($request->tgl_transaksi), 'setoran_' . $request->jenis_simpanan, $request->id, $akun, 0, $request->nilai, 1);
        MyHelpers::inputTransaksi($this->EngTgl($request->tgl_transaksi), 'setoran_' . $request->jenis_simpanan, $request->id, $akunbank, $request->nilai, 0, 0);

        $msg = 'Data setoran berhasil disimpan ';
        echo $msg;
    }

    public function edit($id)
    {
        if (UserAkses::cek_akses('setoran', 'cud') == false) return 'Maaf, Anda tidak memiliki akses';

        $data = Setoran::find($id);
        $anggota = Anggota::where('status', 1)->get();
        $jenis_simpanan = JenisSimpanan::where('status', 1)->get();
        $route = url('/setoran/update');

        return view('setoran.edit', compact('data', 'anggota', 'jenis_simpanan', 'route'));
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('setoran', 'cud') == false) return redirect(route('noakses'));

        Setoran::destroy($id);
        
        Simpanan::where('setoranid', $id)->delete();
        return redirect()->back();
    }

    public function import()
    {
    }

    function buatKode($tabel, $kolom, $inisial)
    {

        $row = DB::table($tabel)->selectRaw("max($kolom) as kode")->first();
        $nourut = substr($row->kode, -5);
        $nourut++;
        $nourut = sprintf("%05s", $nourut);

        return $inisial . $nourut;
    }

    function EngTgl($tanggal)
    {
        $tgl = explode('/', $tanggal);
        if ($tanggal == '' || $tanggal == '00') {
            $awal = null;
        } else {
            $awal = "$tgl[2]-$tgl[1]-$tgl[0]";
        }
        return $awal;
    }
}
