@extends('admin.layouts.app')
@section('title', '掲示板履歴確認')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_history_management.recruit')])

        {{--{{ Form::open(["route"=>"admin.fraud_cancel_reserve.recruit_delete", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}--}}

        <section id="info_area" style="padding-bottom: 0px;">

            <div class="inner_box mb-20">
                <h3>履歴</h3>
                <div class="white_box base_txt">
                    @if(count($recruit_histories) > 0)
                        @foreach($recruit_histories as $history)
                            <p>{{ $history }}</p>
                        @endforeach
                    @else
                        <p>なし</p>
                    @endif
                </div>
            </div>
            <div class="inner_box mb-20">
                <h3>掲示板投稿タイトル・後輩</h3>
                <div class="white_box base_txt">
                    <div style="border-bottom:2px solid #ddd;margin-bottom: 10px;">
                        <a class="" href="">
                            <div>{{ $recruit->rc_title }}</div>
                            <div class="al-r">購入実績：{{ "00件" }}</div>
                        </a>
                    </div>
                    <div class="flex profile" data-id="{{ $recruit->cruitUser->id }}">
                        <a style="display: flex;" href="{{ route('admin.staff.detail', ['staff'=>$recruit->cruitUser->id]) }}">
                            <div class="ico ico-user">
                                <img src="{{ $recruit->cruitUser->avatar_path }}">
                            </div>
                            <div>
                                <div class="pb-5 ft-bold">{{ $recruit->cruitUser->user_name }}<span>{{ "（".\App\Service\CommonService::getAge($recruit->cruitUser->user_birthday)."）" }}</span>{{ $recruit->cruitUser->user_sex ? config('const.gender_type.'.$recruit->cruitUser->user_sex) : '' }}</div>
                                <div class="pb-5">{{ $recruit->cruitUser->user_area_name."　" }} @if($recruit->cruitUser->is_person_confirm)本人確認済み@endif</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            <div class="inner_box mb-20">
                <h3>一緒追加する方</h3>
                <div class="white_box base_txt">
                    {{--<p class="lesson_price"><em>{{ $diff_time }}</em>分・<em>{{ $lesson_request_schedule->lrs_amount }}円</em></p>--}}
                    <p>男性 {{$recruit->rc_man_num}}人　女性 {{$recruit->rc_woman_num}}人</p>
                </div>
            </div>
            <div class="inner_box mb-20">
                <h3>レッスン時間・総料金</h3>
                @if(isset($recruit->proposed_senpai) && $recruit->proposed_senpai)
                    <div class="white_box base_txt">
                        <p class="lesson_price"><em>{{ $recruit->proposed_senpai->pro_lesson_period_start }}</em>分・<em>{{ $recruit->proposed_senpai->pro_money }}円</em></p>
                    </div>
                @else
                    <div class="white_box base_txt">
                        <p class="lesson_price"><em>{{ \App\Service\CommonService::getStartAndEndTime($recruit->rc_start_time, $recruit->rc_end_time) }}</em>分・<em>{{ \App\Service\CommonService::getLessonMoneyRange($recruit->rc_wish_minmoney, $recruit->rc_wish_maxmoney) }}</em></p>
                    </div>
                @endif
            </div>
            <div class="inner_box mb-20">
                <h3>待ち合わせ場所</h3>
                <div class="white_box base_txt">
                    <p>{{ implode('/', $recruit->recruit_area_names) }}</p>
                    @if(isset($recruit->rc_place_detail) && $recruit->rc_place_detail)
                        <div class="balloon balloon_blue">
                            <p>{{ $recruit->rc_place_detail }}</p>
                        </div>
                    @endif
                </div>
            </div>
            <div class="inner_box mb-20">
                <h3>レッスン相手</h3>
                <div class="white_box base_txt">
                    @if(isset($recruit->proposed_senpai) && $recruit->proposed_senpai && $recruit->proposed_senpai->proposalUser)
                        <div class="flex profile" data-id="{{ $recruit->proposed_senpai->proposalUser->id }}">
                            <a style="display: flex;" href="{{ route('admin.staff.detail', ['staff'=>$recruit->proposed_senpai->proposalUser->id]) }}">
                                <div class="ico ico-user">
                                    <img src="{{ $recruit->proposed_senpai->proposalUser->avatar_path }}">
                                </div>
                                <div>
                                    <div class="pb-5 ft-bold">{{ $recruit->proposed_senpai->proposalUser->user_name }}<span>{{ "（".\App\Service\CommonService::getAge($recruit->proposed_senpai->proposalUser->user_birthday)."）" }}</span>{{ $recruit->proposed_senpai->proposalUser->user_sex ? config('const.gender_type.'.$recruit->proposed_senpai->proposalUser->user_sex) : '' }}</div>
                                    <div class="pb-5">{{ $recruit->proposed_senpai->proposalUser->user_area_name."　" }} @if($recruit->proposed_senpai->proposalUser->is_person_confirm)本人確認済み@endif</div>
                                </div>
                            </a>
                        </div>
                    @else
                        <div>未定</div>
                    @endif
                </div>
            </div>
            <div class="inner_box mb-20">
                <h3>レッスン日時</h3>
                @if(isset($recruit->proposed_senpai) && $recruit->proposed_senpai)
                    <div class="white_box base_txt">
                        <p>{{ \App\Service\CommonService::getYMD($recruit->rc_date) }} {{ \App\Service\CommonService::getStartAndEndTime($recruit->proposed_senpai->pro_start_time, $recruit->proposed_senpai->pro_end_time) }}</p>
                    </div>
                @else
                    <div class="white_box base_txt">
                        <p>{{ \App\Service\CommonService::getYMD($recruit->rc_date) }} {{ \App\Service\CommonService::getStartAndEndTime($recruit->pro_start_time, $recruit->pro_end_time) }}</p>
                    </div>
                @endif
            </div>


        </section>

        <section>
            <div class="white_box">
                @if($recruit->rc_stop == config('const.lesson_stop_code.break_lesson'))
                @else
                    <div class="btn mtb">
                        <button type="button" class="modal-syncer" data-target="btn_cancel_reserve">このレッスンを中断する</button>
                    </div>
                @endif
                <div class="btn mtb">
                    <button type="button" onclick="location.href='{{ route('admin.fraud_piro.create', ['user'=>$recruit->cruitUser->id, 'page_from'=>'recruit_history']) }}'">ぴろしきまる</button>
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
        .back_wrap .inner_box {
            padding: 0px 20px 30px !important;
        }

    </style>
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {
        });

        // modal confirm function
        function modalConfirm(modal_id="") {
            // code
            location.href='{{ route('admin.lesson_history_management.alert_recruit_create', ['recruit'=>$recruit]) }}';
        }
    </script>
@endsection
