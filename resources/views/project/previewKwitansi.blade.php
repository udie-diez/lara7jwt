<html>

<head>
	<title>Kwitansi</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->

	<style>
	@page {
			margin: 0px;
		}
		.spesial {
			border: 2px double black;
			padding-right: 5px;
			background-color: gainsboro;

		}

		.border {
			border: 1px solid black;

		}

		.main {
			border: 1px solid black;
			padding-left: 10px;
			padding-bottom: 10px;
		}
		body {
			margin-top: 50px;
			margin-left: 100px;
			margin-right: 100px;
		}
		textarea { height: auto; }
	</style>
</head>

<body>
	@include('layouts.terbilang')
	@include('layouts.mylib')

	<table style="margin-bottom: 50px">
		<tbody>
			<tr>
				<td width="100px">
					<img src="{{ url('/') }}/global_assets/images/trendy.png" alt="" width="90px">
				</td>
				<td style="text-align: center;">
					<span style="font-size: 14;"><b> {{$koperasi->nama}}</b></span><br>
					<span style="font-size: 10;"><b>{{$koperasi->alias}}</b></span><br>
					<span style="font-size: 10;">{{$koperasi->alamat .' '. $koperasi->kota}}</span><br>
					<span style="font-size: 10;">{{$koperasi->telepon}}</span><br>
				</td>
			</tr>
		</tbody>
	</table>
	<div class="main">

		<p style="margin-bottom: 30px;">
			<span style="margin-left: 40px; font-size:x-large; font-weight:bold">K W I T A N S I</span>
			<span style="margin-left: 40px;"> No :</span>
			<span style="margin-left: 10px; padding:10px" class="border"> {{ $data->nomor }}</span>
		</p>

		<table>
			<tbody>
				<tr>
					<td width="200px">Sudah terima dari</td>
					<td width="30px">: </td>
					<td style="font-weight: bold;">{{ $data->perusahaan }}</td>
				</tr>
				<tr>
					<td width="200px" style="padding-top: 20px;">Terbilang</td>
					<td width="30px">: </td>
					<td style="padding-top: 20px;"><textarea class="spesial" style="font-family:Georgia, 'Times New Roman', Times, serif" rows="4"> {{ terbilang($data->total) }}</textarea></td>
				</tr>
				<tr>
					<td width="200px" style="padding-top: 20px;">Uang Pembayaran</td>
					<td width="30px">: </td>
					<td style="padding-top: 20px;"><textarea class="border" style="font-family:Georgia, 'Times New Roman', Times, serif" rows=5 > {{ $data->project }}</textarea></td>
				</tr>
			</tbody>
		</table>


		<table style="margin-left: 20px;margin-top:50px;">
			<tbody>
				<tr>
					<td width="70px" style="font-size: x-large;  font-weight:bold">Rp.</td>
					<td width="150px" align="right" class="spesial" style="font-size: 16px;">{{ Rupiah($data->total)}}</td>
					<td width="280px" align="right">Jakarta, {{ IndoTglx($data->tanggal) }}</td>
				</tr>
			</tbody>
		</table>
		<br>
		<br>
		<br>
		<br>
		<br>
		<span style="margin-left: 390px; font-size:small; font-weight:bold; text-decoration:underline;">{{ $data->pengurus }}</span><br>
		<span style="margin-left: 430px; font-size:small ">{{ $data->jabatan }}</span>

		<div style="font: x-smaller;">
			<p style="margin-bottom:0;">Catatan :</p>
			- No.Rekening {{ $koperasi->rekening }} <br>
			- a.n {{ $koperasi->atasnama }}
		</div>
	</div>

</body>

</html>