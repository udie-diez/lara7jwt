<form method="POST" action="{{ action('ProjectCont@updateAM') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Nama AM</label>
		<div class="col-sm-9">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="hidden" id="projectidAM" name="projectid" value="{{ $projectid ?? $data->projectid }}">
			<select name="pengelola" required id="pengelola" class="select-search" data-placeholder="Pilih">
				<option value=""></option>
				@if(isset($pengelola))@foreach($pengelola as $r)
				<option value="{{$r->id}}" <?php if (isset($data->pengelolaid)) {
												if ($data->pengelolaid == $r->id) echo 'selected';
											} ?>>{{$r->nama}}</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-form-label col-sm-3">Pengelolaan (%)</label>
		<div class="col-sm-2">
			<input type="text" required name="porsi" placeholder="mis. 50" class="form-control" value="{{ $data->porsi ?? '' }}">
		</div>
		<label class="col-form-label col-sm-1">%</label>

	</div>

	<div class="modal-footer">
		<button type="submit" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$('.select-search').select2();
		$("#pengelola").select2({
			dropdownParent: $("#modalMd")
		})
	})
</script>