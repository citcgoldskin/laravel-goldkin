@extends('user.layouts.app')

@section('title', 'カテゴリーを選択')

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">


        <!--main_-->
        <form action="<?php echo $_SERVER['HTTP_REFERER']; ?>" method="post" name="form1" id="form1">

            <!-- 大阪市 ************************************************** -->
            <section>
                <div class="white_box shadow-glay">
                    <div class="ac-margin">
                        <div class="check-list">

                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c1">
                                <label for="c1"><p>ランニング・ウォーキング（100）</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c2">
                                <label for="c2"><p>サイクリング（100）</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c3">
                                <label for="c3"><p>水泳（100）</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c4">
                                <label for="c4"><p>筋トレ（100）</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c5">
                                <label for="c5"><p>ゴルフ（100）</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c6">
                                <label for="c6"><p>カラオケ・ボイトレ（100）</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c7">
                                <label for="c7"><p>その他・アウトドア（100）</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="radio" name="commitment" value="1" id="c8">
                                <label for="c8"><p>その他・インドア（100）</p></label>
                            </div>


                        </div>

                    </div>

                </div>
            </section>

            <footer>
                <div id="footer_button_area" class="result">
                    <ul>
                        <li>
                            <div class="btn_base btn_white clear_btn">
                                <button type="reset">クリア</button>
                            </div>
                        </li>
                        <li>
                            <div class="btn_base btn_white settei_btn">
                                <button type="submit">設定する</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </footer>
        </form>
    </div>
@endsection

