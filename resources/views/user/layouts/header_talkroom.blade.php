
<!-- *****************************************************************************
 トークルームのヘッダー
 ****************************************************************************** -->

<header id="header_main_ttl" class="talkroom_header">
    <div class="header_area">

        <div class="h-icon">
            <p>
                <button type="button" onclick="javascript:window.history.back(-1);return false;">
                    <img src="{{ asset('assets/user/img/arrow_left2.svg') }}" alt="戻る">
                </button>
            </p>
            <span class="midoku" id="talk_new" style="display: none"></span>
        </div>

        <h1 class="talkroom_ttl">
            <div class="icon_s30">
                <a href="{{ route('user.myaccount.profile', Auth::user()->id) }}">
                    <img src=" {{ \App\Service\CommonService::getUserAvatarUrl($talk_user_info['user_avatar']) }} " alt="アイコン">
                </a>
            </div>
            <div>
                @php
                  if (isset($talk_user_info)) {
                    echo $talk_user_info['name'];
                  }
                @endphp
                @if ($menu_type == config('const.menu_type.kouhai'))
                <small>センパイ</small>
                @else
                <small>コウハイ</small>
                @endif
            </div>
        </h1>

        <nav class="global-nav">

        </nav>
        <div class="hamburger" onclick="location.href='{{ route('user.talkroom.setting',
                        [
                            'type'=> $menu_type,
                            'from_user_id' => $talk_user_info['id']
                        ])
                      }}'">
            <a>
                <span class="hamburger__line hamburger__line--1"></span>
                <span class="hamburger__line hamburger__line--2"></span>
                <span class="hamburger__line hamburger__line--3"></span>
            </a>
        </div>

    </div>
</header>
<!--header_end-->
