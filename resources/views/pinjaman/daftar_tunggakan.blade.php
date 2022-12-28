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
                <th class="export">JUMLAH <br>BULAN TUNGGAKAN</th>
                <th class="export">JUMLAH <br>TUNGGAKAN</th>
                <th class="text-center">AKSI</th>
            </tr>
        </thead>
        <tbody>
            <?php $total=0; ?>
            @foreach($data as $row)
            <?php
            foreach ($pinjaman as $a) {
                if ($row->pinjamanid == $a->id) {

                     $nama = $a->nama;
                     $nik = $a->nik;
                     $nilai = $a->nilaifix;
                     $tenor = $a->tenorfix;
                     $angsuran = $a->angsuranfix;
                     $namasumber = $a->namasumber;
                     $jumlahtunggak = $angsuran * $row->bulantunggak;
                     $tgl_awal = $a->tgl_awal;
                     $tgl_akhir = $a->tgl_akhir;
                    break;
                }
            }
            $total += $jumlahtunggak;

            ?>

            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('pinjaman/detail').'/'.$row->pinjamanid}}</td>
                <td> {{ $nama }}</td>
                <td class="text-center">{{ $nik }}</td>
                <td class="text-right">{{ number_format($nilai,0,'.',',') }}</td>
                <td class="text-center">{{ $tenor }}</td>
                <td class="text-center">{{ $namasumber }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($tgl_awal)) }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($tgl_akhir)) }}</td>
                <td class="text-right">{{ number_format($angsuran,0,'.',',')  }}</td>
                <td class="text-right"> {{ number_format($row->bulantunggak,0,'.',',')  }}</td>
                <td class="text-right"> {{ number_format($jumlahtunggak,0,'.',',')  }}</td>
                <td class="text-center">
                    <div class="list-icons">
                        <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Detail Pinjaman" data-toggle="modal" data-target="#modalMd"><i class="icon-eye8"></i></a>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
        <tfoot class="font-weight-bold">
            <tr>
                <td colspan="10" class="text-right">Total</td>
                <td class="text-right"> {{ number_format($total,0,'.',',')  }}</td>
            </tr>
        </tfoot>
    </table>
</div>
<!-- /basic datatable -->
@endsection