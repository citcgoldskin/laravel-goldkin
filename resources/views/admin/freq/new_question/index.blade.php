@extends('admin.layouts.app')
@section('title', '新規作成')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.index')])

        {{ Form::open(["route"=>"admin.freq.new_question.create", "method"=>"post", "name"=>"frm_new_question", "id"=>"frm_new_question"]) }}

        <div class="tabs form_page">

            <section>
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>カテゴリー</h2>
                        <div class="select-box">
                            <select class="text-left" name="category" id="category">
                                @foreach($categories as $category)
                                    <option value="{{ $category['qc_id'] }}" {{ old('category', isset($params['category']) ? $params['category'] : '') == $category['qc_id'] ? 'selected' : '' }}>{{ $category['qc_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <h2>サブカテゴリー</h2>
                        @php
                            $sub_categories = [];
                            if (isset($categories) && count($categories) > 0) {
                                $sub_categories = \App\Service\QuestionService::getQuesClassFromParentAdmin(old('category', isset($params['category']) ? $params['category'] : $categories[0]['qc_id']));
                            }
                        @endphp
                        <div class="select-box">
                            <select class="text-left" name="sub_category" id="sub_category">
                                @foreach($sub_categories as $category)
                                    <option value="{{ $category['qc_id'] }}" {{ old('sub_category', isset($params['sub_category']) ? $params['sub_category'] : '') == $category['qc_id'] ? 'selected' : '' }}>{{ $category['qc_name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('sub_category')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <h2>Q.本文</h2>
                        <textarea name="question" id="question">{{ old('question', isset($params['question']) ? $params['question'] : '') }}</textarea>
                        @error('question')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <h2>A.本文</h2>
                        <textarea name="answer" id="answer">{{ old('answer', isset($params['answer']) ? $params['answer'] : '') }}</textarea>
                        @error('answer')
                            <span class="error_text">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <div class="btn mtb">
                            <button type="submit" class="pubric" id="btn_new_create">新規登録しすぐ公開する</button>
                        </div>
                        <div class="btn mtb">
                            <button type="button" class="reserve" id="btn_reserve">公開予約をして登録する</button>
                        </div>
                        <div class="btn mtb">
                            <button type="submit" onclick="location.href=''" class="draft" id="btn_draft">下書きとして保存</button>
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
            /*$(".hamburger").click(function () {
                $("#overlay").fadeToggle('slow');
                $(".hamburger").toggleClass("menu-close");

            });
            $(".menu_fraud").click(function (e) {
                e.preventDefault();
                /!*$("#overlay").fadeToggle('slow');
                $(".hamburger").toggleClass("menu-close");*!/
                $('.main-menu').addClass('hide');
                $('.second-menu-1').removeClass('hide');
            });*/
            $("#category").change(function() {
                let category = $(this).val();
                console.log("category", category);

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
                $('#frm_new_question').attr('action', "<?php echo e(route('admin.freq.new_question.draft')); ?>");
                $('#frm_new_question').submit();
            });

            $('#btn_reserve').click(function(e) {
                e.preventDefault();
                $('#frm_new_question').attr('action', "<?php echo e(route('admin.freq.new_question.reserve')); ?>");
                $('#frm_new_question').submit();
            });
        });

        /*$(function(){
            $('.sort').css('display', 'none');
            $(".extraction").change(function() {
                var extraction_val = $(".extraction").val();
                if(extraction_val == "すべて") {
                    $('.sort').css('display', 'none');
                }else if(extraction_val == "先輩向け") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "後輩向け") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "センパイについて") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "アカウントについて") {
                    $('.sort').css('display', 'block');
                }else if(extraction_val == "各種機能・その他") {
                    $('.sort').css('display', 'block');
                }
            });
        });*/
    </script>
@endsection
