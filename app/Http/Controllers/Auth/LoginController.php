<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['only' => 'showLoginform']);
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
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/auth/login');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'headers' => [
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf')
                ],
                'json' => $request->all()
            ]);
            $resp = json_decode($reqClient->getBody());
            if (isset($resp->code) && $resp->code === 200) {
                $user = (array) $resp->data->user;
                $accessToken = $resp->data->accessToken->token;
                $refreshToken = $resp->data->accessToken->refreshToken;
                $request->session()->regenerate();
                $request->session()->put('users', $user);
                $request->session()->put('accessToken', $accessToken);
                $request->session()->put('refreshToken', $refreshToken);
            }
            return response()->json($resp, 200);
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
     * Show the authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        $user = $request->session()->get('users');
        return response()->json([
            'status' => 'success',
            'message' => 'Akun yang terotentikasi',
            'data' => ['user' => $user]
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
        $validator = Validator::make(
            $request->all(),
            [
                'refreshToken' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/auth/refreshToken');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'headers' => [
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf')
                ],
                'json' => $request->all()
            ]);
            $resp = json_decode($reqClient->getBody());
            if (isset($resp->code) && $resp->code === 200) {
                // $user = (array) $resp->data->user;
                $accessToken = $resp->data->token;
                $refreshToken = $resp->data->refreshToken;
                $request->session()->regenerate();
                // $request->session()->put('users', $user);
                $request->session()->put('accessToken', $accessToken);
                $request->session()->put('refreshToken', $refreshToken);
            }
            return response()->json($resp, 200);
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
     * Logout the authenticated user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
