@extends('user.layouts.app')

@section('content')

    <div id="contents">
        <div id="startup">
            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">予約を確定しました。</h2>

                    <div class="modal_txt">
                        <p>レッスンのことでわからない事があれば<br>トークルームで直接質問する事ができます。</p>
                    </div>
                </div>
            </div>


            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <a href="{{route('user.talkroom.list')}}">トークルームを確認する</a>
                </div>
                <div class="btn_base btn_white shadow">
                    <a href="{{route('home')}}">ホームへ戻る</a>
                </div>
            </div>


        </div>

    </div><!-- /contents -->

@endsection

