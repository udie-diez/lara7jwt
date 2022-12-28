<script type="text/javascript">
	$(document).ready(function() {
		$('.select').select2({
			minimumResultsForSearch: Infinity
		});
		$('.select-search').select2();
		$("#nama").select2({
			dropdownParent: $("#modalMd")
		});

		$('form').on('submit',function(){
			$('#btn-submit').html("<i class='icon-spinner9 spinner position-left'></i> Simpan");
		})
	})
</script>

<div class="form-group row">
	<label class="col-form-label col-sm-2">Nama </label>
	<div class="col-sm-9">
		<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
		<select name="nama" required id="nama" data-placeholder="Pilih" class="select-search">
			<option value=""></option>
			@foreach($users as $r)
			<option value="{{$r->kode.$r->id}}" <?php if ($data->kode ?? '' == $r->kode . $r->id) echo 'selected';
												?>>{{$r->nama}}</option>
			@endforeach
		</select>
	</div>
</div>
 
<div class="form-group row">
	<label class="col-form-label col-sm-2">Status</label>
	<div class="col-sm-5">
		<select name="status" required data-placeholder="Pilih" id="status" class="select">
			<option value=0 <?php if (isset($data->status)) {
								if ($data->status == 0) echo 'selected';
							} ?>>TIDAK AKTIF</option>
			<option value=1 <?php if (isset($data->status)) {
								if ($data->status == 1) echo 'selected';
							} ?>> AKTIF</option>
		</select>
	</div>
</div>