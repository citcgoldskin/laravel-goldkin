<!-- *******************************************************
フッターメニュー
******************************************************** -->

<div id="f-navi">
    <div class="footer_link">
        <ul>
            <li>
                <a href="{{ route('home') }}">
                    <p class="menu-home">ホーム</p>
                </a>
            </li>
            <li>
                <a href="{{route('keijibann.list')}}">
                    <p class="menu-board">掲示板</p>
                </a>
            </li>
            <li class="navi-listing">
                <a href="{{route('user.syutupinn.lesson_list')}}">
                    <p class="menu-listing">センパイ出品</p>
                </a>
            </li>
            <li>
                <a href="{{ route('user.talkroom.list') }}">
                    <p class="menu-talkroom">トークルーム</p>
                    <span id="talk_new" style="display: none"></span>
                </a>
            </li>
            <li>
                <a href="{{ route('user.myaccount.index') }}">
                    <p class="menu-mypage">マイページ</p>
                </a>
            </li>
        </ul>
    </div>
</div>
