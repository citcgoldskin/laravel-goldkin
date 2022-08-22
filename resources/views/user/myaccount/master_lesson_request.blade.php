@extends('user.layouts.app')
@section('title', $title)
@section('content')

@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <!--main_-->
    @if ( $state >= config('const.schedule_state.cancel_senpai') )
        <section class="pt20">
    @else
        <section class="pt10">
    @endif
            <div class="lesson_info_area">

                <ul class="teacher_info_02">
                    <li class="icon">
                        <img src="{{ \App\Service\CommonService::getUserAvatarUrl($schedule_info['lesson_request']['user']['user_avatar']) }}" class="プロフィールアイコン">
                    </li>
                    <li class="about_teacher">
                        <div class="profile_name">
                            <p>{{ $schedule_info['lesson_request']['user']['name'] }}
                                <span>
                                    （{{ \App\Service\CommonService::getAge($schedule_info['lesson_request']['user']['user_birthday']) }}）
                                    {{ \App\Service\CommonService::getSexStr($schedule_info['lesson_request']['user']['user_sex']) }}
                                </span>
                            </p>
                        </div>
                        <div><p class="orange_link icon_arrow orange_right"><a href="{{ route('user.myaccount.profile', ['user_id' => $schedule_info['lesson_request']['user']['id']]) }}">プロフィール</a></p></div>
                    </li>
                </ul>
            </div>


        </section>

        <section>

            <div class="inner_box">
                <h3>レッスン概要</h3>
                <div class="white_box">
                    <ul class="list_box">
                        <li>
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
                                <p>{{ \App\Service\CommonService::getYMD($schedule_info['lrs_date']) }}
                                    {{ \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}
                                </p>
                            </div>
                            <div>
                                <p>予約成立日   ：</p>
                                <p>{{ $schedule_info['lrs_reserve_date'] ? \App\Service\CommonService::getYMDAndHM($schedule_info['lrs_reserve_date']) : '' }}</p>
                            </div>
                        </li>

                        <li>
                            @if ( $state == config('const.schedule_state.reserve') )
                                <div>
                                    <p>レッスン料</p>
                                    <p class="price_mark"> {{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount']) }}</p>
                                </div>

                                <div>
                                    <p>クーポン適用</p>
                                    <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['coupon']) }}</p>
                                </div>

                                <div>
                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">現在の手数料率</a>
                                    </p>
                                    <p>{{ \App\Service\CommonService::getFeeTypeStr($schedule_info['lrs_fee_type']) }}</p>
                                </div>

                                <div>
                                    <p>出張交通費</p>
                                    <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_traffic_fee']) }}</p>
                                </div>
                        </li>

                        <li>
                            <div>
                                <p>売上金（目安）</p>
                                <p class="price_mark"><strong class="f-big">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount'] - $schedule_info['lrs_fee'] + $schedule_info['lrs_traffic_fee'] + $schedule_info['coupon']) }}</strong></p>
                            </div>
                            @elseif ( $state == config('const.schedule_state.complete') )
                                <div>
                                    <p>レッスン料</p>
                                    <p class="price_mark tax-in">{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount']) }}</p>
                                </div>

                                <div>
                                    <p class="modal-link">
                                        <a class="modal-syncer" data-target="modal-service">現在の手数料率</a>
                                    </p>
                                    <p>{{ \App\Service\CommonService::getFeeTypeStr($schedule_info['lrs_fee_type']) }}</p>
                                </div>
                        </li>

                        <li>
                            <div>
                                <p>売上（目安）</p>
                                <p class="price_mark tax-in"><strong>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount'] - $schedule_info['lrs_fee'] + $schedule_info['lrs_traffic_fee']) }}</strong></p>
                            </div>
                            @elseif ( $state >= config('const.schedule_state.cancel_senpai') )
                                <div>
                                    <p>合計（税込）</p>
                                    <p class="price_mark"><strong>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount']) }}</strong></p>
                                </div>
                            @endif

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

    @if ( $state == config('const.schedule_state.reserve') )
        <section id="f-white_area">
            <div class="button-area">
                <div class="btn_base btn_pale-gray shadow-glay">
                    <a href="{{ route('user.myaccount.cancel_lesson', ['schedule_id' => $schedule_info['lrs_id']]) }}">
                        このレッスンをキャンセルする
                    </a>
                </div>
            </div>
        </section>
    @endif


</div><!-- /contents -->

@include('user.layouts.modal')

@if ( $state >= config('const.schedule_state.cancel_senpai') )
    <div id="footer_comment_area" class="under_area cancel_area">
        <p>
            このレッスンは、<br>
            {{ \App\Service\CommonService::getYMDAndHM($schedule_info['lrs_cancel_date']) }}にキャンセルしました。
        </p>
    </div>
@endif


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
