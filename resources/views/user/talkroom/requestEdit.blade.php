@extends('user.layouts.app')
@section('title', 'リクエスト内容の変更')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents" >

    <!--main_-->
    {{ Form::open(["route" => "user.talkroom.updateRequest", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" id="year" name="year" value="{{date('Y')}}">
        <input type="hidden" id="month" name="month" value="{{date('n')}}">
        <input type="hidden" name="req_id" value="{{$req_info['lr_id']}}">
        <input type="hidden" id="people_num" name="people_num" value="{{ old('people_num') }}">
        <input type="hidden" id="schedule_validation" name="schedule_validation" value="0">
        <input type="hidden" id="30min_fees" name="30min_fees" value="{{ $req_info['lesson']['lesson_30min_fees'] }}">
        <section>
            <div class="white_box mt30 plus-fukidashi">
                <span class="choice_lesson">選択中のレッスン！</span>
                <p class="lesson_ttl">{{ $req_info['lesson']['lesson_title'] }}</p>
                <ul class="choice_price">
                    <li class="icon_taimen">対面</li>
                    <li class="price"><em>{{ $req_info['lesson']['lesson_30min_fees'] }}</em>円<span> / <em>30</em>分〜</span></li>
                </ul>
            </div>
        </section>

        <section>

            <div class="inner_box for-warning">
                <h3>参加人数</h3>
                <p class="warning"></p>
                <ul class="select_flex">
                    <li>
                        <div>男性</div>
                        <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                            <select name="lr_man_num" id="lr_man_num" class="people_num">
                                @for ($i = 0; $i <= 50; $i++)
                                    <option value="{{$i}}" @if ($req_info['lr_man_num'] == $i) selected @endif >{{ $i }}</option>
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
                                    <option value="{{$i}}" @if ($req_info['lr_woman_num'] == $i) selected @endif >{{ $i }}</option>
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
                    <p>※複数回のレッスンを同時に予約できます。<br>
                        ※このセンパイの最低レッスン時間は{{ $req_info['lesson']['lesson_min_hours'] }}分です。<br>
                        連続する{{ $req_info['lesson']['lesson_min_hours'] }}分以上の枠を選択してください。</p>
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
                @error('invalid_date')
                <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
            </div>
        </section>

        <section>
            <div class="inner_box">
                <h3>リクエストする予約</h3>
                <div class="white_box reserved_wrap icon_check">
                    @foreach( $req_info['lesson_request_schedule'] as $key => $value )
                        <input type="hidden" name="origin_schedule_html[]" value="{{ \App\Service\CommonService::getYMDAndWeek($value['lrs_date']) . ' ' .
                         \App\Service\CommonService::getStartAndEndTime($value['lrs_start_time'], $value['lrs_end_time'])}}">
                        <p class="icon_blue"><input type="text" id="target-1" value="{{ \App\Service\CommonService::getYMDAndWeek($value['lrs_date']) . ' ' .
                         \App\Service\CommonService::getStartAndEndTime($value['lrs_start_time'], $value['lrs_end_time'])}}"></p>
                    @endforeach
                </div>
                <h3 class="validate">変更後の予約</h3>
                <div class="white_box reserved_wrap icon_check validate" id="validate">
                </div>
                <h3 class="non_validate">内容に不備のある予約</h3>
                <div class="white_box reserved_wrap icon_check non_validate" id="non_validate">
                </div>
                <div class="balloon balloon_blue">
                    <p>この先輩のレッスンは{{ $req_info['lesson']['lesson_min_hours'] }}分以上の枠で予約してください。変更されない場合は予約内容に反映されません。</p>
                </div>
            </div>

            <div class="inner_box pb0">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="lesson_place">
                        <p class="place_point">{{ implode('/', $req_info['lesson']['lesson_area_names']) }}</p>
                    </div>
                    @if(isset($req_info['lesson']['lesson_pos_detail']) && !empty($req_info['lesson']['lesson_pos_detail']))
                        <div class="balloon balloon_blue">
                            <p>{{$req_info['lesson']['lesson_pos_detail']}}</p>
                        </div>
                    @endif

                    <div class="kodawari_check check-box">
                        <div class="clex-box_01">
                            <input type="hidden" name="target_reserve" value="{{ old('target_reserve', $req_info['lr_target_reserve']) == 1 ? 1 : 0 }}" id="target_reserve">
                            <input type="checkbox" name="c1" value="{{ old('target_reserve', $req_info['lr_target_reserve']) == 1 ? 1 : 0 }}" id="c1" {{ old('target_reserve', $req_info['lr_target_reserve']) == 1 ? 'checked' : '' }}>
                            <label for="c1" class="nobo" id="target_reserve_label"><p>指定地で予約する</p></label>
                        </div>
                        <div class="clex-box_01">
                            <input type="hidden" name="pos_discuss" value="{{ old('pos_discuss', $req_info['lr_pos_discuss']) == 1 ? 1 : 0 }}" id="pos_discuss">
                            <input type="checkbox" name="c2" value="{{ old('pos_discuss', $req_info['lr_pos_discuss']) == 1 ? 1 : 0 }}" id="c2" class="check-trigger" {{ old('pos_discuss', $req_info['lr_pos_discuss']) == 1 ? 'checked' : '' }}>
                            <label for="c2" class="nobo" id="pos_discuss_label"><p>レッスン場所を相談する</p></label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="hide-area pt30">
                <div class="inner_box text-p-04">
                    <h3>相談可能な地域</h3>
                    @if($req_info['lesson']['lesson_discuss_area_names'] && count($req_info['lesson']['lesson_discuss_area_names']) > 0)
                        <ul class="soudan_ok_area">
                            @foreach($req_info['lesson']['lesson_discuss_area_names'] as $val)
                                <li>{{ $val }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="inner_box for-warning">
                    <h3>エリアを選択</h3>
                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                        <select name="area_id" id="area_id">
                            <option value="">選択してください</option>
                            @if($req_info['lesson']['lesson_discuss_area_names'] && count($req_info['lesson']['lesson_discuss_area_names']) > 0)
                                @foreach($req_info['lesson']['lesson_discuss_area'] as $lesson_area)
                                    @if($lesson_area['position'])
                                        @php
                                            $position = json_decode($lesson_area['position']);
                                            $area_content = $position->area_name;
                                        @endphp
                                        <option value="{{ $lesson_area['id'] }}" {{ old('area_id', $req_info['lr_area_id']) == $lesson_area['id'] ? 'selected' : '' }}>{{ $area_content }}</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <p class="warning"></p>
                </div>

                <div class="inner_box for-warning">
                    <h3>続きの住所を入力</h3>
                    <div class="input-text shadow-glay">
                        <input type="text" id="address" name="address" size="50" maxlength="50" value="{{ old('address', isset($req_info['lr_address']) ? $req_info['lr_address'] : '') }}">
                    </div>
                    <p class="warning"></p>
                </div>

                <div class="inner_box no-pb for-warning">
                    <h3>待ち合わせ場所の詳細
                        <small>（200文字まで）<i>任意</i></small>
                    </h3>
                    <div class="input-text2">
                        <textarea placeholder="待ち合わせ場所の詳細説明があれば入力してください。" id="address_detail" cols="50" rows="10" class="shadow-glay"
                                  name="address_detail">{{ old('address_detail', isset($req_info['lr_address_detail']) ? $req_info['lr_address_detail'] : '') }}</textarea>
                    </div>
                    <p class="warning"></p>
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
                    <input type="date" class="form_btn approval" value="{{ old('until_confirm', $req_info['lr_until_confirm']) }}" name="until_confirm" >
                </div>
                @error('until_confirm')
                <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
            </div>

        </section>

        <section id="button-area">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <a class="ajax_submit">予約リクエストを変更して送信</a>
                </div>
            </div>
        </section>

    {{ Form::close() }}

</div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <input type="hidden" class="modal-syncer" data-target="modal-request_henkou" id="modal_ok">
    <div class="modal-wrap completion_wrap ok">

        <div id="modal-request_henkou" class="modal-content ok">
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
                <div class="btn_base btn_ok"><a href="{{ route('user.talkroom.list') }}">OK</a></div>
            </div>

        </div><!-- /modal-content -->

    </div>
    <div id="modal-overlay2" style="display: none;"></div>
    <!-- モーダル部分 / ここまで ************************************************* -->
    @include('user.layouts.modal')

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
            if($('[id=c2]').prop('checked')){
                $('.hide-area').show();
            } else  {
                $('.hide-area').hide();
            }

            var year = $('#year').val();
            var month = $('#month').val();
            show_schedule_list(year, month);

            getPeopleNum();
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

        function prev_schedule(){
            getCurretMonth('prev');
            var year = $('#year').val();
            var month = $('#month').val();
            show_schedule_list(year, month);
        }

        function next_schedule(){
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
                data: {senpai_id:'{{ $req_info['lesson']['lesson_senpai_id'] }}',year: year, month: month, _token:'{{csrf_token()}}'},
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
                            var date = year + '_' + month + '_' + i;
                            var selected = new_request_schedule[date] != undefined ? new_request_schedule[date].indexOf(k) : -1;
                            var checked = selected > -1 ? 1 : 0;
                            var checkbox_checked = checked ? 'checked': '';

                            time_html += '<td class="ok-icon">' +
                                '<input type="checkbox" name="reserve" id="reserve_'
                                + k + '_' + i + '_a" ' + checkbox_checked + '><label name="reserve_label" for="reserve_' + k + '_' + i + '_a" time="' + k + '" day="' + i + '" value="' + checked + '">●</label>' +
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
                var year = $('#year').val();
                var month = $('#month').val();
                var day = $(this).attr('day');
                var date = year + '_' + month + '_'+ day;
                // var date = $(this).attr('day');
                var time = $(this).attr('time');


                if ($(this).attr('value') == 0) {
                    $(this).attr('value', 1);

                    // input schedule info into new_request_schedule
                    if ( new_request_schedule[date] == undefined ) {
                        var schedule_times = [time];
                        new_request_schedule[date] = schedule_times;
                    } else {
                        new_request_schedule[date].push(time);
                    }
                }else{
                    $(this).attr('value', 1);

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
            });
        }

        function showSelectedSchedule() {
            var schedule_list = getSchedule();
            var schedule_ok_html = '';
            var schedule_unok_html = '';

            valid_schedule = true;

            if ( schedule_list.length < 1 ) {
                valid_schedule =false;
            }

            for ( var k in schedule_list ) {
                var first_time = timeIdToStr(schedule_list[k].first_time * 4);
                var last_time = timeIdToStr(schedule_list[k].last_time * 4);
                var date = schedule_list[k].date;
                var day = date.split('_')[2];

                var time = getReserveTime(day, first_time, last_time);

                if ( validateSchedule(schedule_list[k]) ) {
                    schedule_ok_html += getOkScheduleHtml(time);
                } else {
                    schedule_unok_html += getNoScheduleHtml(time);

                    valid_schedule = false;
                }
            }

            if( valid_schedule ) {
                $("#schedule_validation").val(1);
            } else {
                $("#schedule_validation").val(0);
            }

            if ( schedule_ok_html != '' ) {
                $('#validate').html(schedule_ok_html);
                $('.validate').show();
            } else {
                $('.validate').hide();
            }

            if ( schedule_unok_html != '' ) {
                $('#non_validate').html(schedule_unok_html);
                $('.non_validate').show();
            } else {
                $('.non_validate').hide();
            }
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

        function validateSchedule(schedule) {
            var interval = schedule.last_time - schedule.first_time;
            if ( (interval * 60)  < parseFloat('{{ $req_info['lesson']['lesson_min_hours'] }}') ) {
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
            return '<p class="icon_blue"><input type="text" name="sel_schedule_html[]" value="' + data + '"></p>'
        }

        function getNoScheduleHtml(data) {
            return '<p class="icon_red"><input type="text" name="" value="' + data + '"></p>'
        }

        function getPeopleNum() {
            var man = $('#lr_man_num').val();
            var woman = $('#lr_woman_num').val();

            $('#people_num').val(man * 1 + woman * 1)
        }

        function validation() {
            var validation = true;
            if ( $('#people_num').val() < 1 ) {
                addError($('#people_num'), "参加人数を入力してください。")
                validation = false;
            }

            /*if ( $('#schedule_validation').val() < 1 ) {
                validation = false;
            }*/

            if ( $('#pos_discuss').val() > 0 ) {
                if ($('#area_id').val() =='') {
                    addError($('#area_id'), "エリアを選択してください。")
                    validation = false;
                }

                if ($('#address').val() =='') {
                    addError($('#address'), "続きの住所を入力してください。")
                    validation = false;
                }

                if ($('#address_detail').val() =='') {
                    addError($('#address_detail'), "待ち合わせ場所の詳細を入力してください。")
                    validation = false;
                }

            }

            return validation;
        }

        $('.ajax_submit').click(function () {
            if ( !validation() ) {
                return;
            }
            $("#form1").submit();

            /*var postData = new FormData($("#form1").get(0));
            postData.append("_token", "{{csrf_token()}}");
            console.log("postData:", postData);

            $.ajax({
                type: "post",
                url: '{{ route('user.talkroom.updateRequest') }}',
                data: postData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (result) {
                    if ( result.result_code == 'success' ) {
                        $('#modal_ok').click();
                    } else {
                        location.href = "{{route('user.talkroom.list')}}"
                    }
                },
            });*/

        });

    </script>

@endsection
