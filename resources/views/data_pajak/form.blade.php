<div class="form-group row">
	<label class="col-form-label col-sm-3">Nama Pajak</label>
	<div class="col-sm-9">
		<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
		<input type="text" required name="nama" placeholder="Nama" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Nilai</label>
	<div class="col-sm-1">
		<input type="text" name="nilai" placeholder="mis.10" class="form-control" value="{{ $data->nilai ?? '' }}">
	</div>
	<label class="col-form-label col-sm-1">%</label>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Akun Pajak Pembelian</label>
	<div class="col-sm-5">
		<select name="akunin" id="akunin" class="select-search" data-placeholder="Pilih">
			<option value=""></option>
			@foreach($akun as $r)
			<option value="{{$r->id}}" <?php if (($data->akuninid ?? old('akunin')) == $r->id) echo 'selected';
										?>>{{'('.$r->kode.') - '. $r->nama}}</option>
			@endforeach
		</select>
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Akun Pajak Penjualan</label>
	<div class="col-sm-5">
		<select name="akunout" id="akunout" class="select-search" data-placeholder="Pilih">
			<option value=""></option>
			@foreach($akun as $r)
			<option value="{{$r->id}}" <?php if (($data->akunoutid ?? old('akunout')) == $r->id) echo 'selected';
										?>>{{'('.$r->kode.') - '. $r->nama}}</option>
			@endforeach
		</select>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.select-search').select2();
		$("#akunin").select2({
			dropdownParent: $("#modalMd")
		})
		$("#akunout").select2({
			dropdownParent: $("#modalMd")
		})
	})
</script>