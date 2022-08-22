@include('user.layouts.app')

<!--main_visual A-21-->
<div id="modal_wrap" class="modal_basic no_modal">

    <div class="modal_body">
        <div class="modal_inner">
            <h4 id="circle-orange_ttl">!</h4>
            <h2 class="modal_ttl">
                @if($valid_code == 'no_self_conf')
                    この先輩のレッスンを<br>
                    予約するには<br>
                    本人確認が必要です。
                @elseif($valid_code == 'self_block')
                    あなたはこの先輩をブロックしています。<br>
                    予約するには、<br>
                    ブロックを解除してください。
                @elseif($valid_code == 'other_block')
                    この先輩はあなたを<br>
                    ブロックしているので、<br>
                    予約できません。
                @endif
            </h2>

        </div>


        <div class="button-area type_under">
            <div class="btn_base btn_orange shadow">
                @if($valid_code == 'no_self_conf')
                    <a href="{{route('user.myaccount.confirm')}}">本人確認を行う</a>
                @elseif($valid_code == 'self_block')
                    <a href="{{route('user.myaccount.block_outline')}}">ブロック一覧へ</a>
                @elseif($valid_code == 'other_block')
                    <a href="{{route('user.lesson.detail', ['lesson_id' => $lesson_id])}}">戻る</a>
                @endif
            </div>
        </div>

    </div>
</div>

<!--main_visual A-21 end-->

@include('user.layouts.footer')

