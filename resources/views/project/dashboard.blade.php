@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<!-- <script src="{{ url('/') }}/global_assets/js/demo_pages/widgets_stats.js"></script> -->
<script src="{{ url('/') }}/global_assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<!-- <script src="{{ url('/') }}/global_assets/js/demo_charts/echarts/light/bars/columns_basic.js?v=1"></script> -->
<!-- <script src="{{ url('/') }}/global_assets/js/demo_charts/echarts/light/pies/pie_basic.js?v=1"></script> -->
<script src="{{ url('/') }}/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
<!-- <script src="{{ url('/') }}/global_assets/js/demo_pages/widgets_stats.js"></script> -->
<?php
$potensi = $tagihan = $cashin = 0;
$jmlpotensi = $jmltagihan = $jmlcashin = 0;
$persenpotensi = $persentagihan = $persencahsin = 0;
$targettahun = $targettahun ?? 1;

if (isset($rekap) && count($rekap) > 0) foreach ($rekap as $r) {
    if ($r->ket == 'potensi') {
        $potensi = $r->total;
        $jmlpotensi = $r->jumlah;
        $persenpotensi = $potensi / $targettahun;
    } elseif ($r->ket == 'cashin') {
        $cashin = $r->total;
        $jmlcashin = $r->jumlah;
        $persencahsin = $cashin / $targettahun;
    } elseif ($r->ket == 'tagihan') {
        $tagihan = $r->total;
        $jmltagihan = $r->jumlah;
        $persentagihan = $tagihan / $targettahun;
    }
}

if ($targettahun == 1) {
    $persentagihan = $persencahsin = $persenpotensi = 0;
}
?>
<script type="text/javascript">
    var EchartsColumnsBasicLight = function() {

        // Basic column chart
        var _columnsBasicLightExample = function() {
            if (typeof echarts == 'undefined') {
                console.warn('Warning - echarts.min.js is not loaded.');
                return;
            }

            var columns_basic_element = document.getElementById('columns_basic');

            if (columns_basic_element) {

                // Initialize chart
                var columns_basic = echarts.init(columns_basic_element);

                // Options
                columns_basic.setOption({

                    // Define colors
                    color: ['#2196f3', '#ff5c0e', '#5ab1ef', '#ffb980', '#d87a80'],

                    // Global text styles
                    textStyle: {
                        fontFamily: 'Roboto, Arial, Verdana, sans-serif',
                        fontSize: 13
                    },

                    // Chart animation duration
                    animationDuration: 750,

                    // Setup grid
                    grid: {
                        left: 0,
                        right: 40,
                        top: 35,
                        bottom: 0,
                        containLabel: true
                    },

                    // Add legend
                    legend: {
                        data: ['Target', 'Progress (Cash-in)'],
                        itemHeight: 8,
                        itemGap: 20,
                        textStyle: {
                            padding: [0, 5]
                        }
                    },

                    // Add tooltip
                    tooltip: {
                        trigger: 'axis',
                        backgroundColor: 'rgba(0,0,0,0.75)',
                        padding: [10, 15],
                        textStyle: {
                            fontSize: 13,
                            fontFamily: 'Roboto, sans-serif'
                        }
                    },

                    // Horizontal axis
                    xAxis: [{
                        type: 'category',
                        data: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                        axisLabel: {
                            color: '#333'
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        splitLine: {
                            show: true,
                            lineStyle: {
                                color: '#eee',
                                type: 'dashed'
                            }
                        }
                    }],

                    // Vertical axis
                    yAxis: [{
                        type: 'value',
                        axisLabel: {
                            color: '#333'
                        },
                        axisLine: {
                            lineStyle: {
                                color: '#999'
                            }
                        },
                        splitLine: {
                            lineStyle: {
                                color: ['#eee']
                            }
                        },
                        splitArea: {
                            show: true,
                            areaStyle: {
                                color: ['rgba(250,250,250,0.1)', 'rgba(0,0,0,0.01)']
                            }
                        }
                    }],

                    // Add series
                    series: [{
                            name: 'Target',
                            type: 'bar',
                            data: <?php echo $btarget ?>,
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true,
                                        position: 'top',
                                        textStyle: {
                                            fontWeight: 500
                                        }
                                    }
                                }
                            }

                        },
                        {
                            name: 'Progress (Cash-in)',
                            type: 'bar',
                            data: <?php echo $breal ?>,
                            itemStyle: {
                                normal: {
                                    label: {
                                        show: true,
                                        position: 'top',
                                        textStyle: {
                                            fontWeight: 500
                                        }
                                    }
                                }
                            },

                        }
                    ]
                });
            }


            //
            // Resize charts
            //

            // Resize function
            var triggerChartResize = function() {
                columns_basic_element && columns_basic.resize();
            };

            // On sidebar width change
            var sidebarToggle = document.querySelector('.sidebar-control');
            sidebarToggle && sidebarToggle.addEventListener('click', triggerChartResize);

            // On window resize
            var resizeCharts;
            window.addEventListener('resize', function() {
                clearTimeout(resizeCharts);
                resizeCharts = setTimeout(function() {
                    triggerChartResize();
                }, 200);
            });
        };

        return {
            init: function() {
                _columnsBasicLightExample();
            }
        }
    }();

    document.addEventListener('DOMContentLoaded', function() {
        EchartsColumnsBasicLight.init();
    });

    //stat
    var StatisticWidgets = function() {

        // Animated progress with percentage count
        var _progressPercentage = function(element, radius, border, foregroundColor, end) {
            if (typeof d3 == 'undefined') {
                console.warn('Warning - d3.min.js is not loaded.');
                return;
            }

            // Initialize chart only if element exsists in the DOM
            if (element) {
                var d3Container = d3.select(element),
                    startPercent = 0,
                    fontSize = 22,
                    endPercent = end,
                    twoPi = Math.PI * 2,
                    formatPercent = d3.format('.1%'),
                    boxSize = radius * 2;

                var count = Math.abs((endPercent - startPercent) / 0.01);
                var step = endPercent < startPercent ? -0.01 : 0.01;
                var container = d3Container.append('svg');
                var svg = container
                    .attr('width', boxSize)
                    .attr('height', boxSize)
                    .append('g')
                    .attr('transform', 'translate(' + radius + ',' + radius + ')');

                // Arc
                var arc = d3.svg.arc()
                    .startAngle(0)
                    .innerRadius(radius)
                    .outerRadius(radius - border)
                    .cornerRadius(20);

                // Background path
                svg.append('path')
                    .attr('class', 'd3-progress-background')
                    .attr('d', arc.endAngle(twoPi))
                    .style('fill', foregroundColor)
                    .style('opacity', 0.1);

                // Foreground path
                var foreground = svg.append('path')
                    .attr('class', 'd3-progress-foreground')
                    .attr('filter', 'url(#blur)')
                    .style({
                        'fill': foregroundColor,
                        'stroke': foregroundColor
                    });

                // Front path
                var front = svg.append('path')
                    .attr('class', 'd3-progress-front')
                    .style({
                        'fill': foregroundColor,
                        'fill-opacity': 1
                    });

                // Percentage text value
                var numberText = svg
                    .append('text')
                    .attr('dx', 0)
                    .attr('dy', (fontSize / 2) - border)
                    .style({
                        'font-size': fontSize + 'px',
                        'line-height': 1,
                        'fill': foregroundColor,
                        'text-anchor': 'middle'
                    });
                // Animate path
                function updateProgress(progress) {
                    foreground.attr('d', arc.endAngle(twoPi * progress));
                    front.attr('d', arc.endAngle(twoPi * progress));
                    numberText.text(formatPercent(end));
                }

                // Animate text
                var progress = startPercent;
                (function loops() {
                    updateProgress(progress);
                    if (count > 0) {
                        count--;
                        progress += step;
                        setTimeout(loops, 10);
                    }
                })();
            }
        };
        return {
            init: function() {

                _progressPercentage('#progress_percentage_two', 46, 3, "#fff", <?php echo $persencahsin; ?>);
                _progressPercentage('#progress_percentage_three', 46, 3, "#fff", <?php echo $persentagihan; ?>);
                _progressPercentage('#progress_percentage_four', 46, 3, "#fff", <?php echo ($persenpotensi); ?>);

            }
        }
    }();

    // When content loaded
    document.addEventListener('DOMContentLoaded', function() {
        StatisticWidgets.init();
    });
</script>

<div class="card">
    <div class="card-header  header-elements-inline">
        <h5 class="card-title" id="title-form">{{$tag['judul']}}</h5>
        <div class="header-elements">
            <div class="list-icons">
            </div>
        </div>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-sm-6 col-xl-3">

                <!-- Invitation stats colored -->
                <div class="card text-center bg-blue-600 has-bg-image">
                    <div class="card-body mb-4">
                        <h3 class="font-weight-semibold mb-0 mt-1">Target</h3>
                        <div class="mb-4">
                            <h5>Tahun {{date('Y')}}</h5>
                        </div>
                        <div class="svg-center position-relative" id="">
                            <h2> Rp. {{ rupiah($targettahun)}},- </h2>
                        </div>
                    </div>


                </div>
                <!-- /invitation stats colored -->

            </div>

            <div class="col-sm-6 col-xl-2">

                <!-- Invitation stats colored -->
                <div class="card text-center  has-bg-image" style="background-color: #ff5c0e;">
                    <div class="card-body">
                        <h3 class="font-weight-semibold mb-0 mt-1">Cash In</h3>
                        <div class="mb-0">
                            <h6> Rp. {{ rupiah($cashin) }} </h6>

                        </div>
                        <div class="mb-3">
                            <a href="#" value="{{ route('projectListDbl','cashin') }}" class="text-default modalMd" title="DAFTAR PROJECT  - CASH-IN" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">
                                <h6> ({{ $jmlcashin }} Project)</h6>
                            </a>

                        </div>
                        <div class="svg-center position-relative mb-1" id="progress_percentage_two"></div>
                    </div>
                </div>
                <!-- /invitation stats colored -->
            </div>
            <div class="col-sm-6 col-xl-2">

                <!-- Invitation stats colored -->
                <div class="card text-center bg-violet  has-bg-image">
                    <div class="card-body">
                        <h3 class="font-weight-semibold mb-0 mt-1">Tagihan</h3>
                        <div class="mb-0">
                            <h6> Rp. {{ rupiah($tagihan) }} </h6>
                        </div>
                        <div class="mb-3">
                            <a href="#" value="{{ route('projectListDbl','tagihan') }}" class="text-default modalMd" title="DAFTAR PROJECT  - TAGIHAN" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">
                                <h6> ({{ $jmltagihan }} Project)</h6>
                            </a>
                        </div>
                        <div class="svg-center position-relative mb-1" id="progress_percentage_three"></div>

                    </div>
                </div>
                <!-- /invitation stats colored -->
            </div>
            <div class="col-sm-6 col-xl-2">

                <!-- Tickets stats colored -->
                <div class="card text-center has-bg-image" style="background-color: #2bd830;">
                    <div class="card-body">
                        <h3 class="font-weight-semibold mb-0 mt-1">Potensi</h3>
                        <div class="mb-0">
                            <h6>Rp. {{ rupiah($potensi) }}</h6>
                        </div>
                        <div class="mb-3">
                            <a href="#" value="{{ route('projectListDbl','potensi') }}" class="text-default modalMd" title="DAFTAR PROJECT  - POTENSI" data-toggle="modal" data-target="#modalMd" data-backdrop="static" data-keyboard="false">
                                <h6> ({{ $jmlpotensi }} Project)</h6>
                            </a>
                        </div>
                        <div class="svg-center position-relative mb-1" id="progress_percentage_four"></div>
                    </div>

                </div>
                <!-- /tickets stats colored -->

            </div>
            <div class="col-sm-6 col-xl-3">

                <!-- Tickets stats colored -->
                <div class="card text-center bg-danger-400 has-bg-image">
                    <div class="card-body">
                        <h3 class="font-weight-semibold mb-0 mt-1">{{$targettahun > $cashin ? 'Defisit' : 'Surplus'}}</h3>
                        <div class="mb-4">
                            <h5>sampai bulan ini</h5>
                        </div>
                        <div class="mb-3">
                            <h2>Rp. {{ rupiah( $targettahun - $cashin) }},-</h2>
                        </div>
                    </div>

                </div>
                <!-- /tickets stats colored -->

            </div>
        </div>
        <!-- /stats with progress -->
    </div>
    <!-- Basic columns -->
    <div class="card border-top-1  border-bottom-1  rounded-0">
        <div class="card-header header-elements-inline bg-light">
            <h5 class="card-title">Grafik Target dan Progress (Cash-In) Tahun {{date('Y')}} (*Juta)</h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>

        <div class="card-body">
            <div class="chart-container">
                <div class="chart has-fixed-height" id="columns_basic"></div>
            </div>
        </div>
    </div>
    <!-- /basic columns -->
    <style>
        .table td,
        .table th {
            padding: 0.55rem 0.2rem;
        }

        .dataTable thead .sorting:after,
        .dataTable thead .sorting:before {
            right: 0.25rem;
        }
    </style>
    <div class="card mt-3 border-top-1   border-bottom-1  rounded-0">
        <div class="card-header header-elements-inline bg-light">
            <h5 class="card-title">TARGET DAN PROGRESS PEGAWAI </h5>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="reload"></a>
                    <a class="list-icons-item" data-action="remove"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <form method="POST" action="">
                    @csrf
                    <div class="form-group row">
                        <label class="col-form-label col-sm-1  font-weight-bold">FILTER : </label>
                        <label class="col-form-label pr-2">Periode</label>
                        <div style="max-width: 22%;" class="pl-2">
                            <button id="btn-tanggal" type="button" class="btn btn-outline-default daterange-qtr">
                                <i class="icon-calendar position-left"> </i>
                                <span id="tgl_f"></span>
                                <b class="caret"></b>
                                <input type="hidden" id="tglFilter" name="tglFilter">
                            </button>
                        </div>
                        <div class="col-sm-1">
                            <button type="button" class="btn btn-outline-info btn-sm" id="btn_filter">Tampilkan</button>
                        </div>
                    </div>
                </form>
            </div>
            <div id="tbl_rekap">
                <table class="table table-hover table-bordered">
                    <thead class="text-center">
                        <tr>
                            <th rowspan="2">No.</th>
                            <th rowspan="2">Nama </th>
                            <th rowspan="2">Target Setahun <br> (Rp.)</th>
                            <th colspan="2">Cash-in</th>
                            <th colspan="2">Tagihan</th>
                            <th colspan="2">Potensi</th>
                            <th rowspan="2"><span class="text-danger"> Defisit</span> / <span class="text-success"> Surplus</span> <br> (Rp)</th>

                        </tr>
                        <tr>
                            <th>Jumlah (Rp.)</th>
                            <th> Persen (%) </th>
                            <th>Jumlah (Rp.)</th>
                            <th> Persen (%) </th>
                            <th>Jumlah (Rp.)</th>
                            <th> Persen (%) </th>
                        </tr>
                    </thead>
                    <tbody class="text-right">
                        @if(isset($progrespegawai))
                        @php $no=1;$tot_ttarget = 1; $tot_cashin = $tot_tagihan = $tot_potensi = 0; @endphp
                        @foreach($progrespegawai as $p)
                        <?php

                        $ttarget = $p->ttarget ?? 0;
                        $cashin = $p->cashin ?? 0;
                        $tagihan = $p->tagihan ?? 0;
                        $potensi = $p->potensi ?? 0;
                        $tot_ttarget += $ttarget;
                        $tot_cashin += $cashin;
                        $tot_potensi += $potensi;
                        $tot_tagihan += $tagihan;
                        ?>

                        <tr>
                            <td class="text-center">{{$no++}}.</td>
                            <td class="text-left"><a class="text-default" href="{{ route('projectDbpegawai', $p->id) }}"> {{$p->nama}} </a></td>
                            <td>{{ Rupiah($ttarget) }}</td>
                            <td>{{ Rupiah($cashin) }}</td>
                            <td class="text-center">{{ Rupiah( ($ttarget > 0 ? $cashin/$ttarget : 0) * 100,2) }}</td>
                            <td>{{ Rupiah($tagihan) }}</td>
                            <td class="text-center">{{ Rupiah( ($ttarget > 0 ? $tagihan/$ttarget : 0) * 100,2) }}</td>
                            <td>{{ Rupiah($potensi) }}</td>
                            <td class="text-center">{{ Rupiah( ($ttarget > 0 ? $potensi/$ttarget : 0) * 100,2) }}</td>
                            <td><span class="{{$ttarget > $cashin ? 'text-danger' : 'text-success' }}"> {{Rupiah($ttarget - $cashin) }}</span></td>
                        </tr>
                        @endforeach
                        @endif

                    </tbody>
                    <tfoot class="font-weight-bold text-right">
                        <tr>
                            <td></td>
                            <td class="text-center">Total</td>
                            <td>{{ Rupiah($tot_ttarget) }}</td>
                            <td>{{ Rupiah($tot_cashin) }}</td>
                            <td class="text-center">{{ $tot_ttarget>1 ? Rupiah( ($tot_cashin/$tot_ttarget) * 100,2) : 0 }}</td>
                            <td>{{ Rupiah($tot_tagihan) }}</td>
                            <td class="text-center">{{ $tot_ttarget>1 ?  Rupiah( ($tot_tagihan/$tot_ttarget) * 100,2) : 0 }}</td>
                            <td>{{ Rupiah($tot_potensi) }}</td>
                            <td class="text-center">{{ $tot_ttarget>1 ? Rupiah( ($tot_potensi/$tot_ttarget) * 100,2) : 0 }}</td>
                            <td>{{ Rupiah($tot_ttarget - $tot_cashin) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <br>
        </div>
    </div>
    <div class="card">
        <div class="card-header header-elements-inline bg-light">
            <h5 class="card-title">TARGET DAN PROGRESS PEGAWAI PER TRIWULAN </h5>
            <div class="header-elements">

            </div>
        </div>

        <div class="card-cody">
            <table class="table table-hover table-bordered">
                <thead class="text-center">
                    <tr>
                        <th rowspan="2">No.</th>
                        <th rowspan="2">Nama </th>
                        <th rowspan="2">Target <br>PerBulan </th>
                        <th colspan="5" class="alpha-info">Real Q1 <br></th>
                        <th colspan="5" class="alpha-success">Real Q2 <br></th>
                        <th colspan="5" class="alpha-danger">Real Q3 <br></th>
                        <th colspan="5" class="alpha-violet">Real Q4 <br></th>
                        <th colspan="2">Jumlah <br></th>
                    </tr>
                    <tr>
                        <th class="alpha-info">Jan </th>
                        <th class="alpha-info">Feb</th>
                        <th class="alpha-info">Mar</th>
                        <th class="font-weight-bold alpha-info">Q1</th>
                        <th class="font-weight-bold alpha-info">%</th>

                        <th class="alpha-success">Apr </th>
                        <th class="alpha-success">Mei</th>
                        <th class="alpha-success">Jun</th>
                        <th class="font-weight-bold alpha-success">Q2</th>
                        <th class="font-weight-bold alpha-success">%</th>

                        <th class="alpha-danger">Jul </th>
                        <th class="alpha-danger">Agt</th>
                        <th class="alpha-danger">Sep</th>
                        <th class="font-weight-bold alpha-danger">Q3</th>
                        <th class="font-weight-bold alpha-danger">%</th>

                        <th class="alpha-violet">Okt </th>
                        <th class="alpha-violet">Nov</th>
                        <th class="alpha-violet">Des</th>
                        <th class="font-weight-bold alpha-violet">Q4</th>
                        <th class="font-weight-bold alpha-violet">%</th>

                        <th>Rp.</th>
                        <th>%</th>

                    </tr>
                </thead>
                <tbody class="text-right">
                    @if(isset($progresbulan))
                    @php $no=1;$tot_btarget = 1; $q1 = $q2 = $q3 = $q4 = 0; @endphp
                    @php $totb1 = $totb2 = $totb3 = $totb4 = $totb5 = $totb6 = $totb7 = $totb8= $totb9= $totb10= $totb11= $totb12= 0; @endphp
                    @php $totq1 = $totq2 = $totq3 = $totq4 = 0; @endphp

                    @foreach($progresbulan as $p)
                    <?php

                    $btarget = $p->btarget ?? 0;
                    $tot_btarget += $p->ttarget/12 ;

                    $qx1 =  $p->b1 + $p->b2 + $p->b3;
                    $qx2 =  $p->b4 + $p->b5 + $p->b6;
                    $qx3 =  $p->b7 + $p->b8 + $p->b9;
                    $qx4 =  $p->b10 + $p->b11 + $p->b12;

                    $rtahun = $qx1 + $qx2 + $qx3 + $qx4;

                    $q1 += $qx1;
                    $q2 += $qx2;
                    $q3 += $qx3;
                    $q4 += $qx4;

                    $totb1 += $p->b1;
                    $totb2 += $p->b2;
                    $totb3 += $p->b3;
                    $totb4 += $p->b4;
                    $totb5 += $p->b5;
                    $totb6 += $p->b6;
                    $totb7 += $p->b7;
                    $totb8 += $p->b8;
                    $totb9 += $p->b9;
                    $totb10 += $p->b10;
                    $totb11 += $p->b11;
                    $totb12 += $p->b12;

                    $totq1 += $p->b1 + $p->b2 + $p->b3;
                    $totq2 += $p->b4 + $p->b5 + $p->b6;
                    $totq3 += $p->b7 + $p->b8 + $p->b9;
                    $totq4 += $p->b10 + $p->b11 + $p->b12;


                    ?>

                    <tr>
                        <td class="text-center">{{$no++}}.</td>
                        <td class="text-left" style="width: 100px;"><a class="text-default" href="{{ route('projectDbpegawai', $p->id) }}"> {{$p->nama}} </a></td>
                        <td>{{ $btarget > 0 ? Rupiah($btarget) : '' }}</td>
                        <td class="alpha-info">{{ Rupiah($p->b1 ?? 0) }}</td>
                        <td class="alpha-info">{{ Rupiah($p->b2 ?? 0) }}</td>
                        <td class="alpha-info">{{ Rupiah($p->b3 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-info"></b>{{ Rupiah($qx1 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-info"><span class="{{($btarget * 3) > $qx1 ? 'text-danger' : 'text-success' }}">{{ ($qx1 > 0 and $btarget > 0) ? Rupiah( ($qx1/($btarget*3)) * 100, 2 ) : 0 }}</span></td>

                        <td class="alpha-success">{{ Rupiah($p->b4 ?? 0) }}</td>
                        <td class="alpha-success">{{ Rupiah($p->b5 ?? 0) }}</td>
                        <td class="alpha-success">{{ Rupiah($p->b6 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-success">{{ Rupiah($qx2 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-success"><span class="{{($btarget * 3) > $qx2 ? 'text-danger' : 'text-success' }}">{{ ($qx2 > 0 and $btarget > 0) ? Rupiah( ($qx2/($btarget*3)) * 100, 2 ) : 0}}</span></td>

                        <td class="alpha-danger">{{ Rupiah($p->b7 ?? 0) }}</td>
                        <td class="alpha-danger">{{ Rupiah($p->b8 ?? 0) }}</td>
                        <td class="alpha-danger">{{ Rupiah($p->b9 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-danger">{{ Rupiah($qx3 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-danger"><span class="{{($btarget * 3) > $qx1 ? 'text-danger' : 'text-success' }}">{{ ($qx3 > 0 and $btarget > 0) ? Rupiah( ($qx3/($btarget*3)) * 100, 2 ) : 0}}</span></td>

                        <td class="alpha-violet">{{ Rupiah($p->b10 ?? 0) }}</td>
                        <td class="alpha-violet">{{ Rupiah($p->b11 ?? 0) }}</td>
                        <td class="alpha-violet">{{ Rupiah($p->b12 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-violet">{{ Rupiah($qx4 ?? 0) }}</td>
                        <td class="font-weight-bold alpha-violet"><span class="{{($btarget * 3) > $qx1 ? 'text-danger' : 'text-success' }}">{{ ($qx4 > 0 and $btarget > 0) ? Rupiah( ($qx4/($btarget*3)) * 100, 2 ) : 0}}</span></td>

                        <td class="font-weight-bold">{{ Rupiah($rtahun ?? 0) }}</td>
                        <td class="font-weight-bold"><span class="{{($btarget * 12) > $rtahun ? 'text-danger' : 'text-success' }}">{{ ($rtahun > 0 and $btarget > 0) ? Rupiah( ($rtahun/($btarget*12)) * 100, 2 ) : 0}}</span></td>

                    </tr>
                    @endforeach
                    @endif

                </tbody>
                <tfoot class="font-weight-bold text-right">
                    <tr>
                        <td></td>
                        <td>Total</td>
                        <td>{{  Rupiah($tot_btarget) }}</td>
                        <td class="alpha-info">{{ Rupiah($totb1) }}</td>
                        <td class="alpha-info">{{ Rupiah($totb2) }}</td>
                        <td class="alpha-info">{{ Rupiah($totb3) }}</td>
                        <td class="alpha-info">{{ Rupiah($totq1) }}</td>
                        <td class="font-weight-bold alpha-info"><span class="{{($tot_btarget * 3) > $totq1 ? 'text-danger' : 'text-success' }}">{{ ($totq1 > 0 and $tot_btarget > 0) ? Rupiah( ($totq1/($tot_btarget*3)) * 100, 2 ) : 0 }}</span></td>

                        <td class="alpha-success">{{ Rupiah($totb4) }}</td>
                        <td class="alpha-success">{{ Rupiah($totb5) }}</td>
                        <td class="alpha-success">{{ Rupiah($totb6) }}</td>
                        <td class="alpha-success">{{ Rupiah($totq2) }}</td>
                        <td class="font-weight-bold alpha-success"><span class="{{($tot_btarget * 3) > $totq2 ? 'text-danger' : 'text-success' }}">{{ ($totq2 > 0 and $tot_btarget > 0) ? Rupiah( ($totq2/($tot_btarget*3)) * 100, 2 ) : 0 }}</span></td>

                        <td class="alpha-danger">{{ Rupiah($totb7) }}</td>
                        <td class="alpha-danger">{{ Rupiah($totb8) }}</td>
                        <td class="alpha-danger">{{ Rupiah($totb9) }}</td>
                        <td class="alpha-danger">{{ Rupiah($totq3) }}</td>
                        <td class="font-weight-bold alpha-danger"><span class="{{($tot_btarget * 3) > $totq3 ? 'text-danger' : 'text-success' }}">{{ ($totq3 > 0 and $tot_btarget > 0) ? Rupiah( ($totq3/($tot_btarget*3)) * 100, 2 ) : 0 }}</span></td>


                        <td class="alpha-violet">{{ Rupiah($totb10) }}</td>
                        <td class="alpha-violet">{{ Rupiah($totb11) }}</td>
                        <td class="alpha-violet">{{ Rupiah($totb12) }}</td>
                        <td class="alpha-violet">{{ Rupiah($totq4) }}</td>
                        <td class="font-weight-bold alpha-violet"><span class="{{($tot_btarget * 3) > $totq4 ? 'text-danger' : 'text-success' }}">{{ ($totq4 > 0 and $tot_btarget > 0) ? Rupiah( ($totq4/($tot_btarget*3)) * 100, 2 ) : 0 }}</span></td>

                        <td>{{ Rupiah($totq1+$totq2+$totq3+$totq4) }}</td>
                        <td class="font-weight-bold"><span class="{{($tot_btarget * 3) > $totq4 ? 'text-danger' : 'text-success' }}">{{ ($totq1 > 0 and $tot_btarget > 0) ? Rupiah( (($totq1+$totq2+$totq3+$totq4)/($tot_btarget*12)) * 100, 2 ) : 0 }}</span></td>

                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
<!-- /basic datatable -->
<meta name="csrf-token" content="{{ csrf_token() }}" />

<script type="text/javascript">
    $(document).ready(function() {

        $('#btn_filter').on('click', function() {
            $('#btn_filter').html("<i class='icon-spinner9 spinner position-left'></i> Tampilkan");

            var tgl = $('#btn-tanggal').text();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var url = "<?php echo route('filterDashboard'); ?>";

            $.ajax({
                type: "POST",
                url: url,
                data: {
                    tgl: tgl,
                    _token: CSRF_TOKEN,
                },
                success: function(data) {
                    $('#btn_filter').html("Tampilkan");
                    $('#tbl_rekap').html('');
                    $('#tbl_rekap').html(data);
                    //alert(data);
                    // $('#taghasil').html("");
                    // $('#taghasil').html(data);
                }
            });
        })



    })
</script>

@endsection