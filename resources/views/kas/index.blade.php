@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}<br><span class="text-muted font-size-sm"><sup>*</sup> saldo pada jurnal</span></h5>
        <div class="header-elements">
			<div class="btn-group">
				<button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">Tindakan</button>
				<div class="dropdown-menu dropdown-menu-right">
                    <a href="{{ route('terimaUang',  0) }}" class="dropdown-item" title="Terima Uang" > + Terima Uang</a>
                    <a href='#' value="{{ route('transferKas', 0) }}" class="dropdown-item modalMd" title="Transfer Uang (Transfer antar Kas / Bank" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> + Transfer Dana</a>
                    <a href="{{ route('kirimUang', 0) }}" class="dropdown-item" title="Kirim Uang" > + Kirim Uang</a>
				
                </div>
			</div>
		</div>
    </div>

    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>AKUN</th>
                    <th class="text-center">SALDO (Rp)</th>
                    <th width="30px" class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data))
                @php $total=0 @endphp
                @foreach($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td><a href="{{ route('detailAkun', $row->id)}}" class="text-teal-800">{{ $row->kode .' - '. $row->nama ?? '' }}</a></td>
                    <td class="text-right">{{ Rupiah($row->saldo,2 ) }}</td>
                    <td>
                        <div class="btn-group">
                            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"><i class="icon-menu7"></i></button>
                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="{{ route('terimaUang', $row->id ?? 0) }}" class="dropdown-item" title="Terima Uang" > + Terima Uang</a>
                                <a href='#' value="{{ route('transferKas', $row->id ?? 0) }}" class="dropdown-item modalMd" title="Transfer Uang (Transfer antar Kas / Bank" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> + Transfer Dana</a>
                                <a href="{{ route('kirimUang', $row->id ?? 0) }}" class="dropdown-item" title="Kirim Uang" > + Kirim Uang</a>
                            </div>
                        </div>
                    </td>
                </tr>
                @php $total += $row->saldo @endphp
                
                @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="2" class="text-center">Jumlah</td>
                    <td class="text-right">{{ Rupiah($total,2 ) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection