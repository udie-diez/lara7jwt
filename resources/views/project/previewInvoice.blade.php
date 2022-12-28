<html>

<head>
	<title>Invoice</title>
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

		table>tbody>tr>td {
			vertical-align: top;
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
	//	$alamat = explode('Jl.', $data->alamat ?? '');
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
						{{ $alamat[1] ?? '' }}<br>
						{{ $data->kota ?? '' }}<br>
					</td>
				</tr>
			</tbody>
		</table>

		<br>

		<table>
			<tbody>
				<tr>
					<td width="150px">Nomor Invoice/Faktur</td>
					<td width="10px">: </td>
					<td>{{ $invoice->nomor ?? '' }}</td>
				</tr>
				<tr>
					<td width="150px">Nota Pesanan</td>
					<td>: </td>
					<td>{{ $data->nama ?? '' }}</td>
				</tr>
				<tr>
					<td width="150px">Nomor</td>
					<td>: </td>
					<td>{{ $data->no_spk ?? '' }}</td>
				</tr>
				<tr>
					<td width="150px">Tanggal</td>
					<td>: </td>
					<!-- <td>{{ namahari($data->tgl_spk ?? '').', '. IndoTglx($data->tgl_spk ?? '') }}</td> -->
					<td>{{ IndoTglx($data->tgl_spk ?? '') }}</td>
				</tr>
			</tbody>
		</table>

	</div>
	<br>
	@if($invoice->jenis==1)
	@if (isset($item) && count($item) > 0)
	<table style="font-size: small;" cellpadding="5" cellspacing="0" width="100%" border="1">
		<thead align="center">
			<tr>
				<th style="width: 30px">No.</th>

				<?php
				foreach ($item as $row) {
					$namakol = 'No';
					$kolomjumlah = $row->kolomjumlah;

					for ($x = 1; $x < 9; $x++) {
						$namakolom = 'kolom' . $x . '_nama';
						// $align = ($x == ($kolomjumlah - 1)) ? 'align="right"' : 'align="left"';

						if ($row->$namakolom != '') {
							echo '<th ><div>' . $row->$namakolom . '</div></th>';
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
				// dd( (strpos(strtolower('Qty'),'qty')));
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
				<td colspan="{{$kolomjumlah-1}}" align="right">SUB TOTAL</td>
				<td align="right">{{Rupiah($subtotal,2)}}</td>
			</tr>
			<tr>
				<td colspan="{{$kolomjumlah-1}}" align="right">PPN {{$data->ppnpersen ?? 10}}%</td>
				<td align="right">{{Rupiah($data->ppnnilai,2)}}</td>
			</tr>
			<tr>
				<td colspan="{{$kolomjumlah-1}}" align="right">TOTAL</td>
				<td align="right" name="td_total">{{Rupiah(($data->ppnnilai) + $subtotal,2)}}</td>
				<input type="hidden" name="td_total" value="{{($subtotal * 0.1) + $subtotal}}">
			</tr>
		</tfoot>
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
	<div style="margin-left: 400px; font-size:small ">
		Hormat Kami,<br>
		Jakarta, {{ IndoTglx($invoice->tanggal) }}
		<p style="padding-top: 60px;"> &nbsp;</p>
		<b><u>{{ $data->pengurus }}</u></b><br>
		<span style="padding-left: 40px;">{{ $data->jabatan }}</span>

	</div>
</body>

</html>