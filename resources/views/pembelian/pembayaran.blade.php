@extends('layouts.home')

@section('maincontent')

@include('layouts.mylib')
<style>
	.table-ang {
		font-size: small;
		width: 100%;
	}
</style>
<div class="card">
	<div class="card-header  header-elements-inline">
		<h5 class="card-title">{{$tag['judul']}}</h5>

		<div class="header-elements">
			<!-- <div class="list-icon">
				<a href="{{ route('printInvoice', $invoice->id ?? 0) }}" class="btn btn-outline-success btn-sm" title="Cetak Invoice"><i class="icon-printer"></i> Cetak</a>
				<a href="{{ route('invoice') }}" class="btn btn-outline-info btn-sm">Daftar Invoice </a>
			</div> -->
			<div class="btn-group">
				<button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">Tindakan</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="{{ route('showJurnalPembayaranPembelian', $pembayaran->id ?? 0) }}" class="dropdown-item" title="Cetak Kwitansi"> Lihat Jurnal</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group row mb-0">
					 
				</div>
			</div>
		</div>
		<div class="row">

			<div class="col-sm-12">
				<form method="POST" action="{{ route('updatePembelianPembayaran') }}">
					@csrf

					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Tgl. Transaksi</label>
						<div class="col-sm-4">
							<input type="hidden" name="id" value="{{ $pembayaran->id ?? ''}}">
							<input type="hidden" name="pembelianid" value="{{ $data->id ?? ''}}">
							<input type="text" class="form-control pickadate" required name="tanggal" value="<?php if (isset($pembayaran->tanggal)) echo date('d/m/Y', strtotime($pembayaran->tanggal)) ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Cara Pembayaran</label>
						<div class="col-sm-4">
							<select name="cara" id="cara" required data-placeholder="Pilih" class="select">
								<option value=""></option>
								<option value="Transfer Bank" <?php if (isset($pembayaran->cara)) {
																	if ($pembayaran->cara == 'Transfer Bank') echo 'selected';
																} ?>>Transfer Bank</option>
								<option value="Kas Tunai" <?php if (isset($pembayaran->cara)) {
																if ($pembayaran->cara == 'Kas Tunai') echo 'selected';
															} ?>>Kas Tunai</option>
								<option value="Cek & Giro" <?php if (isset($pembayaran->cara)) {
																if ($pembayaran->cara == 'Cek & Giro') echo 'selected';
															} ?>>Cek & Giro</option>
							</select>
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-2">Dibayar dari kas/bank</label>
						<div class="col-sm-4">
							<select name="akunid" class="select" required data-placeholder="Pilih">
								<option value=""></option>
								@foreach($akun as $r)
								<option value="{{$r->id}}" <?php if (($pembayaran->akunid ?? old('akunid')) == $r->id) echo 'selected';
															?>>{{'('.$r->kode . ') - ' .$r->nama}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Catatan</label>
						<div class="col-sm-7">
							<textarea class="form-control" placeholder="memo / catatan" name="catatan" rows="3">{{ $pembayaran->catatan ?? '' }}</textarea>
						</div>
					</div>
					<table class="table mt-3 basicxxx mb-3" style="font-size: small;">
						<thead>
							<tr>
								<th>Nomor</th>
								<th>Deskripsi</th>
								<th class="text-right">Total</th>
								<th class="text-right">Sisa Tagihan</th>
								<th class="text-right">Jumlah Pembayaran (Rp.)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td> {{ '#'.$data->kode }}</td>
								<td> {{ $data->keterangan }}</td>
								<td class="text-right">{{ Rupiah($data->total) }} </td>
								<td class="text-right">{{ Rupiah($data->total) }} </td>
								<td class="text-right"><input type="text" class="text-right bg-info-300" name="td_nilai" id="td_nilai" value="{{ isset($pembayaran->nilai) ? Rupiah($pembayaran->nilai) : Rupiah($data->total) }}"> </td>
							</tr>

						</tbody>
						<tfoot style="font-size: medium; font-weight:bold">
							<tr>
								<td class="text-right" colspan="4">TOTAL</td>
								<td class="text-right">{{ Rupiah($data->total ?? 0) }}</td>
							</tr>
						</tfoot>
					</table>

					<div class="form-group row">
						<div class="col-sm-9">
							@if ($errors->any())
							<div class="alert alert-danger">
								<ul>
									@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
									@endforeach
								</ul>
							</div>
							@endif
							@if ($message = Session::get('sukses'))
							<div class="alert alert-success alert-block">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>{{ $message }}</strong>
							</div>
							@php Session::forget('sukses') @endphp

							@endif
							@if ($message = Session::get('warning'))
							<div class="alert alert-danger alert-block">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>{{ $message }}</strong>
							</div>
							@php Session::forget('warning') @endphp

							@endif
						</div>
					</div>
					<div class="form-group row mb-0">
						<div class="col-sm-12">

							<div class="list-icon text-right">
								<a href="{{ route('showPembelian', $data->id ?? '') }}" class="btn btn-outline-success btn-sm" title="Batal / Kembali"> {{ ($kodeubah==1 || $kodeubah == 2 ) ? 'BATAL' : 'KEMBALI' }}</a>
								@if($kodeubah==1 || $kodeubah == 2)
								<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data Pembayaran ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
								@else
								<a href="{{ route('editPembelianPembayaran',$pembayaran->id ?? '' ) }}" type="button" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
								@endif
								@if($kodeubah==9)
								<a href="{{ action('PembelianCont@destroyPembayaran',['id'=>$pembayaran->id ?? 0]) }}" class="btn btn-outline-warning btn-sm" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')">Hapus</a>
								@endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>

	</div>

	<script type="text/javascript">
		$(document).ready(function() {

			$('.pickadate').pickadate({
				format: 'dd/mm/YYYY'
			})

			var td_nilai = document.getElementById('td_nilai');
			td_nilai.addEventListener('keyup', function(e) {
				td_nilai.value = formatRupiah(this.value);
			});

			$('#jenis').on('change', function() {

				if (this.value == 2) {
					$('#table_2').show();
					$('#table_1').hide();

				} else {
					$('#table_1').show();
					$('#table_2').hide();
				}
			})
		});
	</script>
	@endsection