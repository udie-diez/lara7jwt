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
        <form method="POST" action="{{ route('laporanJurnal') }}">
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

                <label class="col-form-label col-sm-1 text-right">Urutan :</label>
                <div class="col-sm-2">
                    <select name="f_order" id="f_order" data-placeholder="Urutkan Tanggal" class="select">
                        <option value="asc">Tanggal (Ascending)</option>
                        <option value="desc" <?php if (isset($order)) if ($order == 'desc') echo 'selected'; ?>>Tanggal (Descending)</option>
                    </select>
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
        <table class="table table-hover tlaporan" id="table_1">
            <thead class="bg-info-300">
                <tr>
                    <th>Jurnal</th>
                    <th class="text-right">Debit (Rp.)</th>
                    <th class="text-right">Kredit (Rp.)</th>
                </tr>
            </thead>
            @if(isset($data))
            <tbody>
                <?php $tdebet = $tkredit =   $totitemdebit = $totitemkredit = 0;
                $id = $itemid = ''; ?>
                @foreach($data as $row)

                @if($id != $row->kodeitem && $id != '')
                <tr class="font-weight-bold" style="background-color:honeydew;">
                    <td class="text-center">Jumlah</td>
                    <td class="text-right">{{Rupiah($totitemdebit,2)}}</td>
                    <td class="text-right">{{Rupiah($totitemkredit,2)}}</td>
                </tr>
                @php $totitemdebit = $totitemkredit = 0 @endphp
                @endif

                @if($id != $row->kodeitem)
                <tr>
                    <td><span class="text-info-800 font-weight-bold">{{ strtoupper($row->item) }} </span> | {{IndoTgl($row->tanggal)}}</td>
                    <td></td>
                    <td></td>
                </tr>
                @endif

                <tr>
                    <td class="pl-4">{{$row->itemid .' - '. $row->kode .' - '.$row->nama}}</td>
                    <td class="text-right">{{Rupiah($row->debit,2)}}</td>
                    <td class="text-right">{{Rupiah($row->kredit,2)}}</td>
                </tr>
                <?php
                $id = $row->kodeitem;
                $tdebet += $row->debit;
                $tkredit += $row->kredit;
                $totitemdebit += $row->debit;
                $totitemkredit += $row->kredit;
                ?>
                @endforeach

                <tr class="font-weight-bold" style="background-color:honeydew;">
                    <td class="text-center">Jumlah</td>
                    <td class="text-right">{{Rupiah($totitemdebit,2)}}</td>
                    <td class="text-right">{{Rupiah($totitemkredit,2)}}</td>
                </tr>

            </tbody>
            <tfoot style="font-weight: bold; background-color:paleturquoise">
                <tr>
                    <td class="text-center">Total</td>
                    <td class="text-right">{{Rupiah($tdebet,2)}}</td>
                    <td class="text-right">{{Rupiah($tkredit,2)}}</td>
                </tr>
            </tfoot>
            @else
            <tbody>
                <tr>
                    <td class="text-center" colspan="3">Data Tidak Ada</td>
                </tr>
                <tr class="font-weight-bold" style="background-color:honeydew;">
                    <td class="text-center">Total</td>
                    <td class="text-right">0</td>
                    <td class="text-right">0</td>
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
                            return "KOPKAR TRENDY  " + "\n" + " J U R N A L " + "\n" + " Periode : " + tanggal;
                        },
                        customize: function(xlsx) {
                            var sheet = xlsx.xl.worksheets['sheet1.xml'];
                            $('row c[r^="B"]', sheet).attr('s', '52');
                            $('row c[r^="C"]', sheet).attr('s', '52');
                            var row = $('row', sheet);
                           
                        },
                    },
                    {
                        extend: 'pdfHtml5',
                        text: '<i class="icon-file-pdf"></i>',
                        className: 'btn btn-default btn-sm',
                        title: function() {
                            var tanggal = '<?php echo $tgl ?? '' ?>'
                            return 'KOPKAR TRENDY  ' + '\n' + ' J U R N A L ' + '\n' + ' Periode : ' + tanggal;
                        },
                        orientation: 'Portrait',
                        pageSize: 'A4',
                        footer: true,
                        download: 'open',

                        customize: function(doc) {
                            // doc.content[1].table.widths = ['80%', '20%'];
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
                                        'SI-Trendy / Jurnal',
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
                                doc.content[1].table.body[i][2].alignment = 'right';
                                var textx = doc.content[1].table.body[i][0].text;

                                if (textx == 'Jumlah') {
                                    doc.content[1].table.body[i][0].alignment = 'center';
                                    doc.content[1].table.body[i][0].fillColor = 'honeydew';
                                    doc.content[1].table.body[i][1].fillColor = 'honeydew';
                                    doc.content[1].table.body[i][2].fillColor = 'honeydew';
                                    doc.content[1].table.body[i][0].bold = true;
                                    doc.content[1].table.body[i][1].bold = true;
                                    doc.content[1].table.body[i][2].bold = true;

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