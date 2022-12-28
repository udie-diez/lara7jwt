<html>

<head>
	<title>Invoice</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
</head>

<body>
	@include('layouts.terbilang')
	@include('layouts.mylib')
	<?php
	$alamat = explode('Jl.', $data->alamat ?? '');
	?>
	<table style="margin-left: 25px;margin-bottom: 12px">
		<tbody>
			<tr>
				<td>
					<img src="{{ url('/') }}/global_assets/images/trendy.png" alt="" width="90px">
				</td>
				<td style="text-align: center; padding-left:20px">
					<span style="font-size: 14;"><b> {{$koperasi->nama}}</b></span><br>
					<span style="font-size: 10;"><b>{{$koperasi->alias}}</b></span><br>
					<span style="font-size: 10;">{{$koperasi->alamat .' '. $koperasi->kota}}</span><br>
					<span style="font-size: 10;">{{$koperasi->telepon}}</span><br>
				</td>
			</tr>
		</tbody>
	</table>
	<hr>


	<div style="text-align: center;">
		<h3 class="card-title">INVOICE / FAKTUR</h3>
	</div>

	<div style="font-size: small;">
		<table cellpadding="5" cellspacing="0" border="1">
			<tbody>
				<tr>
					<td width="400px"><b>Kepada Yth,</b><br>
						{{ $data->perusahaan ?? '' }}<br>
						{{ $alamat[0] ?? '' }}<br>
						{{ 'Jl. '.$alamat[1] ?? '' }}<br>
						{{ $data->kota ?? '' }}<br>
					</td>
				</tr>
			</tbody>
		</table>

		<br>

		<table>
			<tbody>
				<tr>
					<td width="100px">Nomor Invoice/Faktur</td>
					<td>: </td>
					<td>{{ $invoice->nomor ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">Nota Pesanan</td>
					<td>: </td>
					<td>{{ $data->nama ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">Nomor</td>
					<td>: </td>
					<td>{{ $data->no_po ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">Tanggal</td>
					<td>: </td>
					<td>{{ namahari($data->tgl_po ?? '').', '. IndoTglx($data->tgl_po ?? '') }}</td>
				</tr>
			</tbody>
		</table>

	</div>
	<br>
	@if($invoice->jenis==1)
	<table style="font-size: small;" cellpadding="5" cellspacing="0" width="100%" border="1">
		<thead>
			<tr>
				<th style="width: 30px">No.</th>
				<th style="width: 250px">Nama Barang/Jasa</th>
				<th style="width: 50px">Vol</th>
				<th style="width: 100px">Satuan</th>
				<th style="width: 100px">Harga Satuan</th>
				<th style="width: 100px">Jml Harga</th>
			</tr>
		</thead>
		<tbody>
			@php $subtotal = $ppn = $total = 0 @endphp

			@if(isset($item)) @foreach($item as $row)
			@php
			$subtotal += $row->total;
			$ppn += $row->ppn;
			$total = $subtotal + $ppn;
			@endphp
			<tr>
				<td align="center">{{ $loop->iteration }}.</td>
				<td> {{ $row->nama}}</td>
				<td align="center">{{ number_format($row->jumlah,0,',','.')}}</td>
				<td align="center">{{ $row->satuan}}</td>
				<td align="right">{{ number_format($row->harga,0,',','.')}}</td>
				<td align="right">{{ number_format($row->total,0,',','.')}}</td>

			</tr>
			@endforeach
			@endif
		</tbody>
		<tfoot>
			<tr>
				<td colspan="5" align="right">Subtotal</td>
				<td align="right"><b>{{ Rupiah($subtotal) }}</b></td>
			</tr>
			<tr>
				<td colspan="5" align="right">PPN (10%)</td>
				<td align="right"><b>{{ Rupiah($ppn) }}</b></td>
			</tr>
			<tr>
				<td colspan="5" align="right">Total</td>
				<td align="right"><b>{{ Rupiah($total) }}</b></td>
			</tr>
			@if($bayarinvoice!='null' && $bayarinvoice>0)
			<tr>
				<td colspan="5" align="right">Potongan</td>
				<td align="right"><b>{{ Rupiah($bayarinvoice) }}</b></td>
			</tr>
			<tr>
				<td colspan="5" align="right">Total Tagihan</td>
				<td align="right"><b>{{ Rupiah($total - $bayarinvoice) }}</b></td>
			</tr>
			 
			@endif

			<tr>
				<td colspan="6">Terbilang : <b><i>{{terbilang($total - $bayarinvoice)}}</i></b></td>
			</tr>
		</tfoot>
	</table>
	@endif

	@if($invoice->jenis==2)
	<table style="font-size: small;" cellpadding="5" cellspacing="0" width="100%" border="1">
		<thead>
			<tr>
				<th style="width: 30px">No.</th>
				<th style="width: 350px">U r a i a n</th>
				<th style="width: 100px">Jumlah (Rp)</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center">1.</td>
				<td> Uang Muka : {{ $data->nama}}</td>
				<td align="right"> {{ number_format($invoice->total,0,',','.') }}</td>
			</tr>
			<tr>
				<td colspan="3">Terbilang : <b><i>{{terbilang($invoice->total)}}</i></b></td>
			</tr>
		</tbody>
	</table>
	@endif


	<br>
	<div style="margin-left: 450px; font-size:small ">
		Hormat Kami,<br>
		Jakarta, {{ IndoTglx($invoice->tanggal) }}
		<br>
		<br>
		<br>
		<br>
		<br>
		<b><u>{{ $data->pengurus }}</u></b><br>
		<span style="padding-left: 40px;">{{ $data->jabatan }}</span>

	</div>
</body>

</html>