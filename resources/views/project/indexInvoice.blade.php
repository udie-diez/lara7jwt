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
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info" href="{{ action('ProjectCont@createInvoice') }}" title="Input Invoice"> <i class="icon-plus2"></i> Invoice</a>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('filterInvoice') }}">
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
                    <div class="col-sm-2">
                        <select name="f_status" id="f_status" data-placeholder="Pilih Status" class="select">
                            <option value=""></option>
                            <option value="all" <?php if (isset($f_status)) {
                                                    if ($f_status == 'all') echo 'selected';
                                                } ?>><strong>SEMUA STATUS</strong></option>
                            <option value=3 <?php if (isset($f_status)) {
                                                if ($f_status == 3) echo 'selected';
                                            } ?>><strong>LUNAS</strong></option>
                            <option value=1 <?php if (isset($f_status)) {
                                                if ($f_status == 1) echo 'selected';
                                            } ?>><strong>BELUM LUNAS</strong></option>
                            <option value=4 <?php if (isset($f_status)) {
                                                if ($f_status == 4) echo 'selected';
                                            } ?>><strong>BATAL</strong></option>
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
        <table class="table basicx" id="table_1" style="font-size: smaller;">
            <thead>
                <tr>
                    <th class="export">NO.</th>
                    <th class="export">INVOICE/FAKTUR</th>
                    <th class="export">URAIAN</th>
                    <th class="export" style="min-width: 80px;">TANGGAL</th>
                    <th class="export">UNIT KERJA</th>
                    <th class="export text-right">DPP</th>
                    <th class="export text-right">PPN</th>
                    <th class="export text-right">TOTAL</th>
                    <th class="export">KETERANGAN</th>
                    <th class="export">YANG TTD_PO</th>
                    <th class="export">AM</th>
                    <th class="text-center export">STATUS</th>
                    <th class="text-right">Aksi</th>
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
                    <td> <a href="{{ route('showInvoice',$row->id) }}" class="text-teal-800" title="Detail Invoice"> {{ $row->nomor }} </a></td>
                    <td> {{ $row->nama }}</td>
                    <td>{{ IndoTgl($row->tanggal)}}</td>
                    <td> {{ $row->unitkerja }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->subtotal,0) }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->pajak,0) }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->subtotal + $row->pajak,0) }}</td>
                    <td>{{ $row->no_po ?? $row->no_spk }}</td>
                    <td>{{ $row->pemesan }}</td>
                    <td>{{ $row->pic }}</td>
                    <td class="text-center"> <span class="<?= @$tagx ?>">{{ $statusi[$row->status ?? 0] }}</span></td>
                    <td class="text-right">
                        <div class="list-icons">
                            <a href="{{ route('showInvoice',$row->id) }}" class="list-icons-item text-info-600" title="Detail Invoice"><i class="icon-eye8"></i></a>
                            <a href="{{ action('ProjectCont@destroyInvoice',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="5" class="text-center">TOTAL</td>
                    <td class="text-right">{{Rupiah_no($dpp,0) }}</td>
                    <td class="text-right">{{Rupiah_no($ppn,0) }}</td>
                    <td class="text-right">{{Rupiah_no($ppn+$dpp,0) }}</td>
                    <td colspan="5"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection