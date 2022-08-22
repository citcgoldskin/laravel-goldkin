@extends('user.layouts.app')

@section('title', '募集の詳細')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
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
                                        <a href="{{route('user.myaccount.profile',['user_id'=>$data['rc_user_id']])}}">プロフィール</a>
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
                @if($self_proposed == 0)
                <div id="footer_button_area" class="under_area">
                    <ul>
                        <li class="send-request">
                            <div class="btn_base btn_orange">
                                <button type="submit">この募集に提案する</button>
                            </div>
                        </li>
                    </ul>
                </div>
                @endif
                <input type="hidden" name="rc_id" value="@php echo $data['rc_id']; @endphp" >

            </section>

        {{ Form::close() }}


    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

