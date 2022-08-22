@extends('user.layouts.app')
@section('title', 'リクエスト内容の確認変更')
<!-- ************************************************************************
本文
************************************************************************* -->
@php
    use \App\Service\CommonService;
@endphp
@section('content')
    @include('user.layouts.header_under')

    <div id="contents" >

        <!--main_-->
        <section>

            <div class="white_box shadow-glay">
                <span class="choice_lesson">選択中のレッスン！</span>
                <ul class="reserved_top_box mt10">
                    <li><img src=" {{ \App\Service\CommonService::getLessonImgUrl(\App\Service\LessonService::getLessonFirstImage($req_info['lesson'])) }}" alt=""></li>
                    <li>
                        <p class="lesson_ttl">{{ $req_info['lesson']['lesson_title'] }}</p>
                        <div class="inline_flex">
                            {{--<p class="icon_taimen">{{ \App\Service\CommonService::getLessonMode($req_info['lesson']['lesson_type']) }}</p>--}}
                            <p class="orange_link icon_arrow orange_right">
                                <a href=" {{ route('user.lesson.lesson_view', ['lesson_id'=>$req_info['lesson']['lesson_id']]) }}">詳細を見る</a>
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </section>

        <section>

            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="lesson_place">
                        @if($req_info['lr_pos_discuss'] == 1)
                            <p>
                                {{ $req_info['discuss_lesson_area'] }}
                            </p>
                            <p>
                                {{ $req_info['lr_address'] }}
                            </p>
                        @else
                            <p>
                                {{ implode('/', $req_info['lesson']['lesson_area_names']) }}
                            </p>
                        @endif
                    </div>
                    <div class="balloon balloon_blue">
                        @if($req_info['lr_pos_discuss'] == 1)
                            <p>{{ $req_info['lr_address_detail'] }}</p>
                        @else
                            <p>{{ $req_info['lesson']['lesson_pos_detail'] }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @php
                $show_request_cancel = false;
            @endphp
            <div class="inner_box">
                <h3>レッスン日時</h3>

                <div class="board_box effect_none">
                    <ul class="list_area bo pa">
                        @foreach( $req_info['lesson_request_schedule'] as $k => $v)
                            @if($v['lrs_state'] == config('const.schedule_state.cancel_senpai') || $v['lrs_state'] == config('const.schedule_state.cancel_kouhai') || $v['lrs_state'] == config('const.schedule_state.cancel_system') || $v['lrs_state'] == config('const.schedule_state.reject_senpai'))
                            @else
                                @php
                                    $show_request_cancel = true;
                                @endphp
                                <li>
                                    <div>{{ CommonService::getYMDAndWeek($v['lrs_date']) }}　{{ CommonService::getStartAndEndTime($v['lrs_start_time'],$v['lrs_end_time']) }}</div>
                                    <div>{{ CommonService::showFormatNum($v['lrs_amount']) }}円</div>
                                </li>
                            @endif
                        @endforeach
                        @if(!$show_request_cancel)
                                <li class="pd-0">なし</li>
                        @endif
                    </ul>
                </div>
            </div>

            <div class="inner_box">
                <h3>リクエストの承認期限</h3>
                <div class="white_box">
                    <div class="base_txt">
                        <p>{{ CommonService::getYMD($req_info['lr_until_confirm']) }}</p>
                    </div>
                </div>
            </div>


        </section>

        <div class="white-bk">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <button type="button" onclick="location.href='{{ route('user.talkroom.requestEdit', ['req_id' => $req_info['lr_id']]) }}'">リクエスト内容を変更する</button>
                </div>
                @if($show_request_cancel)
                    <div class="btn_base btn_pale-gray shadow-glay">
                        <button type="button" onclick="location.href='{{ route('user.talkroom.requestCancel', ['req_id' => $req_info['lr_id']]) }}'">リクエストをキャンセルする</button>
                    </div>
                @endif
            </div>
        </div>

    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection


