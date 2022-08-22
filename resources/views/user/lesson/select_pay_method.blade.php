@extends('user.layouts.app')

@section('title', 'お支払い方法')

@section('content')
    @include('user.layouts.header_under')

    @php
        use App\Service\CommonService;
    @endphp
    <!-- ************************************************************************
    本文
    ************************************************************************* -->
    <style>
        html, body {
            background: #FFF;
        }
    </style>

    <div id="contents" class="short">
        <!--main_-->
        {{ Form::open(["route"=>["user.lesson.set_pay_method"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            @if(is_object($default_card))
                <div class="pay_list pb0">
                    <div class="inner_box">
                        <h3>現在の支払い方法</h3>
                        <div class="white_box">
                            <h2 class="ttl-glay">クレジットカード</h2>
                            <div class="payment_box">
                                {{--<div><img src="{{\App\Service\CommonService::getCardCompanyIconUrl($default_card->cc_data['card_brand'])}}" alt=""></div>--}}
                                <div>{{ $default_card->cc_data['card_brand'] }}</div>
                                <div>●●●●　●●●●　●●●●　{{$default_card->cc_data['last_4']}}</div>
                                <div>/一括払い</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @foreach(config('const.payment_methods') as $key=>$method_name)
                <!-- ************************************************************ -->
                <div class="inner_wrap bb">
                    <input id="pay-check{{ $key }}" name="acd" class="acd-check" type="radio">
                    <label class="acd-label" for="pay-check{{ $key }}">{{ $method_name }}</label>
                    <div class="acd-content pay-content">
                        @if($key == config('const.payment_methods_code.card'))
                            <ul>
                                @foreach($cards as $key=>$card)
                                    <li>
                                        <div class="pay_inner">
                                            <label for="card-01">{{ $card->cc_data['card_brand'] }} ●●●●　●●●●　●●●●　{{$card->cc_data['last_4']}}</label>
                                            <input type="radio" name="card" id="card-01">
                                            <div>
                                                {{--<p>{{$data->cc_client_name}}</p>--}}
                                                <p class="deadline">
                                                    有効期限：{{ $card->cc_data['exp_year'] }}
                                                    /{{ $card->cc_data['exp_month'] }}</p>
                                            </div>
                                            {{--<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                                <select class="txt_left">
                                                    <option value="一括払い">一括払い</option>
                                                </select>
                                            </div>--}}
                                        </div>
                                    </li>
                                @endforeach
                                    <li>
                                        <div class="pay_inner icon_form type_arrow_right">
                                            <label for="card-03">新しいカードを登録</label>
                                            <input type="radio" name="card" id="card-03" onclick="location.href='{{ route('user.lesson.credit_card') }}'">
                                            <div></div>
                                        </div>
                                    </li>
                                </ul>
                        @elseif($key == config('const.payment_methods_code.e_money'))
                            <ul>
                                <li>
                                    <div class="pay_inner">
                                        <label for="money-01">Pay Pay</label>
                                        <input type="radio" name="emoney" id="money-01">
                                        <div></div>
                                    </div>
                                </li>
                                <li>
                                    <div class="pay_inner">
                                        <label for="money-02">LINE Pay</label>
                                        <input type="radio" name="emoney" id="money-02">
                                        <div></div>
                                    </div>
                                </li>
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach

            <!-- /contents -->
            <div class="white-bk">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="submit">内容を確定する</button>
                    </div>
                </div>
            </div>

        {{ Form::close() }}
    </div>

@endsection

