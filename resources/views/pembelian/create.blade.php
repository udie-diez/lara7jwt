@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<script src="{{ url('/') }}/global_assets/js/plugins/media/fancybox.min.js"></script>

@if ($kodeubah!=1 && $kodeubah!=3)
<link href="{{ url('/') }}/assets/js/mycss.css" rel="stylesheet" type="text/css">
<script>
	$(document).ready(function() {
		$("form :input").attr('readonly', true);
	})
</script>
@endif


<div class="card">
	<div class="card-header  header-elements-inline">
		<h5 class="card-title">{{$tag['judul']}}/BIAYA PROJECT</h5>
		@if(isset($data->statuspembelian))
		<h5 class="text-center"><?php
								if (($data->statuspembelian ?? 0) == 2) {
									echo '<span class="bg-warning p-1"> BAYAR SEBAGIAN </span>';
								} else if (($data->statuspembelian ?? 0) == 1) {
									echo '<span class="bg-success p-1"> LUNAS </span>';
								} else if ($data->statuspembelian == 0) {
									echo '<span class="bg-warning p-1"> BELUM DIBAYAR </span>';
								}
								?>

		</h5>
		@endif
		<div class="header-elements">
			<!-- <div class="list-icon">
				<a href="{{ route('printInvoice', $invoice->id ?? 0) }}" class="btn btn-outline-success btn-sm" title="Cetak Invoice"><i class="icon-printer"></i> Cetak</a>
				<a href="{{ route('invoice') }}" class="btn btn-outline-info btn-sm">Daftar Invoice </a>
			</div> -->
			<div class="btn-group">
				<button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">Tindakan</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="{{ route('createPembelianPembayaran', $data->pembelianid ?? 0) }}" class="dropdown-item" title="Pembayaran"> Buat Pembayaran</a>
					<a class="dropdown-item" href="#" data-toggle="modal" title="Upload berkas pembelian" data-target="#modalFile" onclick="upload(<?php echo $data->pembelianid ?? 0; ?>)">Upload Berkas</a>

				</div>
			</div>
		</div>
	</div>
	<style>
		.mb-0 {
			height: 30px !important;
		}
	</style>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12 alpha-info">
				<h5>Project</h5>
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Mitra / Perusahaan</label>
					<label class="col-form-label col-sm-10">{{ $data->perusahaan ?? '' }}</label>
				</div>
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Pesanan</label>
					<label class="col-form-label col-sm-10">{{ $data->nama ?? '' }}</label>
				</div>
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Nilai</label>
					<label class="col-form-label col-sm-10">Rp. {{ Rupiah($data->nilai ?? '',2) }}</label>
				</div>
				<div class="form-group row">
					<label class="col-form-label col-sm-2">Nomor & Tanggal NP/SPK</label>
					<label class="col-form-label col-sm-3">{{ $data->no_spk ?? '' }}</label>
					<label class="col-form-label col-sm-4" id="tanggal_spk">Tanggal : {{ date('d/m/Y', strtotime($data->tgl_spk ?? '')) }}</label>
				</div>
			</div>


			<div class="col-sm-12">
				<form method="POST" action="{{ route('updatePembelian') }}">
					<fieldset>

						@csrf

						<style>
							.disable {
								pointer-events: none;
								cursor: default;
							}
						</style>

						@if ($kodeubah==1 || $kodeubah==3)
						<div hidden class="form-group row">
							<div class="col-sm-10">
								<input type="text" id="kolom" class="form-control tokenfield" placeholder="+ Kolom" value="No,Nama Vendor,Nama Produk,Qty,Satuan,Harga,Jumlah" data-fouc>

							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label"></label>
							<div class="list-icon col-sm-5 mt-3">
								<button hidden type="button" id="btn-tabel" class="btn btn-outline-info btn-sm">Buat Tabel</button>

							</div>
						</div>
						@endif
						<style>
							th,
							td {
								overflow: auto;
							}

							td input,
							select {
								overflow: auto;
							}

							th div {
								resize: horizontal;
								overflow: auto;
								border: 0;
							}

							.select2-search--dropdown {
								padding-bottom: .25rem;
								padding-top: .25rem;
							}
						</style>

						<h6 class="mt-3">Daftar Pembelian Barang / Jasa</h6>
						<div class="form-group row">
							<div id="tabel-item" class="ml-2" style="height: 300px;overflow-y: auto;">
								@if (isset($itemproduk) && count($itemproduk) > 0)
								<table id="tabel-item" class="tablexx" width='100%' border='1' cellpadding='5px'>
									<thead class="text-center">
										<tr>
											<th>No.</th>
											<th width="250px">Nama Vendor</th>
											<th width="250px">Nama Produk</th>
											<th width="70px">Qty</th>
											<th>Satuan</th>
											<th class="text-right">Harga</th>
											<th class="text-right">Jumlah</th>
											<th width="150px">Pajak</th>
											<th>#</th>
										</tr>
									</thead>
									<tbody id="bbody">
										<?php
										$no = 1;
										$iid = '';
										$subtotal = $jumlahppn = 0;

										foreach ($itemproduk as $row) {
											$pid = $row->pembelianid;
											$subtotal += $row->jumlah;
											$jumlahppn += $row->pajakid == 1 ? $row->pajak : 0;

											$iid .= $row->id . ',';
											echo '<tr><td class="text-center" width="50px">' . $no . '.</td>';
											$optionstrvendor = '';
											$optionstr = '';
											foreach ($vendor as $v) {
												$vendorid = $row->vendorid ?? 0;
												$selected = ($vendorid > 0) && ($vendorid == $v->id) ? 'selected' : '';
												$optionstrvendor .= "<option value='" . $v->id . "' " . $selected . " >" . $v->alias . " </option>";
											}
											foreach ($produk as $p) {
												$produkid = $row->produkid ?? 0;
												$selected = ($produkid > 0) && ($produkid == $p->id) ? 'selected' : '';
												$optionstr .= "<option value='" . $p->id . "' " . $selected . " >" . $p->nama . " </option>";
											}
											echo '<td><div><select class="select-search vendor" name="vendor' . $no . '"><option value="0"> - </option>' . $optionstrvendor . "</select><div></td>";
											echo '<td><div><select class="select-search produk" name="produk' . $no . '"><option value="0"> - </option>' . $optionstr . "</select><div></td>";
											echo '<td ><div><input type="text" name="tx2_' . $no . '"  class="form-control text-center" value="' . Rupiah($row->qty) . '"></div></td>';
											echo '<td ><div><input type="text" name="tx3_' . $no . '"  class="form-control text-center" value="' . $row->satuan . '"></div></td>';
											echo '<td ><div><input type="text" name="tx4_' . $no . '"  class="form-control text-right" value="' . str_replace('.', '', Rupiah($row->harga)) . '"></div></td>';
											echo '<td ><div><input type="text" name="tx5_' . $no . '"  class="form-control text-right" value="' . Rupiah($row->jumlah) . '"></div></td>';

											$no++;
											$optionstr = '';
											foreach ($pajak as $p) {
												$pajakid = $row->pajakid ?? 0;
												$selected = ($pajakid > 0) && ($pajakid == $p->id) ? 'selected' : '';
												$optionstr .= "<option value='" . $p->id . "' " . $selected . " >" . $p->nama . " (" . $p->nilai . "%)</option>";
											}
											echo '<td><div><select class="select" name="pajak' . ($no - 1) . '"><option value="0"> - </option>' . $optionstr . "</select><div></td>";

										?>
											@if ($kodeubah==1 || $kodeubah==3)

											<td><a href="{{ action('PembelianCont@destroyProduk',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a></td>
											@endif
											</tr>
										<?php
										}
										$no--;
										?>
									</tbody>
									<tfoot class="font-weight-bold">
										<tr>
											<td colspan="6" class="text-right">SUB TOTAL</td>
											<td class="text-right">{{Rupiah($subtotal)}}</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="6" class="text-right">PPN 10%</td>
											<td class="text-right">{{Rupiah($jumlahppn)}}</td>
											<td></td>
											<td></td>

										</tr>
										<tr>
											<td colspan="6" class="text-right">TOTAL</td>
											<td class="text-right">{{Rupiah($jumlahppn + $subtotal)}}</td>
											<td></td>
											<td></td>
										</tr>
									</tfoot>
								</table>
								@endif
							</div>
						</div>

						<input type="hidden" name="txkolomjumlah" id="txkolomjumlah" value="6">
						<input type="hidden" name="txkolom" id="txkolom" value="{{ ($i ?? 0) + 1}}">
						<input type="hidden" name="txbaris" id="txbaris" value="{{ $no ?? ''}}">
						<input type="hidden" name="txprojectid" id="txprojectid" value="{{ $data->id ?? '' }}">
						<input type="hidden" name="id" value="{{ $data->pembelianid ?? '' }}">
						<input type="hidden" name="txnamakolom" id="txnamakolom" value="No,Nama Produk,Qty,Satuan,Harga,Jumlah,Pajak">

						@if ($kodeubah==1 || $kodeubah==3)
						<button type="button" id="btn-additem" class="btn btn-outline-info btn-sm">+ Baris Baru</button>
						<a class="btn btn-outline-info btn-sm" href="#" title="Input Data Produk" data-toggle="modal" data-target="#modalProduk" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Produk</a>
						<a class="btn btn-outline-info btn-sm" href="#" title="Input Data Vendor" data-toggle="modal" data-target="#modalVendor" data-backdrop="static" data-keyboard="false"> <i class="icon-plus2"></i> Vendor</a>

						@endif
						<div class="form-group row">
							<div class="col-sm-9">
								@if ($errors->any())
								<div class="alert alert-danger">
									<ul>
										@foreach ($errors->all() as $error)
										<li>{{ $error }}</li>
										@endforeach
									</ul>
								</div>
								@endif
								@if ($message = Session::get('sukses'))
								<div class="alert alert-success alert-block">
									<button type="button" class="close" data-dismiss="alert">×</button>
									<strong>{{ $message }}</strong>
								</div>
								@endif
								@if ($message = Session::get('warning'))
								<div class="alert alert-danger alert-block">
									<button type="button" class="close" data-dismiss="alert">×</button>
									<strong>{{ $message }}</strong>
								</div>
								@endif
							</div>
						</div>

						<div class="form-group row mt-1">
							<div class="col-sm-11">

								<div class="list-icons" style="float: right;">
									<a href="{{ $kodeubah==1 ? route('showPembelian',$pid ?? '' ) : route('pembelian') }}" class="btn btn-outline-success btn-sm" title="Batal / Kembali">{{ $kodeubah==1 ? 'BATAL' : 'KEMBALI' }}</a>

									@if($kodeubah==1 || $kodeubah==3)
									<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
									@else
									<a href="{{ route('editPembelian', ($pid ?? '') ) }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
									@endif
								</div>
							</div>
						</div>
					</fieldset>

				</form>

				@if($data->file??''!='')
				<div class="form-group row pt-5">
					<label class="col-form-label col-sm-2">Berkas Pembelian</label>
					<div class="col-sm-7">
						<a class="form-control" href="{{ url('/').'/assets/pembelian/'. $data->file}}" id="filepanjar" data-popup="lightbox">{{ $data->file ?? '' }}</a>
					</div>
				</div>
				@endif
			</div>

		</div>


		@if(isset($pembayaran) && count($pembayaran)>0)
		<div class="mt-3" style="width: 80%;" id="tabel_pembayaran">

			<h6>Data Pembayaran</h6>
			<table class="table table-bordered">
				<thead class="bg-slate-600 text-center">
					<tr>
						<th>No.</th>
						<th>Pembayaran</th>
						<th>Tanggal</th>
						<th>Invoice Pembelian</th>
						<th class="text-right">Jumlah(Rp)</th>
						@if ($kodeubah==1 || $kodeubah==3)

						<th>Aksi</th>
						@endif
					</tr>
				</thead>
				<tbody>
					@if(isset($pembayaran))
					@php $total=0 @endphp
					@foreach($pembayaran as $row)
					<tr>
						<td class="text-center">{{ $loop->iteration}}.</td>
						<td><a href="{{ route('showPembelianPembayaran',$row->id)}}" class="text-success-800"> #{{ $row->kode}} </a></td>
						<td>{{ IndoTgl($row->tanggal)}}</td>
						<td>{{ $row->kodepembelian }}</td>
						<td class="text-right">{{ Rupiah($row->nilai) }}</td>
						@if ($kodeubah==1 || $kodeubah==3)

						<td class="text-center">
							<div class="list-icons">
								<a href="{{ route('showPembelianPembayaran',$row->id)}}" class="list-icons-item text-info-600" title="Lihat Data"><i class="icon-pencil7"></i></a>
								<a href="{{ action('PembelianCont@destroyPembayaran',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
							</div>
						</td>
						@endif
					</tr>
					@php $total += $row->nilai @endphp
					@endforeach
					@endif
				</tbody>
				<tfoot class="font-weight-bold">
					<tr>
						<td colspan="4" class="text-right">Total</td>
						<td class="text-right">{{Rupiah($total)}}</td>

					</tr>
				</tfoot>
			</table>
		</div>
		@endif
	</div>
</div>
<!-- /basic datatable -->

<!-- modal -->
<div class="modal fade" id="modalProduk" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="card-header bg-info border-bottom-info header-elements-inline">

				<h4 class="modal-title" id="modalMdTitle">Input Data Produk</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">
				<form method="POST" id="form_produk" action="#">
					@csrf
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Kode Produk</label>
						<div class="col-sm-8">
							<input type="text" disabled name="kode" placeholder="(Auto)" class="form-control border-warning">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Nama Produk </label>
						<div class="col-sm-8">
							<input type="hidden" name="tag" value="pembelian">
							<input type="text" required name="nama" placeholder="Nama Produk Barang/Jasa" required class="form-control border-warning">
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
								<option value='BARANG'>BARANG</option>
								<option value='JASA'>JASA</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Satuan</label>
						<div class="col-sm-5">
							<input type="text" name="satuan" placeholder="Satuan" required class="form-control border-warning">
						</div>
					</div>
					<div class="modal-footer mt-3">
						<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
						<button type="button" class="btn btn-outline-danger btn-sm" id="btnclose" data-dismiss="modal">Tutup</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>
<!-- /modal -->

<!-- modal -->
<div class="modal fade" id="modalVendor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-dialog-scrollable">
		<div class="modal-content">
			<div class="card-header bg-info border-bottom-info header-elements-inline">

				<h4 class="modal-title" id="modalMdTitle">Input Data Vendor</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
			</div>
			<div class="modal-body">

				<form method="POST" action="#" id="form_vendor">
					@csrf
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Nama Vendor</label>
						<div class="col-sm-8">
							<input type="hidden" name="tag" value="pembelian">
							<input type="text" required name="nama" placeholder="Nama Vendor" required class="form-control border-warning">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-3">Nama Alias</label>
						<div class="col-sm-8">
							<input type="text" name="alias" placeholder="Nama Alias Vendor" required class="form-control border-warning">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Alamat</label>
						<div class="col-sm-8">
							<textarea name="alamat" cols="5" placeholder="Alamat Lengkap" class="form-control"></textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Kota</label>
						<div class="col-sm-5">
							<input type="text" name="kota" placeholder="Kota" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Email</label>
						<div class="col-sm-5">
							<input type="text" name="email" placeholder="Email" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Kontak Person</label>
						<div class="col-sm-5">
							<input type="text" name="kontak" placeholder="Nama Kontak Person" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Telepon</label>
						<div class="col-sm-5">
							<input type="text" name="phone" placeholder="Telepon" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">NPWP</label>
						<div class="col-sm-5">
							<input type="text" name="npwp" placeholder="Npwp" class="form-control">
						</div>
					</div>


					<div class="form-group row">
						<label class="col-form-label col-sm-3 text-info"><i class="icon-office"></i> BANK</label>

					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Nama Bank</label>
						<div class="col-sm-5">
							<input type="text" name="bank" placeholder="" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Kantor Cabang</label>
						<div class="col-sm-5">
							<input type="text" name="cabang" placeholder="" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">Atas Nama Rekening</label>
						<div class="col-sm-5">
							<input type="text" name="atasnama" placeholder="" class="form-control">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-3">No.Rekening</label>
						<div class="col-sm-5">
							<input type="text" name="norek" placeholder="" class="form-control">
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-outline-info btn-sm">Simpan</button>
						<button type="button" class="btn btn-outline-danger btn-sm" data-dismiss="modal" id="btnclosevendor">Tutup</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</div>
<!-- /modal -->


@include('layouts.upload')
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ url('/') }}/global_assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript">
	var produk = '<?php echo  $produk ?? '' ?>';
	var vendor = '<?php echo  $vendor ?? '' ?>';
	produk = JSON.parse(produk);
	vendor = JSON.parse(vendor);
	var optionproduk = '';
	var optionvendor = '';
	for (var i = 0; i < produk.length; i++) {
		optionproduk += "<option value=" + produk[i]['id'] + ">" + produk[i]['nama'] + "</option>";
	}
	for (var i = 0; i < vendor.length; i++) {
		optionvendor += "<option value=" + vendor[i]['id'] + ">" + vendor[i]['nama'] + "</option>";
	}

	$(document).ready(function() {

		$('[data-popup="lightbox"]').fancybox({
			padding: 3
		});

		var projectid = '<?php echo $data->id ?? 0 ?>';
		$('.tokenfield').tokenfield();

		var kodeubah = '<?php echo $kodeubah ?>';
		if (kodeubah == 1) {

			var kolomstr = $('#kolom').val();
			var kolomjumlah = $('#kolomjumlah').val();
			var kolomarr = kolomstr.split(',');
			var kolom = "";
			var content = "<table border='1' cellpadding='5px' width='100%'><thead class='text-center'><tr>"
			for (i = 0; i < kolomarr.length; i++) {
				if (i == 1 || i == 2) {
					content += '<th width = "200px">' + kolomarr[i] + '</th>';
				} else if (i == 3) {
					content += '<th width = "70px">' + kolomarr[i] + '</th>';
				} else {
					content += '<th>' + kolomarr[i] + '</th>';
				}
			}
			content += "<th>Pajak</th>"
			content += "</tr></thead><tbody id='bbody'></tbody></table>"

			kolomstr += ",Pajak";

			$('#tabel-item').html('');
			$('#tabel-item').append(content);
			$('#txnamakolom').val(kolomstr);
			$('#txkolomjumlah').val(kolomjumlah);
			$('#txprojectid').val('<?php echo $data->id ?? 0 ?>');
			$('#btn-simpanitem').show();
			for (i = 0; i < 10; i++) {
				$('#btn-additem').trigger('click');
			}
		} else if (kodeubah == 3) {
			var rowCount = $('#bbody tr').length;
			if (rowCount < 10) {
				for (i = 0; i < 10; i++) {
					$('#btn-additem').trigger('click');
				}
			}
		}

		$('input').change(
			function() {
				var id = $(this).attr('name');

				//console.log(id);
				if (id) {
					var idx = id.split('_');
				}
				if (idx[0] == 'tx2' || idx[0] == 'tx4') {

					var hargax = 'tx4_' + idx[1];
					var qtyx = 'tx2_' + idx[1];

					var qty = $('input[name="' + qtyx + '"]').val();
					var harga = $('input[name="' + hargax + '"]').val();
					var jumlah = 0;
					if (harga > 0) {
						jumlah = qty * harga;
					}
					namex = 'tx5_' + idx[1];
					$('input[name="' + namex + '"]').val(formatRupiah(jumlah));
					// console.log(this.value);

				}
			});

		$('#form_produk').on('submit', function(e) {
			e.preventDefault();
			var url = "<?php echo route('updateProduk'); ?>";

			$.ajax({
				type: "POST",
				url: url,
				data: $(this).serialize(),
				success: function(data) {
					var datax = data.split('|');
					$('.produk').append($('<option>', {
						value: datax[1],
						text: datax[0]
					}));

					$('#btnclose').click();

				}
			});
		});

		$('#form_vendor').on('submit', function(e) {
			e.preventDefault();
			var url = "<?php echo route('updateVendor'); ?>";
			alert(url);
			$.ajax({
				type: "POST",
				url: url,
				data: $(this).serialize(),
				success: function(data) {
					var datax = data.split('|');
					$('.vendor').append($('<option>', {
						value: datax[1],
						text: datax[0]
					}));

					$('#btnclosevendor').click();

				}
			});
		});

	})

	var namakol = '<?php echo str_replace(',Pajak', '', $namakol ?? '') ?>'
	if (namakol != '') {
		$('#kolom').val('<?php echo str_replace(',Pajak', '', $namakol ?? '') ?>');
	}

	$('#btn-additem').on('click', function() {
		var kolomstr = $('#kolom').val();
		var kolomarr = kolomstr.split(',');
		var kolom = '';
		var rowCount = $('#bbody tr').length + 1;

		kolom += '<td class="text-center" width="50px">' + rowCount + '</td>';
		kolom += '<td><select name="vendor' + rowCount + '" class="vendor" ><option value="0" selected> - </option>' + optionvendor + "</select></td>";
		kolom += '<td><select name="produk' + rowCount + '" class="produk" ><option value="0" selected> - </option>' + optionproduk + "</select></td>";
		kolom += '<td><input type="text" name="tx2_' + rowCount + '" class="form-control"></td>';
		kolom += '<td><input type="text" name="tx3_' + rowCount + '" class="form-control"></td>';
		kolom += '<td><input type="text" name="tx4_' + rowCount + '" class="form-control text-right"></td>';
		kolom += '<td><input type="text" name="tx5_' + rowCount + '" class="form-control  text-right"></td>';

		//pajak
		var optionstr = '';
		var pajak = '<?php echo  $pajak ?? '' ?>';
		pajak = JSON.parse(pajak);
		for (var i = 0; i < pajak.length; i++) {
			optionstr += "<option value=" + pajak[i]['id'] + ">" + pajak[i]['nama'] + " (" + pajak[i]['nilai'] + "%)</option>";
		}
		kolom += '<td width = "150px"><select name="pajak' + rowCount + '" class="pph" ><option value="0" selected> - </option>' + optionstr + "</select></td>";

		$('#bbody').append('<tr>' + kolom + '</tr>');
		$('#txkolom').val(kolomarr.length + 1);
		$('#txbaris').val(rowCount);

		$(".produk").addClass('select-search');
		$(".vendor").addClass('select-search');
		$(".pph").addClass('select');
		$('.select-search').select2();
		$('.select').select2({
			minimumResultsForSearch: Infinity
		})

	})

	function upload(id) {
		$('#iditem').val(id);
		$('#jenisitem').val('pembelian');
	}
</script>

@endsection