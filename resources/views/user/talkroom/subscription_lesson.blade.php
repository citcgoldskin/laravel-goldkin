@extends('user.layouts.app')
@section('title', '予約中のレッスン')
<!-- ************************************************************************
本文
************************************************************************* -->

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">

        <!--main_-->

        <section>
            <div class="lesson_info_area">

                <ul class="teacher_info_02 mt0">
                    <li class="icon">
                        <img src=" {{ asset( $user_info['user_avatar']) }} " class="プロフィールアイコン">
                    </li>
                    <li class="about_teacher">
                        <div class="profile_name"><p>コウハイ{{  $user_info['user_sei'].$user_info['user_mei'] }}<span>（{{ $user_info['age']}}）{{ $user_info['sex']}}</span></p></div>
                        <div><p class="orange_link icon_arrow orange_right"><a href="{{route('user.myaccount.profile', ['user_id' => $user_info['id']])}}">プロフィール</a></p></div>
                    </li>
                </ul>
            </div>


        </section>

        <section>

            <div class="inner_box">
                <h3>レッスン概要</h3>
                <div class="white_box">
                    <ul class="list_box">
                        <li class="pt0 pb0">
                            <ul class="info_ttl_wrap">
                                <li>
                                    <img src="{{ asset('assets/user/img/icon_lesson_ttl_01.png') }}" alt="">
                                </li>
                                <li>
                                    <p class="info_ttl">{{ $req_info['lesson']['lesson_title'] }}</p>
                                </li>
                            </ul>
                        </li>

                        <li class="lesson_naiyou">
                            <div>
                                <p class="normal">レッスン日時：</p>
                                <p class="ls1">{{ $req_info['lesson_day'] }} {{ $req_info['lesson_stime'] }}〜{{ $req_info['lesson_etime'] }}</p>
                            </div>
                            <div>
                                <p class="normal">予約成立日   ：</p>
                                <p>{{$req_info['reverse_date']}}</p>
                            </div>
                            <div>
                                <p class="normal">参加人数　   ：</p>
                                <p>{{ $req_info['man_woman'] }}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>レッスン料（{{ $req_info['lr_man_num'] + $req_info['lr_woman_num'] }}人）</p>
                                <p class="price_mark tax-in normal">{{ $req_info['amount'] }}</p>
                            </div>
                            @if ( $menu_type == \App\Service\TalkroomService::KOUHAI_MENU )
                                <div>
                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">現在の手数料率</a>
                                    </p>
                                    <p>{{$req_info['fee_type']}}</p>
                                </div>
                            @else
                                <div>
                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">サービス料</a>
                                    </p>
                                    <p class="price_mark tax-in">{{ $req_info['service_fee'] }}</p>
                                </div>
                            @endif
                            <div>
                                <p>売上（目安）</p>
                                <p class="price_mark tax-in"><strong class="f16">{{ $req_info['amount'] }}</strong></p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="lesson_place">
                        <p>
                            大阪府大阪市西区江戸堀1-2-16<br>
                            スターバックス肥後橋南店
                        </p>
                    </div>
                    <div class="balloon balloon_blue font-small">
                        <p>KARADAというジムの中でのレッスンとなります</p>
                    </div>
                </div>
            </div>


        </section>

        <section id="button-area">

            <div class="button-area">
                <div class="btn_base btn_pale-gray shadow-glay">
                    <button type="button" onclick="location.href='E-18.php'">
                        このレッスンをキャンセルする
                    </button>
                </div>
            </div>


        </section>

    </div><!-- /contents -->

    @include('user.layouts.modal')
    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

