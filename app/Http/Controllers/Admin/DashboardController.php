<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('jwt.verify', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.dashboard', ['count' => 0, 'anggota' => []]);
    }

    public function counter()
    {
        return response()->json(['count' => 0, 'anggota' => []], 200);
    }

    /**
     * Get all list data with datatables
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/user');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'query' => [
                    'keyword' => $request->keyword ?? '',
                    'pageSize' => $request->pageSize != -1 ? $request->pageSize : 10,
                    'page' => $request->page ?? 1,
                ],
            ]);
            $resp = json_decode($reqClient->getBody());
            $data = $resp->data->result;
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $buttons = '<div class="text-center">
                            <button type="button" class="action-edit btn btn-outline bg-primary text-primary btn-icon" data-rowid="' . $row->id . '">
                                <i class="icon-pencil7"></i>
                            </button>
                            <button type="button" class="action-delete btn btn-outline bg-danger text-danger btn-icon" data-rowid="' . $row->id . '">
                                <i class="icon-trash"></i>
                            </button>
                        </div>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->toJson();
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = json_decode($response->getBody());
                return response()->json([
                    'code' => $response->getStatusCode(),
                    'message' => $response->getReasonPhrase() . ". " . $body->message,
                ], $response->getStatusCode());
            }
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'kode' => 'nullable|string',
                'name' => 'required|string',
                'email' => 'required|string|email',
                'password' => 'required|string|min:6',
                'user_type' => 'nullable|string',
                'role' => 'required|string',
                'status' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors(),
            ], 400);
        }

        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/user');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'json' => $request->all(),
            ]);
            $resp = json_decode($reqClient->getBody());
            return response()->json($resp, 201);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = json_decode($response->getBody());
                return response()->json([
                    'code' => $response->getStatusCode(),
                    'message' => $response->getReasonPhrase() . ". " . $body->message,
                ], $response->getStatusCode());
            }
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/user/{$id}");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
            ]);
            $resp = json_decode($reqClient->getBody());
            return response()->json($resp, 200);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = json_decode($response->getBody());
                return response()->json([
                    'code' => $response->getStatusCode(),
                    'message' => $response->getReasonPhrase() . ". " . $body->message,
                ], $response->getStatusCode());
            }
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'description' => 'required|string',
                'isAnnualLeave' => 'required',
                'status' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors(),
            ], 400);
        }

        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/user/update');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('PUT', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'json' => $request->merge(['idUser' => $id]),
            ]);
            $resp = json_decode($reqClient->getBody());
            return response()->json($resp, 200);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = json_decode($response->getBody());
                return response()->json([
                    'code' => $response->getStatusCode(),
                    'message' => $response->getReasonPhrase() . ". " . $body->message,
                ], $response->getStatusCode());
            }
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/user/{$id}/delete");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('DELETE', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
            ]);
            $resp = json_decode($reqClient->getBody());
            return response()->json($resp, 200);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            if ($e->hasResponse()) {
                $response = $e->getResponse();
                $body = json_decode($response->getBody());
                return response()->json([
                    'code' => $response->getStatusCode(),
                    'message' => $response->getReasonPhrase() . ". " . $body->message,
                ], $response->getStatusCode());
            }
            return response()->json([
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ], $e->getCode());
        }
    }
}
