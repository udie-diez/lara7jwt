@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<div class="card">
	<div class="card-header  header-elements-inline">
		<h5 class="card-title">{{$tag['judul']}}</h5>
		<div class="header-elements">
			<div class="list-icons" >
				<a href="#" id="btn-ubah" class="btn btn-outline-info btn-sm" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i> Ubah Data</a>

			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-2">
				<div class="form-group row">
					<div class="col-sm-12">
						<a class="btn btn-outline-info btn-sm" href="#" data-toggle="modal" data-target="#modalFile" onclick="upload()">
						 
						@if(file_exists( public_path().'/assets/photo/'. substr(Auth::user()->email,0,6) . '.jpg' ))
							<img src="{{ url('/').'/assets/photo/'. substr(Auth::user()->email,0,6). '.jpg?v='. rand(1,32000) }}"  width="120" height="120" alt="">
						@else
							<img src="{{ url('/') }}/assets/images/nopic.jpg"  width="120" height="120" alt="">
						@endif
						
						</a>
						
					</div>
				</div>
			</div>
			<div class="col-sm-8">
				<div class="form-group row">
					<label class="col-form-label col-sm-3">No.Anggota</label>
					<div class="col-sm-7">
						<input type="hidden"  id="url" value="{{ url('anggota/').'/'.$data->id }}">
						<input type="text" disabled name="nomor" placeholder="(Auto)" required class="form-control  border-warning" value="{{ $data->nomor ?? '' }}">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Nama Lengkap</label>
					<div class="col-sm-7">
						<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
						<input type="text" readonly name="nama"    class="form-control border-warning" value="{{ $data->nama ?? '' }}">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">NIK</label>
					<div class="col-sm-7">
						<input type="text" id="nik" name="nik" readonly   class="form-control" value="{{ $data->nik ?? '' }}">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Email</label>
					<div class="col-sm-7">
						<input type="text" readonly name="email"  class="form-control" value="{{ Auth::user()->email }}">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Phone</label>
					<div class="col-sm-7">
						<input type="text" name="phone" readonly class="form-control" value="{{ $data->phone ?? '' }}">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Jns.Kelamin</label>
					<div class="col-sm-7">
						<select name="jk" disabled data-placeholder="Pilih Jenis Kelamin" class="select border-warning">
							<option value=""></option>
							<option value="L" <?php if (isset($data->jk)) {
													if ($data->jk == 'L') echo 'selected';
												} ?>>Laki-laki</option>
							<option value="P" <?php if (isset($data->jk)) {
													if ($data->jk == 'P') echo 'selected';
												} ?>>Perempuan</option>
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Alamat</label>
					<div class="col-sm-7">
						<textarea name="alamat" readonly   class="form-control border-warning">{{ $data->alamat ?? '' }}</textarea>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Kota</label>
					<div class="col-sm-7">
						<select name="kota"  disabled id="kota"   class="select-search border-warning">
							<option value=""></option>
							@foreach($kota as $r)
							<option value="{{$r->nama}}" <?php if (isset($data->kota)) {
																if ($data->kota == $r->nama) echo 'selected';
															} ?>>{{$r->nama}}</option>
							@endforeach
						</select>
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Tempat/Tgl.Lahir<sup class="text-danger"></sup></label>
					<div class="col-sm-3">
						<input type="text" name="tempatlahir" readonly class="form-control" value="{{ $data->tempat_lahir ?? '' }}">
					</div>
					<div class="col-sm-5">
						<input type="text" name="tgllahir" readonly class="form-control pickadate-year" value="<?php if (isset($data->tgl_lahir)) echo date('d/m/Y', strtotime($data->tgl_lahir)) ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Tgl.Daftar Anggota<sup class="text-danger"></sup></label>
					<div class="col-sm-7">
						<input type="text" name="tgldaftar" readonly class="form-control pickadate-year" value="<?php if (isset($data->tgl_daftar)) echo date('d/m/Y', strtotime($data->tgl_daftar)) ?>">
					</div>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-3">Status Keanggotaan</label>
					<div class="col-sm-3">
						<select name="status" disabled data-placeholder="Pilih" class="select">
							<option value=""></option>
							<option value=1 <?php if (isset($data->status)) {
												if ($data->status == 1) echo 'selected';
											} ?>>Aktif</option>
							<option value=0 <?php if (isset($data->status)) {
												if ($data->status == 0) echo 'selected';
											} ?>>Tidak Aktif</option>
							<option value=0 <?php if (isset($data->status)) {
												if ($data->status == 0) echo 'selected';
											} ?>></option>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- /basic datatable -->


<script type="text/javascript">
	$(document).ready(function() {
		$('.select').select2({
			minimumResultsForSearch: Infinity
		});
		$('.select-search').select2();
		$("#kota").select2({
			dropdownParent: $("#modalMd")
		})

		$('.pickadate-year').pickadate({
			format: 'dd/mm/yyyy',
			selectYears: 60,
			selectMonths: 12,
			max: true
		});
	})

	function upload(){
		var id = $('#nik').val();
		$('#iditem').val(id);
	}

</script>

@endsection
@include('layouts.upload')