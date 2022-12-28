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

			<div class="btn-group">
				<button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">Tindakan</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a  href="{{ route('createJurnalumum', $data->id ?? 0) }}" class="dropdown-item" title="+ Jurnal Baru"  > + Jurnal Baru</a>
					<a href='#' value="{{ route('showJurnalJurnalumum', $data->id ?? 0) }}" class="dropdown-item modalMd" title="Jurnal" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> Lihat Jurnal</a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12">
				<form method="POST" action="{{ route('updateJurnalumum') }}">
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
							<label class="col-form-label col-sm-2">Tanggal Transaksi</label>
							<div class="col-sm-5">
								<input type="text" required name="tanggal" placeholder="" class="form-control pickadate" value="{{ IndoTgl($data->tanggal ?? date('Y/m/d')) }}">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Keterangan</label>
							<div class="col-sm-5">
								<textarea name="catatan" placeholder="" class="form-control nilai">{{ $data->catatan ?? old('catatan') }}</textarea>
							</div>
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
								<input type="text" id="kolom" class="form-control tokenfield" placeholder="+ Kolom" value="No,Akun,Keterangan,Debit,Kredit" data-fouc>

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
								<table id="tabel-item" class="table" width='100%'>
									<thead>
										<tr>
											<th hidden>No.</th>
											<th width="350px">Akun</th>
											<th width="250px">Deskripsi</th>
											<th class="text-right">Debit</th>
											<th class="text-right">Kredit</th>
											<th>#</th>
										</tr>
									</thead>
									<tbody id="bbody">
										@if (isset($detail) && count($detail) > 0)

										<?php
										$no = 1;
										$iid = '';
										$subtotaldebit = $subtotalkredit = $jumlahppn = $debit = $kredit = 0;
										foreach ($detail as $row) {
											$pid = $row->biayaid;
											$subtotaldebit += $row->debit;
											$subtotalkredit += $row->kredit;
											$jumlahppn += $row->pajakid == 1 ? $row->pajak : 0;
											$debit = $row->debit;
											$kredit = $row->kredit;
											$iid .= $row->id . ',';
											echo '<tr id="tr' . $row->id . '"><td hidden class="text-center" width="50px">' . $no . '.</td>';
											$optionstr = '';
											foreach ($akun as $p) {
												$akunid = $row->akunid ?? 0;
												$selected = ($akunid > 0) && ($akunid == $p->id) ? 'selected' : '';
												$optionstr .= "<option value='" . $p->id . "' " . $selected . " >(" . $p->kode . ") - " . $p->nama . " </option>";
											}
											echo '<td><div><select class="select-search" name="akun' . $no . '"><option value="0"> - </option>' . $optionstr . "</select><div></td>";
											echo '<td ><div><input type="text" name="catatan_' . $no . '"  class="form-control" value="' . $row->catatan . '"></div></td>';
											echo '<td ><div><input type="text" name="debit_' . $no . '" id="debit_' . $no . '" class="form-control text-right nilai debit" value="' . Rupiah($debit, 2) . '"></div></td>';
											echo '<td ><div><input type="text" name="kredit_' . $no . '" id="kredit_' . $no . '" class="form-control text-right nilai kredit" value="' . Rupiah($kredit, 2) . '"></div></td>';

											$no++;
											$optionakunstr = '';
											foreach ($pajak as $p) {
												$pajakid = $row->pajakid ?? 0;
												$selected = ($pajakid > 0) && ($pajakid == $p->id) ? 'selected' : '';
												$optionakunstr .= "<option value='" . $p->id . "' " . $selected . " >" . $p->nama . " (" . $p->nilai . "%)</option>";
											}
											echo '<td hidden><div><select class="select" name="pajak' . ($no - 1) . '"><option value="0"> - </option>' . $optionakunstr . "</select><div></td>";

										?>
											@if ($kodeubah==1 || $kodeubah==3)

											<td><a href="javascript:hapusakun(<?= @$no-1 ?>)" class="list-icons-item text-danger-600" title="Hapus Data"><i class="icon-bin"></i></a></td>
											@endif
											</tr>
										<?php
										}
										$no--;

										for ($no = $no+1; $no <= 5; $no++) {
										?>

										<tr>
											<td hidden>{{$no}}.</td>
											<td><div><select class="select-search" name="{{ 'akun'.$no }}">
														<option value="0"> - </option><?= @ str_replace('selected','',$optionstr) ?>
													</select><div></td>
											<td><div><input type="text" name="{{ 'catatan_' . $no}}" id="{{ 'catatan_' . $no}}" class="form-control"></div></td>
											<td><div><input type="text" name="{{'debit_' . $no}}" id="{{ 'debit_' . $no}}" class="form-control text-right nilai debit"></div></td>
											<td><div><input type="text" name="{{'kredit_' . $no}}" id="{{ 'kredit_' . $no}}" class="form-control text-right nilai kredit"></div></td>
										</tr>
										<?php }
										 ?>
										@else
										<?php
										$subtotaldebit = $subtotalkredit = $jumlahppn = $debit = $kredit = 0;

										$optionstr = '';
										foreach ($akun as $p) {
											$optionstr .= "<option value='" . $p->id . "' >(" . $p->kode . ") - " . $p->nama . " </option>";
										}
										for ($no = 1; $no <= 5; $no++) {
											# code...
										?>
											<tr>
												<td hidden>{{$no}}.</td>

												<td>
													<div><select class="select-search" name="{{ 'akun'.$no }}">
															<option value="0"> - </option><?= @$optionstr ?>
														</select>
														<div>
												</td>
												<td>
													<div><input type="text" name="{{ 'catatan_' . $no}}" id="{{ 'catatan_' . $no}}" class="form-control"></div>
												</td>
												<td>
													<div><input type="text" name="{{'debit_' . $no}}" id="{{ 'debit_' . $no}}" class="form-control text-right nilai debit"></div>
												</td>
												<td>
													<div><input type="text" name="{{'kredit_' . $no}}" id="{{ 'kredit_' . $no}}" class="form-control text-right nilai kredit"></div>
												</td>
											</tr>
										<?php } ?>
										@endif
									</tbody>

									<tfoot class="font-weight-bold">
										<tr>
											<td colspan="2" class="text-right">Jumlah</td>
											<td class="text-right" id="totdebit">{{Rupiah($subtotaldebit ?? 0,2)}}</td>
											<td class="text-right" id="totkredit">{{Rupiah($subtotalkredit ?? 0,2)}}</td>
										</tr>
									</tfoot>
								</table>
							</div>

						</div>

						<input type="hidden" name="txkolomjumlah" id="txkolomjumlah" value="5">
						<input type="hidden" name="txkolom" id="txkolom" value="{{ ($i ?? 0) + 1}}">
						<input type="hidden" name="txbaris" id="txbaris" value="{{ $no ?? ''}}">
						<input type="hidden" name="txprojectid" id="txprojectid" value="{{ $pid ?? '' }}">
						<input type="hidden" name="txnamakolom" id="txnamakolom" value="No,Akun,Keterangan,Debit,Kredit">

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

						<div class="form-group row mt-3">
							<div class="col-sm-11">
								<div class="text-left">
								</div>

								<div class="list-icons" style="float: right;">
									<a href="{{ action('JurnalUmumCont@destroy',['id'=>$data->id ?? 0]) }}" onclick="return confirm('Anda yakin ingin menghapus data Jurnal ini ?')" class="btn btn-outline-warning btn-sm"  style="margin-right: 50px;">Hapus</a>
									<a href="{{ $kodeubah==1 ? route('showJurnalumum',$data->id ?? '' ) : 'javascript:window.history.go(-1)' }}" class="btn btn-outline-success btn-sm" title="Batal / Kembali">{{ $kodeubah==3 ? 'BATAL' : 'KEMBALI' }}</a>

									@if($kodeubah==1 || $kodeubah==3)
									<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
									@else
									<a href="{{ route('editJurnalumum',$data->id ?? '') }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
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

		$('.tokenfield').tokenfield();


		$('form').on('submit', function(e) {
			var kredit = $('#totkredit').text();
			var debit = $('#totdebit').text();

			if (kredit != debit) {
				alert('Perhatian. Jumlah Debit dan Kredit tidak sama (Tidak Seimbang), silahkan sesuaikan terlabih dahulu .');
				e.preventDefault();
				return false;
			}
		})

		$('.nilai').change(function() {
			var id = $(this).attr('id');
			$('#' + id).val(formatRupiah(this.value));
			var tot = 0;
			var nilai = 0;

			$('.debit').each(function() {

				nilai = this.value ? this.value : 0;

				nilai = nilai.toString().replace(/[^,\d]/g, '').toString();
				nilai = nilai.replace(',', '.');
				tot = +parseFloat(nilai).toFixed(2) + +parseFloat(tot).toFixed(2);
			});
			tot = parseFloat(tot).toFixed(2).toString().replace('.', ',');
			$('#totdebit').text(formatRupiah(tot));

			var tot = 0;
			var nilai = 0;

			$('.kredit').each(function() {

				nilai = this.value ? this.value : 0;

				nilai = nilai.toString().replace(/[^,\d]/g, '').toString();
				nilai = nilai.replace(',', '.');
				tot = +parseFloat(nilai).toFixed(2) + +parseFloat(tot).toFixed(2);
			});
			tot = parseFloat(tot).toFixed(2).toString().replace('.', ',');
			$('#totkredit').text(formatRupiah(tot));

		});

	})

	function hapusakun(id) {
		$("select[name='akun"+id+"']").val(0).trigger('change');
		$("input[name='catatan_"+id+"']").val('');
		$("input[name='debit_"+id+"']").val('');
		$("input[name='debit_"+id+"']").trigger('change');
		$("input[name='kredit_"+id+"']").val('');
		$("input[name='kredit_"+id+"']").trigger('change');
		 
	}
	
	function upload() {
		var id = $("input[name='id']").val();
		$('#iditem').val(id);
		$('#jenisitem').val('jurnal');
		if (!id) {

			alert('Perhatian. Data harus diisi dan Disimpan terlebh dahulu sebelum meng-upload lampiran !.');
		}
	}
</script>
@include('layouts.upload')

@endsection