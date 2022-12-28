<div class="form-group row">
	<label class="col-form-label col-sm-3">Nama Sumber Pinjaman</label>
	<div class="col-sm-5">
		<input type="hidden"  name="id" value="{{ $data->id ?? '' }}">
		<input type="text" required name="nama"  class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>

<div class="form-group row">
	<label class="col-form-label col-sm-3">Keterangan</label>
	<div class="col-sm-5">
		<input type="text" name="keterangan" id="keterangan" placeholder="keterangan"  class="form-control" value="{{ $data->keterangan ?? '' }}">
	</div>
</div>
 
<br> 