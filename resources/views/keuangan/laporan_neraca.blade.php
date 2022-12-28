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
        <form method="POST" action="{{ route('laporanNeraca') }}">
            @csrf
            <div class="form-group row">
                <label class="col-form-label ml-2 mr-2">Per :</label>
                <div class="col-sm-3">
                    <input type="text" name="tanggal" class="form-control pickadate" value="{{$tanggal ?? date('d/m/Y')}}">
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

            .card-body {
                width: 75%;
            }

            .datatable-header {
                padding-top: 0;
            }
        </style>
        <table class="table tlaporan" id="table_1">
            <thead class="bg-info-300">
                <tr>
                    <th> U R A I A N</th>
                    <th class="text-right">JUMLAH (Rp.)</th>
                </tr>
            </thead>
            <tbody>
                @if(isset($data))
                <?php
                $saldo = 0;
                $aktivalancar = 0;
                $aktivatidaklancar = 0;
                $hutang = 0;
                $modal = 0;

                $id = $itemid = ''; ?>
                @foreach($data as $row)


                <?php
                if (substr($row->kode, 0, 1) > 3) {
                    break;
                }

                $jumlah = 0;

                if ($row->kode == '10.0000') { //aktiva lancar
                    echo "
                    <tr class='font-weight-bold head1 '>
                        <td class='subtitle'>AKTIVA LANCAR</td>
                        <td class='text-right'></td>
                    </tr>
                    ";

                    foreach ($data as $h1) {
                        if (substr($h1->kode, 0, 2) <= 16 && $h1->jenis == 1) {
                            $aktivalancar += $h1->saldo;
                        }
                    };
                }
                if ($row->kode == '17.0000') { //aktiva tidak lancar

                    foreach ($data as $h1) {
                        if (substr($h1->kode, 0, 2) >= 17 && substr($h1->kode, 0, 1) == '1' && $h1->jenis == 1) {
                            $aktivatidaklancar += $h1->saldo;
                        }
                    };
                }

                if ($row->kode == '20.0000') { //hutang

                    foreach ($data as $h1) {
                        if (substr($h1->kode, 0, 1) == '2' && $h1->jenis == 1) {
                            $hutang += abs($h1->saldo);
                        }
                    };
                }
                if ($row->kode == '30.0000') { //modal

                    foreach ($data as $h1) {
                        if ((substr($h1->kode, 0, 1) == '3') && $h1->jenis == 1) {
                            $modal += abs($h1->saldo);
                        }
                    };
                }

                if (substr($row->kode, -4) == '0000') {
                    foreach ($data as $h1) {
                        if ((substr($row->kode, 0, 2) == substr($h1->kode, 0, 2)) && $h1->jenis == 1) {
                            $jumlah += $h1->saldo;
                        }
                    };
                } else if (substr($row->kode, -3) == '000' && $row->jenis != 1) {
                    foreach ($data as $h1) {
                        if ((substr($row->kode, 0, 4) == substr($h1->kode, 0, 4)) && $h1->jenis == 1) {
                            $jumlah += $h1->saldo;
                        }
                    };
                } else {
                    $jumlah += $row->saldo;
                }

                ?>
                @if($row->jenis != 1)
                <?php
                //spasi uraian
                $spasi =  substr($row->kode, -4) == '0000' ? 'pl-3' : 'pl-4';

                //jumlah aktiva lancar
                if (substr($row->kode, 0, 2) == '17') {
                    echo "
                        <tr class='font-weight-bold foot1'>
                            <td>JUMLAH AKTIVA LANCAR</td>
                            <td class='text-right'>" . Rupiah($aktivalancar, 2) . " </td>
                        </tr>
                        <tr class='font-weight-bold head1'>
                        <td>AKTIVA TIDAK LANCAR</td>
                        <td class='text-right'> </td>
                    </tr>
                        ";
                }

                if ($row->kode == '20.0000') {
                    echo "
                        <tr class='font-weight-bold foot1'>
                            <td>JUMLAH AKTIVA TIDAK LANCAR</td>
                            <td class='text-right'>" . Rupiah($aktivatidaklancar, 2) . " </td>
                        </tr>

                        <tr class='font-weight-bold head0'>
                        <td>TOTAL AKTIVA</td>
                        <td class='text-right'>" . Rupiah($aktivatidaklancar + $aktivalancar, 2) . " </td>

                        <tr class='font-weight-bold head1'>
                        <td>HUTANG</td>
                        <td class='text-right'></td>
                    </tr>
                        ";
                }

                if ($row->kode == '30.0000') {
                    echo "
                        <tr class='font-weight-bold foot1'>
                            <td>JUMLAH HUTANG</td>
                            <td class='text-right'>" . Rupiah($hutang, 2) . " </td>
                        </tr>
 
                        <tr class='font-weight-bold head1'>
                        <td>MODAL</td>
                        <td class='text-right'></td>
                    </tr>
                        ";
                }

                ?>
                <tr class="<?= @substr($row->kode, -4) == '0000' ? 'font-weight-bold1' : ''; ?>">
                    <td class="<?= @$spasi ?>">{{ $row->kode .' - '.$row->nama}}</td>
                    <td class="text-right">{{$jumlah ? Rupiah(abs($jumlah),2) : '0,00'}}</td>
                </tr>

                @endif
                <!-- // munculkan detail kalau aktiva tetap -->
                @if(substr($row->kode,0,2)=='18' && $row->jenis==1)
                <tr>
                    <td class="pl-5"><a href="{{ route('detailAkun', $row->id)}}" class="text-teal-800">{{ $row->kode .' - '.$row->nama}} </a></td>
                    <td class="text-right">{{$jumlah ? Rupiah($jumlah,2) : '0,00'}}</td>
                </tr>
                @endif

                <!-- // munculkan detail kalau modal -->
                @if(substr($row->kode,0,1)=='3' && $row->jenis==1)
                <tr>
                    <td class="pl-4"><a href="{{ route('detailAkun', $row->id)}}" class="text-teal-800">{{ $row->kode .' - '.$row->nama}} </a></td>
                    <td class="text-right">{{$jumlah ? Rupiah(abs($jumlah),2) : '0,00'}}</td>
                </tr>
                @endif

                @endforeach

                <tr class='font-weight-bold foot1'>
                    <td>JUMLAH MODAL</td>
                    <td class='text-right'>{{ Rupiah($modal, 2) }}</td>
                </tr>
                <tr class='font-weight-bold head0'>
                    <td>TOTAL PASIVA (HUTANG & MODAL)</td>
                    <td class='text-right'>{{ Rupiah($modal + $hutang, 2) }}</td>
                </tr>
                @else
                <tr>
                    <td class="text-center" colspan="2">Data tidak ada</td>
                </tr>
                <tr class="font-weight-bold">
                    <td>AKTIVA</td>
                    <td class="text-right">0,00</td>
                </tr>
                <tr class="font-weight-bold">
                    <td>PASIVA</td>
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
                            var tanggal = '<?php echo $tanggal ?? '' ?>'
                            return 'KOPKAR TRENDY  ' + '\n' + ' N E R A C A ' + '\n' + ' Per : ' + tanggal;
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row c[r^="B"]', sheet).attr('s', '52');
                            // var col = $('col', sheet);
                            // col.each(function() {
                            //     $(this).attr('s', 52);
                            // });
                        }


                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="icon-file-pdf"></i>',
                        className: 'btn btn-default btn-sm',
                        title: function() {
                            var tanggal = '<?php echo $tanggal ?? '' ?>'
                            return 'KOPKAR TRENDY  ' + '\n' + ' N E R A C A ' + '\n' + ' Per : ' + tanggal;
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
                                        'SI-Trendy / Neraca',
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
                            // var textx = doc.content[1].table.body[2][0].text;
                            //     textx = textx.substring(0,1);
                            //     alert(parseInt(textx));
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