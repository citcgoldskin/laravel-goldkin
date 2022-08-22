@extends('user.layouts.app')
@section('title', 'クーポンBOX')

@section('content')
@include('user.layouts.header_under')
<div id="contents">
    <section id="coupon">

      {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            <ul class="form_area">
                <li>
                    <div class="form_wrap">
                        <input type="text" value="" placeholder="クーポンコードの入力">
                    </div>
                </li>
            </ul>

            <div class="no_coupon">
                <p><img src="{{ asset('assets/user/img/img_coupon.svg') }}" alt=""></p>
                <p>現在持っているクーポンはありません</p>
            </div>

     {{ Form::close() }}


    </section>

</div><!-- /contents -->

<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

