@extends('user.layouts.app')
@section('title', '本人確認')

@section('content')
@include('user.layouts.header_under')
<div id="contents">
    <section id="confirm">

        <div class="inner_box">
            <h3>本人確認とは</h3>
            <div class="base_txt">
                本サービスをユーザー様にご利用いただくうえで、安全性を確保するため、センパイとして出品される場合や特定のレッスンを購入されるコウハイに身分証による本人確認をお願いしております。
            </div>
        </div>

    </section>

    <section id="indivisual">

        <div class="inner_box">
            <h3>ご本人情報</h3>
            <div class="base_txt">
                本人情報を確認してください。
            </div>
            <ul class="form_area">
                <li>
                    <h3 class="must">氏名</h3>
                    <ul class="select_float_box half_box">
                        <li>
                            <h4>姓</h4>
                            <div class="form_wrap shadow-glay for-warning">
                                <input type="text" name="firstname" value="{{$user_info['user_firstname']}}" placeholder="田中">
                                <p class="warning"></p>
                            </div>
                        </li>
                        <li>
                            <h4>名</h4>
                            <div class="form_wrap shadow-glay for-warning">
                                <input type="text" name="lastname" value="{{$user_info['user_lastname']}}" placeholder="太郎">
                                <p class="warning"></p>
                            </div>
                        </li>
                    </ul>
                </li>

                <li>
                    <h3 class="must">フリガナ</h3>
                    <ul class="select_float_box half_box">
                        <li>
                            <h4>姓</h4>
                            <div class="form_wrap shadow-glay for-warning">
                                <input type="text" name="sei" value="{{$user_info['user_sei']}}" placeholder="タナカ">
                                <p class="warning"></p>
                            </div>
                        </li>
                        <li>
                            <h4>名</h4>
                            <div class="form_wrap shadow-glay for-warning">
                                <input type="text" name="mei" value="{{$user_info['user_mei']}}" placeholder="タロウ">
                                <p class="warning"></p>
                            </div>
                        </li>
                    </ul>
                </li>

                <li>
                    <h3 class="must">生年月日</h3>
                    <ul class="select_float_box three_box select_area">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom mark_year shadow-glay for-warning">
                                <select id="year" name="year">
                                    @for($i = $year_100; $i < $year_10 ; $i++)
                                        <option @if($i == $user_info['birth_year']) {{"selected='selected'"}} @endif value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                                <p class="warning"></p>
                            </div>
                            <div>年</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom mark_month shadow-glay for-warning">
                                <select id="month" name="month">
                                    @for($i = 1; $i < 13 ; $i++)
                                        <option @if($i == $user_info['birth_month']) {{"selected='selected'"}} @endif value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>月</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom mark_day shadow-glay for-warning">
                                <select id="day" name="day">
                                    @for($i = 1; $i < 32 ; $i++)
                                        <option @if($i == $user_info['birth_day']) {{"selected='selected'"}} @endif value="{{$i}}">{{$i}}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>日</div>
                        </li>
                    </ul>
                </li>

                <li>
                    <h3 class="must">性別</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay for-warning">
                        <select id="sex" name="sex">
                            <option value="0" @if(0 == $user_info['user_sex']) {{"selected='selected'"}} @endif>指定なし</option>
                            <option value="1" @if(1 == $user_info['user_sex']) {{"selected='selected'"}} @endif>女性</option>
                            <option value="2" @if(2 == $user_info['user_sex']) {{"selected='selected'"}} @endif>男性</option>
                        </select>
                        <p class="warning"></p>
                    </div>
                </li>
            </ul>
        </div>


    </section>

    <section id="identification">

        <div class="inner_box">
            <h3>本人確認に利用できる書類</h3>
            <div class="base_txt">
                ご本人であることを確認するため、氏名・生年月日・有効期限が確認できる下記の書類のうち１つをご提出ください。
            </div>
        </div>

        <div class="inner_box">
            <a onclick="confirmData('{{ route("user.myaccount.confirm_drive") }}')">
                <div class="white_box">
                    <h3>運転免許証／運転経歴証明書</h3>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/coupon/img_license.png') }}" alt="運転免許証／運転経歴証明書">
                        <li>
                            <p>必要な内容</p>
                            <p>1.氏名</p>
                            <p>2.生年月日</p>
                        </li>
                    </ul>
                </div>
            </a>
        </div>

        <div class="inner_box">
            <a onclick="confirmData('{{ route("user.myaccount.confirm_health") }}')">
                <div class="white_box">
                    <h3>健康保険証</h3>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/coupon/img_health_card.png') }}" alt="健康保険証">
                        <li>
                            <p>必要な内容</p>
                            <p>1.氏名</p>
                            <p>2.生年月日</p>
                        </li>
                    </ul>
                </div>
            </a>
        </div>

        <div class="inner_box">
            <a onclick="confirmData('{{ route("user.myaccount.confirm_number") }}')">
                <div class="white_box">
                    <h3>マイナンバーカード</h3>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/coupon/img_mynumber.png') }}" alt="マイナンバーカード">
                        <li>
                            <p>必要な内容</p>
                            <p>1.氏名</p>
                            <p>2.生年月日</p>
                        </li>
                    </ul>
                    <p class="mark_kome kome_txt">
                        表面のみご提出してください。<br>
                        個人番号（マイナンバー）は必要ではありません。
                    </p>
                </div>
            </a>
        </div>

        <div class="inner_box">
            <a onclick="confirmData('{{ route("user.myaccount.confirm_student") }}')">
                <div class="white_box">
                    <h3>学生証</h3>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/coupon/img_student.png') }}" alt="学生証">
                        <li>
                            <p>必要な内容</p>
                            <p>1.氏名</p>
                            <p>2.生年月日</p>
                        </li>
                    </ul>
                </div>
            </a>
        </div>

        <div class="inner_box">
            <a onclick="confirmData('{{ route("user.myaccount.confirm_passport") }}')">
                <div class="white_box">
                    <h3>パスポート</h3>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/coupon/img_passport.png') }}" alt="パスポート">
                        <li>
                            <p>必要な内容</p>
                            <p>1.氏名</p>
                            <p>2.生年月日</p>
                        </li>
                    </ul>
                </div>
            </a>
        </div>

        <div class="inner_box">
            <a onclick="confirmData('{{ route("user.myaccount.confirm_jyumin") }}')">
                <div class="white_box">
                    <h3>住民票</h3>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/coupon/img_resident_card.png') }}" alt="住民票">
                        <li>
                            <p>必要な内容</p>
                            <p>1.氏名</p>
                            <p>2.生年月日</p>
                        </li>
                    </ul>
                    <p class="mark_kome kome_txt">
                        ６ヶ月以内に取得したものに限ります。
                    </p>
                </div>
            </a>
        </div>
    </section>

<script src="{{ asset('assets/user/js/validate.js') }}"></script>
<script>
    function confirmData(urlConf)
    {
        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        form_data.append("firstname", $("input[name='firstname']").val());
        form_data.append("lastname", $("input[name='lastname']").val());
        form_data.append("sei", $("input[name='sei']").val());
        form_data.append("mei", $("input[name='mei']").val());
        form_data.append("year", $("select[name='year']").val());
        form_data.append("month", $("select[name='month']").val());
        form_data.append("day", $("select[name='day']").val());
        form_data.append("sex", $("select[name='sex']").val());

        $.ajax({
            type: "post",
            url: '{{route('user.myaccount.postUserInfo')}}',
            data : form_data,
            dataType: 'json',
            contentType : false,
            processData : false,
            success : function(result) {
                if (result.code == "success") {
                    location.href = urlConf;
                } else
                {
                    if(result.msg.firstname != undefined)
                    {
                        addError($("input[name='firstname']"), result.msg.firstname);
                    }

                    if(result.msg.lastname != undefined)
                    {
                        addError($("input[name='lastname']"), result.msg.lastname);
                    }

                    if(result.msg.sei != undefined)
                    {
                        addError($("input[name='sei']"), result.msg.sei);
                    }

                    if(result.msg.mei != undefined)
                    {
                        addError($("input[name='mei']"), result.msg.mei);
                    }

                    if(result.msg.bithday != undefined)
                    {
                        addError($("select[name='year']"), result.msg.bithday);
                    }

                    if(result.msg.sex != undefined)
                    {
                        addError($("select[name='sex']"), result.msg.sex);
                    }

                }
            },
            error : function(error)
            {
                alert("An error occur..");
            }
        });


    }
</script>


</div><!-- /contents -->

@endsection

