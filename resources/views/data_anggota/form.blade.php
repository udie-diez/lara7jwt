<?php use Illuminate\Support\Facades\Auth; ?>
<div class="form-group row">
	<label class="col-form-label col-sm-3">No.Anggota</label>
	<div class="col-sm-5">
		<input type="text" readonly name="nomor"  placeholder="(Auto)" required  class="form-control  border-warning" value="{{ $data->nomor ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Nama Lengkap</label>
	<div class="col-sm-8">
		<input type="hidden"  name="id" value="{{ $data->id ?? '' }}">
		<input type="text" required name="nama" placeholder="Nama Lengkap"  required  class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">NIK</label>
	<div class="col-sm-5">
		<input type="text" name="nik" placeholder="NIK"  class="form-control" value="{{ $data->nik ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Email</label>
	<div class="col-sm-5">
		<input type="text" name="email" placeholder="Email"  class="form-control" value="{{ $data->email ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Phone</label>
	<div class="col-sm-5">
		<input type="text"  name="phone" placeholder="0811..." class="form-control" value="{{ $data->phone ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Jns.Kelamin</label>
	<div class="col-sm-5">
		<select name="jk"   data-placeholder="Pilih Jenis Kelamin" class="select border-warning">
			<option value=""></option>
			<option value="L" <?php if(isset($data->jk)){if($data->jk=='L') echo 'selected';} ?>>Laki-laki</option>
			<option value="P" <?php if(isset($data->jk)){if($data->jk=='P') echo 'selected';} ?>>Perempuan</option>
		</select>
	</div>
</div>

<div class="form-group row">
	<label class="col-form-label col-sm-3">Lokasi Kerja</label>
	<div class="col-sm-8">
		<input type="text"  name="lokasi" placeholder="Kantor, Divisi, ..." class="form-control" value="{{ $data->lokasikerja ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Jabatan</label>
	<div class="col-sm-5">
		<input type="text"  name="jabatan" placeholder="Jabatan" class="form-control" value="{{ $data->jabatan ?? '' }}">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Informasi Rekening Bank</label>
	<div class="col-sm-2">
		<input type="text"  name="bank" placeholder="Nama Bank" class="form-control" value="{{ $data->bank ?? '' }}">
	</div>
	<div class="col-sm-3">
		<input type="text"  name="norekening" placeholder="No. Rekening Bank" class="form-control" value="{{ $data->norekening ?? '' }}">
	</div>
	<div class="col-sm-4">
		<input type="text"  name="atasnama" placeholder="Atas Nama Rekening" class="form-control" value="{{ $data->atasnama ?? '' }}">
	</div>
</div>


<div class="form-group row">
	<label class="col-form-label col-sm-3">Tempat/Tgl.Lahir<sup class="text-danger"></sup></label>
	<div class="col-sm-4">
		<input type="text" name="tempatlahir" placeholder="Tempat Lahir"  class="form-control" value="{{ $data->tempat_lahir ?? '' }}">
	</div>
	<div class="col-sm-5">
		<input type="text"  name="tgllahir" placeholder="Tanggal Lahir"   class="form-control pickadate-year" value="<?php if(isset($data->tgl_lahir)) echo date('d/m/Y', strtotime($data->tgl_lahir)) ?>">
	</div>
</div>


<div class="form-group row">
	<label class="col-form-label col-sm-3">Tgl. Keanggotaan<sup class="text-danger"></sup></label>
	<div class="col-sm-5">
		<input type="text"  name="tgldaftar" placeholder="Tanggal Daftar Keanggotaan" class="form-control pickadate-year" value="<?php if(isset($data->tgl_daftar)) echo date('d/m/Y', strtotime($data->tgl_daftar)) ?>">
	</div>
</div>
<div class="form-group row" >
	<label class="col-form-label col-sm-3">Status Keanggotaan</label>
	<div class="col-sm-3">
		<select name="status" data-placeholder="Pilih" class="select">
			<option value=""></option>
			<option value=1 <?php if(isset($data->status)){if($data->status==1) echo 'selected';} ?>>Aktif</option>
			<option value=0 <?php if(isset($data->status)){if($data->status==0) echo 'selected';} ?>>Non Aktif</option>
			<option value=2 <?php if(isset($data->status)){if($data->status==2) echo 'selected';} ?>>Keluar</option>
		</select>
	</div>
	<label class="col-form-label col-sm-2">Tanggal Refund :</label>

	<div class="col-sm-3">
		<input type="text"  name="tglnonaktif" placeholder="" class="form-control pickadate-year" value="<?php if(isset($data->tanggal_refund)) echo date('d/m/Y', strtotime($data->tanggal_refund)) ?>">
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
			today:false
		});
})

</script>