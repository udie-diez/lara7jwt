<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>
<script type="text/javascript">
	$(document).ready(function() {
		$('.select').select2({
			minimumResultsForSearch: Infinity
		});

	})
</script>
<form method="POST" action="{{ route('updateProduk') }}">
	@csrf
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Kode Produk</label>
		<div class="col-sm-8">
			<input type="text" disabled name="kode" placeholder="(Auto)" class="form-control border-warning" value="{{ $data->kode ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Nama Produk </label>
		<div class="col-sm-8">
			<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
			<input type="text" required name="nama" placeholder="Nama Produk Barang/Jasa" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Merk / Spesifikasi</label>
		<div class="col-sm-8">
			<textarea name="keterangan" cols="5" placeholder="Deskripsi Produk" class="form-control">{{ $data->keterangan ?? '' }}</textarea>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Jenis</label>
		<div class="col-sm-5">
			<select name="jenis" required data-placeholder="Pilih" class="select">
				<option value=""></option>
				<option value='BARANG' <?php if (isset($data->jenis)) {
											if ($data->jenis == 'BARANG') echo 'selected';
										} ?>>BARANG</option>
				<option value='JASA' <?php if (isset($data->jenis)) {
											if ($data->jenis == 'JASA') echo 'selected';
										} ?>>JASA</option>
			</select>
		</div>
	</div>
	<div class="form-group row">
		<label class="col-form-label col-sm-3">Satuan</label>
		<div class="col-sm-5">
			<input type="text" name="satuan" placeholder="Satuan" required class="form-control border-warning" value="{{ $data->satuan ?? '' }}">
		</div>
	</div>
 
	<div class="modal-footer mt-3">
		<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
		<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
	</div>
</form>