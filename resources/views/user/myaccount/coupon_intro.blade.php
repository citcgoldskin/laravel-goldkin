@extends('user.layouts.app')
@section('title', 'クーポン管理')

@section('content')
@include('user.layouts.header_under')
<div id="contents">

    <div class="management_wrap">

        <div class="management_mv">
            <p>
                クーポンを発行して、<br>
                あなたのコウハイを<br>
                リピーターに誘導しましょう
            </p>
        </div>

        <p class="base_txt">
            クーポンを作成すると、あなたのクーポンを保有してないユーザーがあなたのレッスンを購入する際に自動でオススメされます。
        </p>

        <div class="buttom-area">
            <div class="btn_base btn_orange shadow">
                <a href="{{route('user.myaccount.coupon_publish')}}">新しくクーポンを発行する</a>
            </div>
        </div>

    </div>

    <section id="coupon" class="pt20">

        <div class="top-menu_wrap bg_none">
            <div class="top-menu">
                <nav>
                    <ul class="conditions_box">
                        <li><span>発行済のクーポン</span></li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                <select id="popular" name="popular" class="sort">
                                    <option value="作成日が新しい順">作成日が新しい順</option>
                                </select>
                            </div>
                        </li>
                    </ul>
                </nav>

                <p class="tekiyou_txt ls1">※最も割引額の大きいクーポンがレッスンに適用されます</p>
            </div>
        </div>
    </section>


    <section id="issue">

        @foreach($coupons as $key=>$val)
            <div class="ticket-wrap mb20">
                <div class="ticket_header">
                    <p class="coupon_name two_line">
                        {{$val['cup_name']}}
                    </p>
                    <ul class="issue_wrap">
                        <li><span class="coupon_orange">{{\App\Service\CommonService::showFormatNum($val['cup_reduce_money'])}}<small>円分</small>クーポン</span>×{{$val['cup_cnt_origin']}}<small>枚</small></li>
                        <li><p class="price_mark">{{\App\Service\CommonService::showFormatNum($val['cup_sell_money'])}}</p></li>
                    </ul>
                </div>
                <div class="ticket_rip"></div>
                <div class="ticket_body">
                    <div class="ticket_limited">
                        <p>有効期限：1枚目使用から{{$val['cup_period']}}日間</p>
                        <p>適用条件：レッスン金額{{\App\Service\CommonService::showFormatNum($val['cup_apply_condition'])}}円以上</p>
                    </div>
                </div>
                <span class="coupon_del modal-syncer" data-target="modal-delete-edit" data-cup-id="{{ $val->cup_id }}"><small>削除</small></span>
            </div>
        @endforeach

    </section>

    <input type="hidden" id="pages" value="{{$pages}}">

    {{--Modal--}}
    <div class="modal-wrap completion_wrap">
        <div id="modal-delete-edit" class="modal-content">

            <div class="modal_body">
                <div class="modal_inner">
                    <h4 id="circle-orange_ttl">!</h4>
                    <h2 class="modal_ttl">
                        削除してよろしいですか？
                    </h2>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_orange">
                        <button type="button" onclick="deleteCoupon();">削除</button>

                    </div>
                    <div class="btn_base btn_gray-line">
                        <a id="modal-close" class="button-link">キャンセル</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div><!-- /contents -->

<input type="hidden" name="cup_id" id="cup_id" value="">
{{--{{ Form::open(["route"=>"user.myaccount.coupon_del", "method"=>"post", "name"=>"frm_del_cup", "id"=>"frm_del_cup" ]) }}
    <input type="hidden" name="cup_id" id="cup_id" value="">
{{ Form::close() }}--}}

<div class="modal-wrap coupon-del-ok">
    <div id="modal-new_issue" class="modal-content coupon-del-ok">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    クーポンを<br>
                    削除しました
                </h2>
            </div>
        </div>

        <div class="button-area">
            <div class="btn_base btn_ok">
                <a href="{{route('user.myaccount.coupon_intro') . '/1'}}">OK</a>
            </div>
        </div>
    </div>
</div>
<div id="modal-overlay2" style="display: none;"></div>

<script>
    var pageCount = parseInt($('#pages').val());
    var isLoading = false;
    var pageCurrent = 1;
    $(window).scroll(function(){
        if(pageCount <= 1) return;
        if(pageCount <= pageCurrent) return;

        var totalHeight = document.documentElement.scrollHeight - $("#f-navi").outerHeight(true);
        var clientHeight = document.documentElement.clientHeight;
        var scrollTop = (document.body && document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;

        if ((totalHeight == scrollTop + clientHeight) || (totalHeight <= scrollTop + clientHeight + 3)) {
            if (isLoading) return;
            getMoreCoupons();
        }
    });

    $(document).ready(function () {
        $('.coupon_del').click(function() {
            let cup_id = $(this).attr('data-cup-id');
            console.log("cup_id", cup_id);
            $('#cup_id').val(cup_id);
        });
    });

    function deleteCoupon()
    {
        $('.modal-wrap.completion_wrap').fadeOut();
        // delete coupon ajax
        $.ajax({
            type: "post",
            url: "{{route('user.myaccount.coupon_del')}}",
            data : {
                _token: "{{csrf_token()}}",
                cup_id: $('#cup_id').val()
            },
            dataType: 'json',
            success : function(result) {
                if(result.result_code == "success")
                {
                    $('.modal-wrap.coupon-del-ok').fadeIn();
                    $('.modal-content.coupon-del-ok').fadeIn();
                }
            }
        });
        console.log("deleteCoupon");
        $('#frm_del_cup').submit();
    }

    function getMoreCoupons()
    {
        pageCurrent++;
        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        form_data.append("mode", "ajax");
        form_data.append("page", pageCurrent);
        isLoading = true;
        $.ajax({
            type: "get",
            url: "{{route('user.myaccount.coupon_intro')}}" + "/2" + "?page=" + pageCurrent,
            dataType: 'json',
            contentType: false,
            processData : false,
            success : function(result)
            {
                var coupons = result.coupons;
                appendCoupons(coupons.data);
            }
        });
    }

    function appendCoupons(data)
    {
        var html = "";
        for(var key in data)
        {
            var coupon = data[key];
            html += '<div class="ticket-wrap mb20"><a href=""><div class="ticket_header"><p class="coupon_name two_line">';
            html += coupon.cup_name;
            html += '</p><ul class="issue_wrap"><li><span class="coupon_orange">';
            html += coupon.cup_sell_money;
            html += '<small>円分</small>クーポン</span>×';
            html += coupon.cup_cnt_origin;
            html += '<small>枚</small></li><li><p class="price_mark">';
            html += coupon.cup_reduce_money;
            html += '</p></li></ul></div><div class="ticket_rip"></div><div class="ticket_body"><div class="ticket_limited">';
            html += '<p>有効期限：1枚目使用から';
            html += coupon.cup_period;
            html += '日間</p>';
            html += '<p>適用条件：レッスン金額';
            html += coupon.cup_apply_condition;
            html += '円以上</p></div></div></a></div>';
        }
        $('#issue').append(html);
        isLoading = false;
    }
</script>


<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

