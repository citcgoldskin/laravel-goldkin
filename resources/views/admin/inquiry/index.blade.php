@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">問い合わせ</label>
            <section>
                <div class="staff-search-area">
                    <div class="search-condition">
                        <div class="chk-rect mb-10">
                            <input type="checkbox" value="" name="chk-not-read" id="chk-purchase">
                            <label for="chk-purchase">購入実績あり</label>
                        </div>
                        <div class="flex-space inner_box">
                            <div class="lb-period">期間</div>
                            <div class="select-period form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[purchase_period]" id="purchase_period" class="action-none">
                                    <option value="">すべて</option>
                                    @foreach(config('const.inquiry_period') as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="chk-rect mb-10">
                            <input type="checkbox" value="" name="chk-not-read" id="chk-sales">
                            <label for="chk-sales">販売実績あり</label>
                        </div>
                        <div class="flex-space inner_box for-warning">
                            <div class="lb-period">期間</div>
                            <div class="select-period form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[sales_period]" id="sales_period" class="action-none">
                                    <option value="">すべて</option>
                                    @foreach(config('const.inquiry_period') as $k=>$v)
                                        <option value="{{$k}}">{{$v}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <section>
                <div class="flex" style="align-items: center;margin-bottom: 10px;">
                    <div class="wp-50">1件～50件（全100件）</div>
                    <div class="wp-50 form_wrap icon_form type_arrow_bottom">
                        <select class="wp-100" name="search_params[sort_type]" id="sort_type">
                            @foreach(config('const.stop_lesson_sort') as $key=>$value)
                                <option value="{{ $key }}" {{ old('search_params.sort_type', config('const.stop_lesson_sort_code.register_old')) == $key ? 'selected' : '' }}>{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="tabs search-result-area">
                    <ul class="todo_list talkroom_list">
                        <li class="right-arrow icon_form pos-relative">
                            <a href="{{ route('admin.inquiry.detail') }}">
                                <div class="icon-area">
                                    <img src="{{ asset('assets/admin/img/icon_member.png') }}">
                                </div>
                                <div class="text-area">
                                    <div class="text-small a-centre">
                                        <div class="gray_txt">ニックネーム（24） 男性  大阪</div>
                                    </div>
                                    <p>ユーザー種別：センパイ</p>
                                    <p>操作方法がわからない</p>
                                </div>
                            </a>
                        </li>
                        <li class="right-arrow icon_form pos-relative">
                            <a href="{{ route('admin.inquiry.detail') }}">
                                <div class="icon-area">
                                    <img src="{{ asset('assets/admin/img/icon_member.png') }}">
                                </div>
                                <div class="text-area">
                                    <div class="text-small a-centre">
                                        <div class="gray_txt">ニックネーム（24） 男性  大阪</div>
                                    </div>
                                    <p>ユーザー種別：センパイ</p>
                                    <p>操作方法がわからない</p>
                                </div>
                            </a>
                        </li>
                        <li class="right-arrow icon_form pos-relative">
                            <a href="{{ route('admin.inquiry.detail') }}">
                                <div class="icon-area">
                                    <img src="{{ asset('assets/admin/img/icon_member.png') }}">
                                </div>
                                <div class="text-area">
                                    <div class="text-small a-centre">
                                        <div class="gray_txt">ニックネーム（24） 男性  大阪</div>
                                    </div>
                                    <p>ユーザー種別：センパイ</p>
                                    <p>操作方法がわからない</p>
                                </div>
                            </a>
                        </li>
                        <li class="right-arrow icon_form pos-relative">
                            <a href="{{ route('admin.inquiry.detail') }}">
                                <div class="icon-area">
                                    <img src="{{ asset('assets/admin/img/icon_member.png') }}">
                                </div>
                                <div class="text-area">
                                    <div class="text-small a-centre">
                                        <div class="gray_txt">ニックネーム（24） 男性  大阪</div>
                                    </div>
                                    <p>ユーザー種別：センパイ</p>
                                    <p>操作方法がわからない</p>
                                </div>
                            </a>
                        </li>
                        <li class="right-arrow icon_form pos-relative">
                            <a href="{{ route('admin.inquiry.detail') }}">
                                <div class="icon-area">
                                    <img src="{{ asset('assets/admin/img/icon_member.png') }}">
                                </div>
                                <div class="text-area">
                                    <div class="text-small a-centre">
                                        <div class="gray_txt">ニックネーム（24） 男性  大阪</div>
                                    </div>
                                    <p>ユーザー種別：センパイ</p>
                                    <p>操作方法がわからない</p>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="al-c mt-20">
                    <div class="pager_area">
                        <ul>
                            <li class="prev_page"><a href="#"></a></li>
                            <li><a href="#">1</a></li>
                            <li class="now_page">2</li>
                            <li><a href="#">3</a></li>
                            <li>・・・</li>
                            <li><a href="#">99</a></li>
                            <li class="next_page"><a href="#"></a></li>
                        </ul>
                    </div>

                </div>
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
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
        .lb-period {
            width: 80px;
            font-size: 14px;
        }
        .select-period {
            width: calc(100% - 80px);
        }
        .todo_list {
            margin-bottom: 0px;
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
                location.href = "{{ route('admin.fraud_report.detail', ['user'=>1]) }}";
            });

            $('#chk-purchase').change(function() {
                if ($('#purchase_period').hasClass('action-none')) {
                    $('#purchase_period').removeClass('action-none')
                } else {
                    $('#purchase_period').addClass('action-none')
                }
            });

            $('#chk-sales').change(function() {
                if ($('#sales_period').hasClass('action-none')) {
                    $('#sales_period').removeClass('action-none')
                } else {
                    $('#sales_period').addClass('action-none')
                }
            });
        });
    </script>
@endsection
