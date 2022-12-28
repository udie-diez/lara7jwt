@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<style>
    .table {
        font-size: small;
    }
</style>
<?php

use Illuminate\Support\Facades\Session; ?>

<div class="card">
    <div class="card-body">

        <h5>{{$tag['judul']}}</h5>
        <!-- <div class="col-sm-11">
                <div class="alert alert-danger" style="font-size:small;">
                    <ul>
                        <li>Pastikan data payroll yang akan di-upload adalah file Excel (.xlsx, .xls)</li>
                        <li>Pastikan file payroll tersebut sesuai dengan format yang telah ditentukan.</li>
                        <li>Isi file payroll hanya terdiri dari 1 sheet</li>
                    </ul>
                </div>
            </div> -->

        <div class="form-group row">
            <label class="col-form-label col-sm-3">1. Tentukan Periode Pemotongan:</label>
            <div style="width: 100px;">
                @php $bulan=['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember']; @endphp
                <select name="f_bulan" id="f_bulan" class="select">
                    @for($i=0;$i < 12; $i++) <option value={{$i+1}} <?php


                                                                    if (Session::get('bulan_f')) {
                                                                        if (Session::get('bulan_f') == $i + 1) echo 'selected';
                                                                    } else {
                                                                        if (($i + 1) == date('m')) echo 'selected';
                                                                    }
                                                                    ?>>{{ $bulan[$i] }}</option>
                        @endfor
                </select>
            </div>
            <div class="col-sm-1">
                <input type="text" name="f_tahun" id="f_tahun" placeholder="Tahun" class="form-control" value="<?php echo Session::get('tahun_f') ?? date('Y') ?>">
            </div>

        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3">2. Import Data Pemotongan Payroll:</label>
            <div style="float: right;">
                <a class="btn btn-outline-info btn-sm" href="#" data-toggle="modal" data-target="#modalFile" onclick="upload()"><i class="icon-download"></i> Import</a>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3">3. Proses Payroll Simpanan:</label>
            <div style="float: right;">
                <a class="btn btn-outline-info btn-sm" onclick="proses('Simpanan')" href="#" title="Proses Payroll Simpanan"><i class="icon-cog3"></i> Proses</a>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-form-label col-sm-3">4. Proses Payroll Angsuran / Lain-lain:</label>
            <div style="float: right;">
                <a class="btn btn-outline-info btn-sm" onclick="proses('Angsuran/Lainlain ')" href="#" title="Proses Payroll Angsuran/Lain-lain"><i class="icon-cog3"></i> Proses</a>
            </div>
        </div>
    </div>


    <div class="form-group row ml-3">
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
                {{ $message }}
            </div>
            @endif
        </div>
    </div>


    <hr>
    <div class="card-header  header-elements-inline">
        <h5 class="card-title" id="title-form">DATA PEMOTONGAN PAYROLL</h5>

    </div>

    <div class="card-body">
        <div class="col-sm-11">
            <p class="font-weight-bold">Hasil Proses Rekon Data Payroll</p>
            <p>- Payroll Simpanan : <span class="font-weight-bold" id="simpanan-hasil"><?= @Session::get('jumlahsimpanan_f') ?? 0 ?> Data</span></p>
            <p>- Payroll Angsuran : <span class="font-weight-bold" id="angsuran-hasil"><?= @Session::get('jumlahangsuran_f') ?? 0 ?> Data</span></p>
            <p>- Payroll Lainlain : <span class="font-weight-bold" id="angsuran-hasil"><?= @Session::get('jumlahlainlain_f') ?? 0 ?> Data</span></p>
        </div>
        <table class="table table-hover basic ml-2" style="font-size: small;">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NIK</th>
                    <th>NAMA ANGGOTA</th>
                    <th>SIMPANAN</th>
                    <th>PAYROLL SIMPANAN</th>
                    <th>ANGSURAN</th>
                    <th>PAYROLL ANGSURAN</th>
                    <th>LAINLAIN</th>
                    <th>PAYROLL LAINLAIN</th>
                    <th>TGL.AWAL</th>
                    <th>TGL.AKHIR</th>
                    <th>PERIODE</th>
                </tr>
            </thead>
            <tbody>

                @foreach($data as $row)
                <?php
                $tag_simpanan = $row->status_simpanan == 1 ? "<i class='text-info icon-checkmark-circle2'></i>" : "";
                $tag_angsuran = $row->status_angsuran == 1 ? "<i class='text-info icon-checkmark-circle2'></i>" : "";
                $tag_lainlain = $row->status_lainlain == 1 ? "<i class='text-info icon-checkmark-circle2'></i>" : "";
                ?>
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td>{{ $row->nik }}</td>
                    <td>{{ $row->nama }}</td>
                    <td>{{ number_format($row->simpanan,0,',','.') }}</td>
                    <td>{{ number_format($row->py_simpanan,0,',','.') }} <?= @$tag_simpanan ?></td>
                    <td>{{ number_format($row->angsuran,0,',','.') }} </td>
                    <td>{{ number_format($row->py_angsuran,0,',','.') }} <?= @$tag_angsuran ?></td>
                    <td>{{ number_format($row->lainlain,0,',','.')  }}</td>
                    <td>{{ number_format($row->py_lainlain,0,',','.') }} <?= @$tag_lainlain ?></td>
                    <td>{{ IndoTgl($row->tgl_awal) }}</td>
                    <td>{{ IndoTgl($row->tgl_akhir) }}</td>
                    <td>{{ date('m-Y', strtotime($row->periode)) }}</td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection
@include('layouts.upload')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<script type="text/javascript">
    function proses(kode) {

        if (confirm('Anda yakin ingin memproses data Payroll-' + kode + ' ?')) {
            url = (kode == 'Simpanan') ? "<?php echo route('prosesPayrollSimpanan', ''); ?>" : "<?php echo route('prosesPayrollAngsuran', ''); ?>";

            var bulan = $('#f_bulan').val();
            var tahun = $('#f_tahun').val();
            var periode = tahun + '_' + bulan + '_1';
            location.href = url + '/' + periode;

            // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            // $.post( url, { periode: periode, _token: CSRF_TOKEN} );

            // var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            // $.ajax({
            //     type: "POST",
            //     url: url,
            //     asynchronous: true,
            //     data: {
            //         periode: periode,
            //         _token: CSRF_TOKEN,
            //     },
            //     success: function(data) {
            //         if (data) {
            //             location.reload();
            //         } else {
            //             alert('error. '+ data);
            //         }
            //     }
            // })
        }
    }


    function upload() {
        var bulan = $('#f_bulan').val();
        var tahun = $('#f_tahun').val();
        var periode = tahun + '/' + bulan + '/1';
        $('#iditem').val(tahun + '/' + bulan + '/1');
        $('#jenisitem').val('rekon');

        //cek data

        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = "<?php echo route('cekDataRekon'); ?>";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                periode: periode,
                _token: CSRF_TOKEN,
            },
            success: function(data) {
                if (data == true) {
                    $('#upload-msg').show();
                    $('#upload-msg').html('Data rekon untuk periode bulan ' + bulan + '/' + tahun + ' sudah pernah di Upload, Anda yakin ingin melanjutkan ? Jika YA maka data lama akan tertimpa/terhapus.');
                }
            }
        });

    }
</script>