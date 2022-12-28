<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Angsuran;
use App\BayarAngsuran;
use App\Helpers\MyHelpers;
use App\Helpers\UserAkses;
use App\Pelunasan;
use App\Pengelola;
use App\Pengurus;
use App\Pinjaman;
use App\Setoran;
use App\Simpanan;
use App\SumberPinjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\returnValue;

date_default_timezone_set('Asia/Jakarta');

class PinjamanCont extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    }

    public function daftarPermohonan()
    {
        if (UserAkses::cek_akses('pengajuan_pinjaman', 'lihat') == false) return redirect(route('noakses'));

        $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->where('pinjaman.status', '<>', 9)
            ->where('pinjaman.status', '<>', 8)
            ->select('pinjaman.*', 'anggota.nama', 'anggota.nik')
            ->orderByRaw('pinjaman.id DESC')
            ->get();

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'Daftar Permohonan Pinjaman', 'menuurl' => 'daftarPermohonan', 'modal' => 'false'];
        return view('pinjaman.daftar_permohonan', compact('tag', 'data'));
    }

    public function pengajuan()
    {
        if (Auth::user()->role == 'nonanggota') {
            if (UserAkses::cek_akses('pengajuan_pinjaman', 'cud') == false) return redirect(route('noakses'));
        }
        $nik = Auth::user()->email;
        $nik = substr($nik, 0, 6);
        $data = Anggota::where('nik', $nik)->first();
        $anggota = Anggota::where('status', 1)->get();
        // $pinjaman = Pinjaman::where('nik',$nik)->first();
        // if($pinjaman){
        //     Session::flash('exist','Anda TIDAK DIPERBOLEHKAN mengajukan pinjaman karena ada Pengajuan Pinjaman Anda yang sementara diproses/berjalan.');

        // }

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'PERMOHONAN PENGAJUAN PINJAMAN UANG', 'menuurl' => 'pinjaman', 'modal' => 'false'];
        return view('pinjaman.pengajuan', compact('tag', 'data', 'anggota'));
    }

    public function bayarAngsuran($view = array('kode' => 'pembayaran'))
    {
        if (UserAkses::cek_akses('pembayaran_angsuran', 'cud') == false) return redirect(route('noakses'));

        if ($view == '') {
            Session::forget('sukses');
            Session::forget('warning');
        }

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'PEMBAYARAN ANGSURAN', 'menuurl' => 'bayarAngsuran', 'modal' => 'false'];
        $anggota = Anggota::orderBy('nama')->get();
        return view('pinjaman.bayarangsuran', compact('tag', 'anggota', 'view'));
    }


    
    public function bayarPelunasan($view = array('kode' => 'pembayaran'))
    {
        if (UserAkses::cek_akses('pelunasan', 'cud') == false) return redirect(route('noakses'));

        if ($view == '') {
            Session::forget('sukses');
            Session::forget('warning');
        }
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'PEMBAYARAN PELUNASAN PINJAMAN', 'menuurl' => 'bayarPelunasan', 'modal' => 'false'];
        $anggota = Anggota::orderBy('nama')->get();
        return view('pinjaman.bayarpelunasan', compact('tag', 'anggota', 'view'));
    }


    public function info(Request $request)
    {

        $id = $request->id;
        $pinjamanid = $request->pinjamanid;
        $nilaibayar = $request->nilai ?? 0; // dari updata / bayar angsuran
        $bulanbayar = $request->bulan ?? 0; // dari updata / bayar angsuran
        $kode = $request->kode ?? '';
        $show = $request->show ?? 'pembayaran'; // dari showPelunasan / showAngsuran . pembayaran = saat ini prosespembayaran
        $anggota = Anggota::where('nik', $id)->first();
        $pinjaman = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->where('pinjaman.status', 9)
            ->where('pinjaman.nik', $id)
            ->select('pinjaman.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber', DB::raw("(select  case when sum(angsuran) <= sum(jumlah) then 1 else 0 end  from angsuran  where pinjamanid = pinjaman.id group by pinjamanid) as kodelunas"))
            ->orderByRaw('anggota.nik', 'pinjaman.id')
            ->get();

        $sisa = array();
        $bayarangsuranid = '';
        if ($pinjamanid > 0) {
            $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
                ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
                ->where('pinjaman.id', $pinjamanid)
                ->select('pinjaman.*', 'pinjaman.id as idpinjaman', 'anggota.nama', 'anggota.nomor', 'anggota.pic', 'anggota.nik', 'anggota.id as idanggota', 'sumber_pinjaman.nama as namasumber')
                ->first();

            $angsuran = Angsuran::where('pinjamanid', $pinjamanid)->get();
            $sisapinjaman = 0;
            $sisabulan = 0;
            $outstanding = 0;
            foreach ($angsuran as $a) {
                if ($a->angsuran > $a->jumlah) {
                    $sisapinjaman += $a->angsuran - $a->jumlah;
                    $outstanding = $a->outstanding > $outstanding ? $a->outstanding : $outstanding;
                    $sisabulan++;
                }
                $bayarangsuranid =  $a->bayarangsuranid;
            }

            if ($kode == 'pelunasan') {        //showpelunasan atau showangsuran
                $pelunasan = Pelunasan::where('pinjamanid', $pinjamanid)->first();
                if ($pelunasan) {
                    $sisapinjaman = $pelunasan->sisapinjaman;  // ndak kepake
                    $outstanding = $pelunasan->sisapinjaman;
                    $sisabulan = $pelunasan->sisaangsuran;
                }
            }

            $sisa = ['pinjaman' => $sisapinjaman, 'bulan' => $sisabulan, 'outstanding' => $outstanding];
        } else {
            $data = '';
            $angsuran = '';
        }
        // $angsuran = DB::select("SELECT angsuran.* from angsuran join (select pinjamanid, max(bulan) as bulan from angsuran where  status = 1 group by pinjamanid) a on a.pinjamanid = angsuran.pinjamanid and a.bulan = angsuran.bulan");
        // $angsuran = Angsuran::where('pinjamanid',$data->idpinjaman)->get();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'INFORMASI ANGGOTA', 'menuurl' => 'pinjaman', 'modal' => 'false'];

        return view('pinjaman.form_infopeminjam', compact('data', 'tag', 'pinjaman', 'angsuran', 'anggota', 'nilaibayar', 'bulanbayar', 'kode', 'sisa', 'show', 'bayarangsuranid'));
    }

    public function detail($id)
    {

        return $this->show($id, 'aktif');
    }

    public function show($id = 1, $kode = '')
    {
        if (UserAkses::cek_akses('pengajuan_pinjaman', 'cud') == false) return redirect(route('noakses'));

        $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->leftjoin('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->where('pinjaman.id', $id)
            ->select('pinjaman.*', 'pinjaman.id as idpinjaman', 'anggota.nama', 'anggota.nomor', 'anggota.pic', 'anggota.nik', 'anggota.id as idanggota', 'sumber_pinjaman.nama as namasumber')
            ->first();

        $nik = $data->nik;

        $simpanan = Setoran::join('anggota', 'anggota.id', 'setoran.anggotaid')
            ->groupBy('anggota.id')
            ->where('anggota.nik', $nik)
            ->sum('setoran.nilai');

        $pinjaman = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->where('pinjaman.nik', $data->nik)
            ->where('pinjaman.status', 9)
            ->select('pinjaman.*', 'pinjaman.id as idpinjaman', 'anggota.nama', 'anggota.nomor', 'anggota.pic', 'anggota.nik', 'anggota.id as idanggota')
            ->first();

        if ($kode == '') {
            $sumber = SumberPinjaman::all();
            $angsuran = Angsuran::where('pinjamanid', $id)->where('status', 1)->get();
            if (count($angsuran) > 0) {
                $canedit = false;
            } else {
                $canedit = true;
            }
            $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'DATA PERMOHONAN PINJAMAN UANG', 'menuurl' => 'pinjaman', 'modal' => 'false'];

            return view('pinjaman.form_permohonan', compact('data', 'tag', 'simpanan', 'sumber', 'pinjaman', 'canedit'));
        } else if ($kode == 'aktif') {   //detail pinjaman

            $angsuran = Angsuran::where('pinjamanid', $id)->get();
            $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'DETAIL INFORMASI PINJAMAN', 'menuurl' => 'pinjaman', 'modal' => 'false'];

            return view('pinjaman.form_detailpeminjam', compact('data', 'tag', 'simpanan', 'pinjaman', 'angsuran'));
        }
    }

    public function edit($id = 1)
    {
        if (UserAkses::cek_akses('pengajuan_pinjaman', 'cud') == false) return redirect(route('noakses'));

        $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->where('pinjaman.id', $id)
            ->select('pinjaman.*', 'pinjaman.id as idpinjaman', 'anggota.nama', 'anggota.nomor', 'anggota.pic', 'anggota.nik', 'anggota.id as idanggota')
            ->first();
        $anggota = Anggota::where('status', 1)->get();
        $nikanggota = $data->nik;

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'DATA PERMOHONAN PINJAMAN UANG', 'menuurl' => 'pinjaman', 'modal' => 'false'];

        return view('pinjaman.pengajuan', compact('data', 'tag', 'anggota', 'nikanggota'));
    }

    public function editPinjaman($id)
    {
        $angsuran = Angsuran::where('pinjamanid', $id)->where('status', 1)->get();
        if (count($angsuran) > 0) {
            Session::flash('warning', 'Data Pinjaman Aktif sudah tidak dapat diubah');
            return redirect()->back();
        } else {
            return redirect('/pinjaman/show/' . $id);
        }
    }

    public function store(Request $request)
    {
        if (UserAkses::cek_akses('pinjaman', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id) {
            return redirect('/pinjaman/' . $id);
        } else {

            if (!$request->nik) {
                $nik = $request->anggota;  //admin

            } else {
                $nik = $request->nik;  //anggota

            }
            $id = Pinjaman::create([
                'nik' => $nik,
                'tanggal' => $this->EnglishTgl($request->tanggal) . ' ' . date('H:i:s'),
                'tenor' => $request->tenor,
                'nilai' => str_replace('.', '', $request->nilai),
                'gaji' => str_replace('.', '', $request->gaji ?? 0),
                'keperluan' => $request->keperluan
            ]);
            $id = $id->id;

            if ($id > 0) {
                Session::flash('sukses', 'Data permohonan berhasil tersimpan. Selanjutnya silahkan upload dokumen persyaratan');
                return redirect('/pinjaman/' . $id);
            }
        }
    }

    public function update(Request $request)
    {
        $id = $request->id;
        $pinjaman = Pinjaman::find($id);
        $pinjaman->sumber = $request->sumber;
        $tenor = $request->tenor;
        $pinjaman->tenorfix = $tenor;
        $nilaipinjaman = str_replace('.', '', $request->nilaifix);
        $pinjaman->nilaifix = $nilaipinjaman;

        $pinjaman->catatan = $request->catatan;
        $pinjaman->angsuranfix = str_replace('.', '', $request->angsuran);
        $pinjaman->status = $request->keputusan;
        $pinjaman->tgl_awal = $this->EnglishTgl($request->awal);
        $pinjaman->tgl_akhir = $this->EnglishTgl($request->akhir);
        $pinjaman->save();

        $tgl_bayar = $this->EnglishTgl($request->awal);
        $tgl_bayar = strtotime($tgl_bayar);
        $ibulan = 0;

        if ($request->keputusan == 9) {
            $inputFileName = public_path('/assets/payroll/simulasifix.xlsx');

            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

            if ($tenor >= 12) {
                $workSheet = $spreadsheet->getSheetByName('simulasi2');
            } else {
                $workSheet = $spreadsheet->getSheetByName('simulasi3');
            }

            $spreadsheet->getActiveSheet()->setCellValue('D8', $tenor);
            $spreadsheet->getActiveSheet()->setCellValue('D6', str_replace('.', '', $nilaipinjaman));

            for ($i = 12; $i <= (11 + $tenor); $i++) {

                $angsuran = new Angsuran;

                $tgl_angsur = date("Y-m-d", strtotime("+" . $ibulan . " month", $tgl_bayar));
                $ibulan++;

                $angsuran->tgl_bayar = $tgl_angsur;
                $pokok = $nilaipinjaman - (($i - 11) * ($nilaipinjaman / $tenor));
                $angsuran->pokokfix = $pokok;
                $angsuran->pinjamanid = $id;
                $angsuran->bulan = $workSheet->getCell('C' . $i)->getValue();
                $angsuran->pokok = str_replace(',', '', number_format($workSheet->getCell('D' . $i)->getCalculatedValue(), 0));
                $angsuran->margin = str_replace(',', '', number_format($workSheet->getCell('E' . $i)->getCalculatedValue(), 0));
                $angsuran->angsuran = str_replace(',', '', number_format($workSheet->getCell('F' . $i)->getCalculatedValue(), 0));
                $angsuran->outstanding = str_replace(',', '', number_format($workSheet->getCell('G' . $i)->getCalculatedValue(), 0));
                $angsuran->save();
            }
        } else if ($request->keputusan == 9 && $request->sumber != 1) {
            // for ($i = 1; $i <= ($tenor); $i++) {

            //     $angsuran = new Angsuran;

            //     $tgl_angsur = date("Y-m-d", strtotime("+" . $ibulan . " month", $tgl_bayar));
            //     $ibulan++;

            //     $angsuran->tgl_bayar = $tgl_angsur;
            //     $pokok = $nilaipinjaman - (($i) * ($nilaipinjaman / $tenor));
            //     $angsuran->pokokfix = $pokok;
            //     $angsuran->pinjamanid = $id;
            //     $angsuran->bulan = $i;
            //     $angsuran->angsuran = str_replace('.', '', $request->angsuran);
            //     $angsuran->save();
            // }
        }

        Session::flash('sukses', 'Data pinjaman berhasil diupdate. ');

        return redirect('/pinjaman/show/' . $id);
    }

    public function updateAngsuran(Request $request)
    {

        $id = $request->id;
        $nik = $request->nik;
        $nilai_angsuran = Angsuran::join('pinjaman', 'pinjaman.id', 'angsuran.pinjamanid')
            ->where('pinjamanid', $id)
            ->where('angsuran.status', 1)
            ->select('angsuran.*', 'pinjaman.tenorfix')
            ->orderByRaw('bulan DESC')
            ->limit(1)
            ->first();

        if ($nilai_angsuran) {
            $tenor = $nilai_angsuran->tenorfix;
            $bulan_terakhir = $nilai_angsuran->bulan;
            $jumlah_angsuran = $nilai_angsuran->angsuran;
            $bayar = str_replace('.', '', $request->nilai);
            $jumlahbulan = ceil($bayar / $jumlah_angsuran);
        } else {
            $nilai_angsuran = Pinjaman::where('id', $id)->first();
            $tenor = $nilai_angsuran->tenorfix;
            $bulan_terakhir = 0;
            $jumlah_angsuran = $nilai_angsuran->angsuranfix;
            $bayar = str_replace('.', '', $request->nilai);
            $jumlahbulan = ceil($bayar / $jumlah_angsuran);
        }

        $idangsuran = BayarAngsuran::create([
            'pinjamanid' => $id,
            'jumlah' => $bayar,
            'tgl_bayar' => $this->EnglishTgl($request->tglbayar),
            'userid' => Auth::user()->id,
            'jumlah_bulan' => $request->jumlahbulan,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        $idangsuran = $idangsuran->id;

        if ($idangsuran > 0) {
            if ($bulan_terakhir < $tenor) {
                for ($i = 1; $i <= $jumlahbulan; $i++) {
                    
                    // $update = Angsuran::where('pinjamanid', $id)
                    //     ->where('bulan', $bulan_terakhir + $i)
                    //     ->update(['status' => 1, 'jumlah' => $bayar - ($jumlah_angsuran * ($jumlahbulan - $i)), 'userid' => Auth::user()->id, 'bayarangsuranid' => $idangsuran]);
                    
                    $update = Angsuran::where('pinjamanid', $id)->where('bulan', $bulan_terakhir + $i)->first();
                    $update->status = 1;
                    $update->jumlah =  $bayar - ($jumlah_angsuran * ($jumlahbulan - $i));
                    $update->userid = Auth::user()->id;
                    $update->bayarangsuranid = $idangsuran;
                    $update->save();

                    $bayar -= $jumlah_angsuran;
                }
            } else {
                for ($i = 1; $i <= $jumlahbulan; $i++) {

                    $insert = new Angsuran();
                    $insert->pinjamanid = $id;
                    $insert->bulan = $bulan_terakhir + $i;
                    $insert->tgl_bayar = date("Y-m-d", strtotime("+" . $i . " month", strtotime($nilai_angsuran->tgl_bayar)));
                    $insert->status = 1;
                    $insert->jumlah = $bayar;
                    $insert->userid = Auth::user()->id;
                    $insert->bayarangsuranid = $idangsuran;
                    $insert->save();

                    $bayar -= $jumlah_angsuran;
                }
            }

            $ceklunas = DB::select("select  (case when sum(angsuran) <= sum(jumlah) then 1 else 0 end) as lunas  from angsuran  where pinjamanid = " . $id . " group by pinjamanid");
            if ($ceklunas[0]->lunas == 1) {
                $updatepinjaman = Pinjaman::where('id', $id)->update(['status' => 8, 'updated_at' => date('Y-m-d H:i:s')]);
            }

            //cek nik anggota atau karyawan ?
            $pengurusx = Pengurus::where('nik', $nik)->first();
            if ($pengurusx) {
                $sipeminjam = 'karyawan';
            } else {
                $pengelolax = Pengelola::where('nik', $nik)->first();
                if ($pengelolax) {
                    $sipeminjam = 'karyawan';
                } else {
                    $sipeminjam = 'anggota';
                }
            }

            $akun =  $sipeminjam == 'karyawan' ? MyHelpers::akunMapping('angsuran_karyawan') : MyHelpers::akunMapping('angsuran_anggota');
            $akunbank = MyHelpers::akunMapping('angsuran_pinjaman');
            //insert transaksi
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tglbayar), 'angsuran_pinjaman', $idangsuran, $akun, 0, str_replace('.', '', $request->nilai), 1);
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tglbayar), 'angsuran_pinjaman', $idangsuran, $akunbank, str_replace('.', '', $request->nilai), 0, 0);
        }
        Session::flash('sukses', 'Data pembayaran ANGSURAN berhasil disimpan. ');
        return redirect()->route('showAngsuran', $idangsuran);
    }

    public function updatePelunasan(Request $request)
    {

        $id = $request->id;
        $nik = $request->nik;
        $nilaibayar = str_replace('.', '', $request->nilai);

        $pinjaman = Pinjaman::where('id', $id)
            ->select('id', 'angsuranfix', 'tenorfix as tenor')
            ->first();

        $angsuran = Angsuran::where('pinjamanid', $id)->get();

        $sisapinjaman = 0;
        $sisabulan = 0;
        $outstanding = 0;
        foreach ($angsuran as $a) {
            if ($a->angsuran > $a->jumlah) {
                $sisapinjaman += $a->angsuran - $a->jumlah;
                $outstanding = $a->outstanding > $outstanding ? $a->outstanding : $outstanding;
                $sisabulan++;
            }
        }
        $bulan_pelunasan = $pinjaman->tenor - $sisabulan;
        if ($nilaibayar >= $outstanding) {
            $idpelunasan = Pelunasan::create([
                'pinjamanid' => $id,
                'jumlah' => $nilaibayar,
                'tgl_bayar' => $this->EnglishTgl($request->tglbayar),
                'userid' => Auth::user()->id,
                'sisaangsuran' => $sisabulan,
                'sisapinjaman' => $outstanding,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]);
            $idpelunasan = $idpelunasan->id;

            if ($idpelunasan > 0) {
                $updateangsuran = Angsuran::where('pinjamanid', $id)->where('bulan', $bulan_pelunasan + 1)
                    ->update(['jumlah' => $nilaibayar, 'status' => 1, 'userid' => Auth::user()->id, 'pelunasanid' => $idpelunasan]);
                $updateangsuran = Angsuran::where('pinjamanid', $id)->where('bulan', '>', $bulan_pelunasan + 1)
                    ->update(['jumlah' => 0, 'status' => 1, 'userid' => Auth::user()->id, 'pelunasanid' => $idpelunasan]);
                $updatepinjaman = Pinjaman::where('id', $id)->update(['status' => 8]);  //status LUNAS

                //cek nik anggota atau karyawan ?
                $pengurusx = Pengurus::where('nik', $nik)->first();
                if ($pengurusx) {
                    $sipeminjam = 'karyawan';
                } else {
                    $pengelolax = Pengelola::where('nik', $nik)->first();
                    if ($pengelolax) {
                        $sipeminjam = 'karyawan';
                    } else {
                        $sipeminjam = 'anggota';
                    }
                }

                $akun =  $sipeminjam == 'karyawan' ? MyHelpers::akunMapping('angsuran_karyawan') : MyHelpers::akunMapping('angsuran_anggota');
                $akunbank = MyHelpers::akunMapping('angsuran_pinjaman');
                //insert transaksi
                MyHelpers::inputTransaksi($this->EnglishTgl($request->tglbayar), 'pelunasan_pinjaman', $idpelunasan, $akun, 0, str_replace('.', '', $request->nilai), 1);
                MyHelpers::inputTransaksi($this->EnglishTgl($request->tglbayar), 'pelunasan_pinjaman', $idpelunasan, $akunbank, str_replace('.', '', $request->nilai), 0, 0);
            }
            Session::flash('sukses', 'Data pembayaran PELUNASAN berhasil disimpan. ');
        } else {
            Session::flash('warning', 'Jumlah pembayaran KURANG dari sisa pinjaman (outstanding) yang akan dilunasi.');
        }

        return redirect()->route('showPelunasan', array('id' => $id, 'nilai' => $nilaibayar));
    }

    public function showPelunasan($id, $nilaibayar = 0)
    {
        
        $pelunasan = Pelunasan::join('pinjaman', 'pinjaman.id', 'pelunasan.pinjamanid')
            ->where('pelunasan.pinjamanid', $id)
            ->select('pelunasan.jumlah', 'pinjaman.nik')
            ->first();
        if ($pelunasan) {
            $nik = $pelunasan->nik;
            $jumlah = $pelunasan->jumlah;
        } else {
            $pinjaman = Pinjaman::where('id', $id)->first();
            $nik = $pinjaman->nik;
            $jumlah = $nilaibayar;
        }

        $requestx['id'] = $nik;
        $requestx['pinjamanid'] = $id;
        $requestx['nilai'] = $jumlah;
        $requestx['kode'] = 'pelunasan';
        $requestx['show'] = 'pembayaran';
        $request = new Request($requestx);
        $view = $this->info($request);

        $info = ['view' => $view, 'kode' => 'pelunasan'];

        return $this->bayarPelunasan($info);
    }

    public function showAngsuran($id)
    {

        $angsuran = BayarAngsuran::join('pinjaman', 'pinjaman.id', 'bayar_angsuran.pinjamanid')
            ->where('bayar_angsuran.id', $id)
            ->select('bayar_angsuran.jumlah', 'bayar_angsuran.jumlah_bulan', 'pinjaman.nik', 'bayar_angsuran.pinjamanid')
            ->first();

        $requestx['id'] = $angsuran->nik;
        $requestx['pinjamanid'] = $angsuran->pinjamanid;
        $requestx['nilai'] = $angsuran->jumlah;
        $requestx['bulan'] = $angsuran->jumlah_bulan;
        $requestx['kode'] = 'angsuran';
        $requestx['show'] = 'angsuran';
        $request = new Request($requestx);
        $view = $this->info($request);

        $info = ['view' => $view, 'kode' => 'angsuran'];

        return $this->bayarAngsuran($info);
    }

    public function getSimpanan($id)
    {
        $data = Simpanan::join('anggota', 'anggota.id', 'simpanan.anggotaid')
            ->select('simpanan.*', 'anggota.nama as nama_anggota', 'anggota.nik', 'anggota.nomor')
            ->where('simpanan.anggotaid', $id)
            ->orderByRaw('simpanan.bulan DESC')
            ->get();

        return view('pinjaman.tabel_simpanan', compact('data'));
    }

    public function getPinjaman($id)
    {
        $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->where('pinjaman.status', 9)
            ->where('anggota.id', $id)
            ->select('pinjaman.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber')
            ->orderByRaw('pinjaman.id DESC')
            ->get();


        return view('pinjaman.tabel_pinjaman', compact('data'));
    }

    public function daftarPeminjam()
    {
        if (Auth::user()->role == 'anggota') {
            $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
                ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
                ->where('pinjaman.status', '>', 8)
                ->where('anggota.nik', substr(Auth::user()->email, 0, 6))
                ->select('pinjaman.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber')
                ->orderByRaw('anggota.nik', 'pinjaman.id')
                ->get();
        } else {
            if (UserAkses::cek_akses('pinjaman', 'lihat') == false) return redirect(route('noakses'));

            $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
                ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
                ->where('pinjaman.status', 9)
                ->select('pinjaman.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber')
                ->orderByRaw('anggota.nik', 'pinjaman.id')
                ->get();
        }

        $angsuran = DB::select("SELECT angsuran.* from angsuran join (select pinjamanid, max(bulan) as bulan from angsuran where  status = 1 group by pinjamanid ) a on a.pinjamanid = angsuran.pinjamanid and angsuran.bulan = a.bulan
        UNION select angsuran.* from angsuran where bulan = 1 and (status is null or status = 0)");

        $anggota = Anggota::orderBy('nama')->get();

        $sumber = SumberPinjaman::all();

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'Daftar Peminjam', 'menuurl' => 'daftarPeminjam', 'modal' => 'true'];
        return view('pinjaman.daftar_pinjaman', compact('tag', 'data', 'angsuran', 'anggota', 'sumber'));
    }

    public function daftarPelunasan()
    {
        if (UserAkses::cek_akses('pelunasan', 'lihat') == false) return redirect(route('noakses'));

        $data = Pelunasan::join('pinjaman', 'pinjaman.id', 'pelunasan.pinjamanid')
            ->join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->select('pelunasan.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber', 'pinjaman.nilaifix', 'pinjaman.tenorfix')
            ->orderByRaw('pelunasan.id DESC')
            ->get();

        $sumber = SumberPinjaman::all();


        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pelunasan', 'judul' => 'DAFTAR PELUNASAN', 'menuurl' => 'daftarPelunasan', 'modal' => 'true'];
        return view('pinjaman.daftar_pelunasan', compact('tag', 'data', 'sumber'));
    }

    public function daftarAngsuran()
    {
        if (UserAkses::cek_akses('angsuran', 'lihat') == false) return redirect(route('noakses'));

        $data = BayarAngsuran::join('pinjaman', 'pinjaman.id', 'bayar_angsuran.pinjamanid')
            ->join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->select('bayar_angsuran.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber', 'pinjaman.nilaifix', 'pinjaman.tenorfix')
            ->orderByRaw('bayar_angsuran.id DESC')
            ->get();

        $sumber = SumberPinjaman::all();

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pembayaran Angsuran', 'judul' => 'DAFTAR PEMBAYARAN ANGSURAN', 'menuurl' => 'daftarAngsuran', 'modal' => 'true'];
        return view('pinjaman.daftar_angsuran', compact('tag', 'data', 'sumber'));
    }

    public function destroyAngsuran($id)
    {
        if (UserAkses::cek_akses('angsuran', 'cud') == false) return redirect(route('noakses'));

        $angsuran = Angsuran::where('bayarangsuranid', $id)
            ->update(['jumlah' => 0, 'status' => null, 'bayarangsuranid' => null]);

        $destroy = BayarAngsuran::destroy($id);
        return redirect()->back();
    }

    public function destroyPinjaman($id)
    {

        if (UserAkses::cek_akses('pinjaman', 'cud') == false) return redirect(route('noakses'));


        $angsuran = Angsuran::where('pinjamanid', $id)->where('status', 1)->get();

        if (count($angsuran) > 0) {
            Session::flash('warning', 'Data Pinjaman Aktif, pinjaman ini tidak dapat DIHAPUS');
        } else {
            $destroy = Pinjaman::destroy($id);
            $destroy = Angsuran::where('pinjamanid', $id)->delete();
            Session::flash('sukses', 'Data Pinjaman berhasil dihapus. ');
        }

        return redirect()->back();
    }

    public function destroyPelunasan($id)
    {
        if (UserAkses::cek_akses('pelunasan', 'cud') == false) return redirect(route('noakses'));

        $angsuran = Angsuran::where('pelunasanid', $id)
            ->update(['jumlah' => 0, 'status' => null, 'pelunasanid' => null]);

        $destroy = Pelunasan::destroy($id);
        return redirect()->back();
    }

    public function filter(Request $request)
    {
        $anggotaid_f = $request->f_anggota;
        $sumber_f = $request->f_sumber;
        $bulan_f = $request->f_bulan;
        $tahun_f = $request->f_tahun;
        $tahun_f = $tahun_f == '' ? date('Y') : $tahun_f;

        $tgl = date('Y-m-d', strtotime($tahun_f . '-' . $bulan_f . '-1'));

        $arr_q = array();
        $w = '';
        if ($sumber_f == '' || $sumber_f == 'all') {
            //1
        } else {
            $arr_q[] = array('pinjaman.sumber', '=', $sumber_f);
        }
        if ($anggotaid_f == '' || $anggotaid_f == 'all') {
            //1
        } else {
            $arr_q[] = array('pinjaman.nik', $anggotaid_f);
        }

        $arr_q[] = array('pinjaman.status', 9);
        $arr_q[] = array('pinjaman.tgl_awal', '<=', $tgl);
        $arr_q[] = array('pinjaman.tgl_akhir', '>=', $tgl);

        // $arr_q[] = array(function ($query) use($tgl) {
        //             $query->whereBetween($tgl,array('pinjaman.tgl_awal','pinjaman.tgl_akhir'));
        //             } );

        $data = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->where($arr_q)
            ->select('pinjaman.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber')
            ->orderByRaw('anggota.nik', 'pinjaman.id')
            ->get();

        $anggota = Anggota::orderBy('nama')->get();
        $sumber = SumberPinjaman::all();

        // $angsuran = DB::select("select angsuran.* from angsuran join (select pinjamanid, bulan from angsuran where status = 1 and month(tgl_bayar) = " . $bulan_f . " and year(tgl_bayar) = " . $tahun_f . " group by pinjamanid, bulan) a on a.pinjamanid = angsuran.pinjamanid and a.bulan = angsuran.bulan");

        $angsuran = DB::select("SELECT angsuran.* from angsuran join (select pinjamanid, max(bulan) as bulan from angsuran where  status = 1 group by pinjamanid ) a on a.pinjamanid = angsuran.pinjamanid and angsuran.bulan = (a.bulan + 1)
        UNION select angsuran.* from angsuran where bulan = 1 and (status is null or status = 0)");

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'Daftar Peminjam', 'menuurl' => 'daftarPeminjam', 'modal' => 'true'];
        return view('pinjaman.daftar_pinjaman', compact('data', 'tag', 'sumber_f',  'anggotaid_f', 'anggota', 'sumber', 'angsuran', 'tahun_f', 'bulan_f'));
    }

    public function filterPelunasan(Request $request)
    {
        $sumber_f = $request->f_sumber;
        $bulan_f = $request->f_bulan;
        $tahun_f = $request->f_tahun;
        $tahun_f = $tahun_f == '' ? date('Y') : $tahun_f;

        $tgl = date('Y-m-d', strtotime($tahun_f . '-' . $bulan_f . '-1'));

        $arr_q = array();
        $w = '';
        if ($sumber_f == '' || $sumber_f == 'all') {
            //1
        } else {
            $arr_q[] = array('pinjaman.sumber', '=', $sumber_f);
        }

        $arr_q[] = array(DB::raw('month(pelunasan.tgl_bayar)'), $bulan_f);
        $arr_q[] = array(DB::raw('year(pelunasan.tgl_bayar)'), $tahun_f);

        // $arr_q[] = array(function ($query) use($tgl) {
        //             $query->whereBetween($tgl,array('pinjaman.tgl_awal','pinjaman.tgl_akhir'));
        //             } );

        $data = Pelunasan::join('pinjaman', 'pinjaman.id', 'pelunasan.pinjamanid')
            ->join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->where($arr_q)
            ->select('pelunasan.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber', 'pinjaman.nilaifix', 'pinjaman.tenorfix')
            ->orderByRaw('pelunasan.id')
            ->get();

        $sumber = SumberPinjaman::all();

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pelunasan', 'judul' => 'DAFTAR PELUNASAN', 'menuurl' => 'daftarPelunasan', 'modal' => 'true'];
        return view('pinjaman.daftar_pelunasan', compact('data', 'tag', 'sumber_f', 'sumber', 'tahun_f', 'bulan_f'));
    }

    public function filterAngsuran(Request $request)
    {
        $sumber_f = $request->f_sumber;
        $bulan_f = $request->f_bulan;
        $tahun_f = $request->f_tahun;
        $tahun_f = $tahun_f == '' ? date('Y') : $tahun_f;

        $tgl = date('Y-m-d', strtotime($tahun_f . '-' . $bulan_f . '-1'));

        $arr_q = array();
        $w = '';
        if ($sumber_f == '' || $sumber_f == 'all') {
            //1
        } else {
            $arr_q[] = array('pinjaman.sumber', '=', $sumber_f);
        }

        $arr_q[] = array(DB::raw('month(bayar_angsuran.tgl_bayar)'), $bulan_f);
        $arr_q[] = array(DB::raw('year(bayar_angsuran.tgl_bayar)'), $tahun_f);

        // $arr_q[] = array(function ($query) use($tgl) {
        //             $query->whereBetween($tgl,array('pinjaman.tgl_awal','pinjaman.tgl_akhir'));
        //             } );

        $data = BayarAngsuran::join('pinjaman', 'pinjaman.id', 'bayar_angsuran.pinjamanid')
            ->join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->select('bayar_angsuran.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber', 'pinjaman.nilaifix', 'pinjaman.tenorfix')
            ->where($arr_q)
            ->orderByRaw('bayar_angsuran.id DESC')
            ->get();

        $sumber = SumberPinjaman::all();
        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pembayaran Angsuran', 'judul' => 'DAFTAR PEMBAYARAN ANGSURAN', 'menuurl' => 'daftarAngsuran', 'modal' => 'true'];

        return view('pinjaman.daftar_angsuran', compact('data', 'tag', 'sumber_f', 'sumber', 'tahun_f', 'bulan_f'));
    }

    public function daftarTunggakan()
    {
        if (UserAkses::cek_akses('tunggakan', 'cud') == false) return redirect(route('noakses'));

        $data = DB::select('SELECT TIMESTAMPDIFF(MONTH,tgl_bayar,date(now())) as bulantunggak, angsuran.* from angsuran join (select pinjamanid, max(bulan) as bulan from angsuran where  status = 1 group by pinjamanid
            ) a on a.pinjamanid = angsuran.pinjamanid and a.bulan = angsuran.bulan
    UNION
    SELECT TIMESTAMPDIFF(MONTH,tgl_bayar,date(now())) + 1 as bulantunggak, angsuran.* from angsuran where bulan = 1 and pinjamanid in (select pinjamanid from angsuran where bulan = 1 and (status is null or status = 0))');

        $pinjaman = Pinjaman::join('anggota', 'anggota.nik', 'pinjaman.nik')
            ->join('sumber_pinjaman', 'sumber_pinjaman.id', 'pinjaman.sumber')
            ->where('pinjaman.status', 9)
            ->select('pinjaman.*', 'anggota.nama', 'anggota.nik', 'sumber_pinjaman.nama as namasumber')
            ->orderByRaw('anggota.nik', 'pinjaman.id')
            ->get();

        $anggota = Anggota::orderBy('nama')->get();
        $sumber = SumberPinjaman::all();

        $tag = ['menu' => 'Simpan Pinjam', 'submenu' => 'Pinjaman', 'judul' => 'DAFTAR TUNGGAKAN', 'menuurl' => 'daftarTunggakan', 'modal' => 'true'];
        return view('pinjaman.daftar_tunggakan', compact('tag', 'data', 'anggota', 'sumber', 'pinjaman'));
    }

    function EnglishTgl($tanggal)
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
