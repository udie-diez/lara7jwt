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
                <a class="btn btn-outline-info" href="{{ action('BiayaCont@create') }}" title="Input Biaya"> <i class="icon-plus2"></i> Biaya</a>
            </div>
        </div>
    </div>
    
    <div class="card-body">
        <table class="table basicx">
            <thead>
                <tr>
                    <th class="export">NO.</th>
                    <th class="export">TANGGAL</th>
                    <th class="export">NOMOR</th>
                    <th class="export">KATEGORI</th>
                    <th class="text-right export">JUMLAH (Rp)</th>
                    <th class="export">KETERANGAN</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data))
                @php $jumlah=0 @endphp
                @foreach($data as $row)
                @php $jumlah += $row->total @endphp
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td> {{ IndoTgl($row->tanggal)}}</td>
                    <td><a href="{{ route('showBiaya',$row->id) }}" class="list-icons-item text-teal-800" title="Detail Data">{{ '#'.$row->nomor }}</a></td>
                    <td> {{ $row->nama ?? '' }}</td>
                    <td class="text-right">{{ Rupiah_no($row->total) }}</td>
                    <td> <?php $row->no_po ? 'Project : '.$row->no_po : '';?></td>
                    <td class="text-center">
                        <div class="list-icons">
                            <a href="{{ route('showBiaya',$row->id) }}" class="list-icons-item text-teal-800" title="Detail Data"><i class="icon-pencil7"></i></a>
                            <a href="{{ action('BiayaCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <tfoot >
                <tr style="font-weight: bold;">
                    <td colspan="3"></td>
                    <td class="text-center">Total</td>
                    <td class="text-right">{{Rupiah_no($jumlah) }}</td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection