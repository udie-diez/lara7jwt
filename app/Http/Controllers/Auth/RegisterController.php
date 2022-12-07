<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    /**
     * Show the form register
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    /**
     * Store a newly created user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email',
                'password' => 'required|string|min:6|confirmed',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/user');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'headers' => [
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf')
                ],
                'json' => $request->all()
            ]);
            $resp = json_decode($reqClient->getBody());
            return response()->json($resp, 201);
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
}
