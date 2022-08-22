<!-- モーダル部分（全ページ共通） ************************************************** -->
@php
    use App\Service\SettingService;
    use App\Service\CommonService;
@endphp
<!-- **********************************************************************
出張交通費
*********************************************************************** -->
<div class="modal-wrap">
    <div id="modal-business-trip" class="modal-content">

        <div class="modal_body">

            <div class="modal_inner">
                <h4 id="circle-orange_ttl">?</h4>

                <h2 class="modal_ttl">出張交通費とは？</h2>

                <div class="modal_txt">
                    <p class="ta-left">
                        後輩が指定したレッスン場所で行われる対面レッスンには、最大{{CommonService::showFormatNum(SettingService::getSetting('traffic_max_amount', 'int'))}}円の出張交通費をお願いされることがあります。<br>
                        「こだわり」として「出張交通費なし」が選択されているレッスンには出張交通費はかかりません。<br>
                        また、出張交通費には{{SettingService::getSetting('service_fee_percent', 'int')}}%のサービス料もかかりません。
                    </p>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_ok">
                        <a id="modal-close" class="button-link">OK</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>



<!-- **********************************************************************
サービス料
*********************************************************************** -->
<div class="modal-wrap">
    <div id="modal-service" class="modal-content">

        <div class="modal_body">

            <div class="modal_inner">
                <h4 id="circle-orange_ttl">?</h4>

                <h2 class="modal_ttl">サービス料とは？</h2>

                <div class="modal_txt">
                    <p>
                        センパイのサービスを安心してご利用いただくため、サポート運用等に充てられる費用です。<br>
                        レッスン料の{{SettingService::getSetting('service_fee_percent', 'int')}}％となります。
                    </p>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_ok">
                        <a id="modal-close" class="button-link">OK</a>
                    </div>
                </div>

            </div>


        </div>

    </div>
</div>



<!-- **********************************************************************
キャンセルポリシー
*********************************************************************** -->
<div class="modal-wrap">
    <div id="modal-cancel_policy" class="modal-content">

        <div class="modal_body">

            <div class="modal_inner">

                <h4 id="circle-orange_ttl">?</h4>

                <h2 class="modal_ttl">キャンセルポリシーとは？</h2>

                <div class="modal_txt txt_left">
                    <p>
                        予約が成立したレッスンをキャンセルすると所定のキャンセル料が発生する場合があります。<br>
                        キャンセル料は以下の規定により決定されます。
                    </p>
                </div>

                <ul class="list_box">
                    <li>
                        <div>
                            <p>ご利用2日前まで</p>
                            <p>無料</p>
                        </div>
                    </li>

                    <li>
                        <div>
                            <p>ご利用前日</p>
                            <p>
                                レッスン料とサービス料を<br>
                                合計した金額の{{SettingService::getSetting('cancel_before_1_percent', 'int')}}％
                            </p>
                        </div>
                    </li>

                    <li>
                        <div>
                            <p>ご利用当日</p>
                            <p>
                                レッスン料とサービス料<br>
                                出張交通費を合計した<br>
                                金額の{{SettingService::getSetting('cancel_before_0_percent', 'int')}}％
                            </p>
                        </div>
                    </li>
                </ul>

                <div class="modal_txt_02">
                    <p>
                        ※ただし、ご利用の３時間前までにキャンセル申請されたレッスンについては、センパイが無料キャンセルに承認した場合のみキャンセル料が無料になります。<br>
                        <br>
                        予約日時を変更する場合や急な天候不順・体調不良が発生した場合は無理をせず、センパイに事情を説明して無料キャンセルのお願いをしてください。
                    </p>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_ok">
                        <a id="modal-close" class="button-link">OK</a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

<!-- **********************************************************************
キャンセル料が発生する場合について（トークルーム）
*********************************************************************** -->
<div class="modal-wrap">
    <div id="modal-cancellation_charge" class="modal-content">

        <div class="modal_body">

            <div class="modal_inner">
                <h4 id="circle-orange_ttl">?</h4>

                <h2 class="modal_ttl">キャンセル料が発生する場合について</h2>

                <div class="modal_txt">
                    <p>
                    </p>
                </div>

                <div class="button-area">
                    <div class="btn_base btn_ok">
                        <a id="modal-close" class="button-link">OK</a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>


<!-- モーダル部分 *********************************************************** -->
<div class="modal-wrap coupon_modal">

    <div id="modal-coupon_henkin" class="modal-content">
        <div class="modal_body">

            <div class="close_btn_area">
                <a id="modal-close"><img src="{{ asset('assets/user/img/x-mark.svg') }}" alt="閉じる"></a>
            </div>

            <div class="modal_inner">

                <h2 class="modal_ttl">
                    クーポンの返金について
                </h2>

                <section class="case">

                    <h3 class="case_ttl">
                        <span class="case_icon">caseA</span>
                        <span>特定のセンパイ（出品者）が発行したクーポンの場合</span>
                    </h3>

                    <ul class="case_list">
                        <li>1枚でも使用されたクーポンは返金することは出来ません。</li>
                        <li>有償キャンセルをされた場合につきましても、クーポンを1枚使用したものとさせていただきますので、返金はいたしかねます。</li>
                        <li>無料キャンセルをされた場合パターンによって以下の対応をさせていただきます。</li>
                    </ul>

                    <h4 class="case_ttl_02">
                        キャンセルしたレッスン以降に同種のクーポンを使用しているレッスンを購入している場合
                    </h4>

                    <p class="modal_txt">
                        キャンセルしたレッスンに使用する予定であったクーポンを以降に同種のクーポンを使用しているレッスンの振替させていただきます。
                    </p>

                    <p class="henkin_img"><img src="{{ asset('assets/user/img/coupon/img_henkin.png') }}" alt=""></p>
                </section>

                <section class="case">

                    <h3 class="case_ttl">
                        <span class="case_icon">caseB</span>
                        <span>運営が発行したクーポンの場合</span>
                    </h3>

                    <h5 class="case_ttl_03 mark_square">
                        有償販売クーポン
                    </h5>

                    <p class="modal_txt">
                        原則返金は出来ません。<br>
                        クーポンに不備があった場合は例外的に買い戻しを行うことがあります。
                    </p>

                    <h4 class="case_ttl_02">
                        キャンセルしたレッスン以降に同種のクーポンを使用しているレッスンを購入している場合
                    </h4>

                    <p class="modal_txt">
                        キャンセルしたレッスンに使用する予定であったクーポンを以降に同種のクーポンを使用しているレッスンの振替させていただきます。
                    </p>

                    <p class="logic_img"><img src="{{ asset('assets/user/img/coupon/case_logic.png') }}" alt=""></p>

                    <h4 class="case_ttl_02">
                        3枚つづり300円で販売していたクーポンに不備があった場合
                    </h4>

                    <p class="modal_txt pb20">
                        ・3枚未使用　→300円で買い戻し<br>
                        ・2枚未使用　→200円で買い戻し<br>
                        ・1枚未使用　→100円で買い戻し<br>
                        ・全て使用　 →買い戻しは行いません
                    </p>

                    <h5 class="case_ttl_03 mark_square">
                        無料配布クーポン
                    </h5>

                    <p class="modal_txt">
                        予告なくクーポンの失効措置を実施する場合があります。<br>
                        その際、買い戻し対応などは行いません。<br>
                        あらかじめご了承ください。
                    </p>
                </section>

            </div>


            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a id="modal-close" class="button-link">OK</a>
                </div>
            </div>

        </div><!-- /modal-content -->

    </div>
    <!-- モーダル部分 / ここまで ************************************************* -->

</div>


    <!-- モーダル部分（共通）/ ここまで ************************************************* -->



