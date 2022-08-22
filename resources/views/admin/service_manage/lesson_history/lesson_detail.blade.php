@extends('admin.layouts.app')
@section('title', 'レッスン履歴確認')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_history_management.lesson')])

        {{--{{ Form::open(["route"=>"admin.lesson_history_management.alert_create", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}--}}

            <section id="info_area" style="padding-bottom: 0px;">

                <div class="inner_box mb-20">
                    <h3>履歴</h3>
                    <div class="white_box base_txt">
                        @if(!is_null($lesson_request_schedule->lrs_request_date) && $lesson_request_schedule->lrs_request_date)
                            <p>{{ \Carbon\Carbon::parse($lesson_request_schedule->lrs_request_date)->format('Y年n月j日 H:i') }}{{ config('const.request_type_category.'.$lesson_request_schedule->lesson_request->lr_type) }}リクエスト送信</p>
                        @endif
                        @if(!is_null($lesson_request_schedule->lrs_confirm_date) && $lesson_request_schedule->lrs_confirm_date)
                            <p>{{ \Carbon\Carbon::parse($lesson_request_schedule->lrs_confirm_date)->format('Y年n月j日 H:i') }}{{ config('const.request_type_category.'.$lesson_request_schedule->lesson_request->lr_type) }}リクエスト回答</p>
                        @endif
                        @if(!is_null($lesson_request_schedule->lrs_reserve_date) && $lesson_request_schedule->lrs_reserve_date)
                            <p>{{ \Carbon\Carbon::parse($lesson_request_schedule->lrs_reserve_date)->format('Y年n月j日 H:i') }}レッスン購入済み</p>
                        @endif
                    </div>
                </div>
                <div class="inner_box mb-20">
                    <h3>レッスン名・先輩</h3>
                    <div class="white_box base_txt">
                        <div style="border-bottom:2px solid #ddd;margin-bottom: 10px;">
                            <a class="" href="">
                                <div>{{ $obj_lesson->lesson_title }}</div>
                                <div class="al-r">レッスン実績：{{ "00件" }}</div>
                            </a>
                        </div>
                        <div class="flex profile" data-id="{{ $lesson_request_schedule->senpai->id }}">
                            <a style="display: flex;" href="{{ route('admin.staff.detail', ['staff'=>$lesson_request_schedule->senpai->id]) }}">
                                <div class="ico ico-user">
                                    <img src="{{ $lesson_request_schedule->senpai->avatar_path }}">
                                </div>
                                <div>
                                    <div class="pb-5 ft-bold">{{ $lesson_request_schedule->senpai->user_name }}<span>{{ "（".\App\Service\CommonService::getAge($lesson_request_schedule->senpai->user_birthday)."）" }}</span>{{ $lesson_request_schedule->senpai->user_sex ? config('const.gender_type.'.$lesson_request_schedule->senpai->user_sex) : '' }}</div>
                                    <div class="pb-5">{{ $lesson_request_schedule->senpai->user_area_name."　" }} @if($lesson_request_schedule->senpai->is_person_confirm)本人確認済み@endif</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="inner_box mb-20">
                    <h3>レッスン時間・総料金</h3>
                    <div class="white_box base_txt">
                        @php
                            $start_time = \Carbon\Carbon::parse($lesson_request_schedule->lrs_start_time)->diffInMinutes();
                            $end_time = \Carbon\Carbon::parse($lesson_request_schedule->lrs_end_time)->diffInMinutes();
                            $diff_time = abs($end_time-$start_time);
                        @endphp
                        <p class="lesson_price"><em>{{ $diff_time }}</em>分・<em>{{ $lesson_request_schedule->lrs_amount }}円</em></p>
                    </div>
                </div>
                <div class="inner_box mb-20">
                    <h3>待ち合わせ場所</h3>
                    <div class="white_box base_txt">
                        <p>{{ $obj_lesson->lesson_area_names ? implode('/', $obj_lesson->lesson_area_names) : '' }}</p>
                        @if(isset($obj_lesson->lesson_pos_detail) && $obj_lesson->lesson_pos_detail)
                            <div class="balloon balloon_blue">
                                <p>{{$obj_lesson->lesson_pos_detail}}</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="inner_box mb-20">
                    <h3>レッスン相手</h3>
                    <div class="white_box base_txt">
                        <div class="flex profile" data-id="{{ $lesson_request_schedule->kouhai->id }}">
                            <a style="display: flex;" href="{{ route('admin.staff.detail', ['staff'=>$lesson_request_schedule->kouhai->id]) }}">
                                <div class="ico ico-user">
                                    <img src="{{ $lesson_request_schedule->kouhai->avatar_path }}">
                                </div>
                                <div>
                                    <div class="pb-5 ft-bold">{{ $lesson_request_schedule->kouhai->user_name }}<span>{{ "（".\App\Service\CommonService::getAge($lesson_request_schedule->kouhai->user_birthday)."）" }}</span>{{ $lesson_request_schedule->kouhai->user_sex ? config('const.gender_type.'.$lesson_request_schedule->kouhai->user_sex) : '' }}</div>
                                    <div class="pb-5">{{ $lesson_request_schedule->kouhai->user_area_name."　" }} @if($lesson_request_schedule->kouhai->is_person_confirm)本人確認済み@endif</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="inner_box mb-20">
                    <h3>レッスン日時</h3>
                    <div class="white_box base_txt">
                        <p>{{ \Carbon\Carbon::parse($lesson_request_schedule->lrs_date)->format('Y年n月j日') }} {{ $lesson_request_schedule->lrs_start_time.'～'.$lesson_request_schedule->lrs_end_time }}</p>
                    </div>
                </div>


            </section>

            <section>
                <div class="white_box">
                    @if($lesson_request_schedule->lrs_state == config('const.schedule_state.request') || $lesson_request_schedule->lrs_state == config('const.schedule_state.confirm') || $lesson_request_schedule->lrs_state == config('const.schedule_state.reserve'))
                        <div class="btn mtb">
                            <button type="button" class="modal-syncer" data-target="btn_cancel_reserve">このレッスンを中断する</button>
                        </div>
                    @endif
                    <div class="btn mtb">
                        <button type="button" onclick="location.href='{{ route('admin.fraud_piro.create', ['user'=>$lesson_request_schedule->senpai->id, 'page_from'=>'lesson_history']) }}'">ぴろしきまる</button>
                    </div>
                </div>
            </section>


        {{--{{ Form::close() }}--}}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"btn_cancel_reserve",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"このレッスンを<br>中断(無料キャンセル)しても<br>よろしいですか？",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"戻る",
        ])

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>


    </style>
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {

        });

        // modal confirm function
        function modalConfirm(modal_id="") {
            // code
            //$('#form1').submit();
            location.href='{{ route('admin.lesson_history_management.alert_create', ['lesson_request_schedule'=>$lesson_request_schedule->lrs_id]) }}';
        }
    </script>
@endsection
