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
                    <input type="radio" name="onof-line" id="on-line-1" onclick="location.href='{{route("keijibann.recruiting")}}/1'">
                    <label class="ok" for="on-line-1">投稿管理</label>
                </div>
                <div class="type_radio radio-03">
                    <input type="radio" name="onof-line" id="on-line-2" checked="checked" onclick="location.href='{{route("keijibann.recruiting_proposal")}}/1'">
                    <label class="ok" for="on-line-2">提案管理</label>
                </div>
            </div>
        </section>


        <section id="section_proposal">

            @foreach($proposals as $key =>$val)
            <div class="board_box">
                <a href="{{route('keijibann.recruiting_prop_detail') . '/' . $val['pro_id'] }}">
                    <ul class="info_ttl_wrap">
                        <li>
                            <img src="{{App\Service\CommonService::getLessonClassIconUrl($val['recruit']['cruitLesson']['class_icon'])}}" alt="">
                        </li>
                        <li>
                            <p class="info_ttl">{{$val['recruit']['rc_title']}}</p>
                        </li>
                    </ul>

                    <div class="about_detail_02">
                        <p class="date_time">
                            募集期限：
                            <span>{{$val['recruit']['date']}}</span>
                        </p>
                        <p class="money">予算　　：{{ \App\Service\CommonService::getLessonMoneyRange($val['recruit']['rc_wish_minmoney'], $val['recruit']['rc_wish_maxmoney']) }}<small>{{$val['recruit']['lesson_time']}}</small></p>
                    </div>

                    <ul class="teacher_info_02 photo_small">
                        <li><img src="{{\App\Service\CommonService::getUserAvatarUrl($val['recruit']['cruitUser']['user_avatar'])}}" alt=""></li>
                        <li class="about_teacher">
                            <div class="profile_name"><p>{{$val['recruit']['cruitUser']['name']}}<span>（{{$val['recruit']['age']}}）{{$val['recruit']['sex']}}</span></p></div>
                        </li>
                    </ul>

                    <div class="about_detail teian_naiyou">
                        <h4>提案内容</h4>
                        <p class="buy_limit">
                            購入期限　　：
                            <span>{{$val['pro_date']}}</span>
                            <span>{{$val['pro_time']}}</span>
                        </p>
                        <p class="money">提案額　　　：{{\App\Service\CommonService::showFormatNum($val['pro_money'])}}円</p>
                        <p class="date_time">
                            レッスン日時：
                            <span>{{ \Carbon\Carbon::parse($val->recruit->rc_date)->format('n月j日') }}</span>
                            <span>{{ \Carbon\Carbon::parse($val['pro_start_time'])->format('H:i') }}～{{ \Carbon\Carbon::parse($val['pro_end_time'])->format('H:i') }}</span>
                        </p>
                    </div>
                </a>
                <span class="status-pink">{{$val['pro_state_str']}}</span>
            </div>

            @endforeach

        </section>

    </div><!-- /contents -->

    <input type="hidden" id="pages" value="{{$pages}}">

    <footer>
        @include('user.layouts.fnavi')
    </footer>

    <script>
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
                getMoreProposals();
            }
        });

        function getMoreProposals()
        {
            pageCurrent++;
            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("mode", "ajax");
            form_data.append("page", pageCurrent);
            isLoading = true;
            $.ajax({
                type: "get",
                url: "{{route('keijibann.recruiting_proposal')}}" + "/2" + "?page=" + pageCurrent,
                dataType: 'json',
                contentType: false,
                processData : false,
                success : function(result)
                {
                    var proposals = result.proposals;
                    appendProposals(proposals.data);
                }
            });
        }

        function appendProposals(data)
        {
            var html = "";
            for(var key in data)
            {
                var proposal = data[key];
                html += '<div class="board_box">';
                html += "<a href='" + "{{route('keijibann.recruiting_prop_detail')}}" + "/" + proposal.pro_id + "'>";
                html += '<ul class="info_ttl_wrap"><li>';
                html += "<img src='" + "{{ asset('storage/class_icon')}}" + "/" + proposal.recruit.cruit_lesson.class_icon  + "' alt=''>";
                html += "</li><li><p class='info_ttl'>";
                html += proposal.recruit.rc_title;
                html += "</p></li></ul><div class='about_detail_02'>";
                html += '<p class="date_time">募集期限：';
                html += "<span>" + proposal.recruit.date  + "</span></p>";
                html += "<p class='money'>" + proposal.recruit.rc_wish_minmoney + "円～" + proposal.recruit.rc_wish_maxmoney + "円<small>" + proposal.recruit.lesson_time + "</small></p>";
                html += "</div><ul class='teacher_info_02 photo_small'>";
                html += "<li><img src='" + "{{ asset('storage') }}" + "/avatar/" + proposal.recruit.cruit_user.user_avatar + "' alt=''></li>";
                html += "<li class='about_teacher'>";
                html += "<div class='profile_name'><p>" + proposal.recruit.cruit_user.name + "<span>（" + proposal.recruit.age + "）" + proposal.recruit.sex + "</span></p></div>";
                html += "</li></ul><div class='about_detail teian_naiyou'><h4>提案内容</h4>";
                html += "<p class='buy_limit'>購入期限　　：";
                html += "<span>" + proposal.pro_date + "</span>";
                html += "<span>" + proposal.pro_time + "</span></p>";
                html += "<p class='money'>提案額　　　：" + proposal.pro_money + "円</p>";
                html += "<p class='date_time'>レッスン日時：";
                html += "<span>1月29日 (未定)</span><span>15:00～18:00 (未定)</span></p></div></a><span class='status-pink'>提案中</span></div>";
            }
            $('#section_proposal').append(html);
            isLoading = false;
        }

    </script>

@endsection

