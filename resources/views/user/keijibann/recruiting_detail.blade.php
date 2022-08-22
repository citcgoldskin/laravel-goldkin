@extends('user.layouts.app')

@section('title', '募集内容の編集・提案一覧')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
        {{ Form::open([ "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            <section class="bk-white">
                <div class="board_box_bk-white">
                    <h3 class="edit-title">{{$recruit['rc_title']}}</h3>
                    <ul class="info_ttl_wrap centre">
                        <li> <img src="{{\App\Service\CommonService::getLessonClassIconUrl($recruit['cruitLesson']['class_icon'])}}" alt=""> </li>
                        <li>
                            <div class="about_detail">
                                <p class="money ptb0 flex">{{ \App\Service\CommonService::getLessonMoneyRange($recruit['rc_wish_minmoney'], $recruit['rc_wish_maxmoney']) }}<small>{{$recruit['lesson_time']}}</small></p>
                                <p class="date_time ptb0 flex"> <span>{{$recruit['date']}}</span> <span>{{$recruit['start_end_time']}}</span></p>
                            </div>
                        </li>
                    </ul>
                    <div class="explain">
                        <p>{{$recruit['rc_detail']}}</p>
                    </div>
                    <div class="about_attention"> <span class="attention_heart">{{$recruit['fav_count']}}</span> <span class="attention_look">{{$recruit['rc_views']}}</span> <span class="attention_update">{{$recruit['time_diff']}}前</span> </div>
                    @if ($total == 0)
                    <div class="form_wrap2 icon_form type_edit">
                        <button type="button" onclick="location.href='{{route("keijibann.recruiting_edit") . '/' . $recruit['rc_id']}}'" class="form_btn2 shadow-glay">
                            <div class="payment_box2">編集・削除する</div>
                        </button>
                    </div>
                    @endif
                </div>
            </section>
            <div class="innerecruiting_prop_detailr_box mt50 w100-15">
                <h3 class="flex-box01">提案一覧<span>全 {{$total}}件</span></h3>
                @foreach($proposals as $key=>$val)
                <div class="board_box no-filter shadow-glay">
                    <section class="no-space">
                        <div class="lesson_info_area">
                            <ul class="no-space teacher_info_02">
                                <li class="icon"><img src="{{\App\Service\CommonService::getUserAvatarUrl($val['proposalUser']['user_avatar'])}}" class="プロフィールアイコン"></li>
                                <li class="about_teacher">
                                    <div class="profile_name">
                                        <p><a href="{{route('keijibann.recruiting_prop_detail') . '/'. $val['pro_id']}}" class="underline">{{$val['proposalUser']['name']}}</a><span>（{{$val['age']}}）{{$val['sex']}}}</span></p>
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
                            <button type="button" onclick="location.href='{{route("keijibann.recruiting_conf") . "/" . $val["pro_id"]}}'" class="form_btn3 f14 shadow bold">
                                <div class="payment_box2">予約する (~{{$val['date']}}まで)</div>
                            </button>
                        </div>
                    </section>
                </div>
                @endforeach

            </div>
        {{ Form::close() }}

    </div><!-- /contents -->

    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

