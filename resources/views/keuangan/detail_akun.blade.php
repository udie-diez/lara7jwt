@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h2 class="card-title">{{$tag['judul']}}</h2>
        <div class="header-elements">
            <div class="list-icons">
            <button type="button" class="btn btn-outline-info btn-sm" onclick="window.history.go(-1); return false;"><< Kembali</button>

            </div>
        </div>
    </div>

    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>NO.</th>
                    <th>TANGGAL</th>
                    <th>TRANSAKSI</th>
                    <th class="text-center">DEBIT (Rp.)</th>
                    <th class="text-center">KREDIT (Rp.)</th>
                    <th class="text-center">SALDO (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @php $saldo=0 @endphp
                @if(isset($data))
                @foreach($data as $row)

                <?php
                // if ($row->nobiaya) {
                //     $keterangan = "Biaya #" . $row->nobiaya . "</a><br><span class='text-muted font-size-sm font-italic '>" . $row->namabiaya . "</span>";
                // } else if ($row->notransfer) {
                //     $keterangan = 'Transfer #' . $row->notransfer . "<br><span class='text-muted font-size-sm font-italic '>" . $row->namatransfer . "</span>";
                // } else {
                //     $keterangan = '';
                // }

                $saldo += $row->debit ? $row->debit : 0;
                $saldo -= $row->kredit ? $row->kredit : 0;

                ?>
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ IndoTgl($row->tanggal) }}</td>
                    
                    @if($row->nobiaya)
                    
                    <td><a href="{{ route('showBiaya',$row->itemid) }}" class='text-teal-800' title='Lihat Transaksi'>Biaya #{{$row->nobiaya }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namabiaya}}</span></td>
                    
                    @elseif($row->nojurnalumum)
                    
                    <td><a href="{{ route('showJurnalumum',$row->itemid) }}" class='text-teal-800' title='Jurnal Umum'>Jurnal #{{$row->nojurnalumum }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namajurnalumum}}</span></td>
                    
                    @elseif($row->nosaldoawal)
                    
                    <td><a href="{{ route('saldoawal') }}" class="text-teal-800 modalMd" title="Saldo Awal">Saldo Awal</a> 
                    </td>

                    @elseif($row->notransfer)
                    
                    <td><a href='#' value="{{ route('showtransferKas', $row->itemid ?? 0) }}" class="text-teal-800 modalMd" title="Transfer" data-toggle="modal" data-target="#modalMd">Transfer #{{$row->notransfer }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namatransfer}}</span>
                    </td>

                    @elseif($row->noterimauang)
                    
                    <td><a   href="{{ route('showTerimaUang', $row->itemid ?? 0) }}" class="text-teal-800" title="Terima Uang"    >Terima Uang #{{$row->noterimauang }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namaterimauang}}</span>
                    </td>

                    @elseif($row->nosetoranwajib)
                    
                    <td>Setoran Wajib Bulan {{ date('m/Y', strtotime($row->tanggal)) }}<br><span class='text-muted font-size-sm font-italic '>{{$row->namasetoranwajib}}</span>
                    </td>

                    @elseif($row->nosetoranpokok)
                    
                    <td>Setoran Pokok Bulan {{ date('m/Y', strtotime($row->tanggal)) }}<br><span class='text-muted font-size-sm font-italic '>{{$row->namasetoranpokok}}</span>
                    </td>

                    @elseif($row->noangsuranpinjaman)
                    
                    <td><a href="{{ route('showAngsuran', $row->itemid ?? 0) }}" class="text-teal-800" title="Setoran Wajib" >Angsuran Pinjaman #{{$row->noangsuranpinjaman }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namaangsuranpinjaman}}</span>
                    </td>

                    @elseif($row->nopelunasan)
                    
                    <td><a href="{{ route('showPelunasan', $row->itemid ?? 0) }}" class="text-teal-800" title="Setoran Wajib" >Pelunasan Pinjaman #{{$row->nopelunasan }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namapelunasan}}</span>
                    </td>

                    @elseif($row->nopenjualan)
                    <td><a href="{{ route('showInvoice', $row->itemid ?? 0) }}" class="text-teal-800" title="Invoice / Penjualan" >Invoice #{{$row->nopenjualan }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namapenjualan}}</span>
                    </td>

                    @elseif($row->nopembayaran)
                    <td><a href="{{ route('showPembayaran', $row->itemid ?? 0) }}" class="text-teal-800" title="Pembayaran" >Pembayaran #{{$row->nopembayaran }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namapembayaran}}</span>
                    </td>

                    @elseif($row->nokirimuang)
                    
                    <td><a   href="{{ route('showKirimUang', $row->itemid ?? 0) }}" class="text-teal-800" title="Kirim Uang"    >Kirim Uang #{{$row->nokirimuang }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namakirimuang}}</span>
                    </td>

                    @else
                    <td></td>
                    @endif
                    <td class="text-right">{{ Rupiah($row->debit,2 ) ?? "0,00" }}</td>
                    <td class="text-right">{{ Rupiah($row->kredit,2 ) ?? "0,00" }}</td>
                    <td class="text-right">{{ Rupiah($saldo,2) }}</td>
                    <td>

                    </td>
                </tr>
                @endforeach
                @endif
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td colspan="3" class="text-center">Saldo Akhir</td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ Rupiah($saldo,2) }}</td>

                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection