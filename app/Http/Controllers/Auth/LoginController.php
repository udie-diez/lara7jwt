<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['login', 'showLoginform', 'loginSubmit']]);
        $this->middleware('jwt.xauth', ['except' => ['login', 'showLoginform', 'loginSubmit', 'refresh']]);
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

    public function loginSubmit(Request $request)
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

        try {
            $url = env('API_URL', 'https://api-presensi.chegspro.com');
            $client = new \GuzzleHttp\Client([
                'base_uri' => $url
            ]);
            $reqClient = $client->request('POST', 'auth/login', [
                'headers' => [
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf')
                ],
                'json' => $request->all()
            ]);
            $resp = json_decode($reqClient->getBody());
            if ($resp->code === 200) {
                $user = new User((array) $resp->data->user);
                $accessToken = $resp->data->accessToken->token;
                $refreshToken = $resp->data->accessToken->refreshToken;
                Auth::guard('web')->login($user);
                Auth::guard('api')->login($user);
                $request->session()->regenerate();
                $request->session()->put('users', (array) $resp->data->user);
                $request->session()->put('accessToken', $accessToken);
                $request->session()->put('refreshToken', $refreshToken);
            }
            return $reqClient->getBody();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return response()->json([
                    'status' => 'error',
                    'message' => $response->getReasonPhrase(),
                ], $response->getStatusCode());
            }
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
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
