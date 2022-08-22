@extends('user.layouts.app')
@section('title', 'お気に入り')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents" class="short">

    <div class="tabs info_wrap three_tab second_grade mt0">
        <input id="tab-01" type="radio" name="tab_item" @if ( $type == config('const.favourite_type.lesson') ) checked @endif >
        <label class="tab_item" for="tab-01">
            <span class="f16">{{ $counts[config('const.favourite_type.lesson')] }}</span>
            <span>レッスン</span>
        </label>
        <input id="tab-02" type="radio" name="tab_item" @if ( $type == config('const.favourite_type.follow') ) checked @endif >
        <label class="tab_item" for="tab-02">
            <span class="f16">{{ $counts[config('const.favourite_type.follow')] }}</span>
            <span>フォロー</span>
        </label>
        <input id="tab-03" type="radio" name="tab_item" @if ( $type == config('const.favourite_type.follower') ) checked @endif >
        <label class="tab_item" for="tab-03">
            <span class="f16">{{ $counts[config('const.favourite_type.follower')] }}</span>
            <span>フォロワー</span>
        </label>



        <!-- ********************************************************* -->

        <div class="tab_content" id="tab-01_content">
            <ul class="lesson_list_wrap">
                @foreach( $fav_lessons as $key => $value)
                    <li class="lesson_box">
                        @if ( isset($value['lesson']) )
                        <a href="{{ route('user.lesson.lesson_view', ['lesson_id' => $value['lesson']['lesson_id']]) }}">
                            <div class="img-box">
                                @php
                                    $lesson_image = NULL;
                                    if ( isset($value['lesson']['lesson_image']) && is_array(unserialize($value['lesson']['lesson_image']))) {
                                        $lesson_image = unserialize($value['lesson']['lesson_image'])[0];
                                    }
                                @endphp
                                <img src="{{ \App\Service\CommonService::getLessonImgUrl($lesson_image) }}" alt="ウォーキング画像">
                                <p>{{ $value['lesson']['lesson_class']['class_name'] }}</p>
                            </div>
                            <div class="lesson_info_box">
                                <p class="lesson_name ttl-block">{{ $value['lesson']['lesson_title'] }}</p>
                                <p class="lesson_price">{{ \App\Service\CommonService::showFormatNum($value['lesson']['lesson_30min_fees']) }}<span>円 / 30分〜</span></p>
                                <div class="teacher_name">
                                    <div><img src="{{ asset('storage/avatar/'.$value['lesson']['senpai']['user_avatar']) }}" alt=""></div>
                                    <div>{{ $value['lesson']['senpai']['name'] }}
                                        （{{ App\Service\CommonService::getAge($value['lesson']['senpai']['user_birthday']) }}）</div>
                                </div>
                            </div>
                        </a>
                        @endif
                    </li>
                @endforeach
            </ul>
            {{ $fav_lessons->appends([
                'type' => config('const.favourite_type.lesson'),
                'follow' => $fav_follows->currentPage(),
                'follower' => $fav_followers->currentPage(),
            ])->links('vendor.pagination.senpai-pagination') }}
        </div>

        <div class="tab_content" id="tab-02_content">
            <div class="list-area">
                <ul class="todo_list talkroom_list" style="margin-bottom: 0px">
                    @foreach($fav_follows as $key1 => $value1)
                        @if ( isset($value1['followSenpai']) )
                        <li>
                            <a href="{{ route('user.myaccount.profile', ['user_id' => $value1['followSenpai']['id']]) }}">
                                <div class="icon-area">
                                    <img src="{{ \App\Service\CommonService::getUserAvatarUrl($value1['followSenpai']['user_avatar']) }}" alt="アイコン">
                                </div>
                                <div class="text-area">
                                    <div class="text-small a-centre">
                                        <div class="gray_txt">{{ $value1['followSenpai']['name'] }}<small>センパイ</small></div>
                                    </div>
                                    <p>{{ $value1['followSenpai']['user_intro'] }}</p>
                                </div>
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            {{ $fav_follows->appends([
            'type' => config('const.favourite_type.follow'),
            'lesson' => $fav_lessons->currentPage(),
            'follower' => $fav_followers->currentPage(),
            ])->links('vendor.pagination.senpai-pagination') }}
        </div>
        <div class="tab_content" id="tab-03_content">
            <div class="list-area">
                <ul class="todo_list talkroom_list" style="margin-bottom: 0px">
                    @foreach($fav_followers as $key2 => $value2)
                        @if ( isset($value2['user']) )
                        <li>
                            <a href="{{ route('user.myaccount.profile', ['user_id' => $value2['followSenpai']['id']]) }}">
                                <div class="icon-area">
                                    <img src="{{ \App\Service\CommonService::getUserAvatarUrl($value2['user']['user_avatar']) }}" alt="アイコン">
                                </div>
                                <div class="text-area">
                                    <div class="text-small a-centre">
                                        <div class="gray_txt">{{ $value2['user']['name'] }}<small>コウハイ</small></div>
                                    </div>
                                    <p>{{ $value2['user']['user_intro'] }}</p>
                                </div>
                            </a>
                        </li>
                        @endif
                    @endforeach
                </ul>
            </div>
            {{ $fav_followers->appends([
            'type' => config('const.favourite_type.follower'),
            'lesson' => $fav_lessons->currentPage(),
            'follow' => $fav_follows->currentPage(),
            ])->links('vendor.pagination.senpai-pagination') }}
        </div>


    </div><!-- /tabs -->

</div><!-- /contents -->


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection

@section('page_js')
    <script>
        $(document).ready(function () {
            @if ( $type == config('const.favourite_type.lesson') )
            $("#tab-01_content").show();
            $("#tab-02_content").hide();
            $("#tab-03_content").hide();
            @elseif ( $type == config('const.favourite_type.follow') )
            $("#tab-01_content").hide();
            $("#tab-02_content").show();
            $("#tab-03_content").hide();
            @else
            $("#tab-01_content").hide();
            $("#tab-02_content").hide();
            $("#tab-03_content").show();
            @endif
        })
    </script>
@endsection
