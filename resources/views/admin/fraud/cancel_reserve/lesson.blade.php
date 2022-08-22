@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">取り消し予約一覧</label>

            <section class="tab_area mb0">
                <div class="switch_tab">
                    <div class="type_radio radio-01">
                        <input type="radio" name="onof-line" id="off-line" checked="checked" onclick="location.href='{{ route('admin.fraud_cancel_reserve.lesson') }}'">
                        <label class="ok" for="off-line">レッスン</label>
                    </div>
                    <div class="type_radio radio-02">
                        <input type="radio" name="onof-line" id="on-line-1" onclick="location.href='{{ route('admin.fraud_cancel_reserve.recruit') }}'">
                        <label class="ok" for="on-line-1">投稿</label>
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
                                <a href="{{ route('admin.fraud_cancel_reserve.lesson_detail', ['lesson'=>$lesson->lesson_id]) }}">
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
        });
    </script>
@endsection
