@extends('user.layouts.app')
@section('title', 'リクエストのキャンセル')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents">
        <!--main_-->
        <form action="D-34.php" method="post" name="form1" id="form1">

            <section>

                <div class="inner_box">
                    <h3>キャンセルするリクエストを選択してください。（複数選択可）</h3>
                    <div class="white_box">
                        <div class="check-box">
                            <div class="clex-box_02">
                                <input type="checkbox" name="commitment" value="1" id="c1">
                                <label for="c1">
                                    <p>
                                        <strong>2021</strong>年
                                        <strong>1</strong>月
                                        <strong>18</strong>日（火）　<strong>18:15~19:00</strong>
                                    </p>
                                </label>
                            </div>
                            <div class="clex-box_02">
                                <input type="checkbox" name="commitment" value="1" id="c2">
                                <label for="c2">
                                    <p>
                                        <strong>2021</strong>年
                                        <strong>1</strong>月
                                        <strong>20</strong>日（火）　<strong>18:15~19:00</strong>
                                    </p>
                                </label>
                            </div>
                            <div class="clex-box_02">
                                <input type="checkbox" name="commitment" value="1" id="c3">
                                <label for="c3">
                                    <p>
                                        <strong>2021</strong>年
                                        <strong>1</strong>月
                                        <strong>21</strong>日（火）　<strong>18:15~19:00</strong>
                                    </p>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


            </section>

            <div class="white-bk">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow-orange shadow">
                        <button type="submit">キャンセル内容を確認</button>
                    </div>
                </div>
            </div>

        </form>

    </div><!-- /contents -->

    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection

