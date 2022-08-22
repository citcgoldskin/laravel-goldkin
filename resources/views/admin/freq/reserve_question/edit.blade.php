@extends('admin.layouts.app')
@php
    $reserve_type_title = "公開";
    $reserve_date_at = $question->que_public_at;
    if($reserve_type == config('const.reserve_type_code.public')) {
        $reserve_type_title = '公開';
    } else if($reserve_type == config('const.reserve_type_code.update')) {
        $reserve_type_title = '変更';
        $reserve_date_at = $question->que_update_at;
    } else if($reserve_type == config('const.reserve_type_code.delete')) {
        $reserve_type_title = '削除';
        $reserve_date_at = $question->que_delete_at;
    }
@endphp

@section('title', $reserve_type_title.'予約')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.reserve_question')])

        {{ Form::open(["route"=>"admin.freq.question.questionEdit", "method"=>"post", "name"=>"frm_new_question", "id"=>"frm_new_question"]) }}
        <input type="hidden" name="question_id" value="{{ $question->que_id }}">

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>カテゴリー</h2>
                        <div class="select-box">
                            <select class="text-left" name="category" id="category">
                                <option value="{{ $params['category_id'] }}">{{ $params['category_name'] }}</option>
                            </select>
                        </div>
                        <h2>サブカテゴリー</h2>
                        <div class="select-box">
                            <select class="text-left" name="sub_category" id="sub_category">
                                <option value="{{ $question->question_category->qc_id }}">{{ $question->question_category->qc_name }}</option>
                            </select>
                        </div>
                        <h2>Q.本文</h2>
                        <textarea name="question" id="question" readonly>{{ $params['question'] }}</textarea>
                        <h2>A.本文</h2>
                        <textarea name="answer" id="answer" readonly>{{ $params['answer'] }}</textarea>
                        <div class="btn mtb">
                            <button type="button" onclick="location.href='{{ route('admin.freq.reserve_question.update', ['question'=>$question->que_id, 'reserve_type'=>$reserve_type]) }}'" class="finish">内容を変更する</button>
                        </div>
                        <h2>{{ $reserve_type_title }}日時</h2>
                        <table class="total">
                            <tr>
                                <td><div class="select-box">
                                        <select class="text-left" name="year" id="year">
                                            <option value="{{ \Carbon\Carbon::parse($reserve_date_at)->format('Y') }}">{{ \Carbon\Carbon::parse($reserve_date_at)->format('Y') }}</option>
                                        </select>
                                    </div></td>
                                <td class="text-center">年</td>
                                <td><div class="select-box">
                                        <select class="text-left" name="month" id="month" onchange="setLeapMonth('birthday')">
                                            <option value="{{ \Carbon\Carbon::parse($reserve_date_at)->format('m') }}">{{ \Carbon\Carbon::parse($reserve_date_at)->format('m') }}</option>
                                        </select>
                                    </div></td>
                                <td class="text-center">月</td>
                                <td><div class="select-box">
                                        <select class="text-left" name="day" id="day">
                                            <option value="{{ \Carbon\Carbon::parse($reserve_date_at)->format('d') }}">{{ \Carbon\Carbon::parse($reserve_date_at)->format('d') }}</option>
                                        </select>
                                    </div></td>
                                <td class="text-center">日</td>
                            </tr>
                        </table>
                        <table class="total">
                            <tr>
                                <td><div class="select-box">
                                        <select name="hour" class="text-left" id="hour">
                                            <option value="{{ \Carbon\Carbon::parse($reserve_date_at)->format('H') }}">{{ \Carbon\Carbon::parse($reserve_date_at)->format('H') }}</option>
                                        </select>
                                    </div></td>
                                <td class="text-center">時</td>
                                <td><div class="select-box">
                                        <select name="minute" class="fourth" id="minute">
                                            <option value="{{ \Carbon\Carbon::parse($reserve_date_at)->format('i') }}">{{ \Carbon\Carbon::parse($reserve_date_at)->format('i') }}</option>
                                        </select>
                                    </div></td>
                                <td class="text-center">分</td>
                            </tr>
                        </table>
                        <div class="btn mtb">
                            <button type="button" onclick="location.href='{{ route('admin.freq.reserve_question.change_date', ['question'=>$question->que_id, 'reserve_type'=>$reserve_type]) }}'" class="private">日時を変更する</button>
                        </div>
                    </div>
                </div>
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
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
        $(document).ready(function () {
            $("#category").change(function() {
                let category = $(this).val();

                $.ajax({
                    type: "post",
                    url: "{{route('admin.freq.get_sub_category')}}",
                    data : {
                        _token: "{{ csrf_token() }}",
                        category_id: category
                    },
                    dataType: 'json',
                    success : function(result) {

                        var html_data = '';
                        if(result.result_code == "success") {
                            let data = result.result;
                            for(let key in data)
                            {
                                html_data += '<option value="' + data[key].qc_id + '">' + data[key].qc_name + '</option>';
                            }
                        } else {
                            html_data = '<option value="0"></option>';
                        }

                        $('#sub_category').html(html_data);
                    }
                });
            });

            $('#btn_draft').click(function(e) {
                e.preventDefault();
                $('#frm_new_question').attr('action', "<?php echo e(route('admin.freq.question.draft')); ?>");
                $('#frm_new_question').submit();
            });

            $('#btn_reserve').click(function(e) {
                e.preventDefault();
                $('#frm_new_question').attr('action', "<?php echo e(route('admin.freq.question.reserve')); ?>");
                $('#frm_new_question').submit();
            });
        });
    </script>
@endsection
