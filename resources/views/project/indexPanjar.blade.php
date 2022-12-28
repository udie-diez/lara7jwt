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

        $('.form-check-input-styled').uniform();

        $('.select-search').select2();

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
                <a class="btn btn-outline-info btn-sm" href="{{ action('ProjectCont@createPanjar') }}" title="Input Invoice"> <i class="icon-plus2"></i> Panjar</a>
            </div>
        </div>
    </div> 
    <div class="card-body">
        <table class="table basicx" id="table_1">
            <thead>
                <tr>
                    <th class="export">NO.</th>
                    <th class="export">NOMOR</th>
                    <th class="export" style="min-width: 80px;">TANGGAL PANJAR</th>
                    <th class="export">JENIS PANJAR</th>
                    <th class="export">PENERIMA PANJAR</th>
                    <th class="export">NOMOR SPK</th>
                    <th class="export text-center">NILAI PANJAR (Rp)</th>
                    <th class="export text-center">TOTAL SPK (Rp)</th>
                    <th class="export text-center">SISA (Rp)</th>
                    <th class="text-center export">STATUS</th> 
                    <th class="text-center export">TANGGAL BAYAR</th> 
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // $statusi = ['', 'PROCESSED', 'BAYAR SEBAGIAN', 'SUDAH DIBAYAR', 'BATAL'];
                $statusi[0] = '';
                $statusi[1] = 'APPROVED - BELUM DIBAYAR';
                $statusi[2] = '';
                $statusi[3] = 'SUDAH DIBAYAR';
                $statusi[4] = 'BATAL';
                $statusi[5] = 'BELUM DISETUJUI';
                $statusi[6] = 'BELUM DISETUJUI';
                $statusi[7] = 'BELUM DISETUJUI';

                $total = $spk = $ppn = $ttotal = 0;
                ?>
                @foreach($data as $row)

                <?php $tagx = ($row->statuspanjar > 3) ? 'badge badge-warning' : 'badge badge-success';
                $spk += $row->jumlahpenggunaan;
                $total += $row->nilaipanjar;
                $statusx = $row->jumlahpenggunaan >= $row->nilaipanjar ? 'COMPLETE' : 'NOT COMPLETE';
                $tagstatusx = ($row->jumlahpenggunaan >= $row->nilaipanjar) ? 'badge badge-success' : 'badge badge-warning';

                if($row->statuspanjar==3){
                    $statusx='COMPLETE';
                    $tagstatusx = 'badge badge-success';
                }

                ?>
                <tr>
                    <td>{{ $loop->iteration }}.</td>
                    <td> <a href="{{ route('showPanjar',$row->panjarid) }}" class="text-slate" title="Detail Panjar"> {{ $row->nomor}}</a></td>
                    <td>{{ IndoTgl($row->tanggal) }}</td>
                    <td> {{ $row->jenis==1 ? 'NON SPK' : 'SPK'}}</td>
                    <td> {{ $row->penerima }}</td>
                    <td> {{ $row->no_spk }}</td>
                     
                    <td class="text-right"> {{ Rupiah_no($row->nilaipanjar,0) }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->jenis==1 ? ($row->jumlahpenggunaan ?? 0) : ($row->nilai ?? 0), 0) }}</td>
                    <td class="text-right"> {{ Rupiah_no($row->jenis==1 ? ($row->nilaipanjar - $row->jumlahpenggunaan) : 0 ,0) }}</td>
                    <td class="text-center"> <span class="<?= @$tagx ?>">{{ $statusi[$row->statuspanjar ?? 0] }}</span></td>
                    <td class="text-center">{{ IndoTgl($row->tanggalbayar) }}</td>
                    <td class="text-right">
                        <div class="list-icons">
                            <a href="{{ route('showPanjar',$row->panjarid) }}" class="list-icons-item text-info-600" title="Detail Panjar"><i class="icon-eye8"></i></a>
                            <a href="{{ action('ProjectCont@destroyPanjar',['id'=>$row->panjarid]) }}" class="list-icons-item text-danger-600" title="Hapus Data" onclick="return confirm('Anda yakin ingin menghapus data ini ??')"><i class="icon-bin"></i></a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot style="font-weight: bold;">
                <tr>
                    <td colspan="5" class="text-right">TOTAL</td>
                    <td class="text-right">{{Rupiah_no($total,0) }}</td>
                    <td class="text-right">{{Rupiah_no($spk,0) }}</td>
                    <td class="text-right">{{Rupiah_no($total - $spk,0) }}</td> 
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<!-- /basic datatable -->
@endsection

