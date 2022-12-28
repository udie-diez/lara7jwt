@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

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
		<h5 class="card-title">{{$tag['judul']}}</h5>
		 
		<div class="header-elements">
			<!-- <div class="list-icon">
				<a href="{{ route('printInvoice', $invoice->id ?? 0) }}" class="btn btn-outline-success btn-sm" title="Cetak Invoice"><i class="icon-printer"></i> Cetak</a>
				<a href="{{ route('invoice') }}" class="btn btn-outline-info btn-sm">Daftar Invoice </a>
			</div> -->
			<div class="btn-group">
				<button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">Tindakan</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="{{ action('PembelianPemesananCont@createPembelian', $data->id ?? '') }}" class="dropdown-item" title="Buat Pembelian"> Buat Pembelian</a>
					<a href="{{ action('PembelianPemesananCont@cetak', $data->id ?? 0) }}" class="dropdown-item" title="Cetak Pemesanan"> Cetak Pemesanan (PO)</a>
				
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12">
				<form method="POST" action="{{ route('update_po') }}">
					<fieldset>

						@csrf
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Nomor</label>
							<div class="col-sm-2">
								<input type="hidden" name="id" value="{{ $data->id ?? '' }}">
								<input type="hidden" name="pq_id" value="{{ $data->pq_id ?? '' }}">
								<input type="text" disabled name="nomor" class="form-control" placeholder="(Auto)" value="{{ $data->kode ?? old('nomor') }}">
							</div>
							@if(($data->pq_id ?? '') > 0)
							<div class="col-sm-3"></div>
							<div class="col-sm-2 text-right">
								<label class="col-form-label text-muted"> PQ : <a href="{{ route('show_pq',$data->pq_id)}}">{{ '#'.$data->kode_pq  }}</a></label>
							</div>
							@endif
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Vendor / Supplier</label>
							<div class="col-sm-7">
								<select name="vendor" required class="select-search">
									<option value=""></option>
									@foreach($vendor as $r)
									<option value="{{$r->id}}" <?php if (($data->vendorid ?? old('vendor')) == $r->id) echo 'selected';
																?>>{{$r->nama}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Tanggal </label>
							<div class="col-sm-2">
								<input type="text" required name="tanggal" placeholder="Tanggal" class="form-control pickadate" value="{{ IndoTgl($data->tanggal ?? old('tanggal')) }}">
							</div>
						</div>
						 

						<style>
							.disable {
								pointer-events: none;
								cursor: default;
							}
						</style>

						@if ($kodeubah==1 || $kodeubah==3)
						<div hidden class="form-group row">
							<div class="col-sm-10">
								<input type="text" id="kolom" class="form-control tokenfield" placeholder="+ Kolom" value="No,Nama Produk,Qty,Satuan,Harga,Jumlah" data-fouc>

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

						<div class="form-group row mt-3">
							<div id="tabel-item" class="ml-2">
								@if (isset($itemproduk) && count($itemproduk) > 0)
								<table id="tabel-item" class="table" width='100%'>
									<thead class="text-center">
										<tr>
											<th>No.</th>
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
											$optionstr = '';
											foreach ($produk as $p) {
												$produkid = $row->produkid ?? 0;
												$selected = ($produkid > 0) && ($produkid == $p->id) ? 'selected' : '';
												$optionstr .= "<option value='" . $p->id . "' " . $selected . " >" . $p->nama . " </option>";
											}
											echo '<td><div><select class="select-search" name="produk' . $no . '"><option value="0"> - </option>' . $optionstr . "</select><div></td>";
											echo '<td ><div><input type="text" name="tx2_' . $no . '"  class="form-control text-center" value="' . Rupiah($row->qty) . '"></div></td>';

											echo '<td ><div><input type="text" name="tx3_' . $no . '"  class="form-control text-center" value="' . $row->satuan . '"></div></td>';
											echo '<td ><div><input type="text" name="tx4_' . $no . '"  class="form-control text-right" value="' . Rupiah($row->harga) . '"></div></td>';
											echo '<td ><div><input readonly type="text" name="tx5_' . $no . '"  class="form-control text-right" value="' . Rupiah($row->jumlah) . '"></div></td>';

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

											<td><a href="{{ action('PembelianPemesananCont@destroyProduk',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a></td>
											@endif
											</tr>
										<?php
										}
										$no--;
										?>
									</tbody>
									<tfoot class="font-weight-bold">
										<tr>
											<td colspan="5" class="text-right">SUB TOTAL</td>
											<td class="text-right">{{Rupiah($subtotal)}}</td>
											<td></td>
											<td></td>
										</tr>
										<tr>
											<td colspan="5" class="text-right">PPN 10%</td>
											<td class="text-right">{{Rupiah($jumlahppn)}}</td>
											<td></td>
											<td></td>

										</tr>
										<tr>
											<td colspan="5" class="text-right">TOTAL</td>
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
						<input type="hidden" name="txprojectid" id="txprojectid" value="{{ $pid ?? '' }}">
						<input type="hidden" name="txnamakolom" id="txnamakolom" value="No,Nama Produk,Qty,Satuan,Harga,Jumlah,Pajak">

						@if ($kodeubah==1 || $kodeubah==3)
						<button type="button" id="btn-additem" class="btn btn-outline-info btn-sm">+ Baris Baru</button>
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
									<a type="button" href="{{ $kodeubah==1 ? route('show_po',$data->id ?? '' ) : route('po') }}" class="btn btn-outline-success btn-sm" title="Batal / Kembali">{{ $kodeubah==1 ? 'BATAL' : 'KEMBALI' }}</a>

									@if($kodeubah==1 || $kodeubah==3)
									<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
									@else
									<a type="button" href="{{ route('edit_po',$data->id ?? '') }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
									@endif
								</div>
							</div>
						</div>
					</fieldset>

				</form>
			</div>

		</div>
 
	</div>
</div>
<!-- /basic datatable -->

<script src="{{ url('/') }}/global_assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		// $("#iform :input").attr('readonly',true);

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
				if (i == 1) {
					content += '<th width = "250px">' + kolomarr[i] + '</th>';
				} else if (i == 2) {
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
			$('#btn-additem').trigger('click');
		}

		$('input').change(
			function() {
				var id = $(this).attr('name');
				console.log(id);
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

		var produk = '<?php echo  $produk ?? '' ?>';
		produk = JSON.parse(produk);
		var optionstr = '';

		for (var i = 0; i < produk.length; i++) {
			optionstr += "<option value=" + produk[i]['id'] + ">" + produk[i]['nama'] + "</option>";
		}

		kolom += '<td class="text-center" width="50px">' + rowCount + '</td>';
		kolom += '<td><select name="produk' + rowCount + '" class="produk" ><option value="0" selected> - </option>' + optionstr + "</select></td>";
		kolom += '<td><input type="text" name="tx2_' + rowCount + '" class="form-control"></td>';
		kolom += '<td><input type="text" name="tx3_' + rowCount + '" class="form-control"></td>';
		kolom += '<td><input type="text" name="tx4_' + rowCount + '" class="form-control text-right"></td>';
		kolom += '<td><input readonly type="text" name="tx5_' + rowCount + '" class="form-control  text-right"></td>';

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
		$(".pph").addClass('select');
		$('.select-search').select2();
		$('.select').select2({
			minimumResultsForSearch: Infinity
		})

	})
</script>

@endsection