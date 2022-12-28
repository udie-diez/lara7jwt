@extends('layouts.home') 

@section('maincontent')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info btn-sm modalMd " href="#" value="{{ action('ProdukCont@create') }}" title="Input Data Produk" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Produk</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr>
                <th>NO.</th>
                <th hidden>ID</th>
                <th>KODE</th>
                <th>NAMA PRODUK</th>
                <th>MERK/SPESIFIKASI</th>
                <th>SATUAN</th>
                <th>JENIS</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('produk/').'/'.$row->id }}</td>
                <td> {{ $row->kode }}</td>
                <td><a href="#" id="btn-edit" class="list-icons-item text-teal-800" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">{{ $row->nama }}</a></td>
                <td> {{ $row->keterangan }}</td>
                <td>{{ $row->satuan }}</td>
                <td> {{ $row->jenis }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('ProdukCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection