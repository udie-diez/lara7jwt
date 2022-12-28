@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th width="50px">NO.</th>
                    <th hidden>ID</th>
                    <th>NAMA PAJAK</th>
                    <th>NILAI (%)</th>
                    <th>AKUN PAJAK PEMBELIAN</th>
                    <th>AKUN PAJAK PENJUALAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td hidden>{{ url('pajak/').'/'.$row->id }}</td>
                    <td> {{ $row->nama }}</td>
                    <td> {{ $row->nilai }}</td>
                    <td><a href="{{ route('detailAkun', $row->akuninid ?? '')}}" id="btn-edit" class="list-icons-item text-teal-800" title="Transaksi Akun"><?= @($row->jenis == 1 ? '&nbsp;&nbsp;&nbsp; ' : '') . $row->kodein . ' - ' . $row->namain ?></a></td>
                    <td><a href="{{ route('detailAkun', $row->akunoutid ?? '')}}" id="btn-edit" class="list-icons-item text-teal-800" title="Transaksi Akun"><?= @($row->jenis == 1 ? '&nbsp;&nbsp;&nbsp; ' : '') . $row->kodeout . ' - ' . $row->namaout ?></a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection