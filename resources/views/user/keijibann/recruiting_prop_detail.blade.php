@extends('user.layouts.app')

@section('title', '募集・提案の詳細')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">

        <section class="prof">

            <div class="back_wrap">
                <div class="white_box"></div>
                <div class="inner_box">
                    <div class="profile_base_02">
                        <ul>
                            <li>
                                <div class="profile_img">
                                    <p><img src="{{ isset($origin_proposal) ? App\Service\CommonService::getUserAvatarUrl($origin_proposal->recruit->cruitUser->user_avatar) : '' }}" alt=""></p>
                                </div>
                            </li>

                            <li class="profile_name"><p><big>{{$recruit['cruit_user']['name']}}</big><span>（{{$recruit['age']}}）{{$recruit['sex']}}</span></p></li>
                        </ul>
                        <ul class="profile_info">
                            <li class="jisseki">
                                <p>購入件数 <span>{{$buy_count}}</span><span>件</span></p>
                                <p>レッスン件数 <span>{{$sell_count}}</span><span>件</span></p>
                            </li>
                            <li class="t_right">
                                <p class="orange_link icon_arrow orange_right">
                                    <a href="{{route('user.myaccount.profile', ['user_id' => $recruit['rc_user_id']])}}">プロフィール</a>
                                </p>
                            </li>
                        </ul>
                    </div>

                    <div class="flex">
                        <div class="cate_icon"><img src="{{ asset('assets/user/img/icon_lesson_ttl_01.png') }}" alt=""></div>
                        <h3 class="prof-title">
                            {{$recruit['rc_title']}}
                        </h3>
                    </div>
                    <div class="base_txt">
                        <p>
                            {{$recruit['rc_detail']}}
                        </p>
                    </div>
                </div>
            </div>

            <div class="inner_box special">
                <div class="white_box mb30 pt0">
                    <h3>募集条件</h3>
                    <ul class="list_box about_detail_gray mb20">

                        <li>
                            <div>
                                <p class="member">参加人数</p>
                                <p>男性{{$recruit['rc_man_num']}}人　女性{{$recruit['rc_woman_num']}}人</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p class="money_02">予算</p>
                                <p>{{$recruit['rc_wish_minmoney']}}円～{{$recruit['rc_wish_maxmoney']}}円<small>／{{$recruit['lesson_time']}}分</small></p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p class="attention_update_03 normal">日時</p>
                                <p>{{$recruit['date']}}　{{$recruit['start_end_time']}}</p>
                            </div>
                        </li>

                        <li>
                            <div>
                                <p class="time_limit normal">提案期限</p>
                                <p>{{$proposal['pro_date']}}</p>
                            </div>
                        </li>

                    </ul>
                    <h3>レッスン場所</h3>
                    <div class="form_txt">
                        <p class="f13">
                            {{ isset($origin_proposal) ? implode('/', $origin_proposal->recruit->recruit_area_names) : '' }}
                        </p>

                    </div>
                </div>

                <div class="inner_box teian_wrap">
                    <h3>あなたの提案</h3>
                    <ul class="list_box">
                        <li>
                            <div class="pr0">
                                <p>購入期限</p>
                                <p class="limit_txt">{{$proposal['pro_month']}}<small>月</small>{{$proposal['pro_day']}}<small>日</small> {{$proposal['pro_hour']}}:{{$proposal['pro_minute']}}<small>まで</small></p>
                            </div>
                        </li>

                        <li>
                            <div class="base_txt txt_left">
                                <p class="normal">
                                    {{ $proposal['pro_msg'] }}
                                </p>
                            </div>
                        </li>
                        <li>
                            <div class="pr0">
                                <p>レッスン開始日時</p>
                                {{--{{ dd($origin_proposal->recruit) }}--}}
                                <p class="limit_txt">{{ isset($origin_proposal) ? $origin_proposal->lesson_start_date : '' }}</p>
                                {{--<p class="limit_txt">{{ isset($origin_proposal) ? $origin_proposal->recruit : '' }}1<small>月</small>25<small>日</small> 18:00〜19:00  (test)(test)</p>--}}
                            </div>
                        </li>
                        <li>
                            <div class="pr0">
                                <p>レッスン時間</p>
                                {{--{{ dd($origin_proposal->recruit) }}--}}
                                <p class="limit_txt">{{ isset($origin_proposal) ? $origin_proposal->lesson_period : '' }}</p>
                                {{--<p class="limit_txt">{{ isset($origin_proposal) ? $origin_proposal->recruit : '' }}1<small>月</small>25<small>日</small> 18:00〜19:00  (test)(test)</p>--}}
                            </div>
                        </li>
                        <li>
                            <div class="pr0">
                                <p>提案金額</p>
                                <p class="price_mark tax-in">{{$proposal['pro_money']}}</p>
                            </div>
                            <div class="pr0">

                                <p class="modal-link">
                                    <a class="modal-syncer" data-target="modal-service">現在の手数料率</a>
                                </p>

                                <p>{{$fee_type_letter[$proposal['pro_fee_type']]}}</p>

                            </div>

                            <div class="pr0">
                                <p>売上金（目安）</p>
                                <p class="price_mark tax-in">{{$proposal['pro_money'] - $proposal['pro_fee']}}</p>
                            </div>

                            {{--<div class="pr0">
                                <p>出張交通費</p>
                                <p class="price_mark tax-in">{{$proposal['pro_traffic_fee']}}</p>
                            </div>--}}
                        </li>
                    </ul>
                </div>
            </div>

        </section>

        <div class="bk-white">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <a href="{{route('keijibann.recruiting_prop_edit') . '/' . $proposal['pro_id']}}">提案内容を変更</a>
                </div>
                <div class="btn_base btn_pale-gray shadow">
                    <a class="modal-syncer" data-toggle="modal" data-target="modal1">削除する</a>
                </div>
            </div>
        </div>
    </div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
    <div class="modal-wrap completion_wrap">
        <div id="modal1" class="modal modal-content">

            <div class="modal_body modal_basic">
                <div class="modal_inner">
                    <h4 id="circle-orange_ttl">！</h4>
                    <h2 class="modal_ttl">
                        提案を削除しても<br>
                        よろしいですか？
                    </h2>
                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_orange">
                    <button type="button" onClick="location.href='{{route("keijibann.recruiting_prop_del") . '/' . $proposal['pro_id']}}'">削除</button>
                </div>
                <div class="btn_base btn_gray-line">
                    <a href="{{route('keijibann.recruiting_edit')}}">キャンセル</a>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-wrap completion_wrap">
        <div id="modal2" class="modal modal-content">

            <div class="modal_body">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        募集内容を<br>
                        変更しました
                    </h2>

                </div>
            </div>

            <div class="button-area">
                <div class="btn_base btn_ok">
                    <a href="{{route('keijibann.list')}}">OK</a>
                </div>
            </div>
        </div>
    </div>

    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

