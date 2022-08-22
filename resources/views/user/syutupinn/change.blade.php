@extends('user.layouts.app')

@section('title', '変更内容の確認')
@include('user.layouts.header')
@include('user.layouts.header_under')
@section('content')
    <!-- ************************************************************************
本文
************************************************************************* -->

    <div id="contents">

        <form action="" method="post" name="form1" id="form1" target="senddata">

            <section>
                <div class="inner_box">
                    <h3 class="icon_plus">登録する出勤</h3>
                    <ul class="list_area">
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>19</strong>日（火）　<strong>18:15~19:00</strong>
                        </li>
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>19</strong>日（火）　<strong>19:30~20:45</strong>
                        </li>
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>20</strong>日（水）　<strong>17:30~18:00</strong>
                        </li>
                    </ul>
                </div>

                <div class="inner_box">
                    <h3 class="icon_delete">削除する出勤</h3>
                    <ul class="list_area">
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>18</strong>日（月）　<strong>19:00~19:45</strong>
                        </li>
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>19</strong>日（火）　<strong>19:00~19:30</strong>
                        </li>
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>20</strong>日（水）　<strong>16:00~16:45</strong>
                        </li>
                        <li>
                            <strong>2021</strong>年
                            <strong>1</strong>月
                            <strong>21</strong>日（木）　<strong>20:15~21:00</strong>
                        </li>
                    </ul>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="submit" class="btn-send">変更を確定する</button>
                    </div>
                </div>

                <!-- モーダル部分 *********************************************************** -->
                <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
                <div class="modal-wrap completion_wrap">
                    <div id="modal-content-01" class="modal-content">
                        <div class="modal_body completion">
                            <div class="modal_inner">
                                <h2 class="modal_ttl">
                                    出勤スケジュールを<br>
                                    変更しました
                                </h2>
                                <p class="modal_txt">
                                    コウハイの申込をお待ちください。
                                </p>
                            </div>
                        </div>


                        <div class="button-area type_under">
                            <div class="btn_base btn_ok"><a href="C-16.php">OK</a></div>
                        </div>

                    </div><!-- /modal-content -->

                </div>
                <div id="modal-overlay" style="display: none;"></div>
                <!-- モーダル部分 / ここまで ************************************************* -->

            </section>
        </form>
    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

