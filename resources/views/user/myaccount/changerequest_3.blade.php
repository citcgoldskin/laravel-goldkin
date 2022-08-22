@extends('user.layouts.app')
@section('title', $title)
@section('content')
    <div id="contents" class="pb0">

        <div id="completion_wrap" class="other_page">
            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">変更完了</h2>

                    <div class="modal_txt">
                        <p>
                            以下の内容で変更リクエストを<br>
                            {{ $new_schedule_info['lesson_request']['lesson']['senpai']['name'] }}さんに送信しました。
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!--main_-->
        <form action="./" method="post" name="form1" id="form1">

            <section>

                <div class="inner_box">
                    <h3>変更したレッスン</h3>
                    <div class="white_box pt0 pb0">
                        <ul class="list_box">
                            <li>
                                <ul class="reserved_top_box lesson_summary">
                                    @php
                                        $lesson_image = NULL;
                                        if ( isset($new_schedule_info['lesson_request']['lesson']['lesson_image']) && is_array(unserialize($new_schedule_info['lesson_request']['lesson']['lesson_image']))) {
                                            $lesson_image = unserialize($new_schedule_info['lesson_request']['lesson']['lesson_image'])[0];
                                        }
                                    @endphp
                                    <li><img src="{{ \App\Service\CommonService::getLessonImgUrl($lesson_image) }}" alt=""></li>
                                    <li>
                                        <p class="lesson_ttl">{{ $new_schedule_info['lesson_request']['lesson']['lesson_title'] }}</p>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div></div>
                <div class="inner_box icon_check">
                    <h3 class="icon_red">変更前</h3>
                    <div class="white_box">
                        <ul class="list_box">
                            <li class="lesson_naiyou">
                                <div>
                                    <p>レッスン日時：</p>
                                    <p>{{ \App\Service\CommonService::getYMD($old_schedule_info['lrs_date']) }} {{ \App\Service\CommonService::getStartAndEndTime($old_schedule_info['lrs_start_time'], $old_schedule_info['lrs_end_time']) }}</p>
                                </div>
                                <div class="no-flex ta-left">
                                    <p>レッスン場所：</p>
                                    <p class="ta-left">{{ \App\Service\AreaService::getOneAreaFullName($old_schedule_info['lesson_request']['lr_area_id']) }}<br>
                                        {{ $old_schedule_info['lr_address'] }}</p>
                                </div>
                                <div>
                                    <p>男性 {{ $old_schedule_info['lesson_request']['lr_man_num'] }}人 / 女性{{ $old_schedule_info['lesson_request']['lr_woman_num'] }}人</p>
                                </div>
                            </li>



                            <li>
                                <div>
                                    <p>合計（税込）</p>
                                    <p class="price_mark"><strong class="f-big">{{ \App\Service\CommonService::showFormatNum(\App\Service\CommonService::getTotalPrice($old_schedule_info['lrs_amount'], $old_schedule_info['lrs_traffic_fee'], $old_schedule_info['coupon'])) }}</strong></p>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="inner_box pb0 icon_check">
                    <h3 class="icon_blue">変更後</h3>
                    <div class="white_box">
                        <ul class="list_box">
                            <li class="lesson_naiyou">
                                <div>
                                    <p>レッスン日時：</p>
                                    <p>{{ \App\Service\CommonService::getYMD($new_schedule_info['lrs_date']) }} {{ \App\Service\CommonService::getStartAndEndTime($new_schedule_info['lrs_start_time'], $new_schedule_info['lrs_end_time']) }}</p>
                                </div>
                                <div class="no-flex ta-left">
                                    <p>レッスン場所：</p>
                                    <p class="ta-left">{{ \App\Service\AreaService::getOneAreaFullName($new_schedule_info['lesson_request']['lr_area_id']) }}<br>
                                        {{ $new_schedule_info['lr_address'] }}</p>
                                </div>
                                <div>
                                    <p>男性{{ $new_schedule_info['lesson_request']['lr_man_num'] }}人 / 女性{{ $new_schedule_info['lesson_request']['lr_woman_num'] }}人</p>
                                </div>
                            </li>

                            <li>
                                <div>
                                    <p>合計（税込）</p>
                                    <p class="price_mark"><strong class="f-big">{{ \App\Service\CommonService::showFormatNum(\App\Service\CommonService::getTotalPrice($new_schedule_info['lrs_amount'], $new_schedule_info['lrs_traffic_fee'], 0)) }}</strong></p>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>

                <div class="balloon balloon_blue font-small">
                    <p>センパイから返信があった場合はトークルークから確認できます。</p>
                </div>

            </section>
            <div class="white-bk">
                <div class="btn_base btn_ok">
                    <a href="{{ route('user.talkroom.talkData', [
                    'menu_type' => config('const.menu_type.kouhai'),
                    'room_id' => \App\Service\TalkroomService::getTalkroom($new_schedule_info['lesson_request']['user']['id'], $new_schedule_info['lesson_request']['lesson']['lesson_senpai_id'], config('const.menu_type.kouhai'))]) }}">
                        トークルームへ戻る
                    </a>
                </div>
            </div>
        </form>

    </div><!-- /contents -->

@include('user.layouts.modal')

@endsection
