@extends('user.layouts.app')

@section('content')
<!-- ************************************************************************
本文
************************************************************************* -->



<div id="completion_wrap" class="no_modal">
    <div class="modal_body completion deco_check">

        <div class="modal_inner">
            <h2 class="modal_ttl_02">
                会員登録が完了しました！<br>
                センパイで新しい習慣を<br>
                はじめましょう！
            </h2>
        </div>
    </div>

    <div class="bottom-posi">
        <div class="button-area w100">
            <div class="btn_base btn_orange shadow"><a href="{{ route('user.myaccount.edit_profile.form') }}">プロフィールの入力へ</a></div>
        </div>
    </div>
</div>


@endsection
