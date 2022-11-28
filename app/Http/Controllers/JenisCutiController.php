<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisCutiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.master_data.jenis_cuti');
    }

    /**
     * Get all list data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request $request)
    {
        $data = JenisCuti::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                $btn = '<button type="button" class="btn btn-primary rounded-round" data-rowid="' . $row->id . '" ><i class="icon-pencil7 mr-2"></i> Edit</button>';
                $btn .= '<button type="button" class="btn btn-danger rounded-round" data-rowid="' . $row->id . '" ><i class="icon-cross3 mr-2"></i> Delete</button>';
                return $btn;
            })
            ->rawColumns(['action'])
            ->toJson();
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
                'alasan' => ['required', 'string'],
                'potong_cuti_tahunan' => ['required', 'string', 'in:ya,tidak'],
                'status' => ['required', 'string', 'in:aktif,tidak']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $jenisCuti = JenisCuti::create([
            'alasan' => $request->alasan,
            'potong_cuti_tahunan' => $request->potong_cuti_tahunan,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil disimpan',
            'data' => ['jenis_cuti' => $jenisCuti]
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jenisCuti = JenisCuti::findOrFail($id);

        if (!$jenisCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis cuti tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditemukan',
            'data' => ['jenis_cuti' => $jenisCuti]
        ], 200);
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
        $jenisCuti = JenisCuti::findOrFail($id);

        if (!$jenisCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis cuti tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'alasan' => ['required', 'string'],
                'potong_cuti_tahunan' => ['required', 'string', 'in:ya,tidak'],
                'status' => ['required', 'string', 'in:aktif,tidak']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $jenisCuti->update([
            'alasan' => $request->alasan,
            'potong_cuti_tahunan' => $request->potong_cuti_tahunan,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil diperbarui',
            'data' => ['jenis_cuti' => $jenisCuti]
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $jenisCuti = JenisCuti::findOrFail($id);

        if (!$jenisCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis cuti tidak ditemukan'
            ], 404);
        }

        $jenisCuti->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus',
            'data' => ['jenis_cuti' => $jenisCuti]
        ], 200);
    }
}
