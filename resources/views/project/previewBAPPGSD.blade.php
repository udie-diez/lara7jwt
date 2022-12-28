<html>

<head>
	<title>BASTPP + BAUT (GSD)</title>
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

	<div style="text-align: center;">
		<h4 class="card-title">BERITA ACARA SERAH TERIMA PENYELESAIAN PEKERJAAN <br>(BASTPP)</h4>
		<h4 class="card-title">{{ $data->nama }}</h4>
		<hr>
		NOMOR : <span>{{ $invoice->nomor }}</span>
	</div>

	<p style="text-align:justify">Pada hari ini, <b>{{ namahari($invoice->tanggalba) }}</b> Tanggal <b>{{ str_replace('Rupiah','',terbilang(date('d', strtotime($invoice->tanggalba)))) }}</b> Bulan <b>{{ bulan(date('m', strtotime($invoice->tanggalba))) }}</b> Tahun <b>{{ str_replace('Rupiah','',terbilang(date('Y', strtotime($invoice->tanggalba)))) }}</b>, kami yang bertanda tangan dibawah ini : <br></p>

	<table width="100%">
		<tbody>
			<tr>
				<td style="width: 20px;">1. </td>
				<td style="width: 100px;">Nama</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ strtoupper($data->pemesan)}}</td>

			</tr>
			<tr>
				<td style="width: 20px;"></td>
				<td style="width: 100px;">Perusahaan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ strtoupper($data->perusahaan)}}</td>
			</tr>
			<tr>
				<td style="width: 20px;"></td>
				<td style="width: 100px;">Jabatan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ strtoupper($data->jabatan) }}</td>

			</tr>
			<tr>
				<td colspan="4"><br>Dalam hal ini bertindak untuk dan atas nama yang kemudian disebut <b>PIHAK PERTAMA</b></td>
			</tr>
		</tbody>
	</table>


	<br><br>
	<table>
		<tbody>
			<tr>
				<td style="width: 20px;">2. </td>
				<td style="width: 100px;">Nama</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $data->pengurus}}</td>

			</tr>
			<tr>
				<td></td>
				<td style="width: 100px;">Perusahaan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>KOPKAR TRENDY</td>

			</tr>
			<tr>
				<td></td>
				<td style="width: 100px;">Jabatan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $data->ketua}}</td>

			</tr>
			<tr>
				<td colspan="4"><br>Dalam hal ini bertindak untuk dan atas nama KOPKAR TRENDY, yang kemudian disebut <b>PIHAK KEDUA</b></td>
			</tr>
		</tbody>
	</table>

	<br>

	<p style="text-align:justify">Berdasarkan Nota Pesanan Nomor : {{$data->no_spk}} perihal {{$data->nama}}, Pihak Kedua telah menyerahkan 100% hasil pekerjaan kepada Pihak Pertama, sehingga dinyatakan telah selesai. Terhadap hal ini, Pihak Pertama telah menerima hasil pekerjaan tersebut dengan baik.</p>


	<p style="text-align:justify">Demikian BERITA ACARA SERAH TERIMA PENYELESAIAN PEKERJAAN (BASTPP) ini dibuat dalam rangkap 2 (dua) sesuai dengan keadaan sebenarnya dan untuk dipergunakan sebagaimana mestinya. </p>

	<style>
		.page_break {
			page-break-before: always;
		}
	</style>

	<table style="font-size:smaller; margin-top:20px">
		<tbody>
			<tr>
				<td width="280px" style="padding-left: 20px;">KOPKAR TRENDY</td>
				<td align="center">{{$data->perusahaan}}</td>
			</tr>
			<tr>
				<td><br><br><br><br><br><br></td>
			</tr>
			<tr>
				<td style="font-weight: bold; padding-left:20px">
					<u>{{ strtoupper($manager->nama) }}</u>
				</td>
				<td style="font-weight: bold;" align="center"><u>{{ strtoupper($data->pemesan) }}</u></td>
			</tr>
			<tr>
				<td style="padding-left:40px">
					{{ strtoupper($manager->jabatan) }}
				</td>
				<td align="center">{{ strtoupper($data->jabatan) }}</td>
			</tr>
		</tbody>
	</table>

	<div class="page_break">
		<div style="text-align: center;">
			<h4 class="card-title">BERITA ACARA UJI TERIMA (BAUT)</h4>
			<hr>
		</div>

		<table width="100%">
			<tbody>
				<tr>
					<td style="width: 120px; vertical-align:top">PROYEK </td>
					<td style="width: 20; vertical-align:top">: </td>
					<td style="text-align:justify">{{$data->nama}}<br>{{$data->perusahaan}}</td>
				</tr>
				<tr>
					<td style="width: 120px;">LOKASI </td>
					<td>: </td>
					<td>{{$data->kota}}</td>
				</tr>
				<tr>
					<td style="width: 120px;">SPK </td>
					<td>: </td>
					<td>{{$data->no_spk}}</td>
				</tr>
				<tr>
					<td style="width: 120px;">PELAKSANA </td>
					<td>: </td>
					<td>Kopkar Trendy PT. Telkom</td>
				</tr>
			</tbody>
		</table>
		<p style="text-align:justify">Pada hari ini, <b>{{ namahari($invoice->tanggalba) }}</b> Tanggal <b>{{ str_replace('Rupiah','',terbilang(date('d', strtotime($invoice->tanggalba)))) }}</b> Bulan <b>{{ bulan(date('m', strtotime($invoice->tanggalba))) }}</b> Tahun <b>{{ str_replace('Rupiah','',terbilang(date('Y', strtotime($invoice->tanggalba)))) }}</b>, kami yang bertanda tangan dibawah ini : <br></p>

		<table>
			<tbody style="vertical-align:top">
				<tr>
					<td style="width: 20px;">1. </td>
					<td style="width: 100px;">Nama</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ $data->pengurus}}</td>

				</tr>
				<tr>
					<td></td>
					<td style="width: 100px;">Perusahaan</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>KOPKAR TRENDY</td>

				</tr>
				<tr>
					<td></td>
					<td style="width: 100px;">Alamat</td>
					<td style="width: 20px;" align="center"> : </td>
					<td style="text-align:justify">{{ $koperasi->alamat.' '.$koperasi->kota}}</td>
				</tr>
				<tr>
					<td></td>
					<td style="width: 100px;">Jabatan</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ $data->ketua}}</td>
				</tr>
				<tr>
					<td colspan="4" style="text-align:justify">Dalam hal ini bertindak untuk dan atas nama KOPKAR TRENDY PT. Telkom, yang selanjutnya disebut <b>PIHAK PERTAMA.</b></td>
				</tr>
			</tbody>
		</table>

		<br>
		<table width="100%">
			<tbody style="vertical-align:top">
				<tr>
					<td style="width: 20px;">2. </td>
					<td style="width: 100px;">Nama</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ strtoupper($data->pemesan)}}</td>

				</tr>
				<tr>
					<td style="width: 20px;"></td>
					<td style="width: 100px;">Perusahaan</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ strtoupper($data->perusahaan)}}</td>
				</tr>
				<tr>
					<td style="width: 20px;"></td>
					<td style="width: 100px;">Jabatan</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ strtoupper($data->jabatan) }}</td>

				</tr>
				<tr>
					<td colspan="4" style="text-align:justify">Dalam hal ini bertindak untuk dan atas nama {{$data->perusahaan }} yang selanjutnya disebut <b>PIHAK KEDUA.</b></td>
				</tr>
			</tbody>
		</table>

<p><b>PIHAK PERTAMA</b> dan <b> PIHAK KEDUA</b> menyatakan sebagai berikut:</p>
<table>
	<tbody style="vertical-align: top;">
		<tr>
			<td>1. </td>
			<td style="text-align:justify">Pekerjaan telah selesai dilaksanakan 100% (seratus persen)<br>Telah dilakukan Pemeriksaan Pekerjaan {{$data->nama}} {{$data->perusahaan}}.</td>
		</tr>
		<tr>
			<td>2. </td>
			<td style="text-align:justify">{{$data->perusahaan}}, pada tanggal {{IndoTglx($data->tanggal)}} yang dilaksanakan di {{$data->kota}}.</td>
		</tr>
		<tr>
			<td>3. </td>
			<td style="text-align:justify"><b>PIHAK PERTAMA </b> memeriksa report hasil pekerjaan tersebut yang telah dilakukan oleh <b>PIHAK KEDUA</b> dengan hasil BAIK.</td>
		</tr>
	</tbody>
</table>
<p style="text-align:justify">Demikian <b>Berita Acara Uji Terima (BAUT)</b> ini dibuat dengan sebenar benarnya dan penuh rasa tanggung  jawab untuk dapat digunakan sebagaimana mestinya.</p>
		<table style="font-size:smaller; margin-top:20px">
			<tbody>
				<tr>
					<td width="280px" style="padding-left: 20px;">Pemeriksa, <br>KOPKAR TRENDY</td>
					<td>{{ $data->perusahaan}}</td>
				</tr>
				<tr>
					<td><br><br><br><br><br><br></td>
				</tr>
				<tr>
					<td style="font-weight: bold; padding-left:20px">
						<u>{{ strtoupper($manager->nama) }}</u>
					</td>
					<td style="font-weight: bold;" align="center"><u>{{ strtoupper($data->pemesan) }}</u></td>
				</tr>
				<tr>
					<td style="padding-left:40px">
						{{ strtoupper($manager->jabatan) }}
					</td>
					<td align="center">{{ strtoupper($data->jabatan) }}</td>
				</tr>
			</tbody>
		</table>

	</div>

</body>

</html>