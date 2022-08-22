@extends('user.layouts.app')
@section('content')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="completion_wrap" class="no_modal">
    <div class="modal_body completion deco_mail">
        <div class="modal_inner">
            <h2 class="modal_ttl_02">
                メールの送信を完了しました。<br>
                URLから登録を行って下さい。
            </h2>
        </div>
    </div>

    <div class="bottom-posi">
        <div class="button-area">
            <div class="btn_base btn_ok"><a href="{{ route('user.login') }}">OK</a></div>
        </div>
    </div>
</div>
@endsection
