@extends('admin.layouts.app')
@section('title', '回答詳細')

@section('content')
    @php
        use App\Service\CommonService;
    @endphp

    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.patrol.request_answer', $index_params)])

        @php $sex = ['', '女性', '男性']; @endphp
        <input type="hidden" name="lr_id" value="{{ $obj_lesson_request->lr_id }}">
        <section>
            <div class="lesson_info_area">
                <ul class="teacher_info_02">
                    <li class="icon"><img src="{{\App\Service\CommonService::getUserAvatarUrl($obj_lesson_request->user['user_avatar'])}}" class="プロフィールアイコン"></li>
                    <li class="about_teacher">
                        <div class="profile_name"><p>{{$obj_lesson_request->user['name']}}<span>（{{\App\Service\CommonService::getAge($obj_lesson_request->user['user_birthday'])}}）{{$sex[$obj_lesson_request->user['user_sex']]}}</span></p></div>
                        <div><p class="orange_link icon_arrow orange_right"><a href="{{route('user.myaccount.profile', ['user_id'=>$obj_lesson_request->user['id']])}}">プロフィール</a></p></div>
                    </li>
                </ul>
            </div>
        </section>
        <section class="pb10">
            <div class="inner_box">
                <h3 class="summary_ttl">
                    <span>レッスン概要</span>
                    <span class="shounin_kigen">承認期限：<big>{{ltrim(date('m', strtotime($obj_lesson_request['lr_until_confirm'])), '0')}}</big>月<big>{{ltrim(date('d', strtotime($obj_lesson_request['lr_until_confirm'])), '0')}}</big>日</span>
                </h3>
                <div class="white_box">
                    <div class="lesson_ttl_02">
                        <p>
                            {{$obj_lesson_request['lesson']['lesson_title']}}
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="lesson_place">
                        @if($obj_lesson_request['lr_pos_discuss'] == 1)
                            <p>
                                {{ $obj_lesson_request['discuss_lesson_area'] }}
                            </p>
                            <p>
                                {{ $obj_lesson_request['lr_address'] }}
                            </p>
                        @else
                            <p>
                                {{ implode('/', $obj_lesson_request['lesson']['lesson_area_names']) }}
                            </p>
                        @endif
                    </div>

                    @if($obj_lesson_request['lr_pos_discuss'] == 1)
                        <div class="balloon balloon_blue font-small">
                            <p>{{ $obj_lesson_request['lr_address_detail'] }}</p>
                        </div>
                    @else
                        <div class="balloon balloon_blue font-small">
                            <p>{{ $obj_lesson_request['lesson']['lesson_pos_detail'] }}</p>
                        </div>
                    @endif

                </div>
            </div>

            <div class="inner_box evaluation_area">
                <h3>評価（{{\App\Service\CommonService::showFormatNum(\App\Service\EvalutionService::getLessonEvalutionCount($obj_lesson_request['lr_lesson_id'], \App\Service\EvalutionService::SENPAIS_EVAL))}}件）</h3>
                <input id="evaluation-check1" name="acd" class="acd-check" type="checkbox">
                <label class="acd-label" for="evaluation-check1">{{ $obj_lesson_request->user['name'] }}さんの評価を確認する</label>
                @php
                    $evalution = \App\Service\EvalutionService::getLessonEvalutionPercentByType($obj_lesson_request['lr_lesson_id'], \App\Service\EvalutionService::SENPAIS_EVAL)
                @endphp

                @if(isset($evalution) && !empty($evalution))
                    <div class="acd-content evaluation-content">
                        <div class="box-hide">
                            <h4>他のセンパイは{{ $obj_lesson_request->user['name'] }}さんをこのように評価しています</h4>
                            <ul class="evaluation_list">
                                @foreach($evalution as $key => $value)
                                    <li>
                                        <div>{{$value['type_name']}}</div>
                                        <div class="score"><span>{{$value['percent']}}</span></div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <p class="acc-close">閉じる</p>
                    </div>
                @endif
            </div>
            @if(isset($confirm_lesson_request_schedules) && count($confirm_lesson_request_schedules) > 0)
                <div class="inner_box  for-warning">
                    <h3>承認するレッスン</h3>
                    <p class="warning"></p>
                    <div class="white_box">
                        <div class="check-box"  id="approval_checkbox">
                            @foreach($confirm_lesson_request_schedules as $k=>$v)
                                @if($v['lrs_state'] == config('const.schedule_state.cancel_senpai') || $v['lrs_state'] == config('const.schedule_state.cancel_kouhai') || $v['lrs_state'] == config('const.schedule_state.cancel_system') || $v['lrs_state'] == config('const.schedule_state.reject_senpai'))
                                @else
                                    <div class="clex-box_02">
                                        <p>{{\App\Service\CommonService::getMD($v['lrs_date'])}}（{{\App\Service\CommonService::getWeekday($v['lrs_date'])}}）{{\App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time'])}}　{{\App\Service\CommonService::showFormatNum($v['lrs_amount'])}}円</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            @if(isset($cancel_lesson_request_schedules) && count($cancel_lesson_request_schedules) > 0)
                <div class="inner_box  for-warning">
                    <h3>辞退するレッスン</h3>
                    <p class="warning"></p>
                    <div class="white_box">
                        <div class="check-box" id="cancel_checkbox">
                            @foreach($cancel_lesson_request_schedules as $k=>$v)
                                @if($v['lrs_state'] == config('const.schedule_state.cancel_senpai') || $v['lrs_state'] == config('const.schedule_state.cancel_kouhai') || $v['lrs_state'] == config('const.schedule_state.cancel_system') || $v['lrs_state'] == config('const.schedule_state.reject_senpai'))
                                    <div class="clex-box_02">
                                        <p>{{\App\Service\CommonService::getMD($v['lrs_date'])}}（{{\App\Service\CommonService::getWeekday($v['lrs_date'])}}）{{\App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time'])}}　{{\App\Service\CommonService::showFormatNum($v['lrs_amount'])}}円</p>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        @if(isset($cancel_reason_types) && count($cancel_reason_types) > 0)
                            <div class="inner_box sub_box"  id="cancel_box" style="display: none;">
                                <h3 class="must">辞退の理由</h3>
                                <div class="check-box"  id="reason_checkbox">
                                    @foreach( $cancel_reason_types as $key => $value)
                                        <div class="clex-box_02">
                                            <input type="checkbox" name="commitment" value="{{ $value['crt_id'] }}" id="reason-{{ $value['crt_id'] }}" @if ( $value['crt_id'] == config('const.senpai_cancel_other_reason_id') ) class= "click-balloon" onclick="showBalloon()" @endif>
                                            <label for="reason-{{ $value['crt_id'] }}"><p>{{ $value['crt_content'] }}</p></label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <div class="white_box">
                <div class="btn mtb">
                    <button type="button" class="modal-syncer" data-target="{{ $obj_lesson_request->lr_state == config('const.req_state.reserve') ? "btn_not_stop" : "btn_stop" }}">このリクエストを削除する</button>
                </div>
                <div class="btn mtb">
                    <button type="button" onclick="location.href='{{ route('admin.patrol.request_answer', $index_params) }}'">戻る</button>
                </div>
            </div>

        </section>

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"btn_stop",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"このリクエストを削除しても<br>よろしいですか？",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"戻る",
        ])

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"btn_not_stop",
            'modal_type'=>config('const.modal_type.alert'),
            'modal_title'=>"予約中のリクエストは削除できません。",
            'modal_confrim_btn'=>"OK",
        ])

    </div><!-- /contents -->

@endsection

@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
        .acc-close {
            cursor: pointer;
        }
        .arrow-bottom {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-top: 1px solid #fb7122;
            border-bottom: 1px solid #fb7122;
            margin-bottom: 10px;
            margin-top: 10px;
            padding: 10px;
            color: #fb7122;
        }
        .prof {
            padding-top: 0px !important;
            margin-top: 10px;
        }
        .back_wrap .inner_box {
            padding: 0px 20px 30px !important;
        }
    </style>
@endsection

@section('page_js')
    <script>
        $.document.ready(function() {

        });

        function modalConfirm(modal_id="") {
            // code
            location.href='{{ route('admin.patrol.alert_create', ['lessonRequest'=>$obj_lesson_request->lr_id, 'page_type'=>'request_answer']) }}';
        }
    </script>
@endsection
