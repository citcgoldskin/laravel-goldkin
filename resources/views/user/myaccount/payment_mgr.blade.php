@extends('user.layouts.app')
@section('title', '売上管理・振込申請')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    @php
        $chat_label = array_column($chat_info, 'date_label');
        $chat_amount = array_column($chat_info, 'amount');
        $withdrawal_amount = array_column($chat_info, 'withdrawal');
        $current_month = \Carbon\Carbon::now()->format('Y-m-01');
    @endphp

    <section class="pt0">



        <div class="inner_box">
            <div class="flex-box align-center mb10 ">
                <h3 class="f16 mt30 ml0">売上金残高</h3>
                <p class="link-text"><a href="{{ route('user.myaccount.put_money_term') }}">振込申請期限を確認 ></a></p>
            </div>
            <div class="white_box">
                <ul class="border-none list_box">
                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>売上金残高合計</li>
                                <li>
                                    <p class="money01">{{ number_format($remain_all_price) }} <span>円</span></p>
                                    <p class="text-01">（{{ \Carbon\Carbon::parse($next_month_date)->format('n月j日') }}期限　{{ number_format($remain_next_month_price) }}円）</p>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>振込申請可能な売上</li>
                                <li>
                                    <p class="money02">{{ number_format($current_possible_price) }}<span>円</span></p>
                                    <p class="text-02"><a href="{{ route('user.myaccount.put_money') }}">振込申請 ></a></p>
                                    <!--<p class="text-02"><a href="E-45.php">振込申請 ></a></p>-->
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>{{ $possible_send_date }}以降の申請可能な売上</li>
                                <li>
                                    <p class="money02">{{ number_format($possible_send_price) }}<span>円</span></p>
                                </li>
                            </ul>
                            <ul class="nflex-text">
                                <li><p class="text-01 color_01 text-left">※売上の振込申請の詳細は<a href="{{ route('user.myaccount.payment_detail') }}">こちら</a></p>
                                    <p class="text-01 color_01 text-left">※振込手数料として一律150円かかります。</p></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="inner_box">
            <h3 class="f16">売上履歴</h3>
            <div class="white_box">
                <ul class="border-none list_box">
                    <li class="margin0 due_date">
                        <div class="border">
                            <ul class="border-none flex-text align-center">
                                <li>今日の売上</li>
                                <li>
                                    <p class="money02">{{ number_format(\App\Service\LessonService::getEarningAmount($obj_user->id, config('const.date_type.day'))) }}<span>円</span></p>
                                </li>
                            </ul>
                            <ul class="border-none flex-text align-center">
                                <li>今年の累計売上</li>
                                <li>
                                    <p class="money02">{{ number_format(\App\Service\LessonService::getEarningAmount($obj_user->id, config('const.date_type.year'))) }}<span>円</span></p>
                                </li>
                            </ul>

                        </div>
                    </li>

                    <li class="margin0 due_date">
                        <div>
                            <ul class="nflex-text">
                                <li class="margin0"><p class="margin0 text-01 color_01 text-left">※売上はレッスンスタート又はキャンセル完了時に計上されます。</p></li>
                            </ul>

                            <div class="canvas-area">
                                {{--<canvas id="canvas" height="280" width="600"></canvas>--}}
                                <canvas id="myChart"></canvas>
                            </div>

                        </div>
                    </li>



                </ul>
            </div>
        </div>

        <div class="inner_box lr-box">
            <div class="flex-box">
                <div class="left-box">
                    <p class="text-01">売上</p>
                    <p class="text-02"><span class="month-amount">{{ number_format($month_amount) }}</span> <span>円</span></p>
                </div>
                <div class="right-box">
                    <p class="text-01">出金</p>
                    <p class="text-02"><span class="month-withdrawal">{{ number_format($month_withdrawal) }}</span> <span>円</span></p>
                </div>
            </div>

        </div>

        <div id="payment_history">

        </div>

        <!--※金融機関登録済みユーザーの場合の表示↓-->
        @php
            $bank_id = is_object($obj_user) && $obj_user->bank ? $obj_user->bank : '';
            $bank_name = $bank_id ? \App\Service\BankService::getBankName($bank_id) : '';
            $bank_account_type = is_object($obj_user) && $obj_user->bank_account_type ? $obj_user->bank_account_type : '';
            $bank_account_type_name = config('const.bank_account_type.'.$bank_account_type);
        @endphp
        <div class="inner_box">

            <h3>振込先の口座情報</h3>
            <div class="white_box">
                <ul class="kouza-list">
                    <li>※ご登録のアカウント情報と口座名義が一致していません。<br>振込先の金融機関は必ずご本人名義の口座をご登録ください。</li>
                    <li>※口座名義が急性の場合は、必ず口座情報を変更してください。</li>
                    <li>※口座情報が変更されることなく振り込み申請期限を過ぎた売掛金は執行売上金となりますのでご注意ください。</li>
                </ul>
                <p class="link-text"><a href="">失効売上金とは? ></a></p>
                <ul class="border-none list_box">
                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>金融機関名</li>
                                <li class="fs-14">{{$bank_name}}</li>
                            </ul>
                        </div>
                    </li>

                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>支店名</li>
                                <li class="fs-14">{{ is_object($obj_user) && $obj_user->ob_bank_branch ? $obj_user->ob_bank_branch->name : '' }}</li>
                            </ul>
                        </div>
                    </li>
                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>口座種別</li>
                                <li class="fs-14">{{$bank_account_type_name}}</li>
                            </ul>
                        </div>
                    </li>
                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>口座番号</li>
                                <li class="fs-14">{{is_object($obj_user) && $obj_user->bank_account_no ? $obj_user->bank_account_no : ''}}</li>
                            </ul>
                        </div>
                    </li>
                    <li class="due_date">
                        <div>
                            <ul class="flex-text">
                                <li>口座名義</li>
                                <li class="fs-14">{{is_object($obj_user) && $obj_user->bank_account_name ? $obj_user->bank_account_name : ''}}</li>
                            </ul>
                        </div>
                    </li>


                </ul>
            </div>
            <p class="link-text"><a href="{{ route('user.myaccount.account', ['prev_url_id' => 4]) }}">口座情報の変更 ></a></p>
        </div>


        <!--※金融機関登録済みユーザーの場合の表示↓-->
        <!--
<div class="inner_box">
<h3>振込先の口座情報</h3>
<div class="padding-box white_box">
<h4 id="circle-orange_ttl2">!</h4>
<p class="caution-text">振込申請には金融機関の口座情報登録が必要です。<br>
振込申請期限までに口座情報を登録してください。<br>
登録されていない場合、売上金が失効してしまいますのでご注意ください。</p>

<div class="button-area">
  <div class="btn_base2 btn_orange">
   <a href="E-45.php">口座情報の登録</a>
  </div>
</div>

</div>

</div>

  -->

    </section>



</div><!-- /contents -->


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
@section('page_css')
    <style>
        .canvas-area {
            margin-top: 20px;
            width: 100%;
            height: 250px;
        }
        .month-amount, .month-withdrawal {
            font-size: 26px !important;
        }
        .scroll {
            height: auto;
            max-height: 430px;
            overflow: auto;
            border-bottom-left-radius: 20px;
            border-bottom-right-radius: 20px;
        }
        .list_box:after {
            background: url(../img/arrow_right.svg);
            width: 6px;
            height: 10px;
        }
    </style>
@endsection
@section('page_js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.js"></script>
    <script>
        $(document).ready(function() {
            getPaymentInfo({{ $current_month }});
            const ctx = document.getElementById('myChart').getContext('2d');
            const myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    /*labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],*/
                    labels: <?php echo json_encode($chat_label); ?>,
                    datasets: [{
                        label: '',
                        /*data: [12, 19, 3, 5, 2, 3],*/
                        data: <?php echo json_encode($chat_amount); ?>,
                        backgroundColor: '#5BBEEA',
                        stack: 'Stack 0'
                    }]
                },
                options: {
                    legend: {
                        display: false
                    },
                    maintainAspectRatio: false,
                    scaleStartValue : 0,
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        xAxes:[{
                            barPercentage: 0.4,
                            ticks: {
                                callback: function(value, index, ticks) {
                                    value = value.split('-');
                                    return parseInt(value[1]) + "月";
                                }
                            }
                        }],
                        yAxes:[{
                            ticks: {
                                min: 0,
                                precision: 0,
                                callback: function(value, index, ticks) {
                                    return value + "円";
                                }
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem) {
                                return tooltipItem.yLabel + "円";
                            }
                        }
                    },
                    onHover: function(e) {
                        e.target.style.cursor = 'pointer';
                    },
                    onClick: function(evt, element) {
                        if (element[0] != undefined) {
                            let x_value = this.data.labels[element[0]._index];
                            let y_value = this.data.datasets[0].data[element[0]._index];
                            $('.month-amount').text(y_value);

                            let withdrawal_arr =JSON.parse("{{ json_encode($withdrawal_amount) }}");

                            if (withdrawal_arr.length > 0 && withdrawal_arr[element[0]._index] != undefined) {
                                $('.month-withdrawal').text(withdrawal_arr[element[0]._index]);
                            }
                            // お支払い金額
                            getPaymentInfo(x_value);
                        }

                    }
                }
            });

            $('#payment_history').on('click', '.payment-history-li', function() {
                let lrs_id = $(this).attr('data-lrs-id');
                location.href = "{{ route('user.myaccount.payment_kouhai_detail') }}" + "?lrs_id=" + lrs_id;
            });
        });

        function getPaymentInfo(_date) {
            let condition = {
                _token: "{{ csrf_token() }}",
                month: _date
            };
            $.ajax({
                type: "post",
                url: '{{ route('user.myaccount.get_payment_with_condition') }}',
                data: condition,
                dataType: 'json',
                success: function (result) {
                    if(result.result_code == 'success') {
                        $('#payment_history').html('');
                        $('#payment_history').append(result.payment_list);

                    } else {
                    }
                }
            });
        }
    </script>

@endsection
