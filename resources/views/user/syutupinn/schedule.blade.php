@extends('user.layouts.app')

@section('title', 'センパイ出品')

@section('content')

    <style>
        .activated {
            color: #FB7122 !important;
        }
        .not-active {
            color: #B8B8B8 !important;
        }
    </style>

    @if(isset($page_from))
        @include('user.layouts.header_under')
    @else
        @include('user.layouts.gnavi_under')
    @endif

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents" class="long">
        <!--main_-->
        {{--{{ Form::open(["route"=>["user.syutupinn.schedule_confirm"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}--}}
        <div id="form1">
            <input type="hidden" id="year" name="year" value="{{date('Y')}}">
            <input type="hidden" id="month" name="month" value="{{date('m')}}">
            <section class="tab_area tab_white mb0">
                <div class="switch_tab three_tab mb30">
                    <div class="type_radio radio-01">
                        <input type="radio" name="onof-line" id="off-line" onclick="location.href='{{route('user.syutupinn.lesson_list')}}'">
                        <label class="ok" for="off-line">出品レッスン</label>
                    </div>
                    <div class="type_radio radio-02">
                        <input type="radio" name="onof-line" id="on-line-1" checked="checked" onclick="location.href='{{route('user.syutupinn.schedule')}}'">
                        <label class="ok" for="on-line-1">出勤カレンダー</label>
                    </div>
                    <div class="type_radio radio-03">
                        <input type="radio" name="onof-line" id="on-line-2" onclick="location.href='{{route('user.syutupinn.request')}}'">
                        <label class="ok" for="on-line-2">リクエスト</label>
                        @if($req_count != '')<span class="midoku">{{$req_count}}</span>@endif
                    </div>
                </div>
                <div class="calendar-list">
                    <ul>
                        <li><img src="{{asset('assets/user/img/icon_12.svg')}}"><span>…予約成立</span></li>
                        <li><img src="{{asset('assets/user/img/icon_13.svg')}}"><span>…出勤</span></li>
                        <li><img src="{{asset('assets/user/img/icon_14.svg')}}"><span>…リクエスト</span></li>
                        <li><img src="{{asset('assets/user/img/icon_15.svg')}}"><span>…不可</span></li>
                    </ul>
                </div>
            </section>
            <div class="calendar-area bk-white2 bt" id="lesson_product_schedule">
                <h3 class="text-center">現在のスケジュール</h3>
                <div class="date-area syutupinn-schedule">
                    <input type="hidden" id="cur_date" value="{{$week_start}}">
                    <input type="hidden" id="prev_date">
                    <input type="hidden" id="next_date">
                    <ul>
                        {{--<li class="enabled"><a href="#" onclick="prev_schedule();">前月</a></li>
                        <li></li>
                        <li class="enabled"><a href="#" onclick="next_schedule();">翌月</a></li>--}}
                        <li id="before_week_label"><a onclick="prevSchedule()">前の週</a></li>
                        <li></li>
                        <li id="after_week_label"><a onclick="nextSchedule()">次の週</a></li>
                    </ul>
                    <div class="calendar-box mr0">
                        <table>
                            <thead>
                            <tr class="first-row"></tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                        <div class="calendar-box-02">
                            <div class="space-box"></div>
                            <div class="calendar-button">
                                <button type="button" class="button-01" term="morning">朝<span>(〜9:00)</span></button>
                                <button type="button" class="button-02 f-weight" term="none">昼<span>(9:00~17:00)</span></button>
                                <button type="button" class="button-03" term="night">夜<span>(17:00〜)</span></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--{{ Form::close() }}--}}
        <div id="footer_button_area" class="under_area">
            <ul>
                <li class="send-request">
                    <div class="btn_base btn_orange"> <a href="#" onclick="go_confirm()">スケジュールを変更する</a> </div>
                </li>
            </ul>
        </div>
        <div id="form2" style="display: none">
            <section>
                <div class="inner_box" id="new_schedule_box">
                    <h3 class="icon_plus">登録する出勤</h3>
                    <ul class="list_area" id="new_schedule">
                    </ul>
                </div>

                <div class="inner_box" id="cancel_schedule_box">
                    <h3 class="icon_delete">削除する出勤</h3>
                    <ul class="list_area" id="cancel_schedule">
                    </ul>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="button"  class="button-link" onclick="save_schedule()">変更を確定する</button>
                    </div>
                </div>

                <!-- モーダル部分 *********************************************************** -->
                <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
                <div class="modal-wrap completion_wrap">
                    <div id="modal-content-01" class="modal-content">
                        <div class="modal_body completion">
                            <div class="modal_inner">
                                <h2 class="modal_ttl">
                                    出勤スケジュールを<br>
                                    変更しました
                                </h2>
                                <p class="modal_txt">
                                    コウハイの申込をお待ちください。
                                </p>
                            </div>
                        </div>


                        <div class="button-area type_under">
                            <div class="btn_base btn_ok"><a onclick="reset_schedule_data()">OK</a></div>
                        </div>

                    </div><!-- /modal-content -->

                </div>
                <div id="modal-overlay" style="display: none;"></div>
                <!-- モーダル部分 / ここまで ************************************************* -->

            </section>
        </div>
    </div>
    <!-- /contents -->
    @include('user.layouts.modal')

    <!-- *******************************************************
フッター（リクエストボタンあり）
******************************************************** -->
    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_js')
    <script>
        var WORK_SCHEDULE = [];
        var ADDED_SCHEDULE = [];
        var CANCEL_SCHEDULE = [];

        var g_calendar_header_list; // スケジュール header

        $(document).ready(function(){
            /*$('#header_main_ttl').show();
            $('#header_under_ttl').hide();*/
            var cur_date = $('#cur_date').val();
            setWeekSchedule(cur_date);
        });
        function prevSchedule(){
            var prev_date = $('#prev_date').val();

            // validation (過去へは遡れないようにする。)
            var cur_date = $('#cur_date').val();
            let now = new Date();
            let cur = new Date(cur_date);
            if (cur <= now) {
                return;
            }

            setWeekSchedule(prev_date);
        }

        function nextSchedule(){
            var next_date = $('#next_date').val();

            // validate (未来は8週間先まで。) => 未来に永遠にいけてしまうため修正する
            /*let end_week_day = new Date("{{ $end_week_day }}");
            if (end_week_day <= new Date(next_date)) {
                return;
            }*/

            setWeekSchedule(next_date);
        }

        function setWeekSchedule(cur_date){

            // content
            $.ajax({
                type: "post",
                url: '{{ route('user.syutupinn.get_week_schedule') }}',
                data: {date: cur_date, _token:'{{csrf_token()}}'},
                dataType: 'json',
                success: function (result) {
                    $('#cur_date').val(result.week_info.cur_date);
                    $('#prev_date').val(result.week_info.prev_date);
                    $('#next_date').val(result.week_info.next_date);

                    g_calendar_header_list = result.calendar_header_list;

                    // label color (前の週, 次の週)

                    // ---- 前の週 ---
                    let now = new Date();
                    let cur_date = new Date(result.week_info.cur_date);
                    if (cur_date <= now ) {
                        if (!$('#before_week_label').hasClass('not-active')) {
                            $('#before_week_label').addClass('not-active');
                        }
                        if ($('#before_week_label').hasClass('activated')) {
                            $('#before_week_label').removeClass('activated');
                        }
                    } else {
                        if ($('#before_week_label').hasClass('not-active')) {
                            $('#before_week_label').removeClass('not-active');
                        }
                        if (!$('#before_week_label').hasClass('activated')) {
                            $('#before_week_label').addClass('activated');
                        }
                    }

                    // ---- 次の週 ---
                    /*let end_week_day = new Date("{{ $end_week_day }}");
                    if (end_week_day <= new Date(result.week_info.next_date)) {
                        if (!$('#after_week_label').hasClass('not-active')) {
                            $('#after_week_label').addClass('not-active');
                        }
                        if ($('#after_week_label').hasClass('activated')) {
                            $('#after_week_label').removeClass('activated');
                        }
                    } else {
                        if ($('#after_week_label').hasClass('not-active')) {
                            $('#after_week_label').removeClass('not-active');
                        }
                        if (!$('#after_week_label').hasClass('activated')) {
                            $('#after_week_label').addClass('activated');
                        }
                    }*/

                    // show header
                    $('.calendar-area .date-area ul li:nth-child(2)').html(result.week_info.ym);

                    var d = new Date(year, month, 0);
                    var last_day = d.getDate();
                    var day_html = '<th class="space-box fixed_02"></th>';
                    var k = 1;
                    for(var key in result.calendar_header_list){
                        var red_day = '';
                        if(k == 7) red_day = 'saturday';
                        if(k == 1) red_day = 'sunday';
                        day_html += '<th class="' + red_day + ' fixed"><p class="day">' + WEEK_DAYS[k - 1] + '</p><p class="week">' + result.calendar_header_list[key] + '</p></th>';
                        k++;
                    }
                    $('.calendar-box table thead tr.first-row').html(day_html);

                    // show schedule
                    var obj = {};
                    if(result.state) obj = result.list;
                    set_week_schedule_data(obj, result.calendar_header_list);
                }
            });
        }

        function reset_schedule_data() {
            var year = $('#year').val();
            var month = parseInt($('#month').val());
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            var schedule_obj = {};
            for(var i=1; i<=last_day; i++){
                $('.calendar-area .date-area .calendar-box table tbody tr').each(function(){
                    var k = i + 1;
                    var ele_val = $(this).children('td:nth-child(' + k + ')');
                    if(parseInt($(ele_val).attr('state')) > 0){
                        $(ele_val).attr('old', 1);
                    }else{
                        $(ele_val).attr('old', 0);
                    }
                });
            }
            close_confirm();
            return;
        }

        function go_confirm() {
            WORK_SCHEDULE = [];
            ADDED_SCHEDULE = [];
            CANCEL_SCHEDULE = [];

            var added_obj = get_added_schedule();
            var cancel_obj = get_cancel_schedule();
            if(Object.keys(added_obj).length == 0 && Object.keys(cancel_obj).length == 0){
                show_dialog('出勤スケジュールを変更する内容がないです。');
                return;
            }
            if(Object.keys(added_obj).length > 0){
                $('#new_schedule_box').show();
                $('#new_schedule').html('');
                for(var date_key in added_obj){
                    var d = new Date(date_key);
                    var year = date_key.split('-')[0];
                    var month = date_key.split('-')[1];
                    var day = date_key.split('-')[2];
                    var week_day = d.getDay();
                    var times = added_obj[date_key].split(',');
                    var added_schedule = '';
                    for(var i=0; i<times.length; i++){
                        added_schedule = '<li>' +
                            '<strong>' + year + '</strong>年' +
                            '<strong>' + month + '</strong>月' +
                            '<strong>' + day + '</strong>日（' + WEEK_DAYS[week_day] + '）　<strong>' + times[i] + '</strong>' +
                            '</li>';
                        $('#new_schedule').append(added_schedule);
                        ADDED_SCHEDULE.push(date_key + '=>' + times[i]);
                    }
                }
            }else{
                $('#new_schedule_box').hide();
            }

            if(Object.keys(cancel_obj).length > 0){
                $('#cancel_schedule_box').show();
                $('#cancel_schedule').html('');
                for(var date_key in cancel_obj){
                    var d = new Date(date_key);
                    var year = date_key.split('-')[0];
                    var month = date_key.split('-')[1];
                    var day = date_key.split('-')[2];
                    var week_day = d.getDay();
                    var times = cancel_obj[date_key].split(',');
                    var cancel_schedule = '';
                    for(var i=0; i<times.length; i++){
                        cancel_schedule = '<li>' +
                            '<strong>' + year + '</strong>年' +
                            '<strong>' + month + '</strong>月' +
                            '<strong>' + day + '</strong>日（' + WEEK_DAYS[week_day] + '）　<strong>' + times[i] + '</strong>' +
                            '</li>';
                        $('#cancel_schedule').append(cancel_schedule);
                       CANCEL_SCHEDULE.push(date_key + '=>' + times[i]);
                    }
                }
            }else{
                $('#cancel_schedule_box').hide();
            }

            $('#header_main_ttl').hide();
            $('#header_under_ttl').show();

            $('#form1').hide();
            $('#footer_button_area').hide();
            $('#form2').show();

            $('#header_under_ttl .header_area h1').html("変更内容の確認")
            $('#header_under_ttl .header_area .h-icon p').hide();
            $('#header_under_ttl .header_area .h-icon').append('<p id="temp_p">' +
                '<button type="button" onclick="close_confirm()">' +
                '<img src="{{ asset('assets/user/img/arrow_left2.svg') }}" alt="戻る">' +
                '</button>' +
                '</p>');
        }

        function close_confirm() {
            $('#header_main_ttl').show();
            $('#header_under_ttl').hide();

            $('#form1').show();
            $('#footer_button_area').show();
            $('#form2').hide();
            cancel_modal();
        }

        function get_working_schedule() {
            var year = $('#year').val();
            var month = parseInt($('#month').val());
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            var schedule_obj = {};
            var start_time = '';
            var end_time = '';
            var str_time = '';
            for(var i=1; i<=last_day; i++){
                $('.calendar-area .date-area .calendar-box table tbody tr').each(function(){
                    var k = i + 1;
                    var ele_val = $(this).children('td:nth-child(' + k + ')');
                    if(parseInt($(ele_val).attr('state')) > 0){
                        str_time = $(this).children('td:nth-child(1)').html();
                        if(str_time.indexOf('<strong>') >= 0){
                            str_time = $(this).children('td:nth-child(1)').children('strong').html();
                        }
                        if(start_time == ''){
                            start_time = str_time;
                            end_time = get_endtime(str_time);
                        }else{
                            end_time =  get_endtime(str_time);
                        }
                    }else{
                        if(end_time != ''){
                            var date_key = year + '-' + month + '-' + i;
                            if(schedule_obj[date_key] == '' || schedule_obj[date_key] == undefined ){
                                schedule_obj[date_key] = start_time + '~' + end_time;
                            }else{
                                schedule_obj[date_key] += ',' + start_time + '~' + end_time;
                            }
                            start_time = '';
                            end_time = '';
                        }
                    }
                });
            }
            return schedule_obj;
        }

        function get_added_schedule() {
            var year = $('#year').val();
            var month = parseInt($('#month').val());
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            var schedule_obj = {};
            var start_time = '';
            var end_time = '';
            var str_time = '';
            let i = 1;
            for(let key in g_calendar_header_list){
                $('.calendar-area .date-area .calendar-box table tbody tr').each(function(){
                    let k = i + 1;
                    var ele_val = $(this).children('td:nth-child(' + k + ')');
                    if(parseInt($(ele_val).attr('state')) == 1 && parseInt($(ele_val).attr('old')) == 0){
                        str_time = $(this).children('td:nth-child(1)').html();
                        if(str_time.indexOf('<strong>') >= 0){
                            str_time = $(this).children('td:nth-child(1)').children('strong').html();
                        }
                        if(start_time == ''){
                            start_time = str_time;
                            end_time = get_endtime(str_time);
                        }else{
                            end_time =  get_endtime(str_time);
                        }
                    }else{
                        if(end_time != ''){
                            var date_key = key;
                            if(schedule_obj[date_key] == '' || schedule_obj[date_key] == undefined ){
                                schedule_obj[date_key] = start_time + '~' + end_time;
                            }else{
                                schedule_obj[date_key] += ',' + start_time + '~' + end_time;
                            }
                            start_time = '';
                            end_time = '';
                        }
                    }
                });

                i ++;
            }
            return schedule_obj;
        }

        function get_cancel_schedule() {
            var year = $('#year').val();
            var month = parseInt($('#month').val());
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            var schedule_obj = {};
            var start_time = '';
            var end_time = '';
            var str_time = '';
            let i = 1;
            for(let key in g_calendar_header_list){
                $('.calendar-area .date-area .calendar-box table tbody tr').each(function(){
                    let k = i + 1;
                    var ele_val = $(this).children('td:nth-child(' + k + ')');
                    if(parseInt($(ele_val).attr('state')) == 0 && parseInt($(ele_val).attr('old')) == 1){
                        str_time = $(this).children('td:nth-child(1)').html();
                        if(str_time.indexOf('<strong>') >= 0){
                            str_time = $(this).children('td:nth-child(1)').children('strong').html();
                        }
                        if(start_time == ''){
                            start_time = str_time;
                            end_time = get_endtime(str_time);
                        }else{
                            end_time =  get_endtime(str_time);
                        }
                    }else{
                        if(end_time != ''){
                            var date_key = key;
                            if(schedule_obj[date_key] == '' || schedule_obj[date_key] == undefined ){
                                schedule_obj[date_key] = start_time + '~' + end_time;
                            }else{
                                schedule_obj[date_key] += ',' + start_time + '~' + end_time;
                            }
                            start_time = '';
                            end_time = '';
                        }
                    }
                });
                i ++;
            }
            return schedule_obj;
        }

        function save_schedule() {
            var year = $('#year').val();
            var month = $('#month').val();
            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("year", year);
            form_data.append("month", month);
            form_data.append("new_schedule", ADDED_SCHEDULE);
            form_data.append("cancel_schedule", CANCEL_SCHEDULE);
            $.ajax({
                type: "post",
                url: '{{route('user.syutupinn.save_schedule')}}',
                data : form_data,
                dataType: 'json',
                contentType : false,
                processData : false,
                success : function(result) {
                    if(result.state){
                        open_modal();
                    }else{
                        show_dialog('出勤スケジュールを変更が失敗しました.');
                    }
                }
            });
        }

        function open_modal(){
            $('.modal-wrap').fadeIn();
            $('#modal-content-01').fadeIn();
            $('#modal-overlay').fadeIn();
        }

        function cancel_modal(){
            $('.modal-wrap').fadeOut();
            $('#modal-content-01').fadeOut();
            $('#modal-overlay').fadeOut();
        }

        function set_week_schedule_data(data, calendar_header_list) {
            var h, m, w;
            var time_html = '';
            /*var year = $('#year').val();
            var month = parseInt($('#month').val());
            var d = new Date();
            d.setFullYear(year);
            d.setMonth(month);*/
            var d = new Date();
            var week_day = 0;
            var time_label = "";
            $('.calendar-box table tbody').html('');
            for(var t=0; t<24; t=t+0.25){
                if(t < 9) w = 'morning';
                else if(t >= 17) w = 'night';
                else w = 'noon';
                h = Math.floor(t).toString();
                if(Math.floor(t) < 10) h = '0' + Math.floor(t).toString();
                if(Number.isInteger(t)){
                    time_html = '<tr class="' + w + '"><td class="fixed"><strong>' + h + ':00</strong></td>';
                    time_label = h + ':00';
                }else{
                    m = ((t - Math.floor(t)) * 60).toString();
                    time_html = '<tr class="' + w + '"><td class="fixed">' + h + ':' + m + '</td>';
                    time_label = h + ':' + m;
                }
                if(Object.keys(data).length > 0){
                    for(var key in calendar_header_list){
                    // for(var i=0; i < calendar_header_list.length; i++){
                        var state = 0;
                        var ttl = "";
                        var kouhai = "";
                        var time = "00:00~00:00";
                        var k = '' + t;
                        if(data[k] != undefined && data[k][calendar_header_list[key]] != undefined){
                            if(typeof data[k][calendar_header_list[key]] == "object"){
                                ttl = data[k][calendar_header_list[key]]['ttl'];
                                kouhai = data[k][calendar_header_list[key]]['kouhai'];
                                time = data[k][calendar_header_list[key]]['time'];
                                state = parseInt(data[k][calendar_header_list[key]]['val']);
                            }else{
                                state = parseInt(data[k][calendar_header_list[key]]);
                            }
                            if(isNaN(state)) state = 0;
                        }
                        switch(state){
                            case 1: //出勤
                                time_html += '<td class="ok-icon2" row="'+ time_label +'" col="' + calendar_header_list[key] + '" state="1" old="1" onclick="change_point(this)" data-time="'+ key +'">◎</td>';
                                break;
                            case 2: //リクエスト
                                time_html += '<td class="ok-icon3" row="'+ time_label +'" col="' + calendar_header_list[key] + '" state="2" old="1" onclick="change_point(this)" data-time="'+ key +'"><img src="{{asset('assets/user/img/icon_14big.svg')}}"></td>';
                                break;
                            case 3: //予約成立
                                d = new Date(key);
                                let year = d.getFullYear();
                                let month = d.getMonth()+1;
                                week_day = d.getDay();
                                var talkroor_url = '{{route('user.talkroom.list')}}';
                                time_html += '<td class="bg-color-02" row="'+ time_label +'" col="' + calendar_header_list[key] + '" state="3" old="1" data-time="'+ key +'">' +
                                    '<a ondblclick="open_small_dlg(this)">✓</a><div class="c-baloon">' +
                                    '<div class="c-m"><span>対面</span><time>' + month + '月' + calendar_header_list[key] + '日(' + WEEK_DAYS[week_day] + ')　' + time + '</time></div>' +
                                    '<div class="service-name"><h2>' + ttl +
                                    '</h2></div>' +
                                    '<div class="senpai-name"><p>' + kouhai + ' </p></div>' +
                                    '<p class="to-talkroom"><a href="' + talkroor_url + '">トークルームへ</a></p>' +
                                    '<div class="c-close" onclick="close_small_dlg(this);" style="cursor:pointer">×</div>' +
                                    '</div>' +
                                    '</td>';
                                break;
                            default: //不可
                                time_html += '<td class="bg-color-01" row="'+ time_label +'" col="' + calendar_header_list[key] + '" state="0" old="0" onclick="change_point(this)" data-time="'+ key +'">×</td>';
                                break;
                        }

                    }
                }else{
                    var i = 0;
                    for(var key in calendar_header_list){
                        time_html += '<td class="bg-color-01" row="'+ time_label +'" col="' + calendar_header_list[key] + '" state="0" old="0" onclick="change_point(this)" data-time="'+ key +'">×</td>';
                        i++;
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
        }

        function change_point(obj) {
            // validate
            let data_time = $(obj).attr('data-time');
            let time = $(obj).attr('row');
            let set_datetime = new Date(data_time + ' ' + time);
            let now = new Date();
            if (set_datetime < now) {
                return;
            }

            if($(obj).hasClass('bg-color-01')){
                $(obj).removeClass('bg-color-01');
                $(obj).addClass('ok-icon2');
                $(obj).html('◎');
                $(obj).attr('state',1);
                return;
            }
            if($(obj).hasClass('ok-icon2')){
                $(obj).removeClass('ok-icon2');
                $(obj).addClass('bg-color-01');
                $(obj).html('×');
                $(obj).attr('state',0);
                return;
            }
        }

        function open_small_dlg(obj) {
            if($(obj).next('.c-baloon').css('display') == 'block') return;
            $('.bg-color-02 .c-baloon').each(function () {
                $(this).hide() ;
            });
            $(obj).next('.c-baloon').fadeIn();
        }
        function close_small_dlg(obj) {
            $(obj).parents('.c-baloon').fadeOut();
        }
        function get_endtime(start_time) {
            var end_time_arr = start_time.split(':');
            var str_h = '';
            var str_m = '';
            var h = parseInt(end_time_arr[0]) ;
            var m = parseInt(end_time_arr[1]) + 15;
            if(m == 60){
                m = 0;
                h = h + 1;
                if(h == 24)  h = 0;
            }
            if(h < 10)  str_h = '0' + h;
             else str_h = h;
            if(m < 10)  str_m = '0' + m
            else str_m = m;
            var end_time = str_h + ':' + str_m;
            return end_time;
        }
    </script>
@endsection
