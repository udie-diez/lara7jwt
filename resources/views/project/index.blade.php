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
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a class="btn btn-outline-info btn-sm" href="{{ action('ProjectCont@create') }}" title="Input Data Project"> <i class="icon-plus2"></i> Project</a>
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
                    <div class="col-sm-2">
                        <select name="f_pengelola" id="f_pengelola" data-placeholder="Pilih AM" class="select-search">
                            <option value=""></option>
                            <option value="all" <?php if (isset($f_pengelola)) {
                                                    if ($f_pengelola == 'all') echo 'selected';
                                                } ?>><strong>= SEMUA AM =</strong></option>

                            @foreach($pengelola as $r)
                            <option value="{{$r->id}}" <?php if (isset($f_pengelola)) {
                                                            if ($f_pengelola == $r->id) echo 'selected';
                                                        } ?>>{{$r->nama}}</option>
                            @endforeach

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
                    <th class="export">MITRA PERUSAHAAN</th>
                    <th class="export">URAIAN PROJECT</th>
                    <th class="export" style="min-width: 80px;">TANGGAL</th>
                    <th class="export">NOTA PESANAN</th>
                    <th hidden class="export">DPP</th>
                    <th hidden class="export">PAJAK <br>PPN</th>
                    <th  class="export">NILAI PROJECT</th>
                    <th hidden class="export">PEMBAYARAN</th>
                    <th hidden class="text-center  export">SISA TAGIHAN</th>
                    <th class="export">PEMESAN <br>/ USER</th>
                    <th class="export">AM</th>
                    <th class="export">STATUS</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @php $total = $dpp = $ppn = $pembayaran = 0 @endphp
                @foreach($data as $row)
                @php
                $dpp += $row->subtotal;
                $ppn += $row->pajak;
                $pembayaran += $row->pembayaran
                @endphp
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{$row->perusahaan .' | ' .$row->unitkerja}}</td>
                    <td> <a href="{{ route('showProject',$row->id) }}" class="text-teal-800" title="Detail Project"> {{ $row->nama }}</a></td>
                    <td> {{ IndoTgl($row->tgl_po ?? $row->tgl_spk)}}</td>
                    <td > {{ $row->no_spk }}</td>
                    <td hidden class="text-right"> {{ Rupiah_no($row->subtotal,0) }}</td>
                    <td hidden class="text-right"> {{ Rupiah_no($row->pajak,0) }}</td>
                    <td  class="text-right"> {{ Rupiah_no($row->nilai,0) }}</td>
                    <td hidden class="text-right"> {{ $row->pembayaran ? Rupiah_no($row->pembayaran,0) : 0 }}</td>
                    <td hidden class="text-right"> {{ Rupiah_no($row->subtotal + $row->pajak-$row->pembayaran,0)}}</td>
                    <td>{{ $row->pemesan }}</td>
                    <td>{{ $row->pic }}</td>
                    <td class="text-center"><?php echo $row->status == 1 ? "<span class='badge badge-danger'>OPEN</span>" : "<span class='badge badge-success'>CLOSED</span>" ;?></td>
                    <td class="text-center">
                        <div class="list-icons">
                            <a href="{{ route('showProject',$row->id) }}" class="list-icons-item text-info-600" title="Detail Project"><i class="icon-eye8"></i></a>
                            <a href="{{ action('ProjectCont@destroy',['id'=>$row->id]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="5" class="text-center">TOTAL</td>
                    <td hidden class="text-right">{{Rupiah_no($dpp,0) }}</td>
                    <td hidden class="text-right">{{Rupiah_no($ppn,0) }}</td>
                    <td class="text-right">{{Rupiah_no($ppn+$dpp,0) }}</td>
                    <td hidden class="text-right">{{ $pembayaran ? Rupiah_no($pembayaran,0) : 0}}</td>
                    <td hidden class="text-right">{{Rupiah_no($dpp + $ppn - $pembayaran,0) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection