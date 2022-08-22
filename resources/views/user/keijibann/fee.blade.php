@extends('user.layouts.app')

@section('title', '販売手数料について')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
        <section>
            <div class="back_wrap">
            </div>
            <div class="inner_box">
                <h3 class="fs-22">販売手数料とは？</h3>
                <div class="base_txt">
                    <p>販売手数料はレッスン料金の{{$percent_B}}〜{{$percent_C}}％となります。<br>手数料率は以下の基準により分類されます。</p>
                </div>
                　<div class="rate">
                    <ul>
                        <li><img src="{{ asset('assets/user/img/rate_a.svg') }}"></li>
                        <li>レッスンの最低販売手数料であり、<br>金額は{{$min_fee}}円です。</li>
                    </ul>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/rate_b.svg') }}"></li>
                        <li>同一の後輩と{{$days_B}}日以内に<br>再び行ったレッスンに適用され、<br>手数料はレッスン料金の{{$percent_B}}％です。</li>
                    </ul>
                    <ul>
                        <li><img src="{{ asset('assets/user/img/rate_c.svg') }}"></li>
                        <li>後輩との初回レッスンや、<br>
                            同一の後輩と前回レッスンから{{$days_B + 1}}日以降に<br>
                            行ったレッスンに適用され、手数料は<br>
                            レッスン料金の{{$percent_C}}％です。</li>
                    </ul>
                </div>
                <div class="rate_text base_txt">
                    <ul>
                        <li>料率Aは料率Bまたは料率Cが{{$min_fee}}円を下回った場合にのみ適用されます。</li>
                        <li>交通費はレッスン料金とは分離し、一律{{$percent_B}}％の手数料となります。</li>
                    </ul>
                </div>
                <div class="icon_text base_txt">
                    <p>リピーターさんの獲得が<br>収益の増加につながります</p>
                </div>

            </div>
            </form>
        </section>

    </div><!-- /contents -->
    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap completion_wrap">
        <div id="modal1" class="modal modal-content">
            <div class="modal_body modal_basic">
                <div class="modal_inner">
                    <h4 id="circle-orange_ttl">！</h4>
                    <h2 class="modal_ttl"> 提案を削除しても<br>
                        よろしいですか？ </h2>
                </div>
            </div>
            <div class="button-area">
                <div class="btn_base btn_orange"> <a class="modal-syncer" data-toggle="modal" data-target="#modal2">削除</a> </div>
                <div class="btn_base btn_gray-line"> <a href="{{route('keijibann.recruiting_edit')}}">キャンセル</a> </div>
            </div>
        </div>
    </div>
    <div class="modal-wrap completion_wrap">
        <div id="modal2" class="modal modal-content">
            <div class="modal_body">
                <div class="modal_inner">
                    <h2 class="modal_ttl"> 募集内容を<br>
                        変更しました </h2>
                </div>
            </div>
            <div class="button-area">
                <div class="btn_base btn_ok"> <a href="{{route('keijibann.list')}}">OK</a> </div>
            </div>
        </div>
    </div>

    <!-- モーダル部分 / ここまで ************************************************* -->
    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

