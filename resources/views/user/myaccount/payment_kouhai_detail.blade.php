@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

    <!-- ************************************************************************
本文
************************************************************************* -->


    <div id="contents">

        <section>
            <div class="white_box">
                <div class="lesson_info_area">

                    <ul class="teacher_info_02 mt0">
                        <li class="icon_s48"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($obj_kouhai['user_avatar']) }}" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name">
                                <p>{{ $obj_kouhai['name'] }}
                                    <span>（{{ \App\Service\CommonService::getAge($obj_kouhai['user_birthday']) }}）
                                        {{ \App\Service\CommonService::getSexStr($obj_kouhai['user_sex']) }}
                                    </span>
                                </p></div>
                            <div><p class="orange_link icon_arrow orange_right"><a href="{{ route('user.myaccount.profile', ['user_id' => $obj_kouhai['id']]) }}">プロフィール</a></p></div>
                        </li>
                    </ul>

                </div>
            </div>

        </section>

        <section>

            <div class="inner_box">
                <h3>レッスン詳細</h3>
                <div class="white_box">
                    <ul class="list_box">
                        <li class="pt0 pb0">
                            <ul class="info_ttl_wrap">
                                <li>
                                    <img src="{{ asset('storage/class_icon/' . $obj_lesson['lesson_class']['class_icon']) }}" alt="">
                                </li>
                                <li>
                                    <p class="info_ttl">{{ $obj_lesson['lesson_title'] }}</p>
                                </li>
                            </ul>
                        </li>

                        <li class="lesson_naiyou">
                            <div>
                                <p>レッスン日時：</p>
                                <p class="ls1">{{ \App\Service\CommonService::getYMD($obj_lrs['lrs_date']) }}
                                    {{ \App\Service\CommonService::getStartAndEndTime($obj_lrs['lrs_start_time'], $obj_lrs['lrs_end_time']) }}</p>
                            </div>
                            <div>
                                <p>予約成立日   ：</p>
                                <p>{{ $obj_lrs['lrs_reserve_date'] ? \App\Service\CommonService::getYMDAndHM($obj_lrs['lrs_reserve_date']) : '' }}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>レッスン料金</p>
                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($obj_lrs['lrs_amount']) }}</p>
                            </div>

                            <div>
                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-service">サービス料</a>
                                </p>
                                <p>{{ \App\Service\CommonService::showFormatNum($obj_lrs['lrs_service_fee']) }}</p>
                            </div>

                            {{--<div>
                                <p>出張交通費</p>
                                <p class="price_mark">{{ \App\Service\CommonService::showFormatNum($obj_lrs['lrs_traffic_fee']) }}</p>
                            </div>--}}
                        </li>

                        <li>
                            <div>
                                <p>合計（税込）</p>
                                <p class="price_mark"><strong>{{ \App\Service\CommonService::showFormatNum($obj_lrs['lrs_amount'] + $obj_lrs['lrs_traffic_fee'] + $obj_lrs['lrs_service_fee']) }}</strong></p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

        </section>

    </div><!-- /contents -->

    @include ('user.layouts.modal')

    <footer>

        @include('user.layouts.fnavi')

    </footer>

@endsection
