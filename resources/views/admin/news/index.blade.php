@extends('admin.layouts.app')

@section('title', 'ニュース管理')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">ニュース管理</label>
            <section>
                <form method="GET" action="" accept-charset="UTF-8" name="form1" id="form1">

                    <ul class="btn-list">
                        <li><a href="{{ route('admin.news.news_list') }}">ニュース一覧</a></li>
                        <li><a href="{{ route('admin.news.create') }}">新規作成</a></li>
                        <li><a href="{{ route('admin.news.drafts') }}">下書き・非公開一覧</a></li>
                        <li><a href="{{ route('admin.news.reserves') }}">各予約一覧</a></li>
                    </ul>
                </form>
            </section>
        </div>
        <!-- /tabs -->

    </div>
@endsection

@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
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
        .profile {
            cursor: pointer;
        }
    </style>
@endsection

@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
