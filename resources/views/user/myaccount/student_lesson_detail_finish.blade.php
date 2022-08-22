@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents">

    <!--main_-->
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section>
            <div class="white_box">
                <div class="lesson_info_area">

                    <ul class="teacher_info_02 mt0">
                        <li class="icon_s48"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($schedule_info['lesson_request']['lesson']['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name">
                                <p>{{ $schedule_info['lesson_request']['lesson']['senpai']['name'] }}
                                    <span>（{{ \App\Service\CommonService::getAge($schedule_info['lesson_request']['lesson']['senpai']['user_birthday']) }}）
                                        {{ \App\Service\CommonService::getSexStr($schedule_info['lesson_request']['lesson']['senpai']['user_sex']) }}
                                    </span>
                                </p></div>
                            <div><p class="orange_link icon_arrow orange_right"><a href="{{ route('user.myaccount.profile', ['user_id' => $schedule_info['lesson_request']['lesson']['senpai']['id']]) }}">プロフィール</a></p></div>
                        </li>
                    </ul>

                    <div class="button-area pt20">
                        <div class="btn_base btn_orange mb0 low_btn effect_none">
                            <a href="{{ route('user.lesson.setting_reserve_request', ['lesson_id' => $schedule_info['lesson_request']['lesson']['lesson_id']]) }}">
                                もう一度予約する
                            </a>
                        </div>

                    </div>

                </div>
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
                                    <img src="{{ asset('storage/class_icon/' . $schedule_info['lesson_request']['lesson']['lesson_class']['class_icon']) }}" alt="">
                                </li>
                                <li>
                                    <p class="info_ttl">{{ $schedule_info['lesson_request']['lesson']['lesson_title'] }}</p>
                                </li>
                            </ul>
                        </li>

                        <li class="lesson_naiyou">
                            <div>
                                <p>レッスン日時：</p>
                                <p class="ls1">{{ \App\Service\CommonService::getYMD($schedule_info['lrs_date']) }}
                                    {{ \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}</p>
                            </div>
                            <div>
                                <p>予約成立日   ：</p>
                                <p>{{ \App\Service\CommonService::getYMDAndHM($schedule_info['lrs_reserve_date']) }}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>レッスン料金</p>
                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount']) }}</p>
                            </div>

                            <div>
                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-service">サービス料</a>
                                </p>
                                <p>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_service_fee']) }}</p>
                            </div>

                            <div>
                                <p>出張交通費</p>
                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_traffic_fee']) }}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>合計（税込）</p>
                                <p class="price_mark"><strong>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount'] + $schedule_info['lrs_traffic_fee'] + $schedule_info['lrs_service_fee']) }}</strong></p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="lesson_place">
                        @if($schedule_info['lesson_request']['lr_pos_discuss'] == 1)
                            <p>
                                {{ $schedule_info['lesson_request']['discuss_lesson_area'] }}
                            </p>
                            <p>
                                {{ $schedule_info['lesson_request']['lr_address'] }}
                            </p>
                        @else
                            <p>
                                {{ implode('/', $schedule_info['lesson_request']['lesson']['lesson_area_names']) }}
                            </p>
                        @endif
                    </div>
                    @if($schedule_info['lesson_request']['lr_pos_discuss'] == 1)
                        <div class="balloon balloon_blue font-small">
                            <p>{{ $schedule_info['lesson_request']['lr_address_detail'] }}</p>
                        </div>
                    @else
                        <div class="balloon balloon_blue font-small">
                            <p>{{ $schedule_info['lesson_request']['lesson']['lesson_pos_detail'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

        </section>

        <section id="f-white_area">
            <div class="button-area">
                <div class="btn_base btn_blue">
                    <a href="#">
                        領収書を発行
                    </a>
                </div>
            </div>
        </section>

    {{ Form::close() }}

</div><!-- /contents -->

@include ('user.layouts.modal')

<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
