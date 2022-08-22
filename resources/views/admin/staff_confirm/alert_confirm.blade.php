@extends('admin.layouts.auth')

@section('content')
    <div id="contents">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">通知文作成</label>
            <section>
                {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}
                <div class="profile-area">
                    <div class="inner_box for-warning">
                        <h3>判定</h3>
                        <div>{{ $condition['agree_type'] == config('const.person_confirm_agree_category.agree') ? '承認する' : '承認しない' }}</div>
                    </div>

                    <div class="inner_box for-warning">
                        <h3>通知文タイトル</h3>
                        <div>{{ $condition['alert_title'] }}</div>
                    </div>

                    <div class="inner_box for-warning">
                        <h3>本文メッセージ</h3>
                        <div class="white-space-bs">{!! $condition['alert_text'] !!}</div>
                    </div>

                    <button onclick="location.href='{{ route('admin.staff_confirm.send_alert') }}'" class="btn btn-orange wp-100 mb-10 mt-20">送信する</button>
                </div>
                {{--{{ Form::close() }}--}}

                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.staff_confirm.alert_create') }}'" name="btn_back">戻る</button>
                </div>
            </section>

        </div><!-- /tabs -->

    </div>
@endsection
@section('page_css')
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

    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
            });
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
