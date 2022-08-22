@extends('user.layouts.app')
@section('title', 'リクエスト管理')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->
<div id="contents">
    <div class="tabs info_wrap mt0">
        <input id="tab-01" type="radio" name="tab_item" value="0" @if ( $type == 0 ) checked @endif>
        <label class="tab_item" for="tab-01"> 予約リクエスト@if ( $reserved_req_count > 0 ) <span class="midoku">{{ $reserved_req_count }}</span> @endif </label>
        <input id="tab-02" type="radio" name="tab_item" value="1" @if ( $type == 1 ) checked @endif>
        <label class="tab_item" for="tab-02"> 出勤リクエスト@if ( $attendance_req_count > 0 )<span class="midoku">{{ $attendance_req_count }}</span> @endif </label>

        <!-- ********************************************************* -->
        <div class="tab_content" id="tab-01_content">
            <section class="pt0">
                <div class="top-menu_wrap bg_none">
                    <div class="top-menu">
                        <nav>
                            <ul class="conditions_box pb20 pr0">
                                <li></li>
                                <li>
                                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                        @php
                                            $order_arr = array('0' => '新着順', '1' => '単価の高い順', '2' => '支払総額の高い順', '3' => '残り時間の多い順', '4' => '残り時間の少ない順', '5' => '購入期限の近い順');
                                        @endphp
                                        <select id="popular0" name="popular0" class="sort">
                                            @foreach($order_arr as $key => $value)
                                                @if(isset($reserve_order) && $reserve_order == $key)
                                                    <option	selected='selected' value="{{$key}}">{{$value}}</option>
                                                @else
                                                    <option  value="{{$key}}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                @if ( isset($reserved_request_infos) && count($reserved_request_infos) )
                    @foreach( $reserved_request_infos as $key => $value )
                    <div class="board_box">
                        <a href="{{ route('user.talkroom.requestConfirm', ['request_id' => $value['lr_id']]) }}">
                            <ul class="teacher_info_03 mt0">
                                <li class="icon_s40"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($value['lesson']['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                                <li class="about_teacher">
                                    <div class="profile_name">
                                        <p>{{ $value['lesson']['senpai']['name'] }}<br>
                                            <span>（{{ \App\Service\CommonService::getAge($value['lesson']['senpai']['user_birthday']) }}）{{ \App\Service\CommonService::getSexStr($value['lesson']['senpai']['user_sex']) }}</span></p>
                                    </div>
                                </li>
                                <li><img src="{{ asset('storage/class_icon/'.$value['lesson']['lesson_class']['class_icon']) }}" class="カテゴリーアイコン"></li>
                            </ul>
                            <div>
                                <p class="lesson_ttl">{{ $value['lesson']['lesson_title'] }}</p>
                                <p class="target_area">レッスン場所：{{ $value['lr_pos_discuss'] == 0 ? ($value['lesson']['lesson_area_names'] ? implode('/', $value['lesson']['lesson_area_names']) : '') : ($value['discuss_lesson_area'] ? $value['discuss_lesson_area'] : '') }}</p>
                            </div>
                        </a>
                        <div class="kigen_wrap">
                            <a href="{{ route('user.talkroom.requestConfirm', ['request_id' => $value['lr_id']]) }}">
                                <h4>承認期限：{{ \App\Service\CommonService::getMD($value['lr_until_confirm']) }}</h4>
                                <ul class="list_area">
                                    @foreach( $value['schedule_request'] as $k => $val )
                                    <li>
                                        <div>
                                            {{ \App\Service\CommonService::getMD($val['lrs_date']) }}　
                                            {{ \App\Service\CommonService::getStartAndEndTime($val['lrs_start_time'], $val['lrs_end_time']) }}
                                        </div>
                                        <div>{{ \App\Service\CommonService::showFormatNum($val['lrs_amount']) }}円</div>
                                    </li>
                                    @endforeach
                                </ul>
                            </a>
                            @if ( count($schedules_grouped_confirm_date[$value['lr_id']]) )
                                @foreach( $schedules_grouped_confirm_date[$value['lr_id']] as $k => $val )
                                    <div class="approval_box">
                                        <span class="approval_mark">予約リクエストが承認されました</span>
                                        <h4>購入期限：{{ \App\Service\CommonService::getMD($k) }}</h4>
                                        <ul class="list_area">
                                            @foreach( $val as $v )
                                                <a href="{{ route('user.lesson.check_reserve', ['lr_id' => $value['lr_id']]) }}">
                                                    <li>
                                                        <div>
                                                            {{ \App\Service\CommonService::getMD($v['lrs_date']) }}　
                                                            {{ \App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time']) }}
                                                        </div>
                                                        <div>{{ \App\Service\CommonService::showFormatNum($v['lrs_amount']) }}円</div>
                                                    </li>
                                                </a>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-data">検索結果 0件</div>
                @endif
            </section>
            {{ $reserved_request_infos->appends([
                'type' => config('const.request_type.reserve'),
                'attend' => $attend_request_infos->currentPage(),
            ])->links('vendor.pagination.senpai-pagination') }}
        </div>
        <!-- /tab_content -->

        <!-- ********************************************************* -->

        <div class="tab_content" id="tab-02_content">
            <section class="pt0">
                <div class="top-menu_wrap bg_none">
                    <div class="top-menu">
                        <nav>
                            <ul class="conditions_box pb20 pr0">
                                <li></li>
                                <li>
                                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                        @php
                                            $order_arr = array('0' => '人気順', '1' => '新着順', '2' => '古い順');
                                        @endphp
                                        <select id="popular1" name="popular1" class="sort">
                                            @foreach($order_arr as $key => $value)
                                                @if(isset($attendance_order) && $attendance_order == $key)
                                                    <option	selected='selected' value="{{$key}}">{{$value}}</option>
                                                @else
                                                    <option  value="{{$key}}">{{$value}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
                @if ( isset($attend_request_infos) && count($attend_request_infos) )
                    @foreach( $attend_request_infos as $key => $value )
                    <div class="board_box">
                        <a href="{{ route('user.talkroom.requestConfirm', ['request_id' => $value['lr_id']]) }}">
                            <ul class="teacher_info_03 mt0">
                                <li class="icon_s40"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($value['lesson']['senpai']['user_avatar']) }}" class="プロフィールアイコン"></li>
                                <li class="about_teacher">
                                    <div class="profile_name">
                                        <p>{{ $value['lesson']['senpai']['name'] }}<br>
                                            <span>（{{ \App\Service\CommonService::getAge($value['lesson']['senpai']['user_birthday']) }}）
                                                {{ \App\Service\CommonService::getSexStr($value['lesson']['senpai']['user_sex']) }}</span></p>
                                    </div>
                                </li>
                                <li><img src="{{ asset('storage/class_icon/'.$value['lesson']['lesson_class']['class_icon']) }}" class="カテゴリーアイコン"></li>
                            </ul>
                            <div>
                                <p class="lesson_ttl">{{ $value['lesson']['lesson_title'] }}</p>
                                <p class="target_area">レッスン場所：{{ $value['lr_pos_discuss'] == 0 ? ($value['lesson']['lesson_area_names'] ? implode('/', $value['lesson']['lesson_area_names']) : '') : ($value['discuss_lesson_area'] ? $value['discuss_lesson_area'] : '') }}</p>
                            </div>
                        </a>
                        <div class="kigen_wrap">
                            <a href="{{ route('user.talkroom.requestConfirm', ['request_id' => $value['lr_id']]) }}">
                                <h4>承認期限：{{ \App\Service\CommonService::getMD($value['lr_until_confirm']) }}</h4>
                                <ul class="list_area">
                                    @foreach( $value['schedule_request'] as $k => $val )
                                    <li>
                                        <div>{{ \App\Service\CommonService::getMD($val['lrs_date']) }}　
                                            {{ \App\Service\CommonService::getStartAndEndTime($val['lrs_start_time'], $val['lrs_end_time']) }}
                                        </div>
                                    </li>
                                    @endforeach
                                </ul>
                            </a>
                            <div class="about_attention_02">
                                <span class="attention_update_02">
                                    {{ $value['lr_hope_mintime'] . '~' . $value['lr_hope_maxtime'] }}分 /
                                    {{ \App\Service\CommonService::getHopePrice($value['lr_hope_mintime'], $value['lr_hope_maxtime'], $value['lesson']['lesson_30min_fees'], ($value['lr_man_num'] + $value['lr_woman_num'])) }}円
                                </span>
                            </div>
                            @if ( count($schedules_grouped_confirm_date[$value['lr_id']]) )
                                @foreach( $schedules_grouped_confirm_date[$value['lr_id']] as $k => $val )
                                    <div class="approval_box">
                                        <span class="approval_mark">出勤リクエストが承認されました</span>
                                        <h4>承認期限：{{ \App\Service\CommonService::getYMD($k) }}</h4>
                                        <ul class="list_area">
                                            @foreach( $val as $v )
                                                <a href="{{ route('user.lesson.check_reserve', ['lr_id' => $value['lr_id']]) }}">
                                                    <li>
                                                        <div>
                                                            {{ \App\Service\CommonService::getMD($v['lrs_date']) }}　
                                                            {{ \App\Service\CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time']) }}
                                                        </div>
                                                        <div>{{ \App\Service\CommonService::showFormatNum($v['lrs_amount']) }}円</div>
                                                    </li>
                                                </a>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="no-data">検索結果 0件</div>
                @endif
            </section>
            {{ $attend_request_infos->appends([
                'type' => config('const.request_type.attend'),
                'reserve' => $reserved_request_infos->currentPage(),
            ])->links('vendor.pagination.senpai-pagination') }}
        </div>
        <!-- /tab_content -->
    </div>
    <!-- /tabs -->

</div>
<!-- /contents -->


<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

@section('page_js')
    <script>
        $(document).ready(function () {
            @if ( $type == config('const.request_type.reserve') )
                $('#tab-01_content').show();
                $('#tab-02_content').hide();
            @else
                $('#tab-01_content').hide();
                $('#tab-02_content').show();
            @endif
        });

        $('#popular0').change(function () {
            location.href = '{{ route('user.myaccount.request_mgr') }}' +
                '?type=' + '{{ config('const.request_type.reserve') }}' +
                '&reserve=' + '{{ $reserved_request_infos->currentPage() }}' +
                '&attend' + {{ $attend_request_infos->currentPage() }} +
                '&reserve_order=' + $('#popular0').val() +
                '&attend_order=' + $('#popular1').val();
        });

        $('#popular1').change(function () {
            location.href = '{{ route('user.myaccount.request_mgr') }}' +
                '?type=' + '{{ config('const.request_type.attend') }}' +
                '&reserve=' + '{{ $reserved_request_infos->currentPage() }}' +
                '&attend' + {{ $attend_request_infos->currentPage() }} +
                '&reserve_order=' + $('#popular0').val() +
                '&attend_order=' + $('#popular1').val();
        });
    </script>
@endsection
