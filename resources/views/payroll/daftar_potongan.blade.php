@extends('layouts.home') 

@section('maincontent')
@include('layouts.mylib')

<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/responsive.min.js"></script>

<div class="card">
<div class="card">
    <div class="card-body">
        <form method="POST" action = "{{ route('filterPotongan') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-sm-1  font-weight-bold">FILTER  : </label>
                <label class="col-form-label pr-2">Periode :</label>
                <div class="col-sm-2">
                    @php $bulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
                     <select name="f_bulan" id="f_bulan" class="select">
                         @for($i=0;$i < 12; $i++)
                         <option value={{$i+1}} <?php 
                                if(isset($bulan_f)){
                                    if($bulan_f==$i+1) echo 'selected';
                                }else{
                                    if(($i+1)==date('m')) echo 'selected';
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
    </div>
    <div class="card-header header-elements-inline">
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a hidden class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ route('pinjamanInput') }}" title="Input Permohonan Pinjaman"> <i class="icon-plus2"></i> Permohonan Baru</a>
            </div>
        </div>
    </div>
 
    <table class="table basicx table-hover" id="table_1" style="font-size: smaller;">
        <thead>
            <tr class="text-center">
                <th rowspan="2" class="export">NO.</th>
                <th rowspan="2" class="export">NIK</th>
                <th rowspan="2" class="export">NAMA</th>
                <th rowspan="2" class="export">KODE KOPERASI</th>
                <th rowspan="2" class="export">NAMA KOPERASI</th>
                <th colspan="3" class="export">ANGSURAN</th>
                <th rowspan="2" class="export">TOTAL</th>
                <th rowspan="2" class="export">TANGGAL AWAL</th>
                <th rowspan="2" class="export">TANGGAL AKHIR</th>
                <th rowspan="2" class="export">KETERANGAN</th>
            </tr>
            <tr>
                <th>SIMPANAN</th>
                <th>ANGSURAN</th>
                <th>LAIN-LAIN</th>
            </tr>
        </thead>
        <tbody>
        <?php $nik=''; $angsur = $lainlain = $simpanan = $total = 0 ?>
        @for($i=0;$i < count($angsuran); $i++)
            <?php
                $angsur += $angsuran[$i]['angsuran'];
                $lainlain += $angsuran[$i]['lainlain'];
                $simpanan += $angsuran[$i]['simpanan'];
                $total = $angsuran[$i]['angsuran'] + $angsuran[$i]['lainlain'] + $angsuran[$i]['simpanan'];
                if(isset($bulan_f)){
                    $tgl = '01/'.$bulan_f.'/'.$tahun_f;
                }else{
                    $tgl = '01/'.date('m/Y');
                }
            ?>
            <tr>
                <td>{{ $i+1 }}.</td>
                <td class="text-center">{{ $angsuran[$i]['nik'] }}</td>
                <td> {{ $angsuran[$i]['nama'] }}</td>
                <td class="text-center">KP805</td>
                <td class="text-center">KOPKAR TRENDY</td>
                <td class="text-right">{{ number_format($angsuran[$i]['simpanan'] ,0,'.',',') }}</td>
                <td class="text-right">{{ number_format($angsuran[$i]['angsuran'] ,0,'.',',') }}</td>
                <td class="text-right">{{ number_format($angsuran[$i]['lainlain'] ,0,'.',',') }}</td>
                <td class="text-right">{{ number_format($total ,0,'.',',')  }}</td>
                <td class="text-center">{{ $tgl }}</td>
                <td class="text-center"></td>
                <td class="text-center"> </td>
            </tr>
        @endfor
        </tbody>
        <tfoot>
            <tr style="font-weight: bold;">
                <td colspan="5">TOTAL</td>
                <td class="text-right">{{ number_format($simpanan ,0,'.',',') }}</td>
                <td class="text-right">{{ number_format($angsur ,0,'.',',') }}</td>
                <td class="text-right">{{ number_format($lainlain ,0,'.',',') }}</td>
                <td class="text-right">{{ number_format($angsur + $simpanan + $lainlain ,0,'.',',') }}</td>
                <td class="text-right"></td>
                <td class="text-right"></td>
                <td class="text-right"></td>
            </tr>
        </tfoot>
    </table>
</div>
<!-- /basic datatable --> 
@endsection