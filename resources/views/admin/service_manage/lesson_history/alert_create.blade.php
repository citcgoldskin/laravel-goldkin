@extends('admin.layouts.auth')
@section('title', '通知文作成')

@section('content')
    <div id="contents">
        @if(isset($page_type) && $page_type == "recruit")
            @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_history_management.recruit_detail', ['recruit'=>$recruit])])
        @else
            @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_history_management.lesson_detail', ['lesson_request_schedule'=>$lesson_request_schedule])])
        @endif

        <div class="tabs form_page">
            <section>
                {{ Form::open(["route"=>"admin.lesson_history_management.do_alert_confirm", "method"=>"post", "name"=>"frm_confirm", "id"=>"frm_confirm"]) }}
                @if(isset($page_type) && $page_type == "recruit")
                    <input type="hidden" name="recruit" value="{{ $recruit }}">
                @else
                    <input type="hidden" name="lesson_request_schedule" value="{{ $lesson_request_schedule }}">
                @endif
                <input type="hidden" name="page_type" value="{{ isset($page_type) && $page_type ? $page_type : '' }}">

                <div class="profile-area">
                    <div class="inner_box for-warning">
                        <h3>タイトル</h3>
                        @php
                            $default_title = config('msg_template.service_manage.lesson_history.alert_title');
                        @endphp
                        <div class="input-text2 lesson_ttl_textarea">
                            <input type="text" id="alert_title" name="alert_title" value="{{ old('alert_title', $default_title) }}">
                        </div>
                        @error('alert_title')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div>
                        <h3>本文</h3>
                        @php
                            $default_content = config('msg_template.service_manage.lesson_history.alert_text');
                            // 利用規約リンクが必要
                        @endphp
                        <div class="input-text2 lesson_ttl_textarea">
                            <textarea type="text" id="alert_text" name="alert_text">{!! old('alert_text', $default_content) !!}</textarea>
                        </div>
                    </div>
                    @error('alert_text')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="btn mtb">
                        <button type="submit">送信する</button>
                    </div>
                    <div class="btn mtb">
                        @if(isset($page_type) && $page_type == "recruit")
                            <button type="button" onclick="location.href='{{ route('admin.lesson_history_management.recruit_detail', ['recruit'=>$recruit]) }}'">キャンセル</button>
                        @else
                            <button type="button" onclick="location.href='{{ route('admin.lesson_history_management.lesson_detail', ['lesson_request_schedule'=>$lesson_request_schedule]) }}'">キャンセル</button>
                        @endif
                    </div>
                </div>
                {{ Form::close() }}
            </section>

        </div><!-- /tabs -->


    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        section {
            padding-top: 10px !important;
        }
        h3 {
            font-weight: normal;
        }
        h3.closed {
            margin-bottom: 0px;
        }
        .profile-area {
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
        .td-label {
            font-weight: bold;
        }
        .upload-img {
            background: #eceae7;
            min-height: 150px;
        }
        #alert_text {
            height: 300px;
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
