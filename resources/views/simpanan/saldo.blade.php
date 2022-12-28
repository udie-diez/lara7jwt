@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<script src="{{ url('/')}}/global_assets/js/plugins/pickers/daterangepicker.js"></script>
<script src="{{ url('/')}}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $('.form-check-input-styled').uniform();

        $('.select-search').select2();

        $("#tglfilter").on('DOMSubtreeModified', function() {
            document.getElementById("tglFilter").value = this.innerHTML;
        });

        $('#ck_tanggal').on('change', function() {
            if (this.checked == true) {
                $('#btn-tanggal').prop('disabled', false);
            } else {
                $('#btn-tanggal').prop('disabled', true);

            }
        })

        var ck = "{{ $ck_tanggal ?? '' }}";
        if (ck) {
            $('#ck_tanggal').prop('checked', true);
            $('#btn-tanggal').prop('disabled', false);

            var tgl = "{{ $tgl ?? '' }}";
            if (tgl) $("#tglfilter").html(tgl);
        }

    })
</script>
<style>
    .uniform-checker {
        margin-top: 10px;
    }
</style>
<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('filterSaldosimpanan') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-sm-1 font-weight-bold">FILTER : </label>
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
                <div class="col-sm-1">
                    <button type="submit" class="btn btn-outline-info btn-sm" id="btn-submit-f">Tampilkan</button>
                </div>
            </div>

        </form>
    </div>
    <hr class="mb-0">
    <div class="card-header  header-elements-inline mt-0">
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-info has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2>Rp. {{ Rupiah($rekap[0]->wajib + $rekap[0]->saldowajib) }} </h2>
                            <span class="text-uppercase">Wajib</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-coin-dollar icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-danger-800 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2>Rp. {{ Rupiah($rekap[0]->pokok + $rekap[0]->saldopokok) }} </h2>
                            <span class="text-uppercase">Pokok</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-cash2 icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-teal has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2>Rp. {{ Rupiah($rekap[0]->wajib + $rekap[0]->saldowajib + $rekap[0]->pokok + $rekap[0]->saldopokok) }} </h2>
                            <span class="text-uppercase">Jumlah</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-cash4 icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <table class="table table-hover basicx table-bordered">
            <thead class="text-center">
                <tr>
                    <th rowspan="2">NO</th>
                    <th rowspan="2">NO.ANGGOTA</th>
                    <th rowspan="2">NIK</th>
                    <th rowspan="2">NAMA ANGGOTA</th>
                    <th colspan="3" class="text-center">SIMPANAN</th>
                    <th rowspan="2" class="text-center">PERIODE</th>
                </tr>
                <tr>
                    <th>WAJIB</th>
                    <th>POKOK</th>
                    <th>JUMLAH</th>
                </tr>
            </thead>
            <tbody>
                @php $totwajib = $totpokok = 0 @endphp

                @foreach($data as $row)
                @php
                $wajib = $row->saldowajib + ($row->wajib ?? 0);
                $pokok = $row->saldopokok + ($row->pokok ?? 0);
                $totwajib += $wajib;
                $totpokok += $pokok;
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td class="text-center"> {{ $row->nomor }}</td>
                    <td class="text-center"> {{ $row->nik }}</td>
                    <td> {{ $row->nama }}</td>
                    <td class="text-right">{{ Rupiah_no($wajib) }}</td>
                    <td class="text-right">{{ Rupiah_no($pokok) }}</td>
                    <td class="text-right">{{ Rupiah_no($wajib + $pokok) }}</td>
                    <td class="text-center"> {{ $row->periode }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td class="text-center">TOTAL</td>
                    <td class="text-right">{{ Rupiah_no($totwajib) }}</td>
                    <td class="text-right">{{ Rupiah_no($totpokok) }}</td>
                    <td class="text-right">{{ Rupiah_no($totwajib + $totpokok) }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection