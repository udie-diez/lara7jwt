@extends('layouts.home')

@section('maincontent')

@include('layouts.mylib')

<script src="{{ url('/')}}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/media/fancybox.min.js"></script>
<?php

use App\Helpers\UserAkses;
?>
<style>
	.custom-checkbox {
		margin-top: 10px;
	}
</style>
@if ($kodeubah!=1 && $kodeubah!=3)
<link href="{{ url('/') }}/assets/js/mycss.css" rel="stylesheet" type="text/css">
<script>
	$(document).ready(function() {
		$("form :input").attr('readonly', true);
		$("form :checkbox").attr('disabled', true);
		$("#jenispanjar").attr('disabled', true);
		$("#btn_notapesanan").hide();
	})
</script>
@endif

<div class="card">
	<div class="card-header  header-elements-inline">
		<h5 class="card-title">{{$tag['judul']}}</h5>
		<?php
		$statusi[0] = '';
		$statusi[1] = 'APPROVED - BELUM DIBAYAR';
		$statusi[2] = '';
		$statusi[3] = 'SUDAH DIBAYAR';
		$statusi[4] = 'BATAL';
		$statusi[5] = 'BELUM DISETUJUI';
		$statusi[6] = 'BELUM DISETUJUI';
		$statusi[7] = 'BELUM DISETUJUI';

		// $statusi = ['', 'BELUM DIBAYAR', ' ', 'LUNAS', 'BATAL'];
		?>

		<span style="display: <?= @$panjar->status ? '' : 'none' ?>" class="mr-1 badge bg-info pb-0">
			<h6>{{ $statusi[$panjar->status ?? 0] }}</h6>
		</span>

		<div class="header-elements">
			<div class="btn-group">
				<button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">Tindakan</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="{{ route('createPembayaranPanjar', $panjar->id ?? 0) }}" class="dropdown-item" title="Pembayaran Panjar">Pembayaran</a>
					<a class="dropdown-item" href="#" data-toggle="modal" title="Upload berkas panjar yang sudah disetujui dan ditandatangani" data-target="#modalFile" onclick="upload(<?php echo $panjar->id ?? 0; ?>)">Upload Berkas Panjar</a>

					<a href="{{ route('printPanjar', $panjar->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak Panjar"> Cetak Form Panjar</a>

					@if (UserAkses::cek_akses('persetujuan_panjar', 'lihat'))
					<a href="{{ route('persetujuanPanjar', $panjar->id ?? 0) }}" onclick="return confirm('Anda yakin akan MENYETUJUI panjar ini ??')" class="dropdown-item" title="Persetujuan Panjar"> Setuju Panjar</a>
					<a href="{{ route('batalpersetujuanPanjar', $panjar->id ?? 0) }}" onclick="return confirm('Anda yakin akan MEMBATALKAN PERSETUJUAN Anda untuk panjar ini ??')" class="dropdown-item" title="Batalkan Persetujuan Panjar"> Batal Setuju Panjar</a>
					@endif
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">
		<div class="row mb-3">
			<div class="col-sm-12">

				@if ($message = Session::get('sukses_persetujuan'))
				<div class="alert alert-success alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
				@php Session::forget('sukses_persetujuan') @endphp

				@endif
				@if ($message = Session::get('error_persetujuan'))
				<div class="alert alert-danger alert-block">
					<button type="button" class="close" data-dismiss="alert">×</button>
					<strong>{{ $message }}</strong>
				</div>
				@php Session::forget('error_persetujuan') @endphp

				@endif

				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Jenis Panjar</label>
					<div class="col-sm-3">
						<select name="jenispanjar" id="jenispanjar" data-placeholder="Pilih" class="select">
							<option value=1 <?php if (isset($panjar->jenis)) {
												if ($panjar->jenis ==  1) echo 'selected';
											} ?>>PANJAR NON SPK</option>
							<option value=0 <?php if (isset($panjar->jenis)) {
												if ($panjar->jenis ==  0) echo 'selected';
											} {
												if (isset($jenispanjar)) echo 'selected';
											} ?>>PANJAR SPK</option>
						</select>
					</div>
				</div>
			</div>
		</div>

		<hr>
		@if(isset($project))
		<div id="pilihproject" style="display: <?php echo isset($projectid_f) && $projectid_f > 0 ? '' : 'none' ?> ;">
			<form  method="POST" action="{{ route('infoProjectPanjar') }}">
				<div class="form-group row">
					<label class="col-form-label col-sm-2">PROJECT : </label>
					@csrf
					<div class="col-sm-7">
						<select name="f_project" id="f_project" data-placeholder="Tentukan Project" class="select-search">
							<option value=""></option>
							@foreach($project as $r)
							<option value="{{$r->id}}" <?php if (isset($projectid_f)) {
															if ($projectid_f == $r->id) echo 'selected';
														} ?>>{{$r->no_po ? 'PO: '.$r->no_po .' || '.$r->nama : 'SPK: '. $r->no_spk .' || '.$r->nama}}</option>
							@endforeach
						</select>
					</div>
					<div class="col-sm-1">
						<button type="submit" class="btn btn-outline-info btn-sm">Tampilkan</button>
					</div>
				</div>
			</form>
			@endif
			<div class="row">
				<div class="col-sm-12">
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Perusahaan</label>
						<label class="col-form-label col-sm-10">{{ $data->perusahaan ?? '' }}</label>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Pesanan</label>
						<label class="col-form-label col-sm-10">{{ $data->nama ?? '' }}</label>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Nomor SPK </label>
						<label class="col-form-label col-sm-4">{{ $data->no_spk ?? ''}}</label>
						<label class="col-form-label col-sm-2">Tanggal : {{ IndoTgl($data->tgl_spk ?? '') }}</label>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Nilai Project</label>
						<label class="col-form-label col-sm-10">Rp. {{ Rupiah($data->nilai ?? '') }}</label>
					</div>
				</div>
			</div>
			<hr>
		</div>
		<div class="row">

			<div class="col-sm-12">
				<form method="POST" action="{{ route('updatePanjar') }}" id="formx">
					<fieldset>
						@csrf
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Nomor Panjar</label>
							<div class="col-sm-2">
								<input type="text" placeholder="(Auto)" readonly class="form-control" name="nomor" id="nomor" value="{{ $panjar->nomor ?? '' }}">
							</div>
						</div>
						<div class="form-group row mb-0">
							<label class="col-form-label col-sm-2">Tanggal Panjar</label>
							<div class="col-sm-3">
								<input type="hidden" name="id" id="panjarid" value="{{ $panjar->id ?? ''}}">
								<input type="hidden" name="projectid" value="{{ $data->id ?? ''}}">
								<input type="hidden" id="nilaiproject" value="{{ $data->nilai ?? ''}}">
								<input type="hidden" name="jenis" id="jenispanjarx" value="{{ $panjar->jenis ?? '1'}}">
								<input type="text" class="form-control pickadate" required id="tanggal" name="tanggal" value="<?php if (isset($panjar->tanggal)) echo date('d/m/Y', strtotime($panjar->tanggal)) ?>">
							</div>
						</div>
						<div class="form-group row mb-3">
							<label class="col-form-label col-sm-2">Nilai Panjar(Rp)</label>
							<div class="col-sm-2">
								<input type="text" class="form-control text-right" required name="nilai" id="nilai" value="{{ Rupiah($panjar->nilai ?? '',0) }}">
							</div>
						</div>
						<div class="form-group row mb-3">
							<label class="col-form-label col-sm-2">Catatan</label>
							<div class="col-sm-6">
								<textarea class="form-control" rows="2" name="catatan" id="catatan">{{ $panjar->catatan ?? '' }}</textarea>
							</div>
						</div>

						<div class="form-group row mb-0">
							<label class="col-form-label col-sm-2">Jaminan Pembayaran</label>
							<div class="col-sm-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="jaminan1" id="jaminan1" <?= @$panjar->jaminan1 == 'on' ? 'checked' : '' ?>>
									<label class="custom-control-label" for="jaminan1">Potong Gaji</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="jaminan2" id="jaminan2" <?= @$panjar->jaminan2 == 'on' ? 'checked' : '' ?>>
									<label class="custom-control-label" for="jaminan2">SPK / Kontrak</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="jaminan3" id="jaminan3" <?= @$panjar->jaminan3 == 'on' ? 'checked' : '' ?>>
									<label class="custom-control-label" for="jaminan3">Auto Debet Rekening</label>
								</div>
							</div>
						</div>
						<div class="form-group row mb-0">
							<label class="col-form-label col-sm-2">Lampiran Penyelesaian Pekerjaan</label>
							<div class="col-sm-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="lampiran1" id="lampiran1" <?= @$panjar->lampiran1 == 'on' ? 'checked' : '' ?>>
									<label class="custom-control-label" for="lampiran1">Kwitansi Pengeluaran</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="lampiran2" id="lampiran2" <?= @$panjar->lampiran2 == 'on' ? 'checked' : '' ?>>
									<label class="custom-control-label" for="lampiran2">Copy KTP/Karpeg</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="lampiran3" id="lampiran3" <?= @$panjar->lampiran3 == 'on' ? 'checked' : '' ?>>
									<label class="custom-control-label" for="lampiran3">Copy Justifikasi</label>
								</div>
							</div>
							<div class="col-sm-2">
								<div class="custom-control custom-checkbox">
									<input type="checkbox" class="custom-control-input" name="lampiran4" id="lampiran4" <?= @$panjar->lampiran4 == 'on' ? 'checked' : '' ?>>
									<label class="custom-control-label" for="lampiran4">Form BBQ</label>
								</div>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">User / Peminta Panjar</label>
							<div class="col-sm-3">
								<select name="pemberi" id="pemberi" required data-placeholder="Pilih" class="select-search">
									<option value=""></option>

									@if(isset($pemesan)) @foreach($pemesan as $r)
									<option value="{{$r->id}}" <?php if (isset($panjar->pemberiid)) {
																	if ($panjar->pemberiid == $r->id) echo 'selected';
																} else {
																	if ($data->pemesanid ?? 0 == $r->id) echo 'selected';
																} ?>>{{$r->nama}}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div hidden class="col-sm-1">
								<div class="list-icon" style="margin-top: 10px;">
									<a href="#" value="{{ route('showPemesan','pemberi') }}" class="list-icons-item text-info-600 modalMd" title="Tambah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-stack-plus"></i></a>

									<a href="#" id="btn-edit-pemberi" value="{{ route('showPemesan',0) }}" class="list-icons-item text-info-600 modalMd" title="Lihat Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
								</div>
							</div>


							<label class="col-form-label col-sm-1 text-right">Mengetahui</label>
							<div class="col-sm-3">
								<select name="mengetahuipemberi" id="mengetahuipemberi" data-placeholder="Pilih" class="select-search">
									<option value=""></option>

									@if(isset($pemesan)) @foreach($pemesan as $r)
									<option value="{{$r->id}}" <?php if (isset($panjar->mengetahui1id)) {
																	if ($panjar->mengetahui1id == $r->id) echo 'selected';
																} ?>>{{$r->nama}}</option>
									@endforeach
									@endif
								</select>
							</div>
							<div hidden class="col-sm-1">
								<div class="list-icon" style="margin-top: 10px;">
									<a href="#" id="btn-edit" value="{{ route('showPemesan','mengetahui') }}" class="list-icons-item text-info-600 modalMd" title="Tambah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-stack-plus"></i></a>
									<a href="#" id="btn-edit-mengetahui" value="{{ route('showPemesan',0) }}" class="list-icons-item text-info-600 modalMd" title="Lihat Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
								</div>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form	-label col-sm-2">Penerima Pekerjaan</label>
							<div class="col-sm-3">
								<select name="penerima" id="penerima" data-placeholder="Pilih" class="select-search">
									<option value=""></option>

									@if(isset($pengelola)) @foreach($pengelola as $r)
									<option value="{{$r->id}}" <?php if (isset($panjar->penerimaid)) {
																	if ($panjar->penerimaid == $r->id) echo 'selected';
																} else {
																	if ($data->pic ?? 0 == $r->id) echo 'selected';
																} ?>>{{$r->nama}}</option>
									@endforeach
									@endif
								</select>
							</div>

							<label class="col-form-label col-sm-1 text-right">Mengetahui</label>
							<div class="col-sm-3">
								<select name="mengetahuipenerima" id="mengetahuipenerima" data-placeholder="Pilih" class="select-search">
									<option value=""></option>

									@if(isset($pengurus)) @foreach($pengurus as $r)
									<option value="{{$r->id}}" <?php if (isset($panjar->mengetahui2id)) {
																	if ($panjar->mengetahui2id == $r->id) echo 'selected';
																} ?>>{{$r->nama}}</option>
									@endforeach
									@endif
								</select>
							</div>
						</div>
						@if($panjar->file??''!='')
						<div class="form-group row pt-5">
							<label class="col-form-label col-sm-2">Berkas Panjar</label>
							<div class="col-sm-7">
								<a class="form-control" href="{{ url('/').'/assets/panjar/'. $panjar->file}}" id="filepanjar" data-popup="lightbox">{{ $panjar->file ?? '' }}</a>
							</div>
						</div>
						@endif
			</div>
		</div>

		<div class="card mt-3" style="width: 90%; display:none" id="tabel_pembayaran">
			<div class="card-body">

				<h6>Daftar Pembayaran</h6>
				<table class="table">
					<thead class="bg-slate">
						<tr>
							<th>No.</th>
							<th>Pembayaran</th>
							<th>Tanggal</th>
							<th class="text-right">Jumlah(Rp)</th>
							<th class="text-right">Aksi</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($daftarpembayaran))
						@foreach($daftarpembayaran as $row)
						<tr>
							<td>{{ $loop->iteration}}</td>
							<td>#{{ $row->nomor}}</td>
							<td>{{ IndoTgl($row->tanggal)}}</td>
							<td class="text-right">{{ Rupiah($row->nilai) }}</td>
							<td class="text-right">
								<div class="list-icons">
									<a href="{{ route('showPembayaran',$row->id) }}" class="list-icons-item text-info-600" title="Detail Pembayaran"><i class="icon-eye8"></i></a>
								</div>
							</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
		</fieldset>

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

		<div class="form-group row mt-3">
			<div class="col-sm-9 text-right">
				<div class="list-icon">
					<a href="{{ $kodeubah==1 ? route('showPanjar',$panjar->id ) : route('panjar') }}" class="btn btn-outline-warning btn-sm" title="Batal / Kembali"> {{ $kodeubah==1 ? 'BATAL' : 'KEMBALI' }}</a>
					@if($kodeubah==1 || $kodeubah==3)
					<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data Invoice ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
					@else
					<a href="{{ route('editPanjar',$panjar->id ?? '' ) }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
					@endif
				</div>
			</div>
		</div>
		</form>

		<hr>
		<div class="mt-3" style="width: 75%; display:none" id="tabel_penggunaan">
			<div class="card-body1">

				<h6>Daftar Nota Pesanan</h6>
				<div class="pb-3 text-right">
					<a id="btn_notapesanan" class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ route('createPengunaanPanjar', $panjar->pemberiid ?? 0) }}" title="Tambahkan Nota Pesanan" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Nota Pesanan</a>
				</div>
				<table class="table basicxx">
					<thead class="bg-slate">
						<tr>
							<th>No.</th>
							<th>Nota Pesanan</th>
							<th>Uraian</th>
							<th class="text-right">Nilai (Rp)</th>
							<th class="text-center">Aksi</th>
						</tr>
					</thead>
					<tbody>

						@php $total=0 @endphp
						@if(isset($panjardetail))
						@foreach($panjardetail as $row)
						@php $total += $row->nilai @endphp
						<tr>
							<td>{{$loop->iteration}}</td>
							<td>{{$row->no_spk}}</td>
							<td>{{$row->nama}}</td>
							<td class="text-right">{{ Rupiah($row->nilai)}}</td>
							<td class="text-center">
								@if($kodeubah==1 || $kodeubah==3)
								<div class="list-icons">
									<a href="#" value="{{ route('showPenggunaanPanjar', $row->id ?? '') }}" id="btn-edit" class="list-icons-item text-info-600 modalMd" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
									<a href="{{ route('destroyPenggunaanPanjar',$row->id ?? '') }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
								</div>
								@endif
							</td>
						</tr>
						@endforeach
						@else
						<tr>
							<td>1</td>
							<td colspan="3" class="text-center">No Data</td>
						</tr>
						@endif
					</tbody>
					<tfoot class="font-weight-bold">
						<tr>
							<td colspan="3" class="text-right">Total</td>
							<td class="text-right">{{ Rupiah($total)}}</td>
							<td></td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
@include('layouts.upload')

<meta name="csrf-token" content="{{ csrf_token() }}" />

<script type="text/javascript">
	$(document).ready(function() {

		// $("#formx :input").attr("readonly", true);
		// $("#formx :select").attr("readonly", true);
		// $('#formx input[type=checkbox]').attr('disabled', true)

		$('.form-check-input-styled').uniform();

		$('.pickadate').pickadate({
			format: 'dd/mm/YYYY'
		})

		$('[data-popup="lightbox"]').fancybox({
			padding: 3
		});

		$('#jenispanjar').on('change', function() {
			if (this.value == 0 || this.value == '') {
				$('#pilihproject').show();
				$('#jenispanjarx').val(0);
			} else {
				$('#pilihproject').hide();
				$('#jenispanjarx').val(1);
			}
		})

		var nilai = document.getElementById('nilai');
		nilai.addEventListener('keyup', function(e) {
			nilai.value = formatRupiah(this.value);
		});

		var url = '<?php echo url('/') ?>' + '/pemesan/show/' + $('#pemberi').val();
		$('#btn-edit-pemberi').attr('value', url);
		var url = '<?php echo url('/') ?>' + '/pemesan/show/' + $('#mengetahuipemberi').val();
		$('#btn-edit-mengetahui').attr('value', url);

		$('#pemberi').on('change', function() {
			var url = '<?php echo url('/') ?>' + '/pemesan/show/' + this.value;
			$('#btn-edit-pemberi').attr('value', url)
		})

		$('#mengetahuipemberi').on('change', function() {
			var url = '<?php echo url('/') ?>' + '/pemesan/show/' + this.value;
			$('#btn-edit-mengetahui').attr('value', url)
		})

		$('.btn_pembayaran').on('click', function() {
			$('#tabel_pembayaran').toggle();
		})

		var jenis = '<?php echo $panjar->jenis ?? '' ?>';
		if (jenis == 1) {
			$('#tabel_penggunaan').show();
		}

		$('#formx').on('submit', function(e) {

			$('#jenispanjarx').val($('#jenispanjar').val());
			var nilai = $('#nilai').val();
			var tanggal = $('#tanggal').val();
			if(!tanggal){
				alert('Perhatian. Tanggal Panjar mohon diisi.')
				e.preventDefault();
					return false;
			}
			if(!nilai){
				alert('Perhatian. Nilai Panjar mohon diisi.')
				e.preventDefault();
					return false;
			}
			var nilai_project = $('#nilaiproject').val();
			nilai= nilai.toString().replace(/[^,\d]/g, '').toString();
			if((nilai_project > 0) && (parseFloat(nilai) > parseFloat(nilai_project))){
				alert('Perhatian.  Nilai panjar tidak boleh melebihi nilai project.')
				e.preventDefault();
					return false;
			}
		})
	});

	function save_data() {
		var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
		var url = "<?php echo route('updatePemesan'); ?>";
		$.ajax({
			type: "POST",
			url: url,
			data: new FormData(document.getElementById("form1")),
			processData: false,
			contentType: false,
			success: function(data) {
				if (data['kode']) {
					$('#pemberi').append($('<option>', {
						value: data['id'],
						text: data['nama']
					}));
					$('#mengetahuipemberi').append($('<option>', {
						value: data['id'],
						text: data['nama']
					}));
					if (data['kode'] == 'pemberi') {

						$("#pemberi").val(data['id']).trigger("change");
					} else {
						$("#mengetahuipemberi").val(data['id']).trigger("change");

					}
				}
			}
		});
		$('#modalMd').modal('hide');
	}

	function upload(id) {
		$('#iditem').val(id);
		$('#jenisitem').val('panjar');
	}
</script>
@endsection