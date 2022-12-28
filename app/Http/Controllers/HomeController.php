<?php

namespace App\Http\Controllers;

use App\ActivityLog;
use App\Imports\PayrollImport;
use App\Lampiran;
use App\Panjar;
use App\Pembelian;
use App\Pinjaman;
use App\Target;
use App\TempPayroll;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Intervention\Image\Facades\Image;
use Maatwebsite\Excel\Facades\Excel;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $tahun = date('Y');

        $rekap = DB::select("SELECT sum(p.nilai) as total, count(p.id) as jumlah,year(p.tgl_spk) as tahun, 'cashin' as ket  from project p join invoice i on p.id = i.projectid join pembayaran pb on i.id = pb.invoiceid where year(pb.tanggal) = " . $tahun . " and p.status = 0 group by year(pb.tanggal) 
        UNION 
        SELECT sum(p.nilai) as total, count(p.id) as jumlah, year(p.tgl_spk) as tahun, 'tagihan' as ket  from project p join invoice i on p.id = i.projectid where year(p.tgl_spk)=" . $tahun . " and p.status = 1 and i.status = 1 group by year(p.tgl_spk) 
        UNION
        SELECT sum(p.nilai) as total, count(p.id) as jumlah, year(p.tgl_spk) as tahun, 'potensi' as ket  from project p left join invoice i on p.id = i.projectid where year(p.tgl_spk)=" . $tahun . " and i.id is null group by year(p.tgl_spk);");

        $target = Target::select(DB::raw('sum(nilai) as nilai'))->where('tahun', $tahun)->first();
        $realisasi = DB::select("SELECT sum(p.nilai) as realisasi, count(p.id) as jumlah,year(pb.tanggal) as tahun, month(pb.tanggal) as bulan from project p join invoice i on p.id = i.projectid join pembayaran pb on i.id = pb.invoiceid where year(pb.tanggal) = " . $tahun . " and p.status = 0 group by year(pb.tanggal), month(pb.tanggal) order by bulan,tahun ");
        $realtahun = 0;

        $brealx = array();
        $breal = array();
        if ($realisasi !== null) {
            for ($i = 1; $i < 13; $i++) {
                foreach ($realisasi as $r) {
                    if ($r->bulan == $i && $i <= date('m')) {
                        $breal[$i - 1] = number_format($r->realisasi / 1000000000, 2);
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

        $targettahun = $target->nilai ?? 1;

        for ($i = 1; $i <= date('m'); $i++) {
            if ($i != 1 && $i != 4 && $i != 7 && $i != 10 && count($brealx) > 0) {
                if ($brealx[$i - 2] < $targetpb[$i - 2]) {

                    $targetlebih = $targetpb[$i - 1] + ($targetpb[$i - 2] - $brealx[$i - 2]);
                    $targetpb[$i - 1] = $targetlebih;
                    // dd($targetlebih);
                    $btarget[$i - 1] = number_format($targetlebih / 1000000000, 2);
                } else {
                    $btarget[$i - 1] = number_format($targetpb[$i - 1]  / 1000000000, 2);
                }
            } else {
                if ($i == 1 || $i == 4 || $i == 7 || $i == 10) {
                    $btarget[$i - 1] = number_format($targetpb[$i - 1]  / 1000000000, 2);
                } else {
                    $btarget[$i - 1] = 0;
                }
            }
        }
          

        $persenprogress = ($realtahun / $targettahun);
        // dd($persenprogress);
        $breal = json_encode($breal);
        $btarget = json_encode($btarget);

        //simpanan
        $str = "STR_TO_DATE(concat(tahun,'/',bulan,'/1'), '%Y/%m/%d') <= STR_TO_DATE('".date('Y/m/d')."', '%Y/%m/%d') ";

        $str = "select 
        (select sum(nilai) from simpanan where (".$str.") and jenis_simpanan = 'wajib')  as wajib,
        (select sum(nilai)  from simpanan where (".$str.") and jenis_simpanan = 'pokok')  as pokok,
        sum(a.wajib) as saldowajib,
        sum(a.pokok) as saldopokok from anggota a where status <> 2 or (tanggal_refund > STR_TO_DATE('".date('Y/m/d')."', '%Y-%m-%d') )";

        $simpanan = DB::select($str);

        //ANGGOTA
        $anggota = DB::select('SELECT count(id) as jumlah, status FROM anggota group by status');

        $tag = ['menu' => 'Home', 'submenu' => 'Dashboard', 'menuurl' => '', 'modal' => 'true'];
        return view('dashboard', compact('tag', 'rekap', 'breal', 'btarget', 'targettahun', 'simpanan', 'anggota'));
    }

    public function loginAttemp(Request $request){
        request()->validate([
            'email' => 'required',
            'password' => 'required',
            ]);
            $credentials = $request->only('email', 'password');
            dd($credentials);
            if (Auth::attempt($credentials)) {
                // Authentication passed...
                return redirect()->intended('home');
            };
            return Redirect::to("login")->withSuccess('Opps! Email atau password Anda tidak sesuai/salah');
    
    }

    public function noakses(){
        $tag = ['menu' => 'Home', 'submenu' => 'NoAkses', 'menuurl' => 'home', 'modal' => 'true'];

        return view('layouts.noakses', compact('tag'));
    }

    public function data($kode)
    {

        $tag = ['menu' => 'Master Data', 'submenu' => ucfirst($kode), 'judul' => 'Daftar ' . ucfirst($kode), 'menuurl' => '/data/' . $kode];

        // return view('data_'.$kode.'/'.$kode,compact('kode','tag'));
        return view('data_anggota.anggota', compact('kode', 'tag'));
    }


    public function project()
    {
        $tag = ['menu' => 'Master Data', 'submenu' => 'Anggota', 'menuurl' => '/data/anggota'];

        return view('project.project', compact('tag'));
    }

    public function simulasi(Request $request)
    {

        $tenor = $request->tenor;
        $plafon = str_replace('.', '', $request->plafon);
        $margin = $request->margin;
        //$inputFileName =  url('/assets/payroll/simulasifix.xlsx');
        $inputFileName = public_path('/assets/payroll/simulasifix.xlsx');
        $file = file_get_contents($inputFileName);
        $inputFileName = 'tempfile.xlsx';
        file_put_contents($inputFileName, $file);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        if ($tenor >= 12) {
            $workSheet = $spreadsheet->getSheetByName('simulasi2');
        } else {
            $workSheet = $spreadsheet->getSheetByName('simulasi3');
        }

        $spreadsheet->getActiveSheet()->setCellValue('D8', $tenor);
        $spreadsheet->getActiveSheet()->setCellValue('D6', $plafon);


        $baris = '';
        $tabel = '<table class="table bg-green-300" style="font-size: smaller; font-color: black;">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Pokok</th>
                        <th>Margin</th>
                        <th>Angsuran</th>
                        <th>Outstanding</th>
                    </tr>
                </thead>
                <tbody>';;

        for ($i = 12; $i <= (11 + $tenor); $i++) {


            $baris .= '<tr>
                    <td>' . $workSheet->getCell('C' . $i)->getValue() . '</td> 
                    
                    <td>' . number_format($workSheet->getCell('D' . $i)->getCalculatedValue(), 0) . '</td>
                    <td>' . number_format($workSheet->getCell('E' . $i)->getCalculatedValue(), 0)  . '</td>
                    <td id="angsuranx_' . $i . '">' . number_format($workSheet->getCell('F' . $i)->getCalculatedValue(), 0)  . '</td>
                    <td>' . number_format($workSheet->getCell('G' . $i)->getCalculatedValue(), 0)  . '</td>
                    </tr>';

            // if($workSheet->getCell('G'.$i)->getCalculatedValue()==0)break;

        }
        $tabel .= $baris . '</tbody></table>';
        echo $tabel;
    }

    public function simulasi1()
    {


        // config(['excel.import.startRow' => 13]);
        // Excel::import(new SimulasiImport, public_path('/assets/payroll/simulasi.xlsx'));
        // Session::flash('sukses','Data Payroll Berhasil Diimport!');
        // $workSheet = $spreadsheet->getActiveSheet(); 


        // $inputFileName = public_path('/assets/payroll/Simulasi.xlsx');

        // $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);

        // $spreadsheet->getActiveSheet()->getCell('D8')->setValue('1');
        // $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
        // $writer->setPreCalculateFormulas(false);
        // $writer->save(public_path('/assets/payroll/sim.xlsx'));

        $inputFileName =  url('/assets/payroll/sim.xlsx');
        // $file_copy = public_path('/assets/payroll/simulasi1.xlsx');

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $workSheet = $spreadsheet->getSheetByName('A3');

        // Calculation::getInstance($spreadsheet)->disableCalculationCache();
        //    $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'xlsx');

        // $writer->save($inputFileName);

        // $spreadsheet->disconnectWorksheets();
        // unset($spreadsheet);

        // $spreadsheet1 = \PhpOffice\PhpSpreadsheet\IOFactory::load($file_copy);
        // $workSheet = $spreadsheet1->getActiveSheet();


        $baris = '';
        $tabel = '<table class="table bg-green-300" style="font-size: smaller;">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th>Pokok</th>
                        <th>Margin</th>
                        <th>Angsuran</th>
                        <th>Outstanding</th>
                    </tr>
                </thead>
                <tbody>';;

        for ($i = 24; $i <= 40; $i++) {
            $baris .= '<tr>
                    <td>' . $workSheet->getCell('A' . $i)->getValue() . '</td> 
                    <td>' . $workSheet->getCell('B' . $i)->getValue() . '</td> 
                    
                    <td>' . $workSheet->getCell('C' . $i)->getValue() . '</td>
                    <td>' . $workSheet->getCell('D' . $i)->getValue()  . '</td>
                    <td>' . $workSheet->getCell('E' . $i)->getValue()  . '</td>

                    
                    </tr>';

            // if($workSheet->getCell('G'.$i)->getOldCalculatedValue()==0)break;
        }
        $tabel .= $baris . '</tbody></table>';
        echo $tabel;
    }

    public function upload(Request $request)
    {

        $jenis = $request->jenis;
        $id = $request->iditem;

        if ($jenis == '') {
            $this->validate($request, [
                'files' => 'required|file|image|mimes:jpeg,png,jpg|max:6048'
            ]);
            $image = $request->file('files');

            //zoom profile
            $namafile = $id . '.jpg';

            $destinationPath = public_path('/assets/photo');
            $img = Image::make($image->path());
            $img->resize(500, 500, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $namafile);

            return redirect()->back();
        } else if ($jenis == 'slip') {   //pengajuan, slip gaji
            $this->validate($request, [
                'files' => 'required|mimes:jpeg,png,jpg,pdf|max:6048'
            ]);
            $image = $request->file('files');
            $namafile = time() . "_" . $image->getClientOriginalName();
            $destinationPath = public_path('/assets/slip');
            $ext = $image->extension();

            if ($ext == 'pdf') {
                $image->move($destinationPath, $namafile);

            }else{
                //image
                $img = Image::make($image->path());
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $namafile);
            }


            //slip gaji, pengajuan
            $pinjaman = Pinjaman::where('id', $id)->update(['slip' => $namafile]);
            Session::flash('ok', 'Upload berkas berhasil. Permohonan pinjaman Anda akan segera diproses.');
            return redirect('/pinjaman/' . $id);

        } else if ($jenis == 'panjar') {   //upload berkas panjar
            $this->validate($request, [
                'files' => 'required|mimes:jpeg,png,jpg,pdf|max:6048'

            ]);
            $image = $request->file('files');

            $namafile = time() . "_" . $image->getClientOriginalName();
            $destinationPath = public_path('/assets/panjar');

            $ext = $image->extension();

            if ($ext == 'pdf') {
                $image->move($destinationPath, $namafile);

            }else{
                //image
                $img = Image::make($image->path());
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $namafile);
            }
            
            $panjar = Panjar::where('id', $id)->update(['file' => $namafile]);
            Session::flash('sukses', 'Upload berkas panjar berhasil.');
            return redirect()->back();

        } else if ($jenis == 'pembelian') {   //upload berkas pembelian
            $this->validate($request, [
                'files' => 'required|mimes:jpeg,png,jpg,pdf|max:6048'

            ]);
            $image = $request->file('files');

            $namafile = time() . "_" . $image->getClientOriginalName();
            $destinationPath = public_path('/assets/pembelian');

            $ext = $image->extension();

            if ($ext == 'pdf') {
                $image->move($destinationPath, $namafile);

            }else{
                //image
                $img = Image::make($image->path());
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $namafile);
            }
            
            $panjar = Pembelian::where('id', $id)->update(['file' => $namafile]);
            Session::flash('sukses', 'Upload berkas pembelian berhasil.');
            return redirect()->back();

        

        } else if ($jenis == 'kirim' || $jenis == 'transfer' || $jenis == 'biaya' || $jenis == 'terimauang' || $jenis == 'jurnal') {   //pengajuan, slip gaji
            $this->validate($request, [
                'files' => 'required|mimes:jpeg,png,jpg,pdf,doc,docx,xls,xlsx|max:2048'
            ]);
            $image = $request->file('files');
            $namafile = time() . "_" . $image->getClientOriginalName();
            $destinationPath = public_path('/assets/lampiran');
            $ext = $image->extension();

            if ($ext != 'jpg' ||  $ext != 'png' || $ext != 'jpeg') {
                $image->move($destinationPath, $namafile);

            }else{
                //image
                $img = Image::make($image->path());
                $img->resize(1000, 1000, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $namafile);
            }
            //slip gaji, pengajuan
            $pinjaman = Lampiran::insert([
                'jenis' => $jenis,
                'itemid' => $id,
                'file' => $namafile 
            ]);
            Session::flash('sukses', 'Upload lampiran berhasil.');
            return redirect()->back();

        } else if ($jenis == 'rekon') {

            $this->validate($request, [
                'files' => 'required|mimes:csv,xls,xlsx'
            ]);

            $rekon = TempPayroll::where('periode', $id)->select(DB::raw('min(id) as minid'), DB::raw('max(id) as maxid'))->first();

            $idmin = $idmax = 0;

            if ($rekon){
                $idmin = $rekon->minid ?? 0;
                $idmax = $rekon->maxid ?? 0;
            }
               
            $periode = explode('/', $id);
            $bulan = $periode[1];
            $tahun = $periode[0];
            $file = $request->file('files');

            $nama_file = rand() . $file->getClientOriginalName();
            $file->move(public_path('/assets/payroll'), $nama_file);

            config(['excel.import.startRow' => 2]);

            Excel::import(new PayrollImport($id), public_path('/assets/payroll/' . $nama_file));   //$id = periode

            Session::flash('sukses', 'Data Payroll Berhasil Diimport!');

            $rekon = TempPayroll::where('id', '>=', $idmin)->where('id', '<=', $idmax)->delete();

            return redirect()->back()->with(['bulan_f' => $bulan, 'tahun_f' => $tahun, 'periode_f' => $id]);
        }
    }

    
}
