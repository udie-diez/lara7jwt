@extends('layouts.home')

@section('maincontent')

@include('layouts.mylib')
<style>
	.table-ang {
		font-size: small;
		width: 100%;
	}
</style>
<div class="card">
	<div class="card-header  header-elements-inline">
		<h5 class="card-title">{{$tag['judul']}}</h5>
		<?php
		$statusi = ['', 'BELUM DIBAYAR', 'BAYAR SEBAGIAN', 'LUNAS', 'BATAL'];
		?>

		<span class="mr-1 badge  bg-info-300 pb-0">
			<h6>{{ $statusi[$invoice->status ?? 0] }}</h6>
		</span>

		<div class="header-elements">
			<!-- <div class="list-icon">
				<a href="{{ route('printInvoice', $invoice->id ?? 0) }}" class="btn btn-outline-success btn-sm" title="Cetak Invoice"><i class="icon-printer"></i> Cetak</a>
				<a href="{{ route('invoice') }}" class="btn btn-outline-info btn-sm">Daftar Invoice </a>
			</div> -->
			<div class="btn-group">
				<button type="button" class="btn btn-outline-info btn-sm dropdown-toggle" data-toggle="dropdown">Tindakan</button>
				<div class="dropdown-menu dropdown-menu-right">
					<a href="{{ route('createPembayaran', $invoice->id ?? 0) }}" class="dropdown-item" title="Terima Pembayaran">Terima Pembayaran</a>
					<a href="{{ route('printInvoice', $invoice->id ?? 0) }}" class="dropdown-item" title="Cetak Invoice"> Cetak Invoice</a>
					<a href="{{ route('printSPB', $invoice->id ?? 0) }}" class="dropdown-item" title="Cetak SPB"> Cetak SPB</a>
					<a href="{{ route('printTT', $invoice->id ?? 0) }}" class="dropdown-item" title="Cetak Invoice"> Cetak Tanda Terima</a>
					<a href="{{ route('printSJ', $invoice->id ?? 0) }}" class="dropdown-item" title="Cetak Invoice"> Cetak Surat Jalan</a>
					<a href="{{ route('printBA', $invoice->id ?? 0) }}" class="dropdown-item" title="Cetak Invoice"> Cetak Berita Acara</a>
					<a href="{{ route('createInvoice') }}" class="dropdown-item" title="Input Invoice Baru">Input Invoice Baru </a>
					<a href="{{ route('invoice') }}" class="dropdown-item" title="Daftar Invoice">Daftar Invoice </a>
				</div>
			</div>
		</div>
	</div>

	<div class="card-body">
		@if(isset($project))
		<form method="POST" action="{{ route('infoProject') }}">
			<div class="form-group row">
				<label class="col-form-label col-sm-1">PROJECT : </label>
				@csrf
				<div class="col-sm-7">
					<select name="f_project" id="f_project" data-placeholder="Tentukan Project" class="select-search">
						<option value=""></option>
						@foreach($project as $r)
						<option value="{{$r->id}}" <?php if (isset($projectid_f)) {
														if ($projectid_f == $r->id) echo 'selected';
													} ?>>{{$r->no_po ? 'PO: '.$r->no_po .' || '.$r->nama : 'SPK: '. $r->no_spk .' || '.$r->nama}}</option>

						@endforeach
					</select>
				</div>
				<div class="col-sm-1">
					<button type="submit" class="btn btn-outline-info btn-sm">Tampilkan</button>
				</div>
			</div>
		</form>
		@endif
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Perusahaan</label>
					<label class="col-form-label col-sm-10">{{ $data->perusahaan ?? '' }}</label>
				</div>
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Pesanan</label>
					<label class="col-form-label col-sm-10">{{ $data->nama ?? '' }}</label>
				</div>
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Nomor</label>
					<label class="col-form-label col-sm-10">{{ $data->no_po ?? '' }}</label>
				</div>
				<div class="form-group row mb-0">
					<label class="col-form-label col-sm-2">Tanggal</label>
					<label class="col-form-label col-sm-10">{{ date('d/m/Y', strtotime($data->tgl_po ?? ''))}}</label>
				</div>
			</div>
		</div>
		<hr>
		<div class="row">

			<div class="col-sm-12">
				<form method="POST" action="{{ route('updateInvoice') }}">
					@csrf
					
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">No. Invoice</label>
						<div class="col-sm-7">
							<input type="hidden" name="id" value="{{ $invoice->id ?? '' }}">
							<input type="hidden" name="projectid" value="{{ $data->id ?? '' }}">
							<input type="text" class="form-control" required name="nomor" value="{{ $invoice->nomor ?? old('nomor') }}">
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Tgl.Invoice</label>
						<div class="col-sm-7">
							<input type="text" class="form-control pickadate" required name="tanggal" value="<?php if (isset($invoice->tanggal)) echo date('d/m/Y', strtotime($invoice->tanggal)) ?>">
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Tgl.Jatuh Tempo</label>
						<div class="col-sm-7">
							<input type="text" class="form-control pickadate" required name="tanggal_jatuhtempo" value="<?php if (isset($invoice->tgl_jatuhtempo)) echo date('d/m/Y', strtotime($invoice->tgl_jatuhtempo)) ?>">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Penandatangan</label>
						<div class="col-sm-7">
							<select name="pengurus" id="pengurus" required data-placeholder="Pilih" class="select">
								<option value=""></option>

								@if(isset($pengurus)) @foreach($pengurus as $r)
								<option value="{{$r->id}}" <?php if (isset($invoice->pegawaiid)) {
																if ($invoice->pegawaiid == $r->id) echo 'selected';
															} ?>>{{$r->nama}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>

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
							@php Session::forget('sukses') @endphp

							@endif
							@if ($message = Session::get('warning'))
							<div class="alert alert-danger alert-block">
								<button type="button" class="close" data-dismiss="alert">×</button>
								<strong>{{ $message }}</strong>
							</div>
							@php Session::forget('warning') @endphp

							@endif
						</div>
					</div>


			</div>
		</div>

		<div style="width: 90%;">
			<table style="display: <?= $jenisinvoice == 2 ? 'none' : '' ?>;" class="table mt-3 basicxxx" id="table_1" style="font-size: small;">
				<thead class="bg-slate-600">
					<tr>
						<th class="export">No.</th>
						<th>Nama Barang/Jasa</th>
						<th class="text-right">Vol</th>
						<th class="text-center">Satuan</th>
						<th class="text-right">Harga Satuan</th>
						<th class="text-center">Pajak</th>
						<th width="150px" class="text-right">Jml Harga</th>
					</tr>
				</thead>
				<tbody>
					@php $subtotal = $ppn = $total = 0 @endphp

					@if(isset($item)) @foreach($item as $row)
					@php
					$subtotal += $row->total;
					$ppn += $row->ppn;
					$total = $subtotal + $ppn;
					@endphp
					<tr>
						<td>{{ $loop->iteration }}.</td>
						<td> {{ $row->nama}}</td>
						<td class="text-right">{{ Rupiah($row->jumlah,2)}}</td>
						<td class="text-center">{{ $row->satuan}}</td>
						<td class="text-right">{{ Rupiah($row->harga,2)}}</td>
						<td class="text-center">{{ $row->ppn > 0 ? 'PPN' : ''}}</td>
						<td class="text-right">{{ Rupiah($row->total,2)}}</td>

					</tr>
					@endforeach
					@endif
				</tbody>
				<tfoot style="font-weight: bold;">
					<tr>
						<td colspan="6" class="text-right">Subtotal</td>
						<td class="text-right">{{ Rupiah($subtotal,2) }}</td>

					</tr>
					<tr>
						<td colspan="6" class="text-right">PPN (10%)</td>
						<td class="text-right">{{ Rupiah($ppn,2) }}</td>

					</tr>
					<tr style="font-size: medium;" class="text-success">
						<td colspan="6" class="text-right">Total</td>
						<td class="text-right">{{ Rupiah($total,2) }}</td>
						<input type="hidden" name="td_total" value="{{$total}}">
					</tr>
					@if($bayarinvoice!='null' && $bayarinvoice>0)
					<tr>
						<td colspan="6" class="text-right">Potongan/Pembayaran</td>
						<td class="text-right">{{ Rupiah($bayarinvoice,2) }}</td>
						<td><a href="#tabel_pembayaran" type="button"  class="btn_pembayaran" title="Daftar Pembayaran"><i class='icon-clipboard3'></i> </a></td>

					</tr>
					<tr style="font-size: medium;" class="text-success">
						<td colspan="6" class="text-right">Total Tagihan</td>
						<td class="text-right">{{ number_format($total - $bayarinvoice,2,',','.') }}</td>
						<input type="hidden" name="td_potongan" value="{{ $bayarinvoice }}">
						<input type="hidden" name="td_sisapembayaran" value="{{ $total - $bayarinvoice }}">
					</tr>
					@endif
				</tfoot>
			</table>
			<table style="display: <?= $jenisinvoice == 1 ? 'none' : '' ?>;" class="table mt-3 basicxxx mb-3" id="table_2" style="font-size: small;">
				<thead>
					<tr>
						<th class="export">No.</th>
						<th>U r a i a n</th>
						<th>Unit Kerja</th>
						<th class="text-right">Nilai (Rp.)</th>

					</tr>
				</thead>
				<tbody>
					@if(isset($item)) @foreach($item as $row)
					<tr>
						<td>{{ $loop->iteration }}.</td>
						<td id='td_uangmuka'> Uang Muka : {{ $data->nama }}</td>
						<td id='td_perusahaan'>{{ $data->perusahaan }} </td>
						<td class="text-right"><input type="text" class="text-right" name="td_nilai" id="td_nilai" value="{{ number_format($invoice->total ?? 0, 0,',','.') }}"> </td>
					</tr>
					@endforeach
					@endif

				</tbody>
				<tbody style="font-weight: bold;">
					<tr style="font-size: medium;" class="text-success">
						<td colspan="3" class="text-right">Total</td>
						<td class="text-right">{{ number_format($invoice->total ?? 0, 0,',','.') }}</td>
					</tr>
					@if($bayarinvoice!='null' && $bayarinvoice>0)
					<tr>
						<td colspan="3" class="text-right">Potongan/Pembayaran</td>
						<td class="text-right">{{ number_format($bayarinvoice,0,',','.') }}</td>
						<td><a type="button" href="#tabel_pembayaran" class="btn_pembayaran" title="Daftar Pembayaran"><i class='icon-clipboard3'></i> </a></td>
					</tr>
					<tr>
						<td colspan="3" class="text-right">Total</td>
						<td class="text-right">{{ number_format($invoice->total ?? 0 - $bayarinvoice,0,',','.') }}</td>
					</tr>
					@endif
				</tbody>
			</table>

			<div class="form-group row mt-3">
				<div class="col-sm-12 text-right">

					<div class="list-icon">
					<a href="3" value="{{ route('batalInvoice',$invoice->id ?? '' ) }}" type="button" class="btn btn-outline-danger btn-sm modalMd" title="Pembatalan Invoice" style="float: left;" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">Pembatalan Invoice</a>
						<a href="{{ $kodeubah==1 ? route('showInvoice',$invoice->id ) : route('invoice') }}" class="btn btn-outline-warning btn-sm" title="Batal / Kembali"> {{ $kodeubah==1 ? 'BATAL' : 'KEMBALI' }}</a>
						@if($kodeubah==1 || $kodeubah==3)
						<button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data Invoice ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
						@else
						<a href="{{ route('editInvoice',$invoice->id ?? '' ) }}" type="button" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
						@endif
					</div>
				</div>
			</div>
			</form>
		</div>
	
		<div class="card mt-3" style="width: 90%;display: none;" id="tabel_pembayaran">
			<div class="card-body">

				<h6>Pembayaran</h6>
				<table class="table">
					<thead class="bg-slate-600">
						<tr>
							<th>No.</th>
							<th>Pembayaran</th>
							<th>Tanggal</th>
							<th>Invoice</th>
							<th class="text-right">Jumlah(Rp)</th>
						</tr>
					</thead>
					<tbody>
						@if(isset($daftarpembayaran))
						@foreach($daftarpembayaran as $row)
						<tr>
							<td>{{ $loop->iteration}}</td>
							<td>#{{ $row->nomor}}</td>
							<td>{{ IndoTgl($row->tanggal)}}</td>
							<td>{{ $row->jenis==2 ? 'Uang Muka ' : $row->nomorinv }}</td>
							<td class="text-right">{{ Rupiah($row->nilai) }}</td>
						</tr>
						@endforeach
						@endif
					</tbody>
				</table>
			</div>
		</div>
															
	</div>

	<script type="text/javascript">
		$(document).ready(function() {

			$('.pickadate').pickadate({
				format: 'dd/mm/YYYY'
			})

			var td_nilai = document.getElementById('td_nilai');
			td_nilai.addEventListener('keyup', function(e) {
				td_nilai.value = formatRupiah(this.value);
			});

			$('#jenis').on('change', function() {

				if (this.value == 2) {
					$('#table_2').show();
					$('#table_1').hide();

				} else {
					$('#table_1').show();
					$('#table_2').hide();
				}
			})

			$('.btn_pembayaran').on('click', function(){
				$('#tabel_pembayaran').toggle();
			})
			
		});

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