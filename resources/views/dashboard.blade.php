@extends('layouts.home')

@section('maincontent')
@include('layouts.mylib')

<script src="{{ url('/') }}/global_assets/js/plugins/visualization/echarts/echarts.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/visualization/d3/d3.min.js"></script>
<script src="{{ url('/') }}/global_assets/js/plugins/visualization/d3/d3_tooltip.js"></script>
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

<!-- Widgets with charts -->
<div class="card text-center">
    <div class="card-header pb-2 pt-2 ml-2">
        <h5>
            PROJECT DASHBOARD
        </h5>
    </div>

    <div class="card-body">

        <div class="row">
            <div class="col-sm-6 col-xl-3">


                <div class="card text-center bg-blue-600 has-bg-image">
                    <div class="card-body mb-2">
                        <h3 class="font-weight-semibold mb-0 mt-1">Target</h3>
                        <div class="mb-4">
                            <h5>Tahun {{date('Y')}}</h5>
                        </div>

                        <div class="svg-center position-relative" id="">
                            <h2> Rp. {{ rupiah($targettahun)}},- </h2>
                        </div>
                        <div class="align-self-center">
                            <i class="icon-target icon-4x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6 col-xl-2">
                <div class="card text-center bg-violet has-bg-image" style="background-color: #ff5c0e;">
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

            </div>
            <div class="col-sm-6 col-xl-2">

                <div class="card text-center bg-violet has-bg-image">
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
            </div>

            <div class="col-sm-6 col-xl-2">

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


            </div>
            <div class="col-sm-6 col-xl-3">


                <div class="card text-center bg-danger-400 has-bg-image">
                    <div class="card-body">
                        <h3 class="font-weight-semibold mb-0 mt-1">{{$targettahun > $cashin ? 'Defisit' : 'Surplus'}}</h3>
                        <div class="mb-4">
                            <h5>sampai bulan ini</h5>
                        </div>
                        <div class="mb-3">
                            <h2>Rp. {{ rupiah( $targettahun - $cashin) }},-</h2>
                        </div>
                        <div class="align-self-center">
                            <i class="icon-stats-decline2 icon-4x opacity-75"></i>
                        </div>
                    </div>

                </div>


            </div>
        </div>
        <!-- /stats with progress -->
    </div>

    <!-- Basic columns -->
    <div class="card border-top-1  border-bottom-1  rounded-0">
        <div class="card-header header-elements-inline bg-light">
            <h5 class="card-title">Grafik Target dan Progress (Cash-In) Tahun {{date('Y')}} (*Milyar)</h5>
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

    <hr>
    <div class="card-header pb-2 pt-2 ml-2">
        <h5>
            ANGGOTA
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-info has-bg-image">
                    <div class="media">
                        <div class="media-body">
                                <h2>{{ $anggota[1]->jumlah }}</h2>
                                <span class="text-uppercase">Aktif</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-user-check icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-warning has-bg-image">
                    <div class="media">
                        <div class="media-body">
                                <h2>{{ $anggota[0]->jumlah }}</h2>
                                <span class="text-uppercase">Non Aktif </span>
                            </a>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-user-block icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-danger has-bg-image">
                    <div class="media">
                        <div class="media-body">
                                <h2>{{ $anggota[2]->jumlah ?? 0 }}</h2>
                                <span class="text-uppercase">Keluar </span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-user-block icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-3">
                <div class="card card-body bg-teal has-bg-image">
                    <div class="media">
                        <div class="media-body">
                                <h2>{{$anggota[0]->jumlah + $anggota[1]->jumlah + ($anggota[2]->jumlah ??  0) }}</h2>
                                <span class="text-uppercase">Jumlah </span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-users4 icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <hr>
    <div class="card-header pb-2 pt-2 ml-2">
        <h5>
            SALDO SIMPANAN
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-info has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2>Rp. {{ Rupiah($simpanan[0]->wajib + $simpanan[0]->saldowajib) }} </h2>
                            <span class="text-uppercase">Wajib</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-coin-dollar icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-danger-800 has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2>Rp. {{ Rupiah($simpanan[0]->pokok + $simpanan[0]->saldopokok) }} </h2>
                            <span class="text-uppercase">Pokok</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-cash2 icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-teal has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2>Rp. {{ Rupiah($simpanan[0]->wajib + $simpanan[0]->saldowajib + $simpanan[0]->pokok + $simpanan[0]->saldopokok) }} </h2>
                            <span class="text-uppercase">Jumlah</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-cash4 icon-4x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <hr>
    <div class="card-header pb-2 pt-2 ml-2">
        <h5>
            PURCHASING
        </h5>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-orange has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2> 0</h2>
                            <span class="text-uppercase">Pembelian Belum Dibayar</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-coin-dollar icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-teal has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2> 0 </h2>
                            <span class="text-uppercase">Pembelian Jatuh Tempo</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-stack2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-xl-4">
                <div class="card card-body bg-blue has-bg-image">
                    <div class="media">
                        <div class="media-body">
                            <h2> 0 </h2>
                            <span class="text-uppercase">Pelunasan Bulan Ini</span>
                        </div>

                        <div class="ml-3 align-self-center">
                            <i class="icon-thumbs-up2 icon-3x opacity-75"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>


@endsection