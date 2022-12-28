function getFormattedDate(datex) {

    var date = new Date(datex);
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return day + '/' + month + '/' + year;
}

function getFDate(datex) {

    var date = new Date(datex);
    var year = date.getFullYear();

    var month = (1 + date.getMonth()).toString();
    month = month.length > 1 ? month : '0' + month;

    var day = date.getDate().toString();
    day = day.length > 1 ? day : '0' + day;

    return year + '/' + month + '/' + day;
}


function currdate() {
    var currentdate = new Date();
    var datetime = currentdate.getDate().toString() +
        + (currentdate.getMonth() + 1).toString() +
        + currentdate.getFullYear().toString();
    // + currentdate.getHours() + ":"  
    // + currentdate.getMinutes() + ":" 
    // + currentdate.getSeconds();
    return datetime;
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

$(function () {

    $('.select').select2({
        minimumResultsForSearch: Infinity
    });
    $('.select-search').select2();

    $('.pickadate').pickadate({
        format: 'dd/mm/yyyy'
    });

    $('.basicxx').DataTable({
        ordering: false,
        dom: '<"datatable-scroll-wrap"t>',
    });

    $('.basic').DataTable({
        pageLength: 10,
        ordering: true,
        lengthMenu: [10, 25, 50, 75, 100],
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Search :</span> _INPUT_',
            searchPlaceholder: '...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        },
    });

    //datatable basicx
    $('.basicx').DataTable({
        ordering: true,
        autoWidth: true,
        dom: '<"datatable-header"fBl><"datatable-scroll-wrap"t><"datatable-footer"ip>',
        language: {
            search: '<span>Search :</span> _INPUT_',
            searchPlaceholder: '...',
            lengthMenu: '<span>Show:</span> _MENU_',
            paginate: { 'first': 'First', 'last': 'Last', 'next': $('html').attr('dir') == 'rtl' ? '&larr;' : '&rarr;', 'previous': $('html').attr('dir') == 'rtl' ? '&rarr;' : '&larr;' }
        },
        lengthMenu: [10, 25, 50, 75, 100],
        displayLength: 25,
        buttons: {
            buttons: [
                {
                    extend: 'excelHtml5',
                    text: '<i class="icon-file-excel"></i>',
                    className: 'btn btn-default btn-sm',
                    footer: true,
                    title: $('#title-form').text().toUpperCase() + '\n' + ' KOPKAR TRENDY',
                    messageTop: function () {
                        if ($('#title-form').text() == 'Daftar Peminjam') {
                            return 'Periode : ' + $("#f_bulan option:selected").text() + ' ' + $('#f_tahun').val();
                        } else {
                            return '';
                        }
                    },
                    exportOptions: {
                        columns: ['.export']

                    },
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="icon-file-pdf"></i>',
                    className: 'btn btn-default btn-sm',
                    title: function () {
                        if ($('#title-form').text() == 'Daftar Peminjam') {
                            return 'DAFTAR RINCIAN PEMINJAM KOPKAR TRENDY ' + '\n' + ' Periode : ' + $("#f_bulan option:selected").text() + ' ' + $('#f_tahun').val();
                        } else if ($('#title-form').text() == 'Daftar Setoran') {
                            return 'DAFTAR SETORAN ANGGOTA KOPKAR TRENDY ' + '\n' + ' ';
                        } else if ($('#title-form').text() == 'DAFTAR PELUNASAN') {
                            return 'DAFTAR PELUNASAN PINJAMAN KOPKAR TRENDY' + '\n' + ' Periode : ' + $("#f_bulan option:selected").text() + ' ' + $('#f_tahun').val();
                        } else if ($('#title-form').text() == 'DAFTAR PEMBAYARAN ANGSURAN') {
                            return 'DAFTAR PELUNASAN PINJAMAN KOPKAR TRENDY' + '\n' + ' Periode : ' + $("#f_bulan option:selected").text() + ' ' + $('#f_tahun').val();
                        } else if ($('#title-form').text() == 'Daftar Project') {
                            return 'DAFTAR PROJECT KOPKAR TRENDY';
                        } else if ($('#title-form').text() == 'Daftar Invoice') {
                            return 'DAFTAR INVOICE KOPKAR TRENDY';
                        }
                    },
                    orientation: 'Landscape',
                    pageSize: 'LEGAL',
                    footer: true,
                    exportOptions: {
                        columns: ['.export']
                    },

                    customize: function (doc) {
                        doc.styles.title.fontSize = 12;
                        doc.pageMargins = [30, 30, 50, 30];
                        doc.styles.tableHeader.color = 'black';
                        doc.styles.tableFooter.color = 'black';
                        doc.styles.tableHeader.fillColor = '#dadada';
                        doc.styles.tableFooter.fillColor = '#dadada';
                        if ($('#title-form').text() == 'Daftar Setoran') {
                            doc.defaultStyle.fontSize = 10;
                        } else {
                            doc.defaultStyle.fontSize = 9;
                            doc.styles.tableHeader.fontSize = 9;
                        }

                        doc.styles.tableBodyEven.fillColor = 'white';
                        doc.styles.tableBodyOdd.fillColor = 'white';

                        var objLayout = {};
                        objLayout['hLineWidth'] = function (i) { return .8; };
                        objLayout['vLineWidth'] = function (i) { return .5; };
                        objLayout['hLineColor'] = function (i) { return '#aaa'; };
                        objLayout['vLineColor'] = function (i) { return '#aaa'; };
                        objLayout['paddingLeft'] = function (i) { return 8; };
                        objLayout['paddingRight'] = function (i) { return 8; };
                        doc.content[1].layout = objLayout;  //border

                        doc['footer'] = (function (page, pages) {
                            return {
                                columns: [
                                    'SI-Trendy Kopkar Trendy',
                                    {
                                        // This is the right column
                                        alignment: 'right',
                                        text: ['page ', { text: page.toString() }, ' of ', { text: pages.toString() }]
                                    }
                                ],
                                margin: [30, 10]
                            }
                        });

                        var ht1 = doc.content[1].table.body[0][0].text;
                        if (ht1 != 'P') {
                            // doc.content[0].text=$('#title-form').text();
                            // doc.content[1].text=ht1;
                            var rowCount = document.getElementById("table_1").rows.length;
                            for (i = 0; i < rowCount; i++) {
                                for (var k = 0; k < 8; k++) {
                                    // doc.content[1].table.body[i][k].fillColor = '#FFFFFF';
                                    // doc.content[1].table.body[i][k].color = 'black';
                                }
                                if ($('#title-form').text() == 'Daftar Setoran') {
                                    doc.content[1].table.body[i][6].alignment = 'right';
                                    doc.content[1].table.body[i][5].alignment = 'center';
                                    doc.content[1].table.body[i][4].alignment = 'center';
                                } else if ($('#title-form').text() == 'Daftar Peminjam') {
                                    doc.content[1].table.body[i][3].alignment = 'right';
                                    doc.content[1].table.body[i][4].alignment = 'center';
                                    doc.content[1].table.body[i][8].alignment = 'right';
                                    doc.content[1].table.body[i][9].alignment = 'center';
                                    doc.content[1].table.body[i][10].alignment = 'right';
                                    doc.content[1].table.body[i][11].alignment = 'right';
                                } else if ($('#title-form').text() == 'DAFTAR PELUNASAN') {
                                    doc.content[1].table.body[i][3].alignment = 'right';
                                    doc.content[1].table.body[i][4].alignment = 'center';
                                    doc.content[1].table.body[i][6].alignment = 'center';
                                    doc.content[1].table.body[i][7].alignment = 'right';
                                    doc.content[1].table.body[i][8].alignment = 'right';
                                    doc.content[1].table.body[i][9].alignment = 'center';
                                } else if ($('#title-form').text() == 'DAFTAR PEMBAYARAN ANGSURAN') {
                                    doc.content[1].table.body[i][3].alignment = 'right';
                                    doc.content[1].table.body[i][4].alignment = 'center';
                                    doc.content[1].table.body[i][6].alignment = 'right';
                                } else if ($('#title-form').text() == 'Daftar Project') {
                                    doc.content[1].table.body[i][6].alignment = 'right';
                                    doc.content[1].table.body[i][7].alignment = 'right';
                                    doc.content[1].table.body[i][8].alignment = 'right';
                                    doc.content[1].table.body[i][9].alignment = 'right';
                                    doc.content[1].table.body[i][10].alignment = 'right';
                                } else if ($('#title-form').text() == 'Daftar Invoice') {
                                    doc.content[1].table.body[i][6].alignment = 'right';
                                    doc.content[1].table.body[i][7].alignment = 'right';
                                    doc.content[1].table.body[i][8].alignment = 'right';
                                }
                            };


                        }


                    }
                }
            ]
        },

    });
    $('.dataTables_length select').select2({
        minimumResultsForSearch: Infinity,
        dropdownAutoWidth: true,
        width: 'auto'
    });
    //////////



    $('.daterange-predefined').daterangepicker(
        {
            // startDate: moment().subtract('days', 29),
            startDate: moment(),
            endDate: moment(),
            minDate: '01/01/2000',
            maxDate: '31/12/2030',
            dateLimit: { days: 366 },
            ranges: {
                'Hari Ini': [moment(), moment()],
                'Kemarin': [moment().subtract('days', 1), moment().subtract('days', 1)],
                '7 Hari Terakhir': [moment().subtract('days', 6), moment()],
                '30 Hari Terakhir': [moment().subtract('days', 29), moment()],
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                'Tahun Lalu': [moment().subtract('year', 1).startOf('year'), moment().subtract('year', 1).endOf('year')]
            },
            opens: false,
            applyClass: 'btn-small bg-slate',
            cancelClass: 'btn-small btn-default'
        },
        function (start, end) {
            $('.daterange-predefined span').html(start.format('DD/MM/YYYY') + '  -  ' + end.format('DD/MM/YYYY'));

        }
    );

    $('.daterange-predefined span').html(moment().format('DD/MM/YYYY') + '  -  ' + moment().format('DD/MM/YYYY'));


    $('.daterange-month').daterangepicker(
        {
            // startDate: moment().subtract('days', 29),
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            minDate: '01/01/2000',
            maxDate: '31/12/2030',
            dateLimit: { days: 366 },
            ranges: {
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                '2 Bulan Terakhir': [moment().subtract('month', 1).startOf('month'), moment().endOf('month')],
                '3 Bulan Terakhir': [moment().subtract('month', 2).startOf('month'), moment().endOf('month')],
                'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                'Tahun Lalu': [moment().subtract('year', 1).startOf('year'), moment().subtract('year', 1).endOf('year')]
            },
            opens: false,
            applyClass: 'btn-small bg-slate',
            cancelClass: 'btn-small btn-default'
        },
        function (start, end) {
            $('.daterange-month span').html(start.format('DD/MM/YYYY') + '  -  ' + end.format('DD/MM/YYYY'));

        }
    );

    $('.daterange-month span').html(moment().startOf('month').format('DD/MM/YYYY') + '  -  ' + moment().endOf('month').format('DD/MM/YYYY'));


    $('.daterange-qtr').daterangepicker(
        {
            // startDate: moment().subtract('days', 29),
            startDate: moment().startOf('month'),
            endDate: moment().endOf('month'),
            minDate: '01/01/2000',
            maxDate: '31/12/2030',
            dateLimit: { days: 366 },
            ranges: {
                'Bulan Ini': [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
                'Triwulan 1': [moment().month(0).startOf('month'), moment().month(2).endOf('month')],
                'Triwulan 2': [moment().month(3).startOf('month'), moment().month(5).endOf('month')],
                'Triwulan 3': [moment().month(6).startOf('month'), moment().month(8).endOf('month')],
                'Triwulan 4': [moment().month(9).startOf('month'), moment().month(11).endOf('month')],
                'Tahun Ini': [moment().startOf('year'), moment().endOf('year')],
                'Tahun Lalu': [moment().subtract('year', 1).startOf('year'), moment().subtract('year', 1).endOf('year')]
            },
            opens: false,
            applyClass: 'btn-small bg-slate',
            cancelClass: 'btn-small btn-default'
        },
        function (start, end) {
            $('.daterange-qtr span').html(start.format('DD/MM/YYYY') + '  -  ' + end.format('DD/MM/YYYY'));

        }
    );
    $('.daterange-qtr span').html(moment().startOf('month').format('DD/MM/YYYY') + '  -  ' + moment().endOf('month').format('DD/MM/YYYY'));

    // Add bottom spacing if reached bottom,
    // to avoid footer overlapping
    // -------------------------

    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() > $(document).height() - 40) {
            $('.fab-menu-bottom-left, .fab-menu-bottom-right').addClass('reached-bottom');
        }
        else {
            $('.fab-menu-bottom-left, .fab-menu-bottom-right').removeClass('reached-bottom');
        }
    });

    // Initialize sticky button
    $('#fab-menu-affixed-demo-left, #fab-menu-affixed-demo-right').stick_in_parent({
        offset_top: 20
    });





})

