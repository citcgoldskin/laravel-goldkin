@extends('user.layouts.app')
@section('title', '売上の振込申請の詳細について')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <!--main_-->
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section id="howto-uketori">
            <div class="inner_box">
                <h3 class="fs-18">売上金の受け取り方法</h3>
                <div class="base_txt">
                    <p>
                        受け取り方法は「銀行振込」のみとなります。
                    </p>
                </div>
            </div>

            <div class="inner_box">
                <h3>銀行振込</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>
                            受取可能な売上金残高が301円以上となった段階でご指定の銀行口座に振込申請が可能です。<br>
                            売上金には振込申請期限を設けており、期限内に「振込申請」ボタンを押すと振込予定日に自動的に振り込まれます。
                        </p>
                    </div>
                    <div class="coution_box">
                        <p class="mark_left mark_kome">
                            「振込申請」ボタンを押すためには事前に振込口座の登録が必要です。<br>
                            振込口座の登録・変更は<a href="{{ route('user.myaccount.account', ['prev_url_id' => 2]) }}" class="blue_txt">こちら</a>
                        </p>
                        <p class="mark_left mark_kome">
                            振込手数料は申請者負担となります。
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>振込申請手順</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>
                            売上金を銀行振込で受け取る際はマイページのセンパイメニューにある<a href="{{ route('user.myaccount.payment_mgr') }}" class="blue_txt">売上管理・振込申請</a>を開き、振込申請をするボタンを押してください。
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>振込期間・振込日について</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>
                            決済代行次<br>
                            ダミーダミーダミーダミーダミーダミーダミーダミーダミーダミーダミーダミーダミー
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>振込申請期限について</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>
                            売上金の振込申請期限は売上の発生月を含めて６ヶ月間です。期限までに振込申請がない場合、301円以上の売上金は自動的に振込申請を行い、ご登録の銀行口座に振り込まれます。<br>
                            銀行口座を登録ステいない場合や300円以下の期限切れ売上金については、振込が出来ずそのまま失効となりますのでご注意ください。
                        </p>

                        <p class="pt10"><img src="{{ asset('assets/user/img/ex_uriage.png') }}" alt=""></p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>失効売上金</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>
                            売上には振込申請期限を設けており期限内に「振込申請をする」ボタンを押すと振込予定日に自動的に振り込まれます。<br>
                            但し、売上金が300円以下の場合や、口座情報の未登録、誤りなどの場合は振込は行われず失効扱いとなりますのでご注意ください。
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>振込手数料について</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>
                            売上金の振込の際、振込手数料の300円を差し引いて振込みさせていただきます。
                        </p>
                    </div>
                    <div class="coution_box">
                        <p class="mark_left mark_kome">
                            売上金が300円以下の場合は「振込申請」できません。
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>注意事項</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>
                            振込先の銀行口座は出品者情報の「氏名・フリガナ」と「口座名義」が一致していない場合は口座情報を登録できません。
                        </p>
                    </div>
                    <div class="link_area">
                        <p>アカウント情報の変更は<a href="{{ route('user.myaccount.set_account') }}">こちら</a></p>
                        <p>振込口座情報の登録・変更は<a href="{{ route('user.myaccount.account', ['prev_url_id' => 2]) }}">こちら</a></p>
                    </div>
                </div>
            </div>


        </section>



    {{ Form::close() }}

</div><!-- /contents -->


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
