<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\ApiClientController as ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'showLoginform', 'loginSubmit']]);
        $this->middleware('jwt.xauth', ['except' => ['login', 'showLoginform', 'loginSubmit', 'refresh']]);
        $this->middleware('jwt.verify', ['only' => ['refresh']]);
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
     * Submit the login form
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function loginSubmit(Request $request)
    {
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

        if (!$token = auth('api')->claims(['xtype' => 'auth'])->attempt($validator->validated())) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized user login attempt'
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
            'message' => 'Current authenticated user',
            'data' => ['user' => auth('api')->user()]
        ], 200);
    }

    /**
     * Logout the authenticated user
     *
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        auth('api')->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ], 200);
    }

    /**
     * Generate new refresh token
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $access_token = auth('api')->claims(['xtype' => 'auth'])->refresh(true, true);
        auth('api')->setToken($access_token);
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
            'message' => 'Successfully authenticated',
            'data' => [
                'user' => auth('api')->user(),
                'token_type' => 'bearer',
                'access_token' => $token,
                'access_expires_in' => auth('api')->factory()->getTTL() * 60,
                'refresh_token' => auth('api')
                    ->claims([
                        'xtype' => 'refresh',
                        'xpair' => auth('api')->payload()->get('jti')
                    ])
                    ->setTTL(auth('api')->factory()->getTTL() * 3)
                    ->tokenById(auth('api')->user()->id),
                'refresh_expires_in' => auth('api')->factory()->getTTL() * 60
            ]
        ], 200);
    }
}
