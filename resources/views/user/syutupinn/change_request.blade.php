@extends('user.layouts.app')

@section('title', '変更申請')

@section('content')
    @include('user.layouts.header_under')
    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">
        <form action="C-10-3.php" method="post" name="form1" id="form1">
            <section>
                <h2 class="pb20 f16">変更申請を受け付けます</h2>
                <p class="form_txt pb30"> ご入力いただいた評価は匿名で他のユーザーと平均化されて表示されます。<br>また、ほかのユーザーにセンパイをオススメする際の基準に利用させて頂きます。</p>



                <div class="inner_box">

                    <h3>受付を継続する</h3>
                    <div class="white_box">
                        <p class="f13 pb10">審査完了まで既存のレッスン内容で受付を継続します。</p>
                        <p class="f13"><small>※審査完了までに出されたリクエストは既存のレッスン内容で行うものとします。</small></p>
                    </div>

                </div>

                <div class="inner_box">

                    <h3>受付を停止する</h3>
                    <div class="white_box">
                        <p class="f13 pb10">審査完了まで当該レッスンのリクエストを停止し、審査が完了した段階で新しいレッスンとして公開されます。</p>
                    </div>

                </div>


            </section>

            <section id="button-area">

                <div class="inner_box">

                    <div class="button-area">
                        <div class="btn_base btn_orange shadow">
                            <button type="button" class="modal-syncer button-link" data-target="modall_keizoku">受付を継続する</button>
                        </div>

                        <div class="btn_base btn_pale-gray shadow">
                            <button type="button" class="modal-syncer button-link" data-target="modall_teishi">受付を停止する</button>
                        </div>

                        <div class="btn_base btn_white shadow">
                            <button type="button" onclick="location.href='<?php /*echo $_SERVER['HTTP_REFERER']; */?>'">入力画面に戻る</button>
                        </div>
                    </div>
                </div>
            </section>
        </form>
    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap completion_wrap">
        <div id="modall_keizoku" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        受付を継続します。<br>
                        よろしいですか?
                    </h2>
                    <p class="modal_txt">
                        当該のレッスンは変更前の条件で公開を継続されます。
                    </p>

                </div>
            </div>



            <div class="button-area">
                <div class="btn_base btn_orange">
                    <button type="submit">OK</button>
                </div>

                <div class="btn_base btn_gray-line">
                    <a id="modal-close" class="button-link">キャンセル</a>
                </div>

            </div>


        </div><!-- /modal-content -->

    </div>

    <div class="modal-wrap completion_wrap">
        <div id="modall_teishi" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        受付を停止します。<br>
                        よろしいですか?
                    </h2>
                    <p class="modal_txt">
                        当該のレッスンは非公開とされます。
                    </p>

                </div>
            </div>



            <div class="button-area">
                <div class="btn_base btn_orange">
                    <button type="submit">OK</button>
                </div>

                <div class="btn_base btn_gray-line">
                    <a id="modal-close" class="button-link">キャンセル</a>
                </div>

            </div>


        </div><!-- /modal-content -->

    </div>

    <!-- モーダル部分 / ここまで ************************************************* -->
    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection
