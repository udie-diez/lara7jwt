@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                 <a class="btn btn-outline-info btn-sm" href="{{ route('pinjamanInput') }}"   title="Input Pengajuan Pinjaman Baru"> <i class="icon-plus2"></i> Pengajuan Pinjaman Baru</a>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="form-group row">
                <div class="col-sm-12">
                    @if ($message = Session::get('exist'))
                    <div class="alert alert-warning alert-block">
                        {{ $message }}
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header header-elements-inline bg-slate" style="padding: 0.375rem 1rem;">
                        <h6 class="card-title">1. Isi Form Permohonan </h6>
                    </div>
                    <div class="card-body">


                        <form method="POST" action="{{ route('storePinjaman') }}">
                            @csrf
                            @if(Auth::user()->role=='admin' || Auth::user()->role=='karyawan')
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">Nama Anggota</label>
                                <div class="col-sm-7">
                                    <select name="anggota" id="anggota" data-placeholder="Pilih Anggota" class="select-search">
                                        <option value=""></option> 
                                        @foreach($anggota as $r)
                                        <option value="{{$r->nik}}" <?php if (isset($nikanggota)) {
                                                                        if ($nikanggota == $r->nik) echo 'selected';
                                                                    } ?>>{{$r->nik .' - '.$r->nama}}</option>
                                        @endforeach

                                    </select>
                                    <input type="hidden" name="id" id="idx" class="form-control " value="{{ $data->idpinjaman ?? '' }}">

                                </div>
                            </div>
                            @elseif(Auth::user()->role=='anggota')
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">No.Anggota</label>
                                <div class="col-sm-7">
                                    <input type="text" readonly name="nomor" placeholder="(Terisi Otomatis)" class="form-control  border-warning" value="{{ $data->nomor ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">Nama Lengkap</label>
                                <div class="col-sm-7">
                                    <input type="hidden" name="anggotaid" value="{{ $data->id ?? '' }}">
                                    <input type="text" readonly name="nama" placeholder="(Terisi Otomatis)" class="form-control border-warning" value="{{ $data->nama ?? '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3">NIK</label>
                                <div class="col-sm-7">
                                    <input type="text" id="nik" name="nik" readonly placeholder="(Terisi Otomatis)" class="form-control  border-warning" value="{{ $data->nik ?? '' }}">
                                </div>
                            </div>
                            
                            @endif

                            <div class="form-group row mt-5 ">
                                <label class="col-form-label col-sm-3"> Gaji Terakhir</label>
                                <div class="col-sm-3 ">
                                    <input type="text" id="gaji" name="gaji" required placeholder="Rp. / bulan" class="form-control text-right " value="{{ number_format($data->gaji ?? 0 ,0,',','.') ?? old('gaji') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"> Plafon Pinjaman</label>
                                <div class="col-sm-3">
                                    <input type="text" style="font-size: large;" required id="nilai" name="nilai" placeholder="Rp." class="form-control text-right" value="{{ number_format($data->nilai ?? 0,0,',','.') ?? old('nilai') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"> Tenor</label>
                                <div class="col-sm-3">
                                    <input type="text" required id="tenor" name="tenor" placeholder="bulan" class="form-control text-right" value="{{ $row->tenor ?? '12' }}">

                                </div>
                                <label class="col-form-label col-sm-2">(bulan)</label>

                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"> Untuk Keperluan</label>
                                <div class="col-sm-7">
                                    <input type="text" name="keperluan" id="keperluan" required placeholder="..." class="form-control border-warning" value="{{ $data->keperluan ?? old('keperluan') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-form-label col-sm-3"> Tanggal Pengajuan<sup class="text-danger"></sup></label>
                                <div class="col-sm-7">
                                    <input type="text" name="tanggal" placeholder="Tanggal" class="form-control pickadate-year" value="{{ $data->tanggal ??  date('d/m/Y') }}">
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
                            <div class="form-group mt-5">
                                <button type="submit" <?php if (isset($data->idpinjaman)) if ($data->idpinjaman > 0) echo 'disabled' ?> class="btn btn-outline-info" onclick="return confirm('Anda yakin ingin mengajukan pinjaman ini')" id="btn-submit">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-header header-elements-inline bg-slate" style="padding: 0.375rem 1rem;">
                        <h6 class="card-title">2. Upload Dokumen Persyaratan </h6>
                    </div>
                    <div class="card-body">

                        <div class="form-group row">
                            <div class="col-sm-12">
                                <div class="form-group row">
                                    <label class="col-form-label col-sm-3">Slip Gaji</label>
                                    <div class="col-sm-7">
                                        <input type="text" readonly name="slip" placeholder="File (jpg | png | pdf) max:2MB " required class="form-control  border-warning" value="{{ $data->slip ?? '' }}">
                                    </div>
                                    <div class="col-sm-1">
                                        <a class="btn btn-outline-info btn-sm" href="#" data-toggle="modal" data-target="#modalFile" onclick="upload()">
                                            <i class="icon-file-upload"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                @if ($message = Session::get('ok'))
                                <div class="alert alert-success alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header header-elements-inline bg-slate" style="padding: 0.375rem 1rem;">
                        <h6 class="card-title">Simulasi Kredit </h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="#">
                            @csrf
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Plafon Pinjaman</label>
                                <div class="col-sm-5">
                                    <input type="text" style="font-size: large;" required id="nilais" name="nilai" placeholder="Rp." class="form-control text-right" value="{{ number_format($data->nilai ?? 0,0,',','.') ?? old('nilai') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-sm-4">Tenor</label>
                                <div class="col-sm-5">
                                    <input type="text" required id="tenors" name="tenor" placeholder="bulan" class="form-control text-right" value="{{ $row->tenor ?? '12' }}">

                                </div>
                                <label class="col-form-label col-sm-2">(bulan)</label>

                            </div>
                            <div  class="form-group row">
                                <label  class="col-form-label col-sm-9"></label>
                                <label hidden class="col-form-label col-sm-4">Margin</label>
                                <div hidden class="col-sm-5">
                                    <input type="text" readonly id="margins" name="margins" placeholder="Rp." class="form-control text-muted" value="10%">
                                </div>
                                <div class="col-sm-">
                                    <button type="button" id="btn-simulasi" title="tampilkan simulasi kredit" onclick="simulasi()" class="btn btn-outline-warning btn-sm"><i class="icon-circle-right2"></i></button>
                                </div>
                            </div>

                            <div class="form-group row mt-3" id="tabelsimulasi">
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
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
<!-- /basic datatable -->
<meta name="csrf-token" content="{{ csrf_token() }}" />

<script type="text/javascript">
    $(document).ready(function() {

        $('.pickadate-year').pickadate({
			format: 'dd/mm/yyyy',
			selectYears: 6,
			selectMonths: 12,
			max: true,
            clear: false,
            today: false,
            close:false
		});

        var nilai = document.getElementById('nilai');
        nilai.addEventListener('keyup', function(e) {
            nilai.value = formatRupiah(this.value);
        });

        var nilais = document.getElementById('nilais');
        nilais.addEventListener('keyup', function(e) {
            nilais.value = formatRupiah(this.value);
        });

        var gaji = document.getElementById('gaji');
        gaji.addEventListener('keyup', function(e) {
            gaji.value = formatRupiah(this.value);
        });

        var tenor = document.getElementById('tenor');
        tenor.addEventListener('keyup', function(e) {
            tenor.value = formatRupiah(this.value);
        });

        var tenors = document.getElementById('tenors');
        tenors.addEventListener('keyup', function(e) {
            tenors.value = formatRupiah(this.value);
        });

        $('#tenors').on('change', function() {
            if (this.value < 12) {
                $('#margins').val('1% (perBulan)');
            } else {
                $('#margins').val('10%');

            }
        })

    })

    function simulasi() {

        var plafon = $('#nilais').val();
        var tenor = $('#tenors').val();
        var margin = $('#margins').val();

        $('#btn-simulasi').html("<i class='icon-spinner9 spinner position-left'></i>");
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
                $('#btn-simulasi').html('<i class="icon-circle-right2"></i>');

                $('#tabelsimulasi').html("");
                $('#tabelsimulasi').html(data);
            }
        });
    }

    function upload() {
        var id = $('#idx').val();
        console.log('no '+id);
        $('#iditem').val(id);
        $('#jenisitem').val('slip');
        if(!id){
            
            alert('Perhatian. Isi Form Permohanan harus diisi dan Disimpan terlebh dahulu sebelum meng-upload dokumen  !.');
        }
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