<?php

namespace App\Http\Controllers;

use App\Models\JenisCuti;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisCutiController extends Controller
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
        return view('admin.master_data.jenis_cuti');
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
        $jenisCuti = JenisCuti::select('*');

        if ($request->has('alasan') && $request->alasan) {
            $jenisCuti = $jenisCuti->where('alasan', $request->alasan);
        }
        if ($request->has('potong_cuti_tahunan') && $request->potong_cuti_tahunan) {
            $jenisCuti = $jenisCuti->where('potong_cuti_tahunan', $request->potong_cuti_tahunan);
        }
        if ($request->has('status') && $request->status) {
            $jenisCuti = $jenisCuti->where('status', $request->status);
        }

        $jenisCuti = $jenisCuti->paginate($limit);

        if (!$jenisCuti) {
            return response()->json([
                'status' => 'error',
                'message' => 'Jenis cuti tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Jenis cuti ditemukan',
            'data' => ['jenis_cuti' => $jenisCuti]
        ], 200);
    }

    /**
     * Get all list data with datatables
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
                $buttons = '<div class="text-center">
                    <div class="list-icons">
                        <div class="dropdown">
                            <a href="#" class="list-icons-item" data-toggle="dropdown">
                                <i class="icon-menu9"></i>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="#" class="dropdown-item action-edit" data-rowid="' . $row->id . '"><i class="icon-pencil7"></i> Edit</a>
                                <a href="#" class="dropdown-item action-delete" data-rowid="' . $row->id . '"><i class="icon-cross3"></i> Delete</a>
                            </div>
                        </div>
                    </div>
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
        $jenisCuti = JenisCuti::find($id);

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
        $jenisCuti = JenisCuti::find($id);

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
        $jenisCuti = JenisCuti::find($id);

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
