@extends('user.layouts.app')
<style>
    header {
        background: none;
    }
</style>

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_info')

    <div id="contents">
        <!--main_-->
        <form action="" method="post" name="form1" id="form1" target="senddata">

            <section>

                <div class="inner_box">
                    <h3 class="must">通報する理由を以下から選んでください</h3>
                    <div class="white_box">
                        <div class="check-box">
                            <div class="clex-box_02">
                                <input type="checkbox" name="report" value="1" id="report-11">
                                <label for="report-11"><p>性的いやがらせ／出会い目的</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="checkbox" name="report" value="1" id="report-12">
                                <label for="report-12"><p>迷惑行為</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="checkbox" name="report" value="1" id="report-13">
                                <label for="report-13"><p>スパム／宣伝目的</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="checkbox" name="report" value="1" id="report-14">
                                <label for="report-14"><p>悪質なキャンセル</p></label>
                            </div>
                            <div class="clex-box_02">
                                <input type="checkbox" name="report" value="1" id="report-15">
                                <label for="report-15"><p class="click-balloon hei-auto" onclick="showBalloon()">その他</p></label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="balloon_area" id="makeImg">
                    <div class="balloon balloon_white">
                        <textarea placeholder="理由を200字以内でご記入ください。" cols="50" rows="10" maxlength="200"></textarea>
                    </div>
                </div>


            </section>

        </form>

        <div class="form_txt txt_center pb30">
            <p><strong>通報すると当該ユーザーの情報を送信します。</strong></p>
        </div>

        <div class="button-area">
            <div class="btn_base btn_orange shadow">
                <button type="submit"  class="btn-send2">同意して送信</a>
            </div>
        </div>

    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
    <div class="modal-wrap ok">
        <div id="modal-report" class="modal-content ok">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        〇〇〇〇さんを<br>
                        通報しました
                    </h2>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a href="D-1.php" class="button-link">OK</a>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-overlay2" style="display: none;"></div>

    <!-- ********************************************************* -->

    @include('user.layouts.modal')

@endsection

