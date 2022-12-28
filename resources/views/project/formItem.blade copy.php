@include('layouts.mylib')
<form method="POST" action="{{ action('ProjectCont@updateItem') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Nama Item</label>
		<div class="col-sm-9">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="hidden" id="pid" name="projectid" value="{{ $data->projectid ?? '' }}">
			<input type="text" required name="nama" placeholder="Nama Barang/Pekerjaan" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
		</div>
	</div>

	<div class="form-group row">
		<label class="col-form-label col-sm-2">Satuan</label>
		<div class="col-sm-5">
			<input type="text" name="satuan" placeholder="" class="form-control" value="{{ $data->satuan ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Harga</label>
		<div class="col-sm-3">
			<input type="text" id="harga" name="harga" placeholder="" class="form-control text-right" value="{{ Rupiah($data->harga ?? 0,0) }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Jumlah</label>
		<div class="col-sm-3">
			<input type="text" id="jumlah" name="jumlah" placeholder="" class="form-control" value="{{ $data->jumlah ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-2">Total</label>
		<div class="col-sm-3">
			<input type="text" readonly id="total" name="total" placeholder="" class="form-control text-right" value="{{ Rupiah($data->total ?? 0,0) }}">
		</div>
	</div>
	<div  class="form-group row">
		<label class="col-form-label col-sm-2">PPh</label>
		<div class="col-sm-4">
			<select name="pph" class="select">
				<option value="0" selected>-</option>
				@foreach($pajak as $r)
				<option value='{{ $r->id }}' <?php if (isset($data->pajakid)) {
									if ($data->pajakid == $r->id) echo 'selected';
								} ?>><?=@ $r->nama .'('.$r->nilai.'%)' ?></option>
				@endforeach
			</select>
		</div>
	</div>
	<div hidden class="form-group row">
		<label class="col-form-label col-sm-2">Pajak PPN</label>
		<div class="col-sm-3">
			<select name="pajak" class="select">
				<option value=1 <?php if (isset($data->ppn)) {
									if ($data->ppn > 0) echo 'selected';
								} ?>>YA</option>
				<option value=0 <?php if (isset($data->ppn)) {
									if ($data->ppn == 0 || $data->ppn == null) echo 'selected';
								} ?>>TIDAK</option>
			</select>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" onclick="$('#pid').val( $('#projectid').val()); return confirm('Anda ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>

<script type="text/javascript">
	$(document).ready(function() {

		$('.select').select2({
			minimumResultsForSearch: Infinity
		});

		var harga = document.getElementById('harga');
		harga.addEventListener('keyup', function(e) {
			harga.value = formatRupiah(this.value);
		});
		var jumlah = document.getElementById('jumlah');
		jumlah.addEventListener('keyup', function(e) {
			// jumlah.value = formatRupiah(this.value);

			var hargax = $('#harga').val();
			hargax = hargax.toString().replace(/[^,\d]/g, '').toString();
			var total = hargax * this.value;
			$('#total').val(formatRupiah(total));
		});



	})

	function formatRupiah(angka, prefix) {
		var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
			split = number_string.split(','),
			sisa = split[0].length % 3,
			rupiah = split[0].substr(0, sisa),
			ribuan = split[0].substr(sisa).match(/\d{3}/gi);

		if (ribuan) {
			var separator;
			separator = sisa ? '.' : '';
			rupiah += separator + ribuan.join('.');
		}

		rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
		return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
	}
</script>