@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info modalMd" href="#" value="{{ action('JenisSimpananCont@create') }}" title="Input Data Jenis Simpanan" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Jenis Simpanan</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr>
                <th>NO.</th>
                <th hidden>ID</th>
                <th>JENIS SIMPANAN</th>
                <th>BESAR SIMPANAN</th>
                <th>STATUS</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('jenis_simpanan/').'/'.$row->id }}</td>
                <td> {{ $row->nama }}</td>
                <td>{{ number_format($row->nilai,0,',','.') }}</td>
                <td>{{ $row->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Update Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('JenisSimpananCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection