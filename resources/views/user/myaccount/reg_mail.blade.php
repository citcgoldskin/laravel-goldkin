@extends('user.layouts.app')

@section('title', '新規登録')

@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <section>

        {{ Form::open(["route"=>"user.register.email", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            <ul class="form_area">
                <li>
                    <h3>メールアドレス登録</h3>
                    <div class="form_wrap shadow-glay">
                        <input type="text" value="{{ old('email') }}" placeholder="" name="email">
                        @error('email')
                        <p class="error_text"><strong>{{ $message }}</strong></p>
                        @enderror
                        @if ( isset($errMsg))
                            <p class="error_text"><strong>{{ $errMsg }}</strong></p>
                        @endif
                    </div>
                </li>
            </ul>



            <div class="button-area mt30 w100">
                <div class="btn_base btn_orange shadow ">
                    <button type="submit" class="btn-send">登録する</button>
                </div>
            </div>
        {{ Form::close() }}
    </section>


</div><!-- /contents-->
@endsection
