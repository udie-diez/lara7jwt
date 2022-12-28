@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<div class="card">
    <div class="card-header header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a hidden class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ route('pinjamanInput') }}" title="Input Permohonan Pinjaman"> <i class="icon-plus2"></i> Permohonan Baru</a>
            </div>
        </div>
    </div>
 
    <table class="table basic">
        <thead>
            <tr class="text-center">
                <th>NO.</th>
                <th class="text-left">NAMA</th>
                <th>NIK</th>
                <th class="text-right">PERMOHONAN PLAFON (Rp.)</th>
                <th class="text-right">PLAFON DIPROSES (Rp.)</th>
                <th >TENOR (Bln)</th>
                <th>TGL.PENGAJUAN</th>
                <th>STATUS</th>
                <th class="text-center">PROSES</th>
            </tr>
        </thead>
        <tbody>
        @foreach($data as $row)
            @php 
                $status = ['PENGAJUAN BARU','DITOLAK','DIPENDING','SEDANG DIPROSES'];    
            @endphp

            
            <tr >
                <td>{{ $loop->iteration }}.</td>
                <td> {{ $row->nama }}</td>
                <td class="text-center">{{ $row->nik }}</td>
                <td class="text-right">{{ number_format($row->nilai,0) }}</td>
                <td class="text-right">{{ number_format($row->nilaifix,0) }}</td>
                <td class="text-center">{{ $row->tenor }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($row->tanggal)) }}</td>
                <td class="text-center"><span class="badge badge-warning">{{ $status[$row->status]}}</span></td>
                <td class="text-center" title="Data Pinjaman">
                    <div class="list-icons">
                        <a href="{{ url('pinjaman/show').'/'.$row->id }}"  class="btn btn-outline-info btn-sm" title="Proses Permohonan"> PROSES</a>
                    </div> 
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
<!-- /basic datatable --> 
@endsection