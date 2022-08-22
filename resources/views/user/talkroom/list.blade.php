@extends('user.layouts.app')

@section('title', 'トークルーム')

@php
    use App\Service\TalkroomService;
    use App\Service\CommonService;
@endphp

@section('content')

    @include('user.layouts.gnavi_under')

    <!-- ************************************************************************
本文
************************************************************************* -->

    <div id="contents" class="ultralong">

        <form action="./" method="post" name="form1" id="form1">

            <div class="top-menu_wrap">
                <div class="top-menu area_base">

                    <div class="display_area">
                        @php
                            $cur_ym= date('Y-m', time());
                        @endphp
                        <a href=" {{ route('user.talkroom.subscriptionCal', [$cur_ym]) }}">
                            <p class="icon_calendar">カレンダー</p>
                        </a>
                    </div>
                </div>
            </div>

            <div class="tabs info_wrap mt0">
                <input id="tab-01" type="radio"
                       name="tab_item" {{ $type==config('const.menu_type.kouhai') ? 'checked="checked"':''}}>
                <label class="tab_item" for="tab-01">コウハイメニュー</label>
                <input id="tab-02" type="radio"
                       name="tab_item" {{ $type==config('const.menu_type.senpai') ? 'checked="checked"':''}}>
                <label class="tab_item" for="tab-02">センパイメニュー</label>
                <!-- ********************************************************* -->

                <div class="tab_content" id="tab-01_content">
                    <div class="list-area">

                        <ul class="todo_list talkroom_list">
                            @foreach(TalkroomService::getTalkLists(config('const.menu_type.kouhai')) as $k => $v)
                                <li>
                                    <a href="{{ route('user.talkroom.talkData', [config('const.menu_type.kouhai'), $v['id']])}}">
                                        <div class="icon-area">
                                            <img
                                                src="{{ CommonService::getUserAvatarUrl($v['talkUserInfo']['user_avatar']) }}"
                                                alt="アイコン">
                                        </div>
                                        <div class="text-area">
                                            @php
                                                $unread_cnt =  count($v['unreadMessages']);
                                                $read_cnt =  count($v['readMessages']);
                                            @endphp
                                            <div class="text-small a-centre">
                                                <div
                                                    class="gray_txt">{{ $v['talkUserInfo']['name'] }}
                                                    <small>センパイ</small></div>
                                                @php

                                                    if ( $unread_cnt > 0 ) {
                                                          $unreadMsg = $v['unreadMessages'][$unread_cnt-1];
                                                          $date = App\Service\TimeDisplayService::TalkroomTimeDisplay($unreadMsg['created_at']);
                                                          echo '<div>'.$date.'</div>';
                                                     }
                                                @endphp
                                            </div>

                                            <p>
                                                @php
                                                    if ( $unread_cnt > 0 ) {
                                                        if ( $unreadMsg['msg_type'] == TalkroomService::Kouhai_LeftMsg ) {
                                                            echo $unreadMsg['message'];
                                                        } else if ( $unreadMsg['msg_type'] == TalkroomService::Kouhai_RightMsg ) {
                                                            echo "";
                                                        } else {
                                                            echo "メッセージが入ります";
                                                        }
                                                    } else if ( $read_cnt > 0 ) {
                                                        $readMsg = $v['readMessages'][$read_cnt-1];
                                                        if ( $readMsg['msg_type'] == TalkroomService::Kouhai_LeftMsg ) {
                                                            echo $readMsg['message'];
                                                        } else if ( $readMsg['msg_type'] == TalkroomService::Kouhai_RightMsg ) {
                                                            echo "";
                                                        } else {
                                                            echo "メッセージが入ります";
                                                        }
                                                    }
                                                @endphp
                                            </p>
                                        </div>
                                        @if ($unread_cnt > 0)
                                            <span class="midoku2"> {{ $unread_cnt }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>

                <!-- ********************************************************* -->

                <div class="tab_content showing" id="tab-02_content">

                    <div class="list-area">

                        <ul class="todo_list talkroom_list">
                            @foreach(TalkroomService::getTalkLists(config('const.menu_type.senpai')) as $k => $v)
                                <li>
                                    <a href="{{ route('user.talkroom.talkData', [config('const.menu_type.senpai'), $v['id']])}}">
                                        <div class="icon-area">
                                            <img
                                                src="{{ isset($v['talkUserInfo']) && $v['talkUserInfo']['user_avatar'] ? CommonService::getUserAvatarUrl($v['talkUserInfo']['user_avatar']) : '' }}"
                                                alt="アイコン">
                                        </div>
                                        <div class="text-area">
                                            @php
                                                $unread_cnt =  count($v['unreadMessages']);
                                                $read_cnt =  count($v['readMessages']);
                                            @endphp
                                            <div class="text-small a-centre">
                                                <div
                                                    class="gray_txt">{{ isset($v['talkUserInfo']) ? $v['talkUserInfo']['name'] : '' }}
                                                    <small>コウハイ</small></div>
                                                @php
                                                    if ( $unread_cnt > 0 ) {
                                                          $unreadMsg = $v['unreadMessages'][$unread_cnt-1];
                                                          $date = App\Service\TimeDisplayService::TalkroomTimeDisplay($unreadMsg['created_at']);
                                                          echo '<div>'.$date.'</div>';
                                                     }
                                                @endphp
                                            </div>
                                            <p class="info_ttl">
                                                @php
                                                    if ( $unread_cnt > 0 ) {
                                                        if ( $unreadMsg['msg_type'] == TalkroomService::Senpai_LeftMsg ) {
                                                            echo $unreadMsg['message'];
                                                        } else if ( $unreadMsg['msg_type'] == TalkroomService::Senpai_RightMsg ) {
                                                            echo "";
                                                        } else {
                                                            echo "メッセージが入ります";
                                                        }
                                                    } else if ( $read_cnt > 0 ) {
                                                        $readMsg = $v['readMessages'][$read_cnt-1];
                                                        if ( $readMsg['msg_type'] == TalkroomService::Senpai_LeftMsg ) {
                                                            echo $readMsg['message'];
                                                        } else if ( $readMsg['msg_type'] == TalkroomService::Senpai_RightMsg ) {
                                                            echo "";
                                                        } else {
                                                            echo "メッセージが入ります";
                                                        }
                                                    }
                                                @endphp
                                            </p>
                                        </div>
                                        @if ($unread_cnt > 0)
                                            <span class="midoku2"> {{ $unread_cnt }}</span>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

        </form>

    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection

@section('page_js')
    <script>
        @if ($type == config('const.menu_type.kouhai'))
        $("#tab-01_content").show();
        $("#tab-02_content").hide();
        @else
        $("#tab-01_content").hide();
        $("#tab-02_content").show();
        @endif
    </script>
@endsection


