@extends('user.layouts.app')
@section('title', '予約カレンダー')

@section('content')
<!-- ************************************************************************
本文
************************************************************************* -->
@include('user.layouts.header_under')

    <style>
        .activated {
            color: #FB7122 !important;
        }
    </style>

    <div id="contents">

        <!--main_-->
        <form action="./" method="post" name="form1" id="form1">
            <div class="no-space01 white_box">
                <div class="calendar-area calendar-border">
                    <div class="date-area">
                        <ul>
                            <li class="activated"><a href="{{ route('user.talkroom.subscriptionCal', [$infos['previous']]) }}">前月</a></li>
                            <li>{{ $infos['current_label'] }}</li>
                            <li><a href="{{ route('user.talkroom.subscriptionCal', [$infos['next']]) }}">翌月</a></li>
                        </ul>
                    </div>
                    <table border="1" style="font-size: medium;">
                        <tr>
                            <th>日</th>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th>土</th>
                        </tr>
                    @foreach($infos['calendar'] as $k => $v)
                         <tr>
                            @for( $i = 0 ; $i <= 6; $i++)
                                @if (isset($v[$i]))
                                     <td class="subscription_day" val="{{ $v[$i] }}">
                                    {{ $v[$i] }}
                                    <p class="cnumber-flex">
                                        @php
                                            if (isset($kouhais) && isset($kouhais[date('Y-m-d', strtotime($infos['ym'].'-'.$v[$i]))])) {
                                                echo '<span class="calendar-number-02">';
                                                echo count($kouhais[date('Y-m-d', strtotime($infos['ym'].'-'.$v[$i]))]);
                                                echo '</span>';
                                            }
                                        @endphp
                                        @php
                                            if (isset($senpais) && isset($senpais[date('Y-m-d', strtotime($infos['ym'].'-'.$v[$i]))])) {
                                                echo '<span class="calendar-number-01">';
                                                echo count($senpais[date('Y-m-d', strtotime($infos['ym'].'-'.$v[$i]))]);
                                                echo '</span>';
                                            }
                                        @endphp
                                    </p>
                                @else
                                <td class="subscription_day" val="0">
                                @endif
                                </td>
                            @endfor
                         </tr>
                    @endforeach
                    </table>
                </div>
                <div class="base_txt mt20 mb20 ml20">
                    <p><span class="color01">●</span>…自分がコウハイとして受講する予約</p>
                    <p><span class="color02">●</span>…自分がセンパイとして実施する予約</p>
                </div>

            </div>
            <section>
                <div class="inner_box border-box" id="schedule_box" hidden>
                </div>
            </section>
        </form>
    </div>
    <!-- /contents -->
    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_js')
<script src="{{ asset('assets/vendor/moment/moment.js') }}"></script>
<script src="{{ asset('assets/vendor/moment/moment-with-locales.js') }}"></script>
<script>
    $(function() {

        $(document).ready(function () {
            moment.locale('ja');
            $('td[val="{{$infos['cur_day']}}"]').click();
         });

        $(".subscription_day").click(function(){

            var selDay = $(this).attr('val');
            if ( selDay <= 0)
                return;
            $.ajax({
                type: "post",
                url: " {{ route('user.talkroom.getScheduleInfo') }}",
                data: {
                    selYM: "{{ $infos['current'] }}",
                    selDay: selDay,
                    _token: "{{ csrf_token() }}",
                    },
                dataType: "json",
                success: function(data) {
                    var kouhais = data['kouhais'];
                    var senpais = data['senpais'];
                    var lb_sel_date = data['lb_sel_date'];

                    $("#schedule_box").html('');
                    if ( kouhais.length > 0 || senpais.length > 0 ) {
                        $html = '<h3 class="mb20">' + lb_sel_date + '</h3>';
                        $("#schedule_box").append($html);
                        $('#schedule_box').show();
                    }else{
                        $('#schedule_box').hide();
                    }

                    var storeImgurl = '{{asset("storage/avatar")}}';
                    var defaultImgurl = '{{asset("assets/user/img")}}';

                    if ( kouhais.length > 0 ) {
                        var hUrl = "{{route("user.talkroom.subscriptionLesson", ['menu_type'=> config('const.menu_type.kouhai'), 'schedule_id'=>0])}}";
                        hUrl =hUrl.substr(0, hUrl.length - 1);

                        for (i = 0; i < kouhais.length; i++) {
                            var user = kouhais[i].lesson_request.lesson.senpai;
                            var imgName = user.user_avatar;
                            var imgUrl;
                            if ( imgName )
                                imgUrl = storeImgurl + '/' + imgName;
                            else
                                imgUrl = defaultImgurl + '/icon_02.svg';

                            $html = '<div class="trainer-box"><div class="time-box bar01"><p class="en-color01">'
                                    + kouhais[i].lrs_start_time.substr(0,5)
                                    + '</p><p class="en-color01">'
                                    + kouhais[i].lrs_end_time.substr(0,5)
                                    +'</p></div>'
                                    +'<div class="trainer-name shadow-glay">'
                                    + '<a href="' + hUrl + kouhais[i].lrs_id + '">'
                                    +'<ul><li><img src="' + imgUrl + '"></li>'
                                    +'<li>' +user.name + '</li></ul>'
                                    +'<span class="icon_trainer icon_attendance">受講</span></a></div></div>';
                            $("#schedule_box").append($html);
                        }
                    }
                    if ( senpais.length > 0 ) {
                        var hUrl = "{{route("user.talkroom.subscriptionLesson", ['menu_type'=> config('const.menu_type.senpai'), 'schedule_id'=>0])}}";
                        hUrl =hUrl.substr(0, hUrl.length - 1);

                        for (i = 0; i < senpais.length; i++) {
                            var user = senpais[i].lesson_request.user;
                            var imgName = user.user_avatar;
                            var imgUrl;
                            if ( imgName )
                                imgUrl = storeImgurl + '/' + imgName;
                            else
                                imgUrl = defaultImgurl + '/icon_02.svg';

                            $html = '<div class="trainer-box"><div class="time-box bar02"><p class="en-color02">'
                                + senpais[i].lrs_start_time.substr(0,5)
                                + '</p><p class="en-color02">'
                                + senpais[i].lrs_end_time.substr(0,5)
                                +'</p></div>'
                                +'<div class="trainer-name shadow-glay">'
                                + '<a href="' + hUrl + senpais[i].lrs_id + '">'
                                +'<ul><li><img src="' + imgUrl + '"></li>'
                                +'<li>' +user.name + '</li></ul>'
                                +'<span class="icon_trainer icon_lesson">レッスン</span></a></div></div>';
                            $("#schedule_box").append($html);
                        }
                    }
                },
                error: function(){
                },
                complete: function() {
                }
            });

        });
    });
</script>
@endsection

