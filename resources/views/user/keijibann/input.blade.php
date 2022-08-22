@extends('user.layouts.app')
@section('title', '提案内容の入力')

@section('content')
    @include('user.layouts.header_under')
    <div id="contents">
        {{ Form::open(["route"=>["keijibann.confirm"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        <section class="propose">
            <div class="white_box pt20">
                <span class="choice_lesson">この内容に提案中</span>
                <h3 class="prof-title">@php echo $data['rc_title']; @endphp</h3>

                <ul class="teacher_info_02">
                    <li class="icon"><img src="{{\App\Service\CommonService::getUserAvatarUrl($data['cruitUser']['user_avatar']) }}" class="プロフィールアイコン"></li>
                    <li class="about_teacher">
                        <div class="profile_name"><p>@php echo $data['cruitUser']['name']; @endphp<span>（@php echo $data['age'];@endphp）@php echo $data['sex']; @endphp</span></p></div>
                        @if($confirmed == 1)
                            <div class="honnin_kakunin"><p>本人確認済み</p></div>
                        @endif
                        <div class="t_right pt10">
                            <p class="orange_link icon_arrow orange_right">
                                <a href="{{route('user.myaccount.profile',['user_id'=>$data['rc_user_id']])}}">プロフィール</a>
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <section>

            <div class="inner_box">
                <ul class="list_box">
                    <li>
                        <div>
                            <p class="prof-title2">提案期限</p>
                            <p class="limit_txt">{{ \Carbon\Carbon::parse($data['proposal_period'])->format('n') }}<small>月</small>{{ \Carbon\Carbon::parse($data['proposal_period'])->format('j') }}<small>日</small>{{ \Carbon\Carbon::parse($data['proposal_period'])->format('H:i') }}</p>
                        </div>
                        @php
                            $rc_wish_minmoney = 0;
                            if ($data['rc_wish_minmoney']) {
                                $rc_wish_minmoney = $data['rc_wish_minmoney'];
                            }
                            $rc_wish_maxmoney = 10000;
                            if ($data['rc_wish_maxmoney']) {
                                $rc_wish_maxmoney = $data['rc_wish_maxmoney'];
                            }
                        @endphp
                        <div class="yosan">
                            <p>（予算 {{ \App\Service\CommonService::getLessonMoneyRange($data['rc_wish_minmoney'], $data['rc_wish_maxmoney']) }}）</p>
                        </div>
                        <div>
                            <p class="prof-title2">提案金額</p>
                            {{--<div class="input-text teian_box">
                                <input type="text" name="prop_money" class="w70 propose-money shadow-glay" value="{{old('prop_money', isset($proposal['pro_money']) ? $proposal['pro_money'] : (isset($params['prop_money']) ? $params['prop_money'] : ''))}}">円
                            </div>--}}
                            <div class="flex-space">
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay" style="width: 120px;">
                                    <select id="prop_money" name="prop_money" class="fourth fs-14">
                                        <option value="">指定なし</option>
                                        @for ($i = $rc_wish_minmoney; $i <= $rc_wish_maxmoney; $i+=500)
                                            <option value="{{$i}}"
                                                    @if(old('prop_money', isset($proposal['pro_money']) ? $proposal['pro_money'] : (isset($params['prop_money']) ? $params['prop_money'] : '')) == $i) selected="selected" @endif>{{ \App\Service\CommonService::showFormatNum($i) }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div style="margin-left: 10px;">円</div>
                            </div>
                        </div>
                        @error('prop_money')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror

                        <div class="balloon balloon_blue" style="font-weight: normal;">
                            <p>※レッスン場所までの交通費も含めて提案金額を選択してください。</p>
                        </div>

                        {{--<div >
                            <p class="prof-title2">出張交通費</p>
                            <div class="input-text teian_box">
                                <input type="text" name="traffic_fee" class="w70 propose-money shadow-glay" value="{{old('traffic_fee', isset($proposal['pro_traffic_fee']) ? $proposal['pro_traffic_fee'] : (isset($params['traffic_fee']) ? $params['traffic_fee'] : ''))}}">円
                            </div>
                            @error('traffic_fee')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>--}}

                    </li>
                </ul>

                <ul class="list_box line_top_bottom">
                    <li>
                        <div>
                            <p class="normal">現在の手数料率
                            </p>
                            <p id="fee_type_letter">{{$fee_type_letter}}</p>
                        </div>
                        <div>
                            <p class=" normal">売上金（目安）</p>
                            <p class="price_mark" id="price_mark">{{old('prop_money', isset($proposal['pro_money']) ? $proposal['pro_money'] : (isset($params['price_mark']) ? $params['price_mark'] : '0') )}}</p>
                            <input type="hidden" name="price_mark" value="">
                        </div>
                    </li>
                </ul>
                <div class="balloon balloon_blue">
                    <p>レッスン料金には所定の販売手数料がかかります。</p>
                </div>
                <p class="modal-link modal-link_blue">
                    <a class="button-link" href="{{route('keijibann.fee')}}">
                        販売手数料について
                    </a>
                </p>

                <ul class="list_box">
                    <li>
                        <div>
                            <p class="prof-title2">提案日時(レッスン開始日時)</p>
                            <p class="limit_txt">{{$data['month']}}<small>月</small>{{$data['day']}}<small>日</small></p>
                            <input type="hidden" name="prop_month" value="{{$data['month']}}">
                            <input type="hidden" name="prop_day" value="{{$data['day']}}">
                        </div>
                    </li>
                </ul>

                @php
                    $period_hour_start = \Carbon\Carbon::parse($data['start_time'])->format('H');
                    $period_min_start = \Carbon\Carbon::parse($data['start_time'])->format('i');
                    $period_hour_end = \Carbon\Carbon::parse($data['end_time'])->format('H');
                    $period_min_end = \Carbon\Carbon::parse($data['end_time'])->format('i');
                @endphp
                <ul class="time"><li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="start_hour" class="fourth">
                                @for($i = 0; $i < 24; $i++)
                                    <option value="{{$i}}"
                                        {{ $i == old('start_hour', isset($proposal['start_hour']) ? $proposal['start_hour'] : (isset($params['start_hour']) ? $params['start_hour'] : $period_hour_start) ) ? "selected='selected'":''}}
                                        {{ $i < $period_hour_start || $i > $period_hour_end ? 'disabled' : '' }}
                                    >{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select></div></li>
                    <li>：</li>
                    <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="start_minute" class="fourth">
                                @foreach(range(0, 45, 15) as $time)
                                    <option value="{{ $time }}" {{ $time == old('start_minute', isset($proposal['start_minute']) ? $proposal['start_minute'] : (isset($params['start_minute']) ? $params['start_minute'] : '') ) ? "selected='selected'":''}} >{{ str_pad($time, 2, "0", STR_PAD_LEFT) }}</option>
                                @endforeach
                            </select></div></li>
                    <li>～</li>
                    <li><div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="end_hour" class="fourth">
                                @for($i = 0; $i < 24; $i++)
                                    <option value="{{$i}}"
                                        {{ $i == old('end_hour', isset($proposal['end_hour']) ? $proposal['end_hour'] : (isset($params['end_hour']) ? $params['end_hour'] : $period_hour_end)) ? "selected='selected'":''}}
                                        {{ $i < $period_hour_start || $i > $period_hour_end ? 'disabled' : '' }}
                                    >{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select></div></li>
                    <li>：</li>
                    <li>	 <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="end_minute" class="fourth">
                                @foreach(range(0, 45, 15) as $time)
                                    <option value="{{ $time }}" {{ $time == old('end_minute', isset($proposal['end_minute']) ? $proposal['end_minute'] : (isset($params['end_minute']) ? $params['end_minute'] : '') ) ? "selected='selected'":''}} >{{ str_pad($time, 2, "0", STR_PAD_LEFT) }}</option>
                                @endforeach
                            </select></div></li>
                </ul>
                <div class="balloon balloon_blue">
                    <p>{{$data['start_time']}}〜{{$data['end_time']}}の範囲で入力してください。</p>
                </div>
                @error('start_hour')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('start_minute')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('end_hour')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                @error('end_minute')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="inner_box">
                <h3>レッスン時間</h3>
                <ul class="time2">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="period_start" class="fourth">
                                <option value="0">指定なし</option>
                                @for ($i = 15; $i <= 300; $i+=15)
                                    <option value="{{$i}}"
                                            @if(old('period_start', isset($proposal['lesson_period_start']) ? $proposal['lesson_period_start'] : (isset($data['rc_lesson_period_from']) ? $data['rc_lesson_period_from'] : '')) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                        分
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </li>
                    {{--<li>～</li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="period_end" class="fourth">
                                <option value="0">指定なし</option>
                                @for ($i = 15; $i <= 300; $i+=15)
                                    <option value="{{$i}}"
                                            @if(old('period_end', isset($proposal['lesson_period_end']) ? $proposal['lesson_period_end'] : (isset($data['rc_lesson_period_to']) ? $data['rc_lesson_period_to'] : '')) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                        分
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </li>--}}
                </ul>
                @error('period_start')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>


            <div class="inner_box">
                <h3>メッセージ<small>（任意）</small></h3>
                <div class="input-text2">
                    <textarea name="message" placeholder="アピールしたいポイントや提案の具体的な内容を入力すると成約率が上がります。" cols="50" rows="10" class="count-text shadow-glay">{{old('message', isset($proposal['pro_msg']) ? $proposal['pro_msg'] : (isset($params['message']) ? $params['message'] : '') )}}</textarea>
                    <p class="max_length"><span id="num">0</span>／1,000</p>
                    @error('message')
                    <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <h3>購入期限</h3>
                <input type="hidden" name="rc_period" id="rc_period" value="{{ $data['rc_period'] }}">
                <input type="hidden" name="purchase_period" id="purchase_period" value="">
                <div class="form_txt gray_txt type_top_10">
                    <p>（募集期限 {{ \App\Service\CommonService::getMDH($data['rc_period'])."まで" }}）</p>
                </div>
                @php
                    $current_month = \Carbon\Carbon::now()->format('m');
                    $current_day = \Carbon\Carbon::now()->format('d');
                    $current_hour = \Carbon\Carbon::now()->format('H');
                    $current_minute = \Carbon\Carbon::now()->format('i');
                @endphp
                <ul class="select_float_box half_box select_area">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="month" id="month">
                                @for($i = 1; $i < 13; $i++)
                                    <option value="{{$i}}"
                                        {{ $i == old('month', isset($proposal['pro_month']) ? $proposal['pro_month'] : (isset($params['buy_month']) ? $params['buy_month'] : $current_month) ) ? "selected='selected'":''}}
                                    >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>月</div>
                    </li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="day" id="day">
                                @for($i = 1; $i < 32; $i++)
                                    <option value="{{$i}}" {{ $i == old('day', isset($proposal['pro_day']) ? $proposal['pro_day'] : (isset($params['buy_day']) ? $params['buy_day'] : $current_day)) ? "selected='selected'":''}} >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>日</div>
                    </li>
                </ul>
                <ul class="select_float_box half_box select_area pt10">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="buy_hour" id="buy_hour">
                                @for($i = 0; $i < 24; $i++)
                                    <option value="{{$i}}" {{ $i == old('buy_hour', isset($proposal['pro_hour']) ? $proposal['pro_hour'] : (isset($params['buy_hour']) ? $params['buy_hour'] : $current_hour)) ? "selected='selected'":''}} >{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>時</div>
                    </li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="buy_minute" id="buy_minute">
                                @for($i = 0; $i < 60; $i++)
                                    <option value="{{$i}}" {{ $i == old('buy_minute', isset($proposal['pro_minute']) ? $proposal['pro_minute'] : (isset($params['buy_minute']) ? $params['buy_minute'] : $current_minute)) ? "selected='selected'":''}} >{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>分まで</div>
                    </li>
                </ul>
                @error('purchase_period')
                <span class="error_text">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror

            </div>

            <div id="footer_button_area" class="under_area">
                <ul>
                    <li class="send-request">
                        <div class="btn_base btn_orange shadow">
                            <button type="submit" id="btn_confirm">提案内容を確認する</button>
                        </div>
                    </li>
                </ul>
            </div>
            <input type="hidden" name="rc_id" value="{{$data['rc_id']}}">
            <input type="hidden" name="user_id" value="{{$data['cruitUser']['id']}}">
            <input type="hidden" name="user_name" value="{{$data['cruitUser']['name']}}">
            <input type="hidden" name="user_avatar" value="{{$data['cruitUser']['user_avatar']}}">
            <input type="hidden" name="fee_type" value="{{$fee_type}}">

        </section>
        @if(isset($mode) && $mode == "edit")
            <input type="hidden" name="mode" value="edit">
            <input type="hidden" name="pro_id" value="{{$proposal['pro_id']}}">
        @else
            <input type="hidden" name="mode" value="input">
            <input type="hidden" name="pro_id" value="0">
        @endif
        {{ Form::close() }}

        <input type="hidden" name="minMoney" value="{{$minMoney}}">
        <input type="hidden" name="fee_type_letter" value="{{$fee_type_letter}}">
        <input type="hidden" name="ratio" value="{{$ratio}}">
        <input type="hidden" name="fee_type_origin" value="{{$fee_type}}">

    </div><!-- /contents -->

    @include('user.layouts.modal')
    <!-- モーダル部分 / ここまで ************************************************* -->
    <footer>
        @include('user.layouts.fnavi')
    </footer>

    <script type="text/javascript">
        $(document).ready(function () {
            let price_fee = getFeeValue($('#price_mark').html(), {{$ratio}}, {{$minMoney}});
            console.log("price_fee", price_fee);
            if ($('#price_mark').html() != 0) {
                $('#price_mark').html($('#price_mark').html() - price_fee);
                $('input[name="price_mark"]').val(price_fee);
            }
        });

        $('#btn_confirm').click(function(e) {
            e.preventDefault();
            var rc_period = $('#rc_period').val();
            var month =  parseInt($('#month').children("option:selected").text());
            var day =  parseInt($('#day').children("option:selected").text());
            var buy_hour =  parseInt($('#buy_hour').children("option:selected").text());
            var buy_minute =  parseInt($('#buy_minute').children("option:selected").text());
            $('#purchase_period').val(rc_period.substr(0, 4) + "-" +withZero(month) + "-" +withZero(day) + " " + withZero(buy_hour) + ":" + withZero(buy_minute) + ":00");
            $('#form1').submit();
        });

        $('#prop_money').change(function(){
            var nMoney = getFeeValue(this.value, $('input[name="ratio"]').val(),  $('input[name="minMoney"]').val());
            console.log("nMoney", nMoney);
            console.log("minMoney", $('input[name="minMoney"]').val());
            console.log("fee_type_letter", $('input[name="fee_type_letter"]').val());
            console.log("fee_type_origin", $('input[name="fee_type_origin"]').val());
            if(nMoney == $('input[name="minMoney"]').val())
            {
                $('#fee_type_letter').html("A");
                $('input[name="fee_type"]').val({{config('const.fee_type.a')}});
            }
            else
            {
                $('#fee_type_letter').html($('input[name="fee_type_letter"]').val());
                $('input[name="fee_type"]').val($('input[name="fee_type_origin"]').val());
            }

            $('#price_mark').html(this.value - nMoney);
            $('input[name="price_mark"]').val(nMoney);
        });

    </script>

@endsection

