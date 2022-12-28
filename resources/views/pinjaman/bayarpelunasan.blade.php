@extends('layouts.home')

@section('maincontent') 

<style>
    .table-ang{
        font-size: small;
        width: 100%;
    }
</style>
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
                <a href="{{ route('daftarPelunasan') }}" class="btn btn-outline-info btn-sm" >Daftar Pelunasan</a>
                <a href="{{ route('bayarPelunasan') }}" class="btn btn-outline-info btn-sm ml-1" ><i class="icon-plus2"></i> Pelunasan Baru </a>
           
        </div>
    </div>

    @if($view['kode']=='pembayaran')
    <div class="card-body">
        <div class="form-group row">
            <label class="col-form-label col-sm-1">ANGGOTA  : </label>
                
            <div class="col-sm-3">
                    <select name="f_anggota" id="f_anggota" data-placeholder="Cari Anggota" class="select-search">
                        <option value=""></option>
                        @foreach($anggota as $r)
                            <option value="{{$r->nik}}" <?php if(isset($anggotaid_f)){if($anggotaid_f==$r->nik) echo 'selected';} ?>>{{$r->nik .' - '.$r->nama}}</option>
                        @endforeach

                    </select>
            </div> 
            <div class="col-sm-1">
                <input type="hidden" id="kodebayar" value="pelunasan">
                <button type="button" id="btn_filter" class="btn btn-outline-info btn-sm">Tampilkan</button>
            </div>
        </div>
    </div>
    @endif
    <div class="card-body" >
        <div class="col-sm-12" id="taghasil"><?=@$view['view']?></div>

    </div>
</div>
<!-- /basic datatable -->
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('#btn_filter').on('click', function(){
            var id = $('#f_anggota').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url = "<?php echo route('detailPeminjam'); ?>";
            var pinjamanid='';
            var kode='pelunasan';
            $.ajax({
                type:"POST",
                url: url,
                data:{id:id,pinjamanid:pinjamanid,kode:kode,_token: CSRF_TOKEN,},
                success:function(data){

                    $('#taghasil').html("");
                    $('#taghasil').html(data);
                }
                });
        })


       

    })

</script>
@endsection
@include('layouts.upload')