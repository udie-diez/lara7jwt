<!DOCTYPE html>
<html>

<head>
	<title>Panjar</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<!-- <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> -->
	<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
	<style>
		@page {
			margin: 0px;
		}
		body {
			font-family: Verdana, Tahoma, "DejaVu Sans", sans-serif;
			font-size: 12px;
			color: black;
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
	<table style="margin-left: 25px;margin-bottom: 12px">
		<tbody>
			<tr>
				<td>
					<!-- <img src="{{ url('/') }}/assets/images/telkom.png" alt="Trendy" width="90px"> -->
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

	@php $subtotal = $ppn = $total = 0 @endphp

	@if(isset($item)) @foreach($item as $row)
	@php
	$subtotal += $row->total;
	$ppn += $row->ppn;
	$total = $subtotal + $ppn;
	@endphp
	@endforeach
	@endif

	<div style="text-align: center;">
		<h3 class="card-title">FORM PANJAR SPK / KONTRAK</h3>
	</div>

	<div>
		<table>
			<tbody>
				<tr>
					<td width="100px">Judul Panjar Kerja</td>
					<td>: </td>
					<td>{{ $data->nama ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">No. PO/SPK/Kontrak</td>
					<td>: </td>
					<td>{{ $data->no_po ?? $data->no_spk }}</td>
				</tr>
				<tr>
					<td width="100px">Nilai PO/SPK/Kontrak</td>
					<td>: </td>
					<td>Rp. {{ Rupiah($data->nilai ?? '') }},-</td>
				</tr>
				<tr>
					<td width="100px">NIlai Pencairan Panjar</td>
					<td>: </td>
					<td>Rp. {{ Rupiah($data->nilaipanjar)}},-</td>
				</tr>
			</tbody>
		</table>
		<p style="font-weight: bold;">Pemberi Pekerjaan / Peminta Panjar :</p>
		<table>
			<tbody>
				<tr>
					<td width="100px">Nama / NIK</td>
					<td>: </td>
					<td>{{ $pemberi->nama ?? '' }} / {{ $pemberi->nik ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">Jabatan</td>
					<td>: </td>
					<td>{{ $pemberi->jabatan ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">Lokasi Kerja</td>
					<td>: </td>
					<td>{{ $pemberi->lokasikerja ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">No. Telp / HP</td>
					<td>: </td>
					<td>{{ $pemberi->telepon ?? '' }}</td>
				</tr>
				<tr>
					<td width="100px">Email</td>
					<td>: </td>
					<td>{{ $pemberi->email ?? ''}}</td>
				</tr>
				<tr>
					<td width="100px">Jangka Waktu <br>Penyelesaian Pekerjaan</td>
					<td>: </td>
					<td style="padding-left: 50px;">bulan* </td>
				</tr>
			</tbody>
		</table>

		<table>
			<tbody>
				<tr>
					<td width="200px">Jaminan Pembayaran <br> atas Penyelesaian Pekerjaan :</td>
					<td width="100px"><span style="font-size: 16px;"><?php echo $data->jaminan1 == 'on' ? "&#9745;" : "&#9744;" ?> </span> Potong Gaji </td>
					<td width="100px"><span style="font-size: 16px;"><?php echo $data->jaminan2 == 'on' ? "&#9745;" : "&#9744;" ?> </span> SPK / Kontrak </td>
					<td width="100px"><span style="font-size: 16px;"><?php echo $data->jaminan3 == 'on' ? "&#9745;" : "&#9744;" ?> </span> Auto Debet Rekening </td>
				</tr>
			</tbody>
		</table>
		<table>
			<tbody>
				<tr>
					<td width="200px">Lampiran Penyelesaian <br> Pekerjaan :</td>
					<td width="170px"><span style="font-size: 16px;"><?php echo $data->lampiran1 == 'on' ? "&#9745;" : "&#9744;" ?> </span> Kwitansi Pengeluaran </td>
					<td width="170px"><span style="font-size: 16px;"><?php echo $data->lampiran2 == 'on' ? "&#9745;" : "&#9744;" ?> </span> Copy KTP / Karpeg </td>

				</tr>
				<tr>
					<td></td>
					<td  ><span style="font-size: 16px;"><?php echo $data->lampiran3 == 'on' ? "&#9745;" : "&#9744;" ?> </span> Copy Justifikasi </td>
					<td  ><span style="font-size: 16px;"><?php echo $data->lampiran4 == 'on' ? "&#9745;" : "&#9744;" ?> </span> Form BOQ </td>
				 

				</tr>
			</tbody>
		</table>

	</div>

	<table style="margin-left:20px; margin-top:20px">
		<tbody>
			<tr>
				<td>Jakarta, {{ IndoTglx($data->tanggalpanjar )}}</td>
				<td></td>
			</tr>
			<tr>
				<td width="400px">Pemberi Pekerjaan / Peminta Panjar Kerja</td>
				<td align="center">Mengetahui,</td>
			</tr>
			<tr>
				<td></td>
				<td><br><br><br><br></td>
			</tr>
			<tr>
				<td style="padding-left: 20px;"><u>{{ $pemberi->nama ?? ''}}</u></td>
				<td align="center"><u>{{ $mengetahui->nama ?? ''}}</u></td>
			</tr>
			<tr>
				<td style="padding-left: 20px;">NIK. {{ $pemberi->nik ?? ''}}</td>
				<td align="center">NIK. {{ $mengetahui->nik ?? ''}} </td>
			</tr>
			<tr>
				<td></td>
				<td><br> </td>
			</tr>
			<tr>
				<td  >Penerima Pekerjaan</td>
				<td align="center">Mengetahui,</td>
			</tr>
			<tr>
				<td></td>
				<td><br><br><br><br></td>
			</tr>
			<tr>
				<td style="padding-left: 20px;"><u>{{ $penerima->nama ?? ''}}</u></td>
				<td align="center"><u>{{ $mengetahui2->nama ?? ''}}</u></td>
			</tr>
			<tr>
				<td style="padding-left: 20px;">{{ $penerima->jabatan ?? ''}}</td>
				<td align="center">{{ $mengetahui2->jabatan ?? ''}}</td>
			</tr>
		</tbody>
	</table>
	<div style="font-size:smaller">
	<br>
	<u>Catatan</u> :<br>
	
1.	Apabila penyelesaian pekerjaan telah dilakukan dan dalam waktu lebih dari jangka waktu 
penyelesaian pekerjaan belum dilakukan pembayaran, maka saya bersedia dipotong payroll.<br>
2.	Pelaksanaan pekerjaan diatas Rp.25.000.000,- mengetahui Senior Leader (SM/GM).<br>
3.	*) Proses kontrak dengan Telkom

	</p>
	</div>
</body>

</html>