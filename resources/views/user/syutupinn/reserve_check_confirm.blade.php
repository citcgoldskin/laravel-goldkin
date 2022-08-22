@extends('user.layouts.app')

@section('title', '内容の確認')

@section('content')

    @include('user.layouts.header_under')
    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

        <!--main_-->
        <form action="" method="post" name="form1" id="form1" target="senddata">

            <section class="pb10">

                <div class="inner_box">
                    <h3>辞退するレッスン</h3>
                    <div class="white_box">
                        <ul class="list_box">
                            <li class="due_date">
                                <div>
                                    <p>
		    <span>
		    2021<small>年</small>
			1<small>月</small>
			20<small>日</small>
		   </span>
                                </div>
                                <div class="jitai">
                                    <p>16:00～17:00</p>
                                    <p class="price_mark tax-in">3,000</p>
                                </div>
                            </li>

                            <li>
                                <div>
                                    <p>レッスン料</p>
                                    <p class="price_mark tax-in">4,500</p>
                                </div>
                                <div>

                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">手数料率</a>
                                    </p>

                                    <p class="price_mark">C</p>
                                </div>

                                <div>
                                    <p>手取り金額</p>
                                    <p class="price_mark tax-in">3,600</p>
                                </div>
                            </li>

                        </ul>

                    </div>
                </div>

                <div class="inner_box">
                    <div class="white_box">
                        <ul class="list_box">
                            <li class="due_date">
                                <div>
                                    <p>
		    <span>
		    2021<small>年</small>
			1<small>月</small>
			20<small>日</small>
		   </span>
                                </div>
                                <div class="jitai">
                                    <p>16:00～17:00</p>
                                    <p class="price_mark tax-in">3,000</p>
                                </div>
                            </li>

                            <li>
                                <div>
                                    <p>レッスン料</p>
                                    <p class="price_mark tax-in">4,500</p>
                                </div>
                                <div>

                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">手数料率</a>
                                    </p>

                                    <p class="price_mark">C</p>

                                </div>

                                <div>
                                    <p>手取り金額</p>
                                    <p class="price_mark tax-in">3,600</p>
                                </div>
                            </li>

                        </ul>

                    </div>
                </div>

                <div class="kome_txt pt0">
                    <p class="mark_left mark_kome">
                        手取り金額については、<br>
                        レッスンのキャンセルや追加予約、コウハイがあなたの発行したクーポンを使用した場合に変動することがあります。
                    </p>
                </div>


                <div class="inner_box">
                    <h3>辞退するレッスン</h3>
                    <div class="white_box">
                        <ul class="list_box">
                            <li class="due_date">
                                <div>
                                    <p>
		    <span>
		    2021<small>年</small>
			1<small>月</small>
			20<small>日</small>
		   </span>
                                </div>
                                <div class="jitai">
                                    <p>16:00～17:00</p>
                                </div>
                            </li>

                            <li>
                                理由：当日の天候が心配
                            </li>

                        </ul>

                    </div>
                </div>

            </section>

        </form>

        <div class="white-bk">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <button type="submit" class="btn-send2">この内容で送信</button>
                </div>
            </div>
        </div>
    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
    <div class="modal-wrap completion_wrap ok">
        <div id="modal-send" class="modal-content ok">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        承認・辞退内容を<br>
                        送信しました
                    </h2>

                    <div class="modal_txt">
                        <p>
                            レッスンが購入されると<br>
                            トークルームが開きます。
                        </p>
                    </div>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a href="{{route('user.syutupinn.lesson_list')}}">OK</a>
                </div>
            </div>
        </div>
    </div>
    <div id="modal-overlay2" style="display: none;"></div>
    <!-- モーダル部分 / ここまで ************************************************* -->

    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

