<div class="form-group row">
	<label class="col-form-label col-sm-3">Nama Simpanan</label>
	<div class="col-sm-5">
		<input type="hidden"  name="id" value="{{ $data->id ?? '' }}">
		<input type="text" required name="nama"  class="form-control border-warning" value="{{ $data->nama ?? '' }}">
	</div>
</div>

<div class="form-group row">
	<label class="col-form-label col-sm-3">Besar Simpanan (Rp.)</label>
	<div class="col-sm-5">
		<input type="text" required name="nilai" id="nilai" placeholder=""  class="form-control text-right border-warning" value="<?=@ $data->nilai ? number_format($data->nilai,0,',','.') : '' ?>">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Status </label>
	<div class="col-sm-5">
		<select name="status" required data-placeholder="Pilih" class="select border-warning">
			<option value=""></option>
			<option value=1 <?php if(isset($data->status)){if($data->status==1) echo 'selected';} ?>>Aktif</option>
			<option value=0 <?php if(isset($data->status)){if($data->status==0) echo 'selected';} ?>>Tidak Aktif</option>
		</select>
	</div>
</div>
<br>
<script type="text/javascript">
$(document).ready(function(){
	$('.select').select2({
            minimumResultsForSearch: Infinity
    });
	
	var nilai = document.getElementById('nilai');
		nilai.addEventListener('keyup', function(e){
		nilai.value = formatRupiah(this.value);
		});
 
})

function formatRupiah(angka, prefix){
    var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
    split   		= number_string.split(','),
    sisa     		= split[0].length % 3,
    rupiah     		= split[0].substr(0, sisa),
    ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

    if(ribuan){
        var separator;
        separator = sisa ? '.' : '';
        rupiah += separator + ribuan.join('.');
    }

    rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
    return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
}

</script>