<div class="form-group row">
	<label class="col-form-label col-sm-3">No.Setoran </label>
	<div class="col-sm-3">
		<input type="text" hidden name="id" id="id"    class="form-control" value="{{ $data->id ?? '' }}">
		<input type="text" readonly name="nomor" id="nomor" placeholder="(Auto)"  class="form-control" value="{{ $data->nomor ?? '' }}">
	</div>
</div> 
<div class="form-group row">
	<label class="col-form-label col-sm-3">Tanggal Transaksi</label>
	<div class="col-sm-5">
		<input type="text" required id="tgl_transaksi" name="tgl_transaksi" placeholder="Tanggal Transaksi" class="form-control pickadate" value="<?php if(isset($data->tgl_transaksi)) echo date('d/m/Y', strtotime($data->tgl_transaksi)) ?>">
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Jenis Simpanan</label>
	<div class="col-sm-6">
		<select name="jenissimpanan" required id="jenissimpanan" data-placeholder="Pilih " class="form-control select">
			<option value=""></option>
			@foreach($jenis_simpanan as $r)
				<option value="{{$r->nama}}" <?php if(isset($data->jenis_simpanan)){if($data->jenis_simpanan==$r->nama) echo 'selected';} ?>>{{$r->nama}}</option>
			@endforeach
		</select>
	</div>
</div>
{{ setlocale (LC_TIME, 'id_ID')}}

<div class="form-group row" id="bulaninput" style="display: none;">
	<label class="col-form-label col-sm-3">Pembayaran Bulan</label>
	<div class="col-sm-3">
		<select name="bulan" id="bulan" data-placeholder="Pilih " class="form-control select">
			@for($i=1; $i < 13;$i++)
			<option value={{$i}} <?php if(isset($data->bulan)){if($data->bulan==$i) echo 'selected';} ?>>{{date('F', mktime(0, 0, 0, $i))}}</option>
			@endfor
		</select>
	</div>
	<div class="col-sm-1">
		<input type="text" class="form-control" placeholder="Tahun" value="{{ $data->tahun ?? date('Y') }}" name="tahun" id="tahun" >
	</div>
</div>
<div class="form-group row">
	<label class="col-form-label col-sm-3">Anggota</label>
	<div class="col-sm-6">
		<select name="anggota" required id="anggota" data-placeholder="Pilih Anggota" class="form-control select-search">
			<option value=""></option>
			@foreach($anggota as $r)
				<option value="{{$r->id}}" <?php if(isset($data->anggotaid)){if($data->anggotaid==$r->id) echo 'selected';} ?>>{{$r->nik .' - '.$r->nama}}</option>
			@endforeach
		</select>
	</div>
</div>

<div class="form-group row">
	<label class="col-form-label col-sm-3">Jumlah Pembayaran</label>
	<div class="col-sm-3">
		<input  style="font-size: large;" type="text" name="nilai" id="nilai" required placeholder="Rp."  class="form-control text-right" value="{{ number_format(($data->nilai ?? 0),0,',','.') ?? '' }}">
	</div>
</div> 
<div class="form-group row">
	<label class="col-form-label col-sm-3"></label>
	<div class="col-sm-6">
			<div class="alert alert-info alert-block">
				<button type="button" class="close" data-dismiss="alert">×</button> 
				<span id="message"></span>
			</div>
	</div>
</div>
<meta name="csrf-token" content="{{ csrf_token() }}" />


<script type="text/javascript">
$(document).ready(function(){

	$('.alert').hide();

	$('.pickadate').pickadate({
		format: 'dd/mm/yyyy',
		close: false,
		today: false,
		clear: false
	});

	$('.select').select2({
        minimumResultsForSearch: Infinity
    });
	$('.select-search').select2();
	$("#anggota").select2({
		dropdownParent: $("#modalMd")
	})
	
	var nilai = document.getElementById('nilai');
		nilai.addEventListener('keyup', function(e){
		nilai.value = formatRupiah(this.value);
		});
 
	var nilai_simpanan;
	$('#btn-submit').on('click', function(){

		if(!confirm('Anda ingin menyimpan setoran pembayaran ini ?')) return false;

		var bayar = $('#nilai').val();

		bayar = bayar.replace(/\./g, '');
		nilai_simpanan = getNilaiSimpanan();
		nilai_simpanan = nilai_simpanan.replace('.00','');

		if(+(bayar) == +(nilai_simpanan)){
			prosesPembayaran(bayar);
		}else{
			if(+(bayar) < +(nilai_simpanan)){
				if(confirm("Jumlah Pembayaran KURANG dari Besar Angsuran. Lanjutkan pembayaran ?")){
					prosesPembayaran(bayar);
				} 
			}else{
				if(confirm("Jumlah Pembayaran LEBIH dari Besar Angsuran. Lanjutkan pembayaran ?")){
					prosesPembayaran(bayar);
				} 
			}
		}
		return false;
	})

	$('#btn-input').on('click', function(){
		$('.alert').hide();
		$("#anggota").val("").trigger( "change" );
		$("#anggota").focus();

	}) 

	$('#jenissimpanan').on('change', function(){
		if(this.value =='WAJIB'){
			$('#bulaninput').show();

			var d = new Date();
    		n = d.getMonth();
			$("#bulan").val(n+1).trigger( "change" );

		}else{
			$('#bulaninput').hide();

		}
	})
})

function getNilaiSimpanan(){
	
		var item = $("#jenissimpanan option:selected").val();
		var simpanan = '<?php echo json_encode($jenis_simpanan) ?>';
		var simpananx = $.parseJSON(simpanan);
		for(var i=0; i< simpananx.length ; i++){
			if(simpananx[i]['nama']==item){
				var nilai_simpananx = simpananx[i]['nilai'];
				nilai_simpananx = nilai_simpananx.replace('.00','');
				return nilai_simpananx;
			}
		}
		return 0;
}

function prosesPembayaran(bayar){

	var anggotaid = $("#anggota option:selected").val();
	var bulan = $("#bulan option:selected").val();
	var tahun = $("#tahun").val();
	var id = $("#id").val();
	var jenis_simpanan = $('#jenissimpanan').val();
	var tgl_transaksi = $('#tgl_transaksi').val();
	var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
	var url = "<?=@ $route; ?>";
	$.ajax({
           type:"POST",
           url: url,
           data:{id:id, anggotaid:anggotaid, jenis_simpanan:jenis_simpanan, tgl_transaksi:tgl_transaksi,bulan:bulan, tahun:tahun, nilai:bayar,_token: CSRF_TOKEN,},
           success:function(data){
				$('.alert').show();
              	$('#message').html(data);
				$('#btn-input').show();
				
           }
        });
}

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