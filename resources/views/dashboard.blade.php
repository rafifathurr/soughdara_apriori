<!DOCTYPE html>
<html lang="en">
@include('layouts.head')

<body>
    @csrf
    <div class="wrapper">
        @include('layouts.sidebar')
        <div class="main-panel">
            <div class="content">
                <div class="panel-header bg-primary-gradient">
                    <div class="page-inner py-5">
                        <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                            <div>
                                <h2 class="text-white pb-2 fw-bold">{{$title}}</h2>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="page-inner mt--5">
                    <div class="row">
                        <div class="col-md-7">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-head-row">
                                        <div class="card-title"><strong>Month Income of {{date('Y')}}</strong> </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container" style="min-height: 375px">
                                        <canvas id="statisticsChart"></canvas>
                                    </div>
                                    <div id="myChartLegend"></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="card">
                                <div class="card-header">
                                    <div class="card-title">Top Sales Products</div>
                                </div>
                                <div class="card-body pb-0">
                                @foreach($topproduct as $tp)
                                    <div class="d-flex">
                                        <div class="flex-1 pt-1 ml-2">
                                            <h6 class="fw-bold mb-1">{{$tp->product_name}}</h6>
                                        </div>
                                        <div class="d-flex ml-auto align-items-center">
                                            <h3 class="text-info fw-bold" style="color:rgb(12, 175, 12) !important;">+{{$tp->total}}</h3>
                                        </div>
                                    </div>
                                    <div class="separator-dashed"></div>
                                @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('layouts.footer')
                <script>
                    var ctx = document.getElementById('statisticsChart').getContext('2d');

                    <?php
                        $mos=[];
                        $income=[];
                    ?>
                    @foreach($month as $mo)
                        @foreach($mo as $m)
                            <?php
                            $mos[] = $m;
                            ?>
                        @endforeach
                    @endforeach
                    @foreach($incomepermonth as $pm)
                        @foreach($pm as $p)
                            <?php
                            $income[] = $p;
                            ?>
                        @endforeach
                    @endforeach
                    var month = @json($mos);
                    var incomemonth = @json($income);
                </script>
                <script>

                    var statisticsChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: month,
                            datasets: [ {
                                label: "Profit",
                                borderColor: '#1269db',
                                pointRadius: 0,
                                backgroundColor: '#006EFF8E',
                                legendColor: '#1269db',
                                fill: true,
                                borderWidth: 2,
                                data: incomemonth
                            }]
                        },
                        options : {
                            responsive: true,
                            maintainAspectRatio: false,
                            legend: {
                                display: false
                            },
                            tooltips: {
                                bodySpacing: 4,
                                mode:"nearest",
                                intersect: 0,
                                position:"nearest",
                                xPadding:10,
                                yPadding:10,
                                caretPadding:10,
                                callbacks: {
                                    label: (item) => `Rp. ${item.yLabel} ,-`,
                                },
                            },
                            layout:{
                                padding:{left:5,right:5,top:15,bottom:15}
                            },
                            scales: {
                                yAxes: [{
                                    ticks: {
                                        fontStyle: "500",
                                        beginAtZero: false,
                                        maxTicksLimit: 10,
                                        padding: 10,
                                        min: 0
                                    },
                                    gridLines: {
                                        drawTicks: false,
                                        display: false
                                    }
                                }],
                                xAxes: [{
                                    gridLines: {
                                        zeroLineColor: "transparent"
                                    },
                                    ticks: {
                                        padding: 10,
                                        fontStyle: "500"
                                    }
                                }]
                            },
                            legendCallback: function(chart) {
                                var text = [];
                                text.push('<ul class="' + chart.id + '-legend html-legend">');
                                for (var i = 0; i < chart.data.datasets.length; i++) {
                                    text.push('<li><span style="background-color:' + chart.data.datasets[i].legendColor + '"></span>');
                                    if (chart.data.datasets[i].label) {
                                        text.push(chart.data.datasets[i].label);
                                    }
                                    text.push('</li>');
                                }
                                text.push('</ul>');
                                return text.join('');
                            }
                        }
                    });
                </script>
            </div>
        </div>
    </div>
</body>

</html>
