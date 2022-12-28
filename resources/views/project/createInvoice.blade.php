@extends('layouts.home')

@section('maincontent')

@include('layouts.mylib')
<style>
	.table-ang {
		font-size: small;
		width: 100%;
	}
</style>
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
		<?php
		$statusi = ['', 'BELUM LUNAS', 'BAYAR SEBAGIAN', 'LUNAS', 'BATAL'];
		?>

		<span class="mr-1 badge bg-info-300 pb-0">
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
					<a href="{{ route('createPembayaran', $invoice->id ?? 0) }}" class="dropdown-item" title="Input Pembayaran atau lihat data pembayaran">Pembayaran</a>
					<a href="{{ route('printInvoice', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak Invoice"> Cetak Invoice</a>
					<a href="{{ route('printKwitansi', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak Kwitansi"> Cetak Kwitansi</a>
					<a href="{{ route('printSPB', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak SPB"> Cetak SPB</a>
					<a href="{{ route('printBA', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak Berita Acara"> Cetak Berita Acara</a>
					<a href="{{ route('printBAPPKontrak', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak BAPP Kontrak + BAP"> Cetak BAPP Kontrak + BAP </a>
					<a href="{{ route('printBAUTKontrak', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak BAUT Kontrak"> Cetak BAUT Kontrak </a>
					<a href="{{ route('printBAPPGSD', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak BASTPP (GSD) + BAUT"> Cetak BASTPP + BAUT (GSD) </a>
					<a href="#" class="dropdown-item" title="">_______________________________</a>
					<a href="{{ route('printTT', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak Tanda Terima"> Cetak Tanda Terima</a>
					<a href="{{ route('printSJ', $invoice->id ?? 0) }}" target="_blank" class="dropdown-item" title="Cetak Surat Jalan"> Cetak Surat Jalan</a>

					<a hidden href="{{ route('createInvoice') }}" class="dropdown-item" title="Input Invoice Baru">Input Invoice Baru </a>
					<a hidden href="{{ route('invoice') }}" class="dropdown-item" title="Daftar Invoice">Daftar Invoice </a>
					<a href="#" class="dropdown-item" title="">_______________________________</a>
					<a href='#' value="{{ route('showJurnalInvoice', $invoice->id ?? 0) }}" class="dropdown-item modalMd" title="Jurnal" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"> Lihat Jurnal</a>

				</div>
			</div>
		</div>
	</div>

	<div class="card-body">
		@if(isset($project))
		<form method="POST" action="{{ route('infoProject') }}">
			<div class="form-group row">
				<label class="col-form-label col-sm-2">PILIH PROJECT : </label>
				@csrf
				<div class="col-sm-7">
					<select name="f_project" id="f_project" data-placeholder="Tentukan Project" class="select-search">
						<option value=""></option>
						@foreach($project as $r)
						<option value="{{$r->id}}" <?php if (($projectid_f ?? session('projectid_f')) == $r->id) echo 'selected';
													?>>{{$r->no_po ? 'PO: '.$r->no_po .' || '.$r->nama : 'SPK: '. $r->no_spk .' || '.$r->nama}}</option>

						@endforeach
					</select>
				</div>
				<div class="col-sm-1">
					<button type="submit" class="btn btn-outline-info btn-sm">Tampilkan</button>
				</div>
			</div>
		</form>
		@endif
		<style>
			.mb-x {
				height: 30px !important;
				margin-bottom: 0;
			}

			.table td,
			.table th {
				padding: 0.35rem 0.2rem;
			}
		</style>
		<div class="row">
			<div class="col-sm-12 alpha-info border-top-slate">
				<h5>Data Project</h5>
				<div class="form-group row mb-x">
					<label class="col-form-label col-sm-2">Mitra / Perusahaan</label>
					<label class="col-form-label col-sm-10">{{ $data->alias  ?? '' }} | {{ $data->unitkerja  ?? '' }}</label>
				</div>
				<div class="form-group row mb-x">
					<label class="col-form-label col-sm-2">Pesanan</label>
					<label class="col-form-label col-sm-10">{{ $data->nama ?? '' }}</label>
				</div>
				<div class="form-group row mb-x">
					<label class="col-form-label col-sm-2">Nilai</label>
					<label class="col-form-label col-sm-10">Rp. {{ Rupiah($data->nilai ?? '',2) }}</label>
				</div>
				<div class="form-group row mb-x">
					<label class="col-form-label col-sm-2">Nomor NP/SPK</label>
					<label class="col-form-label col-sm-10">{{ $data->no_spk ?? '' }} <a <?php if (!isset($data->id)) echo 'hidden'; ?> href="{{ route('showProject',$data->id ?? '') }}" class="badge badge-success" title="Detail Project">Klik Detail </a></label>
				</div>
				<div class="form-group row mb-x">
					<label class="col-form-label col-sm-2">Tanggal NP/SPK</label>
					<label class="col-form-label col-sm-10" id="tanggal_spk">{{ date('d/m/Y', strtotime($data->tgl_spk ?? '')) }}</label>
				</div>
				<div style="width: 80%;" class="card mt-3  alpha-info">
					@if (isset($item) && count($item) > 0)
					<h6 class="ml-1 mt-3">Daftar Item Barang/Pekerjaan</h6>
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
									break;
								}
								?>
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
								echo '<tr><td class="text-center" width="50px">' . $no++ . '</td>';

								for ($i = 1; $i < $x; $i++) {
									$isikolom = 'kolom' . $i . '_isi';
									$align = ($i == ($kolomjumlah - 1)) ? 'text-right' : '';
									echo '<td class="' . $align . '"><div>' . Rupiah($row->$isikolom, ($i == ($kolomjumlah - 1)) ? 2 : 0) . '</div></td>';

									if ($i == ($kolomjumlah - 1)) {
										$subtotal += $row->$isikolom;
									}
								}

							?>

								</tr>
							<?php
							}
							$no--;
							?>
						</tbody>
						<tfoot class="font-weight-bold">
							<?php if ($data->cbkeuntungan) { ?>
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
								<td class="text-right">{{Rupiah($data->ppnnilai ,2)}}</td>
							</tr>
							<tr>
								<td colspan="{{$kolomjumlah-1}}" class="text-right">TOTAL</td>
								<td class="text-right" name="td_total1">{{Rupiah($data->ppnnilai + $subtotal,2)}}</td>
								<input type="hidden" value="{{Rupiah($data->ppnnilai + $subtotal,2)}}">
							</tr>
							 
						</tfoot>
					</table>
					@else
					@if(isset($data->perusahaan))
					<div class="alert alert-danger alert-block ml-1 mr-1">
						<strong>Perhatian. Anda belum menginput daftar Item Barang/Pekerjaan pada project ini. <a href="{{route('showProject', $data->id ?? '' )}}"> Input Sekarang</a></strong>
					</div>
					@endif
					@endif


				</div>
			</div>
		</div>

		<div class="row mt-2">

			<div class="col-sm-12">
				<h5>Data Invoice</h5>
				<form method="POST" action="{{ route('updateInvoice') }}" id="formInv">
					@csrf

					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">No. Invoice</label>
						<div class="col-sm-7">
							<input type="hidden" name="id" value="{{ $invoice->id ?? session('id') }}">
							<input type="hidden" name="projectid" value="{{ $data->id ?? session('projectid') }}">
							<input type="hidden" name="total" value="{{ $data->nilai ?? session('total') }}">
							<input type="hidden" name="ppn" value="{{ $data->ppnnilai ?? session('ppnnilai') }}">
							<input type="hidden" id="nomorinvx" value="{{ $invoice->nomor ?? session('nomor') }}">
							<input type="text" class="form-control" required name="nomor" id="nomorinv" value="{{ $invoice->nomor ?? session('nomor') }}">
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Tgl.Invoice</label>
						<div class="col-sm-7">
							<input type="text" class="form-control pickadate" required name="tanggal" id="tanggal_inv" value="<?php echo IndoTgl($invoice->tanggal ?? session('tanggal')) ?>">
						</div>
					</div>
					<div class="form-group row mb-0">
						<label class="col-form-label col-sm-2">Tgl.Akhir Pekerjaan</label>
						<div class="col-sm-7">
							<?php $tgl_jt = isset($data->tgl_jatuhtempo) ? $data->tgl_jatuhtempo : session('tanggal_jatuhtempo');
							$tgl_jt = $invoice->tgl_jatuhtempo ?? $tgl_jt;
							?>
							<input type="text" class="form-control pickadate" required name="tanggal_jatuhtempo" value="{{ IndoTgl($tgl_jt) }}">
						</div>
					</div>

					<div class="form-group row mt-3">
						<label class="col-form-label col-sm-2">Jenis dan Tanggal BA </label>
						<div class="col-sm-7">
							<select name="ba" id="ba" data-placeholder="Pilih" class="select-search">
								<option value=""></option>
								<option value=1 <?php if (($invoice->ba ?? session('ba')) == 1) echo 'selected';
												?>>BERITA ACARA PENYELESAIAN PEKERJAAN</option>
								<option value=12 <?php if (($invoice->ba ?? session('ba')) == 12) echo 'selected';
												?>>BERITA ACARA PENYELESAIAN PEKERJAAN (Tanpa Nopes)</option>
								<option value=2 <?php if (($invoice->ba ?? session('ba')) == 2) echo 'selected';
												?>>BERITA ACARA PENERIMAAN PEKERJAAN</option>
								<option value=11 <?php if (($invoice->ba ?? session('ba')) == 11) echo 'selected';
												?>>BERITA ACARA PENERIMAAN PEKERJAAN (Tanpa Nopes)</option>
								<option value=3 <?php if (($invoice->ba ?? session('ba')) == 3) echo 'selected';
												?>>BERITA ACARA PENYERAHAN DAN PENERIMAAN</option>
								<option value=19 <?php if (($invoice->ba ?? session('ba')) == 19) echo 'selected';
												?>>BERITA ACARA PENYERAHAN DAN PENERIMAAN (Tanpa Nopes)</option>
								<option value=4 <?php if (($invoice->ba ?? session('ba')) == 4) echo 'selected';
												?>>BERITA ACARA PEMERIKSAAN BARANG/ JASA (BAPP)</option>
								<option value=5 <?php if (($invoice->ba ?? session('ba')) == 5) echo 'selected';
												?>>BERITA ACARA PEMERIKSAAN & PENYERAHAN</option>
								<option value=10 <?php if (($invoice->ba ?? session('ba')) == 10) echo 'selected';
												?>>BERITA ACARA PEMERIKSAAN DAN PENERIMAAN </option>
								<option value=14 <?php if (($invoice->ba ?? session('ba')) == 14) echo 'selected';
												?>>BERITA ACARA PEMERIKSAAN DAN PENERIMAAN (Tanpa Nopes) </option>
								<option value=6 <?php if (($invoice->ba ?? session('ba')) == 6) echo 'selected';
												?>>BERITA ACARA PEMERIKSAAN DAN PENERIMAAN BARANG</option>
								<option value=9 <?php if (($invoice->ba ?? session('ba')) == 9) echo 'selected';
												?>>BERITA ACARA SERAH TERIMA </option>
								<option value=13 <?php if (($invoice->ba ?? session('ba')) == 13) echo 'selected';
												?>>BERITA ACARA SERAH TERIMA (Tanpa Nopes) </option>
								<option value=7 <?php if (($invoice->ba ?? session('ba')) == 7) echo 'selected';
												?>>BERITA ACARA SERAH TERIMA BARANG</option>
								<option value=17 <?php if (($invoice->ba ?? session('ba')) == 17) echo 'selected';
												?>>BERITA ACARA SERAH TERIMA BARANG (Tanpa Nopes)</option>
								<option value=8 <?php if (($invoice->ba ?? session('ba')) == 8) echo 'selected';
												?>>BERITA ACARA SERAH TERIMA PENYELESAIAN PEKERJAAN</option>
								<option value=18 <?php if (($invoice->ba ?? session('ba')) == 18) echo 'selected';
												?>>BERITA ACARA SERAH TERIMA PENYELESAIAN PEKERJAAN (Tanpa Nopes)</option>
								<option value=15 <?php if (($invoice->ba ?? session('ba')) == 15) echo 'selected';
												?>>BERITA ACARA PENYELESAIAN PRESTASI PEKERJAAN (Tanpa Nopes)</option>
								<option value=16 <?php if (($invoice->ba ?? session('ba')) == 16) echo 'selected';
												?>>BERITA ACARA PEMERIKSAAN DAN PENERIMAAN PEKERJAAN</option>

							</select>
						</div>
					</div>
					<div class="form-group row mt-2">
						<label class="col-form-label col-sm-2"></label>
						<label class="col-form-label col-sm-1">Tanggal</label>
						<div class="col-sm-3">
							<input type="text" name="tanggalba" placeholder="Tanggal BA" class="form-control pickadate" value="{{ IndoTgl($invoice->tanggalba ?? session('tanggalba')) }}">
						</div>
					</div>
					<div class="form-group row">
						<label class="col-form-label col-sm-2">Penandatangan</label>
						<div class="col-sm-4">
							<select name="pengurus" id="pengurus" required data-placeholder="Pilih" class="select">
								<option value=""></option>
								@if(isset($pengurus)) @foreach($pengurus as $r)
								<option value="{{$r->id}}" <?php if (($invoice->pegawaiid ?? session('pengurus')) == $r->id) echo 'selected';
															?>>{{$r->nama}}</option>
								@endforeach
								@endif
							</select>
						</div>
					</div>
					<div class="form-group row">
						<div class="col-sm-9" id="warningid">
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
					<div class="form-group row mt-5">
						<div class="col-sm-9 text-right">

							<div class="list-icon">
								<a href="3" value="{{ route('batalInvoice',$invoice->id ?? '' ) }}" class="btn btn-outline-danger btn-sm modalMd" title="Pembatalan Invoice" style="float: left;" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">Pembatalan Invoice</a>
								<a href="{{ $kodeubah==1 ? route('showInvoice',$invoice->id ) : route('invoice') }}" class="btn btn-outline-warning btn-sm" title="Batal / Kembali"> {{ $kodeubah==1 ? 'BATAL' : 'KEMBALI' }}</a>
								@if($kodeubah==1 || $kodeubah==3)
								<!-- <button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data Invoice ini ?')" class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button> -->
								<button type="submit" id="btnsave"   class="btn btn-outline-info btn-sm" title="Simpan Data">Simpan</button>
								@else
								<a href="{{ route('editInvoice',$invoice->id ?? '' ) }}" class="btn btn-outline-info btn-sm" title="Ubah Data">Ubah</a>
								@endif
							</div>
						</div>
					</div>
				</form>

			</div>
		</div>

		@if(isset($daftarpembayaran))

		<div class="card mt-3" style="width: 80%;" id="tabel_pembayaran">
			<div class="card-body">

				<h6>Data Pembayaran</h6>
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
							<td><a href="{{ route('createPembayaran', $invoice->id ?? 0) }}" title="Pembayaran"> #{{ $row->nomor}} </a></td>
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
		@endif
	</div>

	<script type="text/javascript">
		$(document).ready(function() {

			$('.pickadate').pickadate({
				format: 'dd/mm/yyyyy'
			})

			$('#jenis').on('change', function() {

				if (this.value == 2) {
					$('#table_2').show();
					$('#table_1').hide();

				} else {
					$('#table_1').show();
					$('#table_2').hide();
				}
			})

			$('#btn_pembayaran').on('click', function() {
				$('#tabel_pembayaran').toggle();
			})

			var td_nilai = document.getElementById('td_nilai');
			if (td_nilai) {
				td_nilai.addEventListener('keyup', function(e) {
					td_nilai.value = formatRupiah(this.value);
				});
			}

			$('#formInv').on('submit', function(e) {

				//cek tanggal
				var tglspk = $('#tanggal_spk').text();
				var tglinv = $('#tanggal_inv').val();

				var mdy = tglspk.split('/');
				tglspk = new Date(mdy[2], mdy[1] - 1, mdy[0]);

				var mdy = tglinv.split('/');
				tglinv = new Date(mdy[2], mdy[1] - 1, mdy[0]);

				if (tglspk > tglinv) {
					alert('Perhatian. Tanggal invoice yang Anda input tidak sesuai dengan tanggal Nopes/SPK nya. ')
					e.preventDefault();
					return false;
				}

				//cek nomor
				var nomor = '<?php echo $nomor ?? '' ?>';
				var noinvx = $('#noinvx').val().toLowerCase();
				var no = $('#nomorinv').val().toLowerCase();
				if (noinvx != no) {
					if (nomor.includes(no)) {
						var msg = '<div class="alert alert-danger alert-block"><button type="button" class="close" data-dismiss="alert">×</button><strong>Data gagal disimpan, Nomor Invoice Sudah Digunakan !.</strong></div>';
						$('#warningid').html(msg);
						e.preventDefault();
						return false;
					}
				}
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