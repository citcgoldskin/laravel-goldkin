@extends('admin.layouts.app')
@section('title', 'よくある質問')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">よくある質問</label>

            <section>
                <form method="GET" action="" accept-charset="UTF-8" name="form1" id="form1">
                    <div id="main-contents">
                        <div class="search-result-area">
                            <h2>アクセス数</h2>
                            <ul class="access-count">
                                <li>
                                    <dl>
                                        <dt>昨日:</dt>
                                        <dd>{{ $access_info['question_access']['yesterday'] }}</dd>
                                    </dl>
                                </li>
                                <li>
                                    <dl>
                                        <dt>今月:</dt>
                                        <dd>{{ $access_info['question_access']['this_month'] }}</dd>
                                    </dl>
                                </li>
                                <li>
                                    <dl>
                                        <dt>累計:</dt>
                                        <dd>{{ $access_info['question_access']['total'] }}</dd>
                                    </dl>
                                </li>
                            </ul>
                            <h2>問い合わせ件数</h2>
                            <ul class="access-count">
                                <li>
                                    <dl>
                                        <dt>昨日:</dt>
                                        <dd>5<span>(0.5%)</span></dd>
                                    </dl>
                                </li>
                                <li>
                                    <dl>
                                        <dt>今月:</dt>
                                        <dd>120<span>(0.5%)</span></dd>
                                    </dl>
                                </li>
                                <li>
                                    <dl>
                                        <dt>累計:</dt>
                                        <dd>550<span>(0.5%)</span></dd>
                                    </dl>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <ul class="btn-list">
                        <li><a href="{{ route('admin.freq.question', ['del_session'=>1]) }}">よくある質問一覧</a></li>
                        <li><a href="{{ route('admin.freq.normal_question') }}">よく見られている質問管理</a></li>
                        <li><a href="{{ route('admin.freq.new_question', ['del_session'=>1]) }}">新規作成</a></li>
                        <li><a href="{{ route('admin.freq.no_public_question') }}">下書き・非公開一覧</a></li>
                        <li><a href="{{ route('admin.freq.reserve_question', ['del_session'=>1]) }}">各予約一覧</a></li>
                        <li><a href="{{ route('admin.freq.category.index') }}">カテゴリー管理</a></li>
                    </ul>
                </form>
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
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
