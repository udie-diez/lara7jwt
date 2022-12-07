<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Anggota;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function __construct()
    {
        // $this->middleware('jwt.verify', ['except' => ['index']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $count = Anggota::all()->count();
        $anggota = DB::table('anggota')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        return view('admin.dashboard', [
            'count' => $count,
            'anggota' => $anggota,
        ]);
    }

    public function counter()
    {
        $count = Anggota::all()->count();
        $anggota = DB::table('anggota')
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();
        return response()->json([
            'count' => $count,
            'anggota' => $anggota,
        ], 200);
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
        $anggota = Anggota::select('*');

        if ($request->has('no_anggota') && $request->no_anggota) {
            $anggota = $anggota->where('no_anggota', $request->no_anggota);
        }
        if ($request->has('nama') && $request->nama) {
            $anggota = $anggota->where('nama', $request->nama);
        }
        if ($request->has('nik') && $request->nik) {
            $anggota = $anggota->where('nik', $request->nik);
        }
        if ($request->has('phone_number') && $request->phone_number) {
            $anggota = $anggota->where('phone_number', $request->phone_number);
        }
        if ($request->has('email') && $request->email) {
            $anggota = $anggota->where('email', $request->email);
        }
        if ($request->has('lokasi_kerja') && $request->lokasi_kerja) {
            $anggota = $anggota->where('lokasi_kerja', $request->lokasi_kerja);
        }
        if ($request->has('jabatan') && $request->jabatan) {
            $anggota = $anggota->where('jabatan', $request->jabatan);
        }
        if ($request->has('status') && $request->status) {
            $anggota = $anggota->where('status', $request->status);
        }

        $anggota = $anggota->paginate($limit);

        if (!$anggota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Anggota ditemukan',
            'data' => ['anggota' => $anggota]
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
        $data = Anggota::all();
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
                'no_anggota' => 'required|string',
                'nama' => 'required|string',
                'nik' => 'required|numeric|unique:anggota,nik',
                'phone_number' => 'nullable|numeric|unique:anggota,phone_number',
                'email' => 'nullable|string|email|unique:anggota,email',
                'lokasi_kerja' => 'nullable|string',
                'jabatan' => 'nullable|jabatan',
                'status' => 'required|string|in:aktif,tidak,keluar',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $anggota = Anggota::create([
            'no_anggota' => $request->no_anggota,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'lokasi_kerja' => $request->lokasi_kerja,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil disimpan',
            'data' => ['anggota' => $anggota]
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
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditemukan',
            'data' => ['anggota' => $anggota]
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
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'no_anggota' => 'required|string',
                'nama' => 'required|string',
                'nik' => 'required|numeric|unique:anggota,nik,' . $id,
                'phone_number' => 'nullable|numeric|unique:anggota,phone_number,' . $id,
                'email' => 'nullable|string|email|unique:anggota,email,' . $id,
                'lokasi_kerja' => 'nullable|string',
                'jabatan' => 'nullable|jabatan',
                'status' => 'required|string|in:aktif,tidak,keluar',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $anggota->update([
            'no_anggota' => $request->no_anggota,
            'nama' => $request->nama,
            'nik' => $request->nik,
            'phone_number' => $request->phone_number,
            'email' => $request->email,
            'lokasi_kerja' => $request->lokasi_kerja,
            'jabatan' => $request->jabatan,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil diperbarui',
            'data' => ['jenis_cuti' => $anggota]
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
        $anggota = Anggota::find($id);

        if (!$anggota) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anggota tidak ditemukan'
            ], 404);
        }

        $anggota->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus',
            'data' => ['anggota' => $anggota]
        ], 200);
    }
}
