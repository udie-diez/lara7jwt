<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use DataTables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.verify', ['except' => ['index']]);
        if (!Session::has('users')) {
            redirect()->route('login');
        }
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
                'gambar' => 'nullable|image|max:2000',
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

        if ($request->file('gambar')) {
            Storage::delete($banner->gambar);
            $path = Storage::putFile('public/images/banners', $request->file('gambar'));
        }

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
