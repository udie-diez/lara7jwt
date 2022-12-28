@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
            <a class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('TargetCont@create') }}" title="Input Data Target" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Target</a>

            </div>
        </div>
    </div>
  
    <table class="table basic table-hover">
        <thead class="text-center">
            <tr>
                <th width="70px">NO.</th>
                <th hidden>ID</th>
                <th>NAMA</th>
                <th>NIK</th>
                <th width='200px'>JUMLAH TARGET (Rp.)</th>
                <th width="100px">TAHUN</th>
                <th width="70px" class="text-center">Aksi</th>
            </tr>
        </thead>
        <tbody class="text-center">
            @php $total=0 @endphp
            @foreach($data as $row)
            @php $total+=$row->nilai @endphp

            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ route('editTarget' , $row->targetid) }}</td>
                <td class="text-left"><a href="#" id="btn-edit" class="text-slate" title="Update Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">{{ $row->nama }}</a></td>
                <td> {{ $row->nik }}</td>
                <td class="text-right">{{ Rupiah($row->nilai) }}</td>
                <td> {{ $row->tahun }}</td>
                <td>
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Update Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        <a href="{{ action('TargetCont@destroy',['id'=>$row->targetid]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')" ><i class="icon-bin"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="font-weight-bold">
            <tr>
                <th colspan="3" class="text-right">Total</th>
                <th class="text-right">{{Rupiah($total) }}</th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>
<!-- /basic datatable -->
@endsection