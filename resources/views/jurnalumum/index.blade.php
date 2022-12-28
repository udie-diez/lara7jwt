@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info btn-sm" href="{{ action('JurnalUmumCont@create') }}" title="Input Jurnal Umum"> <i class="icon-plus2"></i> Jurnal Umum</a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <table class="table basic">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>TANGGAL</th>
                    <th>NOMOR</th>
                    <th>KETERANGAN</th>
                    <th class="text-right">NILAI (Rp)</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data))
                @foreach($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td> {{ IndoTgl($row->tanggal)}}</td>
                    <td><a href="{{ route('showJurnalumum',$row->id) }}" class="list-icons-item text-teal-800" title="Detail Data">{{ '#'.$row->nomor }}</a></td>
                    <td >{{  $row->catatan }}</td>
                    <td class="text-right">{{ Rupiah($row->nilai) }}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                            <a href="{{ action('JurnalUmumCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data Jurnal ini ??')"><i class="icon-bin"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection