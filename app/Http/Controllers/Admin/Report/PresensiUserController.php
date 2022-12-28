<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\PresensiUserExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PresensiUserController extends Controller
{
    public function index()
    {
        $tag = [
            'menu' => 'Laporan',
            'submenu' => 'Presensi User',
            'judul' => 'PRESENSI USER',
            'menuurl' => '',
            'modal' => 'false'
        ];
        return view('admin.report.presensi', ['tag' => $tag]);
    }

    public function list(Request $request)
    {
        $reqApi = $this->handle($request->idUser, $request->startDate, $request->endDate);
        $json = $reqApi->getContent();
        $data = json_decode($json);
        return DataTables::of($data->data)
            ->addIndexColumn()
            ->toJson();
    }

    public function export(Request $request)
    {
        $year = Carbon::parse($request->startDate)->format('Y');
        $month = Carbon::parse($request->endDate)->format('F');
        $reqApi = $this->handle($request->idUser, $request->startDate, $request->endDate);
        $json = $reqApi->getContent();
        $data = json_decode($json, true);
        $collection = isset($data['data']) ? collect($data['data']) : [];
        $export = new PresensiUserExport($year, $month, $request->name, $collection);

        return Excel::download($export, "PresensiUser - {$request->name} ({$month} {$year}).xlsx");
    }

    protected function handle($id, $start, $end)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/absen/{$id}");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
                'allow_redirects' => true,
                'headers' => [
                    'Authorization' => 'Bearer ' . session('accessToken'),
                    'appSecret' => env('API_SECRET', '!FKU!oc@fL,.WNX4_V5JgX!Kf'),
                ],
                'query' => [
                    'dateStart' => $start,
                    'dateEnd' => $end,
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
}
