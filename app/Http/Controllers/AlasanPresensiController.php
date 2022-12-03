<?php

namespace App\Http\Controllers;

use App\Models\AlasanPresensi;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlasanPresensiController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.master_data.alasan_presensi');
    }

    /**
     * Get all list data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        $limit = $request->limit ?? 10;
        $alasanPresensi = AlasanPresensi::select('*');

        if ($request->has('alasan') && $request->alasan) {
            $alasanPresensi = $alasanPresensi->where('alasan', $request->alasan);
        }
        if ($request->has('status') && $request->status) {
            $alasanPresensi = $alasanPresensi->where('status', $request->status);
        }

        $alasanPresensi = $alasanPresensi->paginate($limit);

        if (!$alasanPresensi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan presensi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Alasan presensi ditemukan',
            'data' => ['alasan_presensi' => $alasanPresensi]
        ], 200);
    }

    /**
     * Get all list data with datatables
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request)
    {
        $data = AlasanPresensi::all();
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
                'alasan' => 'required|string',
                'status' => 'required|string|in:aktif,tidak',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $alasanPresensi = AlasanPresensi::create([
            'alasan' => $request->alasan,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil disimpan',
            'data' => ['alasan_presensi' => $alasanPresensi]
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
        $alasanPresensi = AlasanPresensi::find($id);

        if (!$alasanPresensi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan presensi tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditemukan',
            'data' => ['alasan_presensi' => $alasanPresensi]
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
        $alasanPresensi = AlasanPresensi::find($id);

        if (!$alasanPresensi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan presensi tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'alasan' => 'required|string',
                'status' => 'required|string|in:aktif,tidak',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $alasanPresensi->update([
            'alasan' => $request->alasan,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil diperbarui',
            'data' => ['alasan_presensi' => $alasanPresensi]
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
        $alasanPresensi = AlasanPresensi::find($id);

        if (!$alasanPresensi) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan presensi tidak ditemukan'
            ], 404);
        }

        $alasanPresensi->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus',
            'data' => ['alasan_presensi' => $alasanPresensi]
        ], 200);
    }
}
