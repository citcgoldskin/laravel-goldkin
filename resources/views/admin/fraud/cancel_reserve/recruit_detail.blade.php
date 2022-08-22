@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.fraud_cancel_reserve.recruit_delete", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <input type="hidden" name="recruit_id" value="{{ $data['rc_id'] }}">
        <section class="prof">

            <div class="back_wrap profile_area">
                <div class="inner_box main-prof">
                    <div class="profile_base_02">
                        <ul>
                            <li>
                                <div class="profile_img">
                                    <p><img src="{{ App\Service\CommonService::getUserAvatarUrl($data['cruitUser']['user_avatar'])}}" alt=""></p>
                                </div>
                            </li>
                            <li class="profile_name"><p><big>{{$data['cruitUser']['name']}}</big><span>（{{ isset($data['age']) ? $data['age'] : '' }}）<?php echo $data['sex']; ?></span></p></li>
                        </ul>
                        <ul class="profile_info">
                            <li class="jisseki">
                                <p>購入件数 <span>{{$buy_count}}</span><span>件</span></p>
                                <p>レッスン件数 <span>{{$sell_count}}</span><span>件</span></p>
                            </li>
                            <li class="t_right">
                                <p class="orange_link icon_arrow orange_right">
                                    <a href="{{route('admin.staff.detail',['staff'=>$data['rc_user_id']])}}">プロフィール</a>
                                </p>
                            </li>
                        </ul>
                    </div>

                    <div class="cate_icon">
                        <img src="{{ App\Service\CommonService::getLessonClassIconUrl($data['cruitLesson']['class_icon'])}}" alt="">
                    </div>
                    <h3 class="prof-title">
                        {{$data['rc_title']}}
                    </h3>

                    <div class="base_txt">
                        <p>
                            {{$data['rc_detail']}}
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box">
                <h3>募集条件</h3>
                <div class="white_box">
                    <ul class="list_box about_detail_gray">

                        <li>
                            <div>
                                <p class="member normal">参加人数</p>
                                <p>男性{{$data['rc_man_num']}}人　女性{{$data['rc_woman_num']}}人</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p class="money_02 normal">予算</p>
                                <p>{{ \App\Service\CommonService::getLessonMoneyRange($data['rc_wish_minmoney'], $data['rc_wish_maxmoney'], true) }}<small>／{{ \App\Service\CommonService::getTimeUnit($data['rc_lesson_period_from']) }}</small></p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p class="attention_update_03 normal">レッスン開始日時</p>
                                <p>{{$data['date']}}  {{$data['start_end_time']}}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p class="time_limit normal">提案期限</p>
                                <p>{{$data['date_limit']}}</p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="inner_box">
                <h3>レッスン場所</h3>
                <div class="white_box">
                    <div class="form_txt">
                        <p>{{ implode('/', $data->recruit_area_names) }}</p>
                    </div>
                </div>
            </div>

            <div class="inner_box mb-20">
                <h3>取り消し日時</h3>
                <div class="white_box base_txt">
                    <p>{{ \Carbon\Carbon::parse($data->rc_stop_cancel_reverse_at)->format('Y年n月j日 H時i分') }}</p>
                </div>
            </div>

        </section>

        <div id="footer_button_area" class="under_area">
            <ul>
                <li class="send-request">
                    <div class="btn_base btn_orange shadow">
                        <button type="button" class="modal-syncer" data-target="btn_cancel_reserve">予約を削除する</button>
                    </div>
                </li>
            </ul>
        </div>

        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"btn_cancel_reserve",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"予約を削除します。<br>よろしいですか?",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"戻る",
        ])

    </div>
@endsection
@section('page_css')
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
            $('#form1').submit();
        }
    </script>
@endsection
