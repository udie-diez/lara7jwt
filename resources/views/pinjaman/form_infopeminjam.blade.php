@include('layouts.mylib')
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/datatables.min.js"></script>

<style>
    .modal-content {
        float: right;
        min-width: 900px;
    }

    .form-group {
        margin-bottom: 0.2rem;
    }

    .table_2 tbody {
        font-size: smaller;
        display: block;
        overflow: auto;
        height: 400px;
    }

    .table_2 thead tr {
        font-size: smaller;
        display: block;
    }
</style>
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
    </div>
    <div class="card-body">
        <div class="row">

            <div class="col-sm-3">
                <div class="form-group row">
                    <div class="col-sm-12">
                        @if(file_exists( public_path().'/assets/photo/'. ($anggota->pic ? $anggota->pic : 'xx.jpg') ))
                        <a href="{{ url('/').'/assets/photo/'. $anggota->pic}}" data-popup="lightbox">
                            <img src="{{ url('/').'/assets/photo/'. $anggota->pic}}" width="120" height="120" alt="">
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

                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">No.Anggota</label>
                        <div class="col-sm-7">
                            <input type="text" readonly name="nomor" required class="form-control  border-warning" value="{{ $anggota->nomor ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Nama Lengkap</label>
                        <div class="col-sm-7">
                            <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                            <input type="text" readonly name="nama" required class="form-control border-warning" value="{{ $anggota->nama ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">NIK</label>
                        <div class="col-sm-7">
                            <input type="text" id="nik" name="nik" readonly required placeholder="(Terisi Otomatis)" class="form-control  border-warning" value="{{ $anggota->nik ?? '' }}">
                        </div>
                    </div>
                </form>
            </div>
            @if($show=='pembayaran') 
            <div class="card col-sm-12 row mt-3" id='tag1'>
                <div class="card-header" style="padding: 0.375rem 1rem;;">
                    <h5 class="card-title">Daftar Pinjaman</h6>
                </div>
                <div class="card-body">
                    <table class="table table_1 table-bordered" style="font-size: small;">
                        <thead>
                            <tr class="text-center">
                                <th class="export">NO.</th>
                                <th hidden>ID</th>
                                <th class="export">NAMA</th>
                                <th class="export">NIK</th>
                                <th class="export">JUMLAH <br> PINJAMAN (Rp.)</th>
                                <th class="export">TENOR (Bln)</th>
                                <th class="export">SUMBER <br>PINJAMAN</th>
                                <th class="export">AWAL ANGSURAN</th>
                                <th class="export">AKHIR ANGSURAN</th>
                                <th class="export">ANGSURAN <br> PERBULAN (Rp.)</th>
                                <th class="text-center">PILIH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pinjaman as $row)
                                <?php
                                    $status = ['PENGAJUAN BARU', 'DITOLAK', 'DIPENDING', 'SEDANG DIPROSES'];
                                ?>

                                <tr>
                                    <td>{{ $loop->iteration }}.</td>
                                    <td hidden> {{ $row->id }}</td>
                                    <td> {{ $row->nama }}</td>
                                    <td class="text-center">{{ $row->nik }}</td>
                                    <td class="text-center">{{ number_format($row->nilaifix,0,'.',',') }}</td>
                                    <td class="text-center">{{ $row->tenorfix }}</td>
                                    <td class="text-center">{{ $row->namasumber }}</td>
                                    <td class="text-center">{{ date('d/m/Y', strtotime($row->tgl_awal)) }}</td>
                                    <td class="text-center">{{ date('d/m/Y', strtotime($row->tgl_akhir)) }}</td>
                                    <td class="text-center">{{ number_format($row->angsuranfix,0,'.',',')  }}</td>
                                    <td class="text-center">
                                        <div class="list-icons">
                                            <a href="#tag1" id="btn-edit" class="list-icons-item text-info-600" title="Detail Pinjaman"><span class="badge badge-info">Pilih</span></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif


        </div>
    </div>

    @if($kode=='angsuran')
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-header bg-orange-300" style="padding: 0.375rem 1rem;">
                    <h6 class="card-title">PEMBAYARAN ANGSURAN</h6>

                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('updateAngsuranPinjaman') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Angsuran PerBulan</label>
                            <div class="col-sm-2">
                                 <input type="hidden" readonly name="bayarangsuranid" class="form-control" value="{{ $bayarangsuranid ?? ''}}">
                                <input type="hidden"  readonly name="id" class="form-control" value="{{ $data->idpinjaman ?? ''}}">
                                <input type="hidden"  readonly name="nik" class="form-control" value="{{ $data->nik ?? ''}}">
                                <input type="text" readonly id="angsuran" name="angsuran" placeholder="Rp." class="form-control" value="{{ number_format($data->angsuranfix ?? 0 ,0,',','.') ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Jumlah bulan angsuran</label>
                            <div class="col-sm-2">
                                <input type="text" required id="jumlahbulan" name="jumlahbulan" placeholder="mis. 1 (bulan)" class="form-control" value="{{ $bulanbayar ?? '' }}">
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Jumlah Pembayaran</label>
                            <div class="col-sm-2">
                                <input type="text" readonly required style="font-size: medium;" id="jumlahbayar" name="nilai" placeholder="Rp." class="form-control text-right" value="{{ $nilaibayar ?? ''}}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Tanggal Pembayaran</label>
                            <div class="col-sm-3">
                                <input type="text" id="tgl" required name="tglbayar" class="form-control pickadate" value="{{ date('d/m/Y') }}">
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

                        <div class="form-group row mt-3">
                            <label class="col-form-label col-sm-2"></label>
                            <div class="col-sm-3">
                                <button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data pembayaran ANGSURAN ini ?')" class="btn btn-outline-info btn-sm">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
    @if($kode=='pelunasan')
    <div class="row">
        <div class="col-sm-12">

            <div class="card">
                <div class="card-header bg-orange-300" style="padding: 0.375rem 1rem;">
                    <h6 class="card-title">PEMBAYARAN PELUNASAN</h6>

                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('updatePelunasanPinjaman') }}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Angsuran PerBulan</label>
                            <div class="col-sm-2">
                                <input type="hidden"   readonly name="id" class="form-control" value="{{ $data->idpinjaman ?? ''}}">
                                <input type="hidden"   readonly name="nik" class="form-control" value="{{ $data->nik ?? ''}}">
                                <input type="text" readonly id="angsuran" name="angsuran" placeholder="Rp." class="form-control" value="{{ number_format($data->angsuranfix ?? 0 ,0,',','.') ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Sisa angsuran </label>
                            <div class="col-sm-2">
                                <input type="text"  readonly id="sisabulan" name="sisabulan" class="form-control" value="{{ $sisa['bulan'] ?? '' }} bulan">
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Sisa Pinjaman / Outstanding </label>
                            <div class="col-sm-2">
                                <input type="text"  readonly id="sisapinjaman" name="sisapinjaman" class="form-control" value="{{ number_format($sisa['outstanding'] ?? 0,0,',','.') }}">
                            </div>

                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Jumlah Pembayaran</label>
                            <div class="col-sm-2">
                                <input type="text" required <?php if($show=='pelunasan') echo 'readonly'; ?> style="font-size: medium;" id="jumlahbayar" name="nilai" placeholder="Rp." class="form-control text-right" value="{{ number_format($nilaibayar ?? 0,0,',','.') }}">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-form-label col-sm-2">Tanggal Pembayaran</label>
                            <div class="col-sm-3">
                                <input type="text" id="tgl" required <?php if($show=='pelunasan') echo 'disabled'; ?> name="tglbayar" class="form-control pickadate" value="{{ date('d/m/Y') }}">
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
                                @if ($message = Session::get('warning'))
                                <div class="alert alert-danger alert-block">
                                    <button type="button" class="close" data-dismiss="alert">×</button>
                                    <strong>{{ $message }}</strong>
                                </div>
                                @endif
                            </div>

                            
                        </div>

                        <div class="form-group row mt-3">
                            <label class="col-form-label col-sm-2"></label>
                            <div class="col-sm-3">
                                
                                @if($show=='pembayaran') 
                                <button type="submit" onclick="return confirm('Anda yakin ingin menyimpan data pembayaran PELUNASAN ini ?')" class="btn btn-outline-info btn-sm">Simpan</button>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
                           
    @endif
    <div class="row">
        <div class="col-sm-6">

            <div class="card">
                <div class="card-header bg-slate-300" style="padding: 0.375rem 1rem;;">
                    <h6 class="card-title">INFORMASI PINJAMAN</h6>

                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('updatePinjaman')}}">
                        @csrf
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Jumlah Pinjaman</label>
                            <div class="col-sm-5">
                                <input type="text" readonly id="nilaifix" name="nilaifix" placeholder="Rp." class="form-control" value="{{ number_format($data->nilaifix ?? 0 ,0,',','.') ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Tenor (Bulan)</label>
                            <div class="col-sm-5">
                                <input type="text" readonly id="tenorfix" name="tenor"   class="form-control" value="{{ $data->tenorfix ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Angsuran PerBulan</label>
                            <div class="col-sm-5">
                                <input type="text" readonly id="angsuran" name="angsuran"   class="form-control" value="{{ number_format($data->angsuranfix ?? 0 ,0,',','.') ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Sisa Angsuran</label>
                            <div class="col-sm-5">
                                <input type="text" readonly id="sisabulan" name="sisabulan"  class="form-control" value="{{ $sisa['bulan'] ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Sisa Pinjaman</label>
                            <div class="col-sm-5">
                                <input type="text" readonly id="sisapinjaman" name="sisapinjaman"  class="form-control" value="{{ number_format($sisa['outstanding'] ?? 0,0,'.','.') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Awal Potongan</label>
                            <div class="col-sm-3">
                                <input type="text" readonly id="awal" name="awal" class="form-control" value="<?php if (isset($data->tgl_awal)) echo date('d/m/Y', strtotime($data->tgl_awal)) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Akhir Potongan</label>
                            <div class="col-sm-3">
                                <input type="text" readonly id="akhir" name="akhir" class="form-control" value="<?php if (isset($data->tgl_akhir)) echo date('d/m/Y', strtotime($data->tgl_akhir)) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Sumber Pinjaman</label>
                            <div class="col-sm-5">
                                <input type="text" readonly id="sumber" name="sumber" class="form-control" value="{{ $data->namasumber ?? ''}}">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Catatan</label>
                            <div class="col-sm-6">
                                <textarea required id="catatan" name="catatan" placeholder="catatan" class="form-control">{{ $data->catatan ?? '' }} </textarea>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        <div class="col-sm-6">

            <div class="card">
                <div class="card-header bg-slate-300" style="padding: 0.375rem 1rem;">
                    <h6 class="card-title">TABEL ANGSURAN</h6>

                </div>
                <div class="card-body">
                    <table class="table table_2" >
                        <thead>
                            <tr>
                                <th >Bulan</th>
                                <th width='15%'>Pokok</th>
                                <th width='15%'>Margin</th>
                                <th width='15%'>Angsuran</th>
                                <th width='15%'>Outstanding</th>
                                <th width='15%'>Pembayaran</th>
                                <th width='15%'>Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($angsuran <> '') foreach ($angsuran as $row) { 
                                $pembayaran = $row->jumlah > 0  ? number_format($row->jumlah,0,',','.') : '';
                                if($row->jumlah >= $row->angsuran){
                                    $ket='LUNAS';
                                }elseif(($row->jumlah < $row->angsuran) && ($row->jumlah > 0)){
                                    $ket="KURANG BAYAR";
                                }elseif($row->pelunasanid){
                                    $ket='PELUNASAN';
                                }else{
                                    $ket="";
                                }
                                ?>

                                <tr>
                                    <td  >{{ $row->bulan}}</td>
                                    <td width='15%'>{{ number_format($row->pokok,0,',','.') }}</td>
                                    <td width='15%'>{{ number_format($row->margin,0,',','.') }}</td>
                                    <td width='15%'>{{ number_format($row->angsuran,0,',','.') }}</td>
                                    <td width='15%'>{{ number_format($row->outstanding,0,',','.') }}</td>
                                    <td width='15%'>{{ $pembayaran }}</td>
                                    <td width='15%'><span  class="{{ $ket=='LUNAS' || $ket=='PELUNASAN' ? 'badge badge-success' : 'badge badge-warning' }}">{{ $ket }}</span> </td>
                                </tr>
                            <?php } ?>

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

        $('.pickadate').pickadate({
            format: 'dd/mm/yyyy',
			selectYears: 6,
			selectMonths: 12,
			max: true,
            clear: false,
            today: false,
            close:false
        });

        var jumlahbayar = document.getElementById('jumlahbayar');
        jumlahbayar.addEventListener('keyup', function(e) {
            jumlahbayar.value = formatRupiah(this.value);
        });
        var kode = $('#kodebayar').val();

        if(kode=='angsuran'){
            
            var jumlahbulan = document.getElementById('jumlahbulan');
            jumlahbulan.addEventListener('keyup', function(e) {
                jumlahbulan.value = formatRupiah(this.value);
            });
        }

        $('[data-popup="lightbox"]').fancybox({
            padding: 3
        });

        var table = $('.table_1').DataTable({
            dom: '',
            language: {},
            ordering: false,

        });

        $('#jumlahbulan').on('change', function(){
            var jmlangsuran = $('#angsuran').val();
            jmlangsuran = jmlangsuran.replace('.','');
            jmlangsuran = jmlangsuran.replace('.','');
            var bulan  = this.value;
            var jmlbayar = jmlangsuran * bulan;
            $('#jumlahbayar').val(formatRupiah(jmlbayar));
        })

        $('.table_1 tbody ').on('click', 'tr', function() {
            var data = table.row(this).data();
            var pinjamanid = data[1];

            var id = $('#f_anggota').val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url = "<?php echo route('detailPeminjam'); ?>";
            var kode = $('#kodebayar').val();
            $.ajax({
                type: "POST",
                url: url,
                data: {
                    id: id,
                    pinjamanid: pinjamanid,
                    kode:kode,
                    _token: CSRF_TOKEN,
                },
                success: function(data) {
                    $('#taghasil').html("");
                    $('#taghasil').html(data);
                }
            });

        });
    })

    function goDetail($pinjamanid) {
        var id = $('#f_anggota').val();
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = "<?php echo route('detailPeminjam'); ?>";
        $.ajax({
            type: "POST",
            url: url,
            data: {
                id: id,
                pinjamanid: pinjamanid,
                _token: CSRF_TOKEN,
            },
            success: function(data) {

                $('#taghasil').html("");
                $('#taghasil').html(data);
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