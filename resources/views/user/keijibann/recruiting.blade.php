@extends('user.layouts.app')

@section('title', '掲示板')

@section('content')

    @if(isset($page_from))
        @include('user.layouts.header_under')
    @else
        @include('user.layouts.gnavi_under')
    @endif

    <div id="contents">

        <div class="top-menu_wrap">

            @include('user.layouts.top_menu_location_list')
        </div>

        <section class="tab_area mb0">
            <div class="switch_tab three_tab">
                <div class="type_radio radio-01">
                    <input type="radio" name="onof-line" id="off-line" onclick="location.href='{{route("keijibann.list")}}'">
                    <label class="ok" for="off-line">すべて</label>
                </div>
                <div class="type_radio radio-02">
                    <input type="radio" name="onof-line" id="on-line-1" checked="checked" onclick="location.href='{{route("keijibann.recruiting")}}/1'">
                    <label class="ok" for="on-line-1">投稿管理</label>
                </div>
                <div class="type_radio radio-03">
                    <input type="radio" name="onof-line" id="on-line-2" onclick="location.href='{{route("keijibann.recruiting_proposal")}}/1'">
                    <label class="ok" for="on-line-2">提案管理</label>
                </div>
            </div>
        </section>

        <div class="tabs info_wrap three_tab mt0">
            <input id="tab-01" type="radio" name="tab_item" checked="checked" onclick="location.href='{{route("keijibann.recruiting")}}/1'">
            <label class="tab_item" for="tab-01">募集中</label>
            <input id="tab-02" type="radio" name="tab_item" onclick="location.href='{{route("keijibann.draft")}}/1'">
            <label class="tab_item" for="tab-02">下書き</label>
            <input id="tab-03" type="radio" name="tab_item" onclick="location.href='{{route("keijibann.past_contrib")}}/1'">
            <label class="tab_item" for="tab-03">過去投稿</label>


            <!-- ********************************************************* -->
            <div class="tab_content" id="tab-content">

                <section>
                    @foreach($recruits as $key => $val)
                    <div class="board_box">
                        <a href="{{route('keijibann.recruiting_detail') . "/" . $val['rc_id']}}">

                            <ul class="info_ttl_wrap">
                                <li>
                                    <img src="{{\App\Service\CommonService::getLessonClassIconUrl($val['cruitLesson']['class_icon'])}}" alt="">
                                </li>
                                <li>
                                    <p class="info_ttl">{{$val['rc_title']}}</p>
                                </li>
                            </ul>

                            <div class="about_detail">
                                <p class="money">{{ \App\Service\CommonService::getLessonMoneyRange($val['rc_wish_minmoney'], $val['rc_wish_maxmoney'], true) }}<small>{{$val['lesson_time']}}</small></p>
                                <p class="date_time">
                                    <span>{{$val['date']}}</span>
                                    <span>{{$val['start_end_time']}}</span>
                                </p>
                            </div>

                            <div class="about_attention">
                                <span class="attention_heart">{{$val['fav_count']}}</span>
                                <span class="attention_look">{{$val['rc_views']}}</span>
                                <span class="attention_update">{{$val['time_diff']}}前</span>
                            </div>
                        </a>
                        @if($val['pro_count'] > 0)
                            <span class="midoku">{{$val['pro_count']}}</span>
                        @endif
                    </div>
                    @endforeach

                </section>


            </div><!-- /tab_content -->

            <input type="hidden" id="pages" value="{{$pages}}">
        </div><!-- /tabs -->
    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

    <script>
        $(document).ready(function () {
            $('#tab-content').show();
        });

    var pageCount = parseInt($('#pages').val());
    var isLoading = false;
    var pageCurrent = 1;
    $(window).scroll(function(){
        if(pageCount <= 1) return;
        if(pageCount <= pageCurrent) return;

        var totalHeight = document.documentElement.scrollHeight - $("#f-navi").outerHeight(true);
        var clientHeight = document.documentElement.clientHeight;
        var scrollTop = (document.body && document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;

        if ((totalHeight == scrollTop + clientHeight) || (totalHeight <= scrollTop + clientHeight + 3)) {
            if (isLoading) return;
            getMoreRecruits();
        }
    });

    function getMoreRecruits()
    {
        pageCurrent++;
        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        form_data.append("mode", "ajax");
        form_data.append("page", pageCurrent);
        isLoading = true;
        $.ajax({
            type: "get",
            url: "{{route('keijibann.recruiting')}}" + "/2" + "?page=" + pageCurrent,
            dataType: 'json',
            contentType: false,
            processData : false,
            success : function(result)
            {
                var recruits = result.recruits;
                appendRecruits(recruits);
            }
        });
    }

    function appendRecruits(data)
    {
        var html = "";
        for(var key in data)
        {
            var recruit = data[key];
            html += '<div class="board_box">';
            html += "<a href='" + "{{route('keijibann.recruiting_detail')}}" + "/" + recruit.rc_id + "'>";
            html += '<ul class="info_ttl_wrap"><li>';
            html += "<img src='" + "{{ asset('storage/class_icon')}}" + "/" + recruit.cruit_lesson.class_icon  + "' alt=''>";
            html += "</li><li><p class='info_ttl'>";
            html += recruit.rc_title;
            html += "</p></li></ul><div class='about_detail'>";
            html += "<p class='money'>" + recruit.rc_wish_minmoney + "円～" + recruit.rc_wish_maxmoney + "円<small>" + recruit.lesson_time + "</small></p>";
            html += '<p class="date_time">';
            html += "<span>" + recruit.date + "</span>";
            html += "<span>" + recruit.start_end_time + "</span>";
            html += '</p></div><div class="about_attention">';
            html += "<span class='attention_heart'>" + recruit.fav_count + "</span>";
            html += "<span class='attention_look'>" + recruit.rc_views + "</span>";
            html += "<span class='attention_update'>" + recruit.time_diff + "前</span>";
            html += "</div></a>";
            if(recruit.pro_count > 0)
            {
                html += '<span class="midoku">' + recruit.pro_count + '</span>';
            }
            html += "</div>";
        }
        $('#tab-content section').append(html);
        isLoading = false;
    }

    </script>

@endsection

