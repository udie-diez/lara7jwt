@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<style>
    .custom-checkbox {
        margin-top: 5px;
    }

    .table,
    .datatable-header,
    .datatable-footer {
        width: 75%;
    }

    .table td {
        padding: 0.25rem
    }
</style>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<script src="{{ url('/')}}/global_assets/js/plugins/forms/styling/uniform.min.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.form-check-input-styled').uniform();

        $('.aksestbl').DataTable({
            pageLength: 100,
            ordering: false,
            dom: '<"datatable-header"B>'
        });

    $('#ck').on('change',function(){
        $('input:checkbox').not(this).prop('checked', this.checked);
        
    })

    })
</script>

<div class="card">
    <div class="card-header">
        <h5 class="card-title">{{$tag['judul']}}</h5>

        <div class="form-group row mb-0" style="font-size:large;">
            <label class="col-form-label col-sm-1">NAMA :</label>
            <div class="col-sm-9 pt-2">
                {{ $users['nama'] }}
            </div>
        </div>
        <div class="form-group row mt-0" style="font-size:large;">
            <label class="col-form-label col-sm-1">EMAIL :</label>
            <div class="col-sm-9 pt-2">
                {{ $users['email'] }}
            </div>
        </div>
    </div>
    <div class="card-body">
        <div>
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="ck" name="ck">
                <label class="custom-control-label" for="ck">Centang semua</label>
            </div>
        </div>
        <form method="POST" action="{{ route('updateAkses') }}">
            @csrf
            <table class="table table-bordered aksestbl">
                <thead class="text-center">
                    <tr>
                        <th>No</th>
                        <th class="text-left">Kategori</th>
                        <th class="text-left">Modul</th>
                        <th>Melihat</th>
                        <th>Tambah, <br> Ubah, <br>Hapus</th>
                        <th>Mencetak</th>
                    </tr>
                </thead>
                <?php
                function cekakses($modul, $item, $datax)
                {
                    foreach ($datax as $row) {
                        if ($row->modul == $modul) {
                            return $row->$item;
                            break;
                        }
                    }
                }

                ?>
                <tbody class="text-center">
                    <?php
                    $masterdata = 'anggota,pengurus,pengelola,pemesan,jenis_simpanan,sumber_pinjaman,register,perusahaan,vendor,produk,pajak,target_pegawai';
                    $simpanpinjam = 'simpanan,setoran,pengajuan_pinjaman,pinjaman,pembayaran_angsuran,pelunasan,tunggakan,potongan_payroll,rekon_payroll';
                    $project = 'project,invoice,pembayaran,panjar,persetujuan_panjar,rekapppn,rekappph';
                    $proc = 'pembelian,pemesanan,penawaran';
                    $keu = 'data_akun,saldo_awal,kas_bank,biaya,jurnal_umum';
                    $laporan = 'jurnal,buku_besar,neraca,laba_rugi,perubahan_modal,daftar_pajak';
                    $pengaturan = 'user,mapping';
                    //master data = 13, 
                    //simpin = 7,
                    //project = 3
                    //PROC = 3
                    //KEU = 5
                    //laporan = 6,
                    $modul_str = $masterdata . ',' . $simpanpinjam . ',' . $project . ',' . $proc . ',' . $keu . ',' . $laporan .','. $pengaturan;
                    $modularr = explode(',', $modul_str);
                    for ($i = 0; $i < count($modularr); $i++) {

                        if ($i <= 11) {
                            $kategori = 'MASTER DATA';
                        } else if ($i <= 20) {
                            $kategori = 'SIMPAN PINJAM';
                        } else if ($i <= 27) {
                            $kategori = 'PROJECT';
                        } else if ($i <= 30) {
                            $kategori = 'PROCUREMENT';
                        } else if ($i <= 35) {
                            $kategori = 'KEUANGAN';
                        } else if ($i <= 41) {
                            $kategori = 'LAPORAN';
                        }else if ($i <= 43) {
                            $kategori = 'PENGATURAN';
                        }
                    ?>
                        <tr>
                            <td>{{$i+1}}</td>
                            <td class="text-left">{{$kategori}}</td>
                            <td class="text-left">{{ strtoupper($modularr[$i]) }}</td>
                            <td>
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="{{'r_'.$modularr[$i] }}" name="{{'r_'.$modularr[$i]}}" <?= @cekakses($modularr[$i], 'lihat', $data) == 'on' ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="{{'r_'.$modularr[$i]}}"></label>
                                </div>
                            </td>
                            <td>
                                @if($kategori!='LAPORAN')
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="{{ 'cud_'.$modularr[$i] }}" name="{{ 'cud_'.$modularr[$i] }}" <?= @cekakses($modularr[$i], 'cud', $data) == 'on' ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="{{ 'cud_'.$modularr[$i] }}"></label>
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($kategori!='MASTER DATA' && $kategori!='PENGATURAN' && $kategori!='KEUANGAN')
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="{{ 'p_'.$modularr[$i] }}" name="{{ 'p_'.$modularr[$i] }}" <?= @cekakses($modularr[$i], 'cetak', $data) == 'on' ? 'checked' : '' ?>>
                                    <label class="custom-control-label" for="{{ 'p_'.$modularr[$i]}}"></label>
                                </div>
                                @endif

                            </td>
                        </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
            <div class="text-right mt-5 table">
                <input type="hidden" name="id" value="{{ $users['id'] }}">
                <input type="hidden" name="modul" value="{{$modul_str}}">
                <a href="{{ route('users') }}" class="btn btn-outline-danger btn-sm">Kembali</a>
                <button type="submit" onclick="return confirm('Anda ingin menyimpan Data ini ?')" class="btn btn-outline-info btn-sm">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection