@extends('user.layouts.app')

@section('content')
    @include('user.layouts.header_under')

    <!-- ************************************************************************
本文
************************************************************************* -->


    <div id="contents">

        {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section class="pb60">
            <ul class="form_area">
                <li>
                    <h3 class="must">氏名</h3>
                    <ul class="select_float_box half_box">
                        <li>
                            <h4>姓</h4>
                            <div class="form_wrap shadow-glay for-warning">
                                <input type="text" value="{{ $user_info['user_firstname'] }}" placeholder="田中" name="user_firstname" id="user_firstname">
                                <p class="warning"></p>
                            </div>
                        </li>
                        <li>
                            <h4>名</h4>
                            <div class="form_wrap shadow-glay for-warning">
                                <input type="text" value="{{ $user_info['user_lastname'] }}" placeholder="太郎" name="user_lastname" id="user_lastname">
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
                                <input type="text" value="{{ $user_info['user_sei'] }}" placeholder="タナカ" name="user_sei" id="user_sei">
                                <p class="warning"></p>
                            </div>
                        </li>
                        <li>
                            <h4>名</h4>
                            <div class="form_wrap shadow-glay for-warning">
                                <input type="text" value="{{ $user_info['user_mei'] }}" placeholder="タロウ" name="user_mei" id="user_mei">
                                <p class="warning"></p>
                            </div>
                        </li>
                    </ul>
                </li>
                @php
                    $birthday = isset($user_info) ? strtotime($user_info['user_birthday']): strtotime(date());
                    $birthday_year    = date('Y',$birthday);
                    $birthday_month   = date('n',$birthday);
                    $birthday_day     = date('j',$birthday);
                    $last_day         = date('t',$birthday);
                @endphp

                <li>
                    <h3 class="must">生年月日</h3>
                    <p class="birthday_warning"></p>
                    <ul class="select_float_box three_box select_area">
                        <input type="hidden" name="user_birthday" value="" id="birthday">
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom mark_year shadow-glay">
                                <select name="year" id="date_year" onchange="reset_days()">
                                    <option value="---">---</option>
                                    @for ( $i = 1900; $i < (date('Y')+1); $i ++)
                                        <option value="{{ $i }}" {{ $i == $birthday_year ? "selected" : "" }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>年</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom mark_month shadow-glay">
                                <select name="month" id="date_month" onchange="reset_days()">
                                    <option value="---">---</option>
                                    @for ( $i = 1; $i < 13; $i ++)
                                        <option value="{{ $i }}" {{ $i == $birthday_month ? "selected" : "" }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div>月</div>
                        </li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom mark_day shadow-glay">
                                <select name="day" id="date_day">
                                    <option value="---">---</option>
                                    @for ( $i = 1; $i <= $last_day; $i ++)
                                        <option value="{{ $i }}" {{ $i == $birthday_day ? "selected" : "" }}>{{ $i }}</option>
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
                        <p class="warning"></p>
                        <select id="user_sex" name="user_sex">
                            <option value="{{ config('const.sex.uncertain') }}" @if( $user_info['user_sex'] == config('const.sex.uncertain') ) selected @endif>指定なし</option>
                            <option value="{{ config('const.sex.man') }}" @if( $user_info['user_sex'] == config('const.sex.man') ) selected @endif>男性</option>
                            <option value="{{ config('const.sex.woman') }}" @if( $user_info['user_sex'] == config('const.sex.woman') ) selected @endif>女性</option>
                        </select>
                    </div>
                </li>

                @if($user_info['user_is_senpai'] == config('const.staff_type.senpai'))

                    <li>
                        <h3 class="must">郵便番号</h3>
                        <div class="form_wrap shadow-glay w50 for-warning">
                            {{--<input type="text" value="{{ $user_info['user_mail'] }}" name="user_mail" placeholder="" class="zipcode" id="郵便番号" onKeyUp="$('#郵便番号').zip2addr({pref:'#都道府県',addr:'#市区町村'});" >--}}
                            <input type="text" value="{{ $user_info['user_mail'] }}" name="user_mail" placeholder="" class="zipcode" id="郵便番号" onKeyUp="AjaxZip3.zip2addr(this,'', '#都道府県','#市区町村');" >
                            <p class="warning"></p>
                        </div>
                    </li>
                @endif

                <li>
                    <h3 class="must">都道府県</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay for-warning">
                        <p class="warning"></p>
                        <select id="都道府県" name="user_area_id">
                            <option value="">ご希望の都道府県を選択してください</option>
                            @foreach( \App\Service\AreaService::getSecondAreaList() as $key => $value )
                                <option value="{{ $value['area_id'] }}" {{$value['area_id']  == $user_info['user_area_id'] ? "selected='selected'":''}}>{{ $value['area_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </li>

                @if($user_info['user_is_senpai'] == config('const.staff_type.senpai'))
                    <li>
                        <ul class="select_float_box half_box">
                            <li>
                                <h3 class="must">市区町村</h3>
                                <div class="form_wrap shadow-glay for-warning">
                                    <input type="text" value="{{ $user_info['user_county'] }}" id="市区町村" placeholder="例）大阪市西区江戸堀" name="user_county">
                                    <p class="warning"></p>
                                </div>
                            </li>
                            <li>
                                <h3 class="must">町番地</h3>
                                <div class="form_wrap shadow-glay for-warning">
                                    <input type="text" value="{{ $user_info['user_village'] }}" name="user_village" id="user_village" placeholder="例）1-2-3">
                                    <p class="warning"></p>
                                </div>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <h3>マンション名・部屋番号</h3>
                        <div class="form_wrap shadow-glay for-warning">
                            <input type="text" value="{{ $user_info['user_mansyonn'] }}" name="user_mansyonn" id="user_mansyonn" placeholder="例）センパイハイツ101">
                            <p class="warning"></p>
                        </div>
                    </li>

                @endif
            </ul>

            <!-- ※footer.phpにリンク設定の記述あり -->

        </section>

        {{ Form::close() }}

        <div class="white-bk">
            <div class="link-area pt0">
                <div class="orange_link"><a href="{{ route('using_rules') }}">利用規約</a></div>
                <div class="orange_link"><a href="{{ route('privacy_policy') }}">プライバシーポリシー</a></div>
            </div>


            <div class="agree_required">
                <div class="agree_check_area">
                    <label for="agree_btn" class="agree_btn"><input id="agree_btn" type="checkbox"><span class="label_inner">以上の内容に同意する</span></label>
                </div>
            </div>


            <div class="agree_btn_area btn_base shadow-glay mt20">
                <div class="btn_base btn_orange shadow">
                    <button class="btn-glay ajax_submit">更新する</button>
                </div>
            </div>
        </div><!-- /contents-->


        <!-- モーダル部分 *********************************************************** -->
        <input type="hidden" class="ajax-modal-syncer" data-target="#modal-user_update" id="modal_result">
        <div class="modal-wrap">
            <div id="modal-user_update" class="modal-content">

                <div class="modal_body completion">
                    <div class="modal_inner">

                        <h2 class="modal_ttl">
                            ユーザー情報の更新を<br>
                            完了しました
                        </h2>

                    </div>

                    <div class="button-area type_under">
                        <div class="btn_base btn_ok">
                            <a href="{{ route('user.myaccount.index') }}">OK</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- モーダル部分 / ここまで ************************************************* -->
    <footer>

        @include('user.layouts.fnavi')

    </footer>

@endsection

@section('page_js')
    <script src="{{ asset('assets/user/js/validate.js') }}"></script>
    <script src="{{ asset('assets/user/js/ajaxzip3.js') }}"></script>
    <script>
        $('#agree_btn').on('click', function () {
            var checked = $(".agree_btn_area").hasClass('_check');
            if ( checked ) {
                $(".btn-glay").prop("disabled", false);
            } else {
                $(".btn-glay").prop("disabled", true);
            }
        });

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

        $(document).on('keydown', '#form1', function (e) {
            if (e.keyCode == 13) { // press enter key
                e.preventDefault();
                ajaxSubmit($("#form1").get(0));
            }
        });

        $('.ajax_submit').click(function () {
            ajaxSubmit($("#form1").get(0));
        });

        function ajaxSubmit(objForm) {
            var postData = new FormData(objForm);
            postData.append("_token", "{{csrf_token()}}");

            $.ajax({
                type: "post",
                url: '{{ route('user.myaccount.set_user') }}',
                data: postData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (result) {
                    if ( result.result_code == 'success' ) {
                        showAjaxModal($('#modal_result'));
                    } else {
                        if ( result.errors.user_firstname != undefined ) {
                            addError($('#user_firstname'), result.errors.user_firstname);
                        }
                        if ( result.errors.user_lastname != undefined ) {
                            addError($('#user_lastname'), result.errors.user_lastname);
                        }
                        if ( result.errors.user_sei != undefined ) {
                            addError($('#user_sei'), result.errors.user_sei);
                        }
                        if ( result.errors.user_mei != undefined ) {
                            addError($('#user_mei'), result.errors.user_mei);
                        }
                        if ( result.errors.user_birthday != undefined ) {
                            addError($('#birthday'), result.errors.user_birthday);
                        }
                        if ( result.errors.user_sex != undefined ) {
                            addError($('#user_sex'), result.errors.user_sex);
                        }
                        if ( result.errors.user_mail != undefined ) {
                            addError($('#郵便番号'), result.errors.user_mail);
                        }
                        if ( result.errors.user_area_id != undefined ) {
                            addError($('#都道府県'), result.errors.user_area_id);
                        }
                        if ( result.errors.user_county != undefined ) {
                            addError($('#市区町村'), result.errors.user_county);
                        }
                        if ( result.errors.user_village != undefined ) {
                            addError($('#user_village'), result.errors.user_village);
                        }
                        if ( result.errors.user_mansyonn != undefined ) {
                            addError($('#user_mansyonn'), result.errors.user_mansyonn);
                        }
                    }
                },
            });
        }

        function reset_days() {
            var year = $('#date_year').val();
            var month = $('#date_month').val();
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            var str_html = '';
            for(var i=1; i<=last_day; i++){
                str_html += '<option value="' + i + '">' + i + '</option>'
            }
            $('#date_day').html(str_html);
        }

    </script>
@endsection
