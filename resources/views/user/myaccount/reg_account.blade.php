@extends('user.layouts.app')

@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents">

    {{ Form::open(["route"=>["user.myaccount.add_account"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section class="pb40">

            <ul class="form_area">

                <li>
                    <h3>金融機関名</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay">
                        <button type="button" onClick="location.href='{{ route('user.myaccount.sel_bank') }}'" class="form_btn">
                    <?php

                    $bank = "";
                    if (isset($_POST['bank1'])) {
                        $bank = "三菱UFJ銀行";
                    }
                    elseif (isset($_POST['bank2'])) {
                        $bank = "みずほ銀行";
                    }
                    elseif (isset($_POST['bank3'])) {
                        $bank = "りそな銀行";
                    }
                    elseif (isset($_POST['bank4'])) {
                        $bank = "埼玉りそな銀行";
                    }
                    elseif (isset($_POST['bank5'])) {
                        $bank = "三井住友銀行";
                    }
                    elseif (isset($_POST['bank6'])) {
                        $bank = "ジャパンネット銀行";
                    }
                    elseif (isset($_POST['bank7'])) {
                        $bank = "楽天銀行";
                    }
                    elseif (isset($_POST['bank8'])) {
                        $bank = "ゆうちょ銀行";
                    }
                    else {
                        $bank = "選択してください";
                    }
                    echo $bank;
                    ?>
                        </button>
                    </div>

                </li>

                <li>
                    <h3>口座種別</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay">
                        <button type="button" onClick="location.href='{{ route('user.myaccount.sel_account_type') }}'" class="form_btn">
                            <?php
                            $account = "";
                            if (isset($_POST['account1'])) {
                                $account = "普通預金";
                            }
                            elseif (isset($_POST['account2'])) {
                                $account = "当座預金";
                            }
                            elseif (isset($_POST['account3'])) {
                                $account = "貯蓄貯金";
                            }
                            else {
                                $account = "選択してください";
                            }
                            echo $account;
                            ?>
                        </button>
                    </div>
                </li>

                <li>
                    <h3>支店コード</h3>
                    <div class="form_wrap shadow-glay">
                        <input type="text" value="" placeholder="">
                    </div>
                </li>

                <li>
                    <h3>口座番号</h3>
                    <div class="form_wrap shadow-glay">
                        <input type="text" value="" placeholder="">
                    </div>
                    <p class="gray_txt">※口座番号が7桁未満の場合は先頭に0をつけてください</p>
                </li>

                <li>
                    <h3>口座名義（カナ）</h3>
                    <div class="form_wrap shadow-glay">
                        <input type="text" value="" placeholder="">
                    </div>
                </li>
            </ul>

        </section>


        <div class="white-bk pt20">

            <div id="footer_comment_area">
                <ul class="coution_list">
                    <li>登録された氏名と売上金の振込口座名義が一致しない場合、振込申請を行うことができません。</li>
                    <li>振込先口座はご本人名義の口座のみご利用いただけます。</li>
                </ul>
            </div>


            <div class="button-area mt30">
                <div class="btn_base btn_orange shadow ">
                    <button type="submit" class="btn-send">登録する</button>
                </div>
            </div>

        </div>



    {{ Form::close() }}


</div><!-- /contents-->

<!-- モーダル部分 *********************************************************** -->
<iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
<div class="modal-wrap completion_wrap">
    <div id="modal-mail_henkou" class="modal-content">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    口座情報を<br>
                    登録しました
                </h2>

            </div>
        </div>


        <div class="button-area type_under">
            <div class="btn_base btn_ok"><a href="{{ route('user.myaccount.index') }}>OK</a></div>
        </div>

    </div><!-- /modal-content -->

</div>
<div id="modal-overlay" style="display: none;"></div>

<!-- モーダル部分 / ここまで ************************************************* -->
<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
