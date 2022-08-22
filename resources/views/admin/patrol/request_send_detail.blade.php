@extends('admin.layouts.app')
@section('title', 'リクエスト詳細')

@section('content')
    @php
        use App\Service\CommonService;
    @endphp

    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.patrol.request_send', $index_params)])

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

            <div class="inner_box">
                <h3>日時・料金</h3>
                <div class="white_box">
                    <ul>
                        @foreach($lesson_request_schedules as $lrs)
                            @php
                                $lrs_info = CommonService::getMD($lrs['lrs_date']).'|'.CommonService::getStartAndEndTime($lrs['lrs_start_time'], $lrs['lrs_end_time']).'|'.CommonService::showFormatNum($lrs['lrs_amount']);
                            @endphp
                            <li class="flex-space pb-5 fs-13">
                                <div>
                                    {{CommonService::getMD($lrs['lrs_date'])}}&nbsp;&nbsp;&nbsp;{{CommonService::getStartAndEndTime($lrs['lrs_start_time'], $lrs['lrs_end_time'])}}
                                </div>
                                <div>{{CommonService::showFormatNum($lrs['lrs_amount'])}}円</div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            @if($obj_lesson_request->lr_state == config('const.req_state.reserve'))
                <div class="inner_box">
                    <h3>購入日</h3>
                    <div class="white_box fs-13">
                        {{ CommonService::getMD($obj_lesson_request['lr_reserve_date']) }}
                    </div>
                </div>
            @else
                <div class="inner_box">
                    <h3>承認期限</h3>
                    <div class="white_box fs-13">
                        {{ CommonService::getMD($obj_lesson_request['lr_until_confirm']) }}
                    </div>
                </div>
            @endif

            <div class="inner_box">
                <h3>送信元・送信相手</h3>
                <div class="white_box">
                    <div class="flex profile from-user" data-id="{{ $obj_lesson_request->user->id }}">
                        <a style="display: flex;" href="{{ route('admin.staff.detail', ['staff'=>$obj_lesson_request->user->id]) }}">
                            <div class="ico ico-user">
                                <img src="{{ $obj_lesson_request->user->avatar_path }}">
                            </div>
                            <div>
                                <div class="pb-5 ft-bold">{{ $obj_lesson_request->user->user_name }}<span>{{ "（".\App\Service\CommonService::getAge($obj_lesson_request->user->user_birthday)."）" }}</span>{{ $obj_lesson_request->user->user_sex ? config('const.gender_type.'.$obj_lesson_request->user->user_sex) : '' }}</div>
                                <div class="pb-5">{{ $obj_lesson_request->user->user_area_name."　" }} @if($obj_lesson_request->user->is_person_confirm)本人確認済み@endif</div>
                            </div>
                        </a>
                    </div>
                    <div class="arrow-bottom">↓</div>
                    <div class="flex profile to-user" data-id="{{ $obj_lesson_request->lesson->senpai->id }}">
                        <a style="display: flex;" href="{{ route('admin.staff.detail', ['staff'=>$obj_lesson_request->lesson->senpai->id]) }}">
                            <div class="ico ico-user">
                                <img src="{{ $obj_lesson_request->lesson->senpai->avatar_path }}">
                            </div>
                            <div>
                                <div class="pb-5 ft-bold">{{ $obj_lesson_request->lesson->senpai->user_name }}<span>{{ "（".\App\Service\CommonService::getAge($obj_lesson_request->lesson->senpai->user_birthday)."）" }}</span>{{ $obj_lesson_request->lesson->senpai->user_sex ? config('const.gender_type.'.$obj_lesson_request->lesson->senpai->user_sex) : '' }}</div>
                                <div class="pb-5">{{ $obj_lesson_request->lesson->senpai->user_area_name."　" }} @if($obj_lesson_request->lesson->senpai->is_person_confirm)本人確認済み@endif</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="white_box">
                <div class="btn mtb">
                    <button type="button" class="modal-syncer" data-target="{{ $obj_lesson_request->lr_state == config('const.req_state.reserve') ? "btn_not_stop" : "btn_stop" }}">このリクエストを削除する</button>
                </div>
                <div class="btn mtb">
                    <button type="button" onclick="location.href='{{ route('admin.patrol.request_send', $index_params) }}'">戻る</button>
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
        .arrow-bottom {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            border-top: 1px solid #fb7122;
            border-bottom: 1px solid #fb7122;
            margin-bottom: 5px;
            margin-top: 5px;
            padding: 10px;
            color: #fb7122;
        }
        .from-user {
            padding-top: 5px;
            border-top: 1px solid #fb7122;
        }
        .to-user {
            padding-bottom: 5px;
            border-bottom: 1px solid #fb7122;
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
            location.href='{{ route('admin.patrol.alert_create', ['lessonRequest'=>$obj_lesson_request->lr_id, 'page_type'=>'request_send']) }}';
        }
    </script>
@endsection
