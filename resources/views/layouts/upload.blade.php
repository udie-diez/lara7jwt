<div class="modal fade" id="modalFile" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4> Upload File </h4><button type="button" id="btn-modalFile" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
					<div class="col-sm-12">
						<div style="display: none;" id="upload-msg" class="alert alert-danger alert-block">
						</div>
					</div>
				<p class="text-muted">| Pilih file kemudian tekan Upload |</p>
				<form enctype="multipart/form-data" action="{{ route('uploadProses') }}" method="POST">
					@csrf
					<div class="form-group row">
						<label class="col-form-label col-sm-2">File :</label>
						<div class="col-sm-7">
							<input type="hidden" name="iditem" id="iditem">
							<input type="hidden" name="jenis" id="jenisitem">
							<input type="file" multiple class="form-control-uniform" id="files" name="files">
						</div>
						<div class="col-sm-2">
							<button type="submit" class="btn btn-outline-info btn-sm">Upload</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- /modal -->