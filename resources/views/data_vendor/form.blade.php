<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>

<form method="POST" action="{{ route('updateVendor') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Nama Vendor</label>
		<div class="col-sm-8">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="text" required name="nama" placeholder="Nama Vendor" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
		</div>
	</div>
	 
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Nama Alias</label>
		<div class="col-sm-8">
			<input type="text"   name="alias" placeholder="Nama Alias Vendor" required class="form-control border-warning" value="{{ $data->alias ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Alamat</label>
		<div class="col-sm-8">
			<textarea name="alamat"   cols="5" placeholder="Alamat Lengkap" class="form-control">{{ $data->alamat ?? '' }}</textarea>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Kota</label>
		<div class="col-sm-5">
			<input type="text" name="kota"   placeholder="Kota" class="form-control" value="{{ $data->kota ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Email</label>
		<div class="col-sm-5">
			<input type="text" name="email"   placeholder="Email" class="form-control" value="{{ $data->email ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Kontak Person</label>
		<div class="col-sm-5">
			<input type="text" name="kontak"   placeholder="Nama Kontak Person" class="form-control" value="{{ $data->kontak ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Telepon</label>
		<div class="col-sm-5">
			<input type="text" name="phone"   placeholder="Telepon" class="form-control" value="{{ $data->phone ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">NPWP</label>
		<div class="col-sm-5">
			<input type="text" name="npwp"   placeholder="Npwp" class="form-control" value="{{ $data->npwp ?? '' }}">
		</div>
	</div>
	
	
	<div class="form-group row">
		<label class="col-form-label col-sm-3 text-info"><i class="icon-office"></i> BANK</label>
		 
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Nama Bank</label>
		<div class="col-sm-5">
			<input type="text" name="bank"   placeholder="" class="form-control" value="{{ $data->bank ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Kantor Cabang</label>
		<div class="col-sm-5">
			<input type="text" name="cabang"   placeholder="" class="form-control" value="{{ $data->cabang ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Atas Nama Rekening</label>
		<div class="col-sm-5">
			<input type="text" name="atasnama"   placeholder="" class="form-control" value="{{ $data->atasnama ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">No.Rekening</label>
		<div class="col-sm-5">
			<input type="text" name="norek"   placeholder="" class="form-control" value="{{ $data->norek ?? '' }}">
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>