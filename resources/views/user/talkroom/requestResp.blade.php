@extends('user.layouts.app')
@section('title', 'リクエストに回答する')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents">
        <div id="top-menu" class="talkroom_wrap">
            <time class="change">変更申請時刻:{{ \App\Service\CommonService::getYMDAndHM($new_schedule_info['lrs_request_date'])}}</time>
        </div>
        <!--main_-->
        {{ Form::open(["route" => "user.talkroom.respComplete", "method" => "post", "name" => "form1", "id" => "form1"]) }}
            <input type="hidden" name="schedule_id" value="{{ $new_schedule_info['lrs_id'] }}">
            <section>
                <div class="lesson_info_area pt40">
                    <ul class="teacher_info_02">
                        <li class="icon"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($new_schedule_info['lesson_request']['lesson']['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                        <li class="about_teacher">
                            <div class="profile_name">
                                <p>{{ $new_schedule_info['lesson_request']['lesson']['senpai']['name'] }}<span>
                                        （{{ \App\Service\CommonService::getAge($new_schedule_info['lesson_request']['lesson']['senpai']['user_birthday']) }}）
                                    {{ \App\Service\CommonService::getSexStr($new_schedule_info['lesson_request']['lesson']['senpai']['user_sex']) }}</span></p>
                            </div>
                            <div>
                                <p class="orange_link icon_arrow orange_right"><a href="{{ route('user.myaccount.profile', ['user_id' => $new_schedule_info['lesson_request']['lesson']['lesson_senpai_id']]) }}">プロフィール</a></p>
                            </div>
                        </li>
                    </ul>
                </div>
            </section>
            <section class="pb10">
                <div class="inner_box">
                    <h3 class="summary_ttl"> <span>レッスン概要</span> <span class="shounin_kigen">承認期限：{{ \App\Service\CommonService::getMD($new_schedule_info['lesson_request']['lr_until_confirm']) }}</span> </h3>
                    <div class="white_box">
                        <div class="lesson_ttl_02 ttl-block">
                            <p>{{ $new_schedule_info['lesson_request']['lesson']['lesson_title'] }}</p>
                        </div>
                    </div>
                </div>
                <div class="inner_box">
                    <h3>レッスン場所</h3>
                    <div class="white_box">
                        <div class="lesson_place">
                            @if($new_schedule_info['lesson_request']['lr_pos_discuss'] == 1)
                                <p>
                                    {{ $new_schedule_info['lesson_request']['discuss_lesson_area'] }}
                                </p>
                                <p>
                                    {{ $new_schedule_info['lesson_request']['lr_address'] }}
                                </p>
                            @else
                                <p>
                                    {{ implode('/', $new_schedule_info['lesson_request']['lesson']['lesson_area_names']) }}
                                </p>
                            @endif
                            {{--<p> {{ \App\Service\AreaService::getOneAreaFullName($new_schedule_info['lesson_request']['lr_area_id']) }}<br>
                                {{ $new_schedule_info['lesson_request']['lr_address'] }} </p>--}}
                        </div>
                        @if($new_schedule_info['lesson_request']['lr_pos_discuss'] == 1)
                            <div class="balloon balloon_blue font-small">
                                <p>{{ $new_schedule_info['lesson_request']['lr_address_detail'] }}</p>
                            </div>
                        @else
                            <div class="balloon balloon_blue font-small">
                                <p>{{ $new_schedule_info['lesson_request']['lesson']['lesson_pos_detail'] }}</p>
                            </div>
                        @endif
                        {{--<div class="balloon balloon_blue font-small">
                            <p>{{ $new_schedule_info['lesson_request']['lr_address_detail'] }}</p>
                        </div>--}}
                    </div>
                </div>
                <div class="inner_box">
                    <h3>レッスン日時と料金</h3>
                    <div class="white_box">
                        <div class="icon_check">
                            <h3 class="icon_red">変更前</h3>
                        </div>
                        <ul class="list_box cancel_policy">
                            <li class="nobo-t">
                                <div>
                                    <p class="ls1">{{ \App\Service\CommonService::getYMDAndWeek($old_schedule_info['lrs_date']) }} <small>{{ \App\Service\CommonService::getStartAndEndTime($old_schedule_info['lrs_start_time'], $old_schedule_info['lrs_end time']) }}</small></p>
                                    <p class="space mr0"> <em>{{ \App\Service\CommonService::showFormatNum($old_schedule_info['lrs_amount']) }}円</em> </p>
                                </div>
                                <div>
                                    <p>出張交通費</p>
                                    <p class="space mr0"> <em>{{ \App\Service\CommonService::showFormatNum($old_schedule_info['lrs_traffic_fee']) }}円</em> </p>
                                </div>
                            </li>
                        </ul>
                        <div class="icon_check pt20">
                            <h3 class="icon_blue">変更後</h3>
                        </div>
                        <ul class="list_box cancel_policy">
                            <li class="nobo-t nobo-b">
                                <div>
                                    <p class="ls1">{{ \App\Service\CommonService::getYMDAndWeek($new_schedule_info['lrs_date']) }} <small>{{ \App\Service\CommonService::getStartAndEndTime($new_schedule_info['lrs_start_time'], $new_schedule_info['lrs_end_time']) }}</small></p>
                                    <p class="space mr0"> <em>{{ \App\Service\CommonService::showFormatNum($new_schedule_info['lrs_amount']) }}円</em> </p>
                                </div>
                                <div>
                                    <p>出張交通費</p>
                                    <p class="space mr0"> <em>{{ \App\Service\CommonService::showFormatNum($new_schedule_info['lrs_traffic_fee']) }}円</em> </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="balloon_area" id="makeImg">
                    <div class="balloon balloon_white">
                        <textarea placeholder="キャンセルの理由を100字以内でご記入ください。" cols="50" rows="10" maxlength="100"></textarea>
                    </div>
                    <p class="form_txt gray_txt"> ※キャンセルの理由は先輩に通知されます </p>
                </div>
            </section>
            <div class="white-bk pt30">
                <div class="check-box for-warning">
                    <p class="warning"></p>
                    <div class="clex-box_02 pl20">
                        <input type="radio" name="approval" value="1" id="approval-31">
                        <label for="approval-31">
                            <p>変更リクエストを承認する</p>
                        </label>

                        <input type="radio" name="approval" value="0" id="approval-32">
                        <label for="approval-32">
                            <p>変更リクエストを承認しない</p>
                        </label>
                    </div>
                </div>
                <aside class="hosoku pb30"><p>※0000年00月00日 00:00までに回答しなかった場合は承認しなかった扱いになります。</p></aside>
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <a class="show-modal"> 回答を確定する</a>
                    </div>
                </div>
            </div>
            <!-- モーダル部分 *********************************************************** -->
            <!-- ********************************************************* -->
            <input type="hidden" class="ajax-modal-syncer" data-target="#modal-kaitou" id="modal_result">
            <div class="modal-wrap">
                <div id="modal-kaitou" class="modal-content">

                    <div class="modal_body completion">
                        <div class="modal_inner">
                            <h2 class="modal_ttl">
                                変更リクエストを<br>
                                承認します。<br>
                                よろしいですか？
                            </h2>
                        </div>
                    </div>

                    <div class="button-area">
                        <div class="btn_base btn_orange">
                            <button type="submit" class="btn-send2" id="submit_btn">承認する</button>
                        </div>
                        <div class="btn_base btn_ok no-w">
                            <a id="modal-close" class="button-link">戻る</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- モーダル部分 / ここまで ************************************************* -->
        {{ Form::close() }}
    </div>
    <!-- /contents -->

    @include('user.layouts.modal')
    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection

@section('page_js')
<script src="{{ asset('assets/user/js/validate.js') }}"></script>
<script>
    function checkSelectedRadio() {
        if ( $('input[name="approval"]:checked').val() == undefined ) {
                addError($('#approval-31'), "項目を選択してください。");
            return false;
        }

        return true;
    }

    $('.show-modal').click(function () {
        var html= '変更リクエストを<br>' +
            '承認します。<br>' +
            'よろしいですか？';
        if ( checkSelectedRadio() ) {
            if ( $('input[name="approval"]:checked').val() == 0 ) {
                html = '変更リクエストを<br>' +
                    '承認しない。<br>' +
                    'よろしいですか？';
                $('#submit_btn').html('承認しない');
            }

            $('.modal_ttl').html(html);
            showAjaxModal($('#modal_result'));
        }
    })
</script>
@endsection
