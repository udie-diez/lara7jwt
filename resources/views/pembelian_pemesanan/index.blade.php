@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info" href="{{ action('PembelianPemesananCont@create') }}" title="Input Data Pemesanan Pembelian" > <i class="icon-plus2"></i> Pemesanan Pembelian</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr>
                <th>NO.</th>
                <th>NOMOR</th>
                <th>TANGGAL</th>
                <th>VENDOR</th>
                <th>STATUS</th>
                <th class="text-right">SISA TAGIHAN (Rp)</th>
                <th class="text-right">TOTAL (Rp)</th>
                <th class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
        @if(isset($data))
        @foreach($data as $row)
            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td><a href="{{ route('show_po',$row->id) }}"  class="list-icons-item text-teal-800" title="Detail Data" >{{ '#'.$row->kode }}</a></td>
                <td> {{ IndoTgl($row->tanggal)}}</td>
                <td> {{ $row->vendor ?? '' }}</td>
                <td> <?php echo $row->status==0 ? '<span class="badge badge-danger">OPEN</span>' : '<span class="badge badge-success">CLOSED</span>' ?></td>
                <td class="text-right">{{ $row->sisa ?? '' }}</td>
                <td class="text-right">{{ Rupiah($row->total) }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('PembelianPemesananCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div>
                </td>
            </tr>
        @endforeach
        @endif
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection