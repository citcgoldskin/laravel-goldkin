<!-- *****************************************************************************
 グローバルメニュー
 ****************************************************************************** -->

<header id="header_main_ttl">
    <div class="header_area">
        <nav class="global-nav">
        </nav>
        @if(Auth::guard('web')->check())
            <div class="hamburger" id="js-hamburger" onclick="location.href='{{ route('user.todo') }}'">
                    <span class="midoku midoku_open" id="sale" style="display: none"></span>
                    <span class="hamburger__line hamburger__line--1"></span>
                    <span class="hamburger__line hamburger__line--2"></span>
                    <span class="hamburger__line hamburger__line--3"></span>
            </div>
        @endif
        <div class="black-bg" id="js-black-bg"></div>

        <h1 class="logo"><img src="{{ asset('assets/user/img/logo2.svg') }}" alt="センパイ"></h1>

        @if(Auth::guard('web')->check())
            <div class="h-icon icon_bell">
                <p>
                    <button type="button" onclick="location.href='{{ route('user.notice') }}'"><img src="{{ asset('assets/user/img/icon_01.svg') }}" alt="ベルアイコン">
                        <span class="midoku" id="notice" style="display: none"></span>
                    </button>
                </p>
            </div>
        @endif
    </div>
</header>
