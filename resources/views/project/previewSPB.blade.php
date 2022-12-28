<html>
<head>
	<title>SPB</title>
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
	$pos = strpos($data->alamat ?? '', 'Jl.');
	if ($pos) {
		$alamat[0] = substr($data->alamat, 0, $pos - 1);
		$alamat[1] = substr($data->alamat, $pos);
	} else {
		$pos = strpos($data->alamat ?? '', 'Jalan');

		if ($pos && ($pos > 0)) {

			$alamat[0] = substr($data->alamat, 0, $pos - 1);
			$alamat[1] = substr($data->alamat, $pos);
		} else {
			$alamat[0] = $data->alamat ?? '';
			$alamat[1] = '';
		}
	}
	?>
	<table style="margin-left: 25px;">
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
	<div style="font-size: small; margin-top:20px">
		<table>
			<tbody>
				<tr>
					<td style="padding-bottom: 20px;">Nomor : {{ $invoice->nomor }}</td>
				</tr>
				<tr>
					<td style="padding-bottom: 20px;">Jakarta, {{ IndoTglx($invoice->tanggal) }}</td>
				</tr>
				<tr>
					<td width="400px"><b>Kepada Yth,</b><br>
						{{ $data->perusahaan ?? '' }}<br>
						{{ $alamat[0] ?? '' }}<br>
						{{ 'Jl. '.$alamat[1] ?? '' }}<br>
						{{ $data->kota ?? '' }}<br><br>
					</td>
				</tr>
				<tr>
					<td>Perihal : {{ $data->nama ?? '' }}</td>
				</tr>
			</tbody>
		</table>

		<p style="text-align: justify;">
			Berkenaan dengan Surat Pesanan Nomor : {{$data->no_spk ?? ''}} Perihal Nota Pesanan {{ $data->nama ?? '' }} dengan ini disampaikan bahwa Kami telah dapat memenuhi pesanan sesuai yang dipersyaratkan.

		</p>
		<p style="text-align: justify;">
			Selanjutnya Kami mohon agar dapat dilakukan pembayaran atas pekerjaan tersebut, dengan total tagihan sebesar Rp.{{ Rupiah($invoice->total,0) }},- ({{ terbilang($invoice->total) }}), sudah termasuk PPN {{$data->ppnpersen ?? 11}}% dan pajak lainnya yang ditetapkan Pemerintah.
		</p>
		<p style="text-align: justify;">
			Pembayaran dapat dilakukan secara Giral ke rekening {{$koperasi->rekening}}, atas nama {{$koperasi->atasnama}}.
		</p>
		<p>
			Demikian disampaikan, atas perhatian dan kerjasamanya Kami ucapkan terima kasih.
		</p>
	</div>
	<div style="font: small;">
		<br>
		<br>
		Hormat Kami,
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<b><u>{{ $data->pengurus }}</u></b><br>
		<span>{{ $data->jabatan }}</span>
	</div>
</body>

</html>