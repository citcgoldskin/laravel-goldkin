@extends('admin.layouts.app')
@section('title', '新規追加')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.normal_question.detail', ['frequent_type'=>$frequent_type])])

        <div class="tabs form_page">
            <section>
                <div id="main-contents">
                    <div class="search-result-area">
                        <div class="faq-box">
                            <div class="question">
                                <h2>Q.本文</h2>
                                <p>{{ $question->que_ask }}</p>
                            </div>
                            <div class="answer">
                                <h2>A.本文</h2>
                                <textarea class="no_edit_textarea" readonly>{{ $question->que_answer }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div><!-- /tabs -->

    </div>
@endsection
@section('page_css')
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
    <script>
        $(document).ready(function () {
        });

        $(function(){

        });

        function modalConfirm(modal_id="") {
            // code
            if(modal_id == "modal-caution") {
                $('#frm_no_public').submit();
            }
        }
    </script>
@endsection
