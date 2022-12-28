@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')
<div class="card">
    <div class="card-header  header-elements-inline">
        <h2 class="card-title">{{$tag['judul']}}</h2>
        <div class="header-elements">
            <div class="list-icons">

            </div>
        </div>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('laporanLabarugi') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label pl-2">Periode :</label>

                <div style="max-width: 32%;" class="pl-2">
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
            .head0 {
                border-top: 2px solid;
                background-color: lightgray;
                height: 60px;
            }

            .head1 {
                border-top: 2px solid;
                background-color: honeydew;
                height: 60px;
            }

            .foot1 {
                border-top: 2px solid;
                background-color: honeydew;
                height: 30px;
            }
            .dt-buttons {
                margin-bottom: 0;
                margin-top: 0;
                padding-right: 0;
            }

            .dt-buttons>.btn {
                padding-right: 0;
            }
            .card-body{
                width: 75%;   
            }
            .datatable-header {
                padding-top: 0;
            }
        </style>
        <table class="table table-hover tlaporan" id="table_1" >
            <thead class="bg-info-300">
                <tr>
                    <th>U R A I A N</th>
                    <th class="text-right">JUMLAH (Rp.)</th>
                </tr>
            </thead>
            @if(isset($data))
            <tbody>
                <?php
                $saldo = 0;
                $labakotor = 0;
                $biaya = 0;
                $pendapatan = 0;
                $pengeluaran = 0;

                $id = $itemid = '';

                foreach ($datasaldo as $saldoawal) {      //saldo awal periode filter
                    if (substr($saldoawal->kode, 0, 2) == '40' && substr($saldoawal->kode, 0, 2) == '50') {
                        $labakotor += $saldoawal->saldo;        //labakotor
                    }
                    if (substr($saldoawal->kode, 0, 2) == '60') {
                        $biaya += $saldoawal->saldo;
                    }
                    if (substr($saldoawal->kode, 0, 2) == '80') {
                        $pendapatan += $saldoawal->saldo;
                    }
                    if (substr($saldoawal->kode, 0, 2) == '90') {
                        $pengeluaran += $saldoawal->saldo;
                    }
                }

                ?>
                @foreach($data as $row)


                <?php

                $jumlah = 0;

                foreach ($datasaldo as $saldoawal) {      //saldo awal periode filter
                    if ($row->id == $saldoawal->id) {
                        $jumlah += $saldoawal->saldo;
                    }
                }

                if ($row->kode == '40.0000') { //laba kotor
                    foreach ($data as $h1) {
                        if (substr($h1->kode, 0, 2) == '40' && substr($h1->kode, 0, 2) == '50' && $h1->jenis == 1) {
                            $labakotor += $h1->saldo;
                        }
                    };
                }
                if ($row->kode == '60.0000') { //biaya

                    foreach ($data as $h1) {
                        if (substr($h1->kode, 0, 2) == '60' && $h1->jenis == 1) {
                            $biaya += $h1->saldo;
                        }
                    };
                }

                if ($row->kode == '20.0000') { //pendapatan
                    foreach ($data as $h1) {
                        if (substr($h1->kode, 0, 2) == '80' && $h1->jenis == 1) {
                            $pendapatan += abs($h1->saldo);
                        }
                    };
                }

                if ($row->kode == '90.0000') { //pengeluaran
                    foreach ($data as $h1) {
                        if ((substr($h1->kode, 0, 2) == '90') && $h1->jenis == 1) {
                            $pengeluaran += abs($h1->saldo);
                        }
                    };
                }

                if (substr($row->kode, -4) == '0000') {
                    foreach ($datasaldo as $saldoawal) {      //saldo awal periode filter
                        if (substr($row->kode, 0, 2) == substr($saldoawal->kode, 0, 2)) {
                            $jumlah += $saldoawal->saldo;
                        }
                    }

                    foreach ($data as $h1) {
                        if ((substr($row->kode, 0, 2) == substr($h1->kode, 0, 2)) && $h1->jenis == 1) {
                            $jumlah += $h1->saldo;
                        }
                    };
                } else if (substr($row->kode, -3) == '000' && $row->jenis != 1) {
                    foreach ($datasaldo as $saldoawal) {      //saldo awal periode filter
                        if (substr($row->kode, 0, 4) == substr($saldoawal->kode, 0, 4)) {
                            $jumlah += $saldoawal->saldo;
                        }
                    }

                    foreach ($data as $h1) {
                        if ((substr($row->kode, 0, 4) == substr($h1->kode, 0, 4)) && $h1->jenis == 1) {
                            $jumlah += $h1->saldo;
                        }
                    };
                } else {
                    $jumlah += $row->saldo;
                }

                ?>

                <?php
                //spasi uraian
                $spasi =  substr($row->kode, -4) == '0000' ? 'pl-3' : 'pl-4';

                //jumlah aktiva lancar
                if ($row->kode == '60.0000') {
                    echo "
                        <tr class='font-weight-bold foot1'>
                            <td>JUMLAH LABA KOTOR</td>
                            <td class='text-right'>" . Rupiah($labakotor, 2) . " </td>
                        </tr>
                         
                        ";
                }

                if ($row->kode == '80.0000') {
                    echo "
                        <tr class='font-weight-bold foot1'>
                            <td>JUMLAH LABA USAHA</td>
                            <td class='text-right'>" . Rupiah($labakotor - $biaya, 2) . " </td>
                        </tr> 
                        ";
                }

                ?>
                <tr class="<?= @substr($row->kode, -4) == '0000' ? 'font-weight-bold' : ''; ?>">
                    @if($row->jenis==1)
                        <td class="<?= @$spasi ?>"><a href="{{ route('detailAkun', $row->id)}}" class="text-teal-800">{{ $row->kode .' - '.$row->nama}}</a></td>
                    @else
                        <td class="<?= @$spasi ?>">{{ $row->kode .' - '.$row->nama}}</td>
                    @endif
                    <td class="text-right">{{$jumlah ? Rupiah(abs($jumlah),2) : '0,00'}}</td>
                </tr>

                @endforeach

                <tr class='font-weight-bold foot1'>
                    <td>LABA BERSIH</td>
                    <td class='text-right'>{{ Rupiah($labakotor - $biaya + $pendapatan - $pengeluaran, 2) }}</td>
                </tr>

            </tbody>
            @else
            <tbody>
                <tr>
                    <td class="text-center" colspan="2">Data tidak ada</td>
                </tr>
                <tr class="font-weight-bold">
                    <td>LABA BERSIH</td>
                    <td class="text-right">0,00</td>
                </tr>
                
            </tbody>
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
        console.log(tgl);
        if (tgl) $("#tglfilter").html(tgl);

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
                        title: function() {
                            var tanggal = '<?php echo $tgl ?? '' ?>'
                            return "KOPKAR TRENDY  " + "\n" + " LABA RUGI " + "\n" + " Periode : " + tanggal;
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row c[r^="B"]', sheet).attr('s', '52');
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="icon-file-pdf"></i>',
                        className: 'btn btn-default btn-sm',
                        title: function() {
                            var tanggal = '<?php echo $tgl ?? '' ?>'
                            return 'KOPKAR TRENDY  ' + '\n' + ' LABA RUGI ' + '\n' + ' Periode : ' + tanggal;
                        },
                        orientation: 'Portrait',
                        pageSize: 'A4',
                        footer: true,
                        download: 'open',

                        customize: function(doc) {
                            doc.content[1].table.widths = ['80%', '20%'];
                            doc.styles.title.fontSize = 10;
                            doc.pageMargins = [50, 50, 50, 50];
                            doc.styles.tableHeader.color = 'black';
                            doc.styles.tableFooter.color = 'black';
                            doc.styles.tableHeader.fillColor = '#dadada';
                            doc.styles.tableFooter.fillColor = '#dadada';
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
                                        'SI-Trendy / Laba Rugi',
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
                                doc.content[1].table.body[i][1].alignment = 'right';
                                var textx = doc.content[1].table.body[i][0].text;
                                textx = textx.substring(0, 1);

                                if (!parseInt(textx)) {
                                    doc.content[1].table.body[i][0].fillColor = 'honeydew';
                                    doc.content[1].table.body[i][1].fillColor = 'honeydew';
                                    doc.content[1].table.body[i][0].fontSize = 10;
                                    doc.content[1].table.body[i][1].fontSize = 10;
                                    doc.content[1].table.body[i][0].bold = true;
                                    doc.content[1].table.body[i][1].bold = true;

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