@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('PengurusCont@create') }}" title="Input Data Pengurus" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Pengurus</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr>
                <th>NO.</th>
                <th hidden>ID</th>
                <th>NAMA</th>
                <th>NIK</th>
                <th>L/P</th>
                <th>PHONE</th>
                <th>EMAIL</th>
                <th>JABATAN</th>
                <th>STATUS</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('pengurus/').'/'.$row->id }}</td>
                <td><a href="#" id="btn-edit" class="list-icons-item text-teal-800" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">{{ $row->nama }}</a></td>
             
                <td> {{ $row->nik }}</td>
                <td>{{ $row->jk=='L' ? 'Laki-laki' : 'Perempuan'}}</td>
                <td>{{ $row->phone }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->jabatan }}</td>
                <td class="text-center">{{ $row->status == 1 ? 'AKTIF' : 'TIDAK AKTIF' }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" class="list-icons-item text-info-600" id="btn-edit" title="Update Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('PengurusCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div> 
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection