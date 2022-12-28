@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<link href="{{ url('/') }}/assets/js/mycss.css" rel="stylesheet" type="text/css">

<script src="{{ url('/')}}/global_assets/js/plugins/pickers/daterangepicker.js"></script>
<script src="{{ url('/')}}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>

<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        // $('#navbarx').trigger('click');

        $('.form-check-input-styled').uniform();    

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
        <h5 class="card-title" id="title-form">DAFTAR PEMBELIAN/BIAYA PROJECT</h5>
        <div class="header-elements">
            <div class="list-icons">
            </div>
        </div>
    </div>
        <div class="card-body">
            <form method="POST" action="{{ route('filterProject') }}">
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
                        <select name="f_perusahaan" id="f_perusahaan" data-placeholder="Pilih Mitra Perusahaan" class="select-search">
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
                    <div hidden class="col-sm-3">
                        <select name="f_pospk" id="f_pospk" data-placeholder="Pilih Nota Pesanan)" class="select">
                            <option value=""></option>
                            <option value="All" <?php if (isset($f_pospk)) {
                                                    if ($f_pospk == 'all') echo 'selected';
                                                } ?>><strong>SEMUA</strong></option>
                            <option value="1" <?php if (isset($f_pospk)) {
                                                    if ($f_pospk == '1') echo 'selected';
                                                } ?>><strong>Ada PO (Pemesanan)</strong></option>
                            <option value="2" <?php if (isset($f_pospk)) {
                                                    if ($f_pospk == '2') echo 'selected';
                                                } ?>><strong>Ada SPK / Kontrak</strong></option>
                            <option value="3" <?php if (isset($f_pospk)) {
                                                    if ($f_pospk == '3') echo 'selected';
                                                } ?>><strong>Ada PO dan SPK/Kontrak</strong></option>
                            <option value="4" <?php if (isset($f_pospk)) {
                                                    if ($f_pospk == '4') echo 'selected';
                                                } ?>><strong>Tidak Ada PO dan SPK/Kontrak</strong></option>
                        </select>
                    </div>
                    
                    <div class="col-sm-1">
                        <button type="submit" class="btn btn-outline-info btn-sm" id="btn-submit-f">Tampilkan</button>
                    </div>
                </div>

                <div class="form-group row">
                    
                </div>

            </form>
     <hr>
        <table class="table table-hover basicx" id="table_1" style="width: 100%;">
            <thead class="text-center">
                <tr>
                    <th class="export">NO.</th>
                    <!-- <th class="export">MITRA PERUSAHAAN</th> -->
                    <th class="export">NOTA PESANAN</th>
                    <th class="export">URAIAN PROJECT</th>
                    <th class="export" style="min-width: 80px;">TANGGAL</th>
                    <th  class="export">NILAI PROJECT</th>
                    <th class="export">JUMLAH PEMBELIAN/BIAYA</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <!-- <td> {{ $row->perusahaan }}</td> -->
                    <td> {{ $row->no_spk }}</td>
                    <td> <a href="{{ route('createPembelian', $row->id) }}" class="text-teal-800" title="Detail Pembelian/Biaya"> {{ $row->nama }}</a></td>
                    <td> {{ IndoTgl($row->tgl_po ?? $row->tgl_spk)}}</td>
                    <td  class="text-right"> {{ Rupiah_no($row->nilai,0) }}</td>
                    <td  class="text-right"> {{ Rupiah_no($row->total,0) }}</td>
                    <td class="text-center">
                        <div class="list-icons">
                            <a href="{{ route('createPembelian',$row->id) }}" class="list-icons-item text-info-600" title="Detail Pembelian/Biaya"><i class="icon-eye8"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection