@include('layouts.mylib')
<style>
	.modal-content {
		float: right;
		min-width: 800px;
		min-height: 500px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$('.pickadate').pickadate({
			format: 'dd/mm/yyyy',
			close: false, 
			today: false,
			clear: false,
			selectMonths: true,
			closeOnSelect: true,
//   selectYears: true
		});

	})
</script>
<form method="POST" action="{{ route('updateRekapPajak') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Nomor Bukti Potong</label>
		<div class="col-sm-8">
			<input type="text"  name="nomor" class="form-control border-warning" value="{{ $data->nomor ?? '' }}">
			<input type="hidden"  name="id"   value="{{ $data->id ?? '' }}">
			<input type="hidden" name="invoiceid"   value="{{ $id ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Tanggal </label>
		<div class="col-sm-4">
			<input type="text" name="tanggal" placeholder="" class="form-control pickadate" value="{{ IndoTgl($data->tanggal ?? date('Y/m/d')) }}">
		</div>
	</div>

	<div class="modal-footer mt-3">
		<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>