@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<script src="{{ url('/')}}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>

@if ($kodeubah!=1 && $kodeubah!=3)
<link href="{{ url('/') }}/assets/js/mycss.css" rel="stylesheet" type="text/css">

<script>
	$(document).ready(function() {

		$("form :input").attr('readonly', true);
	})
</script>
@endif


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
               		 <a class="dropdown-item" href="{{ action('BiayaCont@create') }}" title="Input Biaya">+ Biaya Baru</a>

					<a href='#' value="{{ route('showJurnalBiaya', $data->id ?? 0) }}" class="dropdown-item modalMd" title="Jurnal" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> Lihat Jurnal</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12">
				<form method="POST" action="{{ route('updateBiaya') }}">
					<fieldset>

						@csrf
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Nomor</label>
							<div class="col-sm-2">
								<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
								<input type="text" name="nomor" class="form-control" placeholder="(Auto)" value="{{ $data->nomor ?? old('nomor') }}">
							</div>

						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Akun Pembayaran</label>
							<div class="col-sm-5">
								<select name="akunid" class="select-search" required data-placeholder="Pilih">
									<option value=""></option>
									@foreach($akun as $r)
									@if(substr($r->kode,0,2)=='10')
									<?php $disabledx = substr($r->kode, 3, 4) == '0000' || substr($r->kode, 4, 3) == '000' ? "disabled='disabled'" : ''; ?>
									<option <?php echo $disabledx ?> value="{{$r->id}}" <?php if (($data->akunbayarid ?? old('akunid')) == $r->id) echo 'selected';
																						?>>{{'('.$r->kode . ') - ' .$r->nama}}</option>
									@endif
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Cara Pembayaran</label>
							<div class="col-sm-5">
								<select name="cara" id="cara" required data-placeholder="Pilih" class="select">
									<option value=""></option>
									<option value="Transfer Bank" <?php if (isset($data->carabayar)) {
																		if ($data->carabayar == 'Transfer Bank') echo 'selected';
																	} ?>>Transfer Bank</option>
									<option value="Kas Tunai" <?php if (isset($data->carabayar)) {
																	if ($data->carabayar == 'Kas Tunai') echo 'selected';
																} ?>>Kas Tunai</option>
									<option value="Cek & Giro" <?php if (isset($data->carabayar)) {
																	if ($data->carabayar == 'Cek & Giro') echo 'selected';
																} ?>>Cek & Giro</option>
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Project (Optional)</label>
							<div class="col-sm-5">
								<select name="project" class="select-search" data-placeholder="Pilih">
									<option value=0>== NON PROJECT ==</option>
									@foreach($project as $r)
									<option value="{{$r->id}}" <?php if (($data->projectid ?? old('vendor')) == $r->id) echo 'selected';
																?>>{{$r->nama}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Tanggal Transaksi</label>
							<div class="col-sm-5">
								<input type="text" required name="tanggal" placeholder="" class="form-control pickadate" value="{{ IndoTgl($data->tanggal ?? old('tanggal')) }}">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Catatan</label>
							<div class="col-sm-5">
								<textarea name="catatan" placeholder="" class="form-control">{{ $data->catatan ?? old('catatan') }}</textarea>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Pajak</label>
							<div>
								<input type="checkbox" class="form-check-input-styled" name="ck_pajak" id="ck_pajak">

							</div>
							<label class="col-form-label col-sm-2">Harga Termasuk Pajak</label>

						</div>
						<style>
							.disable {
								pointer-events: none;
								cursor: default;
							}
						</style>

						@if ($kodeubah==1 || $kodeubah==3)
						<div hidden class="form-group row">
							<div class="col-sm-10">
								<input type="text" id="kolom" class="form-control tokenfield" placeholder="+ Kolom" value="No,Akun Biaya,Deskripsi,Jumlah" data-fouc>

							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label"></label>
							<div class="list-icon col-sm-5 mt-3">
								<button hidden type="button" id="btn-tabel" class="btn btn-outline-info btn-sm">Buat Tabel</button>

							</div>
						</div>
						@endif
						<style>
							th,
							td {
								overflow: auto;
							}

							td input,
							select {
								overflow: auto;
							}

							th div {
								resize: horizontal;
								overflow: auto;
								border: 0;
							}

							.select2-search--dropdown {
								padding-bottom: .25rem;
								padding-top: .25rem;
							}

							.uniform-checker {
								margin-top: 7px;
							}
						</style>

						<div class="form-group row">
							<div id="tabel-item" class="ml-2">
								@if (isset($detail) && count($detail) > 0)
								<table id="tabel-item" class="table" width='100%'>
									<thead>
										<tr>
											<th>No.</th>
											<th width="350px">Akun Biaya</th>
											<th width="250px">Deskripsi</th>
											<th class="text-right">Jumlah</th>
											<th width="150px" class="text-center">Pajak</th>
											<th>#</th>
										</tr>
									</thead>
									<tbody id="bbody">
										<?php
										$no = 1;
										$iid = '';
										$subtotal = $jumlahppn = 0;

										foreach ($detail as $row) {
											$pid = $row->biayaid;
											$subtotal += $row->nilai;
											$jumlahppn += $row->pajakid == 1 ? $row->pajak : 0;
											$nilaiitem = $data->kodepajak == 'on' ? $row->nilai + $row->pajak : $row->nilai;
											$iid .= $row->id . ',';
											echo '<tr id="tr' . $row->id . '"><td class="text-center" width="50px">' . $no . '.</td>';
											$optionstr = '';
											foreach ($akun as $p) {
												$akunid = $row->akunid ?? 0;
												$selected = ($akunid > 0) && ($akunid == $p->id) ? 'selected' : '';
												$optionstr .= "<option value='" . $p->id . "' " . $selected . " >(" . $p->kode . ") - " . $p->nama . " </option>";
											}
											echo '<td><div><select class="select-search" name="akun' . $no . '"><option value="0"> - </option>' . $optionstr . "</select><div></td>";
											echo '<td ><div><input type="text" name="tx2_' . $no . '"  class="form-control" value="' . $row->catatan . '"></div></td>';
											echo '<td ><div><input type="text" name="tx3_' . $no . '"  class="form-control text-right" value="' . Rupiah($nilaiitem, 2) . '"></div></td>';

											$no++;
											$optionstr = '';
											foreach ($pajak as $p) {
												$pajakid = $row->pajakid ?? 0;
												$selected = ($pajakid > 0) && ($pajakid == $p->id) ? 'selected' : '';
												$optionstr .= "<option value='" . $p->id . "' " . $selected . " >" . $p->nama . " (" . $p->nilai . "%)</option>";
											}
											echo '<td><div><select class="select" name="pajak' . ($no - 1) . '"><option value="0"> - </option>' . $optionstr . "</select><div></td>";

										?>
											@if ($kodeubah==1 || $kodeubah==3)

											<td><a href="javascript:hapusakun(<?= @$row->id ?>)" class="list-icons-item text-danger-600" title="Hapus Data"><i class="icon-bin"></i></a></td>
											@endif
											</tr>
										<?php
										}
										$no--;
										?>
									</tbody>
									@if ($kodeubah==2)
									<tfoot class="font-weight-bold">
										<tr>
											<td colspan="3" class="text-right">SUB TOTAL</td>
											<td class="text-right">{{Rupiah($subtotal,2)}}</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="3" class="text-right">PAJAK</td>
											<td class="text-right">{{Rupiah($jumlahppn,2)}}</td>
											<td></td>
											<td></td>

										</tr>
										<tr>
											<td colspan="3" class="text-right" style="font-size: medium;">TOTAL</td>
											<td class="text-right" style="font-size: medium;">{{Rupiah($jumlahppn + $subtotal,2)}}</td>
											<td></td>
											<td></td>
										</tr>
									</tfoot>
									@endif
								</table>
								@endif
							</div>


						</div>

						<input type="hidden" name="txkolomjumlah" id="txkolomjumlah" value="6">
						<input type="hidden" name="txkolom" id="txkolom" value="{{ ($i ?? 0) + 1}}">
						<input type="hidden" name="txbaris" id="txbaris" value="{{ $no ?? ''}}">
						<input type="hidden" name="txprojectid" id="txprojectid" value="{{ $pid ?? '' }}">
						<input type="hidden" name="txnamakolom" id="txnamakolom" value="No,Akun Biaya,Deskripsi,Jumlah">

						@if ($kodeubah==1 || $kodeubah==3)
						<button type="button" id="btn-additem" class="btn btn-outline-info btn-sm">+ Baris Baru</button>
						@endif
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
								@endif
								@if ($message = Session::get('warning'))
								<div class="alert alert-danger alert-block">
									<button type="button" class="close" data-dismiss="alert">×</button>
									<strong>{{ $message }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row mt-1">
							<div class="col-sm-11">

								<div class="list-icons" style="float: right;">
									<a href="{{ action('BiayaCont@destroy',['id'=>$data->id ?? 0]) }}" onclick="return confirm('Anda yakin ingin menghapus data Biaya ini ?')" class="btn btn-outline-warning btn-sm"  style="margin-right: 50px;">Hapus</a>

									<a href="{{ $kodeubah==1 ? 'javascript:window.history.go(-1);' : route('biaya') }}" class="btn btn-outline-success btn-sm" title="Batal / Kembali">{{ $kodeubah==3 ? 'BATAL' : 'KEMBALI' }}</a>

									@if($kodeubah==1 || $kodeubah==3)
									<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
									@else
									<a href="{{ route('editBiaya',$data->id ?? '') }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
									@endif
								</div>
							</div>
						</div>
					</fieldset>

				</form>
			</div>

		</div>

	</div>

	@if($kodeubah != 1)

		@if ($kodeubah==2)
		<style>
			.tombol{
				display: none;
			}
		</style>
		@endif
	<div class="card-body pt-0">
		<hr>
		<h6 class="card-title">Lampiran</h6>
		<div class="form-group row">
			<div class="col-sm-12">
				<div class="form-group row tombol">
					<div class="col-sm-1">
						<a style="line-height: 0.3;" class="btn btn-outline-info btn-sm" href="#" data-toggle="modal" data-target="#modalFile" onclick="upload()">
							<i class="icon-file-upload" style="font-size: small;">Upload</i> </a>
					</div>
				</div>
				@if(isset($lampiran))
				@foreach($lampiran as $la)
				<div class="form-group row mb-0">
					<div class="col-sm-10">
						<a target="_blank" class="text-teal-800" href="{{ url('/').'/assets/lampiran/'.$la->file }}" data-popup="lightbox">
							{{ $la->file ?? '' }}</a>

						<a title="hapus lampiran" onclick="return confirm('Anda yakin ingin menghapus file lampiran ini ?')" href="{{ route('destroyLampiran',$la->id)}}" class="btn btn-sm text-danger tombol"><i class="icon-bin" style="font-size: small;"></i></a>
					</div>
				</div>
				@endforeach
				@else
				<div class="form-group row">
					<label class="col-form-label col-sm-2">Lampiran</label>
					<div class="col-sm-4">
						<input type="text" readonly name="lampiran" placeholder="File (jpg, png, pdf, docx, xlsx) max:2MB " required class="form-control border-warning">
					</div>

				</div>

				@endif
			</div>
		</div>
	</div>
	@endif
</div>
<!-- /basic datatable -->

<script src="{{ url('/') }}/global_assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		// $("#iform :input").attr('readonly',true);
		$('.form-check-input-styled').uniform();

		var projectid = '<?php echo $data->id ?? 0 ?>';
		$('.tokenfield').tokenfield();

		var ck = "{{ $data->kodepajak ?? '' }}";
		if (ck) {
			$('#ck_pajak').prop('checked', true);
		}

		var kodeubah = '<?php echo $kodeubah ?>';
		if (kodeubah == 1) {

			var kolomstr = $('#kolom').val();
			var kolomjumlah = $('#kolomjumlah').val();
			var kolomarr = kolomstr.split(',');
			var kolom = "";
			var content = "<table border='1' cellpadding='5px' width='100%'><thead class='text-center'><tr>"
			for (i = 0; i < kolomarr.length; i++) {
				if (i == 1) {
					content += '<th width = "350px">' + kolomarr[i] + '</th>';
				} else if (i == 2) {
					content += '<th width = "250px">' + kolomarr[i] + '</th>';
				} else {
					content += '<th>' + kolomarr[i] + '</th>';
				}

			}
			content += "<th>Pajak</th>"
			content += "</tr></thead><tbody id='bbody'></tbody></table>"

			kolomstr += ",Pajak";

			$('#tabel-item').html('');
			$('#tabel-item').append(content);
			$('#txnamakolom').val(kolomstr);
			$('#txkolomjumlah').val(kolomjumlah);
			$('#txprojectid').val('<?php echo $data->id ?? 0 ?>');
			$('#btn-simpanitem').show();
			$('#btn-additem').trigger('click');
		}

		$('input').change(
			function() {
				var id = $(this).attr('name');
				console.log(id);
				if (id) {
					var idx = id.split('_');
				}
				if (idx[0] == 'tx2' || idx[0] == 'tx4') {
					var hargax = 'tx4_' + idx[1];
					var qtyx = 'tx2_' + idx[1];

					var qty = $('input[name="' + qtyx + '"]').val();
					var harga = $('input[name="' + hargax + '"]').val();
					var jumlah = 0;
					if (harga > 0) {
						jumlah = qty * harga;
					}
					namex = 'tx5_' + idx[1];
					$('input[name="' + namex + '"]').val(formatRupiah(jumlah));
					// console.log(this.value);

				}
			});

	})

	var namakol = '<?php echo str_replace(',Pajak', '', $namakol ?? '') ?>'
	if (namakol != '') {
		$('#kolom').val('<?php echo str_replace(',Pajak', '', $namakol ?? '') ?>');
	}

	$('#btn-additem').on('click', function() {
		var kolomstr = $('#kolom').val();
		var kolomarr = kolomstr.split(',');
		var kolom = '';
		var rowCount = $('#bbody tr').length + 1;

		var akun = '<?php echo  $akun ?? '' ?>';
		akun = JSON.parse(akun);
		var optionstr = '';

		// var aku = '12.3456';
		// alert(aku.substring(3,7));

		for (var i = 0; i < akun.length; i++) {
			var disabledx = akun[i]['kode'].substring(3, 7) == '0000' ? "disabled='disabled'" : "";
			optionstr += "<option " + disabledx + " value=" + akun[i]['id'] + ">(" + akun[i]['kode'] + ") - " + akun[i]['nama'] + "</option>";
		}

		kolom += '<td class="text-center" width="50px">' + rowCount + '</td>';
		kolom += '<td><select name="akun' + rowCount + '" class="akun" ><option value="0" selected> - </option>' + optionstr + "</select></td>";
		kolom += '<td><input type="text" name="tx2_' + rowCount + '" class="form-control"></td>';
		kolom += '<td><input type="text" name="tx3_' + rowCount + '" class="form-control text-right"></td>';

		//pajak
		var optionstr = '';
		var pajak = '<?php echo  $pajak ?? '' ?>';
		pajak = JSON.parse(pajak);
		for (var i = 0; i < pajak.length; i++) {
			optionstr += "<option value=" + pajak[i]['id'] + ">" + pajak[i]['nama'] + " (" + pajak[i]['nilai'] + "%)</option>";
		}
		kolom += '<td width = "150px"><select name="pajak' + rowCount + '" class="pph" ><option value="0" selected> - </option>' + optionstr + "</select></td>";

		$('#bbody').append('<tr>' + kolom + '</tr>');
		$('#txkolom').val(kolomarr.length + 1);
		$('#txbaris').val(rowCount);

		$(".akun").addClass('select-search');
		$(".pph").addClass('select');
		$('.select-search').select2();
		$('.select').select2({
			minimumResultsForSearch: Infinity
		})

	})

	function hapusakun(id) {
		$('#tr' + id).remove();
		var jml = $('#txbaris').val();
		if (jml > 0) {
			$('#txbaris').val(jml - 1);
		}
	}

	function upload() {
		var id = $("input[name='id']").val();
		$('#iditem').val(id);
		$('#jenisitem').val('biaya');
		if (!id) {

			alert('Perhatian. Data harus diisi dan Disimpan terlebh dahulu sebelum meng-upload lampiran !.');
		}
	}
</script>
@include('layouts.upload')

@endsection