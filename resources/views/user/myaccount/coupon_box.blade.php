@extends('user.layouts.app')
@section('title', 'クーポンBOX')

@section('content')
@include('user.layouts.header_under')
<div id="contents">
    <section id="coupon">
        {{ Form::open(["method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
            <ul class="form_area">
                <li>
                    <div class="form_wrap">
                        <input id="code" name="code" type="text" value="{{$code}}" placeholder="クーポンコードの入力">
                    </div>
                </li>
            </ul>

            <div class="top-menu_wrap bg_none">
                <div class="top-menu">
                    <nav>
                        <ul class="conditions_box pb20">
                            <li></li>
                            <li>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                    <select id="popular" name="order" class="sort">
                                        <option {{ $order == config('const.coupon_sort.period_short') ? "selected" : '' }} value="{{config('const.coupon_sort.period_short')}}">有効期限が短い順</option>
                                        <option {{ $order == config('const.coupon_sort.period_long') ? "selected" : '' }} value="{{config('const.coupon_sort.period_long')}}">有効期限が長い順</option>
                                        <option {{ $order == config('const.coupon_sort.money_large') ? "selected" : '' }} value="{{config('const.coupon_sort.money_large')}}">割引金額が大きい順</option>
                                        <option {{ $order == config('const.coupon_sort.money_small') ? "selected" : '' }} value="{{config('const.coupon_sort.money_small')}}">割引金額が小さい順</option>
                                        <option {{ $order == config('const.coupon_sort.condition_large') ? "selected" : '' }} value="{{config('const.coupon_sort.condition_large')}}">適用条件が高い順</option>
                                        <option {{ $order == config('const.coupon_sort.condition_small') ? "selected" : '' }} value="{{config('const.coupon_sort.condition_small')}}">適用条件が安い順</option>
                                    </select>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        {{ Form::close() }}
    </section>


    <section>
        @foreach($coupons as $key=>$val)
            <div class="ticket-wrap mb20">
            <div class="ticket_header">
                <ul class="coupon_ttl">
                    <li class="coupon_icon"><img src="img/A-14/img_01.png" alt=""></li>
                    <li class="coupon_name two_line">
                        {{isset($val[0]['coupon']['cup_name'])? $val[0]['coupon']['cup_name'] : ''}}
                    </li>
                </ul>
            </div>
            <div class="ticket_rip"></div>
            <div class="ticket_body">
                <div class="ticket_limited">
                    <p>{{isset($val[0]['coupon']['user'][0]['user_sei']) ? $val[0]['coupon']['user'][0]['user_sei'] : ''}}<small>センパイ</small>限定</p>
                    <p>有効期限：{{\App\Service\TimeDisplayService::getDateFromDatetime($val[0]['cpu_date_to'])}}</p>
                </div>

                <div class="coupon_henkin">
                    <input id="coupon-{{$val[0]['cpu_id']}}" name="acd" class="acd-check" type="checkbox">
                    <label class="acd-label" for="coupon-{{$val[0]['cpu_id']}}"></label>
                    <div class="acd-content coupon_content">
                        <ul>
                            <li>返金不可</li>
                            <li>他クーポンとの併用不可</li>
                            <li>同一センパイには1日1枚のみ使用可能</li>
                            <li>有償キャンセル時は消化扱いになります。</li>
                        </ul>

                        <p class="modal-link modal-link_blue">
                            <a class="modal-syncer button-link" data-target="modal-coupon_henkin">クーポンの返金について</a>
                        </p>
                    </div>
                </div>

            </div>
        </div>
        @endforeach

        @if(count($coupons) == 0)
            <div class="no_coupon">
                <p><img src="{{asset('assets/user/img/img_coupon.svg')}}" alt=""></p>
                <p>現在持っているクーポンはありません</p>
            </div>
        @endif


    </section>


</div><!-- /contents -->
<!-- モーダル部分 *********************************************************** -->
<div class="modal-wrap coupon_modal">

    <div id="modal-coupon_henkin" class="modal-content">
        <div class="modal_body">

            <div class="close_btn_area">
                <a id="modal-close"><img src="img/x-mark.svg" alt="閉じる"></a>
            </div>

            <div class="modal_inner">

                <h2 class="modal_ttl">
                    クーポンの返金について
                </h2>

                <section class="case">

                    <h3 class="case_ttl">
                        <span class="case_icon">caseA</span>
                        <span>特定のセンパイ（出品者）が発行したクーポンの場合</span>
                    </h3>

                    <ul class="case_list">
                        <li>1枚でも使用されたクーポンは返金することは出来ません。</li>
                        <li>有償キャンセルをされた場合につきましても、クーポンを1枚使用したものとさせていただきますので、返金はいたしかねます。</li>
                        <li>無料キャンセルをされた場合パターンによって以下の対応をさせていただきます。</li>
                    </ul>

                    <h4 class="case_ttl_02">
                        キャンセルしたレッスン以降に同種のクーポンを使用しているレッスンを購入している場合
                    </h4>

                    <p class="modal_txt">
                        キャンセルしたレッスンに使用する予定であったクーポンを以降に同種のクーポンを使用しているレッスンの振替させていただきます。
                    </p>

                    <p class="henkin_img"><img src="{{ asset('assets/user/img/coupon/img_henkin.png') }}" alt=""></p>
                </section>

                <section class="case">

                    <h3 class="case_ttl">
                        <span class="case_icon">caseB</span>
                        <span>運営が発行したクーポンの場合</span>
                    </h3>

                    <h5 class="case_ttl_03 mark_square">
                        有償販売クーポン
                    </h5>

                    <p class="modal_txt">
                        原則返金は出来ません。<br>
                        クーポンに不備があった場合は例外的に買い戻しを行うことがあります。
                    </p>

                    <h4 class="case_ttl_02">
                        キャンセルしたレッスン以降に同種のクーポンを使用しているレッスンを購入している場合
                    </h4>

                    <p class="modal_txt">
                        キャンセルしたレッスンに使用する予定であったクーポンを以降に同種のクーポンを使用しているレッスンの振替させていただきます。
                    </p>

                    <p class="logic_img"><img src="{{ asset('assets/user/img/coupon/case_logic.png') }}" alt=""></p>

                    <h4 class="case_ttl_02">
                        3枚つづり300円で販売していたクーポンに不備があった場合
                    </h4>

                    <p class="modal_txt pb20">
                        ・3枚未使用　→300円で買い戻し<br>
                        ・2枚未使用　→200円で買い戻し<br>
                        ・1枚未使用　→100円で買い戻し<br>
                        ・全て使用　 →買い戻しは行いません
                    </p>

                    <h5 class="case_ttl_03 mark_square">
                        無料配布クーポン
                    </h5>

                    <p class="modal_txt">
                        予告なくクーポンの失効措置を実施する場合があります。<br>
                        その際、買い戻し対応などは行いません。<br>
                        あらかじめご了承ください。
                    </p>
                </section>

            </div>


            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a id="modal-close" class="button-link">OK</a>
                </div>
            </div>

        </div><!-- /modal-content -->

    </div>
</div>
    <!-- モーダル部分 / ここまで ************************************************* -->
<script>
    $('#popular').change(function(){
        submitForm();
    });

    $('#code').change(function(){
        submitForm();
    });

    function submitForm()
    {
        $('#form1').submit();
    }

</script>
<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

