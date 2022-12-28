@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info" href="{{ action('PembelianCont@create') }}" title="Input Data Pembelian"> <i class="icon-plus2"></i> Pembelian</a>
            </div>
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-orange has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2> Rp. 4.000.000,- </h2>
                            <span class="text-uppercase">Pembelian Belum Dibayar</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-coin-dollar icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-teal has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2> Rp. 1.000.000,- </h2>
                            <span class="text-uppercase">Pembelian Jatuh Tempo</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-stack2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-blue has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2> Rp. 11.000.000,- </h2>
                            <span class="text-uppercase">Pelunasan Bulan Ini</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-thumbs-up2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
             
        </div>
    </div>
    <div class="card-body">
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
                    <td><a href="{{ route('showPembelian',$row->id) }}" class="list-icons-item text-teal-800" title="Detail Data">{{ '#'.$row->kode }}</a></td>
                    <td> {{ IndoTgl($row->tanggal)}}</td>
                    <td> {{ $row->vendor ?? '' }}</td>
                    <td> <?php echo $row->status == 0 ? '<span class="badge badge-danger">OPEN</span>' : '<span class="badge badge-success">CLOSED</span>' ?></td>
                    <td class="text-right">{{ $row->sisa ?? '' }}</td>
                    <td class="text-right">{{ Rupiah($row->total) }}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            <a href="{{ route('showPembelian',$row->id) }}"   class="list-icons-item text-info-600" title="Ubah Data"><i class="icon-pencil7"></i></a>
                            <a href="{{ action('PembelianCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
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