@extends('user.layouts.app')
@section('title', 'お友達を招待する')
@section('content')
@include('user.layouts.header_under')
<div id="contents">
    <section class="slider_area pb10 pt20 coupon-ver pr0 pl0">
        <div class="swiper-container">
            <div class="swiper-inner">
                <div class="coupon">
                    <ol class="swiper-wrapper">
                        <!-- Slides -->
                        <li class="swiper-slide">
                            <div class="swip_contents_block">
                                <div class="slider_box">
                                    <div class="img-box">
                                        <img src="{{ asset('assets/user/img/coupon/slide_01.png') }}" alt="300円分クーポンプレゼント">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide">
                            <div class="swip_contents_block">
                                <div class="slider_box">
                                    <div class="img-box">
                                        <img src="{{ asset('assets/user/img/coupon/slide_02.png') }}" alt="クーポンGET">
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="swiper-slide">
                            <div class="swip_contents_block">
                                <div class="slider_box">
                                    <div class="img-box">
                                        <img src="{{ asset('assets/user/img/coupon/slide_03.png') }}" alt="現在のキャンペーン対象地域">
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ol>
                    <!-- If we need pagination -->
                    <div class="swiper-pagination"></div>

                    <!-- If we need navigation buttons -->
                </div>
            </div>
        </div>
    </section>


    <section id="friend_area">

        <div class="inner_box">
            <h3>現在のお友達招待キャンペーンの対象エリア</h3>
            <ul class="form_area">
                <li>
                    <div class="form_wrap">
                        <input type="text" value="大阪府　京都府　兵庫県　奈良県　和歌山県" class="centre" readonly>
                    </div>
                </li>
            </ul>
        </div>

        <div class="inner_box pb20">
            <h3 class="fs-20">かんたん3ステップ</h3>
            <ul class="step_list">
                <li>
                    <span class="step_icon step_1">STEP1</span>
                    <span>SNSやメールで招待コードをシェアする</span>
                </li>
                <li>
                    <span class="step_icon step_2">STEP2</span>
                    <span>お友達に<big>300</big><small>円分</small>のクーポンをプレゼント！</span>
                </li>
                <li>
                    <span class="step_icon step_3">STEP3</span>
                    <span>お友達が対象のエリア内にてレッスンの受講または実施を完了するとあなたに<big>300</big><small>円分</small>のクーポンをプレゼント！</span>
                </li>
            </ul>
        </div>

    </section>


    <section>

        <div class="inner_box">
            <h3>あなたの招待コード</h3>
            <ul class="form_area">
                <li>
                    <div class="form_wrap w50 number-wrap">
                        <p id="js-copytext">{{$invite_code}}</p>
                        <button type="button" id="js-copybtn"><i class="fa fa-copy"></i></button>
                    </div>
                    <p id="js-copyalert" class="copy_alert">コピーしました</p>
                </li>
            </ul>
        </div>
    </section>


    <section>
        <h3>SNSで招待</h3>
        <ul class="SNS_area">
            <li class="line">

                <div class="line-it-button" data-lang="ja" data-type="share-b" data-env="REAL" data-url="https://senpai.inc" data-color="default" data-size="small" data-count="false" data-ver="3" style="display: none;"></div>
                <script src="https://www.line-website.com/social-plugins/js/thirdparty/loader.min.js" async="async" defer="defer"></script>
                <p>LINEで送る</p>

            </li>
            <li class="twitter">
                <a href="https://twitter.com/share?url=https://senpai.inc">
                    <p><img src="{{ asset('assets/user/img/coupon/icon_sns_twitter.svg') }}" alt="twitter"></p>
                    <p>ツイートする</p>
                </a>
            </li>
            <li class="instagram">
                <a href="">
                    <p><img src="{{ asset('assets/user/img/coupon/icon_sns_instagram.svg') }}" alt="instagram"></p>
                    <p>シェアする</p>
                </a>
            </li>
            <li class="other">
                <a href="">
                    <p><img src="{{ asset('assets/user/img/coupon/icon_sns_other.png') }}" alt="その他"></p>
                    <p>その他</p>
                </a>
            </li>
        </ul>
    </section>


    <section id="invitation_code">
        <h3>招待コードの利用について</h3>

        <div class="inner_box">
            <h3 class="invitation_ttl mark_square">特典内容</h3>
            <ul class="case_list">
                <li>あなたに招待されたお友達が新規会員登録すると、お友達のアカウントに300円クーポンを付与いたします。<br>
                    お友達が招待された日から30日以内にレッスンの受講または実施を完了すると、48時間以内にあなたのアカウントに300円クーポンを付与いたします。</li>
            </ul>


            <h3 class="invitation_ttl mark_square">注意事項</h3>
            <ul class="case_list">
                <li>招待されたお友達はセンパイの新規ユーザーであることが必要です。携帯電話番号、デバイス、クレジットカード番号などの決済情報のいずれかが重複している場合、同一ユーザーと見なされます。</li>
                <li>クーポンの使用条件及び有効期限はクーポンの注意事項をご確認ください。</li>
                <li>本キャンペーンの適用対象はセンパイの指定するエリアのみとなります。</li>
                <li>自分で発行したコードを自分で使用することは出来ません。</li>
                <li class="blue_link">本キャンペーンについてのお問い合わせは、よくある質問を参照の上、<a href="{{route('user.myaccount.asking')}}">お問い合わせフォーム</a>よりお問い合わせください。</li>
                <li>規約違反や不正行為が発覚した場合、クーポンの割引は適用されずアカウントのブロックや法的責任を追及される可能性があります。</li>
                <li>本キャンペーンは予告なしで終了又は変更を行う場合があります。</li>
            </ul>
        </div>
    </section>


</div><!-- /contents -->



<footer>
    @include('user.layouts.fnavi')
</footer>


<script>
    $(function() {
        $('#js-copyalert').hide();
        $('#js-copybtn').on('click', function(){
            // コピーする文章の取得
            let text = $('#js-copytext').text();
            // テキストエリアの作成
            let $textarea = $('<textarea></textarea>');
            // テキストエリアに文章を挿入
            $textarea.text(text);
            //　テキストエリアを挿入
            $(this).append($textarea);
            //　テキストエリアを選択
            $textarea.select();
            // コピー
            document.execCommand('copy');
            // テキストエリアの削除
            $textarea.remove();
            // アラート文の表示
            $('#js-copyalert').show().delay(2000).fadeOut(400);
        });

        $('#js-copybtn .fa-copy').click(function() {

        });
    });
</script>

@endsection

