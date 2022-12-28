@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
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
                <th>EMAIL</th>
                <th>TGL_REGISTER</th>
                <th>AKSI</th>
                <th class="text-center">PROSES</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)

            <tr >
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('register/').'/'.$row->id }}</td>
                <td> {{ $row->nama }}</td>
                <td>{{ $row->nik }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->created_at }}</td>
                <td ><span class="badge {{ $row->status ==0 ? 'badge-warning' : 'badge-info' }} ">{{ $row->status==0 ? 'TIDAK AKTIF' : 'AKTIF' }}</span></td>
                <td class="text-center" title="Data Register">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item" title="Proses Registrasi" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-eye8"></i></a>
                    </div> 
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection