@include('layouts.mylib')
<script src="{{ url('/') }}/assets/js/myjs_m.js"></script>

<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>

<form method="POST" action="{{ action('ProjectCont@updatePenggunaanPanjar') }}">
	@csrf

	<div class="form-group row">
		<label class="col-form-label col-sm-2">Nota Pesanan</label>
		<div class="col-sm-10">
			<select name="spk" id="spk" required data-placeholder="Pilih" class="select-search">
				<option value=""></option>
				@if(isset($spk)) @foreach($spk as $r)
				<option value="{{$r->id}}" data-nilai="{{ Rupiah($r->nilai) }}" <?php if (isset($data->projectid)) {
																					if ($data->projectid == $r->id) echo 'selected';
																				} ?>>SPK : {{$r->no_spk .' | '.$r->nama}}</option>
				@endforeach
				@endif
			</select>
		</div>
	</div>

	<div class="form-group row">
		<label class="col-form-label col-sm-2">Nilai (Rp)</label>
		<div class="col-sm-2">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="hidden" id="pid" name="panjarid" value="{{ $data->panjarid ?? '' }}">
			<input type="text" id="nilai" name="nilai" placeholder="" class="form-control text-right" value="{{ Rupiah($data->nilai ?? 0,0) }}">
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" onclick="$('#pid').val( $('#panjarid').val());" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {
		$("#spk").select2({
			dropdownParent: $("#modalMd")
		});
		var nilai = document.getElementById('nilai');
		nilai.addEventListener('keyup', function(e) {
			nilai.value = formatRupiah(this.value);
		});

		$('#spk').on('change', function() {
			var spk = <?php echo json_encode($spk ?? '') ?>;

			for (var i = 0; i < spk.length; i++) {
				if (spk[i].id == this.value) {
					$('#nilai').val(formatRupiah(spk[i].nilai));
					break;
				}
			}

		})

	})
</script>