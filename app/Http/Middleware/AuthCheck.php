<?php

namespace App\Http\Middleware;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\URL;

class AuthCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!session()->has('accessToken') && !session()->has('refreshToken')) {
            return route('login');
        }

        $user = $request->session()->get('users');
        $accessToken = $request->session()->get('accessToken');
        $refreshToken = $request->session()->get('refreshToken');

        $payload = explode('.', $accessToken);
        $decodedPayload = base64_decode($payload[1]);
        $decodedJson = json_decode($decodedPayload);
        $today = Carbon::now()->tz('Asia/Jakarta');
        $accessExpired = Carbon::parse($decodedJson->exp)->tz('Asia/Jakarta');

        if ($today > $accessExpired) {
            try {
                $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/auth/refreshToken');
                $client = new \GuzzleHttp\Client();
                $reqClient = $client->request('POST', $url, [
                    'allow_redirects' => true,
                    'headers' => [
                        'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                    ],
                    'json' => ['refreshToken' => $refreshToken],
                ]);
                $resp = json_decode($reqClient->getBody());
                if ($resp->code === 200) {
                    $accessToken = $resp->data->token;
                    $refreshToken = $resp->data->refreshToken;
                    $request->session()->regenerate();
                    $request->session()->put('users', $user);
                    $request->session()->put('accessToken', $accessToken);
                    $request->session()->put('refreshToken', $refreshToken);
                }
                // return response()->json($resp, $resp->code);
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                // if ($e->hasResponse()) {
                //     $response = $e->getResponse();
                //     return response()->json([
                //         'code' => $response->getStatusCode(),
                //         'message' => $response->getReasonPhrase(),
                //     ], $response->getStatusCode());
                // }
                // return response()->json([
                //     'code' => $e->getCode(),
                //     'message' => $e->getMessage(),
                // ], $e->getCode());
            }
        }
        return $next($request);
    }
}
