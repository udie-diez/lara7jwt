<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\CutiUserExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class CutiUserController extends Controller
{
    public function index()
    {
        $tag = [
            'menu' => 'Laporan',
            'submenu' => 'Cuti User',
            'judul' => 'CUTI USER',
            'menuurl' => '',
            'modal' => 'false'
        ];
        return view('admin.report.cuti', ['tag' => $tag]);
    }

    public function list(Request $request)
    {
        $reqApi = $this->handle($request->idUser, $request->year);
        $json = $reqApi->getContent();
        $data = json_decode($json);
        return DataTables::of($data->data)
            ->addIndexColumn()
            ->toJson();
    }

    public function export(Request $request)
    {
        $reqApi = $this->handle($request->idUser, $request->year);
        $json = $reqApi->getContent();
        $data = json_decode($json, true);
        $collection = isset($data['data']) ? collect($data['data']) : [];
        $export = new CutiUserExport($request->year, $request->name, $collection);

        return Excel::download($export, "CutiUser - {$request->year} ({$request->name}).xlsx");
    }

    protected function handle($id, $year)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/pengajuanCuti/byDate/{$id}");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'query' => [
                    'year' => $year,
                ],
            ]);
            $resp = json_decode($reqClient->getBody(), true);
            return response()->json($resp);
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

    public function dummyJson()
    {
        return '{"data":[
            {
                "id": 1,
                "date": "2022-11-02,2022-11-03",
                "keterangan": "Sakit Batuk",
                "is_approve": "REJECT",
                "jenis_cuti": "Sakit",
                "alasan": "Sakit Batuk",
                "annualLeaveSpend": 0,
                "dokumen": "pengajuanCuti/1298d1d07111a628c390859529575f7c.jpeg",
                "createdAt": "2022-12-15T10:34:37.000Z",
                "updatedAt": "2022-12-17T11:01:06.000Z",
                "userId": 1
            }
        ]}';
    }
}
