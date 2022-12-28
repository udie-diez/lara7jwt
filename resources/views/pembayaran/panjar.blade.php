@extends('layouts.home')

@section('maincontent')


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
					<a href='#' value="{{ route('showJurnalPembayaran', $data->id ?? 0) }}" class="dropdown-item modalMd" title="Jurnal" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> Lihat Jurnal</a>
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">

		<div class="row">
			<div class="col-sm-12">
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Penerima</label>
					<label class="col-form-label col-sm-10">{{ $data->penerima ?? '' }}</label>
				</div>
			</div>
		</div>
		<div class="row">

			<div class="col-sm-12">
				<form method="POST" action="{{ route('updatePembayaranPanjar') }}">
					@csrf

					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Tgl. Transaksi</label>
						<div class="col-sm-4">
							<input type="hidden" name="id" value="{{ $bayarid->id ?? ''}}">
							<input type="hidden" name="panjarid" value="{{ $data->id ?? ''}}">
							<input type="text" class="form-control pickadate" required name="tanggal" value="<?php if (isset($data->tanggal)) echo date('d/m/Y', strtotime($data->tanggal)) ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Cara Pembayaran</label>
						<div class="col-sm-4">
							<select name="cara" id="cara" required data-placeholder="Pilih" class="select">
								<option value="Transfer Bank" <?php if (isset($data->cara)) {
																	if ($data->cara == 'Transfer Bank') echo 'selected';
																} ?>>Transfer Bank</option>
								<option value="Kas Tunai" <?php if (isset($data->cara)) {
																if ($data->cara == 'Kas Tunai') echo 'selected';
															} ?>>Kas Tunai</option>
								<option value="Cek & Giro" <?php if (isset($data->cara)) {
																if ($data->cara == 'Cek & Giro') echo 'selected';
															} ?>>Cek & Giro</option>
							</select>
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Nilai Panjar  </label>
						<div class="col-sm-4">
							<input class="form-control"  name="nilai"  type="text" value="{{ number_format($data->nilai, 0,',','.') ?? '' }}">
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Catatan</label>
						<div class="col-sm-7">
							<textarea class="form-control" placeholder="memo / catatan" name="catatan" rows="3">{{ $data->catatan ?? 'Pembayaran panjar ke '.$data->penerima }}</textarea>
						</div>
					</div>
					<table class="table mt-3 basicxxx mb-3" style="font-size: small;">
						<thead>
							<tr>
								<th>No.</th>
								<th>Uraian</th>
								<th>Tanggal</th>
								<th class="text-right">Jumlah (Rp.)</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td>1.</td>
								<td>Panjar : {{$data->nomor}}</td>
								<td>{{ date('d/m/Y', strtotime($data->tanggal ?? '')) }}</td>
								<td class="text-right"><input type="text" class="text-right bg-info-300" name="td_nilai" id="td_nilai" value="{{ isset($data->nilai) ? number_format($data->nilai, 0,',','.') : number_format($data->invoicetotal, 0,',','.')  }}"> </td>
							</tr>

						</tbody>
						 
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
								<a href="
								<?php 
								 $kodeubah == 1 ? route('showPembayaran', $data->id ?? 0) : route('showPanjar', $data->panjarid ?? $panjarid ?? '');
								?>
								
								" class="btn btn-outline-success btn-sm" title="Batal / Kembali"> {{ $kodeubah==1 ? 'BATAL' : 'KEMBALI' }}</a>
								@if($kodeubah==1 || $kodeubah == 2)
								<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data Pembayaran Panjar ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
								@else
								<a href="{{ route('editPembayaran',$data->id ?? '' ) }}"  class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
								@endif
								<a href="{{ action('PembayaranCont@destroy',['id'=>$data->id ?? 0]) }}" class="btn btn-outline-warning btn-sm" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')">Hapus</a>

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

		function formatRupiah(angka, prefix) {
			var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
				split = number_string.split(','),
				sisa = split[0].length % 3,
				rupiah = split[0].substr(0, sisa),
				ribuan = split[0].substr(sisa).match(/\d{3}/gi);

			if (ribuan) {
				var separator;
				separator = sisa ? '.' : '';
				rupiah += separator + ribuan.join('.');
			}

			rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
			return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
		}
	</script>
	@endsection