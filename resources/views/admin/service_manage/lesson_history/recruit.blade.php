@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.lesson_history_management.recruit", "method"=>"get", "name"=>"frm_history", "id"=>"frm_history"]) }}
        <input type="hidden" name="clear_condition" id="clear_condition" value="">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">掲示板履歴</label>

            <section class="tab_area mb0">
                <div class="switch_tab">
                    <div class="type_radio radio-01">
                        <input type="radio" name="onof-line" id="off-line" onclick="location.href='{{ route('admin.lesson_history_management.lesson') }}'">
                        <label class="ok" for="off-line">出品レッスン</label>
                    </div>
                    <div class="type_radio radio-02">
                        <input type="radio" name="onof-line" id="on-line-1" checked="checked" onclick="location.href='{{ route('admin.lesson_history_management.recruit') }}'">
                        <label class="ok" for="on-line-1">掲示板</label>
                    </div>
                </div>
            </section>

            <section>
                <div class="staff-search-area">
                    <div class="">
                        <h3 class="icon_form type_arrow_top type_arrow opened">絞り込み</h3>
                    </div>
                    <div class="search-condition">
                        <div class="inner_box for-warning">
                            <h3>ステータス</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[status]" id="status">
                                    <option value="">--</option>
                                    @foreach(config('const.recruit_history_status') as $key=>$value)
                                        <option value="{{ $key }}" {{ (isset($search_params['status']) && !is_null($search_params['status']) ? $search_params['status'] : '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>期間</h3>
                            <div class="flex flex-wrap period-area">
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-10">
                                    <input type="text" name="search_params[from_date]" id="from_date" class="form_btn datepicker" value="{{ isset($search_params['from_date']) ? $search_params['from_date'] : '' }}">
                                </div>
                                <div class="flex-space mr-10">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                    <input type="text" name="search_params[to_date]" id="to_date" class="form_btn datepicker" value="{{ isset($search_params['to_date']) ? $search_params['to_date'] : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>エリア</h3>
                            @php
                                $prefecture_list = \App\Service\AreaService::getPrefectureList();
                            @endphp
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[area]" id="area">
                                    <option value="">--</option>
                                    @foreach($prefecture_list as $prefecture)
                                        <option value="{{ $prefecture->area_id }}" {{ (isset($search_params['area']) && !is_null($search_params['area']) ? $search_params['area'] : '') == $prefecture->area_id ? 'selected' : '' }}>{{ $prefecture->area_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ID（後輩）</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="kouhai_user_no" name="search_params[kouhai_user_no]" value="{{ isset($search_params['kouhai_user_no']) ? $search_params['kouhai_user_no'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ニックネーム（後輩）</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="kouhai_nickname" name="search_params[kouhai_nickname]" value="{{ isset($search_params['kouhai_nickname']) ? $search_params['kouhai_nickname'] : '' }}">
                            </div>
                        </div>

                        <div class="flex" style="margin-top: 20px">
                            <div class="wp-35" style="padding-right: 5px;">
                                <button id="btn_clear" class="btn btn-clear wp-100" name="btn_clear">クリア</button>
                            </div>
                            <div class="wp-65 pos-relative">
                                <button id="btn_search" class="btn btn-search wp-100" name="btn_search">この条件で検索</button>
                            </div>
                        </div>
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
                        @foreach($obj_recruits as $recruit)
                            <div class="lesson_box admin_lesson_box">
                                <a href="{{ route('admin.lesson_history_management.recruit_detail', ['recruit'=>$recruit->rc_id]) }}">
                                    <div class="flex flex-wrap flex-label">
                                        <div class="status-type">{{ $recruit->status_name }}</div>
                                        <div>{{ $recruit->updated_at }}</div>
                                    </div>
                                    <div class="flex flex-wrap flex-center">
                                        <div class="{{ $recruit->cruitUser->user_sex == config('const.sex.woman') ? 'pink-color' : 'water-color' }}">{{ $recruit->cruitUser->name }}<span>（{{\App\Service\CommonService::getAge($recruit->cruitUser->user_birthday)}}）</span></div>
                                        <div>{{ $recruit->rc_title }}</div>
                                    </div>
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="lesson_box admin_lesson_box">
                            <p>検索結果 0件</p>
                        </div>
                    @endif

                    {{--@if(count($obj_recruits) > 0)
                        @foreach($obj_recruits as $key=>$val)
                            <div class="board_box">
                                <a href="{{ route('admin.lesson_history_management.recruit_detail', ['recruit'=>$val->rc_id]) }}">

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
                                        <p class="location">{{ implode('/', $val->recruit_area_names) }}</p>
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
                    @endif--}}

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
        .admin_lesson_box {
            padding: 10px;
            margin-bottom: 10px;
        }
        .admin_lesson_box a {
            display: block;
        }
        .flex-label {
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }
        .flex-label .status-type {
            font-size: 14px;
            font-weight: bold;
        }
        .flex-center {
            justify-content: center;
        }

        .period-area .form_wrap {
            width: 140px;
        }
        .period-area .form_wrap {
            width: 140px;
        }
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
        .ui-datepicker-current {
            display: none;
        }
    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                showButtonPanel: true,
                closeText: '指定なし',
                onClose: function (dateText, inst) {
                    if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
                        document.getElementById(this.id).value = '';
                    }
                }
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

            $('#btn_clear').click(function() {
                $('#clear_condition').val(1);
                $('#frm_history').submit();
            });

            $('#sort_type').change(function() {
                let order_type = $(this).val();
                location.href="{{ route('admin.lesson_history_management.recruit') }}?order="+order_type;
            });
        });
    </script>
@endsection
