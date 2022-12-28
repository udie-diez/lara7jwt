@include('layouts.mylib')

<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>

<form method="POST" action="{{ route('updateAkun') }}">
	@csrf

	<div class="form-group row">
		<label class="col-form-label col-sm-2">Kode Akun</label>
		<div class="col-sm-4">
			<input type="text" required name="kode" placeholder="##.####" class="form-control border-warning" value="{{ $data->kode ?? '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-form-label col-sm-2">Nama Akun</label>
		<div class="col-sm-8">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="text" required name="nama" placeholder=""   class="form-control border-warning" value="{{ $data->nama ?? '' }}">
		</div>
	</div>
	 
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Jenis</label>
		<div class="col-sm-4">
		<select name="jenis" class="select" data-placeholder="Pilih">
			<option value=""></option>
			<option value="0" selected>Header</option>
			<option value="1" <?php if(isset($data->jenis)) if($data->jenis == 1) echo 'selected' ?>>Detail</option>

		</select>
	</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Keterangan</label>
		<div class="col-sm-8">
			<textarea name="deskripsi"   cols="3" placeholder="" class="form-control">{{ $data->deskripsi ?? '' }}</textarea>
		</div>
	</div>
   
	<div class="modal-footer mt-3">
		<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
	  


		$('.select').select2({
            minimumResultsForSearch: Infinity
        });
	})
</script>
 