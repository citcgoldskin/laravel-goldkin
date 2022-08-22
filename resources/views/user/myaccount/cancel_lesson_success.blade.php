@extends('user.layouts.app')

@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <div id="completion_wrap" class="other_page">
        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">キャンセル完了</h2>

                <div class="modal_txt">
                    <p>
                        キャンセルが完了しました。<br>
                        またのご利用をお待ちしております。
                    </p>
                </div>
            </div>
        </div>

    </div>

    <!--main_-->
    {{--{{ Form::open(["route"=>"user.myaccount.master_lesson_history", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}--}}

        <section>

            <div class="inner_box">
                <h3>キャンセルしたレッスン</h3>
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
                                    <a class="modal-syncer" data-target="modal-service">現在の手数料率</a>
                                </p>
                                <p>{{ \App\Service\CommonService::getFeeTypeStr($schedule_info['lrs_fee_type']) }}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p>合計（税込）</p>
                                <p class="price_mark"><strong>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount'] - $schedule_info['lrs_fee']) }}</strong></p>
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
                <div class="btn_base btn_ok">
                    <a href="{{ route('user.myaccount.master_lesson_history') }}">
                        OK
                    </a>
                </div>
            </div>


        </section>

    {{--{{ Form::close() }}--}}

</div><!-- /contents -->

@include('user.layouts.modal')

@endsection
