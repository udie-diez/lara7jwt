<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Angsuran;
use App\BayarAngsuran;
use App\Helpers\MyHelpers;
use App\Helpers\UserAkses;
use App\JenisSimpanan;
use App\Pengelola;
use App\Pengurus;
use App\Pinjaman;
use App\Setoran;
use App\Simpanan;
use App\TempPayroll;
use App\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class RekonController extends Controller
{

    public function index()
    {
        if (UserAkses::cek_akses('rekon_payroll', 'lihat') == false) return redirect(route('noakses'));
        if (UserAkses::cek_akses('rekon_payroll', 'cud') == false) return redirect(route('noakses'));

        // $data = DB::select('SELECT * from temp_payroll where periode = (Select periode from temp_payroll where id = (select max(id) from temp_payroll))');
        $data = array();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Rekon Data Payroll', 'judul' => 'REKON DATA PAYROLL', 'menuurl' => 'rekon', 'modal' => 'true'];
        // Session::pull('tahun_f');
        // Session::flush();
        return view('payroll.index', compact('tag', 'data'));
    }

    public function simpanan($periode = '')
    {

        // //cek data
        $periode = str_replace('_', '/', $periode);
        $periode1 = explode('/', $periode);
        $bulan = $periode1[1];
        $tahun = $periode1[0];

        $cekrekon = Setoran::join('simpanan', 'setoran.id', 'simpanan.setoranid')
            ->where('bulan', $bulan)->where('tahun', $tahun)
            ->where('payroll', 1)->select('setoran.id as angsuranid', 'simpanan.id as simpananid')->get();

        if ($cekrekon !== null) {
            foreach ($cekrekon as $key) {
                $hapus = Setoran::where('id', $key->angsuranid)->delete();
            }
            foreach ($cekrekon as $key) {
                $hapus = Simpanan::where('id', $key->simpananid)->delete();
            }
        }
        $update = TempPayroll::where('periode', $periode)->update(['status_simpanan' => 0]);
        // if (User::where('email', '=', Input::get('email'))->exists()) {
        //     // user found
        //  }

        $rekon = DB::select('SELECT t.*, anggota.id as anggotaid from temp_payroll t inner join anggota on anggota.nik = t.nik where t.py_simpanan > 0 and (t.status_simpanan is null or t.status_simpanan = 0) and t.periode = (Select periode from temp_payroll where id = (select max(id) from temp_payroll))');

        $jenis = JenisSimpanan::all();
        foreach ($jenis as $j) {
            if ($j->nama == 'POKOK') {
                $pokok = $j->nilai;
            } else if ($j->nama == 'WAJIB') {
                $wajib = $j->nilai;
            }
        }

        $jumlah = 0;
        $jumlahWajib = $jumlahPokok = 0;
        foreach ($rekon as $r) {
            if ($r->py_simpanan == ($pokok + $wajib)) {
                $i = 2;
                $jenissetoran = 'POKOK, WAJIB';
                $iuran[0] = ['POKOK', $pokok];
                $iuran[1] = ['WAJIB', $wajib];
            } else if ($r->py_simpanan == (2 * $wajib)) {
                $i = 2;
                $jenissetoran = 'WAJIB';
                $iuran[0] = ['WAJIB', $wajib];
                $iuran[1] = ['WAJIB', $wajib];
            } else if ($r->py_simpanan == (3 * $wajib)) {
                $i = 3;
                $jenissetoran = 'WAJIB';
                $iuran[0] = ['WAJIB', $wajib];
                $iuran[1] = ['WAJIB', $wajib];
                $iuran[2] = ['WAJIB', $wajib];
            } else if ($r->py_simpanan == $wajib) {
                $i = 1;
                $jenissetoran = 'WAJIB';
                $iuran[0] = ['WAJIB', $wajib];
            } else if ($r->py_simpanan == $pokok) {
                $i = 1;
                $jenissetoran = 'POKOK';
                $iuran[0] = ['POKOK', $pokok];
            } else {
                $i = 1;
                $jenissetoran = 'WAJIB';
                $iuran[0] = ['WAJIB', $r->py_simpanan];
            }

            $id = Setoran::insertGetId([
                'anggotaid' => $r->anggotaid,
                'nomor' => $this->buatKode('setoran', 'nomor', 'STS'),
                'userid' => Auth::user()->id,
                'nilai' => $r->py_simpanan,
                'payroll' => 1,
                'jenis_simpanan' => $jenissetoran,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'tgl_transaksi' => date('Y-m-d H:i:s')
            ]);

            if ($id > 0) {
                for ($x = 0; $x < $i; $x++) {
                    $simpananid = Simpanan::insertGetId([
                        'anggotaid' => $r->anggotaid,
                        'setoranid' => $id,
                        'nilai' => $iuran[$x][1],
                        'jenis_simpanan' => $iuran[$x][0],
                        'tahun' => date('Y', strtotime($r->periode)),
                        'bulan' => date('m', strtotime($r->periode)),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                    if($iuran[$x][0] == 'WAJIB') {
                        $jumlahWajib +=$iuran[$x][1];
                    }else{
                        $jumlahPokok += $iuran[$x][1];
                    }
                    
                }
            }

            if ($simpananid > 0) {
                $updaterekon = TempPayroll::where('id', $r->id)->update(['status_simpanan' => 1]);
                $jumlah++;

                // $akun =  $jenissetoran == 'WAJIB' ? MyHelpers::akunMapping('wajib') : MyHelpers::akunMapping('pokok');
                // $akunbank = MyHelpers::akunMapping('simpanan');
                // //insert transaksi
                // MyHelpers::inputTransaksi(date('Y-m-d H:i:s'), 'setoran_' . $jenissetoran, $id, $akun, 0, $r->py_simpanan);
                // MyHelpers::inputTransaksi(date('Y-m-d H:i:s'), 'setoran_' . $jenissetoran, $id, $akunbank, $r->py_simpanan, 0);
            }
        }

        $akunwajib = MyHelpers::akunMapping('wajib');
        $akunpokok = MyHelpers::akunMapping('pokok');
        $akunbank = MyHelpers::akunMapping('simpanan');
        //insert transaksi

        Transaksi::where('item','setoran_wajib')->where(DB::raw('month(tanggal)'),date('m'))->where(DB::raw('year(tanggal)'),date('Y'))->delete();
        Transaksi::where('item','setoran_pokok')->where(DB::raw('month(tanggal)'),date('m'))->where(DB::raw('year(tanggal)'),date('Y'))->delete();

        MyHelpers::inputTransaksi(date('Y-m-d H:i:s'), 'setoran_wajib', $id, $akunbank, $jumlahWajib, 0);
        MyHelpers::inputTransaksi(date('Y-m-d H:i:s'), 'setoran_wajib', $id, $akunwajib, 0, $jumlahWajib);
        if($jumlahPokok > 0){
            MyHelpers::inputTransaksi(date('Y-m-d H:i:s'), 'setoran_pokok', $id, $akunpokok, 0, $jumlahPokok);
            MyHelpers::inputTransaksi(date('Y-m-d H:i:s'), 'setoran_pokok', $id, $akunbank, $jumlahPokok, 0);
        }


        $hasil = TempPayroll::where('periode', $periode)->where('status_simpanan', 1)->count();
        $data = TempPayroll::where('periode', $periode)->get();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Rekon Data Payroll', 'judul' => 'REKON DATA PAYROLL', 'menuurl' => 'rekon', 'modal' => 'true'];
        Session::flash('bulan_f', $bulan);
        Session::flash('tahun_f', $tahun);
        Session::flash('jumlahsimpanan_f', $hasil);
        Session::flash('sukses', 'Data Payroll Simpanan Berhasil Diproses!');

        return view('payroll.index', compact('tag', 'data'));
    }

    public function angsuran($periode = '')
    {

        $periode = str_replace('_', '/', $periode);
        $periode1 = explode('/', $periode);
        $bulan = $periode1[1];
        $tahun = $periode1[0];

        $cekrekon = Pinjaman::join('bayar_angsuran', 'pinjaman.id', 'bayar_angsuran.pinjamanid')
            ->where('tgl_awal', '<=', $periode)
            ->where('tgl_akhir', '>=', $periode)
            ->where('pinjaman.status', 9)
            ->where('payroll', 1)->select('bayar_angsuran.pinjamanid', 'bayar_angsuran.id')->get();
        if ($cekrekon !== null) {
            foreach ($cekrekon as $key) {
                $hapus = BayarAngsuran::where('id', $key->id)->delete();
            }

            foreach ($cekrekon as $key) {
                $update = Angsuran::where('bayarangsuranid', $key->id)->update(['status' => 0, 'jumlah' => 0, 'userid' => 0, 'bayarangsuranid' => 0]);
            }
        }
        $update = TempPayroll::where('periode', $periode)->update(['status_angsuran' => 0, 'status_lainlain' => 0]);
        // if (User::where('email', '=', Input::get('email'))->exists()) {
        //     // user found
        //  }

        $rekon = DB::select('SELECT t.* from temp_payroll t inner join anggota on anggota.nik = t.nik where t.py_angsuran > 0 and (t.status_angsuran is null or t.status_angsuran = 0) and t.periode = (Select periode from temp_payroll where id = (select max(id) from temp_payroll))');
        $pinjaman = Pinjaman::where('status', 9)->get();
        foreach ($rekon as $r) {
            $pinjamanid = $bulan = array();
            $idangsuran = $s = 0;
            foreach ($pinjaman as $p) {
                if ($r->nik == $p->nik) {
                    $pinjamanid[] = $p->id;

                    $bulanx =  ($s == 0 ? $r->py_angsuran : $r->py_lainlain) / $p->angsuranfix;
                    if ($bulanx < 2) {
                        $bulanx = 1;
                    }
                    $bulan[] = $bulanx;
                }
                $s++;
            }

            if (count($pinjamanid) > 0) {
                for ($j = 0; $j < count($pinjamanid); $j++) {

                    $idangsuran = BayarAngsuran::insertGetId([
                        'pinjamanid' => $pinjamanid[$j],
                        'jumlah' => $j == 0 ? $r->py_angsuran : $r->py_lainlain,
                        'tgl_bayar' => $r->tgl_awal,
                        'userid' => Auth::user()->id,
                        'payroll' => 1,
                        'jumlah_bulan' => floor($bulan[$j]),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);

                    if ($idangsuran > 0) {

                        for ($i = 1; $i <= $bulan[$j]; $i++) {
                            if ($bulan[$j] < 2) {
                                $bayar = $j == 0 ? $r->py_angsuran : $r->py_lainlain;
                            } else {
                                $bayar = ($j == 0 ? $r->py_angsuran : $r->py_lainlain) / $bulan[$j];
                            }
                            $tgl_awal = $r->periode; //periode gani tanggal-awal;
                            $tgl_awal = strtotime($tgl_awal);
                            $tgl_awal = date("Y-m-d", strtotime("+" . ($i - 1) . " month", $tgl_awal));

                            $update = Angsuran::where('pinjamanid', $pinjamanid[$j])
                                ->where('tgl_bayar', $tgl_awal)
                                ->update(['status' => 1, 'jumlah' => $bayar, 'userid' => Auth::user()->id, 'bayarangsuranid' => $idangsuran]);
                        }

                        if ($j == 0) {
                            $updaterekon = TempPayroll::where('id', $r->id)->update(['status_angsuran' => 1]);
                        } else {
                            $updaterekon = TempPayroll::where('id', $r->id)->update(['status_lainlain' => 1]);
                        }

                        $ceklunas = DB::select("select (case when sum(angsuran) <= sum(jumlah) then 1 else 0 end) as lunas  from angsuran  where pinjamanid = " . $pinjamanid[$j] . " group by pinjamanid");

                        if ($ceklunas[0]->lunas == 1) {
                            $updatepinjaman = Pinjaman::where('id', $pinjamanid[$j])->update(['status' => 8, 'updated_at' => date('Y-m-d H:i:s')]);
                        }

                        //jurnal

                        //cek nik anggota atau karyawan ?
                        $pengurusx = Pengurus::where('nik', $r->nik)->first();
                        if ($pengurusx) {
                            $sipeminjam = 'karyawan';
                        } else {
                            $pengelolax = Pengelola::where('nik', $r->nik)->first();
                            if ($pengelolax) {
                                $sipeminjam = 'karyawan';
                            } else {
                                $sipeminjam = 'anggota';
                            }
                        }

                        $akun =  $sipeminjam == 'karyawan' ? MyHelpers::akunMapping('angsuran_karyawan') : MyHelpers::akunMapping('angsuran_anggota');
                        $akunbank = MyHelpers::akunMapping('angsuran_pinjaman');
                        //insert transaksi
                        MyHelpers::inputTransaksi($r->tgl_awal, 'angsuran_pinjaman', $idangsuran, $akun, 0, str_replace('.', '', $j == 0 ? $r->py_angsuran : $r->py_lainlain), 1);
                        MyHelpers::inputTransaksi($r->tgl_awal, 'angsuran_pinjaman', $idangsuran, $akunbank, str_replace('.', '', $j == 0 ? $r->py_angsuran : $r->py_lainlain), 0, 0);
                    }
                }
            }
        }

        $datasimpanan = TempPayroll::where('periode', $periode)->where('status_simpanan', 1)->count();
        $dataangsuran = TempPayroll::where('periode', $periode)->where('status_angsuran', 1)->count();
        $datalainlain = TempPayroll::where('periode', $periode)->where('status_lainlain', 1)->count();
        $data = TempPayroll::where('periode', $periode)->get();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Rekon Data Payroll', 'judul' => 'REKON DATA PAYROLL', 'menuurl' => 'rekon', 'modal' => 'true'];

        Session::flash('bulan_f', $bulan);
        Session::flash('tahun_f', $tahun);
        Session::flash('sukses', 'Data Payroll Angsuran dan Lain-lain Berhasil Diproses!');
        Session::flash('jumlahsimpanan_f', $datasimpanan);
        Session::flash('jumlahangsuran_f', $dataangsuran);
        Session::flash('jumlahlainlain_f', $datalainlain);

        return view('payroll.index', compact('tag', 'data'));

        return redirect()->back();
    }

    public function potonganPayroll()
    {
        if (UserAkses::cek_akses('potongan_payroll', 'lihat') == false) return redirect(route('noakses'));

        $data = Anggota::leftJoin('pinjaman', function ($join) {
            $join->on('pinjaman.nik', '=', 'anggota.nik');
            $join->on('pinjaman.status', '=', DB::raw(9));
        })
            ->select('pinjaman.*', 'anggota.nama', 'anggota.nik')
            ->where('anggota.status', 1)
            ->orderByRaw('anggota.nik', 'pinjaman.id')
            ->get();

        $jenis = JenisSimpanan::where('nama', 'WAJIB')->first();

        $angsuran = array();
        $nik = '';
        $i = 0;
        $lainlain = 0;
        foreach ($data as $row) {

            if ($nik != $row->nik) {
                $lainlain = 0;
                $angsuran[$i]['nik'] = $row->nik;
                $angsuran[$i]['nama'] = $row->nama;
                $angsuran[$i]['angsuran'] = $row->angsuranfix;
                $angsuran[$i]['lainlain'] = $lainlain;
                $angsuran[$i]['simpanan'] = $jenis->nilai;

                $i++;
            } else {
                $lainlain += $row->angsuranfix;
                $angsuran[$i - 1]['lainlain'] = $lainlain;
            }
            $nik = $row->nik;
        }

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Potongan Payroll', 'judul' => 'DAFTAR POTONGAN PAYROLL', 'menuurl' => 'potonganPayroll', 'modal' => 'true'];
        return view('payroll.daftar_potongan', compact('tag', 'data', 'angsuran'));
    }

    public function filterPotongan(Request $request)
    {
        $bulan_f = $request->f_bulan;
        $tahun_f = $request->f_tahun;
        $tahun_f = $tahun_f == '' ? date('Y') : $tahun_f;

        $tgl = date('Y-m-d', strtotime($tahun_f . '-' . $bulan_f . '-1'));
        $tgl = "'$tgl'";

        $data = Anggota::leftJoin('pinjaman', function ($join) use ($tgl) {
            $join->on('pinjaman.nik', '=', 'anggota.nik');
            $join->where('pinjaman.status', '=', DB::raw(9));
            $join->whereBetween(DB::raw($tgl), [DB::raw('pinjaman.tgl_awal'), DB::raw('pinjaman.tgl_akhir')]);
        })
            ->select('pinjaman.*', 'anggota.nama', 'anggota.nik', 'anggota.tgl_daftar')
            ->where('anggota.status', 1) 
            ->orderByRaw('anggota.id')
            ->get();
        $wajib = JenisSimpanan::where('nama', 'WAJIB')->first();
        $pokok = JenisSimpanan::where('nama', 'POKOK')->first();

        $angsuran = array();
        $nik = '';
        $i = 0;
        $lainlain = 0;

        $bulan = $tahun_f . '-' . $bulan_f;
        
        foreach ($data as $row) {
            if ($nik != $row->nik) {
                $lainlain = 0;
                $simpanan = $wajib->nilai;
                    $bln = $row->tgl_daftar . " +1 Month";
                    if (date("Y-n", strtotime($bln)) == $bulan) {
                        $simpanan = $wajib->nilai + $pokok->nilai;
                    }
                $angsuran[$i]['nik'] = $row->nik;
                $angsuran[$i]['nama'] = $row->nama;
                $angsuran[$i]['angsuran'] = $row->angsuranfix;
                $angsuran[$i]['lainlain'] = $lainlain;
                $angsuran[$i]['simpanan'] = $simpanan;
                $angsuran[$i]['tgl_awal'] = date('d/m/Y', strtotime($tgl));

                $i++;
            } else {
                $lainlain += $row->angsuranfix;
                $angsuran[$i - 1]['lainlain'] = $lainlain;
            }
            $nik = $row->nik;
        }

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Potongan Payroll', 'judul' => 'DAFTAR POTONGAN PAYROLL', 'menuurl' => 'potonganPayroll', 'modal' => 'true'];
        return view('payroll.daftar_potongan', compact('data', 'tag', 'tahun_f', 'bulan_f', 'angsuran'));
    }

    public function cekDataRekon(Request $request)
    {
        $periode = $request->periode;
        $rekon = TempPayroll::where('periode', $periode)->get();
        if (count($rekon) > 0) {
            return true;
        } else {
            return false;
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
}
