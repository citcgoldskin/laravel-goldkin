@extends('user.layouts.app')
@section('title', 'レッスン履歴')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <div class="tabs info_wrap three_tab mt0">
        <input id="tab-01" type="radio" name="tab_item" @if ( $state == config('const.schedule_state.reserve') ) checked @endif >
        <label class="tab_item" for="tab-01">
            予約中
        </label>
        <input id="tab-02" type="radio" name="tab_item" @if ( $state == config('const.schedule_state.complete') ) checked @endif >
        <label class="tab_item" for="tab-02">
            完了
        </label>
        <input id="tab-03" type="radio" name="tab_item" @if ( $state >= config('const.schedule_state.cancel_senpai') ) checked @endif >
        <label class="tab_item" for="tab-03">
            キャンセル
        </label>

        <input type="hidden" value="{{ $start_date[config('const.schedule_state.complete')] }}" id="from_complete">
        <input type="hidden" value="{{ $end_date[config('const.schedule_state.complete')] }}" id="to_complete">
        <input type="hidden" value="{{ $start_date[config('const.schedule_state.cancel_senpai')] }}" id="from_cancel">
        <input type="hidden" value="{{ $end_date[config('const.schedule_state.cancel_senpai')] }}" id="to_cancel">
        <input type="hidden" value="0" id="order_complete">


        <!-- ********************************************************* -->
        <div class="tab_content" id="tab-01_content">

            <section class="type_summary">

                <div class="form_wrap icon_form type_search">
                    <input type="text" value="{{ isset($keyword[config('const.schedule_state.reserve')]) ? $keyword[config('const.schedule_state.reserve')] : '' }}" placeholder="キーワードで検索" class="search_white" id="keyword_reserve" name="keyword_reserve">
                </div>

                <div id="top-menu_wrap">
                    <div class="top-menu">
                        <nav>
                            <ul class="conditions_box pr0 pb30">
                                <li class="txt_left"><span class="kensuu all_mark">{{ $counts[config('const.schedule_state.reserve')] }}</span></li>
                                <li>
                                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                        @php
                                            $order_arr = array(
                                                '0' => 'レッスンが近い順',
                                                '1' => '支払いの総額の高い順',
                                                '2' => '単価の高い順'
                                            );
                                        @endphp
                                        <select id="order_reserve" name="order_reserve" class="sort">
                                            @foreach($order_arr as $key => $value)
                                                @if(isset($order[config('const.schedule_state.reserve')]) && $order[config('const.schedule_state.reserve')] == $key)
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

                @foreach ( $schedule_lists[config('const.schedule_state.reserve')] as $key => $value )
                <div class="board_box">
                    <a href="{{ route('user.myaccount.master_lesson_request', ['schedule_id' => $value['lrs_id']]) }}">
                        <ul class="teacher_info_02 mt0 icon_top">
                            <li class="icon_s40"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($value['lesson_request']['user']['user_avatar']) }}" class="プロフィールアイコン"></li>
                            <li class="about_teacher">
                                <div class="profile_name">
                                    <p class="lesson_ttl one_line">{{ $value['lesson_request']['lesson']['lesson_title'] }}</p>
                                    <p>{{ $value['lesson_request']['user']['name'] }}
                                        <span>（{{ \App\Service\CommonService::getAge($value['lesson_request']['user']['user_birthday']) }}）
                                            {{ \App\Service\CommonService::getSexStr($value['lesson_request']['user']['user_sex']) }}
                                        </span>
                                    </p>
                                    <p class="target_area">レッスン日時：{{ date('Y/n/j', strtotime($value['lrs_date'])) }}/
                                        {{ \App\Service\CommonService::getHM($value['lrs_start_time']) }}~{{ \App\Service\CommonService::getHM($value['lrs_end_time']) }}</p>
                                </div>
                            </li>
                        </ul>
                    </a>
                </div>
                @endforeach

            </section>

            {{ $schedule_lists[config('const.schedule_state.reserve')]
            ->appends([
                'state' => config('const.schedule_state.reserve'),
                'complete' => $schedule_lists[config('const.schedule_state.complete')]->currentPage(),
                'cancel' => $schedule_lists[config('const.schedule_state.cancel_senpai')]->currentPage(),
                'keyword_reserve' => isset($keyword[config('const.schedule_state.reserve')]) ? $keyword[config('const.schedule_state.reserve')] : '',
                'keyword_complete' => isset($keyword[config('const.schedule_state.complete')]) ? $keyword[config('const.schedule_state.complete')] : '',
                'keyword_cancel' => isset($keyword[config('const.schedule_state.cancel_senpai')]) ? $keyword[config('const.schedule_state.cancel_senpai')] : '',
                'order_reserve' => isset($order[config('const.schedule_state.reserve')]) ? $order[config('const.schedule_state.reserve')] : '',
                'order_complete' => isset($order[config('const.schedule_state.complete')]) ? $order[config('const.schedule_state.complete')] : '',
                'order_cancel' => isset($order[config('const.schedule_state.cancel_senpai')]) ? $order[config('const.schedule_state.cancel_senpai')] : '',
                'from_complete' => isset($start_date[config('const.schedule_state.complete')]) ? $start_date[config('const.schedule_state.complete')] : '',
                'to_complete' => isset($end_date[config('const.schedule_state.complete')]) ? $end_date[config('const.schedule_state.complete')] : '',
                'from_cancel' => isset($start_date[config('const.schedule_state.cancel_senpai')]) ? $start_date[config('const.schedule_state.cancel_senpai')] : '',
                'to_cancel' => isset($end_date[config('const.schedule_state.cancel_senpai')]) ? $end_date[config('const.schedule_state.cancel_senpai')] : '',
            ])
            ->links('vendor.pagination.senpai-pagination') }}

        </div><!-- /tab_content -->

        <div class="tab_content" id="tab-02_content">

            <section class="type_summary">

                <div class="form_wrap icon_form type_search">
                    <input type="text" value="{{ $keyword[config('const.schedule_state.complete')] }}" placeholder="キーワードで検索" class="search_white" id="keyword_complete" name="keyword_complete">
                </div>


                <div class="top-menu">
                    <nav>
                        <ul class="conditions_box pr0 pb30">
                            <li class="txt_left"><span class="kensuu all_mark">{{ $counts[config('const.schedule_state.complete')] }}</span></li>
                            <li>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                    <p class="term-btn">期間を指定する</p>
                                </div>
                            </li>
                        </ul>
                    </nav>
                </div>

                <ul class="date" id="date_comp">
                    <li class="nobk"><input type="date" value="{{ $start_date[config('const.schedule_state.complete')] }}" id="from_comp" class="term_comp"></li>
                    <li>～</li>
                    <li class="nobk"><input type="date" value="{{ $end_date[config('const.schedule_state.complete')] }}" id="to_comp" class="term_comp"></li>
                </ul>

                @foreach ( $schedule_lists[config('const.schedule_state.complete')] as $key => $value )
                    <div class="board_box">
                        <a href="{{ route('user.myaccount.master_lesson_request', ['schedule_id' => $value['lrs_id']]) }}">
                            <ul class="teacher_info_02 mt0 icon_top">
                                <li class="icon_s40"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($value['lesson_request']['user']['user_avatar']) }}" class="プロフィールアイコン"></li>
                                <li class="about_teacher">
                                    <div class="profile_name">
                                        <p class="lesson_ttl one_line">{{ $value['lesson_request']['lesson']['lesson_title'] }}</p>
                                        <p>{{ $value['lesson_request']['user']['name'] }}
                                            <span>（{{ \App\Service\CommonService::getAge($value['lesson_request']['user']['user_birthday']) }}）
                                                {{ \App\Service\CommonService::getSexStr($value['lesson_request']['user']['user_sex']) }}
                                        </span>
                                        </p>
                                        <p class="target_area">レッスン日時：{{ date('Y/n/j', strtotime($value['lrs_date'])) }}/
                                            {{ \App\Service\CommonService::getHM($value['lrs_start_time']) }}~</p>
                                    </div>
                                </li>
                            </ul>
                        </a>
                    </div>
                @endforeach

            </section>

            {{ $schedule_lists[config('const.schedule_state.complete')]
            ->appends([
                'state' => config('const.schedule_state.complete'),
                'reserve' => $schedule_lists[config('const.schedule_state.reserve')]->currentPage(),
                'cancel' => $schedule_lists[config('const.schedule_state.cancel_senpai')]->currentPage(),
                'keyword_reserve' => isset($keyword[config('const.schedule_state.reserve')]) ? $keyword[config('const.schedule_state.reserve')] : '',
                'keyword_complete' => isset($keyword[config('const.schedule_state.complete')]) ? $keyword[config('const.schedule_state.complete')] : '',
                'keyword_cancel' => isset($keyword[config('const.schedule_state.cancel_senpai')]) ? $keyword[config('const.schedule_state.cancel_senpai')] : '',
                'order_reserve' => isset($order[config('const.schedule_state.reserve')]) ? $order[config('const.schedule_state.reserve')] : '',
                'order_complete' => isset($order[config('const.schedule_state.complete')]) ? $order[config('const.schedule_state.complete')] : '',
                'order_cancel' => isset($order[config('const.schedule_state.cancel_senpai')]) ? $order[config('const.schedule_state.cancel_senpai')] : '',
                'from_complete' => isset($start_date[config('const.schedule_state.complete')]) ? $start_date[config('const.schedule_state.complete')] : '',
                'to_complete' => isset($end_date[config('const.schedule_state.complete')]) ? $end_date[config('const.schedule_state.complete')] : '',
                'from_cancel' => isset($start_date[config('const.schedule_state.cancel_senpai')]) ? $start_date[config('const.schedule_state.cancel_senpai')] : '',
                'to_cancel' => isset($end_date[config('const.schedule_state.cancel_senpai')]) ? $end_date[config('const.schedule_state.cancel_senpai')] : '',
            ])
            ->links('vendor.pagination.senpai-pagination') }}

        </div><!-- /tab_content -->

        <div class="tab_content" id="tab-03_content">

            <section class="type_summary">

                <div class="form_wrap icon_form type_search">
                    <input type="text" value="{{ $keyword[config('const.schedule_state.cancel_senpai')] }}" placeholder="キーワードで検索" class="search_white" id="keyword_cancel" name="keyword_cancel">
                </div>

                <div id="top-menu_wrap">
                    <div class="top-menu">
                        <nav>
                            <ul class="conditions_box pr0 pb30">
                                <li class="txt_left"><span class="kensuu all_mark">{{ $counts[config('const.schedule_state.cancel_senpai')] }}</span></li>
                                <li>
                                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                        @php
                                            $order_arr = array(
                                                '0' => '今月のレッスン',
                                                '1' => '先月のレッスン',
                                                '2' => '今年のレッスン',
                                                '3' => '昨年のレッスン',
                                                '4' => '期間を指定する'
                                            );
                                        @endphp
                                        <select id="order_cancel" name="order_cancel" class="sort">
                                            @foreach($order_arr as $key => $value)
                                                @if(isset($order[config('const.schedule_state.cancel_senpai')]) && $order[config('const.schedule_state.cancel_senpai')] == $key)
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

                    <ul id="date_cancel" class="date_custom">
                        <li class="nobk"><input type="date" value="{{ $start_date[config('const.schedule_state.cancel_senpai')] }}" id="from_can" class="term_can"></li>
                        <li>～</li>
                        <li class="nobk"><input type="date" value="{{ $end_date[config('const.schedule_state.cancel_senpai')] }}" id="to_can" class="term_can"></li>
                    </ul>

                </div>

                @foreach ( $schedule_lists[config('const.schedule_state.cancel_senpai')] as $key => $value )
                    <div class="board_box">
                        <a href="{{ route('user.myaccount.master_lesson_request', ['schedule_id' => $value['lrs_id']]) }}">
                            <ul class="teacher_info_02 mt0 icon_top">
                                <li class="icon_s40"><img src="{{ \App\Service\CommonService::getUserAvatarUrl($value['lesson_request']['user']['user_avatar']) }}" class="プロフィールアイコン"></li>
                                <li class="about_teacher">
                                    <div class="profile_name">
                                        <p class="lesson_ttl one_line">{{ $value['lesson_request']['lesson']['lesson_title'] }}</p>
                                        <p>{{ $value['lesson_request']['user']['name'] }}
                                            <span>（{{ \App\Service\CommonService::getAge($value['lesson_request']['user']['user_birthday']) }}）
                                                {{ \App\Service\CommonService::getSexStr($value['lesson_request']['user']['user_sex']) }}
                                        </span>
                                        </p>
                                        <p class="target_area">レッスン日時：{{ date('Y/n/j', strtotime($value['lrs_date'])) }}/
                                            {{ \App\Service\CommonService::getHM($value['lrs_start_time']) }}~</p>
                                    </div>
                                </li>
                            </ul>
                        </a>
                    </div>
                @endforeach

            </section>

            {{ $schedule_lists[config('const.schedule_state.cancel_senpai')]
            ->appends([
                'state' => config('const.schedule_state.cancel_senpai'),
                'reserve' => $schedule_lists[config('const.schedule_state.reserve')]->currentPage(),
                'complete' => $schedule_lists[config('const.schedule_state.complete')]->currentPage(),
                'keyword_reserve' => isset($keyword[config('const.schedule_state.reserve')]) ? $keyword[config('const.schedule_state.reserve')] : '',
                'keyword_complete' => isset($keyword[config('const.schedule_state.complete')]) ? $keyword[config('const.schedule_state.complete')] : '',
                'keyword_cancel' => isset($keyword[config('const.schedule_state.cancel_senpai')]) ? $keyword[config('const.schedule_state.cancel_senpai')] : '',
                'order_reserve' => isset($order[config('const.schedule_state.reserve')]) ? $order[config('const.schedule_state.reserve')] : '',
                'order_complete' => isset($order[config('const.schedule_state.complete')]) ? $order[config('const.schedule_state.complete')] : '',
                'order_cancel' => isset($order[config('const.schedule_state.cancel_senpai')]) ? $order[config('const.schedule_state.cancel_senpai')] : '',
                'from_complete' => isset($start_date[config('const.schedule_state.complete')]) ? $start_date[config('const.schedule_state.complete')] : '',
                'to_complete' => isset($end_date[config('const.schedule_state.complete')]) ? $end_date[config('const.schedule_state.complete')] : '',
                'from_cancel' => isset($start_date[config('const.schedule_state.cancel_senpai')]) ? $start_date[config('const.schedule_state.cancel_senpai')] : '',
                'to_cancel' => isset($end_date[config('const.schedule_state.cancel_senpai')]) ? $end_date[config('const.schedule_state.cancel_senpai')] : '',
            ])
            ->links('vendor.pagination.senpai-pagination') }}

        </div><!-- /tab_content -->

    </div><!-- /tabs -->

</div><!-- /contents -->


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection

@section('page_js')
    <script>
        $(document).ready(function () {
            @if ( $state == config('const.schedule_state.reserve') )
            $('#tab-01_content').show();
            $('#tab-02_content').hide();
            $('#tab-03_content').hide();
            @elseif ( $state == config('const.schedule_state.complete') )
            $('#tab-01_content').hide();
            $('#tab-02_content').show();
            $('#tab-03_content').hide();
            @else
            $('#tab-01_content').hide();
            $('#tab-02_content').hide();
            $('#tab-03_content').show();
            @endif

            @if ( $order[config('const.schedule_state.complete')] == 1 )
                $('#date_comp').show();
            @endif

            @if ( $order[config('const.schedule_state.cancel_senpai')] == 4 )
                $('.date_custom').show();
                $('.date_custom').slideDown(500);
            @else
                $('.date_custom').hide();
                $('.date_custom').slideUp(500);
            @endif
        });

        $('#keyword_reserve').keydown(function (e) {
            if ( e.keyCode == 13 ) { // press enter
                filterList('{{ config('const.schedule_state.reserve') }}');
            }
        });

        $('#keyword_complete').keydown(function (e) {
            if ( e.keyCode == 13 ) { // press enter
                filterList('{{ config('const.schedule_state.complete') }}');
            }
        });

        $('#keyword_cancel').keydown(function (e) {
            if ( e.keyCode == 13 ) { // press enter
                filterList('{{ config('const.schedule_state.cancel_senpai') }}');
            }
        });

        $('#order_reserve').change(function () {
            filterList('{{ config('const.schedule_state.reserve') }}');
        });

        $('#order_cancel').change(function () {
            var now = new Date();
            var from, to;
            if ( $(this).val() == 0 ) { // 今月のレッスン
                from = new Date(now.getFullYear(), now.getMonth(), 1);
                to  = new Date(now.getFullYear(), now.getMonth() + 1, 0)
                $('#from_cancel').val(from.getFullYear() + '/' + (from.getMonth() + 1) + '/' + from.getDate());
                $('#to_cancel').val(to.getFullYear() + '/' + (to.getMonth() + 1) + '/' + to.getDate());
            } else if ( $(this).val() == 1 ) { // 先月のレッスン
                from = new Date(now.getFullYear(), now.getMonth() - 1, 1);
                to  = new Date(now.getFullYear(), now.getMonth(), 0)
                $('#from_cancel').val(from.getFullYear() + '/' + (from.getMonth() + 1) + '/' + from.getDate());
                $('#to_cancel').val(to.getFullYear() + '/' + (to.getMonth() + 1) + '/' + to.getDate());
            } else if ( $(this).val() == 2 ) { // 今年のレッスン
                from = new Date(now.getFullYear(), 0, 1);
                to  = new Date(now.getFullYear() + 1, 0, 0)
                $('#from_cancel').val(from.getFullYear() + '/' + (from.getMonth() + 1) + '/' + from.getDate());
                $('#to_cancel').val(to.getFullYear() + '/' + (to.getMonth() + 1) + '/' + to.getDate());
            } else if ( $(this).val() == 3 ) { // 昨年のレッスン
                from = new Date(now.getFullYear() - 1, 0, 1);
                to  = new Date(now.getFullYear(), 0, 0)
                $('#from_cancel').val(from.getFullYear() + '/' + (from.getMonth() + 1) + '/' + from.getDate());
                $('#to_cancel').val(to.getFullYear() + '/' + (to.getMonth() + 1) + '/' + to.getDate());
            } else if ( $(this).val() == 4 ) { // 期間を指定する
                $(this).parents().addClass('on');
                $('.date_custom').show();
                $('.date_custom').slideDown(500);

                return;
            }

            filterList('{{ config('const.schedule_state.cancel_senpai') }}');
        });

        $('.term-btn').mousedown(function () {
            if ( $('#tab-02_content').hasClass('on') ) {
                $('#order_complete').val(0);
            } else {
                $('#order_complete').val(1);
            }
        });

        $('.term_comp').change(function () {
            $('#from_complete').val($('#from_comp').val());
            $('#to_complete').val($('#to_comp').val());

            filterList('{{ config('const.schedule_state.complete') }}');
        });

        $('.term_can').change(function () {
            $('#from_cancel').val($('#from_can').val());
            $('#to_cancel').val($('#to_can').val());

            filterList('{{ config('const.schedule_state.cancel_senpai') }}');
        });

        function filterList(state) {
            var reserve_page_num = ( state == '{{ config('const.schedule_state.reserve') }}' ) ? '{{ $schedule_lists[config('const.schedule_state.reserve')]->currentPage() }}' : '' ;
            var complete_page_num = ( state == '{{ config('const.schedule_state.complete') }}' ) ? '{{ $schedule_lists[config('const.schedule_state.complete')]->currentPage() }}' : '' ;
            var cancel_page_num = ( state == '{{ config('const.schedule_state.cancel_senpai') }}' ) ? '{{ $schedule_lists[config('const.schedule_state.cancel_senpai')]->currentPage() }}' : '' ;
            location.href = '{{ route('user.myaccount.master_lesson_history') }}' +
                '?state=' + state +
                '&reserve=' + reserve_page_num +
                '&complete=' + complete_page_num +
                '&cancel=' + cancel_page_num +
                '&keyword_reserve=' + $('#keyword_reserve').val() +
                '&keyword_complete=' + $('#keyword_complete').val() +
                '&keyword_cancel=' + $('#keyword_cancel').val() +
                '&order_reserve=' + $('#order_reserve').val() +
                '&order_complete=' + $('#order_complete').val() +
                '&order_cancel=' + $('#order_cancel').val() +
                '&from_complete=' + $('#from_complete').val() +
                '&to_complete=' + $('#to_complete').val() +
                '&from_cancel=' + $('#from_cancel').val() +
                '&to_cancel=' + $('#to_cancel').val();

        }
    </script>
@endsection
