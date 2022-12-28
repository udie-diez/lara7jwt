<html>

<head>
	<title>Penawaran (PQ)</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
	<style>
		@page {
			margin: 0px;
		}

		body {
			margin-top: 50px;
			margin-left: 100px;
			margin-right: 100px;
		}
	</style>
</head>

<body>
	@include('layouts.terbilang')
	@include('layouts.mylib')
	<?php
	$alamat = explode('Jl.', $data->alamat ?? '');
	?>
	<table width="100%">
		<tbody>
			<tr>
				<td width="150px">
					<img src="{{ url('/') }}/global_assets/images/trendy.png" alt="" width="80px">
				</td>
				<td style="text-align: right; padding-left:20px">
					<span style="font-size: 10;"><b> {{$koperasi->nama}}</b></span><br>
					<span style="font-size: 10;">{{$koperasi->alamat .' '. $koperasi->kota}}</span><br>
					<span style="font-size: 10;"><b> {{$koperasi->telepon}}</b></span><br>
				</td>
			</tr>
		</tbody>
	</table>
	<hr>
	<span style="float: right;">
		<h3><b> PURCHASE QUOTATION </b></h3>
	</span><br><br>

	<div style=" margin-top:20px">


		<table>
			<tbody>
				<tr>
					<td width="400px">Kepada</td>
					<td width="30px">Nomor :</td>
					<td># {{$data->kode}}</td>
				</tr>
				<tr>
					<td><b>{{$data->vendor}}</b></td>
					<td>Tanggal :</td>
					<td>{{ IndoTglx($data->tanggal)}}</td>
				</tr>
				<tr>
					<td style="font-size:small;">{{$data->alamat}}</td>
				</tr>
				<tr>
					<td>{{$data->kota}}</td>
					<td></td>
				</tr>
				<tr>
					<td>Email : {{$data->email}}</td>

				</tr>


			</tbody>
		</table>
		<br><br>
		<table  cellpadding="5" cellspacing="0" width="100%" border="1" style="font-size: small;">
			<thead  align="center" style="font-weight: bold;">
				<tr>
					<td>No.</td>
					<td>Nama Produk</td>
					<td>Quantity</td>
					<td>Satuan</td>
					<td>Harga</td>
					<td>Jumlah</td>
				</tr>
			</thead>
			<tbody>
				@php $no=1;$total=$pajak=0; @endphp
				@foreach($itemproduk as $row)
				@php $total += $row->jumlah; $pajak += $row->pajak; @endphp
				<tr>
					<td align="center">{{$no}}.</td>
					<td>{{$row->nama}}</td>
					<td align="center">{{$row->qty}}</td>
					<td align="center">{{$row->satuan}}</td>
					<td align="right">{{Rupiah($row->harga)}}</td>
					<td align="right">{{Rupiah($row->jumlah)}}</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot style="font-weight: bold;" align="right">
				<tr>
					<td colspan="5">Sub Total</td>
					<td>{{Rupiah($total)}}</td>
				</tr>
				<tr>
					<td colspan="5">Pajak</td>
					<td>{{Rupiah($pajak)}}</td>
				</tr>
				<tr>
					<td colspan="5">Total</td>
					<td>{{Rupiah($pajak + $total)}}</td>
				</tr>
			</tfoot>
		</table>

	</div>

</body>

</html>