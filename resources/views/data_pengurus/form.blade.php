<div class="form-group row">
	<label class="col-form-label col-sm-2">Nama Lengkap</label>
	<div class="col-sm-9">
		<input type="hidden"  name="id" value="{{ $data->id ?? '' }}">
		<input type="text" required name="nama" placeholder="Nama Lengkap"  required  class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">NIK</label>
	<div class="col-sm-5">
		<input type="text" name="nik" placeholder="NIK"  class="form-control" value="{{ $data->nik ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">E-mail</label>
	<div class="col-sm-5">
		<input type="text" name="email" placeholder="Email"  class="form-control" value="{{ $data->email ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">No.HP</label>
	<div class="col-sm-5">
		<input type="text"  name="phone" placeholder="0811..." class="form-control" value="{{ $data->phone ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">Jns.Kelamin</label>
	<div class="col-sm-5">
		<select name="jk" required data-placeholder="Pilih Jenis Kelamin" class="select  border-warning">
			<option value=""></option>
			<option value="L" <?php if(isset($data->jk)){if($data->jk=='L') echo 'selected';} ?>>Laki-laki</option>
			<option value="P" <?php if(isset($data->jk)){if($data->jk=='P') echo 'selected';} ?>>Perempuan</option>
		</select>
	</div>
</div> 
<div class="form-group row">
	<label class="col-form-label col-sm-2">JABATAN</label>
	<div class="col-sm-5">
		<input type="text" required name="jabatan" placeholder="Jabatan"  class="form-control border-warning" value="{{ $data->jabatan ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">Status Pengurus</label>
	<div class="col-sm-5">
		<select name="status" required data-placeholder="Pilih" class="select border-warning">
			<option value=""></option>
			<option value=1 <?php if(isset($data->status)){if($data->status==1) echo 'selected';} ?>>Aktif</option>
			<option value=0 <?php if(isset($data->status)){if($data->status==0) echo 'selected';} ?>>Tidak Aktif</option>
		</select>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function(){
	$('.select').select2({
            minimumResultsForSearch: Infinity
        });
	$('.select-search').select2();
	$("#kota").select2({
		dropdownParent: $("#modalMd")
	})

	$('.pickadate-year').pickadate({
			format: 'dd/mm/yyyy',
			selectYears: 4
		});
})

</script>