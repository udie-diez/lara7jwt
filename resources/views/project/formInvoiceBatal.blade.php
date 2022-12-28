<form method="POST" action="{{ action('ProjectCont@updatePembatalan') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-2">No.Invoice</label>
		<div class="col-sm-5">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="text"   name="nomor" readonly class="form-control  " value="{{ $data->nomor ?? '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-form-label col-sm-2">Alasan Pembatalan</label>
		<div class="col-sm-9">
			<input type="text" name="alasan" required class="form-control" value="{{ $data->alasanpembatalan ?? '' }}">
		</div>
	</div>
	 
	<div class="modal-footer">
		<button type="submit" onclick="return confirm('Anda ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>
   