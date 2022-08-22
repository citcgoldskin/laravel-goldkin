<header id="header_under_ttl">
    <div class="header_area">
        @if(isset($sub_title))
            <h1>@yield('title')</h1>
        @else
            <h1>@yield('title')<span>@yield('sub_title')</span></h1>
        @endif

        @if(isset($page_type) && $page_type == 'password_reset')
        @else
            <div class="h-icon">
                <p>
                    <button type="button" onclick="javascript:window.history.back(-1);return false;">
                        <img src="{{ asset('assets/user/img/arrow_left2.svg') }}" alt="戻る">
                    </button>
                </p>
            </div>
        @endif

        @if(isset($page_from))
            <div class="h-icon icon_bell">
                <p>
                    <button type="button" onclick="location.href='{{ route('user.notice') }}'"><img
                            src="{{ asset('assets/user/img/icon_01.svg') }}" alt="ベルアイコン">
                        <span class="midoku" id="notice" style="display: none"></span>
                    </button>
                </p>
            </div>
        @endif
    </div>
</header>
