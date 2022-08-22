<!-- *******************************************************
フッター（トークルーム）
******************************************************** -->

<div id="footer_talkroom">
    <div class="talkroom_menu">
        <div class="menu">
            <input id="f_talk-check1" class="acd-check" type="checkbox">
            <label class="acd-label" for="f_talk-check1">
                <p>メニュー</p>
            </label>
            <div class="acd-content talkroom_content">

                @if ($menu_type == config('const.menu_type.senpai'))
                        <ul class="talkroom_f-submenu">
                            @foreach( $reserves as $k => $v)
                                <li class="cancel_application">
                                    <div class="sub_inner">
                                        <p>レッスンのキャンセル申請は<br class="pcNone">こちらから</p>
                                        <p>※キャンセル規定が適用されます</p>
                                        <div class="button-area">
                                            <div class="btn_base btn_cancel">
                                                <a href=" {{ route('user.myaccount.master_lesson_request', ['schedule_id'=>$v['lrs_id']]) }} ">キャンセルする</a>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @break
                            @endforeach

                            <li class="task_area">
                                <div>
                                    <a href=" {{ route('user.syutupinn.schedule') }}">
                                        <p class="task_icon task_reserved">出勤カレンダー</p>
                                    </a>
                                </div>
                                <div>
                                    <a href="{{ route('user.syutupinn.request') }}">
                                        <p class="task_icon task_request">リクエスト対応</p>
                                    </a>
                                </div>
                            </li>
                        </ul>
                @elseif ($menu_type == config('const.menu_type.kouhai'))

                    <ul class="talkroom_f-submenu">
                        <li class="cancel_application">
                            <div class="sub_inner">
                                <p>レッスンの確認・変更・キャンセル申請はこちらから</p>
                                <p>※キャンセル規定が適用されます</p>
                                <div class="button-area long">
                                    <div class="btn_base btn_cancel">
                                        <a href=" {{ route('user.lesson.change') }}">確認・変更・キャンセル</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="task_area">
                            <div>
                                <a href="#">
                                    <p class="task_icon task_reserved">レッスンを予約</p>
                                </a>
                            </div>
                            <div>
                                <a href="#">
                                    <p class="task_icon task_request">出勤リクエスト</p>
                                </a>
                            </div>
                        </li>
                    </ul>
                @endif
            </div>
        </div>
        <div class="send_area">
            <div class="input-text">
                <textarea placeholder="メッセージを入力" id="textarea" class="message_text"></textarea>
            </div>
            <div class="send_btn">
                <a class="send">
                    <img src="{{ asset('assets/user/img/send_btn.svg') }}" alt="送信ボタン">
                </a>
            </div>
        </div>
    </div>
</div>




