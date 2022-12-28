<html>

<head>
	<title>Berita Acara</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> -->
	<style>
		@page {
			margin: 0px;
		}

		body {
			font-size: small;
			margin-top: 50px;
			margin-left: 100px;
			margin-right: 100px;
		}
		table>tbody>tr>td{
			vertical-align :top;
		}
	</style>
</head>

<body>
	@include('layouts.terbilang')
	@include('layouts.mylib')

	<?php

	$subtotal = $ppn = $total = 0;
	$no = stripos($data->no_spk, "/");
	$no = substr($data->no_spk, $no, strlen($data->no_spk) - $no);
	$ba = ['BERITA ACARA','BERITA ACARA PENYELESAIAN PEKERJAAN','BERITA ACARA PENERIMAAN PEKERJAAN','BERITA ACARA PENYERAHAN DAN PENERIMAAN','BERITA ACARA PEMERIKSAAN BARANG/ JASA (BAPP)','BERITA ACARA PEMERIKSAAN & PENYERAHAN','BERITA ACARA PEMERIKSAAN DAN PENERIMAAN BARANG','BERITA ACARA SERAH TERIMA BARANG','BERITA ACARA SERAH TERIMA PENYELESAIAN PEKERJAAN','BERITA ACARA SERAH TERIMA','BERITA ACARA PEMERIKSAAN DAN PENERIMAAN','BERITA ACARA PENERIMAAN PEKERJAAN', 'BERITA ACARA PENYELESAIAN PEKERJAAN', 'BERITA ACARA SERAH TERIMA', 'BERITA ACARA PEMERIKSAAN DAN PENERIMAAN', 'BERITA ACARA PENYELESAIAN PRESTASI PEKERJAAN', 'BERITA ACARA PEMERIKSAAN DAN PENERIMAAN PEKERJAAN', 'BERITA ACARA SERAH TERIMA BARANG', 'BERITA ACARA SERAH TERIMA PENYELESAIAN PEKERJAAN', 'BERITA ACARA PENYERAHAN DAN PENERIMAAN '];
	?>

	<div style="text-align: center;">
		@if($invoice->ba != 8 && $invoice->ba != 18 && $invoice->ba != 6 && $invoice->ba != 16)
		<h2 class="card-title">{{ $ba[$invoice->ba ?? 0] }}</h2>
		@else
		<h3 class="card-title">{{ $ba[$invoice->ba ?? 0] }}</h3>
		@endif
		Nomor : <span style="font-weight: bold;">{{ $invoice->nomor ?? '' }}</span><br><br>
		@if($invoice->ba < 11 || ($invoice->ba == 16) )
		Tel. <span style="padding-left: 30px;">{{ $no }}</span>
		@endif
	</div>

	<hr>
	<table>
		<tbody>
			<tr>
				<td width="170px" style="vertical-align: top;">PROYEK</td>
				<td width="10px" align="center" style="vertical-align: top;">:</td>
				<td>{{ $data->nama}}</td>
			</tr>
			<tr>
				<td width="170px">MITRA PELAKSANA</td>
				<td >:</td>
				<TD>KOPKAR TRENDY</TD>
			</tr>
			<tr>
				<td>WORK PACKAGE</td>
				<td>:</td>
				<td>{{ $data->paket}}</td>
			</tr>
			<!-- <tr>
				<td>NOMOR NOTA</td>
				<td>:</td>
				<td>{{ $data->no_po}}</td>
			</tr> -->
		</tbody>
	</table>
	<br>
	Kami yang bertanda tangan di bawah ini : <br><br>

	<table>
		<tbody>
			<tr>
				<td style="width: 10px;">1.</td>
				<td>{{ $data->perusahaan}} dalam pembuatan hukum ini diwakili sah oleh :</td>

			</tr>
		</tbody>
	</table>
	<table style="margin-left: 10px;">
		<tbody>
			<tr>
				<td style="width: 10px;"></td>
				<td style="width: 100px;">Nama</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $data->pemesan}}</td>
			</tr>
			<tr>
				<td style="width: 10px;"></td>
				<td style="width: 100px;">Jabatan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $data->jabatan}}</td>
			</tr>

		</tbody>
	</table>
	<table style="margin-left: 15px;">
		<tbody>
			<tr>
				<td>Selanjutnya dalam perjanjian ini disebut sebagai <b>PIHAK KESATU</b> atau <b>{{ $data->alias }}</b></td>
			</tr>
		</tbody>
	</table>
	<br>
	<table>
		<tbody>
			<tr>
				<td style="width: 10px;">2.</td>
				<td>KOPKAR TRENDY dalam pembuatan hukum ini diwakili sah oleh :</td>

			</tr>
		</tbody>
	</table>

	<table style="margin-left: 10px;">
		<tbody>
			<tr>
				<td style="width: 10px;"></td>
				<td style="width: 100px;">Nama</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $data->pengurus}}</td>
			</tr>
			<tr>
				<td style="width: 10px;"></td>
				<td>Jabatan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $data->ketua}}</td>
			</tr>
		</tbody>
	</table>
	<span style="padding-left: 15px;">Selanjutnya dalam perjanjian ini disebut sebagai <b>PIHAK KEDUA</b> atau <b>MITRA</b></span><br>

	<p style="text-align:justify">Pada hari ini {{ namahari($invoice->tanggalba) }} tanggal {{ str_replace('Rupiah','',terbilang(date('d', strtotime($invoice->tanggalba)))) }} bulan {{ bulan(date('m', strtotime($invoice->tanggalba))) }} tahun {{ str_replace('Rupiah','',terbilang(date('Y', strtotime($invoice->tanggalba))))}}, {{$data->alias}} telah : <br></p>

	<center><b>MENERIMA</b></center>

	<p style="text-align:justify">{{$data->nama}}, dengan nilai sebesar Rp.{{Rupiah($data->nilai,0)}},- ({{terbilang($data->nilai)}}), sudah termasuk PPN {{$data->ppnpersen ?? 10}}% dan pajak lainnya yang ditetapkan Pemerintah. Proyek tersebut diterima dalam keadaan BAIK dan DAPAT DIPERGUNAKAN sebagaimana dimaksud dalam ketentuan Surat Pesanan kepada MITRA dengan Nomor : {{$data->no_spk}} </p>
	
	<p style="text-align:justify">{{ $ba[$data->ba ?? 0] }} ini dibuat di atas kertas bermaterai cukup serta mempunyai kekuatan setelah ditandatangani kedua belah pihak.</p>

	<table style="font-size:smaller; margin-top:20px">
		<tbody>
			<tr>
				<td>&nbsp;</td>
				<td align="center">Yang Menerima,</td>
			</tr>
			<tr>
				<td width="370px" style="padding-left: 20px;">KOPKAR TRENDY</td>
				<td align="center">{{$data->alias}}</td>
			</tr>
			<tr>
				<td><br><br><br><br><br><br></td>
			</tr>
			<tr>
				<td style="font-weight: bold; padding-left:20px">
					<u>{{ strtoupper($data->pengurus) }}</u>
				</td>
				<td style="font-weight: bold;" align="center"><u>{{ strtoupper($data->pemesan) }}</u></td>
			</tr>
			<tr>
				<td style="padding-left:50px">
					{{ strtoupper($data->ketua) }}
				</td>
				<td align="center">{{ strtoupper($data->jabatan) }}</td>
			</tr>
		</tbody>
	</table>

</body>

</html>