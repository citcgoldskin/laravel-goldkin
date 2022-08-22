@extends('user.layouts.app')
@section('title', '提案内容の確認')

@section('content')
    @include('user.layouts.header_under')
    <div id="contents">
        <section>

            <div class="center_ttl">
                <p class="light"><img src="{{ asset('assets/user/img/icon_light.svg') }}" alt=""></p>
                <p>提案内容を確認</p>
            </div>

            {{ Form::open(["route"=>["keijibann.input"], "method"=>"get", "id"=>"form1"]) }}
            <div class="inner_box">
                <div class="white_box effect_shadow">
                    <ul class="list_box teian_box">
                        <li>
                            <p class="teian_price">提案金額</p>
                            <p class="big_price">{{\App\Service\CommonService::showFormatNum($data['prop_money'])}}<small>円</small></p>
                            <input type="hidden" name="prop_money" value="{{$data['prop_money']}}">
                        </li>

                        {{--<li>
                            <p class="teian_price">出張交通費</p>
                            <p class="big_price">{{\App\Service\CommonService::showFormatNum($data['traffic_fee'])}}<small>円</small></p>
                            <input type="hidden" name="traffic_fee" value="{{$data['traffic_fee']}}">
                        </li>--}}

                        <li>
                            <p class="teian_date">提案日時(レッスン開始日時)</p>
                            <p>{{$data['prop_month']}}<small>月</small>{{$data['prop_day']}}<small>日</small> {{str_pad($data['start_hour'], 2, "0", STR_PAD_LEFT)}}:{{str_pad($data['start_minute'], 2, "0", STR_PAD_LEFT)}}~{{str_pad($data['end_hour'], 2, "0", STR_PAD_LEFT)}}:{{str_pad($data['end_minute'], 2, "0", STR_PAD_LEFT)}}</p>
                            <input type="hidden" name="start_hour" value="{{$data['start_hour']}}">
                            <input type="hidden" name="start_minute" value="{{$data['start_minute']}}">
                            <input type="hidden" name="end_hour" value="{{$data['end_hour']}}">
                            <input type="hidden" name="end_minute" value="{{$data['end_minute']}}">
                        </li>

                        <li>
                            <p class="teian_date">レッスン時間</p>
                            <p>{{ \App\Service\CommonService::getTimeUnit($data['period_start']) }}</p>
                            <input type="hidden" name="start_hour" value="{{$data['start_hour']}}">
                            <input type="hidden" name="start_minute" value="{{$data['start_minute']}}">
                            <input type="hidden" name="end_hour" value="{{$data['end_hour']}}">
                            <input type="hidden" name="end_minute" value="{{$data['end_minute']}}">
                            <input type="hidden" name="period_start" value="{{$data['period_start']}}">
                            <input type="hidden" name="period_end" value="">
                        </li>

                        <li>
                            <p class="teian_limit">購入期限</p>
                            <p>{{$data['month']}}<small>月</small>{{$data['day']}}<small>日</small> {{str_pad($data['buy_hour'], 2, "0", STR_PAD_LEFT)}}:{{str_pad($data['buy_minute'], 2, "0", STR_PAD_LEFT)}}<small>まで</small></p>
                            <input type="hidden" name="buy_month" value="{{$data['month']}}">
                            <input type="hidden" name="buy_day" value="{{$data['day']}}">
                            <input type="hidden" name="buy_hour" value="{{$data['buy_hour']}}">
                            <input type="hidden" name="buy_minute" value="{{$data['buy_minute']}}">
                        </li>

                    </ul>
                </div>
            </div>
            <input type="hidden" name="rc_id" value="{{$data['rc_id']}}">
            <input type="hidden" name="mode" value="input">
            <input type="hidden" name="user_id" value="{{$data['user_id']}}">
            <input type="hidden" name="user_name" value="{{$data['user_name']}}">
            <input type="hidden" name="user_avatar" value="{{$data['user_avatar']}}">
            <input type="hidden" name="price_mark" value="{{$data['price_mark']}}">
            <input type="hidden" name="fee_type" value="{{$data['fee_type']}}">

            <textarea style="display : none;" name="message">{{$data['message']}}</textarea>
            {{ Form::close() }}

        </section>

        <footer>
            <div id="footer_button_area" class="result">
                <ul>
                    <li>
                        <div class="btn_base btn_white clear_btn shadow-glay"><a onclick="submitForm('{{route('keijibann.input')}}');">修正する</a></div>
                    </li>
                    <li>
                        <div class="btn_base btn_orange shadow"><a onclick="submitForm('{{route('keijibann.conf_com')}}');">提案する</a></div>
                    </li>
                </ul>
            </div>
        </footer>
    </div><!-- /contents -->
    <script>
        function submitForm(url)
        {
            $('#form1').attr('action', url);
            $('#form1').submit();
        }
    </script>


@endsection

