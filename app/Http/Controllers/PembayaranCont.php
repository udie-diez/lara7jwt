<?php

namespace App\Http\Controllers;

use App\Helpers\MyHelpers;
use App\Helpers\UserAkses;
use App\Invoice;
use App\Mapping;
use App\Panjar;
use App\Pembayaran;
use App\Project;
use App\ProjectItem;
use App\Rekap_pajak;
use App\Transaksi;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PembayaranCont extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    }

    public function create($invoiceid)
    {

        if (UserAkses::cek_akses('pembayaran', 'cud') == false) return redirect(route('noakses'));

        $pembayaran = Pembayaran::where('invoiceid', $invoiceid)->first();
        if ($pembayaran) {
            return $this->edit($pembayaran->id, 0);
        }

        $data = Invoice::join('project', 'invoice.projectid', 'project.id')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftjoin('panjar', 'panjar.projectid', 'project.id')
            ->select('invoice.id as invoiceid', 'invoice.jenis', 'project.nama as project', 'invoice.tanggal as invoicetanggal', 'invoice.tgl_jatuhtempo as invoicetanggaljt', 'invoice.nomor as nomorinvoice', 'invoice.total as invoicetotal', 'perusahaan.nama as perusahaan', 'panjar.id as panjarid')
            ->where('invoice.id', $invoiceid)
            ->first();
        $invoice = DB::select('SELECT invoice.id, (invoice.total - sum(ifnull(pembayaran.nilai,0))) as sisa from invoice left join pembayaran on invoice.id = pembayaran.invoiceid where invoice.id = ' . $invoiceid . ' group by invoice.id, invoice.total');
        $sisatagihan = $invoice[0]->sisa;

        $kodeubah = 2;
        $tag = ['menu' => 'Invoice', 'submenu' => ' Pembayaran', 'judul' => 'Data Pembayaran', 'menuurl' => 'invoice', 'modal' => 'false'];

        return view('pembayaran.create', compact('tag', 'data', 'kodeubah', 'invoiceid', 'sisatagihan'));
    }

    public function panjar($id)
    {

        $data = Panjar::join('project_pemesan', 'panjar.pemberiid', 'project_pemesan.id')->select('panjar.*', 'project_pemesan.nama as penerima')->where('panjar.id', $id)->first();
        $bayarid = Pembayaran::where('pannjarid', $id)->select('id')->first();
        $tag = ['menu' => 'Panjar', 'submenu' => ' Pembayaran', 'judul' => 'Pembayaran Panjar', 'menuurl' => 'panjar', 'modal' => 'false'];
        $kodeubah = 2;

        return view('pembayaran.panjar', compact('tag', 'data', 'kodeubah', 'bayarid'));
    }

    public function show($id)
    {
        return $this->edit($id, 0);
    }

    public function edit($id, $kodeubah = 1)
    {
        $data = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')->join('project', 'invoice.projectid', 'project.id')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftjoin('panjar', 'panjar.projectid', 'project.id')
            ->select('pembayaran.*', 'invoice.nomor as nomorinvoice', 'invoice.id as invoiceid', 'invoice.total as invoicetotal', 'invoice.tgl_jatuhtempo as invoicetanggal', 'perusahaan.nama as perusahaan', 'panjar.id as panjarid', 'invoice.jenis')
            ->where('pembayaran.id', $id)
            ->first();

        $panjarid = $data->panjarid;
        $tag = ['menu' => 'Invoice', 'submenu' => ' Pembayaran', 'judul' => 'Penerimaan Pembayaran', 'menuurl' => 'invoice', 'modal' => 'false'];
        return view('pembayaran.create', compact('tag', 'data', 'kodeubah', 'panjarid'));
    }

    public function update(Request $request)
    {
        $invoice = DB::select('SELECT invoice.id,invoice.jenis,invoice.projectid, invoice.panjarid from invoice left join pembayaran on invoice.id = pembayaran.invoiceid where invoice.id = ' . $request->invoiceid . ' group by invoice.id,invoice.jenis,invoice.projectid, invoice.total');
        $jenis = $invoice[0]->jenis;
        $panjarid = $invoice[0]->panjarid;
        $projectid = $invoice[0]->projectid;
        $nilai = str_replace('.', '', $request->td_nilai);
        $id = $request->id;
        if ($id > 0) {
            $bayar = Pembayaran::find($id);
            $bayar->tanggal = $this->EnglishTgl($request->tanggal);
            //$bayar->nomor = $request->nomor;
            $bayar->invoiceid = $request->invoiceid;
            $bayar->cara = $request->cara;
            $bayar->catatan = $request->catatan;
            $bayar->nilai =  $nilai;
            $bayar->save();
        } else {
            $id = Pembayaran::insertGetId([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'nomor'  => $this->buatKode('pembayaran', 'nomor', 'PI'),  //STS=setoran simpanan  , STP, AT=Anggota
                'invoiceid'  => $request->invoiceid,
                'cara'  => $request->cara,
                'catatan'  => $request->catatan,
                'nilai'  =>  $nilai
            ]);
        }
        if ($id > 0) {
            $update = Invoice::where('id', $request->invoiceid)->update(['status' => 3]);  //1=belum bayar, 2=bayar sebagian, 3 = lunas, 4=batal

            //jika pajar = update pajar
            if ($jenis == 2 && $panjarid > 0) {

                $update = Panjar::where('id', $panjarid)->update(['status' => 3]);  //1=belum bayar, 2=bayar sebagian, 3 = lunas, 4=batal
            }

            if ($jenis == 1) {
                //kalau lunas , update project status=0
                $update = Project::where('id', $projectid)->update(['status' => 0]);
            }

            //cek pajak yang dibayar

            $pajak = DB::select('select i.id,pj.nama,p.nilai as pembayaran, i.total, 
        ((100/110) * i.total) as dpp,
        ((100/110) * i.total)/10  as ppn,
        (i.total - (((100/110) * i.total)/10 )) as totalppn,  
        ((i.total - (((100/110) * i.total)/10 )) * (1.5/100) ) as pph22,
        ((i.total - (((100/110) * i.total)/10 )) * (2/100) ) as pph23,
        (i.total - (((100/110) * i.total)/10) - (((i.total - (((100/110) * i.total)/10 )) * (1.5/100)) )) as totalpph22,
        (i.total - (((100/110) * i.total)/10) - (((i.total - (((100/110) * i.total)/10 )) * (2/100)) )) as totalpph23
         from invoice i inner join pembayaran p on i.id = p.invoiceid inner join project pj on pj.id = i.projectid where i.id = ' . $request->invoiceid);
            
            $nilaippn=$nilaix=0;
            $jenispajak=$total='';
            foreach ($pajak as $p) {
                if ($nilai != round($p->total, 0)) {
                    // dd(round($p->totalpph22, 0) .' ' .$nilai);
                    if (round($p->totalpph22, 0) == $nilai) {
                        $jenispajak = "pph22";
                        $total = $p->totalpph22;
                        $nilaix = $p->pph22;
                    } else if (round($p->totalpph23, 0) == $nilai) {
                        $jenispajak = 'pph23';
                        $total = $p->totalpph23;
                        $nilaix = $p->pph23;
                    } else if (round($p->totalppn, 0) == $nilai) {
                        $jenispajak = 'ppn';
                        $total = $p->totalppn;
                        $nilaix = $p->ppn;
                    }
                        Rekap_pajak::updateOrInsert(['invoiceid' => $request->invoiceid], [
                        'invoiceid' => $request->invoiceid,
                        'jenis' => $jenispajak,
                        'total' => $total,
                        'nilai' => $nilaix,
                        'nilaippn' => $p->ppn
                    ]);
                    $nilaippn=$p->ppn;
                }
            }

            //jurnal
            $jurnal = Transaksi::where('item', 'penjualan')->where('itemid', $request->invoiceid)->get();
            $akunrekeningterima = Mapping::where('jenis', 'terima_pembayaran')->first()->akunid;
            $akunpiutang = Project::join('perusahaan', 'project.perusahaanid', 'perusahaan.id')->where('project.id', $projectid)->select('perusahaan.akunid')->first()->akunid;
            $ppn = Mapping::where('jenis', 'ppn_penjualan')->first()->akunid;
            foreach ($jurnal as $row) {
                if ($row->akunid == $akunpiutang) {
                    MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal), 'pembayaran', $id, $akunpiutang, 0, $row->debit, 1);  //piutang
                }
                if ($row->akunid == $ppn) {
                    if($jenispajak=='pph23'){
                        $pph23 = 38; //Mapping::where('jenis', 'pph22_dibayardimuka')->first()->akunid;
                        MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal), 'pembayaran', $id, $pph23, $nilaix,0,);  //ppn

                    }else if($jenispajak=='pph23'){
                        $pph22 = 37; //Mapping::where('jenis', 'pph23_dibayardimuka')->first()->akunid;
                        MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal), 'pembayaran', $id, $pph22, $nilaix,0,);  //ppn
                    }

                    MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal), 'pembayaran', $id, $ppn, $nilaippn,0,);  //ppn

                }
            }
            MyHelpers::inputTransaksi($this->EnglishTgl($request->tanggal), 'pembayaran', $id, $akunrekeningterima, $nilai, 0);  //kas-bank

            Session::flash('sukses', 'Data Pembayaran berhasil disimpan');
            return redirect()->route('showPembayaran', $id);
        } else {
            Session::flash('warning', 'Data Pembayaran gagal disimpan');
            return redirect()->back();
        }
    }

    public function updatePembayaranPanjar(Request $request)
    {

        $id = $request->id;
        $panjarid = $request->panjarid;
        $nilai = str_replace('.', '', $request->td_nilai);
        if ($id > 0) {
            $bayar = Pembayaran::find($id);
            $bayar->tanggal = $this->EnglishTgl($request->tanggal);
            //$bayar->nomor = $request->nomor;
            $bayar->pannjarid = $panjarid;
            $bayar->cara = $request->cara;
            $bayar->catatan = $request->catatan;
            $bayar->nilai =  $nilai;
            $bayar->save();
        } else {

            $id = Pembayaran::insertGetId([
                'tanggal' => $this->EnglishTgl($request->tanggal),
                'nomor'  => $this->buatKode('pembayaran', 'nomor', 'PPJ'),  //PPJ = pembayaran panjar
                'invoiceid'  => $request->invoiceid,
                'pannjarid' => $panjarid,
                'cara'  => $request->cara,
                'catatan'  => $request->catatan,
                'nilai'  =>  $nilai
            ]);
        }
        Session::flash('sukses', 'Data Pembayaran Panjar berhasil disimpan');

        $update = Panjar::where('id', $panjarid)->update(['status' => 3]);  //1=belum bayar, 2=bayar sebagian, 3 = lunas, 4=batal
        return redirect()->back();
    }

    public function destroy($id)
    {

        $pbyr = Pembayaran::find($id);
        $invoiceid = $pbyr->invoiceid;

        Pembayaran::destroy($id);

        $invoice = DB::select('SELECT invoice.id, invoice.total, (invoice.total - sum(ifnull(0,pembayaran.nilai))) as sisa from invoice left join pembayaran on invoice.id = pembayaran.invoiceid where invoice.id = ' . $invoiceid . ' group by invoice.id, invoice.total');

        $sisa = $invoice[0]->sisa;
        $total = $invoice[0]->total;
        if ($sisa == $total) {
            $status = 1;
        } else if ($sisa <= 0) {
            $status = 3;
        } else {
            $status = 2;
        }

        $update = Invoice::where('id', $invoiceid)->update(['status' => $status]);
        Transaksi::where('item', 'pembayaran')->where('itemid', $id)->delete();
        Session::flash('sukses', 'Data Pembayaran berhasil dihapus');
        return redirect(route('showInvoice', $invoiceid));
    }

    public function printKwitansi($id)
    {
        $data = Pembayaran::join('invoice', 'invoice.id', 'pembayaran.invoiceid')->join('project', 'project.id', 'invoice.projectid')
            ->join('perusahaan', 'project.perusahaanid', 'perusahaan.id')
            ->leftJoin('pengurus', 'invoice.pegawaiid', 'pengurus.id')
            ->where('pembayaran.id', $id)
            ->select('pembayaran.*', 'invoice.nomor as noinvoice', 'project.nama as project', 'perusahaan.nama as perusahaan', 'pengurus.nama as pengurus', 'perusahaan.alamat', 'perusahaan.kota', 'pengurus.jabatan')
            ->first();

        $koperasi = DB::table('koperasi')->first();
        $pdf = PDF::loadview('pembayaran.previewKwitansi', ['data' => $data, 'koperasi' => $koperasi])->setPaper('A4', 'potrait');;
        return $pdf->stream();
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

        if ($tanggal == '' || $tanggal == '00') {
            $awal = null;
        } else {
            $tgl = str_replace('-', '/', $tanggal);
            $tgl = explode('/', $tgl);
            $awal = "$tgl[2]-$tgl[1]-$tgl[0]";
        }
        return $awal;
    }
}
