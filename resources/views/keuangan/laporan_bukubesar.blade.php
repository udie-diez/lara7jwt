@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h2 class="card-title">{{$tag['judul']}}</h2>
        <div class="header-elements">
            <div class="list-icons">
                <button type="button" class="btn btn-outline-info btn-sm" onclick="window.history.go(-1); return false;">
                    << Kembali</button>

            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('laporanBukubesar') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label col-sm-1  font-weight-bold">FILTER </label>
                <label class="col-form-label pr-2">Periode :</label>

                <div style="max-width: 22%;" class="pl-2">
                    <button id="btn-tanggal" type="button" class="btn btn-outline-default daterange-qtr">
                        <i class="icon-calendar position-left"> </i>
                        <span id="tglfilter"></span>
                        <b class="caret"></b>
                        <input type="hidden" id="tglFilter" name="tglFilter">
                    </button>
                </div>

                <div class="col-sm-1">
                    <button type="submit" class="btn btn-outline-info btn-sm" id="btn-submit-f">Tampilkan</button>
                </div>
            </div>
        </form>
        <hr>
        <style>
            .dt-buttons {
                margin-bottom: 0;
                margin-top: 0;
                padding-right: 0;
            }

            .dt-buttons>.btn {
                padding-right: 0;
            }

            .datatable-header {
                padding-top: 0;

            }
        </style>
        <table class="table tlaporan" id="table_1">
            <thead>
                <tr>
                    <th hidden>NO.</th>
                    <th>TANGGAL</th>
                    <th>TRANSAKSI</th>
                    <th class="text-center">DEBIT(Rp.)</th>
                    <th class="text-center">KREDIT(Rp.)</th>
                    <th class="text-center">SALDO(Rp)</th>
                </tr>
            </thead>

            @if(isset($data))

            <tbody>
                @php $saldo=0;$id='';$totitemdebit = $totitemkredit = 0; @endphp
                @foreach($data as $row)

                @if($id != $row->id && $id != '')
                <tr class="font-weight-bold" style="background-color:honeydew;">
                    <td hidden></td>
                    <td></td>
                    <td class="text-center">Saldo Akhir</td>
                    <td class="text-right">{{Rupiah($totitemdebit,2)}}</td>
                    <td class="text-right">{{Rupiah($totitemkredit,2)}}</td>
                    <td class="text-right">{{Rupiah($saldo,2)}}</td>
                </tr>
                @php $totitemdebit = $totitemkredit = $saldo=0 @endphp
                @endif

                <?php

                $saldo += $row->debit ? $row->debit : 0;
                $saldo -= $row->kredit ? $row->kredit : 0;
                $totitemdebit += $row->debit;
                $totitemkredit += $row->kredit;
                ?>
                @if($id != $row->id)
                <tr style="background-color:ghostwhite;" class="font-weight-bold font-size-lg">
                    <td hidden></td>
                    <td>{{ $row->kode . ' - ' .$row->nama }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- saldo awal -->
                @foreach($datasaldo as $s)
                @if($s->id == $row->id)
                <tr>
                    <td hidden></td>
                    <td>{{$tgl_str}}</td>
                    <td>Saldo Awal</td>
                    <td class="text-right">{{Rupiah($s->debit,2)}}</td>
                    <td class="text-right">{{Rupiah($s->kredit,2)}}</td>
                    <td class="text-right">{{Rupiah($s->saldo,2)}}</td>
                    <?php
                    $saldo += $s->debit ? $s->debit : 0;
                    $saldo -= $s->kredit ? $s->kredit : 0;
                    ?>
                </tr>
                @endif
                @endforeach

                @endif
                <tr>
                    <td hidden>{{ $loop->iteration }}.</td>
                    <td>{{ IndoTgl($row->tanggal) }}</td>

                    @if($row->nobiaya)

                    <td><a href="{{ route('showBiaya',$row->itemid) }}" class='text-teal-800' title='Lihat Transaksi'>Biaya #{{$row->nobiaya }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namabiaya}}</span></td>

                    @elseif($row->nojurnalumum)

                    <td><a href="{{ route('showJurnalumum',$row->itemid) }}" class='text-teal-800' title='Jurnal Umum'>Jurnal #{{$row->nojurnalumum }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namajurnalumum}}</span></td>

                    @elseif($row->nosaldoawal)

                    <td><a href="{{ route('saldoawal') }}" class="text-teal-800 modalMd" title="Saldo Awal">Saldo Awal</a>
                    </td>

                    @elseif($row->notransfer)

                    <td><a href='#' value="{{ route('showtransferKas', $row->itemid ?? 0) }}" class="text-teal-800 modalMd" title="Transfer" data-toggle="modal" data-target="#modalMd">Transfer #{{$row->notransfer }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namatransfer}}</span>
                    </td>

                    @elseif($row->noterimauang)

                    <td><a href="{{ route('showTerimaUang', $row->itemid ?? 0) }}" class="text-teal-800" title="Terima Uang">Terima Uang #{{$row->noterimauang }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namaterimauang}}</span>
                    </td>

                    @elseif($row->nosetoranwajib)
                    
                    <td>Setoran Wajib Bulan {{date('m/Y', strtotime($row->tanggal)) }}<br><span class='text-muted font-size-sm font-italic '>{{$row->namasetoranwajib}}</span>
                    </td>

                    @elseif($row->noangsuranpinjaman)
                    
                    <td><a href="{{ route('showAngsuran', $row->itemid ?? 0) }}" class="text-teal-800" title="Setoran Wajib" >Angsuran Pinjaman #{{$row->noangsuranpinjaman }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namaangsuranpinjaman}}</span>
                    </td>
                    @elseif($row->nopelunasan)
                    
                    <td><a href="{{ route('showPelunasan', $row->itemid ?? 0) }}" class="text-teal-800" title="Setoran Wajib" >Pelunasan Pinjaman #{{$row->nopelunasan }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namapelunasan}}</span>
                    </td>
                    

                    @elseif($row->nopenjualan)
                    <td><a href="{{ route('showInvoice', $row->itemid ?? 0) }}" class="text-teal-800" title="Invoice / Penjualan" >Invoice #{{$row->nopenjualan }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namapenjualan}}</span>
                    </td>

                    @elseif($row->nopembayaran)
                    <td><a href="{{ route('showPembayaran', $row->itemid ?? 0) }}" class="text-teal-800" title="Pembayaran" >Pembayaran #{{$row->nopembayaran }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namapembayaran}}</span>
                    </td>
                    
                    @elseif($row->nosetoranpokok)
                    
                    <td>Setoran Pokok Bulan {{date('m/Y', strtotime($row->tanggal)) }}<br><span class='text-muted font-size-sm font-italic '>{{$row->namasetoranpokok}}</span>
                    </td>

                   
                    @elseif($row->nokirimuang)

                    <td><a href="{{ route('showKirimUang', $row->itemid ?? 0) }}" class="text-teal-800" title="Kirim Uang">Kirim Uang #{{$row->nokirimuang }}</a><br><span class='text-muted font-size-sm font-italic '>{{$row->namakirimuang}}</span>
                    </td>

                    @else
                    <td></td>
                    @endif
                    <td class="text-right">{{ Rupiah($row->debit,2 ) ?? "0,00" }}</td>
                    <td class="text-right">{{ Rupiah($row->kredit,2 ) ?? "0,00" }}</td>
                    <td class="text-right">{{ Rupiah($saldo,2) }}</td>

                </tr>
                @php $id = $row->id @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr class="font-weight-bold">
                    <td></td>
                    <td class="text-center">Saldo Akhir</td>
                    <td></td>
                    <td></td>
                    <td class="text-right">{{ Rupiah($saldo,2) }}</td>

                </tr>
            </tfoot>
            @else
            <tr>
                <td class="text-center" colspan="5">Data Tidak Ada</td>
            </tr>
            <tr class="font-weight-bold" style="background-color:honeydew;">
                <td colspan="2" class="text-center">Total</td>
                <td class="text-right">0</td>
                <td class="text-right">0</td>
                <td class="text-right">0</td>
            </tr>
            @endif
        </table>
    </div>
</div>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/tables/datatables/extensions/buttons.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {

        $("#tglfilter").on('DOMSubtreeModified', function() {
            document.getElementById("tglFilter").value = this.innerHTML;
        });
        var tgl = "{{ $tgl ?? '' }}";
        if (tgl) $("#tglfilter").html(tgl);

        var tbl = $('.tlaporan').DataTable({
            ordering: false,
            pageLength: 1000,
            dom: '<"datatable-header"B>',
            buttons: {
                buttons: [{
                        extend: 'excelHtml5',
                        text: '<i class="icon-file-excel"></i>',
                        className: 'btn btn-default btn-sm',
                        footer: true,
                        download: 'open',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        },
                        title: function() {
                            var tanggal = '<?php echo $tgl ?? '' ?>'
                            return "KOPKAR TRENDY  " + "\n" + " Buku Besar" + "\n" + " Periode : " + tanggal;
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row c[r^="C"]', sheet).attr('s', '52');
                            $('row c[r^="D"]', sheet).attr('s', '52');
                            $('row c[r^="E"]', sheet).attr('s', '52');

                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="icon-file-pdf"></i>',
                        className: 'btn btn-default btn-sm',
                        title: function() {
                            var tanggal = '<?php echo $tgl ?? '' ?>'
                            return 'KOPKAR TRENDY  ' + '\n' + ' Buku Besar ' + '\n' + ' Periode : ' + tanggal;
                        },
                        orientation: 'Landscape',
                        pageSize: 'A4',
                        footer: true,
                        download: 'open',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        },
                        customize: function(doc) {
                            // doc.content[1].table.widths = ['80%', '20%'];
                            doc.styles.title.fontSize = 10;
                            doc.pageMargins = [50, 50, 50, 50];
                            doc.styles.tableHeader.color = 'black';
                            doc.styles.tableFooter.color = 'black';
                            doc.styles.tableHeader.fillColor = '#dadada';
                            doc.styles.tableFooter.fillColor = '#dadada';
                            doc.styles.tableHeader.margin = 5;
                            doc.defaultStyle.fontSize = 9;
                            doc.styles.tableHeader.fontSize = 9;

                            doc.styles.tableBodyEven.fillColor = 'white';
                            doc.styles.tableBodyOdd.fillColor = 'white';
                            var objLayout = {};
                            objLayout['hLineWidth'] = function(i) {
                                return .8;
                            };
                            objLayout['vLineWidth'] = function(i) {
                                return .5;
                            };
                            objLayout['hLineColor'] = function(i) {
                                return '#aaa';
                            };
                            objLayout['vLineColor'] = function(i) {
                                return '#aaa';
                            };
                            objLayout['paddingLeft'] = function(i) {
                                return 8;
                            };
                            objLayout['paddingRight'] = function(i) {
                                return 8;
                            };
                            doc.content[1].layout = objLayout; //border

                            doc['footer'] = (function(page, pages) {
                                return {
                                    columns: [
                                        'SI-Trendy / Buku Besar',
                                        {
                                            // This is the right column
                                            alignment: 'right',
                                            text: ['page ', {
                                                text: page.toString()
                                            }, ' of ', {
                                                text: pages.toString()
                                            }]
                                        }
                                    ],
                                    margin: [50, 10]
                                }
                            });

                            var rowCount = document.getElementById("table_1").rows.length;
                            for (i = 0; i < rowCount; i++) {
                                doc.content[1].table.body[i][2].alignment = 'right';
                                doc.content[1].table.body[i][3].alignment = 'right';
                                doc.content[1].table.body[i][4].alignment = 'right';
                                var text1 = doc.content[1].table.body[i][1].text;

                                if (text1 == 'Saldo Akhir' || text1 == '') {


                                    if (text1 == 'Saldo Akhir') {
                                        doc.content[1].table.body[i][0].fillColor = 'honeydew';
                                        doc.content[1].table.body[i][1].fillColor = 'honeydew';
                                        doc.content[1].table.body[i][2].fillColor = 'honeydew';
                                        doc.content[1].table.body[i][3].fillColor = 'honeydew';
                                        doc.content[1].table.body[i][4].fillColor = 'honeydew';
                                        doc.content[1].table.body[i][0].alignment = 'center';
                                    } else {
                                        doc.content[1].table.body[i][0].margin = 5;

                                    }
                                    doc.content[1].table.body[i][0].bold = true;
                                    doc.content[1].table.body[i][1].bold = true;
                                    doc.content[1].table.body[i][2].bold = true;
                                    doc.content[1].table.body[i][3].bold = true;
                                    doc.content[1].table.body[i][4].bold = true;
                                }

                            }

                        }
                    }
                ]
            },

        });
        var cetak = '<?php echo $cetak ?>';
        if(!cetak) tbl.buttons().remove();
    })
</script>
@endsection