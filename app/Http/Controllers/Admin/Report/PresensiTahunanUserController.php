<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\PresensiTahunanUserExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PresensiTahunanUserController extends Controller
{
    public function index()
    {
        $tag = [
            'menu' => 'Laporan',
            'submenu' => 'Presensi Tahunan',
            'judul' => 'PRESENSI TAHUNAN',
            'menuurl' => '',
            'modal' => 'false'
        ];
        return view('admin.report.presensiTahunan', ['tag' => $tag]);
    }

    public function list(Request $request)
    {
        $reqApi = $this->handle($request->idUser, $request->startDate, $request->endDate);
        $json = $reqApi->getContent();
        $data = json_decode($json);
        $collection = $this->mapping($data->data);
        return DataTables::of($collection)
            ->addIndexColumn()
            ->toJson();
    }

    public function export(Request $request)
    {
        $year = Carbon::parse($request->startDate)->format('Y');
        $month = Carbon::parse($request->endDate)->format('F');
        $reqApi = $this->handle($request->idUser, $request->startDate, $request->endDate);
        $json = $reqApi->getContent();
        $data = json_decode($json);
        $collection = isset($data->data) ? $this->mapping($data->data) : [];
        $export = new PresensiTahunanUserExport($year, $request->name, $collection);

        return Excel::download($export, "PresensiUser - {$year} ({$request->name}).xlsx");
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

    public function mapping($data)
    {
        $collection = collect($data)
            ->groupBy(function ($item) {
                return Carbon::parse($item->date)->tz('Asia/Jakarta')->format('F');
            })
            ->toArray();
        $presensiUser = [];
        $i = 0;
        foreach ($collection as $key => $item) {
            $total_present = 0;
            $total_ontime = 0;
            $total_late = 0;
            $total_early = 0;
            $total_cuti = 0;
            $total_normal = 0;

            foreach ($item as $row) {
                $presensiUser[$key]['year'] = Carbon::parse($row->date)->tz('Asia/Jakarta')->format('Y');
                $presensiUser[$key]['month'] = $key;
                $presensiUser[$key]['userId'] = $row->userId;
                $presensiUser[$key]['name'] = $row->user->name;
                $presensiUser[$key]['total_present'] = $total_present += ($row->check_in ? 1 : 0);
                $presensiUser[$key]['total_ontime'] = $total_ontime += ($row->is_ontime ? 1 : 0);
                $presensiUser[$key]['total_late'] = $total_late += ($row->is_late ? 1 : 0);
                $presensiUser[$key]['total_early'] = $total_early += ($row->is_early ? 1 : 0);
                $presensiUser[$key]['total_cuti'] = $total_cuti += ($row->is_cuti ? 1 : 0);
                $presensiUser[$key]['total_normal'] = $total_normal += ($row->is_holiday ? 1 : 0);
            }
            $i++;
        }

        return collect($presensiUser);
    }
}
