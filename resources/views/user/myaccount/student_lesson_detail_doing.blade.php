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

        <section class="type_summary">
            <div class="lesson_info_area">

                <ul class="teacher_info_02">
                    <li class="icon_s48"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($schedule_info['lesson_request']['lesson']['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                    <li class="about_teacher">
                        <div class="profile_name">
                            <p>{{ $schedule_info['lesson_request']['lesson']['senpai']['name'] }}
                                <span>（{{ \App\Service\CommonService::getAge($schedule_info['lesson_request']['lesson']['senpai']['user_birthday']) }}）
                                    {{ \App\Service\CommonService::getSexStr($schedule_info['lesson_request']['lesson']['senpai']['user_sex']) }}
                                </span>
                            </p>
                        </div>
                        <div><p class="orange_link icon_arrow orange_right"><a href="{{ route('user.myaccount.profile', ['user_id' => $schedule_info['lesson_request']['lesson']['senpai']['id']]) }}">プロフィール</a></p></div>
                    </li>
                </ul>
            </div>


        </section>

        <section class="mt30">

            <div class="inner_box">
                <h3>レッスン概要</h3>
                <div class="white_box">
                    <ul class="list_box">
                        <li>
                            <ul class="reserved_top_box lesson_summary">
                                @php
                                    $lesson_image = NULL;
                                    if ( isset($schedule_info['lesson_request']['lesson']['lesson_image']) && is_array(unserialize($schedule_info['lesson_request']['lesson']['lesson_image']))) {
                                        $lesson_image = unserialize($schedule_info['lesson_request']['lesson']['lesson_image'])[0];
                                    }
                                @endphp
                                <li><img src="{{ \App\Service\CommonService::getLessonImgUrl($lesson_image) }}" alt=""></li>
                                <li>
                                    <p class="lesson_ttl three_line">{{ $schedule_info['lesson_request']['lesson']['lesson_title'] }}</p>
                                </li>
                            </ul>
                        </li>

                        <li class="lesson_naiyou">
                            <div>
                                <p>レッスン日時：</p>
                                <p>{{ \App\Service\CommonService::getYMD($schedule_info['lrs_date']) }}
                                {{ \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}</p>
                            </div>
                            <div>
                                <p>予約成立日   ：</p>
                                <p>{{ \App\Service\CommonService::getYMDAndHM($schedule_info['lrs_reserve_date']) }}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>レッスン料</p>
                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount']) }}</p>
                            </div>
                            <div>

                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-service">サービス料</a>
                                </p>

                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_service_fee']) }}</p>
                            </div>

                            <div>
                                <p>出張交通費</p>
                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_traffic_fee']) }}</p>
                            </div>

                            <div>
                                <p>クーポン</p>
                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['coupon']) }}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>合計（税込）</p>
                                <p class="price_mark"><strong>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount'] + $schedule_info['lrs_service_fee'] + $schedule_info['lrs_traffic_fee'] + $schedule_info['coupon']) }}</strong></p>
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

            <div class="button-area">
                <p class="btn_base btn_orange shadow"><button type="button" onclick="location.href='{{ route('user.myaccount.changerequest_1', ['schedule_id' => $schedule_info['lrs_id']]) }}'">リクエスト内容を変更する</button></p>
                <div class="btn_base btn_pale-gray shadow-glay">
                    <button type="button" onclick="location.href='{{ route('user.myaccount.cancel_student_lesson', ['schedule_id' => $schedule_info['lrs_id']]) }}'">
                        このレッスンをキャンセルする<br>
                        <small>(現在のキャンセル料{{ \App\Service\CommonService::showFormatNum(\App\Service\CommonService::getCancelFee($schedule_info['lrs_date'], $schedule_info['lrs_amount'], $schedule_info['lrs_service_fee'], $schedule_info['lrs_traffic_fee'])) }}円)</small>
                    </button>
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
