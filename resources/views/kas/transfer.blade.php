@include('layouts.mylib')
<style>
	.modal-content {
		float: right;
		min-width: 800px;
	}
</style>
<div class="card">
	<div class="card-header header-elements-inline">
		<h6 class="card-title">TRANSFER DANA</h6>
		<div class="header-elements">
			<!-- <a href='#' value="{{ route('showJurnalTransfer', $data->id ?? 0) }}"  id="modal1" class="btn btn-outline-info btn-sm modalMd" title="Jurnal" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> Lihat Jurnal</a> -->
		</div>

	</div>
<div class="card-body">
	<form method="POST" action="{{ route('transferKasUpdate') }}">
		@csrf

		<div class="form-group row">
			<label class="col-form-label col-sm-2">Tanggal Transaksi</label>
			<div class="col-sm-5">
				<input type="text" required name="tanggal" placeholder="" class="form-control pickadate" value="{{ IndoTgl($data->tanggal ?? date('Y/m/d') ) }}">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-2">Transfer Dari</label>
			<div class="col-sm-7">
				<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
				<select name="akunsumberid" class="select" required data-placeholder="Pilih">
					<option value=""></option>
					@foreach($akun as $r)
					<option value="{{$r->id}}" <?php if (($data->akunsumberid ?? $id) == $r->id) echo 'selected';
												?>>{{'('.$r->kode . ') - ' .$r->nama}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-2">Setor Ke</label>
			<div class="col-sm-7">
				<select name="akuntujuanid" class="select" required data-placeholder="Pilih">
					<option value=""></option>
					@foreach($akun as $r)
					<option value="{{$r->id}}" <?php if (($data->akuntujuanid ?? old('akuntujuanid')) == $r->id) echo 'selected';
												?>>{{'('.$r->kode . ') - ' .$r->nama}}</option>
					@endforeach
				</select>
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-2">Jumlah (Rp)</label>
			<div class="col-sm-3">
				<input type="text" name="nilai" id="nilai" required placeholder="" class="form-control text-right" value="{{ Rupiah($data->nilai ?? '') }}">
			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label col-sm-2">Catatan</label>
			<div class="col-sm-7">
				<textarea name="catatan" placeholder="" class="form-control">{{ $data->catatan ?? '' }}</textarea>
			</div>
		</div>

		<div class="cars-footer mt-5">
			<a href="{{ route('destroytransferKas',$data->id ?? 0) }}" onclick="return confirm('Anda yakin ingin menghapus data Transfer ini ?')" class="btn btn-outline-warning btn-sm" style="margin-right: 400px;">Hapus</a>
			<button type="submit" class="btn btn-outline-info btn-sm" onclick="return confirm('Anda ingin menyimpan data ini ?')">Simpan</button>
			<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal">Tutup</button>
		</div>
	</form>
</div>
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$('.select').select2({
			minimumResultsForSearch: Infinity
		});

		$('form').on('submit', function(e) {
			var akun1 = $("select[name='akunsumberid']").val();
			var akun2 = $("select[name='akuntujuanid']").val();
			if (akun1 == akun2) {
				alert('Akun sumber dan tujuan transfer tidak boleh sama !');
				e.preventDefault();
				return false;
			}
		})

		var nilai = document.getElementById('nilai');
		nilai.addEventListener('keyup', function(e) {
			nilai.value = formatRupiah(this.value);
		});
		$('.pickadate').pickadate({
			format: 'dd/mm/yyyy',
			close: false,
			clear: false,
			today: false
		});
	})
</script>