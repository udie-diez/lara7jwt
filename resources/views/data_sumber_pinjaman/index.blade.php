@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info modalMd" href="#" value="{{ action('SumberPinjamanCont@create') }}" title="Input Data Sumber Simpanan" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Sumber Pinjaman</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr>
                <th>NO.</th>
                <th hidden>ID</th>
                <th>SUMBER PINJAMAN</th>
                <th>KETERANGAN</th>
                <th class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('sumber_pinjaman/').'/'.$row->id }}</td>
                <td> {{ $row->nama }}</td>
                <td> {{ $row->keterangan }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Update Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('SumberPinjamanCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection