@extends('admin.layouts.auth')
@section('title', '通知文確認')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_examination.alert_create')])

        <div class="tabs form_page">
            <section>
                {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}
                <div class="profile-area">
                    <div class="inner_box for-warning">
                        <h3>判定</h3>
                        <div>{{ $condition['agree_type'] == config('const.agree_flag.agree') ? '承認する' : '承認しない' }}</div>
                    </div>

                    <div>
                        <h3>通知内容</h3>
                        <div>
                            <div class="inner_box for-warning">
                                <h3>[タイトル]</h3>
                                <div>{{ $condition['alert_title'] }}</div>
                            </div>

                            <div class="control-date mt-20">
                                <div class="ft-bold">[本文]</div>
                                <div class="mt-5">
                                    <div class="orange-arrow">
                                        <h3 class="icon_form type_arrow_bottom type_arrow">本文を表示する</h3>
                                    </div>
                                    <div class="alert-content hide">
                                        <div class="pd-5 alert-text">
                                            <textarea class="no_edit_textarea" style="min-height: 400px;" readonly>{{ $condition['alert_text'] }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn mtb">
                        <button onclick="location.href='{{ route('admin.lesson_examination.send_alert') }}'" type="submit" onclick="location.href=''">送信する</button>
                    </div>
                </div>
                {{--{{ Form::close() }}--}}
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
        .type_arrow_top, .type_arrow_bottom {
            width: 140px;
            cursor: pointer;
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
                    $('.alert-content').addClass("hide");
                } else {
                    $(this).addClass('opened');
                    $(this).addClass('type_arrow_top');
                    $(this).removeClass('closed');
                    $(this).removeClass('type_arrow_bottom');
                    $('.alert-content').removeClass("hide");
                }
            });
        });
    </script>
@endsection
