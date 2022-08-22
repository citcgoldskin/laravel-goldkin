@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents" >

    <!--main_-->
    {{ Form::open(["route"=>"user.myaccount.update_request", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
    <input type="hidden" id="year" name="year" value="{{date('Y')}}">
    <input type="hidden" id="month" name="month" value="{{date('n')}}">
    <input type="hidden" name="schedule_id" value="{{$schedule_info['lrs_id']}}">
    <input type="hidden" id="people_num" name="people_num" value="{{ old('people_num') }}">
    <input type="hidden" id="no_change_schedule" name="no_change_schedule" value="0">
    <input type="hidden" id="schedule_validation" name="schedule_validation" value="0">
    <input type="hidden" id="amount_val" value="{{ $schedule_info['lrs_amount'] / ($schedule_info['lesson_request']['lr_man_num'] + $schedule_info['lesson_request']['lr_woman_num']) }}">
        <section>
            <div class="white_box mt30 plus-fukidashi"> <span class="choice_lesson">リクエスト中のレッスン！</span>
                <p class="lesson_ttl">{{ $schedule_info['lesson_request']['lesson']['lesson_title'] }}</p>
                <p class="fs-14 pb10">
                    {{ date('Y/n/j', strtotime($schedule_info['lrs_date'])) . ' ' . \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}
                </p>
                <ul class="choice_price">
                    {{--<li class="icon_taimen">{{ \App\Service\CommonService::getLessonMode($schedule_info['lesson_request']['lesson']['lesson_type']) }}</li>--}}
                    <li class="price"><em>{{ \App\Service\CommonService::showFormatNum(\App\Service\CommonService::getTotalPrice($schedule_info['lrs_amount'], $schedule_info['lrs_traffic_fee'], $schedule_info['coupon'])) }}</em>円<span></span></li>
                </ul>
            </div>
        </section>
        <section class="pb0">

            <div class="inner_box for-warning">
                <h3>参加人数</h3>
                <p class="warning"></p>
                <ul class="select_flex">
                    <li>
                        <div>男性</div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="lr_man_num" id="lr_man_num" class="people_num">
                                @for ($i = 0; $i <= 50; $i++)
                                    <option value="{{$i}}" @if ($schedule_info['lesson_request']['lr_man_num'] == $i) selected @endif >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>名</div>
                    </li>
                    <li>
                        <div>女性</div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="lr_woman_num" id="lr_woman_num" class="people_num">
                                @for ($i = 0; $i <= 50; $i++)
                                    <option value="{{$i}}" @if ($schedule_info['lesson_request']['lr_woman_num'] == $i) selected @endif >{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>名</div>
                    </li>
                </ul>
            </div>

            <div class="calendar-area">
                <h3>ご希望の日時を選択してください</h3>
                <div class="balloon balloon_blue mb">
                    <p> ※このセンパイの最低レッスン時間は{{ $min_hours }}分です。<br>
                        連続する{{ $min_hours }}分以上の枠を選択してください。<br>
                        ※ご希望枠がない場合は、トークルームで相談を行い、出勤スケジュールを変更してもらってください。</p>
                </div>
                <div class="white_box pt0 pb0 mb20">
                    <div class="kodawari_check check-box for-warning">
                        <div class="clex-box_01">
                            <input type="checkbox" value="0" id="no-change">
                            <label for="no-change" class="nobo" id="label_no_change">
                                <p>日時を変更しない</p>
                            </label>
                        </div>
                        <p class="warning"></p>
                    </div>
                </div>
                <div class="date-area hideandseek">
                    <ul>
                        <li><a onclick="prev_schedule();">前月</a></li>
                        <li>2021年1月</li>
                        <li><a onclick="next_schedule();">翌月</a></li>
                    </ul>
                    <div class="calendar-box">
                        <table>
                            <thead>
                                <tr class="first-row"></tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="calendar-box-02">
                            <div class="space-box"></div>
                            <div class="calendar-button">
                                <button type="button" class="button-01">朝<span>(〜9:00)</span></button>
                                <button type="button" class="button-02 f-weight">昼<span>(9:00~17:00)</span></button>
                                <button type="button" class="button-03">夜<span>(17:00〜)</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="inner_box hideandseek">
                <h3>変更前の予約内容</h3>
                <div class="white_box reserved_wrap icon_check">
                    <p class="icon_blue">
                        <input type="text" id="old_request_schedule" value="{{ \App\Service\CommonService::getYMDAndWeek($schedule_info['lrs_date']) . ' ' .
                         \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time'])}}">
                    </p>
                </div>
                <h3>変更後の予約</h3>
                <div class="white_box reserved_wrap icon_check" id="selected_schedule">
                    <p class="fc_red pl10 pt10 fs-13" hidden>変更内容に不備があります。</p>
                </div>
                <div class="balloon balloon_blue">
                    <p>※レッスンの変更は必ず1つずつ行ってください。<br>
                        ※連続する{{ $min_hours }}分以上の枠を1つだけ選択してください。</p>
                </div>
            </div>
            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box for-warning">
                    <p class="warning"></p>
                    <div class="lesson_place">
                        @if($schedule_info['lesson_request']['lr_pos_discuss'] == 1)
                            <p class="place_point">
                                {{ $schedule_info['lesson_request']['discuss_lesson_area'] }}
                            </p>
                            <p>
                                {{ $schedule_info['lesson_request']['lr_address'] }}
                            </p>
                        @else
                            <p class="place_point">
                                {{ implode('/', $schedule_info['lesson_request']['lesson']['lesson_area_names']) }}
                            </p>
                        @endif
                        {{--<p class="place_point">
                            {{ $schedule_info['lesson_request']['lesson']['lesson_map_pos'] . ' ' . $schedule_info['lesson_request']['lesson']['lesson_address_and_keyword'] }}
                        </p>--}}
                    </div>
                    @if($schedule_info['lesson_request']['lr_pos_discuss'] == 1)
                        <div class="balloon balloon_blue">
                            <p>{{ $schedule_info['lesson_request']['lr_address_detail'] }}</p>
                        </div>
                    @else
                        <div class="balloon balloon_blue">
                            <p>{{ $schedule_info['lesson_request']['lesson']['lesson_pos_detail'] }}</p>
                        </div>
                    @endif
                    <div class="kodawari_check check-box">
                        <div class="clex-box_01">
                            <input type="radio" name="meeting_pos" value="{{ config('const.change_position_type.no_change') }}" id="c1" onclick="toggleArea();">
                            <label for="c1" class="nobo">
                                <p>待ち合わせ場所を変更しない</p>
                            </label>
                        </div>
                        <div class="clex-box_01">
                            <input type="radio" name="meeting_pos" value="{{ config('const.change_position_type.lesson_position') }}" id="c2" onclick="toggleArea();">
                            <label for="c2" class="nobo">
                                <p>センパイ指定の場所にする</p>
                            </label>
                        </div>
                        <div class="clex-box_01">
                            <input type="radio" name="meeting_pos" value="{{ config('const.change_position_type.set_position') }}" id="c3" onclick="toggleArea();">
                            <label for="c3" class="nobo">
                                <p>レッスン場所を指定する</p>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="inner_box text-p-04 area_info">
                <h3>変更可能な地域</h3>
                <ul class="soudan_ok_area">
                    <li>大阪市北区</li>
                    <li>中央区</li>
                    <li>西区</li>
                    <li>住之江区</li>
                </ul>
            </div>
            <div class="inner_box area_info">
                <h3>エリアを選択</h3>
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay for-warning">
                    <p class="warning"></p>
                    <select name="area_id" id="area_id">
                        <option value="0">選択してください</option>
                        <option value="大阪市　すべて">大阪市　すべて</option>
                        <option value="大阪市　都島区">大阪市　都島区</option>
                        <option value="大阪市 福島区">大阪市 福島区</option>
                        <option value="大阪市">大阪市</option>
                        <option value="大阪市">大阪市</option>
                        <option value="大阪市">大阪市</option>
                    </select>
                </div>
            </div>
            <div class="inner_box area_info">
                <h3>続きの住所を入力</h3>
                <div class="input-text shadow-glay">
                    <input type="text" name="address" size="50" maxlength="50">
                </div>
            </div>
            <div class="inner_box area_info">
                <h3>待ち合わせ場所の詳細<small>（200文字まで）<i>任意</i></small></h3>
                <div class="input-text2">
                    <textarea placeholder="待ち合わせ場所の詳細説明があれば入力してください。" cols="50" rows="10"  class="shadow-glay" name="pos_detail"></textarea>
                </div>
            </div>
            <div class="inner_box no-pb">
                <h3>出張交通費を選択</h3>
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                    <select name="traffic_fee" id="traffic_fee">
                        <option value="">選択してください</option>
                        <option value="0">0円</option>
                        <option value="500">500円</option>
                        <option value="1000">1000円</option>
                        <option value="1500">1500円</option>
                        <option value="2000">2000円</option>
                    </select>
                </div>
            </div>
            <div class="balloon balloon_blue mb20">
                <p>※金額についてはトークルームで事前に相談してください。</p>
            </div>
            <div class="inner_box pb0">
                <h3>変更が承認されなかった場合</h3>
                <p class="fs-13">センパイが変更に同意しなかった場合や
                    {{ \App\Service\CommonService::getYMD($schedule_info['lesson_request']['lr_until_confirm']) . ' ' . '00:00' }}
                    までにセンパイが回答しなかった場合</p>
            </div>
            <div class="white_box pt0 pb0 mb20">
                <div class="kodawari_check check-box for-warning">
                    <p class="warning"></p>
                    <div class="clex-box_01">
                        <input type="radio" name="no_confirm" value="{{ config('const.no_confirm.old_request') }}" id="d1">
                        <label for="d1" class="nobo"><p>元のレッスン内容で実施する</p></label>
                    </div>
                    <div class="clex-box_01">
                        <input type="radio" name="no_confirm" value="{{ config('const.no_confirm.cancel_request') }}" id="d2">
                        <label for="d2" class="nobo"><p>レッスンをキャンセルする</p></label>
                    </div>
                </div>
            </div>
            <div class="balloon balloon_blue mb20">
                <p>※変更申請時刻で計算されたキャンセル料が発生します。</p>
            </div>

            <div class="inner_box">
                <h3>キャンセルポリシー</h3>
                <p class="fs-13 pb20">変更リクエストの申請時刻によりキャンセル料は変動します。</p>
                <ul class="list_box cancel_policy" id="cancel_policy">

                    <li>
                        <div id="cancel_policy_1">
                            <h3>(i) {{ \App\Service\CommonService::getYMD(\App\Service\CommonService::getAddDate($schedule_info['lrs_date'], -2)) }} 23時59分まで</h3>
                        </div>
                        <div>
                            <p>ご利用前日のキャンセル</p>
                            <p class="space">
                                <em>{{ \App\Service\SettingService::getSetting('cancel_before_1_percent', 'int') }}%</em>
                            </p>
                        </div>
                        <div>
                            <p>ご利用当日のキャンセル</p>
                            <p class="space">
                                <em>{{ \App\Service\SettingService::getSetting('cancel_before_0_percent', 'int') }}%</em><br>＋交通費

                            </p>
                        </div>
                        <p class="ls1 pt10 pb10"><small>※キャンセル料は変更後のレッスン日を基準に計算されます。</small></p>
                    </li>

                    <li>
                        <div id="cancel_policy_2">
                            <h3>(i) {{ \App\Service\CommonService::getYMD(\App\Service\CommonService::getAddDate($schedule_info['lrs_date'], -1)) }} 0時00分まで</h3>
                        </div>
                        <div>
                            <p id="cancel_policy_21">{{ \App\Service\CommonService::getYMD(\App\Service\CommonService::getAddDate($schedule_info['lrs_date'], -1)) }}のキャンセル</p>
                            <p class="space">
                                <em>{{ \App\Service\SettingService::getSetting('cancel_before_1_percent', 'int') }}%</em>
                            </p>
                        </div>
                        <div>
                            <p id="cancel_policy_22">{{ \App\Service\CommonService::getYMD($schedule_info['lrs_date']) }}</p>
                            <p class="space">
                                <em>{{ \App\Service\SettingService::getSetting('cancel_before_0_percent', 'int') }}%</em><br>＋交通費

                            </p>
                        </div>
                        <p class="ls1 pt10 pb10"><small>※キャンセル料は変更前のレッスン日を基準に計算されます。</small></p>
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


            <div class="inner_box pb0">
                <h3>支払い方法</h3>
                <div class="form_wrap icon_form type_edit shadow-glay">
                    <button type="button" onClick="location.href='{{ route('user.lesson.select_pay_method') }}'" class="form_btn">
                        <div class="payment_box">
                            <div><img src="{{ asset('assets/user/img/icon_creca.png') }}" alt=""></div>
                            <div>●●●● ●●●● ●●●● 1811</div>
                        </div>
                    </button>
                </div>

                <div class="card_ok">
                    <p>ご利用可能なクレジットカード一覧</p>
                    <div class="white_box">
                        <p>VISA / Mastercard / JCB / Discover / Diners Club / American Express / China UnionPay / Square Gift Card</p>
                    </div>
                </div>

            </div>

            <div class="inner_box">
                <h3>お支払い金額</h3>
                <div class="white_box" id="payment">
                    <ul class="list_box">
                        <li class="">
                            <div class="al-top">
                                <p>レッスン日時</p>
                                <p class="mark" id="date_time">{{ \App\Service\CommonService::getYMD($schedule_info['lrs_date']) }}<br>
                                    {{ \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}
                                    ({{ \App\Service\CommonService::getIntervalMinute($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}分)</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p id="payment_count">レッスン料({{ $schedule_info['lesson_request']['lr_man_num'] + $schedule_info['lesson_request']['lr_woman_num'] }}名)</p>
                                <p class="price_mark" id="amount"><em>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount']) }}</em></p>
                            </div>
                            <div>

                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-service">サービス料</a>
                                </p>

                                <p class="price_mark" id="show_service_fee"><em>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_service_fee']) }}</em></p>

                            </div>

                            <div>
                                <p>出張交通費</p>
                                <p class="price_mark" id="show_traffic_fee"><em>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_traffic_fee']) }}</em></p>
                            </div>
                        </li>

                        <li>
                            <div class="total_price_with">
                                <p>合計（税込）</p>
                                <p class="price_mark" id="total_price"><em>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount'] + $schedule_info['lrs_service_fee'] + $schedule_info['lrs_traffic_fee']) }}</em></p>
                            </div>
                    </ul>
                </div>
            </div>
        </section>
        <section id="button-area">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <a class="submit_button">変更リクエストを確定する</a>
                </div>
            </div>
        </section>
    {{ Form::close() }}
</div>
<!-- /contents -->

<!-- モーダル部分 *********************************************************** -->
<div class="modal-wrap completion_wrap ok">
    <div id="modal-request_henkou" class="modal-content ok">
        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl"> リクエストを<br>
                    変更しました </h2>
                <div class="modal_txt">
                    <p> センパイからの返信を<br>
                        お待ちください。 </p>
                </div>
            </div>
        </div>
        <div class="button-area type_under">
            <div class="btn_base btn_ok"><a href="{{ route('user.talkroom.list') }}">OK</a></div>
        </div>
    </div>
    <!-- /modal-content -->

</div>
<!-- モーダル部分 / ここまで ************************************************* -->

@include ('user.layouts.modal')
<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection

@section('page_js')
    <script src="{{ asset('assets/user/js/validate.js') }}"></script>

    <script>
        var valid_schedule = true;
        var day_count = 0;
        var new_request_schedule = [];

        $(document).ready(function(){
            var year = $('#year').val();
            var month = $('#month').val();
            show_schedule_list(year, month);

            getPeopleNum();
        });
        function prev_schedule(){
            new_request_schedule = [];
            showSelectedSchedule();

            getCurretMonth('prev');
            var year = $('#year').val();
            var month = $('#month').val();
            show_schedule_list(year, month);
        }

        function next_schedule(){
            new_request_schedule = [];
            showSelectedSchedule();

            getCurretMonth('next');
            var year = $('#year').val();
            var month = $('#month').val();
            show_schedule_list(year, month);
        }

        function getCurretMonth(key){
            var year = $('#year').val();
            var month = $('#month').val();
            if(isNaN(parseInt(year)) || isNaN(parseInt(month)) || parseInt(year) <= 0 || parseInt(month) <= 0 ){
                console.log('wrong input date: year/month : ' + year + '/' + month);
                return;
            }

            if(key == 'prev'){
                if(parseInt(month) > 1){
                    month = parseInt(month) - 1;
                }else{
                    year = parseInt(year) - 1;
                    month = 12;
                }
            }

            if(key == 'next'){
                if(parseInt(month) < 12){
                    month = parseInt(month) + 1;
                }else{
                    year = parseInt(year) + 1;
                    month = 1;
                }
            }

            if(isNaN(parseInt(year)) || isNaN(parseInt(month)) || parseInt(year) <= 0 || parseInt(month) <= 0 ){
                console.log('wrong output date: year/month : ' + year + '/' + month);
                return;
            }

            $('#year').val(year);
            $('#month').val(month);
        }

        function show_schedule_list(year, month){
            if(month.charAt(0) == '0') month = month.replace('0',''); // 05 -> 5

            var Ym_html = year + '年' + month + '月';
            $('.calendar-area .date-area ul li:nth-child(2)').html(Ym_html);
            //
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            day_count = last_day;
            var day_html = '<th class="space-box fixed_02"></th>';
            for(var i=1; i <= last_day; i++){
                d.setDate(i);
                var k = d.getDay();
                var red_day = '';
                if(k == 6) red_day = 'saturday';
                if(k == 0) red_day = 'sunday';
                day_html += '<th class="' + red_day + ' fixed"><p class="day">' + WEEK_DAYS[k] + '</p><p class="week">' + i + '</p></th>';
            }
            $('.calendar-box table thead tr.first-row').html(day_html);
            //
            $.ajax({
                type: "post",
                url: '{{ route('user.syutupinn.get_schedule') }}',
                data: {senpai_id:'{{ $schedule_info['lesson_request']['lesson']['lesson_senpai_id'] }}',year: year, month: month, _token:'{{csrf_token()}}'},
                dataType: 'json',
                success: function (result) {
                    var obj = {};
                    if(result.state) obj = result.list;
                    set_schedule(obj,last_day);
                }
            });
        }

        function set_schedule(data, last_day) {
            var h, m, w;
            var time_html = '';
            var year = $('#year').val();
            var month = $('#month').val();
            var d = new Date();
            d.setFullYear(year);
            d.setMonth(month - 1);
            var week_day = 0;
            $('.calendar-box table tbody').html('');
            for(var t=0; t<24; t=t+0.25){
                if(t < 9) w = 'morning';
                else if(t >= 17) w = 'night';
                else w = 'noon';
                h = Math.floor(t).toString();
                if(Math.floor(t) < 10) h = '0' + Math.floor(t).toString();
                if(Number.isInteger(t)){
                    time_html = '<tr class="' + w + '"><td class="fixed"><strong>' + h + ':00</strong></td>';
                }else{
                    m = ((t - Math.floor(t)) * 60).toString();
                    time_html = '<tr class="' + w + '"><td class="fixed">' + h + ':' + m + '</td>';
                }
                if(Object.keys(data).length > 0){
                    for(var i=1; i <= last_day; i++){
                        var state = 0;
                        var k = '' + t;
                        if(data[k] != undefined && data[k][i] != undefined){
                            if(typeof data[k][i] == "object"){
                                state = parseInt(data[k][i]['val']);
                            }else{
                                state = parseInt(data[k][i]);
                            }

                            if(isNaN(state)) state = 0;
                        }

                        if ( state >= 1 ) {
                            time_html += '<td class="ok-icon">' +
                                '<input type="checkbox" name="reserve" id="reserve_'
                                + k + '_' + i + '_a"><label name="reserve_label" for="reserve_' + k + '_' + i + '_a" time="' + k + '" day="' + i + '" value="0">●</label>' +
                                '</td>';
                        } else {
                            time_html += '<td class="bg-color-01">×</td>';
                        }
                    }
                }else{
                    for(var i=1; i <= last_day; i++){
                        time_html += '<td class="bg-color-01">×</td>';
                    }
                }

                time_html += '</tr>';
                $('.calendar-box table tbody').append(time_html);
            }

            $('.calendar-button button').each(function () {
                var term = '';
                if($(this).hasClass('f-weight')) term = $(this).attr('term');
                switch (term) {
                    case 'morning':
                        $('.morning').show();
                        $('.none').hide();
                        $('.night').hide();
                        break;
                    case 'none':
                        $('.morning').hide();
                        $('.none').show();
                        $('.night').hide();
                        break;
                    case 'night':
                        $('.morning').hide();
                        $('.none').hide();
                        $('.night').show();
                        break;
                    default:
                        break;
                }
            });

            $('label[name="reserve_label"]').click(function(){
                var date = $(this).attr('day');
                var time = $(this).attr('time');

                if ($(this).val() == 0) {
                    $(this).val(1);

                    // input schedule info into new_request_schedule
                    if ( new_request_schedule[date] == undefined ) {
                        var schedule_times = [time];
                        new_request_schedule[date] = schedule_times;
                    } else {
                        new_request_schedule[date].push(time);
                    }
                }else{
                    $(this).val(0);

                    // remove schedule info from new_request_schedule
                    if ( new_request_schedule[date] != undefined ) {
                        if ( new_request_schedule[date].length > 0 ) { // remove info of a time
                            new_request_schedule[date] = new_request_schedule[date].filter(function (ele) {
                                return ele != time;
                            });
                        }

                        // remove info of a day
                        if ( new_request_schedule[date].length < 1 ) {
                            var new_request_schedule_tmp = [];
                            for (var key in new_request_schedule) {
                                if ( key == date)
                                    continue;

                                new_request_schedule_tmp[key] = new_request_schedule[key];
                            }

                            new_request_schedule = new_request_schedule_tmp;
                        }
                    }
                }

                if ( new_request_schedule[date] != undefined ) {
                    // sort by time order
                    new_request_schedule[date].sort(function (a, b) {
                        return parseFloat(a) - parseFloat(b);
                    });
                }

                showSelectedSchedule();
                showPriceInfo();
            });
        }

        function showSelectedSchedule() {
            var schedule_list = getSchedule();
            var schedule_html = '';

            valid_schedule = true;

            if ( schedule_list.length < 1 ) {
                valid_schedule = false;
            }

            if ( schedule_list.length > 1 ) {
                var warn_html = '<p class="fc_red pl10 pt10 fs-13">変更内容に不備があります。</p>'
                schedule_html = warn_html + schedule_html;

                valid_schedule = false;
            }

            for ( var k in schedule_list ) {
                var first_time = timeIdToStr(schedule_list[k].first_time * 4);
                var last_time = timeIdToStr(schedule_list[k].last_time * 4);
                var date = schedule_list[k].date;
                var time = getReserveTime(date, first_time, last_time);

                if ( validateSchedule(schedule_list[k]) ) {
                    schedule_html += getOkScheduleHtml(time);
                } else {
                    schedule_html += getNoScheduleHtml(time);

                    valid_schedule = false;
                }
            }

            if( valid_schedule ) {
                $("#schedule_validation").val(1);
            } else {
                $("#schedule_validation").val(0);
            }

            $('#selected_schedule').html(schedule_html);
        }

        function getSchedule() {
            var schedule_list = [];
            for ( var key in new_request_schedule ) {
                var first_time = 0, last_time = 0;
                for ( var k in new_request_schedule[key] ) {
                    if ( first_time == 0 ) {
                        first_time = parseFloat(new_request_schedule[key][k]);
                        last_time = first_time + 0.25;
                    } else if ( last_time == parseFloat(new_request_schedule[key][k]) ) {
                        last_time += 0.25;
                    } else {
                        schedule_list.push({
                            'date' : key,
                            'first_time' : first_time,
                            'last_time' : last_time
                        });

                        first_time = parseFloat(new_request_schedule[key][k]);
                        last_time = first_time + 0.25;
                    }
                }

                schedule_list.push({
                    'date' : key,
                    'first_time' : first_time,
                    'last_time' : last_time
                });
            }

            return schedule_list;
        }

        function showPriceInfo() {
            var year = $('#year').val();
            var month = $('#month').val();
            var schedule_list = getSchedule();

            if( $('#no_change_schedule').val() == 0 && $("#schedule_validation").val() == 1 ) {
                for ( var k in schedule_list ) {
                    showDateTime(year, month, schedule_list[k].date, schedule_list[k].first_time * 4, schedule_list[k].last_time * 4);
                    setAmountUnit(schedule_list[k].first_time * 4, schedule_list[k].last_time * 4);
                }
            } else {
                showOldDateTime();
                setOldAmountUnit();
            }

            showPrice();
        }

        function showOldDateTime() {
            var html = '{{ \App\Service\CommonService::getYMD($schedule_info['lrs_date']) }}<br>' +
            '{{ \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}' +
            '({{ \App\Service\CommonService::getIntervalMinute($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}分)';

            $('#date_time').html(html);
        }

        function setOldAmountUnit() {
            var amount_unit = '{{ $schedule_info['lrs_amount'] / ($schedule_info['lesson_request']['lr_man_num'] + $schedule_info['lesson_request']['lr_woman_num']) }}';
            $('#amount_val').val(amount_unit);
        }

        function showDateTime(year, month, day, stimeId, etimeId) {
            var str_schedule_date = getYMD(new Date(year, month - 1, day));

            var html = str_schedule_date + '<br>' + timeIdToStr(stimeId) + '~' + timeIdToStr(etimeId) + '(' + (etimeId - stimeId) * 15 + '分)';
            $('#date_time').html(html);
        }

        function setAmountUnit(stimeId, etimeId) {
            var min_fee = '{{ $schedule_info['lesson_request']['lesson']['lesson_30min_fees'] }}';
            var amount_unit = min_fee * (etimeId - stimeId) / 2;

            $('#amount_val').val(amount_unit);
        }

        function showPrice() {
            var service_percent = '{{ \App\Service\CommonService::getServicePercent() }}';

            var html = 'レッスン料(' + ($('#people_num').val()) + '名)';
            $('#payment_count').html(html);

            html = '<em>' + showFormatNum($('#amount_val').val() * $('#people_num').val()) + '</em>';
            $('#amount').html(html);

            html = '<em>' + showFormatNum(Math.round($('#amount_val').val() * $('#people_num').val() * service_percent / 100 )) + '</em>';
            $('#show_service_fee').html(html);

            html = '<em>' + showFormatNum($('#traffic_fee').val() == "" ? 0 : $('#traffic_fee').val()) + '</em>';
            $('#show_traffic_fee').html(html);

            html = '<em>' + showFormatNum($('#amount_val').val() * $('#people_num').val() +
                Math.round($('#amount_val').val() * $('#people_num').val() * service_percent / 100 ) +
                $('#traffic_fee').val() * 1) + '</em>';
            $('#total_price').html(html);
        }

        function validateSchedule(schedule) {
            var interval = schedule.last_time - schedule.first_time;
            if ( (interval * 60) < parseFloat('{{ $schedule_info['lesson_request']['lesson']['lesson_min_hours'] }}') ) {
                return false;
            }

            return true;
        }

        function timeIdToStr(id) {
            var hour, minute;
            hour = Math.floor(id / 4);
            minute = (id - 4 * hour) * 15;
            return strPad(hour) + ':' + strPad(minute);
        }

        function strPad(number) {
            return number < 10 ? '0' + number : number;
        }

        function getReserveTime(day, from_time, to_time) {
            var year = $("#year").val();
            var month = $("#month").val();
            var date = new Date(year, month - 1, 1);
            var first_date_id = date.getDay();
            var date_id = (Number(first_date_id) + Number(day) - 1) % 7;

            return year + '年' + month + '月' + day + '日 (' + WEEK_DAYS[date_id] + ')  ' + from_time + '~' + to_time;
        }

        function getOkScheduleHtml(data) {
            return '<p class="icon_blue"><input type="text" name="sel_schedule_html" value="' + data + '"></p>'
        }

        function getNoScheduleHtml(data) {
            return '<p class="icon_red"><input type="text" name="sel_schedule_html" value="' + data + '"></p>'
        }

        function toggleArea() {
            var checked = $('input[name=meeting_pos]:checked').val();
            if ( checked == 2 ) {
                $('.area_info').show();
            } else {
                $('.area_info').hide();
            }
        }

        function getPeopleNum() {
            var man = $('#lr_man_num').val();
            var woman = $('#lr_woman_num').val();

            $('#people_num').val(man * 1 + woman * 1)
        }

        $('.people_num').change(function () {
            getPeopleNum();
            showPrice();
        });

        function validation() {
            var validation = true;
            if ( $('#people_num').val() < 1 ) {
                addError($('#people_num'), "参加人数を入力してください。")
                validation = false;
            }

            if ( $('input[id=no-change]:checked').val() == undefined && $('#schedule_validation').val() < 1 ) {
                validation = false;
            }

            if ( $('input[name=meeting_pos]:checked').val() == undefined ) {
                addError($('input[name=meeting_pos]'), "項目を選択してください。")
                validation = false;
            }

            if ( $('input[name=meeting_pos]:checked').val() == 2 && $('#area_id').val() == 0 ) {
                addError($('#area_id'), "select area");
                validation = false;
            }

            if ( $('input[name=no_confirm]:checked').val() == undefined ) {
                addError($('input[name=no_confirm]'), "項目を選択してください。")
                validation = false;
            }

            return validation;
        }

        $('#traffic_fee').change(function () {
            showPrice();
        });

        $('#no-change').change(function () {
            if ( $('input[id=no-change]:checked').val() == undefined ) {
                $('#no_change_schedule').val(0);
            } else  {
                $('#no_change_schedule').val(1);
            }
        });

        $('.submit_button').click(function () {
            if ( !validation() ) {
                return;
            }

            $("#form1").get(0).submit();
        });

    </script>
@endsection
