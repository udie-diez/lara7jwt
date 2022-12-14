<?php

namespace App\Http\Controllers\Admin\Report;

use App\Exports\PresensiTahunanUserExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PresensiTahunanUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest', ['only' => 'export']);
    }

    public function export()
    {
        $collection = collect((object) [
            'year' => 2022,
            'user' => 'Anaking',
            'rows' => [
                (object) [
                    'month' => '1',
                    'presence' => 'twitter',
                    'on_time' => 'twitter.com',
                    'late' => 'twitter.com',
                    'fast_leave' => 'twitter.com',
                    'absence' => 'twitter.com',
                    'normalization' => 'twitter.com',
                ],
                (object) [
                    'month' => '2',
                    'presence' => 'google',
                    'on_time' => 'google.com',
                    'late' => 'google.com',
                    'fast_leave' => 'google.com',
                    'absence' => 'google.com',
                    'normalization' => 'google.com',
                ],
                (object) [
                    'month' => '3',
                    'presence' => 'facebook',
                    'on_time' => 'facebook.com',
                    'late' => 'facebook.com',
                    'fast_leave' => 'facebook.com',
                    'absence' => 'facebook.com',
                    'normalization' => 'facebook.com',
                ]
            ],
        ]);
        // $collection = collect([['a' => 1, 'b' => 2, 'c' => 3], ['a' => 4, 'b' => 5, 'c' => 6]]);
        $export = new PresensiTahunanUserExport($collection);

        return Excel::download($export, 'PresensiTahunanUser.xlsx');
    }
}
