<div class="form-group row">
	<label class="col-form-label col-sm-2">Nama Lengkap</label>
	<div class="col-sm-10">
		<input type="hidden"  name="id" value="{{ $data->id ?? '' }}">
		<input type="text" required name="nama" placeholder="Nama Lengkap"    class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">NIK</label>
	<div class="col-sm-5">
		<input type="text" required name="nik" placeholder="NIK"  class="form-control border-warning" value="{{ $data->nik ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">Tanggal Lahir<sup class="text-danger"></sup></label>
	<div class="col-sm-5">
		<input type="text"  name="tgllahir" placeholder="Tanggal Lahir"   class="form-control pickadate-year" value="<?php if(isset($data->tanggal_lahir)) echo date('d/m/Y', strtotime($data->tanggal_lahir)) ?>">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">E-mail</label>
	<div class="col-sm-4">
		<input type="text" name="email" placeholder="Email"  class="form-control" value="{{ $data->email ?? '' }}">
	</div>
	<label class="col-form-label col-sm-2">No.Telepon</label>
	<div class="col-sm-4">
		<input type="text"  name="phone" placeholder="0811..." class="form-control" value="{{ $data->phone ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">No. KTP</label>
	<div class="col-sm-4">
		<input type="text"  name="ktp" placeholder="No. KTP" class="form-control" value="{{ $data->nomor_ktp ?? '' }}">
	</div>
	<label class="col-form-label col-sm-2">NPWP</label>
	<div class="col-sm-4">
		<input type="text"  name="npwp" placeholder="NPWP" class="form-control" value="{{ $data->npwp ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">Alamat</label>
	<div class="col-sm-10">
		<textarea name="alamat" cols="5" placeholder="Alamat Lengkap" class="form-control" >{{ $data->alamat ?? '' }}</textarea>
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">Kota</label>
	<div class="col-sm-3">
		<input type="text" name="kota"   placeholder="Kota"  class="form-control" value="{{ $data->kota ?? '' }}">
	</div>
</div>  
<div class="form-group row">
	<label class="col-form-label col-sm-2">JABATAN</label>
	<div class="col-sm-5">
		<input type="text" name="jabatan" placeholder="Jabatan"  class="form-control border-warning" value="{{ $data->jabatan ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-2">Status Pengelola</label>
	<div class="col-sm-5">
		<select name="status" required data-placeholder="Pilih" class="select border-warning">
			<option value=""></option>
			<option value=1 <?php if(isset($data->status)){if($data->status==1) echo 'selected';} ?>>Aktif</option>
			<option value=0 <?php if(isset($data->status)){if($data->status==0) echo 'selected';} ?>>Tidak Aktif</option>
		</select>
	</div>
</div>

<div class="form-group row">
		<label class="col-form-label col-sm-3 text-info"><i class="icon-office"></i> BANK</label>
		 
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Nama Bank</label>
		<div class="col-sm-5">
			<input type="text" name="bank"   placeholder="" class="form-control" value="{{ $data->bank ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">No.Rekening</label>
		<div class="col-sm-5">
			<input type="text" name="norek"   placeholder="" class="form-control" value="{{ $data->nomor_rek ?? '' }}">
		</div>
	</div><div class="form-group row">
		<label class="col-form-label col-sm-2">Atas Nama</label>
		<div class="col-sm-5">
			<input type="text" name="atasnama"   placeholder="" class="form-control" value="{{ $data->atasnama_rekening ?? '' }}">
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
			selectYears: 70,
			selectMonths: 12,
			max: true,
			close:false,
			clear:false,
			today:false
		});
})

</script>