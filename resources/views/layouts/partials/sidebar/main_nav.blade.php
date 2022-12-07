{{-- Main navigation --}}
<div class="card card-sidebar-mobile">
    @php $parentClassName = ''; @endphp
    <ul class="nav nav-sidebar" data-nav-type="accordion">

        <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Main</div> <i class="icon-menu" title="Main"></i></li>
        <li class="nav-item">
            <a href="{{ route('dashboard') }}" class="nav-link @if (Request::segment(1) == 'dashboard') {{ 'active' }} @endif">
                <i class="icon-home4 text-warning"></i>
                <span>
                    Dashboard
                </span>
            </a>
        </li>
        @if (Request::segment(1) == 'banner' || Request::segment(1) == 'jenis-cuti' || Request::segment(1) == 'alasan-cuti' || Request::segment(1) == 'alasan-presensi')
            @php $parentClassName = 'nav-item-open'; @endphp
        @endif
        <li class="nav-item nav-item-submenu {{ $parentClassName }}">
            <a href="#" class="nav-link"><i class="icon-copy text-success"></i> <span>Data Master</span></a>

            <ul class="nav nav-group-sub" data-submenu-title="Data Master" @if ($parentClassName) {{ 'style=display:block;' }} @endif>
                <li class="nav-item"><a href="{{ route('banner') }}" class="nav-link @if (Request::segment(1) == 'banner') {{ 'active' }} @endif">Banner</a></li>
                <li class="nav-item"><a href="{{ route('jenisCuti') }}" class="nav-link @if (Request::segment(1) == 'jenis-cuti') {{ 'active' }} @endif">Jenis Cuti</a></li>
                <li class="nav-item"><a href="{{ route('alasanCuti') }}" class="nav-link @if (Request::segment(1) == 'alasan-cuti') {{ 'active' }} @endif">Alasan Cuti</a></li>
                <li class="nav-item"><a href="{{ route('alasanPresensi') }}" class="nav-link @if (Request::segment(1) == 'alasan-presensi') {{ 'active' }} @endif">Alasan Presensi</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Cuti Pengurus</a></li>
            </ul>
        </li>
        <li class="nav-item nav-item-submenu">
            <a href="#" class="nav-link"><i class="icon-color-sampler text-primary"></i> <span>Simpan Pinjam</span></a>

            <ul class="nav nav-group-sub" data-submenu-title="Simpan Pinjam">
                <li class="nav-item"><a href="#" class="nav-link active">Default layout</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 2</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 3</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 4</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 5</a></li>
            </ul>
        </li>
        <li class="nav-item nav-item-submenu">
            <a href="#" class="nav-link"><i class="icon-stack text-purple"></i> <span>Project</span></a>

            <ul class="nav nav-group-sub" data-submenu-title="Project">
                <li class="nav-item"><a href="#" class="nav-link active">Default layout</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 2</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 3</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 4</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 5</a></li>
            </ul>
        </li>
        <li class="nav-item nav-item-submenu">
            <a href="#" class="nav-link"><i class="icon-basket text-warning"></i> <span>Pembelian</span></a>

            <ul class="nav nav-group-sub" data-submenu-title="Pembelian">
                <li class="nav-item"><a href="#" class="nav-link active">Default layout</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 2</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 3</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 4</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 5</a></li>
            </ul>
        </li>
        <li class="nav-item nav-item-submenu">
            <a href="#" class="nav-link"><i class="icon-calculator2 text-primary"></i> <span>Keuangan</span></a>

            <ul class="nav nav-group-sub" data-submenu-title="Keuangan">
                <li class="nav-item"><a href="#" class="nav-link active">Default layout</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 2</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 3</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 4</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 5</a></li>
            </ul>
        </li>
        <li class="nav-item nav-item-submenu">
            <a href="#" class="nav-link"><i class="icon-file-stats text-success"></i> <span>Laporan</span></a>

            <ul class="nav nav-group-sub" data-submenu-title="Laporan">
                <li class="nav-item"><a href="#" class="nav-link active">Default layout</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 2</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 3</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 4</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 5</a></li>
            </ul>
        </li>
        <li class="nav-item nav-item-submenu">
            <a href="#" class="nav-link"><i class="icon-hammer-wrench text-primary"></i> <span>Pengaturan</span></a>

            <ul class="nav nav-group-sub" data-submenu-title="Pengaturan">
                <li class="nav-item"><a href="#" class="nav-link active">Default layout</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 2</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 3</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 4</a></li>
                <li class="nav-item"><a href="#" class="nav-link">Layout 5</a></li>
            </ul>
        </li>
        <li class="nav-item">
            <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();document.querySelector('#logout-form2').submit();">
                <i class="icon-exit text-danger"></i>
                <span>Logout</span>
            </a>
            <form id="logout-form2" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>

    </ul>
</div>
{{-- /Main navigation --}}
