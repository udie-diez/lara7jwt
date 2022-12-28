<script src="{{ url('/') }}/assets/js/myjs_m.js?"></script>
<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>

<form method="POST" action="{{ route('updatePemesan') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Nama Lengkap</label>
		<div class="col-sm-9">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="text" required name="nama" placeholder="Nama Lengkap" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">NIK</label>
		<div class="col-sm-5">
			<input type="text" name="nik" placeholder="NIK" class="form-control" value="{{ $data->nik ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">E-mail</label>
		<div class="col-sm-5">
			<input type="text" name="email" placeholder="Email" class="form-control" value="{{ $data->email ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">No.Telepon</label>
		<div class="col-sm-5">
			<input type="text" name="telepon" placeholder="No.Telp/Hp" class="form-control" value="{{ $data->telepon ?? '' }}">
		</div>
	</div> 
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Jabatan</label>
		<div class="col-sm-5">
			<input type="text" required name="jabatan" placeholder="Jabatan" class="form-control border-warning" value="{{ $data->jabatan ?? '' }}">
		</div>
	</div> 

	<div class="form-group row">
		<label class="col-form-label col-sm-2">Lokasi Kerja</label>
		<div class="col-sm-5">
			<input type="text"   name="lokasi" placeholder="Lokasi Kerja" class="form-control border-warning" value="{{ $data->lokasikerja ?? '' }}">
		</div>
	</div> 
	<div class="form-group row">
	<label class="col-form-label col-sm-2">Status </label>
	<div class="col-sm-5">
		<select name="status" required data-placeholder="Pilih" class="select border-warning">
			<option value=""></option>
			<option value=1 <?php if(isset($data->status)){if($data->status==1) echo 'selected';} ?>>Aktif</option>
			<option value=0 <?php if(isset($data->status)){if($data->status==0) echo 'selected';} ?>>Tidak Aktif</option>
		</select>
	</div>
</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form> 