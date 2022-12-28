@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>

<div class="card">
    @if(Auth::user()->role=='admin')
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('filterPinjaman') }}">
                @csrf
                <div class="form-group row">
                    <label class="col-form-label col-sm-1  font-weight-bold">FILTER : </label>
                    <label class="col-form-label pr-2">Periode :</label>
                    <div class="col-sm-2">
                        @php $bulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
                        <select name="f_bulan" id="f_bulan" class="select">
                            @for($i=0;$i < 12; $i++) <option value={{$i+1}} <?php
                                                                            if (isset($bulan_f)) {
                                                                                if ($bulan_f == $i + 1) echo 'selected';
                                                                            } else {
                                                                                if (($i + 1) == date('m')) echo 'selected';
                                                                            }
                                                                            ?>>{{ $bulan[$i] }}</option>
                                @endfor
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <input type="text" name="f_tahun" id="f_tahun" placeholder="Tahun" class="form-control" value="{{ $tahun_f ?? date('Y') }}">
                    </div>
                    <div class="col-sm-2">
                        <select name="f_sumber" id="f_sumber" data-placeholder="Pilih Sumber Pinjaman" class="select">
                            <option value=""></option>
                            <option value="all" <?php if (isset($sumber_f)) {
                                                    if ($sumber_f == 'all') echo 'selected';
                                                } ?>><strong>SEMUA</strong></option>

                            @foreach($sumber as $r)
                            <option value="{{$r->id}}" <?php if (isset($sumber_f)) {
                                                            if ($sumber_f == $r->id) echo 'selected';
                                                        } ?>>{{$r->nama}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="f_anggota" id="f_anggota" data-placeholder="Pilih Anggota" class="select-search">
                            <option value=""></option>
                            <option value="all" <?php if (isset($anggotaid_f)) {
                                                    if ($anggotaid_f == 'all') echo 'selected';
                                                } ?>><strong>= SEMUA ANGGOTA =</strong></option>

                            @foreach($anggota as $r)
                            <option value="{{$r->nik}}" <?php if (isset($anggotaid_f)) {
                                                            if ($anggotaid_f == $r->nik) echo 'selected';
                                                        } ?>>{{$r->nik .' - '.$r->nama}}</option>
                            @endforeach

                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-outline-info btn-sm" id="btn-submit-f">Tampilkan</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <div class="form-group row ml-3">
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

    <div class="card-header header-elements-inline">

        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a hidden class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ route('pinjamanInput') }}" title="Input Permohonan Pinjaman"> <i class="icon-plus2"></i> Permohonan Baru</a>
            </div>
        </div>
    </div>
    @endif
    <table class="table basicx table-hover" id="table_1" style="font-size: smaller;">
        <thead>
            <tr class="text-center">
                <th class="export">NO.</th>
                <th hidden>ID</th>
                <th class="export">NAMA</th>
                <th class="export">NIK</th>
                <th class="export">JUMLAH <br> PINJAMAN (Rp.)</th>
                <th class="export">TENOR (Bln)</th>
                <th class="export">SUMBER <br>PINJAMAN</th>
                <th class="export">AWAL ANGSURAN</th>
                <th class="export">AKHIR ANGSURAN</th>
                <th class="export">ANGSURAN <br> PERBULAN (Rp.)</th>
                <th class="export">ANGSURAN KE-</th>
                <th class="export">SISA <br>POKOK</th>
                <th class="export">MARGIN</th>
                <th class="text-center">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $x = 1;
            $totangsuran = $totsisapokok = $totmargin = 0;
            foreach ($data as $row) {
                $status = ['PENGAJUAN BARU', 'DITOLAK', 'DIPENDING', 'SEDANG DIPROSES'];
                $datapinjaman = [$row->nilaifix, 0, 0];
                foreach ($angsuran as $a) {
                    if ($row->id == $a->pinjamanid) {
                        $bulan = $a->status == null  || $a->status == 0 ? 0 : $a->bulan;
                        $sisapokok = $row->nilaifix - ($bulan * ($row->nilaifix / $row->tenorfix));
                        $datapinjaman = [$sisapokok, $a->margin, $bulan];

                        break;
                    }
                }
                $totangsuran += $row->angsuranfix;
                $totsisapokok += $datapinjaman[0];
                $totmargin += $datapinjaman[1];
            ?>

                <tr>
                    <td>{{ $x++ }}.</td>
                    <td hidden>{{ url('pinjaman/detail').'/'.$row->id}}</td>
                    <td> {{ $row->nama }}</td>
                    <td class="text-center">{{ $row->nik }}</td>
                    <td class="text-center">{{ number_format($row->nilaifix,0,'.',',') }}</td>
                    <td class="text-center">{{ $row->tenorfix }}</td>
                    <td class="text-center">{{ $row->namasumber }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($row->tgl_awal)) }}</td>
                    <td class="text-center">{{ date('d/m/Y', strtotime($row->tgl_akhir)) }}</td>
                    <td class="text-center">{{ number_format($row->angsuranfix,0,'.',',')  }}</td>
                    <td class="text-center"> {{ number_format($datapinjaman[2],0,'.',',')  }}</td>
                    <td class="text-center"> {{ number_format($datapinjaman[0],0,'.',',')  }}</td>
                    <td class="text-center"> {{ number_format($datapinjaman[1],0,'.',',')  }}</td>
                    <td class="text-center" title="Data Pinjaman">
                        <div class="list-icons">
                            <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Detail Pinjaman" data-toggle="modal" data-target="#modalMd"><i class="icon-eye8"></i></a>
                        </div>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
        <tfoot>
            <tr class="text-center font-weight-bold">
                <td colspan="8">T O T A L</td>
                <td class="text-center"> {{ number_format($totangsuran,0,'.',',')  }}</td>
                <td></td>
                <td class="text-center"> {{ number_format($totsisapokok,0,'.',',')  }}</td>
                <td class="text-center"> {{ number_format($totmargin,0,'.',',')  }}</td>
                <td></td>

            </tr>
        </tfoot>
    </table>
</div>
<!-- /basic datatable -->
@endsection