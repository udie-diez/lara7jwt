<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
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
        return view('admin.master_data.banner');
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
        $banner = Banner::select('*');

        if ($request->has('judul') && $request->judul) {
            $banner = $banner->where('judul', $request->judul);
        }
        if ($request->has('deskripsi') && $request->deskripsi) {
            $banner = $banner->where('deskripsi', $request->deskripsi);
        }
        if ($request->has('status') && $request->status) {
            $banner = $banner->where('status', $request->status);
        }

        $banner = $banner->paginate($limit);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Banner ditemukan',
            'data' => ['banner' => $banner]
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
        $data = Banner::all();
        return DataTables::of($data)
            ->addIndexColumn()
            ->editColumn('gambar', function (Banner $banner) {
                $path = Storage::url($banner->gambar);
                return !$banner->gambar ? null : '<img src="' . url($path) . '">';
            })
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
            ->rawColumns(['gambar', 'action'])
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
                'judul' => 'required|string',
                'gambar' => 'required|image|max:2000',
                'deskripsi' => 'nullable|string',
                'link' => 'nullable|string|url',
                'status' => 'required|string|in:aktif,tidak',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        $path = Storage::putFile('public/images/banners', $request->file('gambar'));

        $banner = Banner::create([
            'judul' => $request->judul,
            'gambar' => $path,
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
        $banner = Banner::find($id);

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
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make(
            $request->all(),
            [
                'judul' => 'required|string',
                'gambar' => 'required|image|max:2000',
                'deskripsi' => 'nullable|string',
                'link' => 'nullable|string|url',
                'status' => 'required|string|in:aktif,tidak',
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                $request->all(),
                'status' => 'error',
                'message' => $validator->errors()
            ], 400);
        }

        Storage::delete($banner->gambar);
        $path = Storage::putFile('public/images/banners', $request->file('gambar'));

        $banner->update([
            'judul' => $request->judul,
            'gambar' => $path,
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
        $banner = Banner::find($id);

        if (!$banner) {
            return response()->json([
                'status' => 'error',
                'message' => 'Banner tidak ditemukan'
            ], 404);
        }

        Storage::delete($banner->gambar);
        $banner->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil dihapus',
            'data' => ['banner' => $banner]
        ], 200);
    }
}
