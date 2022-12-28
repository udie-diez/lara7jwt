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
@if(!isset($data->invoiceid))
<style>
	.tindakan {
		pointer-events: none;
	}
</style>
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

					<a href="{{ route('createInvoice') }}" class="dropdown-item" title="Input Invoice Baru">Buat Invoice Baru </a>
					<a href="#" class="dropdown-item" title="">_______________________________</a>

					<a href="{{ route('printInvoice', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak Invoice"> Cetak Invoice</a>
					<a href="{{ route('printKwitansi', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak Kwitansi"> Cetak Kwitansi</a>
					<a href="{{ route('printSPB', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak SPB"> Cetak SPB</a>
					<a href="{{ route('printBA', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak Berita Acara"> Cetak Berita Acara</a>
					<a href="{{ route('printBAPPKontrak', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak BAPP Kontrak + BAP"> Cetak BAPP Kontrak + BAP </a>
					<a href="{{ route('printBAUTKontrak', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak BAUT Kontrak"> Cetak BAUT Kontrak </a>
					<a href="{{ route('printBAPPGSD', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak BASTPP (GSD) + BAUT"> Cetak BASTPP + BAUT (GSD) </a>
					<a href="#" class="dropdown-item" title="">_______________________________</a>
					<a href="{{ route('printTT', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak Tanda Terima"> Cetak Tanda Terima</a>
					<a href="{{ route('printSJ', $data->invoiceid  ?? 0) }}" target="_blank" class="dropdown-item tindakan" title="Cetak Surat Jalan"> Cetak Surat Jalan</a>

					<a hidden href="{{ route('createInvoice') }}" class="dropdown-item" title="Input Invoice Baru">Input Invoice Baru </a>
					<a hidden href="{{ route('invoice') }}" class="dropdown-item" title="Daftar Invoice">Daftar Invoice </a>
				</div>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12">
				<form method="POST" action="{{ route('storeProject') }}">
					<fieldset>

						@csrf
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Mitra / Perusahaan</label>
							<div class="col-sm-6">
								<select name="perusahaan" required class="select-search">
									<option value=""></option>
									@foreach($perusahaan as $r)
									<option value="{{$r->id}}" <?php

																if (($data->perusahaanid ?? session('perusahaanid')) == $r->id) echo 'selected';
																?>>{{$r->alias .' | ' .$r->unitkerja}}</option>
									@endforeach
								</select>
							</div>
							@if($kodeubah!=3)
							<label class="col-form-label col-sm-4 text-right">#INVOICE : <a href="{{ route('showInvoice',$data->invoiceid ?? '') }}">{{ $data->invoicenomor ?? '' }}</a></label>
							@endif
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Uraian Project</label>
							<div class="col-sm-6">
								<input type="hidden" name="id" id="projectid" value="{{ $data->id ?? session('id') }}">
								<textarea name="nama" placeholder="Nama/Uraian Project" cols="3" class="form-control">{{ $data->nama ?? session('nama') }}</textarea>
							</div>
						</div>
						<div style="display: none;" class="form-group row">
							<label class="col-form-label col-sm-2">Pemesanan (PO)</label>
							<div class="col-sm-3">
								<input type="text" name="no_po" class="form-control" placeholder="Nomor" value="{{ $data->no_po ?? '' }}">
							</div>
							<div class="col-sm-4">
								<input type="text" name="tgl_po" placeholder="Tanggal" class="form-control pickadate" value="{{ IndoTgl($data->tgl_po ?? old('tgl_po')) }}">
							</div>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Nota Pesanan</label>
							<div class="col-sm-3">
								<input type="text" name="no_spk" class="form-control" placeholder="Nomor" value="{{ $data->no_spk ?? session('no_spk') }}">
							</div>
							<div class="col-sm-3">
								<input type="text" name="tgl_spk" placeholder="Tanggal" class="form-control pickadate" value="{{ IndoTgl($data->tgl_spk ?? session('tgl_spk')) }}">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form-label col-sm-2">Paket Pekerjaan </label>
							<div class="col-sm-3">
								<select name="paket" required data-placeholder="Pilih" class="select">
									<option value=""></option>
									<option value='BARANG' <?php if (($data->paket ?? session('paket')) == 'BARANG') echo 'selected'; ?>>BARANG</option>
									<option value='JASA' <?php if (($data->paket ?? session('paket')) == 'JASA') echo 'selected'; ?>>JASA</option>
									<option value='BARANG/JASA' <?php if (($data->paket ?? session('paket')) == 'BARANG/JASA') echo 'selected'; ?>>BARANG/JASA</option>
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form-label col-sm-2">Nilai Project (Rp.)</label>
							<div class="col-sm-2">
								<input readonly type="text" name="nilai" id="nilai" class="form-control text-right" value="{{ Rupiah($data->nilai ?? session('nilai'),2) }}">
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form-label col-sm-2">Keuntungan (%)</label>
							<div class="col-sm-2">
								<input type="text" name="keuntungan" id="keuntungan" placeholder="mis: 20.5" class="form-control text-right" value="{{ Rupiah($data->keuntungan ?? session('keuntungan'),2) }}">
							</div>
							<label class="col-form-label col-sm-1">%</label>
						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Lama Pekerjaan</label>
							<div class="col-sm-2">
								<input type="text" required name="lamapekerjaan" id="lamapekerjaan" class="form-control text-right" value="{{ Rupiah($data->lamapekerjaan ?? session('lamapekerjaan')) }}">
							</div>
							<label class="col-form-label col-sm-1">hari</label>

						</div>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">Pemesan/User</label>
							<div class="col-sm-4">
								<select name="pemesan" id="pemesan" data-placeholder="Pilih" class="select-search">
									<option value=""></option>
									@if(isset($pemesan))
									@foreach($pemesan as $r)
									<option value="{{$r->id}}" <?php if (($data->pemesanid ?? session('pemesanid')) == $r->id) echo 'selected';
																?>>{{$r->nama . ' (' .$r->jabatan.')'}}</option>
									@endforeach
									@endif
								</select>
							</div>
						</div>

						<div class="form-group row">
							<label class="col-form-label col-sm-2">AM </label>
							<div class="col-sm-4">
								<select name="pic" id="pic" data-placeholder="Pilih" class="select-search">
									<option value="0">-</option>
									@foreach($pengelola as $r)
									<option value="{{$r->id}}" <?php if (($data->pic ?? session('pic')) == $r->id) echo 'selected';
																?>>{{$r->nama}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<style>
							.disable {
								pointer-events: none;
								cursor: default;
							}
						</style>
						<div class="form-group row">
							<label class="col-form-label col-sm-2">AM Pelaksana</label>
							<div class="col-sm-2">
								<a id="btnAM" href='#' value="{{ route('createAM', $data->id ?? 0) }}" onclick="" class="btn  btn-outline-slate text-slate modalMd <?php if ($kodeubah != 1 && $kodeubah != 3) echo 'disabled'; ?>" title="Tambahkan Nama AM Pelaksana Project" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-plus22"></i>Tambah AM</a>
							</div>
						</div>
						@if(isset($am) && count($am) > 0)
						<div class="form-group row">
							<label class="col-form-label col-sm-2"></label>
							<div class="col-sm-5">
								<table class="table table-bordered basicxx">
									<thead class="text-center">
										<tr>
											<th width="50px">No.</th>
											<th hidden>id</th>
											<th>Nama AM</th>
											<th width="120px">Pengelolaan (%)</th>
											@if($kodeubah==1 || $kodeubah==3)
											<th width="50px">Aksi</th>
											@endif
										</tr>
									</thead>
									<tbody>
										@php $jumlah=0 @endphp
										@foreach($am as $row)
										@php $jumlah += $row->porsi @endphp

										<tr>
											<td class="text-center">{{ $loop->iteration }}</td>
											<td hidden>{{ route('showAM', $row->id) }}</td>
											<td>{{ $row->nama }}</td>
											<td class="text-center">{{ $row->porsi }}</td>
											@if($kodeubah==1 || $kodeubah==3)
											<td class="text-center">
												<div class="list-icons">
													<a href="#" id="btn-edit" class="list-icons-item text-info-600 modalMd" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
													<a href="{{ action('ProjectCont@destroyAM',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
												</div>
											</td>
											@endif
										</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<th></th>
											<th>Jumlah</th>
											<th class="text-center">{{ Rupiah_no($jumlah,2) }}</th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
						@endif

					</fieldset>

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

					<div class="form-group row mt-5">
						<div class="col-sm-9">

							<div class="list-icons" style="float: right;">
								<a href="{{ $kodeubah==1 ? route('showProject',$data->id ?? 0 ) : route('project') }}" class="btn btn-outline-success btn-sm" title="Daftar Project">{{ $kodeubah==1 ? 'BATAL' : 'KEMBALI' }}</a>

								@if($kodeubah==1 || $kodeubah==3)
								<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm btn-submit" title="Simpan Data">Simpan</button>
								@else
								<a href="{{ route('editProject',$data->id ?? '') }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
								@endif
							</div>
						</div>
					</div>
				</form>
				<hr>

			</div>
		</div>
	</div>
	<div class="card-header  header-elements-inline pt-0">
		<h6 class="card-title">DAFTAR RINCIAN BARANG/JASA</h6>
		<div class="header-elements">
			<div class="list-icons">
				@if($kodeubah==1)
				<a hidden href="#" value="{{ action('ProjectCont@createItem') }}" class="btn btn-outline-primary btn-sm modalMd" title="Item Barang/Jasa" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-plus22"></i>Barang/Jasa</a>
				@endif
			</div>
		</div>
	</div>
	<div class="card-body mt-3">
		@if ($kodeubah==1 || $kodeubah==3)

		<div class="form-group row">
			<label class="col-form-label pr-3 ml-2">Nama Kolom :</label>
			<div class="col-sm-10">
				<input type="text" id="kolom" class="form-control tokenfield" placeholder="+ Kolom" value="{{ isset($item) &&  count($item) > 0 ? '' : 'No,Nama/Uraian,Qty,Satuan,Harga,Jumlah'}}" data-fouc>

			</div>
		</div>
 
		<div class="form-group row">
			<label class="col-form-label pr-3 ml-2">Urutan Kolom untuk Jumlah/Total :</label>
			<div class="col-sm-1">
				<input type="text" id="kolomjumlah" class="form-control" placeholder="mis: 6" value="6">

			</div>
		</div>
		<div class="form-group row">
			<label class="col-form-label pr-3 ml-2">Keuntungan mitra : </label>
			<div style="width: 20px!important;" class="pt-2">
				<input type="checkbox" class="form-check-input-styled" name="ck_keuntungan" id="ck_keuntungan" <?php if ($data->cbkeuntungan ?? '') echo "checked" ?>>
			</div>
			<div class="col-sm-1">
				<label class="col-form-label">Tampilkan</label>
			</div>

		</div>

		<div class="form-group row">
			<label class="col-form-label"></label>
			<div class="list-icon col-sm-5 mt-3">
				<button type="button" id="btn-tabel" class="btn btn-outline-info btn-sm">Buat Tabel</button>
				<button type="button" id="btn-additem" class="btn btn-outline-info btn-sm">+ Baris Baru</button>
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
		</style>
		<form action="{{ route('updateItem') }}" method="POST">
			@csrf
			<div class="form-group row">
				<div id="tabel-item" class="ml-2">
					@if (isset($item) && count($item) > 0)
					<table id="tabel-item" class="table" width='1000px'>
						<thead>
							<tr>
								<th>No.</th>

								<?php
								foreach ($item as $row) {
									$namakol = 'No';
									$kolomjumlah = $row->kolomjumlah;

									for ($x = 1; $x < 9; $x++) {
										$namakolom = 'kolom' . $x . '_nama';
										$align = ($x == ($kolomjumlah - 1)) ? 'text-right' : '';

										if ($row->$namakolom != '') {
											echo '<th class="' . $align . '"><div>' . $row->$namakolom . '</div></th>';
											$namakol .= ',' . $row->$namakolom;
										} else {
											break;
										}
									}
									echo '<th width="100px">Pajak PPh </th>';
									$namakol .= ',Pajak PPh';
									break;
								}
								?>
								<th>#</th>
							</tr>
						</thead>
						<tbody id="bbody">
							<?php
							$no = 1;
							$iid = '';
							$subtotal = 0;

							foreach ($item as $row) {
								$pid = $row->projectid;
								$kolomjumlah = $row->kolomjumlah;
								$iid .= $row->id . ',';
								echo '<td class="text-center" width="50px">' . $no++ . '</td>';

								for ($i = 1; $i < $x; $i++) {
									$isikolom = 'kolom' . $i . '_isi';
									$align = ($i == ($kolomjumlah - 1)) ? 'text-right' : '';
									echo '<td ><div><input type="text" name="tx' . $i . '_' . ($no - 1) . '"  class="form-control ' . $align . '"  value="' . Rupiah($row->$isikolom, ($i == ($kolomjumlah - 1)) ? 2 : 0) . '"></div></td>';

									if ($i == ($kolomjumlah - 1)) {
										$subtotal += $row->$isikolom;
									}
								}
								//pajak
								$optionstr = '';
								foreach ($pajak as $p) {
									$pajakid = $row->pajakid ?? 0;
									$selected = ($pajakid > 0) && ($pajakid == $p->id) ? 'selected' : '';
									$optionstr .= "<option value='" . $p->id . "' " . $selected . " >" . $p->nama . " (" . $p->nilai . "%)</option>";
								}
								echo '<td><div><select class="select" name="pph' . ($no - 1) . '"><option value="0"> - </option>' . $optionstr . "</select><div></td>";

							?>
								@if ($kodeubah==1 || $kodeubah==3)

								<td><a href="{{ action('ProjectCont@destroyItem',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a></td>
								@endif
								</tr>
							<?php
							}
							$no--;
							?>
						</tbody>
						<tfoot class="font-weight-bold">
							<?php if ($data->cbkeuntungan ?? '') { ?>
								<tr>
									<td colspan="<?= @$kolomjumlah - 1 ?>" class="text-right">JUMLAH</td>
									<td class="text-right"><?= @Rupiah($subtotal, 2) ?></td>
									<?php
									$keuntungan = $data->keuntungan;
									$keuntungan = $subtotal * ($keuntungan / 100);
									$subtotal += $keuntungan;
									?>
								</tr>
								<tr>
									<td colspan="<?= @$kolomjumlah - 1 ?>" class="text-right">KEUNTUNGAN MITRA</td>
									<td class="text-right"><?= @Rupiah($keuntungan, 2) ?></td>
								</tr>
							<?php } ?>

							<tr>
								<td colspan="{{$kolomjumlah-1}}" class="text-right">SUB TOTAL</td>
								<td class="text-right">{{Rupiah($subtotal,2)}}</td>
							</tr>
							<tr>
								<td colspan="{{$kolomjumlah-1}}" class="text-right">PPN {{$data->ppnpersen ?? 10}}%</td>
								<td class="text-right">{{Rupiah($data->ppnnilai ?? 0,2)}}</td>
							</tr>
							<tr>
								<td colspan="{{$kolomjumlah-1}}" class="text-right">TOTAL</td>
								<td class="text-right">{{Rupiah(($data->ppnnilai ?? 0) + $subtotal,2)}}</td>
							</tr>
							
						</tfoot>
					</table>
					@endif
				</div>
			</div>

			<input type="hidden" name="txkolomjumlah" id="txkolomjumlah" value="{{$kolomjumlah ?? 0}}">
			<input type="hidden" name="txkolom" id="txkolom" value="{{ ($i ?? 0) + 1}}">
			<input type="hidden" name="txbaris" id="txbaris" value="{{ $no ?? ''}}">
			<input type="hidden" name="txprojectid" id="txprojectid" value="{{ $pid ?? '' }}">
			<input type="hidden" name="txid" value="{{ $iid ?? '' }}">
			<input type="hidden" name="txnamakolom" id="txnamakolom" value="{{ $namakol ?? '' }}">
			<input type="hidden" name="cb_keuntungan" id="cb_keuntungan" value="{{ ($data->cbkeuntungan ?? '')==1 ? 'true' : false  }}">
			@if ($kodeubah==1 || $kodeubah==3)
			<button type="submit" style="display: <?php if (!isset($pid)) echo 'none' ?>;" id="btn-simpanitem" value="{{ action('ProjectCont@updateItem') }}" class="btn btn-outline-primary btn-sm" title="Simpan Item Barang/Jasa" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')"></i> Simpan</button>
			@endif
			<a hidden href="#" style="display: none;" id="btn-item" value="{{ action('ProjectCont@createItem') }}" class="btn btn-outline-primary btn-sm modalMd" title="Item Barang/Jasa" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-plus22"></i> Item Barang/Jasa</a>

		</form>

	</div>


</div>
<!-- /basic datatable -->

<script src="{{ url('/') }}/global_assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script src="{{ url('/')}}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>

<script type="text/javascript">
	$(document).ready(function() {

		$('.form-check-input-styled').uniform();

		// $('form').on('submit', function() {
		$.blockUI({
			message: '<i class="icon-spinner4 spinner"></i>',
			timeout: 2000, //unblock after 2 seconds
			overlayCSS: {
				backgroundColor: '#1b2024',
				opacity: 0.8,
				zIndex: 1200,
				cursor: 'wait'
			},
			css: {
				border: 0,
				color: '#fff',
				padding: 0,
				zIndex: 1201,
				backgroundColor: 'transparent'
			}
		});
		// });
		$(document).ajaxStop($.unblockUI);

		// $("#iform :input").attr('readonly',true);

		var projectid = '<?php echo $data->id ?? 0 ?>';
		$('.tokenfield').tokenfield();

		var tx_nilai = document.getElementById('kolomjumlah');

		tx_nilai.addEventListener('keyup', function(e) {
			$('#txkolomjumlah').val(this.value);
		});

		var nilai = document.getElementById('nilai');
		nilai.addEventListener('keyup', function(e) {
			nilai.value = formatRupiah(this.value);
		});

		var lamapekerjaan = document.getElementById('lamapekerjaan');
		lamapekerjaan.addEventListener('keyup', function(e) {
			$('#lamapekerjaan').val(this.value);
		});

		var ck = document.getElementById('ck_keuntungan');
		ck.addEventListener('click', function(e) {
			$('#cb_keuntungan').val(this.checked);
		});

		$('#btnAM').on('click', function() {
			var projectidx = '<?php echo $data->id ?? 0 ?>';
			if (projectid > 0) {
				$('#projectidAM').val(projectidx);
			} else {
				alert('Untuk menambahkan AM Pelaksana, Silahkan menyimpan Project ini terlebih dahulu ! ');
				return false;
			}
		})

		$('#btn-tabel').on('click', function() {
			var kolomstr = $('#kolom').val();
			var kolomjumlah = $('#kolomjumlah').val();
			var kolomarr = kolomstr.split(',');
			var kolom = "";
			var content = "<table border='1' cellpadding='5px' width='800px'><thead class='text-center'><tr>"
			for (i = 0; i < kolomarr.length; i++) {
				content += '<th>' + kolomarr[i] + '</th>';
				// if(i==0){
				// 	kolom += '<td class="text-center">1.</td>';
				// }else{

				// 	kolom += '<td><input type="text" id="tx'+i+'" class="form-control"></td>';
				// }
			}
			content += "<th>Pajak PPh</th>"
			content += "</tr></thead><tbody id='bbody'></tbody></table>"

			kolomstr += ",Pajak PPh";

			$('#tabel-item').html('');
			$('#tabel-item').append(content);
			$('#txnamakolom').val(kolomstr);
			$('#txkolomjumlah').val(kolomjumlah);
			$('#txprojectid').val('<?php echo $data->id ?? 0 ?>');
			$('#btn-simpanitem').show();
			$('#btn-additem').trigger('click');
		})

		var namakol = '<?php echo str_replace(',Pajak PPh', '', $namakol ?? '') ?>'
		if (namakol != '') {
			$('#kolom').val('<?php echo str_replace(',Pajak PPh', '', $namakol ?? '') ?>');
		}

		var kolomjml = '<?php echo $kolomjumlah ?? 6 ?>';
		$('#kolomjumlah').val(kolomjml);

		$('#btn-additem').on('click', function() {
			var kolomstr = $('#kolom').val();
			var kolomarr = kolomstr.split(',');
			var kolom = '';
			var rowCount = $('#bbody tr').length + 1;
			var kolomjumlah = $('#kolomjumlah').val();

			for (i = 0; i < kolomarr.length; i++) {
				if (i == 0) {
					kolom += '<td class="text-center" width="50px">' + rowCount + '</td>';
				} else {
					kolom += '<td><input type="text" name="tx' + i + '_' + rowCount + '" class="form-control"></td>';
				}
			}
			//pajak
			var optionstr = '';
			var pajak = '<?php echo  $pajak ?? '' ?>';
			pajak = JSON.parse(pajak);
			for (var i = 0; i < pajak.length; i++) {
				optionstr += "<option value=" + pajak[i]['id'] + ">" + pajak[i]['nama'] + " (" + pajak[i]['nilai'] + "%)</option>";
			}
			kolom += '<td><select name="pph' + rowCount + '" class="select" ><option value="0" selected> - </option>' + optionstr + "</select></td>";

			$('#bbody').append('<tr>' + kolom + '</tr>');
			$('#txkolom').val(kolomarr.length + 1);
			$('#txbaris').val(rowCount);

		})

	})
</script>

@endsection