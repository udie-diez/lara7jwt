<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>

<form method="POST" action="" id="form1">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Nama Lengkap</label>
		<div class="col-sm-9">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="hidden" name="kode" value="{{ $kode ?? '' }}">
			<input type="text" required name="nama" placeholder="Nama Lengkap" class="form-control border-warning" value="{{ $data->nama ?? '' }}">
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
		<label class="col-form-label col-sm-2">No.HP</label>
		<div class="col-sm-5">
			<input type="text" name="telepon" placeholder="0811..." class="form-control" value="{{ $data->telepon ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">JABATAN</label>
		<div class="col-sm-5">
			<input type="text" name="jabatan"   placeholder="Jabatan" class="form-control border-warning" value="{{ $data->jabatan ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">LOKASI KERJA</label>
		<div class="col-sm-9">
			<input type="text" name="lokasi"   placeholder="Lokasi Kerja" class="form-control border-warning" value="{{ $data->lokasikerja ?? '' }}">
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" onclick="save_data()" class="btn btn-outline-info btn-sm">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form> 