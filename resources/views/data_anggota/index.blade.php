@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info modalMd" href="#" value="{{ action('AnggotaCont@create') }}" title="Input Data Anggota" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Anggota</a>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-info has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route('filterAnggota',1) }}" class="text-white">
                                <h2>{{ $rekap[1]->jumlah }}</h2>
                                <span class="text-uppercase">Aktif <i class="icon-circle-right2"></i></span>
                            </a>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-user-check icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-warning has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route('filterAnggota',0) }}" class="text-white">
                                <h2>{{ $rekap[0]->jumlah }}</h2>
                                <span class="text-uppercase">Non Aktif <i class="icon-circle-right2"></i></span>
                            </a>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-user-block icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route('filterAnggota',2) }}" class="text-white">
                                <h2>{{ $rekap[2]->jumlah ?? 0 }}</h2>
                                <span class="text-uppercase">Keluar <i class="icon-circle-right2"></i></span>
                            </a>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-user-block icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-teal has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <a href="{{ route('anggota') }}" class="text-white">

                                <h2>{{$rekap[0]->jumlah + $rekap[1]->jumlah + ($rekap[2]->jumlah ??  0) }}</h2>
                                <span class="text-uppercase">Jumlah <i class="icon-circle-right2"></i></span>
                            </a>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <table class="table basicx" width="100%">
            <thead>
                <tr>
                    <th class="export">NO.</th>
                    <th hidden>ID</th>
                    <th class="export">No.Anggota</th>
                    <th class="export">NAMA</th>
                    <th class="export">NIK</th>
                    <th class="export">PHONE</th>
                    <th class="export">EMAIL</th>
                    <th class="export">LOKASI KERJA</th>
                    <th class="export">JABATAN</th>
                    <th class="export">STATUS</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <?php
                    if($row->status==0){
                        $tagstatus = '<span class="badge badge-warning"> NON AKTIF</span></span>';
                    }else if($row->status==2){
                        $tagstatus = '<span class="badge badge-danger"> KELUAR </span><br><span style="font-size:smaller" >' . IndoTgl($row->tanggal_refund) . '</span>';
                    }else{
                        $tagstatus = '<span class="badge badge-info">AKTIF</span>';
                    }
                ?>
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td hidden>{{ url('anggota/').'/'.$row->id }}</td>
                    <td> {{ $row->nomor }}</td>
                    <td style="width: 200px;"><a href="#" id="btn-edit" class="list-icons-item text-teal-800" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">{{ $row->nama }}</a></td>
                    <td> {{ $row->nik }}</td>
                    <td>{{ $row->phone }}</td>
                    <td>{{ $row->email }}</td>
                    <td>{{ $row->lokasikerja }}</td>
                    <td>{{ $row->jabatan }}</td>
                    <td class="text-center"><?php echo $tagstatus ?></td>
                    <td class="text-center">
                        <div class="list-icons">
                            <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                            <a href="{{ action('AnggotaCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
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