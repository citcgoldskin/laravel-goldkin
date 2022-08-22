@extends('user.layouts.app')

@section('title', 'スプラッシュ')

@section('content')
<!-- ************************************************************************
本文
************************************************************************* -->

<!--startup-->
<div id="startup" class="top_page">
    <div class="top_wrap">
        <div class="logo"><img src="{{asset('assets/user/img/logo.svg')}}" alt="センパイロゴ"></div>
    </div>
</div>
<!--startup end-->


<script>
    $(document).ready(function() {
        var delay = 3000
        var timeout = setTimeout(function () {
            window.location = "{{ route('lesson_area') }}";
        }, delay);
    });

    $(document).click(function () {
        window.location = "{{ route('lesson_area') }}";
    });
</script>

@endsection

