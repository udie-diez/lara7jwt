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
    <div class="card-header  header-elements-inline">
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('filterPpn') }}">
                @csrf
                <div class="form-group row">
                    <label class="col-form-label col-sm-1  font-weight-bold">FILTER : </label>
                    <label class="col-form-label pr-2">Periode</label>
                    <div>
                        <input type="checkbox" class="form-check-input-styled" name="ck_tanggal" id="ck_tanggal">
                    </div>
                    <div style="max-width: 22%;" class="pl-2">
                        <button id="btn-tanggal" disabled type="button" class="btn btn-outline-default daterange-month">
                            <i class="icon-calendar position-left"> </i>
                            <span id="tglfilter"></span>
                            <b class="caret"></b>
                            <input type="hidden" id="tglFilter" name="tglFilter">
                        </button>
                    </div>

                    <div class="col-sm-3">
                        <select name="f_perusahaan" id="f_perusahaan" data-placeholder="Pilih Perusahaan" class="select-search">
                            <option value=""></option>
                            <option value="all" <?php if (isset($f_perusahaan)) {
                                                    if ($f_perusahaan == 'all') echo 'selected';
                                                } ?>><strong>SEMUA</strong></option>

                            @foreach($perusahaan as $r)
                            <option value="{{$r->id}}" <?php if (isset($f_perusahaan)) {
                                                            if ($f_perusahaan == $r->id) echo 'selected';
                                                        } ?>>{{$r->alias .' | ' .$r->unitkerja}}</option>
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

    <style>
        .table td,
        .table th {
            padding: 0.55rem 0.2rem;
        }
        .dataTable thead .sorting:after, .dataTable thead .sorting:before{
            right: 0.25rem;
        }
    </style>
    <div class="card-body">
        <table class="table basicx" id="table_11" style="font-size: smaller;">
            <thead>
                <tr>
                    <th class="export">NO.</th>
                    <th class="export">UNIT KERJA</th>
                    <th class="export">INVOICE/FAKTUR</th>
                    <th class="export">URAIAN</th>
                    <th class="export" style="min-width: 80px;">TANGGAL INVOICE</th>
                    <th class="export" style="min-width: 80px;">TANGGAL PEMBAYARAN</th>
                    <th class="export text-right">DPP</th>
                    <th class="export text-right">PPN 10%</th>
                    <th class="export text-right">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $statusi = ['', 'BELUM LUNAS', 'BAYAR SEBAGIAN', 'LUNAS', 'BATAL'];
                $total = $dpp = $ppn = $ttotal = 0;
                ?>
                @foreach($data as $row)

                <?php $tagx = ($row->status == 1 || $row->status == 4) ? 'badge badge-warning' : 'badge badge-success';
                $dpp += $row->subtotal;
                $ppn += $row->pajak;
                ?>
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td> {{ $row->unitkerja }}</td>
                    <td>  {{ $row->nomor }} </td>
                    <td> {{ $row->nama }}</td>
                    <td>{{ IndoTgl($row->tanggal)}}</td>
                    <td>{{ IndoTgl($row->tanggalbayar)}}</td>
                    <td class="text-right"> {{ Rupiah_no($row->subtotal,0) }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->pajak,0) }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->subtotal + $row->pajak,0) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="6" class="text-center">TOTAL</td>
                    <td class="text-right">{{Rupiah_no($dpp,0) }}</td>
                    <td class="text-right">{{Rupiah_no($ppn,0) }}</td>
                    <td class="text-right">{{Rupiah_no($ppn+$dpp,0) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection