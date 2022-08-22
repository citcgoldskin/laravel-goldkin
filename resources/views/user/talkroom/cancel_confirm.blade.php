@extends('user.layouts.app')
@section('title', 'キャンセル内容の確認')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_under')

    <div id="contents">

        <form action="" method="post" name="form1" id="form1" target="senddata">

            <section class="pb0">

                <div class="inner_box">
                    <ul class="list_area">
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>19</strong>日（火）　<strong>18:15~19:00</strong>
                        </li>
                    </ul>
                </div>

            </section>

            <section>

                <div class="inner_box">
                    <div class="modal_inner">
                        <h4 id="circle-orange_ttl" class="shadow-orange_02">!</h4>
                        <h2 class="modal_ttl_03">
                            キャンセルしてよろしいですか？
                        </h2>

                    </div>

                </div>


                <div class="button-area pt30">
                    <div class="btn_base btn_orange shadow">
                        <button type="submit"  class="btn-send2">キャンセル確定</button>
                    </div>
                    <div class="btn_base btn_pale-gray shadow-glay">
                        <a href="D-29_30.php">キャンセルしない</a>
                    </div>
                </div>


            </section>
        </form>
    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
    <div class="modal-wrap completion_wrap">

        <div id="modal-cancel_confirm" class="modal-content ok">
            <div class="modal_body completion ok">
                <div class="modal_inner">

                    <h2 class="modal_ttl">
                        リクエストを<br>
                        キャンセルしました
                    </h2>

                    <div class="modal_txt">
                        <p>
                            またのご利用を<br>
                            お待ちしております。
                        </p>
                    </div>
                </div>
            </div>


            <div class="button-area type_under">
                <div class="btn_base btn_ok"><a href="D-1.php">OK</a></div>
            </div>

        </div><!-- /modal-content -->



    </div>
    <div id="modal-overlay2" style="display: none;"></div>
    <!-- モーダル部分 / ここまで ************************************************* -->
    @include('user.layouts.modal')
    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

