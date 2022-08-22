@php
use \App\Service\TalkroomService;
@endphp
@extends('user.layouts.app')

@section('title', '美久')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_talkroom')

    <script>
        $(function() {
            $('body').delay(10).animate({
                scrollTop: $(document).height()
            },10);
        });
    </script>

    <div id="contents">
    <!--main_-->
    <form action="./" method="post" name="form1" id="form1">
        <div class="calender-sort">
            @include('user.layouts.top_menu_lesson_list')
        </div>

        <section class="pt70">

            <div class="talk_area">
                <ul style="display: none">
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_Request_Response."\""; @endphp>
                        <div class="talk_icon">
                            <img src=" {{ asset('storage/avatar/'.$talk_user_info['user_avatar']) }} " alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li class="myself" id=@php echo "\"Senpai_".TalkroomService::Senpai_Request_ChangeResponse."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li class="myself" id=@php echo "\"Senpai_".TalkroomService::Senpai_Request_ConfirmOrChange."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li class="myself" id=@php echo "\"Senpai_".TalkroomService::Senpai_Request_Cancel."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li class="myself"  id=@php echo "\"Senpai_".TalkroomService::Senpai_Request_Confirm."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_Sys_Msg."\""; @endphp>
                        <div class="talk_icon">
                            <img src=" {{ asset('storage/avatar/default.png') }} " alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_Sys_RequestConfirm."\""; @endphp>
                        <div class="talk_icon">
                            <img src=" {{ asset('storage/avatar/default.png') }} " alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_Sys_PosShare."\""; @endphp>
                        <div class="talk_icon">
                            <img src=" {{ asset('storage/avatar/default.png') }} " alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_Btn_LessonBuy."\""; @endphp>
                        <div class="talk_area_btn buy_ellipse shadow-glay">
                            <p>レッスンが購入されました</p>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_Btn_PosCancel."\""; @endphp>
                        <div class="talk_area_btn cancel">
                            <a href="D-26.php">
                                <p>位置情報を共有しキャンセルする</p>
                                <p>※申請直後に共有オフになります</p>
                            </a>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_Btn_Evalution."\""; @endphp>
                        <div class="talk_area_btn yellow_base shadow-glay">
                            <a href="D-21_22b.php"><p>評価を入力する</p></a>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Senpai_LeftMsg."\""; @endphp>
                        <div class="talk_icon">
                            <img src=" {{ asset('storage/avatar/'.$talk_user_info['user_avatar']) }} " alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li class="myself" id=@php echo "\"Senpai_".TalkroomService::Senpai_RightMsg."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li  id=@php echo "\"Senpai_".TalkroomService::Senpai_Map."\""; @endphp>
                        <div class="talk_icon">
                            <!-- グーグルマップ表のためアイコンなし -->
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_area_map"  id="me_message">
                                <iframe src="http://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.1881663481045!2d139.70811601528163!3d35.64773538020213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b41b58cd6f5%3A0x90b5d7b83c971892!2z44CSMTUwLTAwMTMg5p2x5Lqs6YO95riL6LC35Yy65oG15q-U5a-_77yR5LiB55uu77yY4oiS77yTIOaBteavlOWvv-ODquODkOODvOOCueODiOODvOODs-ODj-OCpOODoA!5e0!3m2!1sja!2sjp!4v1638772504137!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>

                    ///
                    <li class="myself" id=@php echo "\"Kouhai_".TalkroomService::Kouhai_Rquest_ConfirmOrChange."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li class="myself" id=@php echo "\"Kouhai_".TalkroomService::Kouhai_Request_Cancel."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Kouhai_".TalkroomService::Kouhai_Request_Buy."\""; @endphp>
                        <div class="talk_icon">
                            <img src=" {{ asset('storage/avatar/'.$talk_user_info['user_avatar']) }} " alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Kouhai_".TalkroomService::Kouhai_Sys_Msg."\""; @endphp>
                        <div class="talk_icon">
                            <img src="{{ asset('storage/avatar/default.png') }}" alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li @php echo "\"Kouhai_".TalkroomService::Kouhai_Sys_Confirm."\""; @endphp>
                        <div class="talk_icon">
                            <img src="{{ asset('storage/avatar/default.png') }}" alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li @php echo "\"Kouhai_".TalkroomService::Kouhai_Sys_CancelMoney."\""; @endphp>
                        <div class="talk_icon">
                            <img src="{{ asset('storage/avatar/default.png') }}" alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon"  id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li @php echo "\"Kouhai_".TalkroomService::Kouhai_Sys_PosShare."\""; @endphp>
                        <div class="talk_icon">
                            <img src="{{ asset('storage/avatar/default.png') }}" alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li @php echo "\"Kouhai_".TalkroomService::Kouhai_Btn_LessonBuy."\""; @endphp>
                        <div class="talk_area_btn buy_ellipse shadow-glay">

                            <p>レッスンを購入しました</p>

                        </div>
                    </li>
                    <li @php echo "\"Kouhai_".TalkroomService::Kouhai_Btn_Start."\""; @endphp>
                        <div class="talk_area_btn yellow_base start shadow-glay">
                            <a class="modal-syncer" data-target="modal-lesson_start">
                                <p>START!</p>
                            </a>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Kouhai_Btn_PosCancel."\""; @endphp>
                        <div class="talk_area_btn cancel">
                            <a href="D-26.php">
                                <p>位置情報を共有しキャンセルする</p>
                                <p>※申請直後に共有オフになります</p>
                            </a>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Kouhai_Btn_Evalution."\""; @endphp>
                        <div class="talk_area_btn yellow_base shadow-glay">
                            <a href="D-21_22b.php"><p>評価を入力する</p></a>
                        </div>
                    </li>
                    <li id=@php echo "\"Senpai_".TalkroomService::Kouhai_LeftMsg."\""; @endphp>
                        <div class="talk_icon">
                            <img src=" {{ asset('storage/avatar/'.$talk_user_info['user_avatar']) }} " alt="">
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li class="myself" id=@php echo "\"Senpai_".TalkroomService::Kouhai_RightMsg."\""; @endphp>
                        <div class="talk_balloon_box">
                            <div class="talk_balloon" id="me_message">
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <li  id=@php echo "\"Senpai_".TalkroomService::Kouhai_Map."\""; @endphp>
                        <div class="talk_icon">
                            <!-- グーグルマップ表のためアイコンなし -->
                        </div>
                        <div class="talk_balloon_box">
                            <div class="talk_area_map">
                                <iframe src="http://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3242.1881663481045!2d139.70811601528163!3d35.64773538020213!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x60188b41b58cd6f5%3A0x90b5d7b83c971892!2z44CSMTUwLTAwMTMg5p2x5Lqs6YO95riL6LC35Yy65oG15q-U5a-_77yR5LiB55uu77yY4oiS77yTIOaBteavlOWvv-ODquODkOODvOOCueODiOODvOODs-ODj-OCpOODoA!5e0!3m2!1sja!2sjp!4v1638772504137!5m2!1sja!2sjp" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                            </div>
                            <div class="send_time">
                                <span id="time"></span>
                            </div>
                        </div>
                    </li>
                    <div class="talk_date_area" id="talk_date_area">
                        <p id="talkdate"></p>
                    </div>
                </ul>

                <input type="hidden" id="room_id" name="room_id" value="{{ $room_id }}">
                <input type="hidden" id="previous_id" name="previous_id" value="0">

                <ul class="messages">
                </ul>
            </div>

        </section>

    </form>


</div><!-- /contents -->
    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.footer_talkroom')
    </footer>

@endsection

@section('page_js')
    <script src="{{ asset('assets/user/js/chat/js/index.js') }}"></script>
    <script src="{{ asset('assets/user/js/moment/moment.js') }}"></script>
    <script src="{{ asset('assets/user/js/moment/moment-with-locales.js') }}"></script>
    <script>

        (function () {
            var clearResizeScroll, insertPartner, insertMe;
            var olddate;
            var oldid;
            var curyear = new Date().getFullYear();
            clearResizeScroll = function () {
                $(".message_text").val("");
                return goBottomScroll();
            };

            goBottomScroll = function () {
                $(window).scrollTop(999999);
            };

            insertMe = function (message, position='start', sending_show=true) {
                @if ( $menu_type == TalkroomService::KOUHAI_MENU )
                    var template = $("#Senpai_" + message['msg_type']).clone();
                @else
                    var template = $("#Kouhai_" + message['msg_type'] ).clone();
                @endif

                template.attr('id', 'me_template_' + message['id']);
                template.attr('message_id', message['id']);
                var mmt = moment(message['created_at'], 'YYYY-MM-DD hh:mm:ss');
                var stime = mmt.format('HH:mm');
                var second = mmt.format('s');
                var md;

                $("#time", template).html(stime);
                if ( second > 0 )
                    $("#time", template).addClass("kidoku");
                var weekday = mmt.localeData().weekdaysShort()[mmt.day()];
                var curdate = mmt.format('YYYY-MM-DD');
                var date_template = $("#talk_date_area" ).clone();
                date_template.attr('id', 'talk_date_area' + message['id']);
                if ( curyear != mmt.format('YYYY')) {
                    md = mmt.format('YYYY/M/D');
                } else {
                    md = mmt.format('M/D');
                }

                $("#me_message", template).html( '<p>' +message['message'].replace(/\n/g, '<br>').replace(/\s/g, "&nbsp;") + '</p>');
                template.show();

                if(position == 'start') {
                    $(".messages").prepend(template);

                    $("#talkdate", date_template).html(md + '<span>（' + weekday + '）');
                    date_template.show();
                    $(".messages").prepend(date_template);
                    if ( olddate != undefined && curdate == olddate ) {
                        var strid ='#talk_date_area' + oldid;
                        $(strid).remove();
                    }

                    olddate = mmt.format('YYYY-MM-DD');
                    oldid = message['id'];

                    if ($('#previous_id').val() == 0) {
                        goBottomScroll();
                    }

                } else {

                    if ( ( olddate != undefined && olddate != curdate) || olddate == undefined ) {
                        $("#talkdate", date_template).html(md + '<span>（' + weekday + '）');
                        date_template.show();
                        $(".messages").append(date_template);
                        olddate = mmt.format('YYYY-MM-DD');
                    }
                    /*if (sending_show ) {
                        $("#msg-sending", template).show();
                    }*/
                    $(".messages").append(template);
                    clearResizeScroll();
                }
            };

            /*deleteNotSequenceElements = function() {
                var previous_latest_id = $('#latest_id').val();
                $('.chat_waku').each(function(i, obj) {
                    var message_id = $(this).attr('message_id');
                    if ( message_id > previous_latest_id ) {
                        $(this).remove();
                    }
                });
            };*/

            getMessages = function(method='old') {

                //method new->get new messages, old->get old messages
                var room_id = $('#room_id').val();
                var previous_id = $('#previous_id').val();
                $.ajax({
                    type: "post",
                    url: " {{ route('user.talkroom.getMessages') }}",
                    data: {
                        id: room_id,
                        _token: "{{ csrf_token() }}",
                        previous_id: previous_id,
                        get_method: method
                    },
                    dataType: "json",
                    success: function(data) {
                        if (method == 'old') {
                            var previous_messages = data['previous_messages'];
                            if (previous_messages.length > 0) {
                                var new_previous_id = previous_messages[previous_messages.length-1]['id'];
                                for (i = 0; i < previous_messages.length; i++) {
                                    var message = previous_messages[i];
                                    insertMe(message, 'start')
                                }
                                $('#previous_id').val(new_previous_id);
                            }
                        } else {
                            var messages = data['new_messages'];

                            if ( messages.length > 0) {
                                //delete not sequence chat history
                                //deleteNotSequenceElements();
                                for (i = 0; i < messages.length; i++) {
                                    var message = messages[i];
                                    insertMe(message, 'end', false);
                                }

                            }
                        }
                    },
                    error: function(){
                    },
                    complete: function() {
                    }
                 });
            };

            sendMessage = function(msg, id) {
                var room_id = $('#room_id').val();
                $.ajax({
                    type: "post",
                    url: " {{ route('user.talkroom.sendMessage') }}",
                    data: {
                        id: room_id,
                        _token: "{{ csrf_token() }}",
                        menu_type: "{{ $menu_type }}",
                        message: msg
                    },
                    dataType: "json",
                    success: function(data) {
                        if (data['result_code'] == 'success') {
                            var element = $('#me_template_' + id);
                            if (element) {
                                //element.find('#msg-sending').hide();
                                element.attr('id', 'me_template_' + data['message']['id']);
                                element.attr('message_id', data['message']['id']);
                            }
                        }
                    },
                    error: function(){

                    },
                });
            };

            insertSend = function () {
                message = [];
                message['id'] = 'tmp_' + getRandomInt(10000, 99999);
                @if ( $menu_type == TalkroomService::KOUHAI_MENU )
                    message['msg_type'] = {{ TalkroomService::Senpai_RightMsg }}
                @else
                    message['msg_type'] = {{ TalkroomService::Kouhai_RightMsg }}
                @endif
                message['message'] = $.trim($(".message_text").val());
                message['created_at'] = new Date();

                if ( message['message'] !='' ) {
                    insertMe(message, 'end');
                    sendMessage(message['message'],  message['id']);
                }
            };

            getRandomInt = function (min, max) {
                min = Math.ceil(min);
                max = Math.floor(max);
                return Math.floor(Math.random() * (max - min)) + min;
            };

            getNewMessages = function () {
                setInterval(function () {
                    getMessages('new');
                }, 50000);
            };

            $(document).ready(function () {

                moment.locale('ja');
                getMessages();
                getMessages('new');
                getNewMessages();

                $(".message_text").keydown(function (e) {
                    if (e.keyCode === 13 && e.ctrlKey) {
                        insertSend();
                        return false;
                    }
                });

                $(".send").click(function () {
                    insertSend();
                });

                $(window).scroll(function(){
                    if ($(window).scrollTop() == 0){
                        getMessages();
                    }
                });
            });

        }).call(this);

    </script>
@endsection

