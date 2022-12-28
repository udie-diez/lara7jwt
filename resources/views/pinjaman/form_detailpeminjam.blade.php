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
.basic tbody{
    font-size: small;
  display:block;
  overflow:auto;
  height:300px; 
}
.basic thead tr{
    font-size: small;
  display:block;
}
</style>
<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
                <a   class="btn btn-outline-info btn-sm modalMd" href="{{ route('editPinjaman', $data->id) }}" title="Ubah Pinjaman"> <i class="icon-pen"></i> Ubah Pinjaman</a>
                <a   class="btn btn-outline-danger btn-sm modalMd" href="{{ route('pinjamanDestroy', $data->id) }}" onclick="return confirm('Anda yakin ingin menghapus data pinjaman ini ?')" title="Hapus Pinjaman"> <i class="icon-bin"></i> Hapus Pinjaman</a>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-3">
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
                        <label class="col-form-label col-sm-4">No.Anggota</label>
                        <div class="col-sm-7">
                            <input type="text" readonly name="nomor" required class="form-control  border-warning" value="{{ $data->nomor ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Nama Lengkap</label>
                        <div class="col-sm-7">
                            <input type="hidden" name="id" value="{{ $data->id ?? '' }}">
                            <input type="text" readonly name="nama" required class="form-control border-warning" value="{{ $data->nama ?? '' }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">NIK</label>
                        <div class="col-sm-7">
                            <input type="text" id="nik" name="nik" readonly required placeholder="(Terisi Otomatis)" class="form-control  border-warning" value="{{ $data->nik ?? '' }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Tanggal Pengajuan<sup class="text-danger"></sup></label>
                        <div class="col-sm-7">
                            <input type="text" readonly name="tanggal" placeholder="Tanggal" class="form-control" value="{{ date('d/m/Y h:m:s', strtotime($data->tanggal ?? '')) }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-form-label col-sm-4">Status Pinjaman</label>
                            <label class="col-form-label col-sm-3"><span class="badge badge-info" style="font-size:small;">AKTIF</span></label>
                    </div>

                </form>
            </div>

            

        </div>
    </div>

    <div class="row">
        <div class="col-sm-6">

            <div class="card">
                <div class="card-header bg-info" style="padding: 0.375rem 1rem;;">
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
                                <input type="text" readonly id="tenorfix" name="tenor" placeholder="bulan" class="form-control" value="{{ $data->tenorfix ?? '' }}">
                            </div> 
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Angsuran PerBulan</label>
                            <div class="col-sm-5">
                                <input type="text"   readonly id="angsuran" name="angsuran" placeholder="Rp." class="form-control" value="{{ number_format($data->angsuranfix ?? 0 ,0,',','.') ?? '' }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Awal Potongan</label>
                            <div class="col-sm-3">
                                <input type="text" readonly id="awal" name="awal"  class="form-control" value="<?php if(isset($data->tgl_awal)) echo date('d/m/Y', strtotime($data->tgl_awal)) ?>">
                            </div>  
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Akhir Potongan</label>
                            <div class="col-sm-3">
                                <input type="text" readonly id="akhir" name="akhir"  class="form-control"  value="<?php if(isset($data->tgl_akhir)) echo date('d/m/Y', strtotime($data->tgl_akhir)) ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-sm-5">Sumber Pinjaman</label>
                            <div class="col-sm-5">
                                <input type="text" readonly id="sumber" name="sumber"  class="form-control"  value="{{ $data->namasumber ?? ''}}">
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
                <div class="card-header bg-info" style="padding: 0.375rem 1rem;">
                    <h6 class="card-title">TABEL ANGSURAN</h6>

                </div>
                <div class="card-body">
                    <table class="table basic">
                        <thead>
                            <tr>
                                <th width='15%'>Bulan</th>
                                <th width='15%'>Pokok</th>
                                <th width='15%'>Margin</th>
                                <th width='15%'>Angsuran</th>
                                <th width='15%'>Outstanding</th>
                                <th width='15%'>Lunas</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if($angsuran<>'')foreach($angsuran as $row) {?>

                            <tr>
                                <td width='15%'>{{ $row->bulan}}</td>
                                <td width='15%'>{{ number_format($row->pokok,0,',','.') }}</td>
                                <td width='15%'>{{ number_format($row->margin,0,',','.') }}</td>
                                <td width='15%'>{{ number_format($row->angsuran,0,',','.') }}</td>
                                <td width='15%'>{{ number_format($row->outstanding,0,',','.') }}</td>
                                <td width='15%' style="font-size: smaller;" class="text-center {{ $row->status==null ? '' : 'bg bg-green' }}">{{ $row->status==null ? '' : 'LUNAS'}} </td>
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

        $('.pickadate-year').pickadate({
			format: 'mm/dd/yyyy',
			selectMonths: 12,
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