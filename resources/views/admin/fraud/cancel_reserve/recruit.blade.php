@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">取り消し予約一覧</label>

            <section class="tab_area mb0">
                <div class="switch_tab">
                    <div class="type_radio radio-01">
                        <input type="radio" name="onof-line" id="off-line" onclick="location.href='{{ route('admin.fraud_cancel_reserve.lesson') }}'">
                        <label class="ok" for="off-line">レッスン</label>
                    </div>
                    <div class="type_radio radio-02">
                        <input type="radio" name="onof-line" id="on-line-1" checked="checked" onclick="location.href='{{ route('admin.fraud_cancel_reserve.recruit') }}'">
                        <label class="ok" for="on-line-1">投稿</label>
                    </div>
                </div>
            </section>

            <section>
                <div class="chk-rect mb-10">
                </div>
                <div class="flex" style="align-items: center;margin-bottom: 10px;">
                    @php
                        $from_page = ($obj_recruits->currentPage() - 1) * $obj_recruits->perPage() + 1;
                        $to_page = $obj_recruits->perPage() * $obj_recruits->currentPage();
                        if ($to_page > $obj_recruits->total()) {
                            $to_page = $obj_recruits->total();
                        }
                    @endphp
                    @if($obj_recruits->total() <= 1)
                        <div class="wp-50">全{{ $obj_recruits->total() }}件</div>
                    @else
                        <div class="wp-50">{{ $from_page }}件～{{ $to_page }}件（全{{ $obj_recruits->total() }}件）</div>
                    @endif
                    <div class="wp-50 form_wrap icon_form type_arrow_bottom">
                        <select class="wp-100" name="search_params[sort_type]" id="sort_type">
                            @foreach(config('const.stop_lesson_sort') as $key=>$value)
                                <option value="{{ $key }}" {{ (isset($search_params['order']) && $search_params['order'] ? $search_params['order'] : config('const.stop_lesson_sort_code.register_new')) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <section class="yarukoto_list">

                    @if(count($obj_recruits) > 0)
                        @foreach($obj_recruits as $key=>$val)
                            <div class="board_box">
                                <a href="{{ route('admin.fraud_cancel_reserve.recruit_detail', ['recruit'=>$val->rc_id]) }}">

                                    <ul class="info_ttl_wrap">
                                        <li>
                                            <img src="{{\App\Service\CommonService::getLessonIconImgUrl($val['cruitLesson']['class_icon'])}}" alt="">
                                        </li>
                                        <li class="about_teacher">
                                            <div class="profile_name">
                                                <p>{{ $val->cruitUser->user_name }}<span>（{{ \App\Service\CommonService::getAge($val->cruitUser->user_birthday) }}）{{ $val->cruitUser->user_sex ? config('const.gender_type.'.$val->cruitUser->user_sex) : '' }}</span></p>
                                            </div>
                                        </li>
                                    </ul>

                                    <div class="about_detail">
                                        <p class="money">{{ \App\Service\CommonService::getLessonMoneyRange(\App\Service\CommonService::showFormatNum($val['rc_wish_minmoney']), \App\Service\CommonService::showFormatNum($val['rc_wish_maxmoney']), true) }}<small>{{\App\Service\CommonService::getTimeUnit($val['rc_lesson_period_from'])}}</small></p>
                                        <p class="location">{{ \App\Service\AreaService::getOneAreaFullName($val->cruitUser->user_area_id) }}</p>
                                        <p class="date_time">
                                            <span>{{\App\Service\CommonService::getMd($val['rc_date'])}}</span>
                                            <span>{{\App\Service\CommonService::getStartAndEndTime($val['rc_start_time'], $val['rc_end_time'])}}</span>
                                        </p>
                                    </div>
                                    <div class="al-r">
                                        <span>{{ \Carbon\Carbon::parse($val->rc_stopped_at)->format('Y年n月j日') }}</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="board_box">
                            <p>検索結果 0件</p>
                        </div>
                    @endif

                </section>

                {{ $obj_recruits->links('vendor.pagination.senpai-admin-pagination') }}
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .info_ttl_wrap {
            justify-content: flex-start;
        }
        .tab_item {
            border-bottom: 2px solid #f1f1f1 !important;
            border-top: 2px solid #dad8d6 !important;
        }
        section {
            padding-top: 10px !important;
        }
        .ui-datepicker {
            font-size: 16px;
        }
        h3 {
            font-weight: normal;
        }
        h3.closed {
            margin-bottom: 0px;
        }
        .search-result-area {
            padding: 15px;
            background: white;
        }
        table {
            width: 100%;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .td-detail {
            text-align: center;
            vertical-align: middle;
            width: 55px;
        }
        .ico-user {
            width: 50px;
            margin-right: 5px;
        }
    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
            });

            // 検索条件
            $('.type_arrow').click(function(){
                if($(this).hasClass('opened')) {
                    $(this).removeClass('opened');
                    $(this).removeClass('type_arrow_top');
                    $(this).addClass('closed');
                    $(this).addClass('type_arrow_bottom');
                    $('.search-condition').addClass("hide");
                } else {
                    $(this).addClass('opened');
                    $(this).addClass('type_arrow_top');
                    $(this).removeClass('closed');
                    $(this).removeClass('type_arrow_bottom');
                    $('.search-condition').removeClass("hide");
                }
            });

            // profile
            $('.profile').click(function() {
                let lesson_id = $(this).attr('data-id');
                location.href = "{{ route('admin.fraud_stop_lesson.detail') }}"+ "/" + lesson_id;
            });
        });
    </script>
@endsection
