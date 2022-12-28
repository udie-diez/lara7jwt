<?php

namespace App\Http\Controllers;

use App\Akses;
use App\Aktivasi;
use App\Helpers\UserAkses;
use App\Mail\SitrendyEmail;
use App\Pengelola;
use App\Pengurus;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (UserAkses::cek_akses('user', 'lihat') == false) return redirect(route('noakses'));

        $data = DB::select("select distinct IFNULL(IFNULL(pengurus.nama,pengelola.nama),'ADMINISTRATOR') as name, users.status, users.role, users.email, users.id, ua.keterangan from users LEFT JOIN pengurus ON SUBSTR(users.kode,1,1)='u' AND pengurus.id=SUBSTR(users.kode,2) LEFT JOIN pengelola ON SUBSTR(users.kode,1,1)='a' AND pengelola.id=SUBSTR(users.kode,2) LEFT JOIN user_aktivasi ua on ua.userid = users.id");
        // $data = User::orderByRaw('id DESC')->get();
        $tag = ['menu' => 'Master Data', 'submenu' => 'User', 'judul' => 'Daftar User', 'menuurl' => 'users', 'modal' => 'true'];
        return view('data_user.index', compact('tag', 'data'));
    }

    public function edit($id)
    {
        if (UserAkses::cek_akses('user', 'cud') == false) return 'Maaf, Anda tidak memiliki akses';
        if($id==1){
            return 'Forbidden' ;
        }
        $pengelola = Pengelola::where('status', 1)->select('id', 'nama', 'nik', DB::raw("'a' as kode"));
        $users = Pengurus::where('status', 1)->select('id', 'nama', 'nik', DB::raw("'u' as kode"))->union($pengelola)->get();
        // $anggota = Anggota::all();
        $data = DB::table('users')->where('id', $id)->first();
        return view('data_user.edit', compact('data', 'users'));
    }

    public function update(Request $request)
    {
        if (UserAkses::cek_akses('user', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;

        if ($id) {
            $data = User::find($request->id);
            $data->status = $request->status;
            $data->save();
        } else {
            $peg = substr($request->nama, 0, 1) == 'u' ? 'pengurus' : 'pengelola';
            if ($peg == 'pengelola') {
                $data = Pengelola::find(substr($request->nama, 1));
            } else {
                $data = Pengurus::find(substr($request->nama, 1));
            }
            // $data = DB::select("select IFNULL(pengurus.nama,pengelola.nama) as nama, IFNULL(pengurus.email,pengelola.email) as email from users LEFT JOIN pengurus ON SUBSTR(users.kode,1,1)='u' AND pengurus.id=SUBSTR(users.kode,2) LEFT JOIN pengelola ON SUBSTR(users.kode,1,1)='a' AND pengelola.id=SUBSTR(users.kode,2) where pegnurus.kode = '".$request->nama."'");
            $email = $data->email;
            $nama = $data->nama;
            if (!$email) {
                Session::flash('warning', 'Data Email belum ada, silahkan lengkapi email terlebih dahulu.');
                return redirect()->back();
            }

            // $cek = User::where('name',$nama)->first();
            // if($cek){
            //     Session::flash('warning',"Maaf, akun gagal dibuat. Akun `".$nama."` sudah ada.");
            //     return redirect()->back();
            // }
            $cek = User::where('email', $email)->first();
            if ($cek) {
                Session::flash('warning', "Maaf, akun gagal dibuat. Email `" . $email . "` sudah ada.");
                return redirect()->back();
            }
 
            $id = User::create([
                'kode' => $request->nama,
                'name' => $nama,
                'status' => 0,
                'email' => $email,
                'role' => 'karyawan'
            ]);
            $id = $id->id;

            if ($id > 0) {
                $code = Str::random(10);
                Mail::to($email)->send(new SitrendyEmail('aktivasi', $code, $nama));
                Aktivasi::insert([
                    'code' => $code,
                    'userid' => $id,
                    'status' => 0,
                    'keterangan' => 'Email aktivasi telah terkirim'
                ]);
            }
            Session::flash('sukses', 'User berhasil dibuat, dan email aktivasi telah terkirim.');
        }

        return redirect()->back();
    }

    public function updateAkses(Request $request)
    {
        if (UserAkses::cek_akses('user', 'cud') == false) return redirect(route('noakses'));

        Akses::where('userid', $request->id)->delete();

        $modulx = $request->modul;
        $modul = explode(',', $modulx);
        for ($i = 0; $i < count($modul); $i++) {
            $data = array();
            $namamodul = $modul[$i];
            $r = 'r_' . $namamodul;
            $cud = 'cud_' . $namamodul;
            $p = 'p_' . $namamodul;
            $data = ['userid' => $request->id, 'modul' => $namamodul, 'lihat' => $request->$r, 'cud' => $request->$cud, 'cetak' => $request->$p];
            Akses::create($data);
        }

        return redirect()->back();
    }

    public function create()
    {
        if (UserAkses::cek_akses('user', 'cud') == false) return 'Maaf, Anda tidak memiliki akses';

        $pengelola = Pengelola::where('status', 1)->select('id', 'nama', 'nik', DB::raw("'a' as kode"));
        $users = Pengurus::where('status', 1)->select('id', 'nama', 'nik', DB::raw("'u' as kode"))->union($pengelola)->get();
        return view('data_user.edit', compact('users'));
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('user', 'cud') == false) return redirect(route('noakses'));
        if($id==1){
            return redirect()->back();
        }
        User::destroy($id);
        // $data = DB::table('users')->where('id', $id)->delete();

        return redirect()->back();
    }

    public function akses($id)
    {
        if ($id > 0) {
            $user = DB::select("select IFNULL(pengurus.nama,pengelola.nama) as name, users.status, users.role, users.email, users.id from users LEFT JOIN pengurus ON SUBSTR(users.kode,1,1)='u' AND pengurus.id=SUBSTR(users.kode,2) LEFT JOIN pengelola ON SUBSTR(users.kode,1,1)='u' AND pengelola.id=SUBSTR(users.kode,2) WHERE users.id = $id");
            foreach ($user as $row) {
                $users['nama'] = $row->name;
                $users['email'] = $row->email;
                $users['id'] = $row->id;
                break;
            }
            $data = Akses::where('userid', $id)->get();
        }

        $tag = ['menu' => 'User', 'submenu' => 'Hak Akses', 'judul' => 'Hak Akses User', 'menuurl' => 'users', 'modal' => 'true'];
        return view('data_user.akses', compact('tag', 'data', 'users'));
    }
}
