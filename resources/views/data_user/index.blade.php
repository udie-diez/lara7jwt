@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('UserController@create') }}" title="Input User" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> User</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="form-group row">
            <div class="col-sm-9">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                @if ($message = Session::get('sukses'))
                <div class="alert alert-success alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @endif
                @if ($message = Session::get('warning'))
                <div class="alert alert-danger alert-block">
                    <button type="button" class="close" data-dismiss="alert">×</button>
                    <strong>{{ $message }}</strong>
                </div>
                @endif
            </div>
        </div>
        <table class="table basic">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th hidden>ID</th>
                    <th>NAMA</th>
                    <th>USERNAME</th>
                    <th class="text-center">ROLE</th>
                    <th class="text-center">STATUS</th>
                    <th class="text-center">KETERANGAN</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)

                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td hidden>{{ url('users/').'/'.$row->id }}</td>
                    <td> {{ $row->name }}</td>
                    <td>{{ $row->email }}</td>
                    <td class="text-center">{{ strtoupper($row->role) }}</td>
                    <td class="text-center"><span class="badge {{ $row->status ==0 ? 'badge-warning' : 'badge-info' }} ">{{ $row->status==0 ? 'TIDAK AKTIF' : 'AKTIF' }}</span></td>
                    <td>{{$row->keterangan}}</td>
                    <td class="text-center" title="Data User">
                        <div class="list-icons">
                            <a href="{{ route('akses',$row->id) }}" class="list-icons-item text-violet" title="Hak Akses User"><i class="icon-stack-check"></i></a>
                            <a href="#" id="btn-edit" class="list-icons-item  text-info-600" title="User" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                            <a href="{{ route('destroyUsers',$row->id) }}" class="list-icons-item text-danger" title="Hapus User" onclick="return confirm('Anda yakin ingin menghapus data ini ?')"><i class="icon-bin"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection