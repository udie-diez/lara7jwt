<?php

namespace App\Http\Controllers\Admin\MasterData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class HariLiburController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tag = [
            'menu' => 'Master Data',
            'submenu' => 'Hari Libur',
            'judul' => 'HARI LIBUR',
            'menuurl' => '',
            'modal' => 'false'
        ];
        return view('admin.master_data.hari_libur', ['tag' => $tag]);
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
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/holiday/byRange');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'query' => [
                    'startDate' => $request->startDate,
                    'endDate' => $request->endDate,
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
                'year' => 'required|numeric',
                'date.*' => 'required|date_format:Y-m-d',
                'description.*' => 'required|string',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'message' => $validator->errors()
            ], 400);
        }

        $mappedHolidays = $this->mapHolidays($request->all());

        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/holiday/add');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('POST', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'json' => $mappedHolidays,
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
     * @param  string  $date
     * @return \Illuminate\Http\Response
     */
    public function show($date)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/holiday/byDate");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'query' => ['date' => $date],
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
                'date' => 'required|date_format:Y-m-d',
                'description' => 'required|string',
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
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . '/holiday/update');
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('PUT', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'json' => array_merge(['id' => $id], $request->all()),
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
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/holiday/{$id}/delete");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('DELETE', $url, [
                'allow_redirects' => true,
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

    protected function mapHolidays($request)
    {
        foreach ($request['date'] as $key => $value) {
            $holidays[] = [
                'id' => null,
                'date' => $value,
                'description' => $request['description'][$key],
                'status' => true,
            ];
        }
        return [
            'year' => $request['year'],
            'holidays' => $holidays,
        ];
    }
}
