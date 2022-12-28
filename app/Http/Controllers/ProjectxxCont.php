<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelpers;
use App\Helpers\UserAkses;
use App\Invoice;
use App\Pajak;
use App\Panjar;
use App\Panjar_detail;
use App\Panjar_setuju;
use App\Pembayaran;
use App\Pemesan;
use App\Pengelola;
use App\Pengurus;
use App\Perusahaan;
use App\Project;
use App\ProjectAM;
use App\ProjectItem;
use App\Target;
use App\Transaksi;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProjectCont extends Controller
{
    //


    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (UserAkses::cek_akses('project', 'lihat') == false) return redirect(route('noakses'));

        // $data = DB::select('SELECT p.*, perusahaan.alias as perusahaan, pengelola.nama as pic, 
        // (SELECT sum(pi.total) FROM project pp left join projectitem pi on pp.id = pi.projectid where pp.id = p.id) as subtotal ,
        // (SELECT sum(pi.ppn) FROM project pp left join projectitem pi on pp.id = pi.projectid where pp.id = p.id) as pajak,
        // (SELECT sum(pb.nilai) as bayar from project pp left join invoice i on pp.id = i.projectid left join pembayaran pb on pb.invoiceid = i.id where pp.id = p.id) as pembayaran
        // from project p inner join perusahaan on p.perusahaanid = perusahaan.id left join pengelola on pengelola.id = p.pic order by p.id DESC');

        $data = DB::select('SELECT p.*, perusahaan.alias as perusahaan, perusahaan.unitkerja, pp.nama as pemesan, pengelola.nama as pic, ((p.nilai * 100)/110 * 0.1 ) as pajak, ((p.nilai * 100)/110) as subtotal,
        (SELECT sum(pb.nilai) as bayar from project pp left join invoice i on pp.id = i.projectid left join pembayaran pb on pb.invoiceid = i.id where pp.id = p.id) as pembayaran
        from project p inner join perusahaan on p.perusahaanid = perusahaan.id left join project_pemesan pp on pp.id = p.pemesanid left join pengelola on pengelola.id = p.pic order by p.id DESC');

        $perusahaan = Perusahaan::all();
        $pengelola = Pengelola::all();
        $tag = ['menu' => 'Project', 'submenu' => 'Daftar Project', 'judul' => 'Daftar Project', 'menuurl' => 'project', 'modal' => 'true'];
        return view('project.index', compact('tag', 'data', 'perusahaan', 'pengelola'));
    }

    public function filterProject(Request $request)
    {

        $tgl = $request->tglFilter;
        $anggotaid_f = $request->f_anggota;
        $ck_tanggal = $request->ck_tanggal;

        $f_perusahaan = $request->f_perusahaan;
        $f_pengelola = $request->f_pengelola;
        $f_pospk = $request->f_pospk;

        $w = '1=1';
        if ($f_perusahaan == '' || $f_perusahaan == 'all') {
            //1
        } else {
            $w = " p.perusahaanid = " . $f_perusahaan;
        }
        if ($f_pengelola == '' || $f_pengelola == 'all') {
            //1
        } else {
            $w .= $w == '' ? ' pengelola.id = ' . $f_pengelola : ' AND pengelola.id = ' . $f_pengelola;
        }

        if ($f_pospk == '' || $f_pospk == 'all') {
            //1
        } else  if ($f_pospk == '1') {
            //ada po
            $w .= " AND p.no_po > '' AND  TRIM(COALESCE(p.no_spk, '')) = ''  ";
        } else  if ($f_pospk == '2') {
            //ada spk
            $w .= " AND p.no_spk > '' AND  TRIM(COALESCE(p.no_po, '')) = ''  ";
        } else  if ($f_pospk == '3') {
            $w .= " AND p.no_po > '' AND  p.no_spk > ''  ";
        } else  if ($f_pospk == '4') {
            $w .= " AND TRIM(COALESCE(p.no_spk, '')) = '' AND  TRIM(COALESCE(p.no_po, '')) = ''  ";
        }

        if ($ck_tanggal) {
            $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
            $tgl2 =  $this->EnglishTgl(substr($tgl, -10));

            $filter_periode = " ((p.tgl_po BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "') OR (p.tgl_spk BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "'))";
            $w .= $w == '' ? $filter_periode : ' AND ' . $filter_periode;
        }

        // $data = DB::select('SELECT distinct p.*, perusahaan.alias as perusahaan, pengelola.nama as pic, 
        // (SELECT sum(pi.total)   FROM project pp left join projectitem pi on pp.id = pi.projectid where pp.id = p.id) as subtotal ,
        // (SELECT sum(pi.ppn)   FROM project pp left join projectitem pi on pp.id = pi.projectid where pp.id = p.id) as pajak,
        // (SELECT sum(pb.nilai) as bayar from project pp left join invoice i on pp.id = i.projectid left join pembayaran pb on pb.invoiceid = i.id where pp.id = p.id) as pembayaran
        // from project p inner join perusahaan on p.perusahaanid = perusahaan.id left join pengelola on pengelola.id = p.pic where ' . $w . ' order by p.id DESC');

        $data = DB::select('SELECT p.*, perusahaan.alias as perusahaan, perusahaan.unitkerja, pp.nama as pemesan, pengelola.nama as pic, ((p.nilai * 100)/110 * 0.1 ) as pajak, ((p.nilai * 100)/110) as subtotal,
        (SELECT sum(pb.nilai) as bayar from project pp left join invoice i on pp.id = i.projectid left join pembayaran pb on pb.invoiceid = i.id where pp.id = p.id) as pembayaran
        from project p inner join perusahaan on p.perusahaanid = perusahaan.id left join project_pemesan pp on pp.id = p.pemesanid left join pengelola on pengelola.id = p.pic where ' . $w . ' order by p.id DESC');


        $perusahaan = Perusahaan::all();
        $pengelola = Pengelola::all();
        $tag = ['menu' => 'Project', 'submenu' => 'Daftar Project', 'judul' => 'Daftar Project', 'menuurl' => 'project', 'modal' => 'true'];
        return view('project.index', compact('tag', 'data', 'perusahaan', 'pengelola', 'f_pengelola', 'f_perusahaan', 'f_pospk', 'ck_tanggal', 'tgl'));
    }

    public function batalInvoice($id)
    {
        if (UserAkses::cek_akses('invoice', 'cud') == false) return 'Maaf, Anda tidak memiliki akses !';
        
        $data = Invoice::find($id);
        return view('project.formInvoiceBatal', compact('data'));
    }

    public function filterInvoice(Request $request)
    {

        $tgl = $request->tglFilter;
        $anggotaid_f = $request->f_anggota;
        $ck_tanggal = $request->ck_tanggal;

        $f_perusahaan = $request->f_perusahaan;
        $f_status = $request->f_status;

        $w = 'i.jenis = 1';
        if ($f_perusahaan == '' || $f_perusahaan == 'all') {
            //1
        } else {
            $w .= " AND p.perusahaanid = " . $f_perusahaan;
        }
        if ($f_status == '' || $f_status == 'all') {
            //1
        } else {
            $w .= $w == '' ? ' i.status = ' . $f_status : ' AND i.status = ' . $f_status;
        }

        if ($ck_tanggal) {
            $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
            $tgl2 =  $this->EnglishTgl(substr($tgl, -10));

            $filter_periode = " (i.tanggal BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "')";
            $w .= $w == '' ? $filter_periode : ' AND ' . $filter_periode;
        }

        // $data = DB::select("SELECT invoice.*, project.nama, perusahaan.nama as perusahaan from invoice inner join project on project.id = invoice.projectid inner join perusahaan on project.perusahaanid = perusahaan.id where " . $w);
        $data = DB::select('SELECT i.*, p.nama,pp.nama as pemesan, p.no_po, p.no_spk, perusahaan.alias as perusahaan, perusahaan.unitkerja, pengelola.nama as pic, 
        (SELECT sum(pi.total) FROM project pp left join projectitem pi on pp.id = pi.projectid where pp.id = p.id) as subtotal ,
        (SELECT sum(pi.ppn) FROM project pp left join projectitem pi on pp.id = pi.projectid where pp.id = p.id) as pajak
        from invoice i inner join project p on i.projectid = p.id inner join perusahaan on p.perusahaanid = perusahaan.id left join  pengelola on pengelola.id = p.pic left join project_pemesan pp on pp.id = p.pemesanid where ' . $w . ' order by p.id DESC');
        $perusahaan = Perusahaan::all();

        $tag = ['menu' => 'Project', 'submenu' => 'Invoice', 'judul' => 'Daftar Invoice', 'menuurl' => 'invoice', 'modal' => 'true'];
        return view('project.indexInvoice', compact('tag', 'data', 'perusahaan', 'tgl', 'ck_tanggal', 'f_perusahaan', 'f_status'));
    }

    public function create()
    {

        if (UserAkses::cek_akses('project', 'cud') == false) return redirect(route('noakses'));

        $perusahaan = Perusahaan::all();
        $pengelola = Pengelola::where('status', 1)->get();
        $pemesan = Pemesan::where('status', 1)->get();
        $pajak = Pajak::where('nama', 'like', DB::raw("'%pph%'"))->get();

        $tag = ['menu' => 'Project', 'submenu' => 'Input Project', 'judul' => 'INPUT PROJECT', 'menuurl' => 'project', 'modal' => 'true'];
        $kodeubah = 3;
        return view('project.create', compact('tag', 'perusahaan', 'pengelola', 'kodeubah', 'pajak', 'pemesan'));
    }

    public function createAM($projectid)
    {

        $pengelola = Pengelola::where('status', 1)->get();
        return view('project.formAM', compact('pengelola', 'projectid'));
    }

    public function showAM($id)
    {
        $data = ProjectAM::find($id);
        $pengelola = Pengelola::where('status', 1)->get();

        return view('project.formAM', compact('data', 'pengelola'));
    }

    public function updateAM(Request $request)
    {
        $id = $request->id;
        if ($id > 0) {
            $am = ProjectAM::find($id);
            $am->projectid = $request->projectid;
            $am->pengelolaid = $request->pengelola;
            $am->porsi = $request->porsi;
            $am->save();
        } else {
            $id = ProjectAM::insertGetId([
                'projectid' => $request->projectid,
                'porsi' => $request->porsi,
                'pengelolaid' => $request->pengelola
            ]);
        }
        if ($id > 0) {
            Session::flash('sukses', 'Data Pelaksana AM berhasil disimpan');
        } else {
            Session::flash('warning', 'Data gagal disimpan');
        }
        return redirect()->back();
    }

    public function destroyAM($id)
    {
        ProjectAM::destroy($id);
        return redirect()->back();
    }

    public function store(Request $request)
    {
        if (UserAkses::cek_akses('project', 'cud') == false) return redirect(route('noakses'));

        $projectid = $request->id;
        $data = [
        'id' => $request->id,    
        'perusahaanid' => $request->perusahaan,
        'nama' => $request->nama,
        'no_po' => $request->no_po,
        'tgl_po' => $this->EnglishTgl($request->tgl_po),
        'no_spk' => $request->no_spk,
        'tgl_spk' => $this->EnglishTgl($request->tgl_spk),
        'keuntungan' => str_replace(',', '.', $request->keuntungan),
        'nilai' => str_replace(',', '.', $this->Angkapolos($request->nilai)),
        'lamapekerjaan' => $this->Angkapolos($request->lamapekerjaan),
        'pemesanid' => $request->pemesan,
        'pic' => $request->pic,
        'paket' => $request->paket,
        'status' => 1];

        if($request->no_spk != ''){
            if($projectid==''){
                $nopo = Project::where('no_spk',$request->no_spk)->first();
            }else{
                $nopo = Project::where('no_spk',$request->no_spk)->where('id','<>',$projectid)->first();
            }
            if($nopo){
                Session::flash('warning', 'Data gagal disimpan, Nomor Pesanan / SPK sudah ada.');
                return redirect()->back()->with( $data );
            }
        }
        if ($projectid > 0) {
            $project = Project::find($projectid);
            $project->perusahaanid = $request->perusahaan;
            $project->nama = $request->nama;
            $project->no_po = $request->no_po;
            $project->tgl_po = $this->EnglishTgl($request->tgl_po);
            $project->no_spk = $request->no_spk;
            $project->tgl_spk = $this->EnglishTgl($request->tgl_spk);
            $project->pemesanid = $request->pemesan;
            $project->nilai =  str_replace(',', '.', $this->Angkapolos($request->nilai));
            $project->keuntungan = str_replace(',', '.', $request->keuntungan);
            $project->lamapekerjaan = $this->Angkapolos($request->lamapekerjaan);
            $project->paket = $request->paket;
            $project->pic = $request->pic;
            $project->save();
        } else {

            $projectid = Project::insertGetId($data);

            //insert AM
            if ($projectid > 0) {
                $id = ProjectAM::insertGetId([
                    'projectid' => $projectid,
                    'porsi' => 100,
                    'pengelolaid' => $request->pic
                ]);
            }
        }



        if ($projectid > 0) {
            Session::flash('sukses', 'Data Project berhasil disimpan');
            return redirect()->route('showProject', $projectid);
        } else {
            Session::flash('warning', 'Data Project gagal disimpan');
            return redirect()->back();
        }
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('project', 'cud') == false) return redirect(route('noakses'));

        Project::destroy($id);
        ProjectItem::where('projectid', $id)->delete();
        return redirect()->back();
    }

    public function show($id)
    {
        return $this->edit($id, 0, 1);
    }

    public function edit($id = 0, $kodeubah = 1, $kode = 0)
    {
        if ($kode == 0) {
            if (UserAkses::cek_akses('project', 'cud') == false) return redirect(route('noakses'));
        }
        $data = Project::leftjoin('invoice', 'invoice.projectid', 'project.id')
            ->select('project.*', 'invoice.id as invoiceid', 'invoice.nomor as invoicenomor')
            ->where('project.id', $id)
            ->first();
        $perusahaan = Perusahaan::all();
        $pengelola = Pengelola::where('status', 1)->get();
        $pemesan = Pemesan::where('status', 1)->get();
        $pajak = Pajak::where('nama', 'like', DB::raw("'%pph%'"))->get();

        // $item = ProjectItem::where('projectid', $id)->get();
        $item = ProjectItem::leftjoin('pajak', 'pajak.id', 'projectitem.pajakid')->where('projectid', $id)->select('projectitem.*', 'pajak.nama as pajak')->get();

        $tag = ['menu' => 'Project', 'submenu' => 'Detail Project', 'judul' => 'DETAIL PROJECT', 'menuurl' => 'project', 'modal' => 'true'];

        $am = ProjectAM::join('pengelola', 'pengelola.id', 'projectAM.pengelolaid')
            ->select('projectAM.*', 'pengelola.nama')
            ->where('projectid', $id, 'pengelola.id as pengelolaid')
            ->get();

        return view('project.create', compact('tag', 'data', 'perusahaan', 'pengelola', 'item', 'kodeubah', 'am', 'pajak', 'pemesan'));
    }

    public function createItem()
    {
        $pajak = Pajak::where('nama', 'like', DB::raw("'%pph%'"))->get();
        return view('project.formItem', compact('pajak'));
    }

    public function updateItem(Request $request)
    {

        $iditem = $request->txid;
        $iditem = explode(',', $iditem);

        $jmlkolom = $request->txkolom;
        $jmlbaris = $request->txbaris;
        $kolomjumlah = $request->txkolomjumlah;
        $kolomjumlah--;
        $projectid = $request->txprojectid;
        $namakolom = $request->txnamakolom;
        $kolomarr = explode(',', $namakolom);
        $subtotal = 0;
        for ($i = 0; $i < $jmlbaris; $i++) {
            $arr = array();
            $kolomnama = 'kolom' . ($i + 1) . '_nama';
            $kolomisi = 'kolom' . ($i + 1) . '_isi';
            $pajakid = 'pph' . ($i + 1);
            $pphnilaix = Pajak::where('id', $request->$pajakid)->get();

            $pphnilai = 0;
            if ($pphnilaix !== null) {
                foreach ($pphnilaix as $p) {
                    $pphnilai = $p->nilai;
                }
            }
            $pphjumlah = 0;
            $arr += ['projectid' => $projectid];
            $arr += ['kolomjumlah' => $kolomjumlah + 1];

            for ($j = 1; $j < $jmlkolom - 1; $j++) {

                $kolomnama = 'kolom' . ($j) . '_nama';
                $kolomisi = 'kolom' . ($j) . '_isi';
                $txnilai = 'tx' . $j . '_' . ($i + 1);
                $arr += [$kolomnama => $kolomarr[$j]];
                $arr += [$kolomisi => $this->cekNum($request->$txnilai)];
                //pph
                if (($j == ($kolomjumlah)) && ($kolomjumlah > 0)) {
                    $pphjumlah = $this->cekNum($request->$txnilai) * ($pphnilai / 100);
                    $subtotal += $this->cekNum($request->$txnilai);
                    $arr += ['pajakid' => $request->$pajakid];
                    $arr += ['pph' => $pphjumlah];
                }
            }

            if (isset($iditem[$i]) && $iditem[$i] > 0) {
                $update = ProjectItem::where('id', $iditem[$i])->update($arr);
            } else {
                $insert = ProjectItem::insert($arr);
            }

            $total = $subtotal + ($subtotal / 10);
            $update = Project::where('id', $projectid)->update(['nilai' => $total]);


            // $item = new ProjectItem;
            // $kolomnama = 'kolom'.($i+1).'_nama';
            // $kolomisi = 'kolom'.($i+1).'_isi';
            // $item->projectid = $projectid;
            // for($j=1; $j < $jmlkolom; $j++){
            //     $kolomnama = 'kolom'.($j).'_nama';
            //     $kolomisi = 'kolom'.($j).'_isi';
            //     $txnilai = 'tx'.$j.'_'.($i+1);
            //     $item->$kolomnama = $kolomarr[$j];
            //     $item->$kolomisi = $this->cekNum($request->$txnilai);
            // }
            // $item->save();

        }

        return redirect()->back();
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

    public function updateItem1(Request $request)
    {
        $pajak = Pajak::all();
        foreach ($pajak as $p) {
            $pa[$p->id] = $p->nilai;
        }
        $id = $request->id;
        if (!$id) {
            $item = new ProjectItem;
            $item->nama = str_replace('"', '``', $request->nama);
            $item->satuan = $request->satuan;
            $item->projectid = $request->projectid;
            $item->harga = str_replace('.', '', $request->harga);
            $item->jumlah = $request->jumlah;
            $item->total = str_replace('.', '', $request->total);
            $item->ppn = $request->pajak == 1 ? $item->total / 10 : 0;
            $item->pajakid = $request->pph;
            $item->pph = $request->pph > 0 ? $item->total / $pa[$request->pph] : 0;
            $item->save();
        } else {
            $item = ProjectItem::find($id);
            $item->nama = str_replace('"', '``', $request->nama);
            $item->satuan = $request->satuan;
            $item->projectid = $request->projectid;
            $item->harga = str_replace('.', '', $request->harga);
            $item->jumlah = $request->jumlah;
            $item->total = str_replace('.', '', $request->total);
            $item->pajakid = $request->pph;
            $item->pph = $request->pph > 0 ? $item->total * ($pa[$request->pph] / 100) : 0;
            $item->ppn = $request->pajak == 1 ? $item->total / 10 : 0;
            $item->save();
        }

        return redirect()->back();
    }

    public function editItem($id)
    {
        $data = ProjectItem::find($id);
        $pajak = Pajak::where('nama', 'like', DB::raw("'%pph%'"))->get();

        return view('project.formItem', compact('data', 'pajak'));
    }

    public function destroyItem(int $id)
    {
        ProjectItem::destroy($id);
        return redirect()->back();
    }

    public function invoice()
    {
        if (UserAkses::cek_akses('invoice', 'lihat') == false) return redirect(route('noakses'));


        $data = DB::select('SELECT i.*, p.nama, pp.nama as pemesan , p.no_po, p.no_spk, perusahaan.alias as perusahaan,perusahaan.unitkerja, pengelola.nama as pic, ((p.nilai * 100)/110 * 0.1 ) as pajak, ((p.nilai * 100)/110) as subtotal
            from invoice i inner join project p on i.projectid = p.id inner join perusahaan on p.perusahaanid = perusahaan.id left join pengelola on pengelola.id = p.pic left join project_pemesan pp on pp.id = p.pemesanid where i.jenis = 1 order by p.id DESC');

        $perusahaan = Perusahaan::all();

        $tag = ['menu' => 'Project', 'submenu' => 'Invoice', 'judul' => 'Daftar Invoice', 'menuurl' => 'invoice', 'modal' => 'true'];
        return view('project.indexInvoice', compact('tag', 'data', 'perusahaan'));
    }

    public function createInvoice()
    {
        if (UserAkses::cek_akses('invoice', 'cud') == false) return redirect(route('noakses'));

        $project = Project::where('status', 1)->orderByRaw('id DESC')->get();
        $jenisinvoice = 1;
        $tag = ['menu' => 'Project', 'submenu' => 'Input Invoice', 'judul' => 'INPUT INVOICE', 'menuurl' => 'invoice', 'modal' => 'true'];
        $kodeubah = 3;
        $bayarinvoice = 'null';
        return view('project.createInvoice', compact('tag', 'project', 'jenisinvoice', 'kodeubah', 'bayarinvoice'));
    }

    public function panjar()
    {
        if (UserAkses::cek_akses('panjar', 'lihat') == false) return redirect(route('noakses'));

        $data = Panjar::leftjoin('project', 'project.id', 'panjar.projectid')->leftjoin('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftjoin('invoice', function ($join) {
                $join->on('invoice.projectid', '=', 'project.id');
                $join->on('invoice.jenis', '=', DB::raw(2));
            })
            ->leftjoin('project_pemesan', 'project_pemesan.id', 'panjar.pemberiid')
            ->leftjoin('pembayaran', 'pembayaran.pannjarid', 'panjar.id')

            ->select('panjar.*', 'panjar.id as panjarid', 'panjar.nilai as nilaipanjar', 'project.*', 'project.nama as uraian', 'perusahaan.nama as perusahaan', 'project_pemesan.nama as penerima', 'panjar.status as statuspanjar', 'invoice.status as statusinvoice', DB::raw('(select sum(pd.nilai) from panjar_detail pd inner join project  p on pd.projectid = p.id   where panjarid = panjar.id group by pd.panjarid) as jumlahpenggunaan'))
            ->get();



        $tag = ['menu' => 'Project', 'submenu' => 'Daftar Panjar', 'judul' => 'DAFTAR PANJAR', 'menuurl' => 'panjar', 'modal' => 'true'];
        $kodeubah = 3;
        return view('project.indexPanjar', compact('tag', 'data'));
    }

    public function createPengunaanPanjar($id)
    {

        $spk = Project::where('status', 1)->get();
        return view('project.formPenggunaan', compact('spk'));
    }

    public function updatePenggunaanPanjar(Request $request)
    {
        $id = $request->id;
        if ($id > 0) {
            //update
            $panjar = Panjar_detail::find($id);
            $panjar->panjarid = $request->panjarid;
            $panjar->projectid = $request->spk;
            $panjar->nilai = $this->Angkapolos($request->nilai);
            $panjar->save();
        } else {
            $panjar = new Panjar_detail;
            $panjar->uraian = $request->uraian;
            $panjar->projectid = $request->spk;
            $panjar->panjarid = $request->panjarid;
            $panjar->nilai = $this->Angkapolos($request->nilai);
            $panjar->save();
        }

        return redirect()->back();
    }


    public function showPenggunaanPanjar($id)
    {
        $data = Panjar_detail::join('project', 'project.id', 'panjar_detail.projectid')
            ->select('project.nilai', 'panjar_detail.projectid', 'panjar_detail.id', 'panjar_detail.panjarid', 'project.pemesanid')
            ->where('panjar_detail.id', $id)
            ->first();
        // dd($data);

        $spk = Project::where('status', 1)->where('pemesanid', $data->pemesanid)->get();

        return view('project.formPenggunaan', compact('data', 'spk'));
    }

    public function destroyPenggunaanPanjar($id)
    {
        Panjar_detail::destroy($id);
        return redirect()->back();
    }
    public function createPanjar()
    {
        if (UserAkses::cek_akses('panjar', 'cud') == false) return redirect(route('noakses'));

        $project = Project::where('status', 1)->orderByRaw('id DESC')->get();
        $pengurusx = Pengelola::where('jabatan', 'manager')->where('status', 1)->select(DB::raw(99), 'nama');
        $pengurus = Pengurus::where('status', 1)->select('id', 'nama')->union($pengurusx)->get();

        $pemesan = Pemesan::all();
        $tag = ['menu' => 'Project', 'submenu' => 'Input Panjar', 'judul' => 'INPUT PANJAR', 'menuurl' => 'panjar', 'modal' => 'true'];
        $pengelola = Pengelola::where('status', 1)->get();

        $kodeubah = 3;
        return view('project.createPanjar', compact('tag', 'project', 'kodeubah', 'pemesan', 'pengelola', 'pengurus'));
    }

    public function infoProjectPanjar(Request $request)
    {
        $data = Project::join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->where('project.id', $request->f_project)
            ->select('project.*', 'perusahaan.nama as perusahaan')
            ->first();

        $project = Project::where('status', 1)->orderByRaw('id DESC')->get();
        $pengelola = Pengelola::where('status', 1)->get();
        $pengurusx = Pengelola::where('jabatan', 'manager')->where('status', 1)->select(DB::raw(99), 'nama');
        $pengurus = Pengurus::where('status', 1)->select('id', 'nama')->union($pengurusx)->get();
        $projectid_f = $request->f_project;
        $kodeubah = 3;
        $pemesan = Pemesan::all();
        $item = Project::join('projectitem', 'project.id', 'projectitem.projectid')
            ->select(DB::raw('sum(total+ppn) as nilai'))
            ->where('projectid', $request->f_project)
            ->groupby('project.id')
            ->first();
        $jenispanjar = "SPK";
        $tag = ['menu' => 'Project', 'submenu' => 'Input Panjar', 'judul' => 'INPUT PANJAR', 'menuurl' => 'panjar', 'modal' => 'true'];
        return view('project.createPanjar', compact('tag', 'data', 'projectid_f',  'project', 'kodeubah', 'item', 'pengelola', 'pemesan', 'pengurus','jenispanjar'));
    }

    public function infoProject(Request $request)
    {
        $data = Project::join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->where('project.id', $request->f_project)
            ->select('project.*', 'perusahaan.nama as perusahaan', DB::raw('DATE_ADD( tgl_spk, INTERVAL lamapekerjaan DAY ) as tgl_jatuhtempo') )
            ->first();
        
        $potongan = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')
            ->select(DB::raw('sum(pembayaran.nilai) as nilai'))
            ->where('invoice.projectid', $request->f_project)
            ->groupby('invoice.projectid')
            ->first();

        $daftarpembayaran = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')
            ->where('invoice.projectid', $request->f_project)
            ->select('pembayaran.*', 'invoice.nomor as nomorinv', 'invoice.jenis')
            ->get();

            $nomorinvoice = Invoice::select('nomor')->get();
        
        $nomor = array();
        foreach ($nomorinvoice as $key ) {
            $nomor[] = strtolower($key->nomor);

        }

        $nomor = json_encode($nomor);

        $invoice = Invoice::where('projectid', $request->f_project)->where('jenis', 1)->orderByRaw('id DESC')->limit(1)->first();
        $jenisinvoice = $invoice->jenis ?? 1;
        $item = ProjectItem::where('projectid', $request->f_project)->get();
        $project = Project::where('status', 1)->orderByRaw('id DESC')->get();
        $pengurusx = Pengelola::where('jabatan', 'manager')->where('status', 1)->select(DB::raw(99), 'nama');
        $pengurus = Pengurus::where('status', 1)->where('jabatan', '<>', 'bendahara')->select('id', 'nama')->union($pengurusx)->get();
        $projectid_f = $request->f_project;
        $bayarinvoice = $potongan->nilai ?? 'null';
        $kodeubah = 3;

        $tag = ['menu' => 'Project', 'submenu' => 'Input Invoice', 'judul' => 'INPUT INVOICE', 'menuurl' => 'invoice', 'modal' => 'true'];
        return view('project.createInvoice', compact('tag', 'data', 'projectid_f', 'project', 'item', 'pengurus', 'invoice', 'jenisinvoice', 'bayarinvoice', 'kodeubah', 'potongan', 'daftarpembayaran', 'nomor'));
    }

    public function updateInvoice(Request $request)
    {

        if (UserAkses::cek_akses('invoice', 'cud') == false) return redirect(route('noakses'));

        $invid = $request->id;
 
        if ($invid) {
            $inv = Invoice::find($invid);
            $inv->nomor = $request->nomor;
            $inv->tanggal = $this->EnglishTgl($request->tanggal);
            $inv->tgl_jatuhtempo = $this->EnglishTgl($request->tanggal_jatuhtempo);
            $inv->pegawaiid = $request->pengurus;
            $inv->ba = $request->ba;
            $inv->tanggalba = $this->EnglishTgl($request->tanggalba ?? $request->tanggal);
            // $inv->jenis = $request->jenis;
            // $inv->status = 1; //belum bayar
            $inv->total = $request->total;
            $inv->save();
        } else {
            $invid = Invoice::insertGetId([
                    'nomor' => $request->nomor,
                    'tanggal' => $this->EnglishTgl($request->tanggal),
                    'tgl_jatuhtempo' => $this->EnglishTgl($request->tanggal_jatuhtempo),
                    'pegawaiid' => $request->pengurus,
                    'projectid' => $request->projectid,
                    'jenis' => 1,
                    'total' => $request->total,
                    'ba' => $request->ba,
                    'tanggalba' => $this->EnglishTgl($request->tanggalba ?? $request->tanggal),
                    'status' => 1 //belum bayar
                ]);
        }
        if ($invid > 0) {

            //jurnal
            $project = Project::join('perusahaan','perusahaan.id','project.perusahaanid')->select('project.paket','perusahaan.akunid')->where('project.id',$request->projectid)->first();
            $jenisproject = $project->paket;
            $akunpiutang = $project->akunid;
            $akunpenjualan =  $jenisproject=='BARANG' ? MyHelpers::akunMapping('penjualan_barang') : MyHelpers::akunMapping('penjualan_jasa');
            $akunppn = MyHelpers::akunMapping('ppn_penjualan');
             
            // transaksi
            $item = ProjectItem::where('projectid',$request->projectid)->get();
            $nilai=$pph23=$pph22=0;
            foreach ($item as $row) {
                $kolomjumlah = $row->kolomjumlah;
                $isikolom = 'kolom' . ($kolomjumlah - 1) . '_isi';
                $nilai += $row->$isikolom;
                $pph22 += $row->pajakid == 3 ? $row->pph : 0;
                $pph23 += $row->pajakid == 4 ? $row->pph : 0;
            }
            $ppn = $nilai / 10; 
            $nilai = $nilai - $pph22 - $pph23;
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal),'penjualan',$invid,$akunpiutang,$nilai,0, 1);  //piutang
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal),'penjualan',$invid,$akunppn,$ppn,0, 0);  //ppn
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal),'penjualan',$invid,$akunpenjualan,0,$request->total, 0);  //penjualan
            if($pph22>0){
                $akunpph22 = Pajak::find(3)->akunoutid;
                MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal),'penjualan',$invid,$akunpph22,$pph22,0, 0);  //ppn
            }
            if($pph23>0){
                $akunpph23 = Pajak::find(4)->akunoutid;
                MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal),'penjualan',$invid,$akunpph23,$pph23,0, 0);  //ppn
            }

            Session::flash('sukses', ' Data Invoice berhasil disimpan.');
            return redirect(route('showInvoice', $invid));
        } else {
            Session::flash('warning', ' Data Invoice gagal disimpan.');
            return redirect()->back();
        }
    }

    public function updatePembatalan(Request $request)
    {
        if (UserAkses::cek_akses('invoice', 'cud') == false) return redirect(route('noakses'));

        $inv = Invoice::find($request->id);
        $inv->status = 4;
        $inv->alasanpembatalan = $request->alasan;
        $inv->tgl_pembatalan = date('Y/m/d H:i:s');
        $inv->save();

        Transaksi::where('item','penjualan')->where('itemid',$request->id)->delete();

        Session::flash('sukses', ' Data Pembatalan Invoice berhasil disimpan.');
        return redirect(route('showInvoice', $request->id));
    }

    public function updatePanjar(Request $request)
    {
        $panjarid = $request->id;

        if ($panjarid) {
            $panjar = Panjar::find($panjarid);
            $panjar->tanggal = $this->EnglishTgl($request->tanggal);
            $panjar->nilai =  $this->Angkapolos($request->nilai);
            $panjar->projectid =  $request->projectid;
            $panjar->jenis =  $request->jenis;
            $panjar->catatan =  $request->catatan;
            $panjar->jaminan1 =  $request->jaminan1;
            $panjar->jaminan2 =  $request->jaminan2;
            $panjar->jaminan3 =  $request->jaminan3;
            $panjar->lampiran1 =  $request->lampiran1;
            $panjar->lampiran2 =  $request->lampiran2;
            $panjar->lampiran3 =  $request->lampiran3;
            $panjar->lampiran4 =  $request->lampiran4;
            $panjar->pemberiid =  $request->pemberi;
            $panjar->penerimaid =  $request->penerima;
            $panjar->mengetahui1id =  $request->mengetahuipemberi;
            $panjar->mengetahui2id =  $request->mengetahuipenerima;
            $panjar->save();
        } else {
            // dd($request->jaminan2);
            $panjarid = Panjar::insertGetId([

                'tanggal' => $this->EnglishTgl($request->tanggal),
                'nilai' => $this->Angkapolos($request->nilai),
                'nomor' => $this->buatKode('panjar', 'nomor', 'PJ'), //PJ=panjar simpanan  , STP
                'jenis' => $request->jenis,
                'catatan' => $request->catatan,
                'projectid' => $request->projectid,
                'jaminan1' => $request->jaminan1,
                'jaminan2' => $request->jaminan2,
                'jaminan3' => $request->jaminan3,
                'lampiran1' => $request->lampiran1,
                'lampiran2' => $request->lampiran2,
                'lampiran3' => $request->lampiran3,
                'lampiran4' => $request->lampiran4,
                'pemberiid' => $request->pemberi,
                'penerimaid' => $request->penerima,
                'mengetahui1id' => $request->mengetahuipemberi,
                'mengetahui2id' => $request->mengetahuipenerima,
                'status' => 5
            ]);
        }

        if ($panjarid > 0) {
            Invoice::updateOrInsert(['id' => $panjarid], [
                'nomor' => '',
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'tgl_jatuhtempo' => $this->EnglishTgl($request->tanggal),
                'pegawaiid' => 0,
                'projectid' => $request->projectid,
                'panjarid' => $panjarid,
                'jenis' => 2,
                'total' => $this->Angkapolos($request->nilai),
                'status' => 1 //belum bayar
            ]);

            Session::flash('sukses', ' Data Panjar berhasil disimpan.');
            return redirect(route('showPanjar', $panjarid));
        } else {
            Session::flash('warning', ' Data Panjar gagal disimpan.');
            return redirect()->back();
        }
    }

    public function batalpersetujuanPanjar($id){
        if (UserAkses::cek_akses('persetujuan_panjar', 'cud') == false) return redirect(route('noakses'));

        Panjar_setuju::where('panjarid',$id)->where('userid',Auth::user()->id)->delete();

        $pengurusx = Pengelola::where('jabatan', 'manager')->where('status', 1)->select(DB::raw(99), 'nama', 'jabatan');
            $pengurus = Pengurus::where('status', 1)->select('id', 'nama', 'jabatan')->union($pengurusx)->get();
            $statusid = 0; 
            foreach ($pengurus as $p) {
                if ($p->nama == Auth::user()->name) {
                    if (strtolower($p->jabatan) == 'manager'){
                            $statusid = 5;
                    } else if (strtolower($p->jabatan) == 'bendahara'){
                            $statusid = 6;
                    }else if (strtolower($p->jabatan) == 'ketua'){
                            $statusid = 7;
                    }
                }
            }

        $update = Panjar::where('id', $id)->update(['status' => $statusid ]);
        Session::flash('sukses_persetujuan', ' Anda telah MEMBATALKAN PERSETUJUAN terhadap Panjar ini.');
        return redirect()->route('showPanjar', $id);

    }

    public function persetujuanPanjar($id){

        if (UserAkses::cek_akses('persetujuan_panjar', 'cud') == false) return redirect(route('noakses'));

        $panjarsetujuid = Panjar_setuju::insertGetId([
            'panjarid' => $id,
            'tanggal' => date('Y-m-d H:i:s'),
            'userid' => Auth::user()->id
        ]);

        if($panjarsetujuid){
            $panjar = Panjar::find($id);
            $pengurusx = Pengelola::where('jabatan', 'manager')->where('status', 1)->select(DB::raw(99), 'nama', 'jabatan');
            $pengurus = Pengurus::where('status', 1)->select('id', 'nama', 'jabatan')->union($pengurusx)->get();
            $statusid = 0; $msg='';
            foreach ($pengurus as $p) {
                if ($p->nama == Auth::user()->name) {
                    if (strtolower($p->jabatan) == 'manager'){
                        if($panjar->nilai <= 50000000) {
                            $statusid = 1;
                        } else {
                            $statusid = 6;
                            $msg = " Panjar ini membutuhkan persetujuan berikutnya.";
                        }
                    } else if (strtolower($p->jabatan) == 'bendahara'){
                        if($panjar->nilai <= 100000000) {
                            $statusid = 1;
                        } else {
                            $statusid = 7;
                            $msg = " Panjar ini membutuhkan persetujuan berikutnya.";

                        }
                    }else if (strtolower($p->jabatan) == 'ketua'){
                            $statusid = 1;
                    }
                }
            }

            $update = Panjar::where('id', $id)->update(['status' => $statusid ]);
            Session::flash('sukses_persetujuan', ' Data Panjar berhasil diupdate, Anda telah MENYETUJUI panjar ini. '.$msg);
            return redirect()->route('showPanjar', $id);
            
        }else{
            Session::flash('error_persetujuan', ' Data Panjar gagal diupdate.');
            return redirect()->back();
        }
        
    }

    public function showPanjar($id)
    {
        return $this->editPanjar($id, 0, 1);
    }

    public function editPanjar($id, $kodeubah = 1, $kode = 0)
    {
        if ($kode == 0) {
            if (UserAkses::cek_akses('panjar', 'cud') == false) return redirect(route('noakses'));
        }

        $panjar = Panjar::find($id);
        $projectid_f = $panjar->projectid ?? '';

        $data = Project::join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftjoin('invoice', 'invoice.projectid', 'project.id')
            ->where('project.id', $projectid_f)
            ->select('project.*', 'perusahaan.nama as perusahaan', 'invoice.status as statusinvoice')
            ->first();

        $daftarpembayaran = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')
            ->join('project', 'project.id', 'invoice.projectid')
            ->join('panjar', 'panjar.projectid', 'project.id')
            ->where('panjar.id', $id)
            ->where('invoice.jenis', 2)
            ->select('pembayaran.*')
            ->get();

        $panjardetail = Panjar_detail::join('project', 'project.id', 'panjar_detail.projectid')
            ->select('panjar_detail.nilai', 'project.no_spk', 'project.nama', 'panjar_detail.id')
            ->where('panjarid', $id)->get();

        $project = Project::where('status', 1)->get();
        $pemesan = Pemesan::all();
        $pengelola = Pengelola::where('status', 1)->get();
        $item = Project::join('projectitem', 'project.id', 'projectitem.projectid')
            ->select(DB::raw('sum(total+ppn) as nilai'))
            ->where('projectid', $projectid_f)
            ->groupby('project.id')
            ->first();

        $tag = ['menu' => 'Project', 'submenu' => 'Data Panjar', 'judul' => 'DATA PANJAR', 'menuurl' => 'panjar', 'modal' => 'true'];
        return view('project.createPanjar', compact('tag', 'data', 'projectid_f',  'project', 'kodeubah', 'item', 'pengelola', 'panjar', 'pemesan', 'daftarpembayaran', 'panjardetail'));
    }

    public function showInvoice($id)
    {
        return $this->editInvoice($id, 0, 1);
    }

    public function editInvoice($id, $kodeubah = 1, $kode = 0)
    {
        if ($kode == 0) {
            if (UserAkses::cek_akses('invoice', 'cud') == false) return redirect(route('noakses'));
        }

        $data = Invoice::join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->where('invoice.id', $id)
            ->select('project.*', 'perusahaan.nama as perusahaan')
            ->first();

        $item = ProjectItem::where('projectid', $data->id)->get();
        $invoice = Invoice::find($id);
        $jenisinvoice = $invoice->jenis;
        $pengurusx = Pengelola::where('jabatan', 'manager')->where('status', 1)->select(DB::raw(99), 'nama');
        $pengurus = Pengurus::where('status', 1)->where('jabatan', '<>', 'bendahara')->select('id', 'nama')->union($pengurusx)->get();

        $nomorinvoice = Invoice::select('nomor')->where('id', '<>', $id)->get();
        $nomor = array();
        foreach ($nomorinvoice as $key ) {
            $nomor[] = strtolower($key->nomor);

        }
        $nomor = json_encode($nomor);

        $potongan = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')
            ->select(DB::raw('sum(pembayaran.nilai) as nilai'))
            ->where('invoice.projectid', $data->id)
            ->groupby('invoice.projectid')
            ->first();

        $bayarinvoice = $potongan->nilai ?? 'null';

        // $daftarpembayaran = Pembayaran::where('invoiceid', $id)->get();
        $daftarpembayaran = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')
            ->where('invoice.projectid', $data->id)
            ->select('pembayaran.*', 'invoice.nomor as nomorinv', 'invoice.jenis')
            ->get();

        $tag = ['menu' => 'Invoice', 'submenu' => $kodeubah == 1 ? 'Ubah Invoice' : 'Detail Invoice', 'judul' => 'INVOICE', 'menuurl' => 'invoice', 'modal' => 'true'];

        return view('project.createInvoice', compact('tag', 'data', 'item', 'pengurus', 'invoice', 'jenisinvoice', 'bayarinvoice', 'kodeubah', 'bayarinvoice', 'daftarpembayaran', 'nomor'));
    }

    public function destroyInvoice($id)
    {
        if (UserAkses::cek_akses('invoice', 'cud') == false) return redirect(route('noakses'));

        Invoice::destroy($id);
        return redirect()->back();
    }
    public function destroyPanjar($id)
    {
        if (UserAkses::cek_akses('panjar', 'cud') == false) return redirect(route('noakses'));

        $panjar = Panjar::find($id);
        Invoice::where('projectid', $panjar->projectid)->where('jenis', 2)->delete();
        Panjar::destroy($id);

        return redirect()->back();
    }

    public function printInvoice($id)
    {
        if (UserAkses::cek_akses('invoice', 'cetak') == false) return redirect(route('noakses'));

        $data = Invoice::join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftJoin('pengurus', 'invoice.pegawaiid', 'pengurus.id')
            ->where('invoice.id', $id)
            ->select('project.*', 'perusahaan.nama as perusahaan', 'pengurus.nama as pengurus', 'perusahaan.alamat', 'perusahaan.kota', 'pengurus.jabatan')
            ->first();

        $potongan = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')
            ->select(DB::raw('sum(pembayaran.nilai) as nilai'))
            ->where('invoice.projectid', $data->id)
            ->groupby('invoice.projectid')
            ->first();

        $bayarinvoice = $potongan->nilai ?? 'null';

        $item = ProjectItem::where('projectid', $data->id)->get();
        $invoice = Invoice::find($id);
        $koperasi = DB::table('koperasi')->first();
        $pdf = PDF::loadview('project.previewInvoice', ['data' => $data, 'item' => $item, 'invoice' => $invoice, 'koperasi' => $koperasi, 'bayarinvoice' => $bayarinvoice])->setPaper('A4', 'potrait');
        // $pdf->set_base_path("/assets/css/");
        // return $pdf->download('laporan-pdf.pdf');
        return $pdf->stream();
    }

    public function printSPB($id)
    {
        if (UserAkses::cek_akses('invoice', 'cetak') == false) return redirect(route('noakses'));

        $data = Invoice::join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftJoin('pengurus', 'invoice.pegawaiid', 'pengurus.id')
            ->where('invoice.id', $id)
            ->select('project.*', 'perusahaan.nama as perusahaan', 'pengurus.nama as pengurus', 'perusahaan.alamat', 'perusahaan.kota', 'pengurus.jabatan')
            ->first();

        $invoice = Invoice::find($id);
        $koperasi = DB::table('koperasi')->first();
        $pdf = PDF::loadview('project.previewSPB', ['data' => $data,  'invoice' => $invoice, 'koperasi' => $koperasi])->setPaper('A4', 'potrait');
        return $pdf->stream();
    }

    public function printTT($id)
    {
        if (UserAkses::cek_akses('invoice', 'cetak') == false) return redirect(route('noakses'));

        $data = Invoice::join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftJoin('pengurus', 'invoice.pegawaiid', 'pengurus.id')
            ->where('invoice.id', $id)
            ->select('project.*', 'perusahaan.alias')
            ->first();
        $invoice = Invoice::find($id);
        $item = ProjectItem::where('projectid', $data->id)->get();

        $manager = Pengelola::where('jabatan', 'MANAGER')->first();
        $pdf = PDF::loadview('project.previewTT', ['data' => $data, 'manager' => $manager, 'invoice' => $invoice, 'item' => $item])
            ->setPaper('A4', 'potrait');
        return $pdf->stream();
    }

    public function printSJ($id)
    {
        if (UserAkses::cek_akses('invoice', 'cetak') == false) return redirect(route('noakses'));

        $data = Invoice::join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftJoin('pengurus', 'invoice.pegawaiid', 'pengurus.id')
            ->where('invoice.id', $id)
            ->select('project.*', 'perusahaan.alias')
            ->first();
        $invoice = Invoice::find($id);
        $item = ProjectItem::where('projectid', $data->id)->get();

        $manager = Pengelola::where('jabatan', 'MANAGER')->first();
        $pdf = PDF::loadview('project.previewSJ', ['data' => $data, 'manager' => $manager, 'invoice' => $invoice, 'item' => $item])->setPaper('A4', 'potrait');
        return $pdf->stream();
    }
    public function print_berita_acara($id, $kode)
    {
        if (UserAkses::cek_akses('invoice', 'cetak') == false) return redirect(route('noakses'));

        $data = Invoice::join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftJoin('pengurus', 'invoice.pegawaiid', 'pengurus.id')
            ->leftJoin('project_pemesan', 'project_pemesan.id', 'project.pemesanid')
            ->where('invoice.id', $id)
            ->select('project.*', 'perusahaan.nama as perusahaan', 'perusahaan.unitkerja', 'perusahaan.alias', 'pengurus.nama as pengurus', 'perusahaan.alamat', 'perusahaan.kota', 'pengurus.jabatan as ketua', 'project_pemesan.nama as pemesan', 'project_pemesan.jabatan as jabatan')
            ->first();

        $invoice = Invoice::find($id);
        $item = ProjectItem::where('projectid', $data->id)->get();
        $koperasi = DB::table('koperasi')->first();
        $manager = Pengelola::where('jabatan', 'MANAGER')->first();

        if ($kode == 'BA') {
            $file = 'project.previewBA';
        } else if ($kode == 'BAPPKontrak') {
            $file = 'project.previewBAPPKontrak';
        } else if ($kode == 'BAPPGSD') {
            $file = 'project.previewBAPPGSD';
        } else if ($kode == 'BAUTKontrak') {
            $file = 'project.previewBAUTKontrak';
        }

        $pdf = PDF::loadview($file, ['data' => $data, 'manager' => $manager, 'invoice' => $invoice, 'item' => $item, 'koperasi' => $koperasi])->setPaper('A4', 'potrait');
        return $pdf->stream();
    }
    public function printBA($id)
    {
        return $this->print_berita_acara($id, 'BA');
    }
    public function printBAPPKontrak($id)
    {
        return $this->print_berita_acara($id, 'BAPPKontrak');
    }
    public function printBAPPGSD($id)
    {
        return $this->print_berita_acara($id, 'BAPPGSD');
    }
    public function printBAUTKontrak($id)
    {
        return $this->print_berita_acara($id, 'BAUTKontrak');
    }


    public function printPanjar($id)
    {
        if (UserAkses::cek_akses('panjar', 'cetak') == false) return redirect(route('noakses'));

        // cek persetujuan
        $panjar_setuju = Panjar::find($id);
        if($panjar_setuju->status >= 5){
            Session::flash('error_persetujuan', 'Panjar ini masih belum DISETUJUI, Cetak Form Panjar belum bisa dilakukan !');
            
        }


        $data = Panjar::leftjoin('project', 'project.id', 'panjar.projectid')->leftjoin('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftjoin('pengelola', 'pengelola.id', 'panjar.penerimaid')
            ->select('panjar.*', 'panjar.tanggal as tanggalpanjar', 'panjar.id as panjarid', 'panjar.nilai as nilaipanjar', 'project.*', 'project.id as projectid', 'project.nama as uraian', 'project.nilai as nilaiproject', 'perusahaan.nama as perusahaan', 'pengelola.nama as penerima', 'panjar.pemberiid')
            ->where('panjar.id', $id)
            ->first();
        // $data = Panjar::find($id);

        // $data = Project::join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
        //     ->leftjoin('invoice', 'invoice.projectid', 'project.id')
        //     ->where('project.id', $projectid_f)
        //     ->select('project.*', 'perusahaan.nama as perusahaan', 'invoice.status as statusinvoice')
        //     ->first();

        // dd($data);
        foreach (Pengelola::all() as $p) {
            $pengelola_arr[$p->id] = ['nama' => $p->nama, 'jabatan' => $p->jabatan, ''];
        };

        $pemberi = Pemesan::find($data->pemberiid);
        $mengetahui = Pemesan::find($data->mengetahui1id);
        $penerima = Pengelola::where('pengelola.id', $data->penerimaid)->first();
        $mengetahui2 = Pengelola::where('pengelola.id', $data->mengetahui2id)->first();

        $item = ProjectItem::where('projectid', $data->projectid)->get();
        $koperasi = DB::table('koperasi')->first();
        $pdf = PDF::loadview('project.previewPanjar', ['data' => $data, 'pengelola' => $pengelola_arr, 'koperasi' => $koperasi, 'item' => $item, 'pemberi' => $pemberi, 'mengetahui' => $mengetahui, 'penerima' => $penerima, 'mengetahui2' => $mengetahui2])->setPaper('A4', 'potrait');
        return $pdf->stream();
    }

    public function printKwitansi($id)
    {
        if (UserAkses::cek_akses('invoice', 'cetak') == false) return redirect(route('noakses'));

        $data = Invoice::join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftJoin('pengurus', 'invoice.pegawaiid', 'pengurus.id')
            ->where('invoice.id', $id)
            ->select('invoice.*', 'project.nama as project', 'perusahaan.nama as perusahaan', 'pengurus.nama as pengurus', 'perusahaan.alamat', 'perusahaan.kota', 'pengurus.jabatan')
            ->first();
        $koperasi = DB::table('koperasi')->first();
        $pdf = PDF::loadview('project.previewKwitansi', ['data' => $data, 'koperasi' => $koperasi])->setPaper('A4', 'potrait');;
        return $pdf->stream();
    }

    public function dashboardpegawai($id)
    {
        $tag = ['menu' => 'Project', 'submenu' => 'Dashboard Project', 'judul' => 'Dashboard Pegawai', 'menuurl' => 'projectDb', 'modal' => 'true'];

        $tahun = date('Y');

        $rekap = DB::select("SELECT sum(((am.porsi/100) * p.nilai)) as total, count(p.id) as jumlah, year(p.tgl_spk) as tahun, 'cashin' as ket  from project p join invoice i on p.id = i.projectid join pembayaran pb on i.id = pb.invoiceid join projectAM am on p.id = am.projectid where am.pengelolaid = " . $id . " and year(pb.tanggal) = " . $tahun . " and p.status = 0 group by year(pb.tanggal) 
        UNION 
        SELECT sum(((am.porsi/100) * p.nilai)) as total, count(p.id) as jumlah, year(p.tgl_spk) as tahun, 'tagihan' as ket  from project p join invoice i on p.id = i.projectid join projectAM am on p.id = am.projectid where am.pengelolaid = " . $id . " and year(p.tgl_spk)=" . $tahun . " and p.status = 1 and i.status = 1 group by year(p.tgl_spk) 
        UNION
        SELECT sum(((am.porsi/100) * p.nilai)) as total, count(p.id) as jumlah, year(p.tgl_spk) as tahun, 'potensi' as ket  from project p left join invoice i on p.id = i.projectid join projectAM am on p.id = am.projectid where am.pengelolaid = " . $id . " and year(p.tgl_spk)=" . $tahun . " and i.id is null group by year(p.tgl_spk);");

        $progrespegawai = DB::select("SELECT pl.id,pl.nama, t.nilai as ttarget, (t.nilai/12) as btarget,
        (SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id inner join pembayaran pb on i.id = pb.invoiceid where p.status = 0 AND year(pb.tanggal) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as cashin,
        (SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id where p.status = 1 AND year(p.tgl_spk) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as tagihan,
        (SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id left join invoice i on i.projectid = p.id where i.id is null AND p.status = 1 AND year(p.tgl_spk) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as potensi 
         from pengelola pl left join target t on pl.id= t.pengelolaid where pl.id = $id ; 
        ");


        $realisasi = DB::select("SELECT pengelola.id, pengelola.nama, pengelola.nik, target.nilai as nilaitahun, target.nilai/12 as target,  sum(((am.porsi/100) * p.nilai)) as realisasi , sum(p.nilai) as nilaiproject, month(p.tgl_spk) as bulan, year(p.tgl_spk) as tahun from pengelola left join  target on pengelola.id = target.pengelolaid left join projectAM am on am.pengelolaid = pengelola.id left join project p on p.id = am.projectid where target.tahun = " . date('Y') . " and year(p.tgl_spk) = " . date('Y') . " and pengelola.id = " . $id . " and p.status = 0 group by pengelola.id, month(p.tgl_po), year(p.tgl_po) order by pengelola.id,bulan,tahun;
        ");


        $realtahun = $targetbulan = 0;
        $nama = $nik = '';

        if ($realisasi !== null) {
            for ($i = 1; $i < 13; $i++) {
                foreach ($realisasi as $r) {
                    if ($r->bulan == $i && $i <= date('m')) {
                        $breal[$i - 1] = number_format($r->realisasi / 1000000, 2);
                        $brealx[$i - 1] = $r->realisasi;
                        $realtahun += $r->realisasi;
                        $targetbulan =  $r->target;
                        $nama = $r->nama;
                        $nik = $r->nik;
                        break;
                    } else {
                        $breal[$i - 1] = 0;
                        $brealx[$i - 1] = 0;
                    }
                }
            }
        }

        for ($i = 1; $i < 13; $i++) {
            $targetpb[$i - 1] = $targetbulan;
        }
        $targettahun = $targetbulan * 12;
        for ($i = 1; $i < 13; $i++) {
            if ($i <= date('m') && $i != 1) {
                if ($brealx[$i - 2] < $targetpb[$i - 2]) {

                    $targetlebih = $targetpb[$i - 1] + ($targetpb[$i - 2] - $brealx[$i - 2]);
                    $targetpb[$i - 1] = $targetlebih;
                    // dd($targetlebih);
                    $btarget[$i - 1] = number_format($targetlebih / 1000000, 2);
                } else {
                    $btarget[$i - 1] = number_format($targetpb[$i - 1]  / 1000000, 2);
                }
            } else {
                if ($i == 1) {
                    $btarget[$i - 1] = number_format($targetpb[$i - 1]  / 1000000, 2);
                } else {
                    $btarget[$i - 1] = 0;
                }
            }
        }
        // dd($btarget);

        $persenprogress = number_format($realtahun / $targettahun, 4);
        $breal = json_encode($breal);
        $btarget = json_encode($btarget);
        return view('project.dashboardpegawai', compact('tag', 'nama', 'nik', 'breal', 'btarget', 'targettahun', 'realtahun', 'persenprogress', 'rekap', 'progrespegawai'));
    }

    public function dashboard()
    {

        if (UserAkses::cek_akses('project', 'lihat') == false) return redirect(route('noakses'));

        $tag = ['menu' => 'Project', 'submenu' => 'Dashboard Project', 'judul' => 'DASHBOARD PROJECT', 'menuurl' => 'projectDb', 'modal' => 'true'];

        $pengelola = Pengelola::all();

        // rekap cashin, tagihan, potensi

        $tahun = date('Y');
        // $bulan = 2;

        $rekap = DB::select("SELECT sum(p.nilai) as total, count(p.id) as jumlah,year(p.tgl_spk) as tahun, 'cashin' as ket  from project p join invoice i on p.id = i.projectid join pembayaran pb on i.id = pb.invoiceid where year(pb.tanggal) = " . $tahun . " and p.status = 0 group by year(pb.tanggal) 
        UNION 
        SELECT sum(p.nilai) as total, count(p.id) as jumlah, year(p.tgl_spk) as tahun, 'tagihan' as ket  from project p join invoice i on p.id = i.projectid where year(p.tgl_spk)=" . $tahun . " and p.status = 1 and i.status = 1 group by year(p.tgl_spk) 
        UNION
        SELECT sum(p.nilai) as total, count(p.id) as jumlah, year(p.tgl_spk) as tahun, 'potensi' as ket  from project p left join invoice i on p.id = i.projectid where year(p.tgl_spk)=" . $tahun . " and i.id is null group by year(p.tgl_spk);");

        //target dan progres pegawai
        // $progrespegawai = DB::select("SELECT pengelola.id, pengelola.nama, target.nilai as nilaitahun, target.nilai/12 as target, target.tahun,  sum(((am.porsi/100) * p.nilai)) as realisasi , sum(p.nilai) as nilaiproject, month(p.tgl_spk) as bulan, year(p.tgl_spk) as tahun from pengelola left join target on pengelola.id = target.pengelolaid left join projectAM am on am.pengelolaid = pengelola.id left join project p on p.id = am.projectid where year(p.tgl_spk)=" . date('Y') . " group by pengelola.id order by pengelola.id,bulan,tahun;");

        //         $progrespegawai = DB::select("SELECT pl.id,pl.nama, t.nilai as ttarget, (t.nilai/12) as btarget,
        // (SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id inner join pembayaran pb on i.id = pb.invoiceid where p.status = 0 and year(pb.tanggal) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as cashin,
        // (SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id where p.status = 1 and year(p.tgl_spk) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as tagihan,
        // (SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id left join invoice i on i.projectid = p.id where i.id is null and p.status = 1 and year(p.tgl_spk) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as potensi 
        //  from pengelola pl left join target t on pl.id= t.pengelolaid ; 
        // ");

        $progrespegawai = DB::select("SELECT pl.id,pl.nama, t.nilai as ttarget, (t.nilai/12) as btarget,
(SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id inner join pembayaran pb on i.id = pb.invoiceid where p.status = 0 AND year(pb.tanggal) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as cashin,
(SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id where p.status = 1 AND year(p.tgl_spk) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as tagihan,
(SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id left join invoice i on i.projectid = p.id where i.id is null AND p.status = 1 AND year(p.tgl_spk) = " . $tahun . " AND pm.pengelolaid = pl.id group by pm.pengelolaid) as potensi 
 from pengelola pl left join target t on pl.id= t.pengelolaid ; 
");

        //target dan progres koperasi
        $target = Target::select(DB::raw('sum(nilai) as nilai'))->where('tahun', $tahun)->first();
        $realisasi = DB::select("SELECT sum(p.nilai) as realisasi, count(p.id) as jumlah,year(pb.tanggal) as tahun, month(pb.tanggal) as bulan from project p join invoice i on p.id = i.projectid join pembayaran pb on i.id = pb.invoiceid where year(pb.tanggal) = " . $tahun . " and p.status = 0 group by year(pb.tanggal), month(pb.tanggal) order by bulan,tahun ");
        // $realisasi = DB::select("select sum(((am.porsi/100) * p.nilai)) as realisasi, month(p.tgl_spk) as bulan, year(p.tgl_spk) as tahun from projectAM am left join project p on p.id = am.projectid where year(p.tgl_spk)=" . date('Y') . " group by month(p.tgl_spk), year(p.tgl_spk) order by bulan,tahun");

        $realtahun = 0;
        $breal = array();
        $brealx = array();

        if ($realisasi !== null) {
            for ($i = 1; $i < 13; $i++) {
                foreach ($realisasi as $r) {
                    if ($r->bulan == $i && $i <= date('m')) {
                        $breal[$i - 1] = number_format($r->realisasi / 1000000, 2);
                        $brealx[$i - 1] = $r->realisasi;
                        $realtahun += $r->realisasi;
                        break;
                    } else {
                        $breal[$i - 1] = 0;
                        $brealx[$i - 1] = 0;
                    }
                }
            }
        }

        for ($i = 1; $i < 13; $i++) {
            $targetpb[$i - 1] = $target->nilai / 12;
        }
        $targettahun = $target->nilai;

        for ($i = 1; $i <= date('m'); $i++) {
            if ($i != 1 && $i != 4 && $i != 7 && $i != 10) {
                if ($brealx && ($brealx[$i - 2] < $targetpb[$i - 2])) {

                    $targetlebih = $targetpb[$i - 1] + ($targetpb[$i - 2] - $brealx[$i - 2]);
                    $targetpb[$i - 1] = $targetlebih;
                    // dd($targetlebih);
                    $btarget[$i - 1] = number_format($targetlebih / 1000000, 2);
                } else {
                    $btarget[$i - 1] = number_format($targetpb[$i - 1]  / 1000000, 2);
                }
            } else {
                if ($i == 1 || $i == 4 || $i == 7 || $i == 10) {
                    $btarget[$i - 1] = number_format($targetpb[$i - 1]  / 1000000, 2);
                } else {
                    $btarget[$i - 1] = 0;
                }
            }
        }
        // dd($btarget);

        $persenprogress = ($realtahun / ($targettahun ?? 1) ) ;
        // dd($persenprogress);
        $breal = json_encode($breal);
        $btarget = json_encode($btarget);
        return view('project.dashboard', compact('tag', 'pengelola', 'breal', 'btarget', 'targettahun', 'realtahun', 'persenprogress', 'progrespegawai', 'rekap'));
    }

    public function filterDashboard(Request $request)
    {
        $tgl = $request->tgl;
        $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
        $tgl2 =  $this->EnglishTgl(substr($tgl, -10));

        $progrespegawai = DB::select("SELECT pl.id,pl.nama, t.nilai as ttarget, (t.nilai/12) as btarget,
(SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id inner join pembayaran pb on i.id = pb.invoiceid where p.status = 0 AND pb.tanggal BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "' AND pm.pengelolaid = pl.id group by pm.pengelolaid) as cashin,
(SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id inner join invoice i on i.projectid = p.id where p.status = 1 AND p.tgl_spk BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "' AND pm.pengelolaid = pl.id group by pm.pengelolaid) as tagihan,
(SELECT sum(((pm.porsi/100) * p.nilai)) as realisasi from projectAM pm inner join project p on pm.projectid = p.id left join invoice i on i.projectid = p.id where i.id is null AND p.status = 1 AND p.tgl_spk BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "' AND pm.pengelolaid = pl.id group by pm.pengelolaid) as potensi 
 from pengelola pl left join target t on pl.id= t.pengelolaid ; 
");
        return view('project.dashboard_filter', compact('progrespegawai'));
    }

    public function dashboardProjectList($jenis)
    {
        $tahun = date('Y');

        if ($jenis == 'potensi') {
            $project = DB::select("SELECT p.*, i.nomor, i.total, pr.alias as perusahaan, pl.nama as am, pp.nama as pemesan from project p inner join perusahaan pr on p.perusahaanid = pr.id left join pengelola pl on p.pic = pl.id left join project_pemesan pp on p.pemesanid = pp.id  left join invoice i on p.id = i.projectid where year(p.tgl_spk)= " . $tahun . " and i.id is null ");
        } else if ($jenis == 'tagihan') {
            $project = DB::select("SELECT p.*, i.nomor, i.total, pr.alias as perusahaan, pl.nama as am, pp.nama as pemesan from project p inner join perusahaan pr on p.perusahaanid = pr.id left join pengelola pl on p.pic = pl.id left join project_pemesan pp on p.pemesanid = pp.id  left join invoice i on p.id = i.projectid where year(p.tgl_spk)= " . $tahun . " and p.status = 1 and i.status = 1;");
        } else if ($jenis == 'cashin') {
            $project = DB::select("SELECT p.*, i.nomor, i.total, pr.alias as perusahaan, pl.nama as am, pp.nama as pemesan from project p inner join perusahaan pr on p.perusahaanid = pr.id left join pengelola pl on p.pic = pl.id left join project_pemesan pp on p.pemesanid = pp.id  left join invoice i on p.id = i.projectid where year(p.tgl_spk)= " . $tahun . " and p.status = 0;");
        }

        return view('project.dashboard_list_project', compact('project'));
    }

    function EnglishTgl($tanggal)
    {

        if ($tanggal == '' || $tanggal == '00') {
            $awal = null;
        } else {
            $tgl = str_replace('-', '/', $tanggal);
            $tgl = explode('/', $tgl);
            $awal = "$tgl[2]-$tgl[1]-$tgl[0]";
        }
        return $awal;
    }

    function Angkapolos($nilai)
    {
        return str_replace('.', '', $nilai);
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
