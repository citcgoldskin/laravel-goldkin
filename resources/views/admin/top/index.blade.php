@extends('admin.layouts.app')

@section('title', 'ダッシュボード')

@section('content')
    <div id="contents">

        <div class="tabs form_page">
            <label class="page-title wp-100">ダッシュボード</label>
            <section class="top-sec">

                <div class="flex dash-info">
                    <div class="dv-left">
                        <div class="dv-block">会員数 ◯◯◯,◯◯◯</div>
                        <div class="dv-block">アプリDL ◯◯◯,◯◯◯</div>
                        <div class="flex dv-content">
                            <div class="dv-content-left">
                                <div>センパイ</div>
                                <div>◯◯,◯◯◯</div>
                            </div>
                            <div class="dv-content-right">
                                <div>コウハイ</div>
                                <div>◯◯,◯◯◯</div>
                            </div>
                        </div>
                    </div>
                    <div class="dv-middle"></div>
                    <div class="dv-right">
                        <div class="dv-block">
                            <div>アクセス数（今日）</div>
                            <div>◯◯,◯◯◯</div>
                        </div>
                        <div class="dv-bottom">
                            <div>アクセス数（30日間）</div>
                            <div>◯◯,◯◯◯</div>
                        </div>
                    </div>
                </div>

                <div class="flex dash-info">
                    <div class="dv-left">
                        <div class="dv-block">
                            会員数 ◯◯◯,◯◯◯<br>会員数 ◯◯◯,◯◯◯
                        </div>
                        <div class="flex">
                            <div class="dv-content-left">
                                <div>流通金額</div>
                                <div>◯◯,◯◯◯</div>
                            </div>
                            <div class="dv-content-right">
                                <div>売上（税込）</div>
                                <div>◯◯,◯◯◯</div>
                            </div>
                        </div>
                    </div>
                    <div class="dv-middle"></div>
                    <div class="dv-right">
                        <div class="dv-block">
                            <div>実施レッスン（今日）</div>
                            <div>◯◯,◯◯◯</div>
                        </div>
                        <div class="dv-block">
                            <div>予約成立（今日）</div>
                            <div>◯◯,◯◯◯</div>
                        </div>
                        <div class="dv-bottom">
                            <div>予約残（今日まで）</div>
                            <div>◯◯,◯◯◯</div>
                        </div>
                    </div>
                </div>
            </section>
            <section class="top-sec">
                <div class="fs-14 ft-bold">売上（税込）の内訳</div>
                <div class="flex dash-info">
                    <div class="dv-left">
                        <div class="dv-block">
                            <div>出品サービス</div>
                            <div class="flex-space">
                                <div>◯,◯◯◯件</div>
                                <div>◯,◯◯◯円</div>
                            </div>
                        </div>
                        <div class="dv-bottom">
                            <div>掲示板サービス</div>
                            <div class="flex-space">
                                <div>◯,◯◯◯件</div>
                                <div>◯,◯◯◯円</div>
                            </div>
                        </div>
                    </div>
                    <div class="dv-middle"></div>
                    <div class="dv-right pie-one wp-30">
                        <div>
                            <canvas id="myChart4"></canvas>
                        </div>
                        {{--<div class="pie">
                            <div class="pie_cover"><div class="pie_label">全<span style="font-size: 20px;">4</span>件</div></div>
                        </div>--}}
                    </div>
                </div>
            </section>

            <section class="top-sec">
                <div class="fs-14 ft-bold">カテゴリー別状況</div>
                <div class="canvas-area">
                    <canvas id="myChart"></canvas>
                </div>
                {{--<div id="chartContainer"></div>--}}
            </section>

            <section class="top-sec">
                <div class="fs-14 ft-bold">地域別レッスン状況</div>
                <div class="canvas-area">
                    <canvas id="myChart2"></canvas>
                </div>
            </section>

            <section class="top-sec">
                <div class="flex">
                    <div class="wp-70">
                        <div>
                            <canvas id="myChart3"></canvas>
                        </div>
                    </div>
                    <div class="wp-30">
                        <table>
                            <tr>
                                <td>センパイ</td>
                                <td>コウハイ</td>
                            </tr>
                            <tr>
                                <td><div class="flex"><span class="color-block" style="background: #4b77a9;"></span>アプリ</div></td>
                                <td>アプリ</td>
                            </tr>
                            <tr>
                                <td><div class="flex"><span class="color-block" style="background: #5f255f;"></span>アプリ</div></td>
                                <td>Web</td>
                            </tr>
                            <tr>
                                <td><div class="flex"><span class="color-block" style="background: #d21243;"></span>Web</div></td>
                                <td>アプリ</td>
                            </tr>
                            <tr>
                                <td><div class="flex"><span class="color-block" style="background: #B27200;"></span>Web</div></td>
                                <td>Web</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </section>
        </div>

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>

        section {

        }
        .pie-one {
            min-width: 180px;
        }
        .top-sec {
            background: white;
            margin-top: 20px;
            padding: 10px 10px !important;
        }
        table {
            width: 100%;
        }
        td {
            padding: 5px;
            border: 1px solid #ddd;
        }
        h3 {
            font-weight: normal;
        }
        h3.closed {
            margin-bottom: 0px;
        }
        .canvas-area {
            margin-top: 20px;
            width: 100%;
            max-height: 500px;
            height: auto;
        }
        .dash-info {
            font-size: 14px;
        }
        .dash-info:last-child {
            padding-top: 20px;
        }
        .dv-block {
            border-bottom: 1px solid #ddd;
            padding: 10px 5px;
        }
        .dv-bottom {
            padding: 10px 5px;
        }
        .dv-content {
            display: flex;
            justify-content: space-between;
        }

        .dv-content-left, .dv-content-right {
            width: 50%;
            padding: 10px 5px;
            text-align: center;
        }
        .dv-content-left {
            border-right: 1px solid #ddd;
        }
        .dv-left {
            width: 70%;
            border: 1px solid #ddd;
            display: grid;
        }
        .dv-middle {
            width: 20px;
        }
        .dv-right {
            border: 1px solid #ddd;
        }
        .color-block {
            width: 10px;
            height: 10px;
            margin-right: 3px;
        }
    </style>
@endsection
@section('page_js')
    {{--<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@0.4.0/dist/chartjs-plugin-datalabels.min.js"></script>
    {{--<script src="{{ asset('assets/admin/js/canvasjs.min.js') }}"></script>--}}
    <script>
        $(document).ready(function() {
            const ctx = document.getElementById('myChart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ["ランニング ウォーキング", "サイクリング", "水泳", "筋トレ", "ゴルフ", "カラオケ", "その他 アウトドア", "その他 インドア"],
                    datasets: [
                        {
                            label: 'Low',
                            data: [67.8, 40, 30, 23, 45, 67, 33, 44],
                            backgroundColor: '#fdfac6' // green
                        },
                        {
                            label: 'Moderate',
                            data: [23.3, 20, 50, 53, 15, 27, 13, 34],
                            backgroundColor: '#75D1ED' // yellow
                        },
                        {
                            label: 'High',
                            data: [11.4, 60, 50, 53, 15, 57, 63, 64],
                            backgroundColor: '#EEB898' // red
                        }
                    ]
                },
                options: {
                    scales: {
                        xAxes: [{
                            stacked: true,
                            width: 50,
                            ticks: {
                                fontSize: 10,
                                callback: function(label, index, labels) {
                                    if (/\s/.test(label)) {
                                        return label.split(" ");
                                    }else{
                                        return label;
                                    }
                                }
                            }
                        }],
                        yAxes: [{ stacked: true }]
                    },
                    plugins: {
                        datalabels: {
                            display: false,
                        },
                    }
                }
            });

            const ctx2 = document.getElementById('myChart2').getContext('2d');
            var myChart2 = new Chart(ctx2, {
                type: 'bar',
                data: {
                    labels: ["大阪府", "兵庫県", "奈良県", "和歌山県", "三重県", "滋賀県", "京都府", "新潟県"],
                    datasets: [
                        {
                            label: 'Low',
                            data: [67.8, 40, 30, 23, 45, 67, 33, 44],
                            backgroundColor: '#fdfac6' // green
                        },
                        {
                            label: 'Moderate',
                            data: [23.3, 20, 50, 53, 15, 27, 13, 34],
                            backgroundColor: '#b2f6fa' // yellow
                        },
                        {
                            label: 'High',
                            data: [11.4, 60, 50, 53, 15, 57, 63, 64],
                            backgroundColor: '#FB7122' // red
                        }
                    ]
                },
                options: {
                    scales: {
                        xAxes: [{
                            stacked: true,
                            width: 50,
                            ticks: {
                                fontSize: 10
                            }
                        }],
                        yAxes: [{
                            stacked: true
                        }]
                    },
                    plugins: {
                        datalabels: {
                            display: false,
                        },
                    }
                }
            });

            const ctx3 = document.getElementById('myChart3').getContext('2d');
            var data3 = [{
                data: [50, 55, 60, 33],
                labels: ["アプリ", "アプリ", "Web", "Web"],
                backgroundColor: [
                    "#4b77a9",
                    "#5f255f",
                    "#d21243",
                    "#B27200"
                ],
                borderColor: "#fff"
            }];

            var options3 = {
                tooltips: {
                    enabled: false
                },
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value*100 / sum).toFixed(1)+"%";
                            return percentage;
                        },
                        color: '#fff',
                    }
                }
            };

            var myChart3 = new Chart(ctx3, {
                type: 'pie',
                data: {
                    datasets: data3
                },
                options: options3
            });

            const ctx4 = document.getElementById('myChart4').getContext('2d');
            var data4 = [{
                data: [60, 33],
                labels: ["掲示板", "出品"],
                backgroundColor: [
                    "#4b77a9",
                    "#B27200"
                ],
                borderColor: "#fff"
            }];

            var options4 = {
                tooltips: {
                    enabled: false
                },
                plugins: {
                    datalabels: {
                        formatter: (value, ctx) => {
                            let sum = 0;
                            let dataArr = ctx.chart.data.datasets[0].data;
                            dataArr.map(data => {
                                sum += data;
                            });
                            let percentage = (value*100 / sum).toFixed(0)+"%";
                            return percentage;
                        },
                        color: '#fff',
                    }
                }
            };
            var myChart4 = new Chart(ctx4, {
                type: 'pie',
                data: {
                    datasets: data4
                },
                options: options4
            });

        });
    </script>
@endsection
