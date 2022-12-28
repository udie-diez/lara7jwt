<?php

namespace App\Http\Controllers;

use App\Anggota;
use App\Helpers\UserAkses;
use App\Register;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

date_default_timezone_set('Asia/Jakarta');

class RegisterCont extends Controller
{
    
    public function index(){
        // $this->middleware('auth');
        if(UserAkses::cek_akses('register','lihat')==false)return redirect(route('noakses')) ;

        $data = Register::orderByRaw('id DESC')->get();
        $tag = ['menu'=> 'Master Data','submenu'=>'Register','judul'=>'Daftar Register', 'menuurl'=>'pengurus','modal'=>'true'];
        return view('register.index', compact('tag','data'));
    }

    public function store(Request $request){

        $request->validate([
            'captcha' => 'required|captcha'
        ]);

        $register = new Register();
        $register->nama = $request->name;
        $register->nik = $request->numbers;
        $email = $request->numbers.'@telkom.co.id';
        $register->email = $email;

        $register->password = Hash::make($request->password);
        $register->status = 0;
        $register->save();
        
        Session::flash('sukses','Proses pendaftaran berhasil. Untuk proses selanjutnya Silahkan cek email Anda di '.$email);
        return redirect()->back(); 
    }

    public function create(){
        return view('register.register');
    }

    public function edit($id){
        $data = Register::find($id);
        return view('register.edit', compact('data'));
    }

    public function update(Request $request){

        $id = $request->id;
        $register = Register::find($id);
        $register->status = 1;
        $register->role = $request->rule ;
        $register->save();
        
        if($request->rule==9){
            $role = 'Admin';
        }else if($request->rule==1){
            $role = 'Pengurus';
        }else if($request->rule==2){
            $role = 'Pengelola';
        }else{
            $role = 'Anggota';
        }

        User::create([
            'name' => $register['nama'],
            'email' => $register['email'],
            'password' => $register['password'],
            'role' => $role,
        ]);

       $anggota =  Anggota::where('nik',$register['nik'])->first();
       if(!$anggota){
           $nomor = $this->buatKode('anggota','nomor', 'AT');
        Anggota::create([
            'nama' => $register['nama'],
            'email' => $register['email'],
            'nomor' => $nomor,
            'nik' => $register['nama'],
            'status' => 1
        ]);

       }

        return redirect()->back(); 

    }
    function buatKode($tabel,$kolom, $inisial){
        
        $row = DB::table($tabel)->selectRaw("max($kolom) as kode")->first();
        $nourut = substr($row->kode, -5);
        $nourut++;
        $nourut = sprintf("%05s", $nourut);
         
        return $inisial.$nourut;
    }
}
