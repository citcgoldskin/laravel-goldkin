@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.fraud_stop_lesson.index", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" name="clear_condition" id="clear_condition" value="">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">公開停止レッスン</label>

            <section>
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
                            <h3>ID（レッスン出品者）</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="user_no" name="search_params[user_no]" value="{{ isset($search_params['user_no']) ? $search_params['user_no'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ニックネーム（レッスン出品者）</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="nickname" name="search_params[nickname]" value="{{ isset($search_params['nickname']) ? $search_params['nickname'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ぴろしきまるのみ表示</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[punishment]" id="punishment">
                                    <option value="">--</option>
                                    @foreach(config('const.punishment_show') as $key=>$value)
                                        @if($key != config('const.punishment_show_code.no_apply'))
                                            <option value="{{ $key }}" {{ (isset($search_params['punishment']) && $search_params['punishment'] ? $search_params['punishment'] : '') == $key ? "selected" : '' }}>{{ $value }}</option>
                                        @endif
                                    @endforeach
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

            <section>
                <div class="chk-rect mb-10">
                </div>
                <div class="flex" style="align-items: center;margin-bottom: 10px;">
                    @php
                        $from_page = ($obj_lessons->currentPage() - 1) * $obj_lessons->perPage() + 1;
                        $to_page = $obj_lessons->perPage() * $obj_lessons->currentPage();
                        if ($to_page > $obj_lessons->total()) {
                            $to_page = $obj_lessons->total();
                        }
                    @endphp
                    @if($obj_lessons->total() <= 1)
                        <div class="wp-50">全{{ $obj_lessons->total() }}件</div>
                    @else
                        <div class="wp-50">{{ $from_page }}件～{{ $to_page }}件（全{{ $obj_lessons->total() }}件）</div>
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

                    @if(count($obj_lessons) > 0)
                        @foreach($obj_lessons as $lesson)
                            <div class="lesson_box admin_lesson_box">
                                <a href="{{ route('admin.fraud_stop_lesson.detail', ['lesson'=>$lesson->lesson_id]) }}">
                                    <div class="img-box">
                                        @php
                                            $lesson_image = \App\Service\LessonService::getLessonFirstImage($lesson);
                                        @endphp
                                        <img src="{{ \App\Service\CommonService::getLessonImgUrl($lesson_image) }}">
                                        <p>{{ $lesson->lesson_class ? $lesson->lesson_class->class_name : '' }}</p>
                                    </div>
                                    <div class="lesson_info_box">
                                        <p class="lesson_name">{{ $lesson->lesson_title }}</p>
                                        <p class="lesson_price"><em>{{\App\Service\CommonService::showFormatNum($lesson->lesson_30min_fees)}}</em><span>円 / <em>30</em>分〜</span></p>
                                        <div class="teacher_name">
                                            <div><img src="{{ \App\Service\CommonService::getUserAvatarUrl($lesson->senpai->user_avatar) }}" alt=""></div>
                                            <div>{{$lesson->senpai->name}}（<em>{{\App\Service\CommonService::getAge($lesson->senpai->user_birthday)}}</em>）</div>
                                        </div>
                                    </div>
                                </a>
                                <div class="al-r">
                                    <span>{{ \Carbon\Carbon::parse($lesson->lesson_stopped_at)->format('Y年n月j日') }}</span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="lesson_box admin_lesson_box">
                            <p>検索結果 0件</p>
                        </div>
                    @endif

                </section>

                {{ $obj_lessons->links('vendor.pagination.senpai-admin-pagination') }}
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .period-area .form_wrap {
            width: 140px;
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
                location.href="{{ route('admin.fraud_stop_lesson.index') }}?order="+order_type;
            });
        });
    </script>
@endsection
