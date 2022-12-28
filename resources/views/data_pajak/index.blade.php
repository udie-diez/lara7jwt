@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('PajakCont@create') }}" title="Input Data Pajak" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Pajak</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr>
                <th width="50px">NO.</th>
                <th hidden>ID</th>
                <th>NAMA PAJAK</th>
                <th>NILAI (%)</th>
                <th>AKUN PAJAK PEMBELIAN</th>
                <th>AKUN PAJAK PENJUALAN</th>
                <th width="50px" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('pajak/').'/'.$row->id }}</td>
                <td> <a href="#" class="text-teal-800" id="btn-edit" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">{{ $row->nama }}</a></td>
                <td> {{ $row->nilai }}</td>
                <td> {{ $row->kodein . ' - '. $row->namain }}</td>
                <td> {{ $row->kodeout . ' - '. $row->namaout }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" class="list-icons-item text-info-600" id="btn-edit" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('PajakCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div> 
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection