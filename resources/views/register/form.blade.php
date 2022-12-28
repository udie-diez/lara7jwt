<div class="form-group row">
	<label class="col-form-label col-sm-2">Nama </label>
	<div class="col-sm-5">
		<input type="hidden"  name="id" value="{{ $data->id ?? '' }}">
		<input type="text" name="nama" readonly  class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">NIK</label>
	<div class="col-sm-5">
		<input type="text"  name="nik"  readonly class="form-control" value="{{ $data->nik ?? '' }}">
	</div>
</div>

<div class="form-group row">
	<label class="col-form-label col-sm-2">E-mail</label>
	<div class="col-sm-5">
		<input type="text" name="email" readonly   class="form-control" value="{{ $data->email ?? '' }}">
	</div>
</div>

<div class="form-group row">
	<label class="col-form-label col-sm-2">Tgl. Register</label>
	<div class="col-sm-5">
		<input type="text" name="tgl_register" readonly   class="form-control" value="{{ $data->created_at ?? '' }}">
	</div>
</div>  

<div class="form-group row">
	<label class="col-form-label col-sm-2">Status</label>
	<div class="col-sm-2">
		<input type="text" name="status" readonly class="form-control text-warning" value="{{ $data->status==0 ? 'TIDAK AKTIF' : 'AKTIF' }}">
	</div>
</div> 
<hr> 
<div hidden class="form-group row" id="tag-aktivasi" >
		<label class="col-form-label col-sm-3">Aktivasi Akun, Tentukan Role :</label>
		<div class="col-sm-4">
			<select name="rule" data-placeholder="Pilih" id="rule" class="select">
				<option value=""></option>
				<option value=9 <?php if(isset($data->role)){if($data->role==9) echo 'selected';} ?>>Admin</option>
				<option value=1 <?php if(isset($data->role)){if($data->role==1) echo 'selected';} ?>>Pengurus</option>
				<option value=4 <?php if(isset($data->role)){if($data->role==4) echo 'selected';} ?>>Pengawas</option>
				<option value=2 <?php if(isset($data->role)){if($data->role==2) echo 'selected';} ?>>Pengelola</option>
				<option value=3 <?php if(isset($data->role)){if($data->role==3) echo 'selected';} ?>>Anggota</option>
			</select>
		</div> 
</div> 

<script type="text/javascript">
$(document).ready(function(){
 
	$('.select').select2({
        minimumResultsForSearch: Infinity
    });
 
})
</script>