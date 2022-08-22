@extends('user.layouts.app')
@section('title', 'クーポン発行')

@section('content')
@include('user.layouts.header_under')
<div id="contents">
    <!--main_-->
            <section class="pb20">
                <div class="inner_box">
                    <h3>クーポン名<small>（30文字まで）</small></h3>
                    <div class="input-text2 lesson_ttl_textarea for-warning">
                        <textarea name="coupon_name" id="coupon_name" placeholder="" maxlength="30" class="shadow-glay">{{old('coupon_name', '')}}</textarea>
                        <p class="warning"></p>
                    </div>
                </div>
                <div class="inner_box">
                    <h3>適用条件</h3>
                    <div class="input-text for-warning">
                        <input type="text" id="coupon_condition" name="coupon_condition" class="w50 shadow-glay" value="{{old('coupon_condition', '')}}">
                        <span class="unit">円以上</span>
                        <p class="warning"></p>
                    </div>
                    <p class="input_txt pt10">※1,000〜100,000円の範囲で入力してください</p>
                </div>

                <div class="inner_box">
                    <h3>クーポンのセット枚数</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay for-warning">
                        <select name="coupon_number" id="coupon_number">
                            <option value="0" {{old('coupon_number', 0) == 0 ? "selected='selected'" : ''}}>選択してください</option>
                            <option value="2" {{old('coupon_number', 0) == 2 ? "selected='selected'" : ''}}>2枚</option>
                            <option value="3" {{old('coupon_number', 0) == 3 ? "selected='selected'" : ''}}>3枚</option>
                            <option value="4" {{old('coupon_number', 0) == 4 ? "selected='selected'" : ''}}>4枚</option>
                            <option value="5" {{old('coupon_number', 0) == 5 ? "selected='selected'" : ''}}>5枚</option>
                        </select>
                        <p class="warning"></p>
                    </div>
                </div>

                <div class="inner_box">
                    <h3>有効期限</h3>
                    <div class="flex_wrap">
                        <div class="input_txt">1枚目の使用から</div>
                        <div class="form_wrap icon_form type_arrow_bottom w50 shadow-glay for-warning">
                            <select name="coupon_period" id="coupon_period">
                            </select>
                            <p class="warning"></p>
                        </div>
                        <div class="input_txt">日間</div>
                    </div>
                    <p class="input_txt pt10" style="left: 107px; position: relative; display: inline-block;">※10〜30の範囲で選択</p>
                </div>

                <div class="inner_box">
                    <h3>割引金額</h3>
                    <div class="input-text for-warning">
                        <input type="text" name="coupon_discount" id="coupon_discount" class="w50 shadow-glay" value="{{old('coupon_discount', '')}}">
                        <span class="unit">円</span>
                        <p class="warning"></p>
                    </div>
                    <p class="input_txt pt10">※1,000〜100,000円の範囲で入力してください</p>
                </div>

                <div class="inner_box">
                    <h3>販売金額</h3>
                    <div class="input-text for-warning">
                        <input type="text" name="coupon_buy" id="coupon_buy" class="w50 shadow-glay" value="{{old('coupon_buy', '')}}">
                        <span class="unit">円</span>
                        <p class="warning"></p>
                    </div>
                    <p class="input_txt pt10">※1,000〜100,000円の範囲で入力してください</p>
                </div>
            </section>
            <section id="button-area">
                <div class="inner_box mlr15">
                    <ul class="issue_coution_list">
                        <li>
                            クーポンはレッスン総額から割引されます。
                            <p class="modal-link modal-link_blue">
                                <a class="modal-syncer button-link" data-target="modal-ex_tekiyou">クーポン適用例はこちら</a>
                            </p>
                        </li>
                        <li>1枚目のクーポンは購入時に即適用されます。</li>
                        <li>販売済みのクーポンはどのような理由があっても返金することは出来ません。</li>
                        <li>一度購入されたクーポンは、後から編集／削除出来ません。</li>
                    </ul>
                </div>
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="submit" class="" onclick="sendFormData();">クーポンを発行する</button>
                    </div>
                </div>

            </section>
</div><!-- /contents -->
<!-- モーダル部分 *********************************************************** -->
<iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
<div class="modal-wrap coupon-ok">
    <div id="modal-new_issue" class="modal-content coupon-ok">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    クーポンを<br>
                    発行しました
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

<!-- ********************************************************* -->

<div class="modal-wrap coupon_modal">

    <div id="modal-ex_tekiyou" class="modal-content">
        <div class="modal_body">

            <div class="close_btn_area">
                <a id="modal-close"><img src="img/x-mark.svg" alt="閉じる"></a>
            </div>

            <div class="modal_inner">

                <h2 class="modal_ttl">
                    クーポンの適用例
                </h2>

                <section>
                    <h3 class="case_ttl">
                        <span class="case_icon">例1</span>
                        <span>
		  自分で発行した500円クーポンをコウハイが使用した場合<br>
		  <small>（レッスン料金は3,000円の場合）</small>
		 </span>
                    </h3>

                    <ul class="coupon_price_list">
                        <li>
                            <div>レッスン料金</div>
                            <div class="price_mark">3,000</div>
                        </li>
                        <li>
                            <div>クーポン適用</div>
                            <div class="price_mark">-500</div>
                            <p>※料率B適用</p>
                        </li>
                        <li>
                            <div>手数料率B</div>
                            <div class="price_mark">-175</div>
                        </li>
                        <li>
                            <div>出張交通費</div>
                            <div class="price_mark">1,000</div>
                        </li>
                        <li>
                            <div>交通費手数料</div>
                            <div class="price_mark">-70</div>
                            <p>※交通費の手数料は定率7％</p>
                        </li>
                        <li class="total_price">
                            <div>手取り金額</div>
                            <div class="price_mark">3,225</div>
                        </li>
                    </ul>
                </section>


                <section>
                    <h3 class="case_ttl">
                        <span class="case_icon">例2</span>
                        <span>
		  運営が発行した500円クーポンをコウハイが使用した場合<br>
		  <small>（レッスン料金は3,000円の場合）</small>
		 </span>
                    </h3>

                    <ul class="coupon_price_list">
                        <li>
                            <div>レッスン料金</div>
                            <div class="price_mark">3,000</div>
                        </li>
                        <li>
                            <div>クーポン適用</div>
                            <div class="price_mark fc_red">-0</div>
                            <p class="txt_left">※運営発行のクーポンが使用されても出品者の負担はなし</p>
                        </li>
                        <li>
                            <div>手数料率B</div>
                            <div class="price_mark">-210</div>
                            <p>※料率B適用</p>
                        </li>
                        <li>
                            <div>出張交通費</div>
                            <div class="price_mark">1,000</div>
                        </li>
                        <li>
                            <div>交通費手数料</div>
                            <div class="price_mark">-70</div>
                            <p>※交通費の手数料は定率7％</p>
                        </li>
                        <li class="total_price">
                            <div>手取り金額</div>
                            <div class="price_mark">3,720</div>
                        </li>
                    </ul>
                </section>

                <section class="issue">
                    <div class="inner_box">
                        <ul class="issue_coution_list">
                            <li>上記はあくまで一例です。</li>
                            <li class="blue_link">手数料の条件の詳細は<a href="{{route('keijibann.fee')}}">販売手数料のついて</a>をご確認ください。</li>
                            <li class="blue_link">出張交通費の詳細は<a href="{{route('user.syutupinn.reserve_check')}}">出張交通費とは</a>をご確認ください。</li>
                        </ul>
                    </div>
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
<script src="{{ asset('assets/user/js/validate.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        setValidDate($('#coupon_number').val());
    })

    $('#coupon_number').change(function () {
        setValidDate(this.value);
    });

    function setValidDate(number)
    {
        var nStart = 0;
        var nEnd = 0;

        var html = "<option value='0'>選択してください</option>";

        if(number != 0)
        {
            nStart = 10 * (number - 1);
            nEnd = 10 * (2 * number - 1);

            for(var i = nStart; i <= nEnd; i++)
            {
                html += "<option value='" + i + "'>" + i + "</option>";
            }
        }
        $('#coupon_period').html(html);
    }

    function sendFormData()
    {
        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");

        form_data.append("coupon_name", $('textarea[name="coupon_name"]').val());
        form_data.append("coupon_condition", $('input[name="coupon_condition"]').val());
        form_data.append("coupon_number", $('select[name="coupon_number"]').val());
        form_data.append("coupon_period", $('select[name="coupon_period"]').val());
        form_data.append("coupon_discount", $('input[name="coupon_discount"]').val());
        form_data.append("coupon_buy", $('input[name="coupon_buy"]').val());

        $.ajax({
            type: "post",
            url: "{{route('user.myaccount.post_coupon_publish')}}",
            data : form_data,
            dataType: 'json',
            contentType : false,
            processData : false,
            success : function(result) {
                if(result.result_code == "success")
                {
                    $('.modal-wrap.coupon-ok,.modal-wrap.ok').fadeIn();
                    $('.modal-content.coupon-ok,.modal-content.ok').fadeIn();
                    $('#modal-overlay2').fadeIn();
                } else
                {
                    var message = result.message.error;
                    if ( message.coupon_name != undefined ) {
                        addError($('#coupon_name'), message.coupon_name);
                    }

                    if ( message.coupon_condition != undefined ) {
                        addError($('#coupon_condition'), message.coupon_condition);
                    }

                    if ( message.coupon_number != undefined ) {
                        addError($('#coupon_number'), message.coupon_number);
                    }

                    if ( message.coupon_period != undefined ) {
                        addError($('#coupon_period'), message.coupon_period);
                    }

                    if ( message.coupon_discount != undefined ) {
                        addError($('#coupon_discount'), message.coupon_discount);
                    }

                    if ( message.coupon_buy != undefined ) {
                        addError($('#coupon_buy'), message.coupon_buy);
                    }
                }
            }
        });
    }
</script>


<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

