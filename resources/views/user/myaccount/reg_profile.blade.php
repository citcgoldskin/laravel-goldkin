@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents" class="short">

    {{ Form::open(["route"=>"user.register.profile", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
    <section>
        <ul class="form_area">

            <li>
                <h3 class="must">ニックネーム</h3>
                <div class="form_wrap shadow-glay">
                    <input type="text" value="{{ old('name') }}" placeholder="例）センパイ太郎" name="name">
                    @error('name')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                </div>
            </li>

            <li>
                <h3 class="must">メールアドレス</h3>
                <div class="white_box shadow-glay" id="autoload-email">
                    <!-- ※自動入力 -->
                    <p>{{ $email }}</p>
                    <input type="hidden" value="{{ $email }}" placeholder="例）センパイ太郎" name="email">
                    @error('email')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                </div>
            </li>

            <li>
                <h3 class="must">パスワード</h3>
                <p class="pw_txt">※英字と数字の両方を含めて設定してください。</p>
                <div class="form_wrap shadow-glay">
                    <input type="password" value="" placeholder="7文字以上の半角英数字" name="password" id="input_password">
                    @error('password')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                </div>
                <div class="check-box">
                    　<div class="clex-box_03 pw_box">
                        　　<input type="checkbox" name="show_password" value="1" id="show_password">
                        　　<label for="show_password" id="password"><p>パスワードを表示する</p></label>
                        　　</div>
                    　</div>
            </li>

            <li>
                <h3 class="must">登録地</h3>
                <p class="pw_txt">登録地は後で変更できます。</p>
                <div class="form_wrap icon_form type_arrow_bottom w50 shadow-glay">
                    <select id="pref" name="user_area_id">
                        <option>都道府県選択</option>
                        @foreach(\App\Service\AreaService::getSecondAreaList() as $key => $value)
                            <option value="{{ $value['area_id'] }}" {{$value['area_id']  == old('user_area_id', '') ? "selected='selected'":''}}>{{ $value['area_name'] }}</option>
                        @endforeach
                    </select>
                </div>
            </li>

            <li>
                <h3 class="must">氏名</h3>
                <ul class="select_float_box half_box">
                    <li>
                        <h4>姓</h4>
                        <div class="form_wrap shadow-glay">
                            <input type="text" value="{{ old('user_firstname') }}" placeholder="田中" name="user_firstname">
                            @error('user_firstname')
                            <p class="error_text"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div>
                    </li>
                    <li>
                        <h4>名</h4>
                        <div class="form_wrap shadow-glay">
                            <input type="text" value="{{ old('user_lastname') }}" placeholder="太郎" name="user_lastname">
                            @error('user_lastname')
                            <p class="error_text"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div>
                    </li>
                </ul>
            </li>

            <li>
                <h3 class="must">フリガナ</h3>
                <ul class="select_float_box half_box">
                    <li>
                        <h4>セイ</h4>
                        <div class="form_wrap shadow-glay">
                            <input type="text" value="{{ old('user_sei') }}" placeholder="タナカ" name="user_sei">
                            @error('user_sei')
                            <p class="error_text"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div>
                    </li>
                    <li>
                        <h4>メイ</h4>
                        <div class="form_wrap shadow-glay">
                            <input type="text" value="{{ old('user_mei') }}" placeholder="タロウ" name="user_mei">
                            @error('user_mei')
                            <p class="error_text"><strong>{{ $message }}</strong></p>
                            @enderror
                        </div>
                    </li>
                </ul>
            </li>

            <li>
                <h3 class="must">生年月日</h3>
                @error('user_birthday')
                <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
                <ul class="select_flex three_box ver2 select_area">
                    <input type="hidden" name="user_birthday" value="//" id="birthday">
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="year" id="date_year" onchange="reset_days()">
                                <option value="---">---</option>
                                @foreach(range(date('Y')-10, date('Y')-80) as $year)
                                    <option value="{{ $year }}" {{ $year == old('year') ? "selected" : "" }}>{{ $year }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>年</div>
                    </li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="month" id="date_month" onchange="reset_days()">
                                <option value="---">---</option>
                                @for ( $i = 1; $i < 13; $i ++)
                                    <option value="{{ $i }}" {{ $i == old('month') ? "selected" : "" }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>月</div>
                    </li>
                    <li>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="day" id="date_day">
                                <option value="---">---</option>
                                @for ( $i = 1; $i < 32; $i ++)
                                    <option value="{{ $i }}" {{ $i == old('day') ? "selected" : "" }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>日</div>
                    </li>
                </ul>
            </li>

            <li>
                <h3 class="must">性別</h3>
                <div class="form_wrap icon_form type_arrow_bottom w50 shadow-glay">
                    @error('user_sex')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                    <select id="sex" name="user_sex">
                        <option value="{{config('const.sex.uncertain')}}" @if( old('user_sex') == config('const.sex.uncertain') ) selected @endif>指定なし</option>
                        <option value="{{config('const.sex.man')}}" @if( old('user_sex') == config('const.sex.man') ) selected @endif>男性</option>
                        <option value="{{config('const.sex.woman')}}" @if( old('user_sex') == config('const.sex.woman') ) selected @endif>女性</option>
                    </select>
                </div>
            </li>

        </ul>



        <div class="balloon no_arrow mb0">
            <p class="mark_left mark_kome ls1">
                本人情報は正しく入力してください。<br>
                会員登録後、修正するにはお時間を頂く場合があります。
            </p>
        </div>
    </section>


    <section id="link-area">

        <div class="link-area">
            <div class="orange_link"><a href="{{ route('using_rules') }}">利用規約</a></div>
            <div class="orange_link"><a href="{{ route('privacy_policy') }}">プライバシーポリシー</a></div>
        </div>

        <div class="inner_box">
            <div class="agree_required">
                <div class="agree_check_area">
                    <label for="agree_btn" class="agree_btn"><input id="agree_btn" type="checkbox">
                        <span class="label_inner ls1">
		                    <a href="{{ route('using_rules') }}">利用規約</a>および<a href="{{ route('privacy_policy') }}">プライバシーポリシー</a>に同意する。
                        </span>
                    </label>
                </div>
            </div>

            <div class="agree_btn_area btn_base shadow-glay mt20">
                <div class="btn_base btn_orange shadow">
                    <button type="submit" class="btn-send btn-glay agree_change" id="reg_profile_submit">SMS認証に進む</button>
                </div>
            </div>


        </div>

    </section>
    {{ Form::close() }}

</div><!-- /contents-->

@endsection

@section('page_js')
    <script>
        $('#agree_btn').on('click', function () {

            var checked = $(".agree_btn_area").hasClass('_check');
            if ( checked ) {
                $(".btn-glay").prop("disabled", false);
            } else {
                $(".btn-glay").prop("disabled", true);
            }
        });

        $('#show_password').on('click', function () {
            $('#show_password').toggleClass('__checked');
            checkShowPassword();
        });

        function checkShowPassword() {
            var checked = $('#show_password').hasClass('__checked');
            if ( checked ) {
                $('#input_password').attr('type', 'text');
            } else {
                $('#input_password').attr('type', 'password');
            }
        }

        $('#date_year').change(function () {
            getDate();
        });

        $('#date_month').change(function () {
            getDate();
        });

        $('#date_day').change(function () {
            getDate();
        });

        $(document).ready(function () {
            getDate();
        })

        function getDate() {
            var year = $('#date_year').val();
            var month = $('#date_month').val();
            var day = $('#date_day').val();
            birthday = year + '/' + month + '/' + day;
            $('#birthday').val(birthday);
        }

        function reset_days() {
            var year = $('#date_year').val();
            var month = $('#date_month').val();
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            var str_html = '<option>--</option>';
            for(var i=1; i<=last_day; i++){
                str_html += '<option value="' + i + '">' + i + '</option>'
            }
            $('#date_day').html(str_html);
        }
    </script>
@endsection
