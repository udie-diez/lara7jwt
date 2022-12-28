<?php

namespace App\Http\Controllers;

use App\Helpers\UserAkses;
use App\Pengelola;
use App\Target;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class TargetCont extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (UserAkses::cek_akses('target_pegawai', 'lihat') == false) return redirect(route('noakses'));

        $data = Pengelola::join('target', 'target.pengelolaid', 'pengelola.id')
            ->where('pengelola.status', 1)
            ->select('pengelola.id', 'target.nilai', 'target.tahun', 'pengelola.nama', 'pengelola.nik','target.id as targetid')
            ->orderbyraw('target.tahun desc')
            ->orderbyraw('pengelola.nama asc')
            ->get();


        $tag = ['menu' => 'Master Data', 'submenu' => 'Target Pegawai', 'judul' => 'Daftar Target Pegawai', 'menuurl' => 'target', 'modal' => 'true'];
        return view('data_target.index', compact('tag', 'data'));
    }

    public function create()
    {
        if (UserAkses::cek_akses('target_pegawai', 'cud') == false) return redirect(route('noakses'));
        $pengelola = Pengelola::where('status', 1)->get();

        return view('data_target.create', compact('pengelola'));
    }

    public function destroy($id)
    {
        if (UserAkses::cek_akses('target_pegawai', 'cud') == false) return redirect(route('noakses'));
        Target::destroy($id);
        return redirect()->back();
    }

    public function edit($id)
    {
        $data = Pengelola::leftjoin('target', 'target.pengelolaid', 'pengelola.id')
            ->where('target.id', $id)
            // ->where('tahun',date('Y'))
            ->select('pengelola.id as pengelolaid', 'pengelola.nama', 'target.id', 'target.nilai', 'target.tahun')
            ->first();
        if (!$data) {
            $data = Pengelola::where('id', $id)->select('pengelola.id as pengelolaid', 'pengelola.*', DB::raw('0 as id'))
                ->first();
        }

        return view('data_target.form', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if (UserAkses::cek_akses('target_pegawai', 'cud') == false) return redirect(route('noakses'));

        $id = $request->id;
        if ($id > 0) {
            $data = Target::find($request->id);
            $data->pengelolaid = $request->pengelola;
            $data->nilai = str_replace('.', '', $request->nilai);
            $data->tahun = $request->tahun;
            $data->save();
        } else {
            $id = Target::create([
                'pengelolaid' => $request->pengelola,
                'nilai' => str_replace('.', '', $request->nilai),
                'tahun' => $request->tahun
            ]);
            $id = $id->id;
        }

        if ($id > 0) {
            Session::flash('sukses', 'Data target BERHASIL disimpan .');
        } else {
            Session::flash('warning', 'Data target GAGAL disimpan .');
        }
        return redirect()->back();
    }
}
