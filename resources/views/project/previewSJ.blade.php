<html>

<head>
	<title>Surat Jalan</title>
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
		<h2 class="card-title">SURAT JALAN</h2>
		Nomor : <span style="font-weight: bold;">{{ $invoice->nomor ?? '' }}</span>
	</div>
	<hr>
	<div style="margin-bottom: 30px;">
		Pekerjaan : <span style="font-weight: bold;">{{$data->nama}}</span>
	</div>
	@if($invoice->jenis==1)
	@if (isset($item) && count($item) > 0)
	<table style="font-size: small;" cellpadding="5" cellspacing="0" width="100%" border="1">
		<thead>
			<tr>
				<th style="width: 30px">No.</th>

				<?php
				foreach ($item as $row) {
					$namakol = 'No';
					$kolomjumlah = $row->kolomjumlah;

					for ($x = 1; $x < 9; $x++) {
						$namakolom = 'kolom' . $x . '_nama';
						 

						if (strlen($row->$namakolom) > 1 && $x != $kolomjumlah - 1 && strtolower($row->$namakolom) != 'harga') {
							echo '<th align="center"><div>' . $row->$namakolom . '</div></th>';
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
				echo '<tr align="center"><td  width="30px">' . $no++ . '</td>';

				for ($i = 1; $i < $x; $i++) {
					$isikolom = 'kolom' . $i . '_isi';
					$namakolom = 'kolom' . $i . '_nama';
					$align = ($i == 1) ? 'align="left"' : '';
					if (strlen($row->$namakolom) > 1 && $i != $kolomjumlah - 1 && strtolower($row->$namakolom) != 'harga') {

						echo '<td ' . $align . '><div>' . Rupiah($row->$isikolom, ($i == ($kolomjumlah - 1)) ? 2 : 0) . '</div></td>';
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
	<span style="margin-left:370px;"> Jakarta, </span><span style="margin-left: 130px;">{{date('Y')}}</span>

	<table style="font-size:smaller; margin-top:20px">
		<tbody>
			<tr>
				<td width="370px" style="padding-left: 20px;">KOPKAR TRENDY</td>
				<td align="center">Yang Menerima,</td>
			</tr>
			<tr>
				<td></td>
				<td align="center">{{$data->alias}}</td>
			</tr>
			<tr>
				<td><br><br><br><br></td>
			</tr>
			<tr>
				<td style="font-weight: bold; padding-left:20px">
					<u>{{ $manager->nama }}</u>
				</td>
				<td>
					_______________________________
				</td>
			</tr>
			<tr>
				<td style="padding-left:30px">
					{{ $manager->jabatan }}
				</td>
				<td>NIK. </td>
			</tr>
		</tbody>
	</table>

</body>

</html>