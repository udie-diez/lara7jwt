@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<style>
    .modal-content {
        float: right;
        min-width: 800px;
    }
</style>
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a href="{{ route('daftarPermohonan') }}" class="btn btn-outline-info">
                    << <i class="icon-stack"></i> Permohonan
                </a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-sm-2">
                <div class="form-group row">
                    <div class="col-sm-12">
                        @if(file_exists( public_path().'/assets/photo/'. ($data->pic ?? 'xx.jpg') ))
                        <a href="{{ url('/').'/assets/photo/'. $data->pic}}" data-popup="lightbox">
                            <img src="{{ url('/').'/assets/photo/'. $data->pic}}" width="120" height="120" alt="">
                        </a>
                        @else
                        <a href="{{ url('/').'/assets/images/nopic.jpg' }}" data-popup="lightbox">
                            <img src="{{ url('/').'/assets/images/nopic.jpg' }}" width="120" height="120" alt="">
                        </a>

                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <form method="POST" action="{{ route('storePinjaman') }}">
                    @csrf
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">STATUS</label>
                        <div class="col-sm-3">
                            @php 
                                $status = ['PENGAJUAN BARU','DITOLAK','DIPENDING','SEDANG DIPROSES','SEDANG DIPROSES','SEDANG DIPROSES','SEDANG DIPROSES','SEDANG DIPROSES','SEDANG DIPROSES','DISETUJUI'];    
                            @endphp
                            <span class="form-control badge badge-warning" style="font-size:12px;">{{ $status[$data->status] }}</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">No.Anggota</label>
                        <div class="col-sm-7">
                            <input type="text" readonly name="nomor" required class="form-control  border-warning" value="{{ $data->nomor ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Nama Lengkap</label>
                        <div class="col-sm-7">
                            <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                            <input type="text" readonly name="nama" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">NIK</label>
                        <div class="col-sm-7">
                            <input type="text" id="nik" name="nik" readonly required placeholder="(Terisi Otomatis)" class="form-control  border-warning" value="{{ $data->nik ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3"> Gaji Terakhir</label>
                        <div class="col-sm-3">
                            <input type="text" id="gaji" readonly name="gaji" placeholder="Rp. / bulan" class="form-control" value="Rp. {{ number_format($data->gaji ?? 0 ,0,',','.') ?? old('gaji') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Plafon Pinjaman</label>
                        <div class="col-sm-3">
                            <input type="text" style="font-size: large;" readonly id="nilai" name="nilai" placeholder="Rp." class="form-control" value="Rp. {{ number_format($data->nilai ?? 0,0,',','.') ?? old('nilai') }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Tenor</label>
                        <div class="col-sm-3">
                            <input type="text" readonly id="tenor" name="tenor" class="form-control" value="{{ $data->tenor ?? '' }} BULAN">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Untuk Keperluan</label>
                        <div class="col-sm-7">
                            <input type="text" name="keperluan" id="keperluan" readonly placeholder="..." class="form-control border-warning" value="{{ $data->keperluan ?? old('keperluan') }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-3">Tanggal Pengajuan<sup class="text-danger"></sup></label>
                        <div class="col-sm-7">
                            <input type="text" readonly name="tanggal" placeholder="Tanggal" class="form-control" value="{{ date('d/m/Y h:m:s', strtotime($data->tanggal)) }}">
                        </div>
                    </div>



                </form>
            </div>

            <div class="col-sm-3">
                <div class="form-group row">
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Slip Gaji</label>
                        <div class="col-sm-8">
                            @if(file_exists( public_path().'/assets/slip/'. ($data->slip ?? 'xx.jpg') ))
                            <a href="{{ url('/').'/assets/slip/'.$data->slip }}" data-popup="lightbox">
                                <img src="{{ url('/').'/assets/slip/'.$data->slip  }}" width="150" height="150" alt="">
                            </a>
                            @else
                            <img src="{{ url('/').'/assets/images/no-image.png' }}" width="120" height="120" alt="">
                            @endif
                        </div>

                    </div>
                </div>
           

                <hr>
                <div class="form-group row">
                    <label class="col-form-label col-sm-12"><h6 class="text-orange-800">DATA SIMPANAN ANGGOTA</h6></label>
                    <label class="col-form-label col-sm-5">Saldo Simpanan</label>
                    <div class="col-sm-4">
                        <input type="text" readonly name="id" id="idx" class="form-control " value="Rp. {{ number_format($simpanan,0) ?? 0 }}">
                    </div>
                    <div class="col-sm-2">
                        <a class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('PinjamanCont@getSimpanan',['id'=>$data->idanggota]) }}" title=" Data Simpanan " data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-stack3"></i></a>

                    </div>
                </div>
                <hr>

                <div class="form-group row">
                    <label class="col-form-label col-sm-12"><h6 class="text-orange-800">PINJAMAN SAAT INI</h6></label>
                    <label class="col-form-label col-sm-3">Plafon</label>
                    <div class="col-sm-6">
                        <input type="text" readonly name="id" id="idx" class="form-control " value="Rp. {{ number_format($pinjaman->nilaifix ?? 0,0) ?? 0 }}">
                    </div>
                     
                    <div class="col-sm-2">
                        <a class="btn btn-outline-info btn-sm modalMd" href="#" value="{{ action('PinjamanCont@getPinjaman',['id'=>$data->idanggota]) }}" title=" Data Pinjaman " data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false"><i class="icon-stack3"></i></a>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">
        <div class="col-sm-7">

            <div class="card">
                <div class="card-header header-elements-inline bg-slate" style="padding: 0.375rem 1rem;">
                    <h6 class="card-title">PROSES PERMOHONAN PINJAMAN</h6>

                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('updatePinjaman')}}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Plafon Yang Disetujui</label>
                            <div class="col-sm-3">
                                <input type="text" style="font-size: large;" required id="nilaifix" name="nilaifix" placeholder="Rp." class="form-control" value="{{ $data->nilaifix ? number_format($data->nilaifix ,0,',','.') : '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Tenor (Bulan)</label>
                            <div class="col-sm-3">
                                <input type="text" required id="tenorfix" name="tenor" placeholder="bulan" class="form-control" value="{{ $data->tenorfix ?? '' }}">
                            </div>
                            <div class="col-sm-4">
                                <button type="button" id="btn-angsuran" class="btn btn-outline-warning btn-sm" title="Lihat Tabel Angsuran">Tabel Angsuran >></button>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Angsuran PerBulan</label>
                            <div class="col-sm-3">
                                <input type="text" style="font-size: large;" required id="angsuran" name="angsuran" placeholder="Rp." class="form-control" value="{{ $data->angsuranfix ? number_format($data->angsuranfix ,0,',','.') : '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Awal Potongan</label>
                            <div class="col-sm-3">
                                <input type="text" required id="awal" name="awal"  class="form-control  pickadate-year" value="<?php if(isset($data->tgl_awal)) echo date('d/m/Y', strtotime($data->tgl_awal)) ?>">
                            </div>
                            <label class="col-form-label col-sm-2"> sampai</label>

                            <div class="col-sm-3">
                                <input type="text" required id="akhir" name="akhir"  class="form-control"  value="<?php if(isset($data->tgl_akhir)) echo date('d/m/Y', strtotime($data->tgl_akhir)) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Sumber Pinjaman</label>
                            <div class="col-sm-5">
                                <select name="sumber" id="sumber" class="select" data-placeholder="Pilih">
                                    <option value=""></option>
                                    @foreach($sumber as $r)
                                    <option value="{{$r->id}}" <?php if (isset($data->sumber)) {
                                                                    if ($data->sumber == $r->id) echo 'selected';
                                                                } ?>>{{$r->nama}}</option>
                                    @endforeach

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Keputusan Permohonan</label>
                            <div class="col-sm-5">
                                <select name="keputusan" required id="keputusan" class="select" data-placeholder="Pilih">
                                    <option value=""></option>
                                    <option value=1 <?php if (isset($data->status)) if ($data->status == 1) echo 'selected' ?>>PERMOHONAN DITOLAK</option>
                                    <option value=3 <?php if (isset($data->status)) if ($data->status == 3) echo 'selected' ?>>PERMOHONAN SEDANG DIPROSES</option>
                                    <option value=9 <?php if (isset($data->status)) if ($data->status == 9) echo 'selected' ?>>PERMOHONAN DISETUJUI</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-3">Catatan</label>
                            <div class="col-sm-6">
                                <textarea required id="catatan" name="catatan" placeholder="catatan" class="form-control">{{ $data->catatan ?? '' }} </textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-9">

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                                @if ($message = Session::get('sukses'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mt-5">
                            <label class="col-form-label col-sm-2"></label>
                            <input type="text" hidden name="id" class="form-control " value="{{ $data->idpinjaman ?? '' }}">
                            <div class="col-sm-6 text-right">
                                <button type="submit" <?php if($canedit == false )echo 'disabled' ?> class="btn btn-outline-info btn-sm" onclick="return confirm('Anda yakin ingin menyimpan data ini')">Simpan</button>
                                <a href="{{ route('daftarPermohonan') }}" class="btn btn-outline-warning btn-sm">Tutup</a>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-5">

            <div class="card border-orange">
                <div class="card-header header-elements-inline bg-slate" style="padding: 0.375rem 1rem;">
                    <h6 class="card-title">TABEL ANGSURAN</h6>

                </div>
                <div class="card-body" id="tabelsimulasi">
                    <table class="table bg-green-300" style="font-size: smaller; ">
                        <thead>
                            <tr>
                                <th>Bulan</th>
                                <th>Pokok</th>
                                <th>Margin</th>
                                <th>Angsuran</th>
                                <th>Outstanding</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>
<!-- /basic datatable -->
<meta name="csrf-token" content="{{ csrf_token() }}" />

<script src="{{ url('/') }}/global_assets/js/plugins/media/fancybox.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $('.pickadate-year').pickadate({
			format: 'mm/dd/yyyy',  
            today: false,
            clear : false,
            close : false,
		});

        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });

        var nilaifix = document.getElementById('nilaifix');
        nilaifix.addEventListener('keyup', function(e) {
            nilaifix.value = formatRupiah(this.value);
        });

        var angsuran = document.getElementById('angsuran');
        angsuran.addEventListener('keyup', function(e) {
            angsuran.value = formatRupiah(this.value);
        });

        $('#awal').on('change', function(){
            var tenor =  $('#tenorfix').val();
            var tgl = this.value;
            $('#awal').val(getFormattedDate(tgl));
               
            tgl = new Date(tgl);

            var tgl = addMonths(new Date(tgl.getFullYear(),tgl.getMonth(), tgl.getDate()),tenor-1).toString();
            $('#akhir').val(getFormattedDate(tgl));
        })

        $('#tenorfix').on('change', function(){

            var tenor = this.value;
            $('#awal').val('');
            $('#akhir').val(''); 
        })

        

        $('#btn-angsuran').on('click', function() {
            var plafon = $('#nilaifix').val();
            var tenor = $('#tenorfix').val();
            var margin = 0;
            $('#tabelsimulasi').html("");

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url = "<?php echo route('simulasiKredit'); ?>";
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    plafon: plafon,
                    tenor: tenor,
                    margin: margin,
                    _token: CSRF_TOKEN,
                },
                success: function(data) {

                    $('#tabelsimulasi').html("");
                    $('#tabelsimulasi').html(data);

                }
            });
        })

    })

    function addMonths(date, months) {
    var d = date.getDate();
    date.setMonth(date.getMonth() + +months);
    if (date.getDate() != d) {
      date.setDate(0);
    }
    return date;
}

    function angsuran() {
        var plafon = $('#nilaifix').val();
        var tenor = $('#tenor').val();
        var margin = 0;
        $('#tabelsimulasi').html("");

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = "<?php echo route('simulasiKredit'); ?>";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                plafon: plafon,
                tenor: tenor,
                margin: margin,
                _token: CSRF_TOKEN,
            },
            success: function(data) {

                $('#tabelsimulasi').html("");
                $('#tabelsimulasi').html(data);
            }
        });
    }

    function formatRupiah(angka, prefix) {
        var number_string = angka.toString().replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa = split[0].length % 3,
            rupiah = split[0].substr(0, sisa),
            ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            var separator;
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        return prefix == undefined ? rupiah : (rupiah ? 'Rp. ' + rupiah : '');
    }
</script>
@endsection
@include('layouts.upload')