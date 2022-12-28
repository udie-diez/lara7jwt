@extends('layouts.home') 

@section('maincontent')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info modalMd" href="#" value="{{ action('PerusahaanCont@create') }}" title="Input Data Perusahaan" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Perusahaan</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr>
                <th>NO.</th>
                <th hidden>ID</th>
                <th>NAMA MITRA/PERUSAHAAN</th>
                <th>UNIT KERJA</th>
                <th>ALIAS</th>
                <th>ALAMAT</th>
                <th>KOTA</th>
                <th>STATUS</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('perusahaan/').'/'.$row->id }}</td>
                <td><a href="#" id="btn-edit" class="list-icons-item text-teal-800" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">{{ $row->nama }}</a></td>
                <td> {{ $row->unitkerja }}</td>
                <td> {{ $row->alias }}</td>
                <td> {{ $row->alamat }}</td>
                <td>{{ $row->kota }}</td>
                <td class="text-center">{{ $row->status == 1 ? 'AKTIF' : 'TIDAK AKTIF' }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('PerusahaanCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection