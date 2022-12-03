<?php

namespace App\Http\Controllers;

use App\Models\AlasanCuti;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AlasanCutiController extends Controller
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
        $jenisCuti = DB::select('select * from jenis_cuti where status = ?', ['aktif']);
        return view('admin.master_data.alasan_cuti', ['jenis_cuti' => $jenisCuti]);
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
        $alasanCuti = AlasanCuti::with('jenis_cuti');

        if ($request->has('alasan') && $request->alasan) {
            $alasanCuti = $alasanCuti->where('alasan', $request->alasan);
        }
        if ($request->has('status') && $request->status) {
            $alasanCuti = $alasanCuti->where('status', $request->status);
        }

        $alasanCuti = $alasanCuti->paginate($limit);

        if (!$alasanCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan cuti tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Alasan cuti ditemukan',
            'data' => ['alasan_cuti' => $alasanCuti]
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
        $data = AlasanCuti::with('jenis_cuti');
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
                'jenis_cuti_id' => 'required',
                'alasan' => 'required|string',
                'max_hari' => 'required|numeric|min:1',
                'status' => 'required|string|in:aktif,tidak',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $alasanCuti = AlasanCuti::create([
            'jenis_cuti_id' => $request->jenis_cuti_id,
            'alasan' => $request->alasan,
            'max_hari' => $request->max_hari,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil disimpan',
            'data' => ['alasan_cuti' => $alasanCuti]
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
        $alasanCuti = AlasanCuti::find($id);

        if (!$alasanCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan cuti tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditemukan',
            'data' => ['alasan_cuti' => $alasanCuti]
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
        $alasanCuti = AlasanCuti::find($id);

        if (!$alasanCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan cuti tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'jenis_cuti_id' => 'required',
                'alasan' => 'required|string',
                'max_hari' => 'required|numeric|min:1',
                'status' => 'required|string|in:aktif,tidak',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $alasanCuti->update([
            'jenis_cuti_id' => $request->jenis_cuti_id,
            'alasan' => $request->alasan,
            'max_hari' => $request->max_hari,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil diperbarui',
            'data' => ['alasan_cuti' => $alasanCuti]
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
        $alasanCuti = AlasanCuti::find($id);

        if (!$alasanCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Alasan cuti tidak ditemukan'
            ], 404);
        }

        $alasanCuti->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus',
            'data' => ['alasan_cuti' => $alasanCuti]
        ], 200);
    }
}
