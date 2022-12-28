<div class="form-group row">
	<label class="col-form-label col-sm-3">Nama Mitra/Perusahaan</label>
	<div class="col-sm-8">
		<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
		<input type="text" required name="nama" placeholder="Nama Perusahaan" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Unit Kerja</label>
	<div class="col-sm-8">
		<input type="text" name="unitkerja" placeholder="Nama Unit Kerja" required class="form-control border-warning" value="{{ $data->unitkerja ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Nama Alias</label>
	<div class="col-sm-8">
		<input type="text" required name="alias" placeholder="Nama Alias Perusahaan" class="form-control border-warning" value="{{ $data->alias ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Email</label>
	<div class="col-sm-8">
		<input type="text" name="email" placeholder="Email" class="form-control border-warning" value="{{ $data->email ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">No.Telepon</label>
	<div class="col-sm-8">
		<input type="text" name="phone" placeholder="Telepon" class="form-control" value="{{ $data->phone ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">NPWP</label>
	<div class="col-sm-8">
		<input type="text" name="npwp" placeholder="NPWP" class="form-control" value="{{ $data->npwp ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Alamat</label>
	<div class="col-sm-8">
		<textarea name="alamat" required cols="5" placeholder="Alamat Lengkap" class="form-control border-warning">{{ $data->alamat ?? '' }}</textarea>
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Kota</label>
	<div class="col-sm-3">
		<input type="text" name="kota" required placeholder="Kota" class="form-control border-warning" value="{{ $data->kota ?? '' }}">
	</div>
	<label class="col-form-label col-sm-2">Kode Pos</label>

	<div class="col-sm-3">
		<input type="text" name="kodepos" placeholder="kodepos" class="form-control" value="{{ $data->kodepos ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Status</label>
	<div class="col-sm-3">
		<select name="status" required data-placeholder="Pilih" class="select border-warning">
			<option value=""></option>
			<option value=1 <?php if (isset($data->status)) {
								if ($data->status == 1) echo 'selected';
							} ?>>Aktif</option>
			<option value=0 <?php if (isset($data->status)) {
								if ($data->status == 0) echo 'selected';
							} ?>>Tidak Aktif</option>
		</select>
	</div>
</div>

<div class="form-group row mb-5">
	<label class="col-form-label col-sm-3">Akun Piutang</label>
	<div class="col-sm-8">
		<select id="akun" name="akun" class="select-search" required data-placeholder="Pilih">
			<option value=""></option>
			@foreach($akun as $r)
			<option value="{{$r->id}}" <?php if (($data->akunid ?? '') == $r->id) echo 'selected';
										?>>{{'('.$r->kode . ') - ' .$r->nama}}</option>
			@endforeach
		</select>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.select').select2({
			minimumResultsForSearch: Infinity
		});
		$('.select-search').select2();
		$("#akun").select2({
			dropdownParent: $("#modalMd")
		})
	})
</script>