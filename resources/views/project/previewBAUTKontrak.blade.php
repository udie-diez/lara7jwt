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
			margin-bottom: 50px;
			margin-left: 100px;
			margin-right: 100px;
		}
	</style>
</head>

<body>
	@include('layouts.terbilang')
	@include('layouts.mylib')

	<div class="page_break">
		<div style="text-align: center;">
			<h4 class="card-title">BERITA ACARA UJI TERIMA (BAUT)<br>{{$data->nama}}</h4>
			<hr>
			<p>Nomor : {{ $invoice->nomor}}</p>
		</div>
		<br>
		<p style="text-align:justify">Pada hari ini, <b>{{ namahari($invoice->tanggalba) }}</b> Tanggal <b>{{ str_replace('Rupiah','',terbilang(date('d', strtotime($invoice->tanggalba)))) }}</b> Bulan <b>{{ bulan(date('m', strtotime($invoice->tanggalba))) }}</b> Tahun <b>{{ str_replace(' Rupiah','',terbilang(date('Y', strtotime($invoice->tanggalba)))) }} ({{ IndoTgl($invoice->tanggalba)}})</b> bertempat di Kantor {{$data->perusahaan . ' '. $data->alamat }} dengan berdasarkan : <br></p>

		<p style="text-align:justify">Surat Pesanan No : {{$data->no_spk.' tanggal '. $data->tgl_spk}}, Perihal Perjanjian {{$data->nama}}</p>

		<p style="text-align:justify">Sehubungan dengan hal tersebut telah dilaksanakan Uji Terima Pekerjaan {{$data->nama}} dari KOPKAR TRENDY, sebagai berikut :</p>
		@if (isset($item) && count($item) > 0)
		<table style="margin-left: 20px;" cellpadding="5" cellspacing="0" width="100%" border="1">
			<thead>
				<tr>
					<th style="width: 30px">No.</th>

					<?php
					foreach ($item as $row) {
						$namakol = 'No';
						$kolomjumlah = $row->kolomjumlah;

						for ($x = 1; $x < 4; $x++) {
							$namakolom = 'kolom' . $x . '_nama';
							$align = ($x == ($kolomjumlah - 1)) ? 'align="right"' : 'align="center"';

							if ($row->$namakolom != '') {
								echo '<th ' . $align . '><div>' . $row->$namakolom . '</div></th>';
								$namakol .= ',' . $row->$namakolom;
							} else {
								break;
							}
						}
						break;
					}
					?>
					<th>KETERANGAN <br> CHECKLIST</th>
				</tr>
			</thead>
			<tbody id="bbody">
				<?php
				$no = 1;
				$iid = '';
				$subtotal = 0;

				foreach ($item as $row) {
					$pid = $row->projectid;
					$kolomjumlah = $row->kolomjumlah;
					$iid .= $row->id . ',';
					echo '<tr><td align="center" width="30px">' . $no++ . '</td>';

					for ($i = 1; $i < $x; $i++) {
						$isikolom = 'kolom' . $i . '_isi';
						$align = ($i == 1) ? 'align="left"' :  'align="center"';
						echo '<td ' . $align . '><div>' . Rupiah($row->$isikolom, ($i == ($kolomjumlah - 1)) ? 2 : 0) . '</div></td>';

						if ($i == ($kolomjumlah - 1)) {
							$subtotal += $row->$isikolom;
						}
					}

				?>
					<td align="center">. . . . . . . . . . . .</td>
					</tr>
				<?php
				}
				$no--;
				?>

			</tbody>

		</table>
		@endif

		<p style="text-align: justify;">Dari semua hasil {{$data->nama}} tersebut, dapat dinyatakan bahwa Pekerjaan tersebut telah dilaksanakan dengan hasil :</p>
		<br>
		<center><b>" Baik, dan Dapat Diterima "</b></center><br>

		<p>Demikian Berita Acara Uji Terima ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
		<table style="margin-top:20px">
			<tbody>
				<tr>
					<td></td>
					<td class="text-right"><br>Jakarta, {{ IndoTglx($invoice->tanggalba) }}<br></td>
				</tr>
				<tr>
					<td  width="300px" style="padding-left: 40px;">{{'PT. TELKOM' }}</td>
					<td>KOPKAR TRENDY</td>
				</tr>
				<tr>
					<td><br><br><br><br><br></td>
				</tr>
				<tr>
					<td style="font-weight: bold; padding-left:20px"><u>{{ strtoupper($data->pemesan) }}</u></td>
					<td style="font-weight: bold;" align="center">
						<u>{{ strtoupper($manager->nama) }}</u>
					</td>
				</tr>
				<tr>
					<td style="padding-left:40px">{{ strtoupper($data->jabatan) }}</td>
					<td  align="center">
						{{ strtoupper($manager->jabatan) }}
					</td>
				</tr>
			</tbody>
		</table>

	</div>

</body>

</html>