@extends('user.layouts.app')

@section('title', '予約内容の確認')

@section('$page_id', 'home')


@php
    use App\Service\CommonService;
    use App\Service\LessonService;
@endphp
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_under')

    <div id="contents">

        <!--main_-->
        <form action="./" method="post" name="form1" id="form1">

            <section>

                <ul class="reserved_top_box">
                    @php
                        $lesson_image = LessonService::getLessonFirstImage($obj_lesson);
                    @endphp
                    <li><img src="{{ CommonService::getLessonImgUrl($lesson_image) }}" alt=""></li>
                    <li>
                        <p class="lesson_ttl">{{$obj_lesson['lesson_title']}}</p>
                    </li>
                </ul>
            </section>

            <section>

                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <div class="white_box">
                        <div class="lesson_place">
                            @if($obj_lesson_request['lr_pos_discuss'] == 1)
                                <p>
                                    {{ $obj_lesson_request['discuss_lesson_area'] }}
                                </p>
                                <p>
                                    {{ $obj_lesson_request['lr_address'] }}
                                </p>
                            @else
                                <p>
                                    {{ implode('/', $obj_lesson['lesson_area_names']) }}
                                </p>
                            @endif
                        </div>
                        @if($obj_lesson_request['lr_pos_discuss'] == 1)
                            <div class="balloon balloon_blue font-small">
                                <p>{{ $obj_lesson_request['lr_address_detail'] }}</p>
                            </div>
                        @else
                            <div class="balloon balloon_blue font-small">
                                <p>{{ $obj_lesson['lesson_pos_detail'] }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="inner_box pb0">
                    <h3>支払い方法</h3>
                    <div class="form_wrap icon_form type_edit shadow-glay">
                        <button type="button" onClick="location.href='{{route('user.lesson.select_pay_method')}}'" class="form_btn">
                            <div class="payment_box">
                                <div>{{ $default_card && $default_card->cc_data ? $default_card->cc_data['card_brand'] : '' }}</div>
                                <div>●●●●　●●●●　●●●●　{{ $default_card && $default_card->cc_data ? $default_card->cc_data['last_4'] : '' }}</div>
                            </div>
                        </button>
                    </div>

                    <div class="card_ok">
                        <p>ご利用可能なクレジットカード一覧</p>
                        <div class="white_box">
                            <p>VISA / MasterCard / JCB / Discover / Diners Club / American Express</p>
                        </div>
                    </div>

                </div>

                <div class="inner_box">
                    <h3>お支払い金額</h3>
                    @php
                        $cancel_price_1 = 0; // ご利用前日のキャンセル
                        $cancel_price_2 = 0; // ご利用当日のキャンセル
                        $all_lesson_fee = 0; // レッスン料
                        $all_service_fee = 0; // サービス料
                        $all_traffic_fee = 0; // 出張交通費
                    @endphp
                    @foreach($schedules_info as $key=>$schedule_info)
                        @if($schedule_info->lrs_state == config('const.schedule_state.confirm'))
                            @php
                                $senpai_id = $schedule_info['lesson_request']['lesson']['lesson_senpai_id'];
                                $is_coupon = $schedule_info['lesson_request']['lesson']['lesson_coupon'];
                                $choose_result = \App\Service\CouponService::chooseCoupon($senpai_id, $user_id, $schedule_info['lrs_amount']);
                                if($is_coupon > 0){
                                    if($choose_result['code'] == 'new'){
                                        $coupon_info['state'] = 'new';
                                        $coupon_info['coupon'] = $choose_result['obj'];
                                        $coupon_info['valid_cnt'] = $choose_result['valid_cnt'];
                                        $coupon_info['cup_id'] = $choose_result['obj']['cup_id'];
                                        $coupon_info['cpu_id'] = 0;
                                    }else if($choose_result['code'] == 'exist'){
                                        $coupon_info['state'] = 'exist';
                                        $coupon_info['coupon'] = $choose_result['obj']['coupon'];
                                        $coupon_info['valid_cnt'] = $choose_result['valid_cnt'];
                                        $coupon_info['cup_id'] = 0;
                                        $coupon_info['cpu_id'] = $choose_result['obj']['cpu_id'];
                                    }else{
                                        $coupon_info['state'] = 'none';
                                        $coupon_info['cup_id'] = 0;
                                        $coupon_info['cpu_id'] = 0;
                                    }
                                }else{
                                    $coupon_info['state'] = 'none';
                                    $coupon_info['cup_id'] = 0;
                                    $coupon_info['cpu_id'] = 0;
                                }

                                $cancel_price_1 += round($schedule_info['lrs_amount'] + $schedule_info['lrs_service_fee']) * $cancel_before_1_percent / 100;
                                $cancel_price_2 += ($schedule_info['lrs_amount'] + $schedule_info['lrs_service_fee'] + $schedule_info['lrs_traffic_fee']) * $cancel_before_0_percent / 100;
                                $all_lesson_fee += $schedule_info['lrs_amount'];
                                $all_service_fee += $schedule_info['lrs_service_fee'];
                                $all_traffic_fee += $schedule_info['lrs_traffic_fee'];
                            @endphp
                            <div class="white_box">
                                <ul class="list_box">


                                    <li class="">

                                        <div class="al-top">
                                            <p>レッスン日時</p>
                                            <p class="mark">{{CommonService::getYMD($schedule_info['lrs_date']) }}
                                                <br>{{ CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}
                                                ({{CommonService::getIntervalMinute($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time'])}}
                                                分)
                                            </p>
                                        </div>
                                        <div>
                                            <p>参加人数</p>
                                            <p class="mark">
                                                {{CommonService::getManWomanStr($schedule_info['lesson_request']['lr_man_num'], $schedule_info['lesson_request']['lr_woman_num'])}}
                                            </p>
                                        </div>
                                    </li>

                                    <li>
                                        <div>
                                            <p>レッスン料(
                                                {{$schedule_info['lesson_request']['lr_man_num'] + $schedule_info['lesson_request']['lr_woman_num']}}
                                                名)</p>
                                            <p class="price_mark">
                                                <em>{{CommonService::showFormatNum($schedule_info['lrs_amount'])}}</em>
                                            </p>
                                            {{--<input type="hidden" id="lesson_fee_{{ $key }}" value="{{$schedule_info['lrs_amount']}}">--}}
                                        </div>
                                        <div>

                                            <p class="modal-link">
                                                <a class="modal-syncer" data-target="modal-service">サービス料</a>
                                            </p>

                                            <p class="price_mark">
                                                <em>{{CommonService::showFormatNum($schedule_info['lrs_service_fee'])}}</em>
                                            </p>
                                            {{--<input type="hidden" id="service_fee_{{ $key }}" value="{{$schedule_info['lrs_service_fee']}}">--}}

                                        </div>

                                        <div>
                                            <p>出張交通費</p>
                                            <p class="price_mark">
                                                <em>{{CommonService::showFormatNum($schedule_info['lrs_traffic_fee'])}}</em>
                                            </p>
                                            {{--<input type="hidden" id="traffic_fee_{{ $key }}" value="{{$schedule_info['lrs_traffic_fee']}}">--}}
                                        </div>
                                    </li>

                                    <li>
                                        <div>
                                            <p>合計（税込）</p>
                                            <p class="price_mark" id="total_price_{{ $key }}">{{ CommonService::showFormatNum($schedule_info['lrs_amount'] + $schedule_info['lrs_service_fee'] + $schedule_info['lrs_traffic_fee']) }}</p>
                                            <input type="hidden" id="total_fee_{{ $key }}" value="0">
                                        </div>
                                    </li>
                                    @if($key == count($schedules_info) - 1)
                                        <li>
                                            <input type="hidden" id="lesson_fee" value="{{ $all_lesson_fee }}">
                                            <input type="hidden" id="service_fee" value="{{ $all_service_fee }}">
                                            <input type="hidden" id="traffic_fee" value="{{ $all_traffic_fee }}">
                                            <div>
                                                <p>合計</p>
                                                <p class="price_mark" id="total_price"></p>
                                                <input type="hidden" id="total_fee" value="0">
                                            </div>
                                            <input type="hidden" id="cup_state"
                                                   @if($coupon_info['state'] == 'new')
                                                   value="1"
                                                   @elseif($coupon_info['state'] == 'exist')
                                                   value="2"
                                                   @else
                                                   value="0"
                                                @endif
                                            >
                                            <input type="hidden" id="cup_id" value="{{$coupon_info['cup_id']}}">
                                            <input type="hidden" id="cpu_id" value="{{$coupon_info['cpu_id']}}">
                                            @if($coupon_info['state'] != 'none')
                                                <div class="coupon_price">
                                                    <p>クーポン割引</p>
                                                    <p class="price_mark"><em>-{{CommonService::showFormatNum($coupon_info['coupon']['cup_reduce_money'])}}</em></p>
                                                    <input type="hidden" id="cup_reduce_money" value="{{$coupon_info['coupon']['cup_reduce_money']}}">
                                                </div>
                                                <div class="balloon balloon_orange font-small">
                                                    <p>あなたが保有しているクーポンは<a href="{{route('user.myaccount.coupon_box') . '/1'}}">こちら</a></p>
                                                </div>

                                                <div class="coupon_wrap">
                                                    <div class="flex">
                                                        <div class="pic">
                                                            {{--<img src="img/A-14/img_01.png" class="プロフィールアイコン">--}}
                                                        </div>
                                                        <div class="text">
                                                            <h2>{{$coupon_info['coupon']['cup_name']}}, {{$coupon_info['coupon']['cup_code']}}, {{$coupon_info['valid_cnt']}}枚</h2>
                                                        </div>
                                                    </div>
                                                    <div class="coupon_ticket" onclick="setChecked()">
                                                        <ul>
                                                            <li>
                                                                <div><h3><em>{{CommonService::showFormatNum($coupon_info['coupon']['cup_reduce_money'])}}</em>円分クーポン</h3></div>
                                                            </li>
                                                            <li>×<em>{{CommonService::showFormatNum($coupon_info['coupon']['cup_cnt_origin'])}}</em>枚</li>
                                                            <li class="hide_checked"><p class="price_mark"><em>{{CommonService::showFormatNum($coupon_info['coupon']['cup_sell_money'])}}</em></li>
                                                        </ul>
                                                    </div>
                                                    <input type="hidden" id="cup_sell_money" value="{{$coupon_info['coupon']['cup_sell_money']}}">
                                                    <p class="acc-btn"><span>詳細を見る</span></p>


                                                </div>

                                                <div class="hide-contents">
                                                    <div class="situation">
                                                        <p>有効期限：1枚目適用レッスン日から{{$coupon_info['coupon']['cup_period']}}日間<br>
                                                            適用条件：レッスン金額{{CommonService::showFormatNum($coupon_info['coupon']['cup_apply_condition'])}}円以上</p>
                                                    </div>
                                                    <div class="limited">
                                                        <h2>あやの
                                                            <small>センパイ</small>
                                                            限定
                                                        </h2>
                                                        <p>1枚目即適用・返金不可・他クーポンとの併用不可・同一センパイには1日1枚のみ使用可能・有償キャンセル時は消化扱いになります。</p>
                                                    </div>
                                                    <p class="cash_back"><a class="modal-syncer button-link"
                                                                            data-target="modal-coupon_henkin">クーポンの返金について</a></p>
                                                </div>
                                            @endif
                                        </li>
                                        @if($coupon_info['state'] != 'none')
                                            <li>
                                                <div class="total_price_with">
                                                    <p>合計</p>
                                                    <p class="price_mark" id="total_price_with_cup"></p>
                                                    <input type="hidden" id="total_fee_with_cup" value="0">
                                                </div>
                                            </li>
                                        @endif
                                    @endif
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="inner_box">
                    <h3>キャンセルポリシー</h3>
                    <ul class="list_box cancel_policy">

                        <li>
                            <div>
                                <p>ご利用前日のキャンセル</p>
                                <p class="space">
                                    <em>{{$cancel_before_1_percent}}%</em><br>
                                    <span>({{ CommonService::showFormatNum($cancel_price_1) }}円)</span>
                                </p>
                            </div>
                        </li>
                        <li>
                            <div>
                                <p>ご利用当日のキャンセル</p>
                                <p class="space">
                                    <em>{{$cancel_before_0_percent}}%</em>＋交通費<br>
                                    <span>({{ CommonService::showFormatNum($cancel_price_2) }}円)</span>
                                </p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-cancel_policy">キャンセルポリシーとは</a>
                                </p>
                            </div>


                        </li>

                    </ul>
                </div>

            </section>

            @if($obj_lesson_request['lr_state'] == config('const.req_state.response'))
                <div class="white-bk">
                    <div class="button-area">
                        <div class="btn_base btn_orange shadow">
                            <a onclick="checkReserve()">予約を確定する</a>
                        </div>
                    </div>
                </div>
            @endif

        </form>

    </div><!-- /contents -->

    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_js')
    <script type="text/javascript">
        $(document).ready(function () {
            var lesson_fee = $('#lesson_fee').val();
            var service_fee = $('#service_fee').val();
            var traffic_fee = $('#traffic_fee').val();
            var total_fee =  Number(lesson_fee) + Number(service_fee) + Number(traffic_fee);
            $('#total_price').html('<em>' + showFormatNum(total_fee) + '</em>');
            $('#total_fee').val(total_fee);
            $('#total_price_with_cup').html('<em>' + showFormatNum(total_fee) + '</em>');
            $('#total_fee_with_cup').val(total_fee);

        });
        function setChecked() {
            var check_obj = $('.coupon_ticket ul li:last-child').not(this);
            var total_fee = $('#total_fee_with_cup').val();
            var cup_state = $('#cup_state').val();
            var cup_reduce_fee = $('#cup_reduce_money').val();
            var cup_sell_fee = 0;
            var cup_total_fee = 0;
            if(cup_state == 1){
                cup_sell_fee = $('#cup_sell_money').val();
            }
            cup_total_fee = Number(cup_reduce_fee) - Number(cup_sell_fee);
            if($(check_obj).hasClass('show_checked')){
                $(check_obj).removeClass('show_checked');
                $(check_obj).addClass('hide_checked');
                total_fee = Number(total_fee) + Number(cup_total_fee);
                $('#total_price_with_cup').html('<em>' + showFormatNum(total_fee) + '</em>');
                $('#total_fee_with_cup').val(total_fee);
            }else{
                $(check_obj).removeClass('hide_checked');
                $(check_obj).addClass('show_checked');
                total_fee = Number(total_fee) - Number(cup_total_fee);
                $('#total_price_with_cup').html('<em>' + showFormatNum(total_fee) + '</em>');
                $('#total_fee_with_cup').val(total_fee);
            }

        }
        function checkReserve(){
            var check_obj = $('.coupon_ticket ul li:last-child').not(this);
            var cup_id = 0;
            var cpu_id = 0;
            if($(check_obj).hasClass('show_checked')){
                cup_id = $('#cup_id').val();
                cpu_id = $('#cpu_id').val();
            }
            location.href = "{{route('user.lesson.check_reserve_comp').'/'.$lr_id}}" + "/" + cup_id + "/" + cpu_id;
        }
    </script>
@endsection
