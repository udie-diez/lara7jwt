<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Perusahaan;
use App\Rekap_pajak;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RekapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function pph22(){
        if (UserAkses::cek_akses('rekappph', 'lihat') == false) return redirect(route('noakses'));

        $data = DB::select('select i.id,pj.nama,p.nilai as pembayaran, rp.nomor, rp.tanggal,p.tanggal as tanggalpby,
        (i.total - (((100/110) * i.total)/10 )) as totalppn,
        ((i.total - (((100/110) * i.total)/10 )) * (1.5/100) ) as pph22, 
        (i.total - (((100/110) * i.total)/10) - (((i.total - (((100/110) * i.total)/10 )) * (1.5/100)) )) as totalpph22
         from invoice i inner join pembayaran p on i.id = p.invoiceid inner join project pj on pj.id = i.projectid left join rekap_pajak rp on rp.invoiceid = i.id HAVING p.nilai = ROUND(totalpph22,2)  order by p.tanggal desc');
         
         $perusahaan = Perusahaan::all();
         $tag = ['menu' => 'Project', 'submenu' => 'Rekap Pajak', 'judul' => 'Rekap Pajak PPH22', 'menuurl' => 'rekappph22', 'modal' => 'false'];
         return view('rekap.pph22', compact('tag', 'data', 'perusahaan'));
    }

    public function pph23(){
        if (UserAkses::cek_akses('rekappph', 'lihat') == false) return redirect(route('noakses'));

        // $data = DB::select('select i.id,pj.nama,p.nilai as pembayaran, i.total, rp.nomor, rp.tanggal,
        // ((100/110) * i.total) as dpp,
        // ((100/110) * i.total)/10  as ppn,
        // (i.total - (((100/110) * i.total)/10 )) as totalppn,  
        // ((i.total - (((100/110) * i.total)/10 )) * (1.5/100) ) as pph22,
        // ((i.total - (((100/110) * i.total)/10 )) * (2/100) ) as pph23,
        // (i.total - (((100/110) * i.total)/10) - (((i.total - (((100/110) * i.total)/10 )) * (1.5/100)) )) as totalpph22,
        // (i.total - (((100/110) * i.total)/10) - (((i.total - (((100/110) * i.total)/10 )) * (2/100)) )) as totalpph23
        //  from invoice i inner join pembayaran p on i.id = p.invoiceid inner join project pj on pj.id = i.projectid left join rekap_pajak rp on rp.invoiceid = i.id  HAVING p.nilai = ROUND(totalpph23,2)');

        // dpp =  total * (100/110);
        // ppn = dpp / 10;
        // totalppn = total - ppn;
        // pph22 = totalppn * 1,5%;
        // pph23 = totalppn * 2%;
        // totalpph22 = total - ppn - 22
        // totalpph23 = total - ppn - 23

        $data = DB::select('select i.id, pj.nama, p.nilai as pembayaran, rp.nomor, rp.tanggal, p.tanggal as tanggalpby,
        (i.total - (((100/110) * i.total)/10 )) as totalppn,
        ((i.total - (((100/110) * i.total)/10 )) * (2/100) ) as pph23,
        (i.total - (((100/110) * i.total)/10) - (((i.total - (((100/110) * i.total)/10 )) * (2/100)) )) as totalpph23
         from invoice i inner join pembayaran p on i.id = p.invoiceid inner join project pj on pj.id = i.projectid left join rekap_pajak rp on rp.invoiceid = i.id  HAVING p.nilai = ROUND(totalpph23,2) order by p.tanggal desc');
         
         $perusahaan = Perusahaan::all();
         
         $tag = ['menu' => 'Project', 'submenu' => 'Rekap Pajak', 'judul' => 'Rekap Pajak PPH23', 'menuurl' => 'rekappph23', 'modal' => 'true'];
         return view('rekap.pph23', compact('tag', 'data', 'perusahaan'));

    }

    public function ppn(){

        if (UserAkses::cek_akses('rekapppn', 'lihat') == false) return redirect(route('noakses'));

        $data = DB::select('SELECT i.*, p.nama, pp.nama as pemesan , p.no_po, p.no_spk, perusahaan.alias as perusahaan,perusahaan.unitkerja, py.tanggal as tanggalbayar, ((p.nilai * 100)/110 * 0.1 ) as pajak, ((p.nilai * 100)/110) as subtotal
            from invoice i inner join project p on i.projectid = p.id inner join perusahaan on p.perusahaanid = perusahaan.id left join pembayaran py on i.id = py.invoiceid left join project_pemesan pp on pp.id = p.pemesanid where i.status = 3 order by p.id DESC');

        $perusahaan = Perusahaan::all();

        $tag = ['menu' => 'Project', 'submenu' => 'Rekap Pajak', 'judul' => 'Rekap Pajak PPN', 'menuurl' => 'rekapppn', 'modal' => 'true'];
        return view('rekap.ppn', compact('tag', 'data', 'perusahaan'));

    }

    public function filterppn(Request $request){
        if (UserAkses::cek_akses('rekapppn', 'lihat') == false) return redirect(route('noakses'));

        $tgl = $request->tglFilter;
        $ck_tanggal = $request->ck_tanggal;
        $f_perusahaan = $request->f_perusahaan;

        $w = 'i.jenis = 1';
        if ($f_perusahaan == '' || $f_perusahaan == 'all') {
            //1
        } else {
            $w .= " AND p.perusahaanid = " . $f_perusahaan;
        }
        if ($ck_tanggal) {
            $tgl1 =  $this->EnglishTgl(substr($tgl, 0, 10));
            $tgl2 =  $this->EnglishTgl(substr($tgl, -10));

            $filter_periode = " (py.tanggal BETWEEN '" . $tgl1 . "' AND '" . $tgl2 . "')";
            $w .= $w == '' ? $filter_periode : ' AND ' . $filter_periode;
        }

        $data = DB::select('SELECT i.*, p.nama, pp.nama as pemesan , p.no_po, p.no_spk, perusahaan.alias as perusahaan,perusahaan.unitkerja, py.tanggal as tanggalbayar, ((p.nilai * 100)/110 * 0.1 ) as pajak, ((p.nilai * 100)/110) as subtotal
        from invoice i inner join project p on i.projectid = p.id inner join perusahaan on p.perusahaanid = perusahaan.id left join pembayaran py on i.id = py.invoiceid left join project_pemesan pp on pp.id = p.pemesanid where i.status = 3 and '.$w.' order by p.id DESC');

        $perusahaan = Perusahaan::all();

        $tag = ['menu' => 'Project', 'submenu' => 'Rekap Pajak', 'judul' => 'Rekap Pajak PPN', 'menuurl' => 'rekapppn', 'modal' => 'true'];
        return view('rekap.ppn', compact('tag', 'data', 'perusahaan','tgl', 'ck_tanggal','f_perusahaan'));
    }

    function updaterekap(Request $request){
        Rekap_pajak::updateorInsert(['invoiceid' => $request->invoiceid],[
            'invoiceid' => $request->invoiceid,
            'nomor'=> $request->nomor,
            'tanggal' => $this->EnglishTgl($request->tanggal)
        ]);

        return redirect()->back()->with('invid',$request->invoiceid);
    }

    function edit($id){
        $data = Rekap_pajak::where('invoiceid',$id)->first();
        
        return view('rekap.form', compact('data','id'));
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
