@extends('admin.layouts.app')

@section('title', '新規作成')

@section('content')
    <div id="contents">
        <div class="tabs form_page">
            <div class="under-title">
                <button type="button" onclick="location.href='{{ route('admin.news.index') }}'">＜</button>
                <label class="page-title" style="width: 100%;">新規作成</label>
            </div>
            <section>
                {{ Form::open(["route"=> ["admin.news.set_info"], "method"=>"post", "name"=>"frm_news", "id"=>"frm_news"]) }}
                    @include('admin.news._form')
                    <div class="btn mtb">
                        <button type="submit" class="open" name="publish">公開する</button>
                    </div>
                    <div class="btn mtb">
                        <button type="submit" class="draft" name="draft">下書きとして保存</button>
                    </div>
                {{ Form::close() }}
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
