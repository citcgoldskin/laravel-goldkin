
@extends('user.layouts.app')

@section('title', 'マイページ')

@section('content')
@include('user.layouts.gnavi_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    {{ Form::open(["route"=>"user.myaccount.index", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section id="mypage">

            <div class="inner_box lesson_info_area">

                <ul class="teacher_info_02">
                    <li class="icon"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($user_info['user_avatar']) }}" class="プロフィールアイコン"></li>
                    <li class="about_teacher">
                        <div class="profile_name"><p class="fs-14">{{ $user_info['name'] }}</p></div>
                        <div>
                            <p class="blue_link icon_arrow blue_right">
                                <a href="{{ route('user.myaccount.edit_profile.form') }}">プロフィール編集</a>
                            </p>
                        </div>
                    </li>
                </ul>
            </div>


            <div class="inner_box">
                <ul class="list_area mypage_list icon_setting">

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.favorite') }}">お気に入り／フォロー／フォロワー</a>
                    </li>
                    @if ( $grant == 1 )
                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.myaccount.friend_invite') }}">お友達招待<span class="orange_txt">（{{ \App\Service\CommonService::showFormatNum(\App\Service\SettingService::getSetting('coupon_value_system', 'int')) }}円クーポン獲得）</span></a>
                        </li>
                    @endif
                </ul>
            </div>

            <div class="inner_box">
                <h3 class="icon_user_senpai">センパイメニュー</h3>

                <ul class="list_area mypage_list icon_setting">

                    @if ( $grant == 1 )
                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.syutupinn.lesson_list', ['page_from'=>'myaccount']) }}">出品レッスン管理</a>
                        </li>

                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.syutupinn.schedule', ['page_from'=>'myaccount']) }}">出勤スケジュール確認・編集</a>
                        </li>

                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.myaccount.coupon_intro') . '/1'}}">クーポン管理</a>
                        </li>

                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.syutupinn.request', ['page_from'=>'myaccount']) }}">リクエスト管理</a>
                        </li>

                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('keijibann.recruiting_proposal') }}/1?page_from=myaccount">掲示板への提案管理</a>
                        </li>

                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.myaccount.payment_mgr') }}">売上管理／振込申請</a>
                        </li>

                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.myaccount.sale_detail_list') }}">売上明細</a>
                        </li>

                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.myaccount.master_lesson_history') }}">レッスン履歴</a>
                        </li>
                    @else
                        <li>
                            <div class="pb20 txt_center"><img src="{{ asset('assets/user/img/mypage_senpai.svg') }}" alt="" width="254"></div>
                            <div class="button-area">
                                <div class="btn_base btn_orange effect_none shadow">
                                    <a href="{{ route('user.myaccount.reg_senpai') }}">センパイ登録する</a>
                                </div>
                            </div>
                        </li>
                    @endif

                </ul>
            </div>

            <div class="inner_box">
                <h3 class="icon_user_kouhai">コウハイメニュー</h3>

                <ul class="list_area">

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.request_mgr') }}">リクエスト管理</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('keijibann.recruiting') . '/1?page_from=myaccount' }}">掲示板募集管理</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.coupon_box') . '/1' }}">クーポンBOX</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.student_lesson_history') }}">レッスン履歴</a>
                    </li>

                </ul>
            </div>

            <div class="inner_box">
                <h3 class="icon_setting">設定</h3>

                <ul class="list_area mypage_list icon_setting">

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.set_account') }}">アカウント</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.push_and_mail') }}">プッシュ通知・メール</a>
                    </li>

                </ul>
            </div>

            <div class="inner_box">
                <h3 class="icon_help">ヘルプ</h3>

                <ul class="list_area">

                    {{--@if(isset($user_info['user_is_senpai']) && $user_info['user_is_senpai'] == config('const.staff_type.senpai'))--}}
                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.guide', ['type'=>config('const.menu_type.senpai')]) }}">ご利用ガイド（センパイ向け）</a>
                        </li>
                    {{--@endif--}}

                    {{--@if(isset($user_info['user_is_senpai']) && $user_info['user_is_senpai'] == config('const.staff_type.kouhai'))--}}
                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.guide', ['type'=>config('const.menu_type.kouhai')]) }}">ご利用ガイド（コウハイ向け）</a>
                        </li>
                    {{--@endif--}}

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.ques_cate') }}">よくある質問</a>
                    </li>

                    @if ( $grant == 1 )
                        <li class="icon_form type_arrow_right">
                            <a href="{{ route('user.myaccount.asking') }}">お問い合わせ</a>
                        </li>
                    @endif

                </ul>
            </div>

            <div class="inner_box pb80">

                <ul class="list_area">

                    <li class="icon_form type_arrow_right">
                        <a href="{{ route('user.myaccount.others') }}">その他</a>
                    </li>

                    <li class="icon_form type_arrow_right">
                        <a href="" id="btn-logout">ログアウト</a>
                    </li>

                    @if ( $grant != 1 )
                        <li class="icon_form type_arrow_right">
                            <a href="#">お問い合わせ</a>
                        </li>
                    @endif

                </ul>
            </div>

        </section>

    {{ Form::close() }}

</div><!-- /contents-->

<form id="logout-form" action="{{ route('user.login.logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<div class="rb_btn_area">
    @php
        $cur_ym= date('Y-m', time());
    @endphp
    <a href="{{ route('user.talkroom.subscriptionCal', ['ym'=>$cur_ym]) }}">
        <div class="btn_inner">
            <p><img src="{{ asset('assets/user/img/footer/btn_img_calendar.svg') }}" alt=""></p>
            <p>カレンダー</p>
        </div>
    </a>
</div>

<footer>
    @include('user.layouts.fnavi')
</footer>
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {
            $('#btn-logout').click(function (e) {
                e.preventDefault();
                $('#logout-form').submit();
            });
        });
    </script>
@endsection
