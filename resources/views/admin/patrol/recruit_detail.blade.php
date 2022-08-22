@extends('admin.layouts.app')
@section('title', '募集の詳細')

@section('content')

    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.patrol.recruit')])

        {{ Form::open(["route"=>["keijibann.input"], "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
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
                                        <a href="{{ route('admin.staff.detail', ['staff'=>$data['cruitUser']['id']]) }}">プロフィール</a>
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
                <input type="hidden" name="rc_id" value="@php echo $data['rc_id']; @endphp" >

                <div class="innerecruiting_prop_detailr_box">
                    <h3 class="flex-box01">提案一覧<span>全 {{$total}}件</span></h3>
                    @foreach($proposals as $key=>$val)
                        <div class="board_box no-filter shadow-glay">
                            <div class="lesson_info_area">
                                <ul class="no-space teacher_info_02">
                                    <li class="icon"><img src="{{\App\Service\CommonService::getUserAvatarUrl($val['proposalUser']['user_avatar'])}}" class="プロフィールアイコン"></li>
                                    <li class="about_teacher">
                                        <div class="profile_name">
                                            <p><a href="{{ route('admin.staff.detail', ['staff'=>$val['proposalUser']['id']]) }}" class="underline">{{$val['proposalUser']['name']}}</a><span>（{{$val['age']}}）{{$val['sex']}}}</span></p>
                                        </div>
                                        <div>
                                            <p class="">提案金額:  <span class="black"><em>{{\App\Service\CommonService::showFormatNum($val['pro_money'])}}</em>円</span></p>
                                        </div>
                                    </li>
                                </ul>
                                <div class="base_txt mt20 mb20">
                                    <p>{{$val['pro_msg']}}</p>
                                </div>
                            </div>
                            <div class="form_wrap3 icon_form mb20">
                                <button type="button" onclick="location.href='{{route("admin.patrol.recruit.proposal") . "/" . $val["pro_id"]}}'" class="form_btn3 f14 shadow bold">
                                    <div class="payment_box2">提案内容の詳細</div>
                                </button>
                            </div>
                        </div>
                    @endforeach

                </div>

                @if($data['rc_stop'] != config('const.lesson_stop_code.stop_lesson'))
                    <div class="white_box">
                        <div class="btn mtb">
                            <button type="button" class="modal-syncer" data-target="btn_stop">この投稿を公開停止する</button>
                        </div>
                    </div>
                @endif

            </section>

        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"btn_stop",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"<div class='toukou_kanryou'><p><img class='modal-avatar' src='".\App\Service\CommonService::getUserAvatarUrl($data['cruitUser']['user_avatar'])."'></p><p>".$data['cruitUser']['name']."<small>さんの</small>この投稿を</p></div>"."公開停止します。<br>よろしいですか？",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"戻る",
        ])


    </div><!-- /contents -->

@endsection

@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
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
            location.href='{{ route('admin.patrol.stop_recruit', ['recruit'=>$data['rc_id']]) }}';
        }
    </script>
@endsection
