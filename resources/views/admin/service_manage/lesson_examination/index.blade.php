@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">出品審査</label>

            {{ Form::open(["route"=>"admin.lesson_examination.index", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
            <section class="tab_area mb0">
                <div class="switch_tab">
                    @php
                        $cnt_new_lessons = \App\Service\LessonService::getNewLessonCount();
                        $cnt_change_lessons = \App\Service\LessonService::getChangeLessonCount();
                    @endphp
                    <div class="type_radio radio-01">
                        <input type="radio" name="search_params[onof-line]" id="off-line" value="{{ config('const.lesson_service_browser.new') }}" {{ (isset($search_params['onof-line']) && $search_params['onof-line'] ? $search_params['onof-line'] : config('const.lesson_service_browser.new')) == config('const.lesson_service_browser.new') ? "checked" : '' }}>
                        <label class="ok" for="off-line">新規サービス@if($cnt_new_lessons && $cnt_new_lessons > 0)<span class="midoku">{{ $cnt_new_lessons }}</span>@endif</label>
                    </div>
                    <div class="type_radio radio-02">
                        <input type="radio" name="search_params[onof-line]" id="on-line" value="{{ config('const.lesson_service_browser.change') }}" {{ (isset($search_params['onof-line']) && $search_params['onof-line'] ? $search_params['onof-line'] : '') == config('const.lesson_service_browser.change') ? "checked" : '' }}>
                        <label class="ok" for="on-line">差し替えサービス@if($cnt_change_lessons && $cnt_change_lessons > 0)<span class="midoku">{{ $cnt_change_lessons }}</span>@endif</label>
                    </div>
                </div>
            </section>

            <section>
                <div class="tabs">
                    <div class="form_wrap icon_form type_arrow_bottom sort-type">
                        <select name="search_params[sort_type]" id="sort_type">
                            <option value="0" {{ isset($search_params) && $search_params['sort_type'] == 0 ? 'selected' : '' }}>全て</option>
                            @if($lesson_classes->count())
                                @foreach($lesson_classes as $k=>$v)
                                    <option value="{{ $v->class_id }}" {{ isset($search_params) && $search_params['sort_type'] == $v->class_id ? 'selected' : '' }}>{{ $v->class_name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <table class="new-user">
                        <tbody>
                        @if($obj_lessons->count() > 0)
                            @foreach($obj_lessons as $lesson)
                            <tr>
                                <td>
                                    <div class="board_box lesson_box">
                                        <a href="{{ route('admin.lesson_examination.detail', ['lesson_id'=>$lesson['lesson_id'], 'page_type'=>isset($search_params['onof-line']) && $search_params['onof-line'] ? $search_params['onof-line'] : '']) }}">

                                            <ul class="info_ttl_wrap pb-0">
                                                <li>
                                                    <div class="img-box">
                                                        @php
                                                            $pic_arr = \App\Service\CommonService::unserializeData($lesson['lesson_image']);
                                                        @endphp
                                                        <img src="{{count($pic_arr) > 0 ? \App\Service\CommonService::getLessonImgUrl($pic_arr[0]) : ''}}" alt="イメージ">
                                                        <p>{{ $lesson->lesson_class ? $lesson->lesson_class->class_name : '' }}</p>
                                                    </div>
                                                </li>
                                                <li>
                                                    <p class="info_ttl">{{$lesson['lesson_title']}}</p>
                                                    <div class="profile_name">
                                                        <p>{{ $lesson->senpai->name }}<span>（{{ $lesson->senpai->age  }}）{{config('const.gender_type'.$lesson->senpai->user_sex) }}</span>{{\App\Service\CommonService::getSexStr($lesson->senpai->user_sex)}}<span> 実績：{{ \App\Service\LessonService::getScheduleCntByLessonId($lesson->lesson_id) }}件</span></p>
                                                    </div>
                                                    <div class="about_detail">
                                                        <p class="money">{{$lesson['lesson_30min_fees']}}円<small>/30分</small></p>
                                                        <p class="listing_area_ttl">出品エリア</p>
                                                        <p class="lesson_area_label">
                                                            {{ implode('/', $lesson->lesson_area_names) }}
                                                        </p>
                                                        <p class="al-r">
                                                            <span>{{ \Carbon\Carbon::parse($lesson->updated_at)->format('Y-m-d H:i') }}</span>
                                                        </p>
                                                    </div>
                                                    {{--<div class="listing_area">
                                                        <p class="listing_area_ttl">出品エリア</p>
                                                        <p class="lesson_area_label">
                                                            {{ implode('/', $lesson->lesson_area_names) }}
                                                        </p>
                                                    </div>--}}
                                                </li>
                                            </ul>

                                            {{--<div class="about_detail">
                                                <p class="money">{{$lesson['rc_wish_minmoney']}}円～{{$lesson['rc_wish_maxmoney']}}円<small>{{$lesson['lesson_time']}}</small></p>
                                                <p class="date_time">
                                                    <span>{{$lesson['date']}}</span>
                                                    <span>{{$lesson['start_end_time']}}</span>
                                                </p>
                                            </div>

                                            <div class="about_attention">
                                                <span class="attention_heart">{{$lesson['fav_count']}}</span>
                                                <span class="attention_look">{{$lesson['rc_views']}}</span>
                                                <span class="attention_update">{{$lesson['time_diff']}}前</span>
                                            </div>--}}
                                        </a>
                                        @if($lesson['pro_count'] > 0)
                                            <span class="midoku">{{$lesson['pro_count']}}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>
                                    <div class="board_box lesson_box">
                                        <p class="info_ttl" style="font-size: 14px;">検索結果 0件</p>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                    <table class="old-user">
                        <tbody>

                        </tbody>
                    </table>
                </div>
                {{--<div class="al-c">
                    <span><</span>1 2 3 ... 11 12<span>></span>
                </div>--}}
            </section>

        </div><!-- /tabs -->


    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .info_ttl_wrap img {
            width: 100%;
        }
        .sort-type {
            width: 230px;
            float:right;
            margin-bottom: 10px;
        }
        .page-title {
            margin-bottom: 20px;
        }
        .tab-label {
            font-size: 14px;
            font-weight: bold;
            padding: 10px;
            position: relative;
            background: #dbdbdb;
        }
        .active {
            background: white;
        }
        span.midoku {
            margin-left: 10px;
            right: unset;
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
            /*border: 1px solid #ddd;*/
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
        .listing_area {
            padding: 0px;
        }
        .about_detail .lesson_area_label {
            font-size: 10px;
            color: #78BCE6;
        }
        .listing_area > p:nth-of-type(2) {
            font-size: 10px;
            color: #78BCE6;
        }
        .info_ttl_wrap li:first-child {
            width: 30%;
        }
        .info_ttl_wrap li:last-child {
            width: 68%;
        }
        .board_box {
            margin-bottom: 0px;
        }
        .listing_area_ttl::before {
            top: 10px;
        }
        .listing_area_ttl {
            padding: 4px 0px 4px 20px !important;
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
            $('.tab-label').click(function(){
                if($(this).hasClass('tab-new')) {
                    // new
                    if($(this).hasClass('active')) {
                    } else {
                        $(this).addClass('active');
                        $('.new-user').removeClass('hide');
                        $('.tab-old').removeClass('active');
                        $('.old-user').addClass('hide');
                    }
                } else {
                    // old
                    if($(this).hasClass('active')) {
                    } else {
                        $(this).addClass('active');
                        $('.old-user').removeClass('hide');
                        $('.tab-new').removeClass('active');
                        $('.new-user').addClass('hide');
                    }
                }
            });
            $('#off-line, #on-line').click(function() {
                $('#form1').submit();
            });

            // detail
            $('.detail').click(function() {
                location.href = "{{ route('admin.staff_confirm.detail') }}";
            });

            $('#sort_type').change(function() {
                let order_type = $(this).val();
                location.href="{{ route('admin.lesson_examination.index') }}?order="+order_type;
            });
        });
    </script>
@endsection
