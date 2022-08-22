@extends('user.layouts.app')

@section('title', '予約リクエスト')

@section('$page_id', 'home')

@section('content')
    @include('user.layouts.header_under')

    @php
        use App\Service\CommonService;
        $min_minutes = (isset($lesson['lesson_min_hours']) && !empty($lesson['lesson_min_hours'])) ? $lesson['lesson_min_hours'] : 0;
        $date_arr = array(0 => "日", 1 => "月", 2 => "火", 3 => "水", 4 => "木", 5 => "金", 6 => "土");
    @endphp

    <div id="contents">
        <!--main_-->
        {{ Form::open(["route"=>["user.lesson.add_reserve_request"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" name="cur_ym" id="cur_ym" value="{{$time['current']}}">
        <input type="hidden" name="prev_ym" id="prev_ym" value="{{$time['previous']}}">
        <input type="hidden" name="next_ym" id="next_ym" value="{{$time['next']}}">
        <input type="hidden" name="ym_label" id="ym_label" value="{{$time['current']}}">
        <input type="hidden" name="year" id="year" value="{{$time['cur_year']}}">
        <input type="hidden" name="month" id="month" value="{{$time['cur_month']}}">
        <input type="hidden" name="date" id="date" value="{{$time['first_date']}}">
        <input type="hidden" name="day_count" id="day_count" value="{{$time['day_count']}}">
        <input type="hidden" name="min_minute" id="min_minute" value="{{$min_minutes}}">
        <input type="hidden" name="30min_fees" id="30min_fees" value="{{$lesson['lesson_30min_fees']}}">
        <section class="tab_area hide">
            <h3>ご希望のレッスン形式を選択してください</h3>

            <div class="switch_tab">
                <div class="radio-01">
                    <input type="radio" name="hope_type" id="off-line" value="0" checked="checked">
                    <label class="ok" for="off-line" id="off-label">対面希望</label>
                </div>
                <div class="radio-02">
                    <input type="radio" name="hope_type" id="on-line" value="1">
                    <label class="ok" for="on-line" id="on-label">オンライン希望</label>
                </div>
            </div>
        </section>

        <section>
            <div class="white_box mt30 plus-fukidashi">
                <input type="hidden" name="lesson_id"
                       @if(isset($lesson['lesson_id']) && !empty($lesson['lesson_id']))
                            value="{{$lesson['lesson_id']}}"
                        @endif>
                <span class="choice_lesson">選択中のレッスン！</span>
                <p class="lesson_ttl">
                    @if(isset($lesson['lesson_title']) && !empty($lesson['lesson_title']))
                        {{$lesson['lesson_title']}}
                    @endif
                </p>
                <ul class="choice_price">
                    {{--<li class="icon_taimen">
                        {{CommonService::getLessonMode($lesson['lesson_type'])}}
                    </li>--}}
                    <li class="price">
                        <em>
                            @if(isset($lesson['lesson_30min_fees']) && !empty($lesson['lesson_30min_fees']))
                                {{CommonService::showFormatNum($lesson['lesson_30min_fees'])}}
                            @endif
                        </em>円
                        <span> / <em>30</em>分〜</span>
                    </li>
                </ul>
            </div>
        </section>

        <section>

            <div class="inner_box {{ $lesson->lesson_cond_9 ? '' : 'hide' }}">
                <h3>参加人数</h3>
                <input type="hidden" name="member_flag" id="member_flag" value="1">
                <ul class="select_flex">
                    <li>
                        <div>男性</div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="man_num" class="member" id="man_num">
                                @for ($i = 0; $i <= 50; $i++)
                                    <option value="{{$i}}" {{ old('man_num', is_object(Auth::user()) && Auth::user()->user_sex == config('const.sex.man') ? 1 : (is_object(Auth::user()) && Auth::user()->user_sex != config('const.sex.man') && Auth::user()->user_sex != config('const.sex.woman') ? 1 : '') ) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>名</div>
                    </li>
                    <li>
                        <div>女性</div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="woman_num" class="member" id="woman_num">
                                @for ($i = 0; $i <= 50; $i++)
                                    <option value="{{$i}}" {{ old('woman_num', is_object(Auth::user()) && Auth::user()->user_sex == config('const.sex.woman') ? 1 : '' ) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div>名</div>
                    </li>
                    <li>
                        @error('member_flag')
                        <p class="error_text"><strong>{{ $message }}</strong></p>
                        @enderror
                    </li>
                </ul>
                <div class="balloon balloon_blue">
                    <p>※リクエストの入力者は必ず参加してください</p>
                </div>
            </div>

            <div class="calendar-area new-calendar-area">
                <h3>ご希望の日時を選択してください</h3>
                <div class="balloon balloon_blue mb">
                    <p>※複数回のレッスンを同時に予約できます。<br>
                        ※このセンパイの最低レッスン時間は{{$min_minutes}}分です。<br>
                        連続する{{$min_minutes}}分以上の枠を選択してください。
                    </p>
                </div>
                <div class="date-area">
                    <ul>
                        <li>
                            <a onclick="prev_schedule();">前月</a>
                        </li>
                        <li>{{$time['current_label']}}</li>
                        <li>
                            <a onclick="next_schedule();">翌月</a>
                        </li>
                    </ul>
                    <div class="calendar-box">
                        <table>
                            <thead>
                                <tr class="first-row"></tr>
                            </thead>
                            <tbody>
                            </tbody>
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
                @error('invalid_date')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
                @error('reserve_flag')
                <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
            </div>
        </section>

        <section>
            <div class="inner_box">
                <input type="hidden" name="reserve_count" id="reserve_count">
                <input type="hidden" name="no_reserve_count" id="no_reserve_count">
                <input type="hidden" name="reserve_flag" id="reserve_flag">
                <h3 id="ok_title" style="display: none">リクエストする予約</h3>
                <div class="white_box reserved_wrap icon_check" id="reserve_ok" style="display: none">
                </div>
                <h3 id="no_title" style="display: none">内容に不備のある予約</h3>
                <div class="white_box reserved_wrap icon_check" id="reserve_no" style="display: none">
                </div>
                <div class="balloon balloon_blue" id="reserve_no_detail" style="display: none">
                    <p>この先輩のレッスンは{{$min_minutes}}以上の枠で予約してください。変更されない場合は予約内容に反映されません。</p>
                </div>
            </div>

            <div class="inner_box pb0">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="lesson_place">
                        <p class="place_point">{{ implode('/', $lesson->lesson_area_names) }}</p>
                    </div>
                    @if(isset($lesson['lesson_pos_detail']) && !empty($lesson['lesson_pos_detail']))
                        <div class="balloon balloon_blue">
                            <p>{{$lesson['lesson_pos_detail']}}</p>
                        </div>
                    @endif

                    @if(isset($lesson['lesson_able_discuss_pos']) && $lesson['lesson_able_discuss_pos'])
                        <div class="kodawari_check check-box">
                            <div class="clex-box_01">
                                <input type="hidden" name="target_reserve" value="{{ old('target_reserve', '') == 1 ? 1 : 0 }}" id="target_reserve">
                                <input type="checkbox" name="c1" value="{{ old('target_reserve', '') == 1 ? 1 : 0 }}" id="c1" {{ old('target_reserve', '') == 1 ? 'checked' : '' }}>
                                <label for="c1" class="nobo" id="target_reserve_label"><p>指定地で予約する</p></label>
                            </div>
                            <div class="clex-box_01">
                                <input type="hidden" name="pos_discuss" value="{{ old('pos_discuss', '') == 1 ? 1 : 0 }}" id="pos_discuss">
                                <input type="checkbox" name="c2" value="{{ old('pos_discuss', '') == 1 ? 1 : 0 }}" id="c2" class="check-trigger" {{ old('pos_discuss', '') == 1 ? 'checked' : '' }}>
                                <label for="c2" class="nobo" id="pos_discuss_label"><p>レッスン場所を相談する</p></label>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="hide-area pt30">

                <div class="inner_box text-p-04">
                    <h3>相談可能な地域</h3>
                    @if($lesson->lesson_discuss_area_names && count($lesson->lesson_discuss_area_names) > 0)
                        <ul class="soudan_ok_area">
                            @foreach($lesson->lesson_discuss_area_names as $val)
                                <li>{{ $val }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="inner_box">
                    <h3>エリアを選択</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="area_id">
                            <option value="">選択してください</option>
                            @if($lesson->lesson_discuss_area_names && count($lesson->lesson_discuss_area_names) > 0)
                                @foreach($lesson->lesson_discuss_area as $lesson_area)
                                    @if($lesson_area->position)
                                        @php
                                            $position = json_decode($lesson_area->position);
                                            $area_content = $position->area_name;
                                        @endphp
                                        <option value="{{ $lesson_area->id }}" {{ old('area_id') == $lesson_area->id ? 'selected' : '' }}>{{ $area_content }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    @error('area_id')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                </div>

                <div class="inner_box">
                    <h3>続きの住所を入力</h3>
                    <div class="input-text shadow-glay">
                        <input type="text" name="address" size="50" maxlength="50" value="{{ old('address', '') }}">
                    </div>
                    @error('address')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                </div>

                <div class="inner_box no-pb">
                    <h3>待ち合わせ場所の詳細
                        <small>（200文字まで）<i>任意</i></small>
                    </h3>
                    <div class="input-text2">
                        <textarea placeholder="待ち合わせ場所の詳細説明があれば入力してください。" cols="50" rows="10" class="shadow-glay"
                                  name="address_detail">{{ old('address_detail', '') }}</textarea>
                    </div>
                    @error('address_detail')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                </div>
                <div class="balloon balloon_blue">
                    <p>指定するレッスン場所によっては出張交通費をお願いされる場合があります。</p>
                </div>

            </div>

            <p class="modal-link modal-link_blue">
                <a class="modal-syncer button-link" data-target="modal-business-trip">出張交通費とは？</a>
            </p>

            <div class="inner_box">
                <h3>リクエストの承認期限</h3>
                <div class="form_wrap icon_form type_arrow_right shadow-glay">
                    <input type="date" class="form_btn approval" value="{{ old('until_confirm', date('Y-m-d')) }}" name="until_confirm">
                </div>
                @error('until_confirm')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
            </div>


        </section>
        <section id="button-area">

            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <button type="submit">予約リクエストを送信</button>

                </div>
            </div>
        </section>

        {{ Form::close() }}

    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap completion_wrap">

        <div id="modal-request_henkou" class="modal-content">
            <div class="modal_body completion">
                <div class="modal_inner">

                    <h2 class="modal_ttl">
                        リクエストを<br>
                        変更しました
                    </h2>

                    <div class="modal_txt">
                        <p>
                            センパイからの返信を<br>
                            お待ちください。
                        </p>
                    </div>
                </div>
            </div>


            <div class="button-area type_under">
                <div class="btn_base btn_ok"><a id="modal-close">OK</a></div>
            </div>

        </div><!-- /modal-content -->

    </div>
    <!-- モーダル部分 / ここまで ************************************************* -->

    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_css')
    <style>
        .calendar-area .date-area ul li:nth-child(1) {
            color: #FB7122;
        }
        .calendar-area .date-area ul li:nth-child(1):before {

        }
    </style>
@endsection

@section('page_js')
    <script type="text/javascript">
        $(document).ready(function () {
            if($('[id=c2]').prop('checked')){
                $('.hide-area').show();
            } else  {
                $('.hide-area').hide();
            }

            var ym = $('#cur_ym').val();
            show_schedule_list(ym);
        });

        function show_schedule_list(ym){
            $.ajax({
                type: "post",
                url: '{{ route('user.lesson.get_schedule') }}',
                data: {lesson_id:"{{$lesson['lesson_id']}}" ,ym: ym, _token:'{{csrf_token()}}'},
                dataType: 'json',
                success: function (result) {
                    $('#cur_ym').val(result.time.current);
                    $('#prev_ym').val(result.time.previous);
                    $('#next_ym').val(result.time.next);
                    $('#ym_label').val(result.time.current_label);
                    $('#year').val(result.time.cur_year);
                    $('#month').val(result.time.cur_month);
                    $('#date').val(result.time.first_date);
                    $('#day_count').val(result.time.day_count);

                    $('.calendar-area .date-area ul li:nth-child(2)').html(result.time.current_label);

                    var week_html = '<th class="space-box fixed_02"></th>';
                    for(var key in result.time.month)
                    {
                        var week_id = result.time.month[key];
                        var red_day = '';
                        if(week_id == 6) red_day = 'saturday';
                        if(week_id == 0) red_day = 'sunday';
                        week_html += '<th class="' + red_day + ' fixed"><p class="day">' + WEEK_DAYS[week_id] + '</p><p class="week">' + key + '</p></th>';
                    }
                    $('.calendar-box table thead tr.first-row').html(week_html);

                    var refresh_data = getSelectedSchedule(ym, result.time);

                    var time_html = '';
                    for(var key in DAY_TYPES){
                        time_html += set_schedule(DAY_TYPES[key], refresh_data);
                    }
                    $('.calendar-box table tbody').html(time_html);
                }
            });
        }

        function set_schedule(day_type, data) {
            var from_id, to_id;
            if(day_type == 'morning'){
                from_id = 0;
                to_id = 36;
            }else if(day_type == 'noon'){
                from_id = 36;
                to_id = 68;
            }else if(day_type == 'night'){
                from_id = 68;
                to_id = 96;
            }

            var time_html = '';
            for(var i = from_id ; i < to_id; i++){
                time_html += '<tr class="' + day_type + '">';
                time_html += '<td class="fixed">';
                if(i % 4 == 0){
                    time_html += '<strong>' + data.info[i].title + '</strong>';
                }else{
                    time_html += data.info[i].title;
                }
                time_html += '</td>';
                for(var j = 0 ; j < data.day_count; j++){
                    if(data.info[i].value[j] == 0){
                        time_html += '<td class="bg-color-01">×</td>';
                    }else if(data.info[i].value[j] == 1){
                        time_html += '<td class="ok-icon">';
                        time_html += '<input type="checkbox" name="reserve" id="reserve_' + j + '_' + i + '_a">';
                        time_html += '<label name="reserve_label" for="reserve_' + j + '_' + i + '_a" id="reserve_label_' + j + '_' + i + '" value="0" onclick="clickSchedulePoint(this)">●</label>';
                        time_html += '</td>';
                    }else if(data.info[i].value[j] == 2){
                        time_html += '<td class="ok-icon">';
                        time_html += '<input type="checkbox" name="reserve" id="reserve_' + j + '_' + i + '_a" checked="checked">';
                        time_html += '<label name="reserve_label" for="reserve_' + j + '_' + i + '_a" id="reserve_label_' + j + '_' + i + '" value="1" onclick="clickSchedulePoint(this)">●</label>';
                        time_html += '</td>';
                    }
                }
                time_html += '</tr>';
            }
            return time_html;
        }

        function prev_schedule(){
            var ym = $('#prev_ym').val();
            show_schedule_list(ym);
        }

        function next_schedule(){
            var ym = $('#next_ym').val();
            show_schedule_list(ym);
        }

        function getSelectedSchedule(ym, data){
            var prev_data = new Array(), count = 0, prev_ym;
            for (var i = 0; i < $("#reserve_count").val(); i++){
                prev_ym = $("#reserve_ok_" + i).attr('ym');
                if(prev_ym == ym){
                    prev_data[count++] = $("#reserve_ok_" + i).val();
                }
            }
            for (var i = 0; i < $("#no_reserve_count").val(); i++){
                prev_ym = $("#reserve_no_" + i).attr('ym');
                if(prev_ym == ym){
                    prev_data[count++] = $("#reserve_no_" + i).val();
                }
            }
            for (var i = 0; i < count; i++){
                var from_id, to_id;
                var day_from_pos = prev_data[i].indexOf('月');
                var day_to_pos = prev_data[i].indexOf('日');
                var day = Number(prev_data[i].slice(day_from_pos + 1, day_to_pos));
                var from_time_pos = prev_data[i].indexOf(')');
                var from_time = prev_data[i].substr(from_time_pos + 3, 5);
                from_id = strToTimeId(from_time);
                var to_time_pos = prev_data[i].indexOf('~');
                var to_time = prev_data[i].substr(to_time_pos + 1, 5);
                to_id = strToTimeId(to_time);
                for (var j = from_id; j < to_id; j++){
                    data.info[j].value[day - 1] = 2;
                }
            }
            return data;
        }

        $("#off-label").click(function () {
            $("#off-line").attr('checked');
        });
        $("#on-label").click(function () {
            $("#on-line").attr('checked');
        });

        $("#target_reserve_label").click(function () {
            if ($("#target_reserve").val() == 0) {
                $("#target_reserve").val(1);
            } else {
                $("#target_reserve").val(0);
            }
        });
        $("#pos_discuss_label").click(function () {
            if ($("#pos_discuss").val() == 0) {
                $("#pos_discuss").val(1);
            } else {
                $("#pos_discuss").val(0);
            }
        });

        $(".member").change(function () {
            var man_num = $("#man_num").val();
            var woman_num = $("#woman_num").val();
            if (Number(man_num) > 0 || Number(woman_num) > 0) {
                $("#member_flag").val(1);
            }
        });

        function clickSchedulePoint(obj){

            if ($(obj).val() == 0) {
                $(obj).val(1);
            } else {
                $(obj).val(0);
            }

            var ok_html = '', no_html = '', ok_count = 0, no_count = 0;
            var ym = $('#cur_ym').val(), prev_ym, prev_ok_data, prev_no_data;
            for (i = 0; i < $("#reserve_count").val(); i++){
                prev_ym = $("#reserve_ok_" + i).attr('ym');
                if(prev_ym != ym){
                    prev_ok_data = $("#reserve_ok_" + i).val();
                    ok_html += getOkReserveHtml(ok_count++, prev_ok_data, prev_ym);
                }
            }

            for (i = 0; i < $("#no_reserve_count").val(); i++){
                prev_ym = $("#reserve_no_" + i).attr('ym');
                if(prev_ym != ym){
                    prev_no_data = $("#reserve_no_" + i).val();
                    no_html += getNoReserveHtml(no_count++, prev_no_data, prev_ym);
                }
            }

            var i, j, id, is_continue, count;
            var data = new Array();
            var id = new Array();
            for (i = 0; i < $("#day_count").val(); i++) {
                is_continue = false;
                id[i] = 0;
                count = 0;
                data[i] = new Array();
                for (j = 0; j < 96; j++) {
                    if ($("#reserve_label_" + i + "_" + j).val() == 1) {
                        if (!is_continue) {
                            is_continue = true;
                            data[i][id[i]] = new Array();
                            data[i][id[i]][0] = j;
                        }
                        count++;
                    } else {
                        if (is_continue) {
                            data[i][id[i]][1] = count;
                            id[i]++;
                        }
                        count = 0;
                        is_continue = false;
                    }
                }
            }
            var from_time, to_time, reserve_time;
            for (i = 0; i < $("#day_count").val(); i++) {
                for (j = 0; j < id[i]; j++) {
                    if (data[i][j][1] > 0) {
                        from_time = timeIdToStr(data[i][j][0]);
                        to_time = timeIdToStr(data[i][j][0] + data[i][j][1]);
                        reserve_time = getReserveTime(i + 1, from_time, to_time);
                        if (15 * (data[i][j][1]) >= $("#min_minute").val()) {
                            ok_html += getOkReserveHtml(ok_count, reserve_time, ym);
                            ok_count++;
                        } else {
                            no_html += getNoReserveHtml(no_count, reserve_time, ym);
                            no_count++;
                        }
                    }
                }
            }
            $("#reserve_ok").html(ok_html);
            $("#reserve_no").html(no_html);

            if(ok_count > 0){
                $("#ok_title").show();
                $("#reserve_ok").show();
            }else{
                $("#ok_title").hide();
                $("#reserve_ok").hide();
            }
            if(no_count > 0){
                $("#no_title").show();
                $("#reserve_no").show();
                $("#reserve_no_detail").show();
            }else{
                $("#no_title").hide();
                $("#reserve_no").hide();
                $("#reserve_no_detail").hide();
            }

            $("#reserve_count").val(ok_count);
            $("#no_reserve_count").val(no_count);
            if (ok_count > 0 && no_count == 0) {
                $("#reserve_flag").val(1);
            } else {
                $("#reserve_flag").val(0);
            }
        }

        function timeIdToStr(id) {
            var hour, minute;
            hour = Math.floor(id / 4);
            minute = (id - 4 * hour) * 15;
            return strPad(hour) + ':' + strPad(minute);
        }

        function strToTimeId(time) {
            var hour = Number(time.substr(0, 2));
            var pos = time.search(':');
            var minute = Number(time.substr(pos + 1, 2));
            var timeId = hour * 4 + minute / 15;
            return timeId;
        }

        function strPad(number) {
            return number < 10 ? '0' + number : number;
        }

        function getReserveTime(day, from_time, to_time) {
            var year = $("#year").val();
            var month = $("#month").val();
            var first_date_id = $("#date").val();
            var dates = ["日", "月", "火", "水", "木", "金", "土"];
            var date_id = (Number(first_date_id) + Number(day) - 1) % 7;
            return year + '年' + month + '月' + day + '日 (' + dates[date_id] + ')  ' + from_time + '~' + to_time;
        }

        function getDateForPost(day) {
            var year = $("#year").val();
            var month = $("#month").val();
            return year + ':' + strPad(month) + ':' + strPad(day);
        }

        function getTimeForPost(time) {
            return time + ':00';
        }

        function getOkReserveHtml(id, data, ym) {
            return '<p class="icon_blue" id="icon_blue_' + id + '"><input type="text" name ="reserve_ok_' + id + '" id="reserve_ok_' + id + '" value="' + data + '" ym="' + ym + '"></p>'
        }

        function getNoReserveHtml(id, data, ym) {
            return '<p class="icon_red" id="icon_red_' + id + '"><input type="text" name="reserve_no_' + id + '" id="reserve_no_' + id + '" value="' + data + '" ym="' + ym + '"></p>'
        }

    </script>
@endsection


