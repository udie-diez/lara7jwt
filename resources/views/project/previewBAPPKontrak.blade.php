<html>

<head>
	<title>BAPP + BAP (Kontrak)</title>
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

	<?php

	$subtotal = $ppn = $total = 0;
	$no = stripos($data->no_po, "/");
	$no = substr($data->no_po, $no, strlen($data->no_po) - $no);
	?>

	<div style="text-align: center;">
		<h3 class="card-title">BERITA ACARA PENERIMAAN PEKERJAAN</h3>
		<h3 class="card-title">{{ strtoupper($data->nama) }}</h3>
		<hr>
		NOMOR : TEL. <span style="padding-left: 25px;">{{ $no }}</span>
	</div>

	<p style="text-align:justify">Pada hari ini, <b>{{ namahari($invoice->tanggalba) }}</b> Tanggal <b>{{ str_replace('Rupiah','',terbilang(date('d', strtotime($invoice->tanggalba)))) }}</b> Bulan <b>{{ bulan(date('m', strtotime($invoice->tanggalba))) }}</b> Tahun <b>{{ str_replace('Rupiah','',terbilang(date('Y', strtotime($invoice->tanggalba)))) }}</b>, bertempat di Kantor {{$data->perusahaan.' '.$data->unitkerja}}, kami yang bertanda tangan di bawah ini telah dilakukan serah terima penerimaan pekerjaan {{$data->nama }} dari : <br></p>

	<table style="margin-left: 20px;">
		<tbody>
			<tr>
				<td style="width: 20px;">I. </td>
				<td style="width: 100px;">Nama</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $manager->nama}}</td>
			</tr>
			<tr>
				<td style="width: 20px;"></td>
				<td>Jabatan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ $manager->jabatan}}</td>
			</tr>
			<tr>
				<td style="width: 20px;"></td>
				<td colspan="3" style="text-align: justify;">Dalam acara Serah Terima hasil pekerjaan ini mewakili KOPKAR TRENDY, selanjutnya disebut sebagai <b>PIHAK KE-I</b></td>
			</tr>
		</tbody>
	</table>
	<br>
	<table style="margin-left: 20px;">
		<tbody>
			<tr>
				<td style="width: 20px;">II. </td>
				<td style="width: 100px;">Nama</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ strtoupper($data->pemesan)}}</td>
			</tr>
			<tr>
				<td></td>
				<td style="width: 100px;">Jabatan</td>
				<td style="width: 20px;" align="center"> : </td>
				<td>{{ strtoupper($data->jabatan)}}</td>
			</tr>
			<tr>
				<td style="width: 20px;"></td>
				<td colspan="3" style="text-align: justify;">Dalam acara Serah Terima ini mewakili {{$data->perusahaan.' '.$data->unitkerja}}, selanjutnya disebut sebagai <b>PIHAK KE- II</b></td>
			</tr>
		</tbody>
	</table>

	<br>
	<b>Berdasarkan :</b> <br><br>
	<table style="margin-left: 20px;">
		<tbody style="vertical-align: top;">
			<tr>
				<td style="width: 20px;" align="top">1. </td>
				<td style="text-align: justify;">Perjanjian {{$data->nama}}</td>
			</tr>
			<tr>
				<td></td>
				<td style="text-align: justify;"> No.Kontrak : {{$data->no_spk.', Tanggal '. IndoTglx($data->tgl_spk)}}</td>
			</tr>
		</tbody>
	</table>
	<br>
	<p style="text-align:justify">Bahwa <b>PIHAK KE-I</b> telah menyelesaikan Pengadaan dan menyerahkan objek tersebut kepada <b>{{$data->alias}}</b> sebagaimana <b>{{$data->alias}}</b> telah menerima seluruh objek sebagaimana yang dimaksud, dan setelah melalui pemeriksaan pekerjaan secara Teknis maupun Komersial dinyatakan dalam keadaan : </p>

	<br>
	<center><b>"Baik dan Dapat diterima"</b></center><br>

	<p style="text-align:justify">Selanjutnya kepada <b>KOPKAR TRENDY</b> dapat dilakukan proses pembayaran biaya pengadaannya sesuai dengan nilai Kontrak No : {{$data->no_spk.', Tanggal '. IndoTglx($data->tgl_spk)}} sebesar <b>Rp. {{ rupiah($data->nilai)}},-</b> terbilang ({{terbilang($data->nilai)}}), sudah termasuk Ppn {{$data->ppnpersen ?? 10}}% dan pajak lainnya yang dipungut sesuai Peraturan Pemerintah dengan rincian sebagai berikut :</p>

	<style>
		.page_break {
			page-break-before: always;
		}
	</style>
	<div class="page_break">
		<br>
		<br>
		@if (isset($item) && count($item) > 0)
		<table cellpadding="5" cellspacing="0" width="100%" border="1">
		<thead align="center">

				<tr>
					<th style="width: 30px">No.</th>

					<?php
					foreach ($item as $row) {
						$namakol = 'No';
						$kolomjumlah = $row->kolomjumlah;

						for ($x = 1; $x < 9; $x++) {
							$namakolom = 'kolom' . $x . '_nama';
							 

							if ($row->$namakolom != '') {
								
								echo '<th><div>' . $row->$namakolom . '</div></th>';
								$namakol .= ',' . $row->$namakolom;
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'qty')) > -1 ? 'align="center"' : '';
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'satuan')) > -1 ? 'align="center"' : $alignisi[$x];
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'harga')) > -1 ? 'align="right"' : $alignisi[$x];
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'jumlah')) > -1 ? 'align="right"' : $alignisi[$x];

								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'volume')) > -1 ? 'align="center"' : $alignisi[$x];
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'price')) > -1 ? 'align="right"' : $alignisi[$x];
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'total')) > -1 ? 'align="right"' : $alignisi[$x];
							} else {
								break;
							}
						}
						break;
					}
					?>
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
						echo '<td ' . $alignisi[$i] . '><div>' . Rupiah($row->$isikolom, ($i == ($kolomjumlah - 1)) ? 2 : 0) . '</div></td>';

						if ($i == ($kolomjumlah - 1)) {
							$subtotal += $row->$isikolom;
						}
					}

				?>

					</tr>
				<?php
				}
				$no--;
				?>
			</tbody>
			<tfoot style="font-weight: bold;">
			<?php if ($data->cbkeuntungan) { ?>
				<tr> 
					<td colspan="<?= @$kolomjumlah - 1 ?>" align="right">JUMLAH</td>
					<td align="right"><?= @Rupiah($subtotal, 2) ?></td>
					<?php
					$keuntungan = $data->keuntungan;
					$keuntungan = $subtotal * ($keuntungan / 100);
					$subtotal += $keuntungan;
					?>
				</tr>
				<tr>
					<td colspan="<?= @$kolomjumlah - 1 ?>" align="right">KEUNTUNGAN MITRA</td>
					<td align="right"><?= @Rupiah($keuntungan, 2) ?></td>
				</tr>
			<?php } ?>
				<tr>
					<td colspan="{{$kolomjumlah-1}}" align="right">SUBTOTAL</td>
					<td align="right">{{Rupiah($subtotal,2)}}</td>
				</tr>
				<tr>
					<td colspan="{{$kolomjumlah-1}}" align="right">PPN {{$data->ppnpersen ?? 10}}%</td>
					<td align="right">{{Rupiah($data->ppnnilai,2)}}</td>
				</tr>
				<tr>
					<td colspan="{{$kolomjumlah-1}}" align="right">TOTAL</td>
					<td align="right" name="td_total">{{Rupiah($data->ppnnilai + $subtotal,2)}}</td>
					<input type="hidden" name="td_total" value="{{$data->ppnnilai + $subtotal}}">
				</tr>
			</tfoot>
		</table>
		@endif
		<br>
		Demikian Berita Acara ini dibuat untuk digunakan sebagaimana mestinya <br><br>

		<table style="font-size:smaller; margin-top:20px">
			<tbody>
				<tr>
					<td width="300px" style="padding-left: 20px;">KOPKAR TRENDY</td>
					<td>{{$data->perusahaan}}</td>
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

	<div class="page_break">
		<div style="text-align: center;">
			<h3 class="card-title"><U>BERITA ACARA PEMERIKSAAN</U><br>(BAP)</h3>
		</div>
		<br>
		<table>
			<tbody style="vertical-align: top;">
				<tr>
					<td style="width: 150px;">Pengadaan</td>
					<td style="text-align: justify;">: {{$data->nama}}</td>
				</tr>
				<tr>
					<td>Lokasi</td>
					<td>: {{$data->alamat}}</td>
				</tr>
				<tr>
					<td>Perjanjian Nomor</td>
					<td>: {{$data->no_spk}}</td>
				</tr>
				<tr>
					<td>Mitra Kerja</td>
					<td>: KOPKAR TRENDY</td>
				</tr>
				<tr>
					<td>Nilai Kontrak</td>
					<td>: Rp. {{rupiah($data->nilai)}},- (Sudah Termasuk PPN {{$data->ppnpersen ?? 10}}%)</td>
				</tr>
			</tbody>
		</table>

		<p style="text-align:justify">Pada hari ini, <b>{{ namahari($invoice->tanggalba) }}</b> Tanggal <b>{{ str_replace('Rupiah','',terbilang(date('d', strtotime($invoice->tanggalba)))) }}</b> Bulan <b>{{ bulan(date('m', strtotime($invoice->tanggalba))) }}</b> Tahun <b>{{ str_replace('Rupiah','',terbilang(date('Y', strtotime($invoice->tanggalba)))) }}</b>, kami yang bertanda tangan dibawah ini : <br></p>

		<table style="margin-left: 20px;">
			<tbody>
				<tr>
					<td style="width: 20px;">1. </td>
					<td style="width: 100px;">Nama</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ $manager->nama}}</td>
				</tr>
				<tr>
					<td style="width: 20px;"></td>
					<td>Jabatan</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ $manager->jabatan}}</td>
				</tr>

			</tbody>
		</table>

		<br><br>
		<table style="margin-left: 20px;">
			<tbody>
				<tr>
					<td style="width: 20px;">2. </td>
					<td style="width: 100px;">Nama</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ $data->pemesan}}</td>
				</tr>
				<tr>
					<td></td>
					<td style="width: 100px;">Jabatan</td>
					<td style="width: 20px;" align="center"> : </td>
					<td>{{ $data->jabatan}}</td>
				</tr>

			</tbody>
		</table>

		<p style="text-align:justify">Telah melaksanakan Pemeriksaan atas Pekerjaan Pengadaan tersebut diatas dengan hasil sebagai berikut :</p>

		<table>
			<tbody>
				<tr>
					<td style="width: 20px; vertical-align:top">1. </td>
					<td style="text-align: justify;">Pekerjaan Perjanjian {{$data->nama}} telah selesai dilaksanakan 100% dengan hasil BAIK dan sesuai spesifikasi yang disepakati dalam Perjanjian, dengan rincian pekerjaan sebagai berikut :</td>
				</tr>
			</tbody>
		</table>
		<br>
		@if (isset($item) && count($item) > 0)
		<table style="margin-left: 20px;" cellpadding="5" cellspacing="0" width="80%" border="1">
			<thead align="center">

				<tr>
					<th style="width: 30px">No.</th>

					<?php
					foreach ($item as $row) {
						$namakol = 'No';
						$kolomjumlah = $row->kolomjumlah;

						for ($x = 1; $x < 4; $x++) {
							$namakolom = 'kolom' . $x . '_nama';

							if ($row->$namakolom != '') {
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'qty')) > -1 ? 'align="center"' : '';
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'satuan')) > -1 ? 'align="center"' : $alignisi[$x];
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'harga')) > -1 ? 'align="right"' : $alignisi[$x];
								$alignisi[$x] =  (strpos(strtolower($row->$namakolom), 'jumlah')) > -1 ? 'align="right"' : $alignisi[$x];
								
								echo '<th><div>' . $row->$namakolom . '</div></th>';
								$namakol .= ',' . $row->$namakolom;
								
							} else {
								break;
							}
						}
						break;
					}
					?>
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
						// $align[$i] = ($i == ($kolomjumlah - 1)) ? 'align="right"' : $alignisi[$i];
						echo '<td ' . $alignisi[$i] . '><div>' . Rupiah($row->$isikolom, ($i == ($kolomjumlah - 1)) ? 2 : 0) . '</div></td>';
						if ($i == ($kolomjumlah - 1)) {
							$subtotal += $row->$isikolom;
						}
					}

				?>

					</tr>
				<?php
				}
				$no--;
				?>
			</tbody>

		</table>
		@endif
		<br>
		<table>
			<tbody>
				<tr>
					<td style="width: 20px;">2. </td>
					<td>Waktu pelaksanaan pekerjaan tidak mengalami keterlambatan.</td>
				</tr>
				<tr>
					<td style="width: 20px; vertical-align:top">3. </td>
					<td>Hasil Pekerjaan telah diserahkan kepada Pihak TELKOM dengan kondisi LENGKAP dan BAIK.</td>
				</tr>
			</tbody>
		</table>

	</div>
	<div class="page_break">
		<br><br><br>
		Berdasarkan hal tersebut diatas maka dengan ini dinyatakan :
		<br><br>
		<center><b>PEKERJAAN DITERIMA DENGAN HASIL BAIK <br>BAPP DAPAT DITERBITKAN</b></center>
		<br>
		<br>
		Demikian Berita Acara ini dibuat untuk digunakan sebagaimana mestinya <br><br>

		<table style="font-size:smaller; margin-top:20px">
			<tbody>
				<tr>
					<td width="300px" style="padding-left: 20px;">KOPKAR TRENDY</td>
					<td>{{$data->perusahaan}}</td>
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