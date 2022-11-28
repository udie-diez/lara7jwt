<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.master_data.banner');
    }

    /**
     * Get all list data
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function list(Request  $request)
    {
        $data = Banner::all();
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
                'judul' => ['required', 'string'],
                'gambar' => ['nullable', 'string'],
                'deskripsi' => ['nullable', 'string'],
                'link' => ['nullable', 'string', 'url'],
                'status' => ['required', 'string', 'in:aktif,tidak']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $banner = Banner::create([
            'judul' => $request->judul,
            'gambar' => $request->gambar,
            'deskripsi' => $request->deskripsi,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil disimpan',
            'data' => ['banner' => $banner]
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
        $banner = Banner::findOrFail($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil ditemukan',
            'data' => ['banner' => $banner]
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
        $banner = Banner::findOrFail($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'judul' => ['required', 'string'],
                'gambar' => ['nullable', 'string'],
                'deskripsi' => ['nullable', 'string'],
                'link' => ['nullable', 'string', 'url'],
                'status' => ['required', 'string', 'in:aktif,tidak']
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $banner->update([
            'judul' => $request->judul,
            'gambar' => $request->gambar,
            'deskripsi' => $request->deskripsi,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil diperbarui',
            'data' => ['banner' => $banner]
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
        $banner = Banner::findOrFail($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner tidak ditemukan'
            ], 404);
        }

        $banner->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus',
            'data' => ['banner' => $banner]
        ], 200);
    }
}
