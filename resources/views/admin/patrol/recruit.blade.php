@extends('admin.layouts.app')
@section('title', '掲示板')

@section('content')

    <div id="contents">
        @include('admin.layouts.header_under', ['no_action'=>1])

        {{ Form::open(["route"=>"admin.patrol.recruit", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" name="clear_condition" id="clear_condition" value="">

        <div class="">

            <section class="pb-0">
                <div class="staff-search-area">
                    <div class="">
                        <h3 class="icon_form type_arrow_top type_arrow opened">絞り込み</h3>
                    </div>
                    <div class="search-condition">
                        <div class="inner_box for-warning">
                            <h3>カテゴリー</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[category]" id="category">
                                    <option value="">--</option>
                                    @foreach($categories as $value)
                                        <option value="{{ $value->class_id }}" {{ (isset($search_params['category']) && !is_null($search_params['category']) ? $search_params['category'] : '') == $value->class_id ? 'selected' : '' }}>{{ $value->class_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>都道府県</h3>
                            @php
                                $prefecture_list = \App\Service\AreaService::getPrefectureList();
                            @endphp
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[province_id]" id="province_id">
                                    <option value="">--</option>
                                    @foreach($prefecture_list as $prefecture)
                                        <option value="{{ $prefecture->area_id }}" {{ (isset($search_params['province_id']) && !is_null($search_params['province_id']) ? $search_params['province_id'] : '') == $prefecture->area_id ? 'selected' : '' }}>{{ $prefecture->area_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div style="margin-bottom: 20px;" data-target="modal-area" class="modal-syncer lesson-discuss-area {{ isset($search_params['province_id']) && !is_null($search_params['province_id']) ?: 'action-disable' }}">
                            <h3>エリア</h3>
                            <input type="hidden" name="search_params[area_id]" id="area_id" value="{{ isset($search_params['area_id']) && !is_null($search_params['area_id']) ? $search_params['area_id'] : '' }}">
                            <div class="form_wrap icon_form type_arrow_right shadow-glay">
                                @php
                                    $area_name = "";
                                    if (isset($search_params['area_id']) && !is_null($search_params['area_id'])) {
                                        $area_name = \App\Service\AreaService::getAreaNames($search_params['area_id']);
                                    }
                                    if ($area_name == "") {
                                        $area_name = "指定なし";
                                    }
                                @endphp
                                <button type="button" class="form_btn btn_area">
                                    {{ $area_name }}
                                </button>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>レッスン開始日時</h3>
                            <div class="flex flex-wrap period-area mb-10 wp-100">
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay" style="width: 100%;">
                                    <input type="text" name="search_params[date]" id="date" class="form_btn datepicker" value="{{ isset($search_params['date']) ? $search_params['date'] : '' }}">
                                </div>
                            </div>
                            <div class="flex flex-wrap period-area flex-between">
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-5 time-input">
                                    <select name="search_params[start_hour]" class="fourth" id="start_hour">
                                        @foreach(range(0, 23, 1) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['start_hour']) ? $search_params['start_hour'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mr-5 flex-space">：</div>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-5 time-input">
                                    <select name="search_params[start_minute]" class="fourth" id="start_minute">
                                        @foreach(range(0, 59, 1) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['start_minute']) ? $search_params['start_minute'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mr-5 flex-space">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-5 time-input">
                                    <select name="search_params[end_hour]" class="fourth" id="end_hour">
                                        @foreach(range(0, 23, 1) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['end_hour']) ? $search_params['end_hour'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mr-5 flex-space">：</div>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-5 time-input">
                                    <select name="search_params[end_minute]" class="fourth" id="end_minute">
                                        @foreach(range(0, 59, 1) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['end_minute']) ? $search_params['end_minute'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>レッスン時間</h3>
                            <div class="flex flex-wrap period-area flex-between">
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 47%;">
                                    <select name="search_params[start_interval]" id="start_interval">
                                        <option value="">--</option>
                                        @foreach(range(15, 300, 15) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['start_interval']) ? $search_params['start_interval'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                                分
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-space">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 47%;">
                                    <select name="search_params[end_interval]" id="end_interval">
                                        <option value="">--</option>
                                        @foreach(range(15, 300, 15) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['end_interval']) ? $search_params['end_interval'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                                分
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>料金</h3>
                            <div class="flex flex-wrap period-area flex-between">
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 47%;">
                                    <select name="search_params[start_fee]" id="start_fee">
                                        <option value="">下限なし</option>
                                        @foreach(range(1000, 100000, 1000) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['start_fee']) ? $search_params['start_fee'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                                分
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-space">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 47%;">
                                    <select name="search_params[end_fee]" id="end_fee">
                                        <option value="">上限なし</option>
                                        @foreach(range(1000, 100000, 1000) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['end_fee']) ? $search_params['end_fee'] : '') == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}
                                                分
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>募集期限</h3>
                            <div class="flex flex-wrap period-area">
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-10">
                                    <input type="text" name="search_params[recruit_date]" id="recruit_date" class="form_btn datepicker" value="{{ isset($search_params['recruit_date']) ? $search_params['recruit_date'] : '' }}">
                                </div>
                                <div class="flex-space mr-10">日</div>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-10" style="width:100px;">
                                    <select name="search_params[period_hour]" class="fourth" id="period_hour">
                                        @foreach(range(0, 23, 1) as $i)
                                            <option value="{{$i}}"
                                                    @if((isset($search_params['period_hour']) ? $search_params['period_hour'] : 0) == $i) selected="selected" @endif>{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-space mr-10">時</div>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>性別</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[user_sex]" id="user_sex">
                                    <option value="">--</option>
                                    @foreach(config('const.gender_type') as $k => $v)
                                        @if($k)
                                            <option value="{{ $k }}" {{ $k == (isset($search_params['user_sex']) ? $search_params['user_sex'] : '')  ? "selected": ""}}>{{ $v }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>年代</h3>
                            <div class="flex flex-wrap period-area flex-between">
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 47%;">
                                    <select name="search_params[start_age]" id="start_age">
                                        <option value="">指定なし</option>
                                        @foreach(range(10, 70, 10) as $val)
                                            @php
                                                $age = '';
                                                if (isset($search_params['start_age']) && $search_params['start_age']) {
                                                    $age = (int)($search_params['start_age'] / 10);
                                                    if ($age == 0) {
                                                        $age = 10;
                                                    } else if($age > 7) {
                                                        $age = 70;
                                                    } else {
                                                        $age = $age * 10;
                                                    }
                                                }
                                            @endphp
                                            <option value="{{$val}}" {{ old('start_age', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-space">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 47%;">
                                    <select name="search_params[end_age]" id="end_age">
                                        <option value="">指定なし</option>
                                        @foreach(range(10, 70, 10) as $val)
                                            @php
                                                $age = '';
                                                if (isset($search_params['end_age']) && $search_params['end_age']) {
                                                    $age = (int)($search_params['end_age'] / 10);
                                                    if ($age == 0) {
                                                        $age = 10;
                                                    } else if($age > 7) {
                                                        $age = 70;
                                                    } else {
                                                        $age = $age * 10;
                                                    }
                                                }
                                            @endphp
                                            <option value="{{$val}}" {{ old('end_age', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>参加人数</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[numbers]" id="numbers">
                                    <option value="">--</option>
                                    @foreach(range(1, 10) as $value)
                                        <option value="{{$value}}" {{ (isset($search_params['numbers']) ? $search_params['numbers'] : '') == $value ? 'selected' : '' }}>{{ $value }}人</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="inner_box for-warning">
                            <h3>絞り込み（特別）</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[piro]" id="piro">
                                    <option value="">なし</option>
                                    <option value="1" {{ 1 == (isset($search_params['piro']) ? $search_params['piro'] : '')  ? "selected": ""}}>ぴろしきまるのみ</option>
                                </select>
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

        </div>

        {{ Form::close() }}

        <section class="yarukoto_list pb-0">
            <div class="flex" style="align-items: center;margin-bottom: 15px;">
                @php
                    $from_page = ($recruits->currentPage() - 1) * $recruits->perPage() + 1;
                    $to_page = $recruits->perPage() * $recruits->currentPage();
                    if ($to_page > $recruits->total()) {
                        $to_page = $recruits->total();
                    }
                @endphp
                @if($recruits->total() <= 1)
                    <div class="wp-50">全{{ $recruits->total() }}件</div>
                @else
                    <div class="wp-50">{{ $from_page }}件～{{ $to_page }}件（全{{ $recruits->total() }}件）</div>
                @endif
                <div class="wp-50 form_wrap icon_form type_arrow_bottom">
                    <select class="wp-100" name="search_params[sort_type]" id="sort_type">
                        @foreach(config('const.stop_lesson_sort') as $key=>$value)
                            <option value="{{ $key }}" {{ (isset($search_params['order']) && $search_params['order'] ? $search_params['order'] : config('const.stop_lesson_sort_code.register_new')) == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

        @foreach($recruits as $key => $val)
                <div class="board_box">
                    <a href="{{ route('admin.patrol.recruit.detail', ['recruit'=>$val['rc_id']])  }}">
                        <ul class="info_ttl_wrap">
                            <li>
                                <img src="{{\App\Service\CommonService::getLessonIconImgUrl($val['cruitLesson']['class_icon'])}}" alt="">
                            </li>
                            <li>
                                <p class="info_ttl {{ isset($val['is_visited']) && $val['is_visited'] == 1 ? 'navy_txt': 'blue_txt' }}">{{$val['rc_title']}}</p>
                            </li>
                        </ul>
                        <div class="about_detail">
                            <p class="money">{{ \App\Service\CommonService::getLessonMoneyRange($val['rc_wish_minmoney'], $val['rc_wish_maxmoney']) }}<small>{{ \App\Service\CommonService::getTimeUnit($val['rc_lesson_period_from']) }}</small></p>
                            <p class="location">{{ implode('/', $val->recruit_area_names) }}</p>
                            <p class="date_time">
                                <span>{{ \App\Service\CommonService::getMd($val['rc_date']) }}</span>
                                <span>{{ \App\Service\CommonService::getStartAndEndTime($val['rc_start_time'], $val['rc_end_time']) }}</span>
                            </p>
                        </div>

                        <ul class="teacher_info_03 mb0">
                            <li class="icon_s30"><img src="{{isset($val['cruitUser']) ? \App\Service\CommonService::getUserAvatarUrl($val['cruitUser']['user_avatar']) : ''}}" class="プロフィールアイコン"></li>
                            <li class="about_teacher" style="width: 90%;">
                                @php
                                    $age = isset($val['cruitUser']) ? \App\Service\CommonService::getAge($val['cruitUser']['user_birthday']) : '';
                                    $sex = isset($val['cruitUser']) ? \App\Service\CommonService::getSexStr($val['cruitUser']['user_sex']) : '';
                                    $date_recruit = \App\Service\TimeDisplayService::getDateFromDatetime($val['created_at']);
                                @endphp
                                <div class="profile_name">
                                    <p>{{isset($val['cruitUser']) ? $val['cruitUser']['name'] : ''}}   <span>（{{isset($age) ? $age : '' }}）{{$sex}}</span></p>
                                    <p><span>投稿日：{{$date_recruit}}</span></p>
                                </div>
                            </li>
                        </ul>
                    </a>
                    @php
                        $period_futre = \App\Service\CommonService::getDateRemain($val['rc_period']);
                    @endphp
                    @if(isset($period_futre) && $period_futre != "")
                        <span class="time_limit"><small>あと</small>{{$period_futre}}</span>
                    @endif
                </div>
            @endforeach

        </section>

        {{--{{ $recruits->appends(['search_params' =>$search_params])->links('vendor.pagination.senpai-admin-pagination') }}--}}
        {{ $recruits->links('vendor.pagination.senpai-admin-pagination') }}

        <div class="modal-wrap coupon_modal">
            <div id="modal-area" class="modal-content ajax-modal-container">
            </div>
        </div>

    </div><!-- /contents -->

@endsection

@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .flex-between {
            justify-content: space-between;
        }
        .time-input {
            width: 18% !important;
        }
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
                $('#form1').submit();
            });

            $('#sort_type').change(function() {
                let order_type = $(this).val();
                location.href="{{ route('admin.patrol.recruit') }}?order="+order_type;
            });

            $('#province_id').change(function() {
                let province_val = $(this).val();
                $.ajax({
                    type: "post",
                    url: '{{ route('keijibann.province_modal') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    dataType: 'json',
                    success: function (result) {
                        if(result.result_code == 'success') {
                            if (province_val == "") {
                                if (!$('.lesson-discuss-area').hasClass('action-disable')) {
                                    $('.lesson-discuss-area').addClass('action-disable')
                                }
                                $('.btn_area').text("指定なし");
                                $('#area_id').val("");
                            } else {
                                if ($('.lesson-discuss-area').hasClass('action-disable')) {
                                    $('.lesson-discuss-area').removeClass('action-disable')
                                }
                            }
                        } else {
                        }
                    }
                });
            });

            $('.lesson-discuss-area').click(function() {
                let province_id = $('#province_id').val();
                console.log("province_id", province_id);
                if (province_id == "") {
                    return;
                }
                let area_id_arr = $('#area_id').val();
                $.ajax({
                    type: "post",
                    url: '{{ route('admin.area_modal') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        province_id: province_id,
                        area_id_arr: area_id_arr
                    },
                    dataType: 'json',
                    success: function (result) {
                        if(result.result_code == 'success') {
                            $('#modal-area').html('');
                            $('#modal-area').append(result.area_detail);

                        } else {
                        }
                    }
                });
            });

            $('.ajax-modal-container').on('click', '.clear_btn', function() {
                var i, j, child_count;
                var count = $("#area_count").val();
                for(j = 1; j <= count; j++){
                    $("#c_" + j).prop('checked', false);
                    $("#area_" + j).val(0);
                }
            });
            $('.ajax-modal-container').on('click', '#btn_area_setting', function() {
                $('.start-active').addClass('appear');
                $("#modal-area,#modal-overlay").fadeOut("fast", function () {

                    $('#modal-overlay').remove();

                });

                let province_name = $('.btn_province').text();
                let area_ids = [];
                let area_names = [];
                $('input[name=area]:checked').each(function(){
                    area_ids.push($(this).attr('data-area-id'));
                    area_names.push($(this).attr('data-area-name'));
                });
                let area_id = $(this).attr('data-id');
                console.log("area_id", area_id);
                let area_name = $(this).attr('data-name');
                console.log("area_names", area_names);
                if(area_names.length == 0) {
                    $('.btn_area').text("指定なし");
                } else {
                    $('.btn_area').text(area_names.join('、'));
                }
                $('#area_id').val(area_ids.join(','));
            });

            $('.modal-close').click(function () {
                alert("modal-close");
                $('.modal-wrap').fadeOut();
                $(this).parents('.modal-content').fadeOut();
                $('#modal-overlay').fadeOut();
            });

            $('.ajax-modal-container').on('click', '.modal-close', function() {
                $('.start-active').addClass('appear');
                $("#modal-area,#modal-overlay").fadeOut("fast", function () {

                    $('#modal-overlay').remove();

                });
            });

        });
    </script>
@endsection


