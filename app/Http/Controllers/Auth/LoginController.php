<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'showLoginform']]);
        $this->middleware('jwt.xauth', ['except' => ['login', 'showLoginform', 'refresh']]);
        $this->middleware('jwt.xrefresh', ['only' => ['refresh']]);
    }

    /**
     * Show the form login
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginform()
    {
        return view('auth.login');
    }

    /**
     * Attempt the user to login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string', 'min:6']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        if (!$token = Auth::guard('api')->claims(['xtype' => 'auth'])->attempt($validator->validated())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Percobaan masuk ke sistem gagal'
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Show the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function me()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Akun yang terotentikasi',
            'data' => ['user' => Auth::guard('api')->user()]
        ], 200);
    }

    /**
     * Logout the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil keluar dari sistem'
        ], 200);
    }

    /**
     * Generate new refresh token
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $access_token = Auth::guard('api')->claims(['xtype' => 'auth'])->refresh(true, true);
        Auth::guard('api')->setToken($access_token);
        return $this->respondWithToken($access_token);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $token
     * @return \Illuminate\Http\Response
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Akun berhasil terotentikasi',
            'data' => [
                'user' => Auth::guard('api')->user(),
                'token_type' => 'bearer',
                'access_token' => $token,
                'access_expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
                'refresh_token' => Auth::guard('api')
                    ->claims([
                        'xtype' => 'refresh',
                        'xpair' => Auth::guard('api')->payload()->get('jti')
                    ])
                    ->setTTL(Auth::guard('api')->factory()->getTTL() * 3)
                    ->tokenById(Auth::guard('api')->user()->id),
                'refresh_expires_in' => Auth::guard('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
