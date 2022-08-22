<div class="under-title">
    @if(isset($no_action) && $no_action)
    @else
        @if(isset($action_url) && $action_url)
            <button type="button" onclick="location.href='{{ $action_url }}'">＜</button>
        @else
            <button type="button" onclick="history.back()">＜</button>
        @endif
    @endif
    <label class="page-title al-c" style="width: 100%;">@yield('title')</label>
</div>
