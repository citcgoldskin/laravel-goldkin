<header id="header_main_ttl">
    <div class="header_area">
        <nav class="global-nav">
            {{--@include('user.myaccount.notice')--}}
        </nav>
        <h1 class="logo"><img src="{{ asset('assets/admin/img/logo2.svg') }}" alt="センパイ"></h1>
        @if(Auth::guard('admin')->check() )
            <a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn-logout" id="btn_logout">ログアウト</a>
            <div class="hamburger" id="js-hamburger">
                <span class="hamburger__line hamburger__line--1"></span>
                <span class="hamburger__line hamburger__line--2"></span>
                <span class="hamburger__line hamburger__line--3"></span>
            </div>
        @endif
    </div>
    <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
    <div id="overlay">
        <div class="MDL_window_wrap">
            <nav class="main-menu">
                <ul>
                    <li class="label">最新情報</li>
                    <li class="link icon_form"><a href="{{ route('admin.top.index') }}">ダッシュボード</a></li>
                    <li class="label">ユーザー管理</li>
                    <li class="link icon_form"><a href="{{ route('admin.staff.index') }}">一覧情報</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.staff_confirm.index') }}">本人確認</a></li>
                    <li class="link icon_form"><a href="" class="menu_fraud">不正管理</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.inquiry.index') }}">お問い合わせ</a></li>
                    <li class="label">サービス管理</li>
                    <li class="link icon_form"><a href="{{ route('admin.lesson_examination.index') }}">出品審査</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.lesson_history_management.lesson') }}">レッスン履歴</a></li>
                    <li class="label">パトロール</li>
                    <li class="link icon_form"><a href="{{ route('admin.patrol.index') }}">ホーム</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.patrol.recruit') }}">掲示板</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.patrol.request_send') }}">リクエスト管理</a></li>
                    <li class="label">売上管理</li>
                    <li class="link icon_form"><a href="">売上確認</a></li>
                    <li class="link icon_form"><a href="">支払い確認</a></li>
                    <li class="label">設定</li>
                    <li class="link icon_form"><a href="{{ route('admin.freq.index') }}">よくある質問</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.master.index') }}">マスター管理</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.maintenance.index') }}">メンテナンス管理</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.news.index') }}">ニュース管理</a></li>
                    <li class="link icon_form"><a href="">クーポン</a></li>
                    <li class="link icon_form"><a href="">メール管理</a></li>
                    <li class="link icon_form"><a href="" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" id="btn_logout">ログアウト</a></li>
                    <li style="border: none;"></li>
                    <li style="border: none;"></li>
                </ul>
            </nav>
            <nav class="hide second-menu-1">
                @php
                    $not_read_report_cnt = \App\Service\AppealService::getUnreadAppeal();
                    $not_read_block_cnt = \App\Service\BlockService::getUnreadBlock();
                @endphp
                <ul>
                    <li class="label">不正管理</li>
                    <li class="link icon_form"><a href="{{ route('admin.fraud_report.index') }}">通報</a>@if($not_read_report_cnt)<span class="midoku">{{ $not_read_report_cnt }}</span>@endif</li>
                    <li class="link icon_form"><a href="{{ route('admin.fraud_block.index') }}">ブロック</a>@if($not_read_block_cnt)<span class="midoku">{{ $not_read_block_cnt }}</span>@endif</li>
                    <li class="link icon_form"><a href="{{ route('admin.fraud_piro.index') }}">ぴろしきまる</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.fraud_stop_lesson.index') }}">公開停止レッスン</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.fraud_stop_recruit.index') }}">公開停止投稿</a></li>
                    <li class="link icon_form"><a href="{{ route('admin.fraud_cancel_reserve.lesson') }}">取り消し予約一覧</a></li>

                </ul>
            </nav>
        </div>
    </div>
    {{--<div id="sub_menu_first" class="hide">
        <div class="sub-menu-1">
            <nav>
                <ul>
                    <li class="label">不正管理</li>
                    <li class="link icon_form"><a href="">通報</a></li>
                    <li class="link icon_form"><a href="">ブロック</a></li>
                    <li class="link icon_form"><a href="">ぴろしきまる</a></li>
                    <li class="link icon_form"><a href="">公開停止レッスン</a></li>
                    <li class="link icon_form"><a href="">公開停止投稿</a></li>
                    <li class="link icon_form"><a href="">取り消し予約一覧</a></li>

                </ul>
            </nav>
        </div>
    </div>--}}
</header>
<style>
    .header_area {
        align-items: center;
    }
    .header_area h1 {
        left: 60px;
    }
    .hamburger {
        left: unset;
        right: 0px;
        background: #fb7122;
        padding: 20px 19px;
    }
    .hamburger__line {
        left: 10px;
        width: 18px;
        background: white;
    }
    .hamburger__line--1 {
        top: 13px;
    }
    .hamburger__line--2 {
        top: 19px;
    }
    .hamburger__line--3 {
        width: 18px;
        top: 25px;
    }
    .btn-logout {
        position: absolute;
        right: 50px;
        font-size: 13px;
    }
    .second-menu-1 {
        top: 0px;
        width: 100%;
    }
    span.midoku {
        right: 40px;
        top: 12px;
    }
    @media screen and (max-width: 980px) {
        .header_area {
            /*margin-right: 0px;*/
        }
    }
</style>
<script>
    $(document).ready(function () {
        $(".hamburger").click(function () {
            $("#overlay").fadeToggle('slow');
            $(".hamburger").toggleClass("menu-close");
            if($(this).hasClass('menu-close')) {
                if ($('.main-menu').hasClass('hide')) {
                    $('.main-menu').removeClass('hide');
                }
                if (!$('.second-menu-1').hasClass('hide')) {
                    $('.second-menu-1').addClass('hide');
                }
            }

        });
        $(".menu_fraud").click(function (e) {
            e.preventDefault();
            /*$("#overlay").fadeToggle('slow');
            $(".hamburger").toggleClass("menu-close");*/
            $('.main-menu').addClass('hide');
            $('.second-menu-1').removeClass('hide');
        });
    });
</script>
