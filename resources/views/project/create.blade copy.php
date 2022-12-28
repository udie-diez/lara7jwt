@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<div class="card">
	<div class="card-header  header-elements-inline">
		<h5 class="card-title">{{$tag['judul']}}</h5>
		<div class="header-elements">
			<div class="list-icons">
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="row">
			<div class="col-sm-12">
				<form method="POST" action="{{ route('storeProject') }}">
					@csrf
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Perusahaan Pemesan</label>
						<div class="col-sm-7">
							<select name="perusahaan" required class="select">
								<option value=""></option>
								@foreach($perusahaan as $r)
								<option value="{{$r->id}}" <?php if (($data->perusahaanid ?? old('perusahaan')) == $r->id) echo 'selected';
															?>>{{$r->nama}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Uraian Project</label>
						<div class="col-sm-7">
							<input type="hidden" name="id" id="projectid" value="{{ $data->id ?? old('id') }}">
							<textarea name="nama" placeholder="Nama/Uraian Project" cols="3" class="form-control">{{ $data->nama ?? old('nama') }}</textarea>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Pemesanan (PO)</label>
						<div class="col-sm-3">
							<input type="text" name="no_po" class="form-control" placeholder="Nomor" value="{{ $data->no_po ?? old('no_po') }}">
						</div>
						<div class="col-sm-4">
							<input type="text" name="tgl_po" placeholder="Tanggal" class="form-control pickadate" value="{{ IndoTgl($data->tgl_po ?? old('tgl_po')) }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">SPK</label>
						<div class="col-sm-3">
							<input type="text" name="no_spk" class="form-control" placeholder="Nomor" value="{{ $data->no_spk ?? old('no_spk') }}">
						</div>
						<div class="col-sm-4">
							<input type="text" name="tgl_spk" placeholder="Tanggal" class="form-control pickadate" value="{{ IndoTgl($data->tgl_spk ?? old('tgl_spk')) }}">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-2">Paket Pekerjaan </label>
						<div class="col-sm-3">
							<select name="paket" required data-placeholder="Pilih" class="select">
								<option value=""></option>
								<option value='BARANG' <?php if (isset($data->paket)) {
															if ($data->paket == 'BARANG') echo 'selected';
														} ?>>BARANG</option>
								<option value='JASA' <?php if (isset($data->paket)) {
															if ($data->paket == 'JASA') echo 'selected';
														} ?>>JASA</option>
								<option value='BARANG/JASA' <?php if (isset($data->paket)) {
																if ($data->paket == 'BARANG/JASA') echo 'selected';
															} ?>>BARANG/JASA</option>
							</select>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Keuntungan (%)</label>
						<div class="col-sm-1">
							<input type="text" name="keuntungan" id="keuntungan" placeholder="Keuntungan (%)" class="form-control text-right" value="{{ Rupiah($data->keuntungan ?? old('keuntungan'),2) }}">
						</div>
						<label class="col-form-label col-sm-1">%</label>

					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Pemesan</label>
						<div class="col-sm-3">
							<input type="text" name="pemesan" placeholder="Nama" class="form-control" value="{{ $data->pemesan ?? old('pemesan') }}">
						</div>
						<div class="col-sm-4">
							<input type="text" name="jabatan" placeholder="Jabatan" class="form-control" value="{{ $data->jabatan ?? old('jabatan') }}">
						</div>
					</div>

					<div class="form-group row">
						<label class="col-form-label col-sm-2">AM </label>
						<div class="col-sm-3">
							<select name="pic" id="pic" data-placeholder="Pilih" class="select-search">
								<option value="0">-</option>
								@foreach($pengelola as $r)
								<option value="{{$r->id}}" <?php if (($data->pic ?? old('pic')) == $r->id) echo 'selected';
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
										<th width="50px">Aksi</th>
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
										<td class="text-center">
											@if($kodeubah==1 || $kodeubah==3)
											<div class="list-icons">
												<a href="#" id="btn-edit" class="list-icons-item text-info-600 modalMd" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
												<a href="{{ action('ProjectCont@destroyAM',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
											</div>
											@endif
										</td>
									</tr>
									@endforeach
								</tbody>
								<tfoot>
									<tr>
										<th></th>
										<th>Jumlah</th>
										<th class="text-center">{{ Rupiah_no($jumlah,2) }}</th>
										<th></th>
									</tr>
								</tfoot>
							</table>
						</div>
					</div>
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

					<div class="form-group row mt-3">
						<div class="col-sm-9">

							<div class="list-icons" style="float: right;">
								<a type="button" href="{{ $kodeubah==1 ? route('showProject',$data->id ) : route('project') }}" class="btn btn-outline-success btn-sm" title="Daftar Project">Kembali</a>

								@if($kodeubah==1 || $kodeubah==3)
								<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
								@else
								<a type="button" href="{{ route('editProject',$data->id ?? '') }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
								@endif
							</div>
						</div>
				</form>
			</div>
		</div>
	</div>
	<hr>
	<div class="card-header  header-elements-inline">
		<h6 class="card-title">DAFTAR RINCIAN BARANG/JASA</h6>
		<div class="header-elements">
			<div class="list-icons">
				@if($kodeubah==1)
				<a href="#" value="{{ action('ProjectCont@createItem') }}" class="btn btn-outline-primary btn-sm modalMd" title="Item Barang/Jasa" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-plus22"></i>Barang/Jasa</a>
				@endif
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="form-group row">
			<label class="col-form-label">Nama Kolom :</label>
			<div class="col-sm-7">
				<input type="text" id="kolom" class="form-control tokenfield" placeholder="+ Kolom" value="No,Nama/Uraian,Qty,Satuan,Harga,Jumlah" data-fouc>

			</div>
			<div class="col-sm-2">
				<button type="button" id="btn-tabel" class="btn btn-outline-slate btn-sm">+ Tabel</button>
			</div>
		</div>
		<form action="{{ route('updateItem') }}" method="POST">
			@csrf
			<div class="form-group row">
				<div id="tabel-item" class="ml-2"></div>

				<div class="col-sm-2">
					<button type="button" id="btn-additem" class="btn btn-outline-slate btn-sm">+ Item Barang/Jasa</button>
				</div>
			</div>

			<input type="hidden" name="txkolom" id="txkolom">
			<input type="hidden" name="txbaris" id="txbaris">
			<input type="hidden" name="txprojectid" id="txprojectid">
			<input type="hidden" name="txnamakolom" id="txnamakolom">
			<button type="submit"  style="display: none;" id="btn-simpanitem" value="{{ action('ProjectCont@updateItem') }}" class="btn btn-outline-primary btn-sm" title="Simpan Item Barang/Jasa" onclick="return confirm('Anda yakin ingin menyimpan data ini ?')"></i> Simpan</button>

				<a hidden href="#" style="display: none;" id="btn-item" value="{{ action('ProjectCont@createItem') }}" class="btn btn-outline-primary btn-sm modalMd" title="Item Barang/Jasa" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-plus22"></i> Item Barang/Jasa</a>

		</form>
	</div>

	<div class="card-body">
		<table class="table basicxx">
			<thead>
				<tr>
					<th>No.</th>
					<th hidden>ID</th>
					<th>Nama Barang/Jasa</th>
					<th class="text-right">Qty</th>
					<th class="text-center">Satuan</th>
					<th class="text-right">Harga</th>
					<th class="text-right">Jumlah</th>
					<th class="text-center">Ket</th>
					<th <?php if ($kodeubah == 0) echo 'hidden' ?> class="text-right">Aksi</th>
				</tr>
			</thead>
			<tbody>
				@php $subtotal = $ppn = $total = 0 @endphp

				@if(isset($item) && count($item)>0) @foreach($item as $row)
				@php
				$subtotal += $row->total;
				$ppn += $row->ppn;
				$total = $subtotal + $ppn;
				@endphp

				<tr>
					<td>{{ $loop->iteration }}.</td>
					<td hidden>{{ url('project/item/').'/'.$row->id }}</td>
					<td>{{ $row->nama}}</td>
					<td class="text-right">{{ Rupiah($row->jumlah,2)}}</td>
					<td class="text-center">{{ $row->satuan}}</td>
					<td class="text-right">{{ Rupiah($row->harga,2)}}</td>
					<td class="text-right">{{ Rupiah($row->total,2)}}</td>
					<td class="text-center">{{ $row->pajak}}</td>
					<td <?php if ($kodeubah == 0) echo 'hidden' ?> class="text-right">
						<div class="list-icons">
							<a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Ubah Data" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
							<a href="{{ action('ProjectCont@destroyItem',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
						</div>
					</td>
				</tr>
				@endforeach
				@endif
			</tbody>
			<tfoot style="font-weight: bold;">
				<tr>
					<td colspan="5" class="text-right">Subtotal</td>
					<td class="text-right">{{ Rupiah($subtotal,2) }}</td>
					<td></td>
					<td></td>

				</tr>
				<tr>
					<td colspan="5" class="text-right">Pajak PPN (10%)</td>
					<td class="text-right">{{ Rupiah($ppn,2) }}</td>
					<td></td>
					<td></td>

				</tr>
				<tr>
					<td colspan="5" class="text-right">Total</td>
					<td class="text-right">{{ Rupiah($total,2) }}</td>
					<td></td>
					<td></td>
				</tr>
			</tfoot>
		</table>
	</div>

</div>
</div>
<!-- /basic datatable -->

<script src="{{ url('/') }}/global_assets/js/plugins/forms/tags/tokenfield.min.js"></script>
<script type="text/javascript">
	$(document).ready(function() {

		var projectid = '<?php echo $data->id ?? 0 ?>';
		$('.tokenfield').tokenfield();

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
			content += "</tr></thead><tbody></tbody></table>"
			$('#tabel-item').html('');
			$('#tabel-item').append(content);
			$('#txnamakolom').val(kolomstr);
			$('#txprojectid').val('<?php echo $data->id ?? 0 ?>');
			$('#btn-simpanitem').show();
		})

		$('#btn-additem').on('click', function() {
			var kolomstr = $('#kolom').val();
			var kolomarr = kolomstr.split(',');
			var kolom = '';
			var rowCount = $('#tabel-item tr').length;
			for (i = 0; i < kolomarr.length; i++) {
				if (i == 0) {
					kolom += '<td class="text-center" width="50px">' + rowCount + '</td>';
				} else {
					kolom += '<td><input type="text" name="tx' + i + '_' + rowCount + '" class="form-control"></td>';
				}
			}
			$('#tabel-item tr:last').after('<tr>' + kolom + '</tr>');
			$('#txkolom').val(kolomarr.length);
			$('#txbaris').val(rowCount);
			

		})

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

@endsection