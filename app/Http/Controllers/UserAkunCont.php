<?php

namespace App\Http\Controllers;

use App\Aktivasi;
use App\Mail\SitrendyEmail;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Symfony\Component\CssSelector\Node\FunctionNode;

class UserAkunCont extends Controller
{
    //
    public function __construct()
    {
    }

    public function aktivasi($code){
        $data='';
        if(strlen($code)==10){
            $data = Aktivasi::where('code',$code)->where('status',0)->first();
        }
        if($data){
            $status = 1;
            return view('data_user.aktivasi',compact('code','status'));
        }else{
            $status = 0;
            return view('data_user.aktivasi',compact('code','status'));
        }
    }

    public function updateAktivasi(Request $request){
        $code = $request->code;
        $password = $request->password;
        $data = Aktivasi::where('code',$code)->where('status',0)->first();
        $id = $data->userid;
        User::where('id',$id)->update(['password'=>bcrypt($password), 'status'=>1]);
        Aktivasi::where('userid',$id)->update(['status'=>1, 'keterangan' => 'Activated']);
        $status = 2;
        return view('data_user.aktivasi',compact('code','status'));
    }

    public function lupaPassword(){
        $status = 0;
        return view('data_user.lupapassword',compact('status'));
    }

    public function resetPassword(Request $request){

        $email = $request->email;
        $data = User::where('email',$email)->first();
        if($data){
            $code = Str::random(10);
            
            Mail::to($email)->send(new SitrendyEmail('reset',$code,$data->name ?? ''));
            Aktivasi::where('userid',$data->id)->where('status',2)->delete();
            Aktivasi::insert([
                'code' => $code,
                'userid' => $data->id,
                'status' => 2,  //reset password aktif   ,3=reset password tidak aktif
                'keterangan' => 'Permintaan reset password'
            ]);
        
            $msg = "Silahkan cek email Anda, Kami telah mengirimkan link untuk mereset password Anda !";
            $status = 1;
            return view('data_user.lupapassword',compact('status','msg'));
        }else{
            $msg = "Maaf, Email yang Anda masukkan tidak ada dalam data Kami !";
            $status = 2;
            return view('data_user.lupapassword',compact('status', 'msg'));

        }
    }

    public function viewresetPassword($code){
        $data='';
        if(strlen($code)==10){
            $data = Aktivasi::where('code',$code)->where('status',2)->first();
        }
        if($data){
            $status = 1;
            return view('data_user.resetpassword',compact('code','status'));
        }else{
            $status = 0;
            return view('data_user.resetpassword',compact('code','status'));
        }
    }

    public function updateResetPassword(Request $request){
        $code = $request->code;
        $password = $request->password;
        $data = Aktivasi::where('code',$code)->where('status',2)->first();
        $id = $data->userid;
        User::where('id',$id)->update(['password'=>bcrypt($password)]);
        Aktivasi::where('userid',$id)->update(['status'=>3, 'keterangan' => 'Activated']);
        $status = 2;
        return view('data_user.resetpassword',compact('code','status'));
    }
}
