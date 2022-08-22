@extends('user.layouts.app')

@section('content')
    <div class="modal">
    <!--main_visual A-21-->
    <div id="completion_wrap" class="no_modal">
        <div class="modal_body completion">
            <div class="modal_inner">

                <h2 class="modal_ttl">
                    変更リクエストを<br>
                    承認しました
                </h2>

            </div>

        </div>


        <div class="button-area">
            <div class="btn_base btn_white shadow">
                <a href="{{ route('user.talkroom.talkData', ['menu_type' => config('const.menu_type.senpai'), 'room_id' => $talkroom_id]) }}">トークルームへ</a>
            </div>
        </div>


    </div>
    <!--main_visual A-21 end-->

@endsection

