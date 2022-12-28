@include('layouts.mylib')
<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>

<form method="POST" action="{{ route('updateTarget') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Nama Pegawai</label>
		<div class="col-sm-5">
			<input type="hidden" name="pengelola"   value="{{ $data->pengelolaid ?? '' }}">
			<input type="hidden" name="id"   value="{{ $data->id ?? '' }}">
			<input type="text" name="nama" readonly  placeholder="" class="form-control" value="{{ $data->nama ?? '' }}">
		</div>
	</div>  
	<div class="form-group row">
		<label class="col-form-label col-sm-3">JUMLAH TARGET (Rp)</label>
		<div class="col-sm-2">
			<input type="text" name="nilai" id="nilai" required placeholder="" class="form-control text-right" value="{{ Rupiah($data->nilai ?? '') }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">TAHUN</label>
		<div class="col-sm-1">
			<input type="text" name="tahun" placeholder="" class="form-control" value="{{ $data->tahun ?? date('Y') }}">
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('.select').select2({
			minimumResultsForSearch: Infinity
		});
		$('.select-search').select2();
	  
		var nilai = document.getElementById('nilai');
		nilai.addEventListener('keyup', function(e) {
			nilai.value = formatRupiah(this.value);
		});
	})
</script>