<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>{{ 'SITrendy - '. ($tag['submenu'] ?? '')}}</title>

	<!-- Global stylesheets -->

	<link rel="icon" type="image/png" sizes="32x32" href="{{ url('/') }}/assets/images/icontelkom.png">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/global_assets/css/icons/icomoon/styles.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/layout.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/components.min.css" rel="stylesheet" type="text/css">
	<link href="{{ url('/') }}/assets/css/colors.min.css" rel="stylesheet" type="text/css">
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="{{ url('/') }}/global_assets/js/main/jquery.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/main/bootstrap.bundle.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<script src="{{ url('/') }}/global_assets/js/plugins/forms/styling/switchery.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/ui/moment/moment.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/forms/selects/select2.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/loaders/blockui.min.js"></script>

	<script src="{{ url('/') }}/global_assets/js/plugins/pickers/pickadate/picker.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/pickers/pickadate/picker.date.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/pickers/daterangepicker.js"></script>
	<script src="{{ url('/') }}/assets/js/app.js"></script>
	<script src="{{ url('/') }}/assets/js/myjs.js?v=1.3"></script>

	<script src="{{ url('/') }}/global_assets/js/plugins/ui/fab.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/ui/sticky.min.js"></script>
	<script src="{{ url('/') }}/global_assets/js/plugins/ui/prism.min.js"></script>


	<style>
		.body {
			background-color: #efefef;
		}

		.content {
			padding: 0.55rem 0.55rem;
		}

		.dataTable thead .sorting,
		.dataTable thead .sorting_asc,
		.dataTable thead .sorting_asc_disabled,
		.dataTable thead .sorting_desc,
		.dataTable thead .sorting_desc_disabled {
			padding-right: 0.5rem;
		}

		.table td,
		.table th {
			padding: .55rem 0.45rem;
		}

		.form-group {
			margin-bottom: 0.50rem;
		}
	</style>
</head>

<body>

	<?php
	//cek persetujuan panjar

    use App\ActivityLog;
    use App\Panjar;
	use App\Pengelola;
	use App\Pengurus;
	use Illuminate\Support\Facades\Auth;
	use Illuminate\Support\Facades\DB;

	$pengurusx = Pengelola::where('jabatan', 'manager')->where('status', 1)->select(DB::raw(99), 'nama', 'jabatan');
	$pengurus = Pengurus::where('status', 1)->select('id', 'nama', 'jabatan')->union($pengurusx)->get();
	$statusid = 0;
	$notif = array();
	foreach ($pengurus as $p) {
		if ($p->nama == Auth::user()->name) {
			if (strtolower($p->jabatan) == 'manager') $statusid = 5;
			if (strtolower($p->jabatan) == 'bendahara') $statusid = 6;
			if (strtolower($p->jabatan) == 'ketua') $statusid = 7;
		}
	}

	if ($statusid > 0) {
		$panjar = Panjar::where('status', $statusid)->get();
		if ($panjar && count($panjar) > 0) {
			foreach ($panjar as $pa) {
				$notif[] = ['ket' => 'Panjar ini #' . $pa->nomor . ' membutuhkan persetujuan Anda', 'id' => $pa->id];
			}
		}
	}
	// -----
	?>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-dark bg-primary-800 navbar-static">

		<div class="navbar-brand1">
			<span class="navbar-text" style="min-width: 250px;">
				<span class=""></span>
				{{ config('app.name') }}
			</span>
		</div>

		<div class="d-md-none">
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>
			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>

		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" id="navbarx" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>

				<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
						Welcome, {{ Auth::User()->name}}
						@if(count($notif)>0)
						<span class="d-md-none ml-2"></span>
						<span class="badge badge-pill bg-warning-400 ml-auto ml-md-0"> {{ count($notif) }}</span>
						@endif
					</a>

					<div class="dropdown-menu dropdown-content wmin-md-350">
						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">

								<?php if (isset($notif)) for ($i = 0; $i < count($notif); $i++) { ?>

									<li class="media">
										<div class="mr-3">
											<a href="{{ route('showPanjar', $notif[$i]['id'] )  }}" class="btn bg-transparent border-primary text-primary rounded-round border-2 btn-icon"><i class="icon-pulse2"></i></a>
										</div>
										<div class="media-body">
											{{ $notif[$i]['ket'] }}
										</div>
									</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</li>
			</ul>

			<ul class="navbar-nav ml-md-auto">

				<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
						<i class="icon-pulse2 mr-2"></i>
						Activity
					</a>
					<?php
						$log = ActivityLog::where('log_name','<>','transaksi')->where('causer_id',Auth::user()->id)->orderBy('id','Desc')->limit(5)->get();
					?>
					<div class="dropdown-menu dropdown-menu-right dropdown-content wmin-md-350">
						<div class="dropdown-content-header">
							<span class="font-size-sm line-height-sm text-uppercase font-weight-semibold">Latest activity	</span>
						</div>

						<div class="dropdown-content-body dropdown-scrollable">
							<ul class="media-list">
								<?php foreach($log as $row){ ?>
								<li class="media">
									<div class="mr-3">
										<a href="#" class="btn bg-success-400 rounded-round btn-icon"><i class="icon-mention"></i></a>
									</div>

									<div class="media-body">
										 {{ ucfirst($row->log_name) . ' : ' . $row->description }}
										<div class="font-size-sm text-muted mt-1">{{ date_format($row->created_at,"d/m/Y H:i:s") }}</div>
									</div>
								</li>
								<?php } ?>
							</ul>
						</div>
					</div>
				</li>

				<li class="nav-item">
					<a class="navbar-nav-link dropdown-item" title="Logout" href="{{ route('logout') }}" onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();"><i class="icon-exit"></i>
					</a>
					<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
						@csrf
					</form>
				</li>
			</ul>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-dark sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				<span class="font-weight-semibold">Navigation</span>
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">

				<!-- User menu -->
				<div class="sidebar-user-material">
					<div class="sidebar-user-material-body">
						<div class="card-body text-center">
							<a href="#">
								@if(file_exists( public_path().'/assets/photo/' . substr(Auth::user()->email,0,6) . '.jpg' ))
								<img src="{{ url('/').'/assets/photo/'. substr(Auth::user()->email,0,6). '.jpg?v='.rand(1,32000) }}" width="80" height="80" alt="">
								@else
								<img src="{{ url('/') }}/assets/images/default-avatar.png" width="80" height="80" alt="">
								@endif

								<!-- <img src="{{ url('/') }}/global_assets/images/default-avatar.png" class="img-fluid rounded-circle shadow-1 mb-3" width="80" height="80" alt=""> -->
							</a>
							<h6 class="mb-0 mt-2 text-white text-shadow-dark">{{ Auth::user()->name}}</h6>
							<span class="font-size-sm text-white text-shadow-dark">Jakarta</span>
						</div>

						<div class="sidebar-user-material-footer">
							<a href="#user-nav" class="d-flex justify-content-between align-items-center text-shadow-dark dropdown-toggle" data-toggle="collapse">
								<span>My account</span>
							</a>
						</div>
					</div>

					<div class="collapse" id="user-nav">
						<ul class="nav nav-sidebar">
							<li class="nav-item">
								<a href="{{ route('profileanggota') }}" class="nav-link">
									<i class="icon-user-plus"></i>
									<span>My profile</span>

								</a>
							</li>

							<li class="nav-item">
								<a href="#" class="nav-link">
									<i class="icon-cog5"></i>
									<span>Hak Akses</span>
								</a>
							</li>
							<li class="nav-item">

								<a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault();
										document.getElementById('logout-form').submit();"><i class="icon-exit"></i>Logout
								</a>
							</li>
						</ul>
					</div>
				</div>
				<!-- /user menu -->


				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">

						<!-- Main -->
						<li class="nav-item-header">
							<div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i>
						</li>
						<li class="nav-item">
							<a href="/home" class="nav-link ">
								<i class="icon-home4 text-orange"></i>
								<span>
									Dashboard
								</span>
							</a>
						</li>
						@if(Auth::user()->role=='anggota')

						<li class="nav-item">
							<a href="{{ route('profileanggota') }}" class="nav-link ">
								<i class="icon-user text-violet"></i>
								<span>
									Data Profile
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{  route('simpanan') }}" class="nav-link ">
								<i class="icon-copy text-green"></i>
								<span>
									Data Simpanan
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('pinjamanInput') }}" class="nav-link ">
								<i class="icon-coin-dollar text-warning"></i>
								<span>
									Pengajuan Pinjaman
								</span>
							</a>
						</li>
						<li class="nav-item">
							<a href="{{ route('daftarPeminjam') }}" class="nav-link ">
								<i class="icon-coin-dollar text-info"></i>
								<span>
									Data Pinjaman
								</span>
							</a>
						</li>

						@elseif(Auth::user()->role!='anggota')

						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-copy text-green"></i> <span>Data Master</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Layouts">
								<!-- <li class="nav-item"><a href="/data/anggota" class="nav-link ">Data Anggota</a></li> -->
								<li class="nav-item"><a href="{{ route('anggota') }}" class="nav-link ">Data Anggota</a></li>
								<li class="nav-item"><a href="{{ route('pengurus') }}" class="nav-link ">Data Pengurus</a></li>
								<li class="nav-item"><a href="{{ route('pengelola') }}" class="nav-link ">Data Pengelola</a></li>
								<li class="nav-item"><a href="{{ route('pemesan') }}" class="nav-link ">Data Pemesan/User</a></li>
								<li class="nav-item"><a href="{{ route('jenis_simpanan') }}" class="nav-link ">Data Jenis Simpanan</a></li>
								<li class="nav-item"><a href="{{ route('sumber_pinjaman') }}" class="nav-link ">Data Sumber Pinjaman</a></li>
								<li class="nav-item"><a href="{{ route('daftarRegister') }}" class="nav-link ">Data Register</a></li>
								<li class="nav-item"><a href="{{ route('perusahaan') }}" class="nav-link ">Data Perusahaan / Mitra</a></li>
								<li class="nav-item"><a href="{{ route('vendor') }}" class="nav-link ">Data Vendor</a></li>
								<li class="nav-item"><a href="{{ route('produk') }}" class="nav-link ">Data Produk</a></li>
								<li class="nav-item"><a href="{{ route('pajak') }}" class="nav-link ">Data Pajak</a></li>
								<li class="nav-item"><a href="{{ route('target') }}" class="nav-link ">Data Target Pegawai</a></li>
                                <li class="nav-item"><a href="{{ route('banner') }}" class="nav-link">Banner</a></li>
                                <li class="nav-item"><a href="{{ route('jenisCuti') }}" class="nav-link">Jenis Cuti</a></li>
                                <li class="nav-item"><a href="{{ route('alasanCuti') }}" class="nav-link">Alasan Cuti</a></li>
                                <li class="nav-item"><a href="{{ route('alasanPresensi') }}" class="nav-link">Alasan Presensi</a></li>
                                <li class="nav-item"><a href="{{ route('cuti') }}" class="nav-link">Cuti Pengurus</a></li>
                                <li class="nav-item"><a href="{{ route('hariLibur') }}" class="nav-link">Hari Libur</a></li>
                                <li class="nav-item"><a href="{{ route('appVersion') }}" class="nav-link">App Version</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-color-sampler text-blue"></i> <span>Simpan Pinjam</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Themes">

								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Simpanan</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="{{ route('saldosimpanan') }}" class="nav-link">Saldo Simpanan</a></li>
										<li class="nav-item"><a href="{{ route('simpanan') }}" class="nav-link">Daftar Simpanan</a></li>
										<li class="nav-item"><a href="{{ route('setoran') }}" class="nav-link">Setoran</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Pinjaman</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="{{ route('pinjamanInput') }}" class="nav-link">Pengajuan Pinjaman</a></li>
										<li class="nav-item"><a href="{{ route('daftarPermohonan') }}" class="nav-link">Daftar Pemohonan Pinjaman</a></li>
										<li class="nav-item"><a href="{{ route('daftarPeminjam') }}" class="nav-link">Daftar Pinjaman</a></li>
										<li class="nav-item"><a href="{{ route('bayarAngsuran') }}" class="nav-link">Input Pembayaran Angsuran</a></li>
										<li class="nav-item"><a href="{{ route('daftarAngsuran')}}" class="nav-link">Daftar Pembayaran Angsuran</a></li>
										<li class="nav-item"><a href="{{ route('bayarPelunasan') }}" class="nav-link">Input Pelunasan</a></li>
										<li class="nav-item"><a href="{{ route('daftarPelunasan') }}" class="nav-link">Daftar Pelunasan</a></li>
										<li class="nav-item"><a href="{{ route('daftarTunggakan') }}" class="nav-link">Daftar Tunggakan</a></li>
									</ul>
								</li>
								<li class="nav-item"><a href="{{ route('potonganPayroll') }}" class="nav-link">Potongan Payroll</a></li>
								<li class="nav-item"><a href="{{ route('rekon') }}" class="nav-link">Rekon Payroll</a></li>
							</ul>
						</li>

						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-stack text-violet"></i> <span>Project</span></a>
							<ul class="nav nav-group-sub">

								<li class="nav-item"><a href="{{ route('projectDb') }}" class="nav-link">Dashboard</a></li>
								<li class="nav-item"><a href="{{ route('createProject') }}" class="nav-link">Input Project</a></li>
								<li class="nav-item"><a href="{{ route('project') }}" class="nav-link">Daftar Project</a></li>
								<li hidden class="nav-item"><a href="{{ route('createPanjar') }}" class="nav-link">Input Panjar</a></li>
								<li class="nav-item"><a href="{{ route('invoice') }}" class="nav-link">Invoice</a></li>
								<li class="nav-item"><a href="{{ route('panjar') }}" class="nav-link">Panjar</a></li>
								<li class="nav-item nav-item-submenu">
									<a href="#" class="nav-link">Rekap Pajak</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="{{ route('rekapppn') }}" class="nav-link">PPN</a></li>
										<li class="nav-item"><a href="{{ route('rekappph22') }}" class="nav-link">PPH 22 </a></li>
										<li class="nav-item"><a href="{{ route('rekappph23') }}" class="nav-link">PPH 23 </a></li>
									</ul>
								</li>

								<li hidden class="nav-item"><a href="{{ route('createInvoice') }}" class="nav-link">Input Invoice</a></li>
							</ul>
						</li>

						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-basket text-warning"></i> <span>Pembelian</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Form components">
								<li class="nav-item"><a href="{{route('vendor')}}" class="nav-link">Daftar Vendor</a></li>
								<li class="nav-item"><a href="{{route('pembelian')}}" class="nav-link">Pembelian</a></li>
								<li hidden class="nav-item"><a href="{{ route('po') }}" class="nav-link">Pemesanan Pembelian (P0)</a></li>
								<li hidden class="nav-item"><a href="{{ route('pq') }}" class="nav-link">Penawaran Pembelian (PQ)</a></li>
							</ul>
						</li>

						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-calculator2 text-blue"></i> <span>Keuangan</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="JSON forms">
								<li class="nav-item"><a href="{{ route('akun') }}" class="nav-link ">Daftar Akun</a></li>
								<li class="nav-item"><a href="{{ route('saldoawal') }}" class="nav-link ">Saldo Awal</a></li>
								<li class="nav-item"><a href="{{ route('saldoKas')}}" class="nav-link ">Kas / Bank</a></li>
								<li class="nav-item"><a href="{{ route('biaya') }}" class="nav-link ">Biaya</a></li>
								<li class="nav-item"><a href="{{ route('jurnalumum') }}" class="nav-link ">Jurnal Umum</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-file-stats2 text-green"></i> <span>Laporan</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="JSON forms">
								<li class="nav-item"><a href="{{ route('laporanJurnalCreate') }}" class="nav-link">Jurnal</a></li>
								<li class="nav-item"><a href="{{ route('laporanNeracaCreate') }}" class="nav-link">Neraca</a></li>
								<li class="nav-item"><a href="{{ route('laporanBukubesarCreate') }}" class="nav-link">Buku Besar</a></li>
								<li class="nav-item"><a href="{{ route('laporanLabarugiCreate')}}" class="nav-link">Laba Rugi</a></li>
								<li class="nav-item"><a href="{{ route('laporanPerubahanmodalCreate')}}" class="nav-link">Perubahan Modal</a></li>
								<li class="nav-item"><a href="{{ route('laporanPajak')}}" class="nav-link">Pajak</a></li>
                                <li class="nav-item"><a href="{{ route('report.presensiTahunanUser') }}" class="nav-link">Presensi Tahunan User</a></li>
                                <li class="nav-item"><a href="{{ route('report.presensiBulananUser') }}" class="nav-link">Presensi Bulanan User</a></li>
                                <li class="nav-item"><a href="{{ route('report.presensiUser') }}" class="nav-link">Presensi User</a></li>
                                <li class="nav-item"><a href="{{ route('report.cutiUser') }}" class="nav-link">Cuti User</a></li>
							</ul>
						</li>

						<li class="nav-item nav-item-submenu">
							<a href="#" class="nav-link"><i class="icon-calculator2 text-blue"></i> <span>Pengaturan</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="JSON forms">
								<li class="nav-item"><a href="{{ route('users') }}" class="nav-link ">Manajemen User</a></li>
								<li class="nav-item"><a href="{{ route('mappingAkun') }}" class="nav-link ">Mapping Akun</a></li>
							</ul>
						</li>

						<li class="nav-item">
							<a class="nav-link" href="{{ route('logout') }}" title="LogOut" onclick="event.preventDefault();
												document.getElementById('logout-form').submit();"><i class="icon-exit text-danger"></i>
								<span>
									Logout
								</span>
							</a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
								@csrf
							</form>
						</li>

						@endif
					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->

		</div>
		<!-- /main sidebar -->


		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->

			<div class="page-header page-header-light">
				<div class="page-header-content header-elements-md-inline">
					<div class="page-title d-flex">
						<h4><a href="javascript:history.back()" title="<< Back"><i class="icon-arrow-left52 mr-2" style="font-size:x-large;"></i></a> <span class="font-weight-semibold">{{ $tag['menu'] ?? ''}}</span> - {{ $tag['submenu'] ?? ''}}</h4>
						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>

					<div class="header-elements d-none">

						<!-- Top right menu -->
						<ul class="fab-menu fab-menu-absolute fab-menu-top-right" data-fab-toggle="hover" id="fab-menu-affixed-demo-right">
							<li>
								<a class="fab-menu-btn btn bg-warning-300 btn-float rounded-round btn-icon">
									<i class="fab-icon-open icon-grid3"></i>
									<i class="fab-icon-close icon-cross2"></i>
								</a>

								<ul class="fab-menu-inner">
									<li>
										<div data-fab-label="Setoran">
											<a href="{{ route('setoran') }}" class="btn btn-info rounded-round btn-icon btn-float">
												<i class="icon-wallet"></i>
											</a>
										</div>
									</li>
									<li>
										<div data-fab-label="Simpanan">
											<a href="{{ route('simpanan') }}" class="btn btn-primary rounded-round btn-icon btn-float">
												<i class="icon-cash4"></i>
											</a>
										</div>
									</li>

									<li>
										<div data-fab-label="Project">
											<a href="{{ route('project') }}" class="btn bg-orange rounded-round btn-icon btn-float">
												<i class="icon-stack-text"></i>
											</a>
										</div>
									</li>
									<li>
										<div data-fab-label="Invoice">
											<a href="{{ route('invoice') }}" class="btn bg-success rounded-round btn-icon btn-float">
												<i class="icon-stack-text"></i>
											</a>
										</div>
									</li>
									<li>
										<div data-fab-label="Panjar">
											<a href="{{ route('panjar') }}" class="btn bg-violet rounded-round btn-icon btn-float">
												<i class="icon-cash4"></i>
											</a>
										</div>
									</li>
								</ul>
							</li>
						</ul>
						<!-- /top right menu -->
					</div>
				</div>

				@if(($tag['submenu'] ?? '') != 'Dashboard')
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="/home" class="breadcrumb-item"><i class="icon-home2 mr-2"></i>Dashboard</a>
							<a href="#" class="breadcrumb-item">{{ ($tag['menu'] ?? '')}}</a>
							<span class="breadcrumb-item active">{{ $tag['submenu'] ?? ''}}</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
				@endif
			</div>

			<!-- /page header -->
			@if(isset($tag['modal']))
			@include('layouts.modalform')
			@endif
			<div class="content">
				@yield('maincontent')
			</div>



			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>

				<div class="navbar-collapse collapse" id="navbar-footer">
					<span class="navbar-text">
						&copy; 2021. Kopkar Trendy, PT. Telekomunikasi Indonesia</a>
					</span>


				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->
	</div>
	<!-- /page content -->
</body>

</html>
