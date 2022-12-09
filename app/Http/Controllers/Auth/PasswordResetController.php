<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class PasswordResetController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the form forgot password
     *
     * @return \Illuminate\Http\Response
     */
    public function showForgotForm()
    {
        return view('auth.forgot_password');
    }

    /**
     * Store the token and send link request into email
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function sendForgotLink(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string|email|exists:users',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors(),
            ], 400);
        }

        $token = Str::random(64);

        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgot_password', ['token' => $token], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        return response()->json([
            'code' => 201,
            'message' => 'We have e-mailed your password reset link!',
        ], 201);
    }

    /**
     * Show the form reset password
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    public function showResetForm($token)
    {
        return view('auth.reset_password', ['token' => $token]);
    }

    /**
     * Update the user password
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'required|string|email|exists:users',
                'password' => 'required|string|min:6|confirmed',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors(),
            ], 400);
        }

        $updatePassword = DB::table('password_resets')->where([
            'email' => $request->email,
            'token' => $request->token
        ])->first();

        if (!$updatePassword) {
            return response()->json([
                'code' => 401,
                'message' => 'Invalid token',
            ], 401);
        }

        $user = User::where('email', $request->email)
            ->update(['password' => bcrypt($request->password)]);

        DB::table('password_resets')->where([
            'email' => $request->email
        ])->delete();

        return response()->json([
            'code' => 200,
            'message' => 'You have successfully changed the password',
        ], 200);
    }
}
