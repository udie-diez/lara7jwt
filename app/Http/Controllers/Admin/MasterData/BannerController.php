<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.master_data.banner');
    }

    /**
     * Get all list data with datatables
     *
     * @return \Illuminate\Http\Response
     */
    public function list()
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/banner');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
            ]);
            $resp = json_decode($reqClient->getBody());
            $data = $resp->data;
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
                'title' => 'required|string',
                'image' => 'required|image|max:2000',
                'description' => 'nullable|string',
                'link' => 'required|string|url',
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
            $file = $request->file('image');
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/banner');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'multipart' => [
                    ['name' => 'title', 'contents' => $request->title],
                    ['name' => 'description', 'contents' => $request->description],
                    ['name' => 'link', 'contents' => $request->link],
                    ['name' => 'status', 'contents' => $request->status],
                    [
                        'name' => 'file',
                        'filename' => $file->getClientOriginalName(),
                        'contents' => fopen($file->getPathname(), 'r'),
                    ],
                ],
            ]);
            $resp = json_decode($reqClient->getBody());
            return response()->json($resp, 201);
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/banner/{$id}");
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

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'title' => 'required|string',
                'image' => 'required|image|max:2000',
                'description' => 'nullable|string',
                'link' => 'required|string|url',
                'status' => 'required',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()
            ], 400);
        }

        try {
            $file = $request->file('image');
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/banner/{$id}");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('PUT', $url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'multipart' => [
                    ['name' => 'title', 'contents' => $request->title],
                    ['name' => 'description', 'contents' => $request->description],
                    ['name' => 'link', 'contents' => $request->link],
                    ['name' => 'status', 'contents' => $request->status],
                    [
                        'name' => 'file',
                        'filename' => $file->getClientOriginalName(),
                        'contents' => fopen($file->getPathname(), 'r'),
                    ],
                ],
            ]);
            $resp = json_decode($reqClient->getBody());
            return response()->json($resp, 200);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/banner/{$id}");
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