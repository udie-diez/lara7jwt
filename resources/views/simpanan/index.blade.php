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
$(document).ready(function(){

    $('.form-check-input-styled').uniform();

	$('.select-search').select2();

    $("#tglfilter").on('DOMSubtreeModified',function(){
        document.getElementById("tglFilter").value = this.innerHTML;
     });

     $('#ck_tanggal').on('change',function(){
            if(this.checked==true){
                $('#btn-tanggal').prop('disabled',false);
            }else{
                $('#btn-tanggal').prop('disabled',true);

            }
        })

     var ck = "{{ $ck_tanggal ?? '' }}";
     if(ck){
         $('#ck_tanggal').prop('checked',true);
         $('#btn-tanggal').prop('disabled',false);

        var tgl = "{{ $tgl ?? '' }}";
        if(tgl) $("#tglfilter").html(tgl);  
     }

})  

</script>
<style>
    .uniform-checker{
        margin-top: 10px;
    }
</style>
<div class="card"> 
    <div class="card">
    <div class="card-body">
        <form method="POST" action = "{{ route('filterSimpanan') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-sm-1 font-weight-bold">FILTER  : </label>
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
                    <select name="f_anggota" id="f_anggota" data-placeholder="Pilih Anggota" class="select-search"  data-fouc>
                        <option value=""></option>
                        <option value="all" <?php if(isset($anggotaid_f)){if($jenis_f=='all') echo 'selected';} ?>>SEMUA ANGGOTA</option>

                        @foreach($anggota as $r)
                            <option value="{{$r->id}}" <?php if(isset($anggotaid_f)){if($anggotaid_f==$r->id) echo 'selected';} ?>>{{$r->nik .' ' .$r->nama}}</option>
                        @endforeach

                    </select>

                </div>
                <div class="col-sm-3">
                        <select name="f_jenissimpanan" id="f_jenissimpanan" data-placeholder="Pilih Simpanan" class="select" data-fouc>
                            <option value=""></option>
                            <option value="all" <?php if(isset($jenis_f)){if($jenis_f=='all') echo 'selected';} ?>><strong>SEMUA SIMPANAN</strong></option>

                            @foreach($jenis_simpanan as $r)
                                <option value="{{$r->nama}}" <?php if(isset($jenis_f)){if($jenis_f==$r->nama) echo 'selected';} ?>>{{$r->nama}}</option>
                            @endforeach

                        </select>
                </div>
                <div class="col-sm-1">
                    <input type="hidden" id="idxx" value="{{$akses ?? ''}}">
                    <button type="submit" class="btn btn-outline-primary btn-sm" id="btn-submit-f">Tampilkan</button>
                </div>
            </div>

        </form>
    </div>
    </div>
    <div class="card-header  header-elements-inline">
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
            </div>
        </div>
    </div> 
 
    <table class="table table-hover basicx">
        <thead>
            <tr>
                <th>NO</th>
                <th hidden>ID</th>
                <th>NO.ANGGOTA</th>
                <th>NAMA ANGGOTA</th>
                <th>NIK</th>
                <th class="text-center">SIMPANAN</th>
                <th>JUMLAH (Rp)</th>
                <th>BULAN</th>
                <th>TAHUN</th>
            </tr>
        </thead>
        <tbody>
        
        @foreach($data as $row)

            <tr>
                <td>{{ $loop->iteration }}.</td>
                <td hidden>{{ url('setoran/').'/'.$row->id }}</td>
                <td> {{ $row->nomor }}</td>
                <td> {{ $row->nama_anggota }}</td>
                <td> {{ $row->nik }}</td>
                <td class="text-center">{{ $row->jenis_simpanan }}</td>
                <td  class="text-right">{{ number_format($row->nilai,0,'.',',') }}</td>
                <td> {{ strtoupper(bulan($row->bulan))}}</td>
                <td> {{  $row->tahun }}</td>
                 
            </tr>
        @endforeach
        </tbody>
        <tfoot>
            <tr class="font-weight-bold">
                <td></td>
                <td hidden></td>
                <td></td>
                <td></td>
                <td></td>
                <td class="text-center">TOTAL</td>
                <td class="text-right">{{ number_format($data->sum('nilai')) }}</td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
</div>
<!-- /basic datatable --> 
@endsection