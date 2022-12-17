<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\PresensiBulananUserExport;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class PresensiBulananUserController extends Controller
{
    public function index()
    {
        return view('admin.report.presensiBulanan');
    }

    public function list(Request $request)
    {
        $reqApi = $this->handle($request->startDate, $request->endDate);
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
        $reqApi = $this->handle($request->startDate, $request->endDate);
        $json = $reqApi->getContent();
        $data = json_decode($json);
        $collection = isset($data->data) ? $this->mapping($data->data) : [];
        $export = new PresensiBulananUserExport($year, $month, $collection);

        return Excel::download($export, "PresensiUser - {$month} {$year}.xlsx");
    }

    protected function handle($start, $end)
    {
        try {
            $url = URL::to(env('API_URL', 'https://api-presensi.chegspro.com') . "/absen");
            $client = new \GuzzleHttp\Client();
            $reqClient = $client->request('GET', $url, [
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
        $total_present = 0;
        $total_ontime = 0;
        $total_late = 0;
        $total_early = 0;
        $total_cuti = 0;
        $total_normal = 0;

        $collection = collect($data->result)->groupBy('userId')->toArray();
        foreach ($collection as $key => $item) {
            $presensiUser[$item[$key - 1]->userId]['userId'] = $item[$key - 1]->userId;
            $presensiUser[$item[$key - 1]->userId]['name'] = $item[$key - 1]->user->name;
            $presensiUser[$item[$key - 1]->userId]['total_present'] = $total_present += ($item[$key - 1]->check_in ? 1 : 0);
            $presensiUser[$item[$key - 1]->userId]['total_ontime'] = $total_ontime += ($item[$key - 1]->is_ontime ? 1 : 0);
            $presensiUser[$item[$key - 1]->userId]['total_late'] = $total_late += ($item[$key - 1]->is_late ? 1 : 0);
            $presensiUser[$item[$key - 1]->userId]['total_early'] = $total_early += ($item[$key - 1]->is_early ? 1 : 0);
            $presensiUser[$item[$key - 1]->userId]['total_cuti'] = $total_cuti += ($item[$key - 1]->is_cuti ? 1 : 0);
            $presensiUser[$item[$key - 1]->userId]['total_normal'] = $total_normal += ($item[$key - 1]->is_holiday ? 1 : 0);
        }

        return collect($presensiUser);
    }
}
