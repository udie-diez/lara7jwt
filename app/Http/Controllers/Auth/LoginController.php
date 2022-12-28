<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
// use Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->status == 0) {
            Auth::logout();
            return redirect('/login')->with('error', 'Maaf, status akun Anda tidak aktif');
        }

        $prop = ['name' => Auth::user()->name, 'ip' => Request()->ip(), 'info' => Request()->userAgent()];
        activity('login')->withProperties($prop)->log('Login successful');

        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/auth/login');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'json' => $request->all(),
            ]);
            $resp = json_decode($reqClient->getBody());
            if ($resp->code === 200) {
                $user = (array) $resp->data->user;
                $accessToken = $resp->data->accessToken->token;
                $refreshToken = $resp->data->accessToken->refreshToken;
                $request->session()->regenerate();
                $request->session()->put('users', $user);
                $request->session()->put('accessToken', $accessToken);
                $request->session()->put('refreshToken', $refreshToken);
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // dd($e->getMessage());
        }
    }

    public function showLoginform()
    {
        return view('auth.login');
    }

    public function me(Request $request)
    {
        $user = $request->session()->get('users');
        return response()->json([
            'code' => 200,
            'message' => 'Success',
            'data' => ['user' => $user],
        ], 200);
    }

    /**
     * Generate new refresh token
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function refresh(Request $request)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/auth/refreshToken');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'json' => ['refreshToken' => session('refreshToken')],
            ]);
            $resp = json_decode($reqClient->getBody());
            if ($resp->code === 200) {
                // $user = (array) $resp->data->user;
                $accessToken = $resp->data->token;
                $refreshToken = $resp->data->refreshToken;
                $request->session()->regenerate();
                // $request->session()->put('users', $user);
                $request->session()->put('accessToken', $accessToken);
                $request->session()->put('refreshToken', $refreshToken);
            }
            return response()->json($resp, $resp->code);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                return response()->json([
                    'code' => $response->getStatusCode(),
                    'message' => $response->getReasonPhrase(),
                ], $response->getStatusCode());
            }
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
