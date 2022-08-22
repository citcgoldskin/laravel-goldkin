@extends('user.layouts.app')

@section('title', 'ブロック一覧')

@section('$page_id', 'mypage')

@section('content')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    @include('user.layouts.header_under')


    <div id="contents">

        <section>

            <div class="inner_box">
                <p class="form_txt">
                    現在、以下のユーザーをブロックしています。<br>
                    ブロックしたユーザーはあなたのサービスや投稿を閲覧できなくなり、予約や提案を送信することができなくなります。
                </p>
            </div>

            <div class="inner_box top_border">
                <h3>ユーザー</h3>
                <ul class="block_list">
                    <li>
                        <div><img src="{{ asset('assets/user/img/icon_photo_03.png') }}" alt=""></div>
                        <div>YUI</div>
                        <div>
                            <p class="btn_base btn_gray">
                                <a data-target="#msg-1" class="modal">ブロック解除</a>
                            </p>
                        </div>
                    </li>

                    <li>
                        <div><img src="{{ asset('assets/user/img/icon_photo_06.png') }}" alt=""></div>
                        <div>JIRO</div>
                        <div>
                            <p class="btn_base btn_gray">
                                <a data-target="#msg-2" class="modal">ブロック解除</a>
                            </p>
                        </div>
                    </li>

                </ul>


        </section>


    </div><!-- /contents -->

    <!-- 以下がモーダルで呼ばれる -->

    <!-- ※実際に構築される時は「id="msg-ユーザーの名前"」等にされると繰り返し条件でできると思います -->
    <div class="modalBox" id="msg-1">
        <div class="modalInner">
            <p>
                YUI<br>
                さんのブロックを解除しました
            </p>
        </div>
    </div>

    <div class="modalBox" id="msg-2">
        <div class="modalInner">
            <p>
                JIRO<br>
                さんのブロックを解除しました
            </p>
        </div>
    </div>

    <footer>
        @include('user.layouts.fnavi')
    </footer>
    @include('user.layouts.footer')

@endsection
