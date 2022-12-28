@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<?php use Illuminate\Support\Facades\Session; ?>

<script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.16/sl-1.2.4/datatables.min.js"></script>
<script src="https://cdn.datatables.net/plug-ins/1.10.16/api/row().show().js"></script>
<script src="{{ url('/')}}/global_assets/js/plugins/pickers/daterangepicker.js"></script>
<script src="{{ url('/')}}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        var id =  "<?php echo route('editRekapPajak' , Session::get('invid') ?? '') ?>";
        if(id){
        var table = $('#table_11').DataTable();

            var row = table.row(function(idx, data, node) {
                return data[1] === id;
            });
            if (row.length > 0) {
                row.select()
                    .show()
                    .draw(false);
            };
        };

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
            <form method="POST" action="#">
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
                        <button type="submit" disabled class="btn btn-outline-info btn-sm" id="btn-submit-f">Tampilkan</button>
                    </div>
                </div>

            </form>
        </div>
    </div>

    <style>
        /* .table td,
        .table th {
            padding: 0.55rem 0.2rem;
        }
        .dataTable thead .sorting:after, .dataTable thead .sorting:before{
            right: 0.25rem;
        } */
    </style>
    <div class="card-body">
        <table class="table basicx table-bordered" id="table_11">
            <thead class="text-center">
                <tr>
                    <th rowspan="2" class="export table-hover">NO.</th>
                    <th rowspan="2" hidden>ID</th>
                    <th rowspan="2" class="export">NAMA DAN NPWP <BR> PEMOTONG/PEMUNGUT PAJAK</th>
                    <th colspan="2" class="export">OBJEK PEMOTONGAN/PEMUNGUTAN</th>
                    <th rowspan="2" class="export">PAJAK <BR> PENGHASILAN YANG <BR> DIPOTONG/DIPUNGUT</th>
                    <th rowspan="2" class="export">TANGGAL <BR> PEMBAYARAN</th>
                    <th colspan="2" class="export">BUKTI <BR> POTONG/PEMUNGUT</th>
                    <th rowspan="2">AKSI</th>
                </tr>
                <tr>
                    <th class="export">JENIS PENGHASILAN/TRANSAKSI</th>
                    <th class="export text-right">(RUPIAH)</th>
                    <th class="export">NOMOR</th>
                    <th class="export text-center">TANGGAL</th>
                </tr>

            </thead>
            <tbody>
                <?php
                $total = 0;
                ?>
                @foreach($data as $row)

                <?php
                $total += $row->pph23;
                ?>
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td hidden>{{ route('editRekapPajak' , $row->id) }}</td>
                    <td>PT. Telekomunikasi Indonesia , Tbk 01.000.013.1-093.004</td>
                    <td> {{ $row->nama}} </td>
                    <td class="text-right"> {{ Rupiah_no($row->totalppn,0) }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->pph23,0) }}</td>
                    <td> {{ IndoTgl($row->tanggalpby) }} </td>
                    <td> {{ $row->nomor}} </td>
                    <td> {{ IndoTgl($row->tanggal) }} </td>
                    <td class="text-center">
                        <div class="list-icons">
                            <a href="#" id="btn-edit" class="list-icons-item text-info-600" title="Input Nomor Bukti Potong" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-pencil7"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="4" class="text-center">TOTAL</td>
                    <td class="text-right">{{Rupiah_no($total,0) }}</td>
                    <td colspan="3"></td>

                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection