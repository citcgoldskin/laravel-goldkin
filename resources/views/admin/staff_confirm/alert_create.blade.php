@extends('admin.layouts.auth')

@section('content')
    <div id="contents">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">通知文作成</label>
            <section>
                {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_confirm", "id"=>"frm_confirm"]) }}
                <div class="profile-area">
                    <div class="inner_box for-warning">
                        <h3>タイトル</h3>
                        @php
                            $default_title = "本人確認が完了しました。";
                            if (isset($condition['agree_type']) && $condition['agree_type'] == config('const.person_confirm_agree_category.disagree')) {
                                $default_title = "本人確認ができません。";
                            }
                        @endphp
                        <div class="input-text2 lesson_ttl_textarea">
                            <input type="text" id="alert_title" name="alert_title" value="{{ old('alert_title', isset($condition['alert_title']) && $condition['alert_title'] ? $condition['alert_title'] : $default_title) }}">
                        </div>
                    </div>

                    <div>
                        <h3>本文メッセージ</h3>
                        @php
                            $default_content = ($obj_user && $obj_user->name ? $obj_user->name : '')."さんの本人確認が完了しました。\nセンパイ出品からレッスンを出品することができます。\nまた、本人確認を必須としているセンパイのレッスンにもお申し込みができます。\n\n引き続きセンパイのご利用をよろしくお願いいたします。";
                            if (isset($condition['agree_type']) && $condition['agree_type'] == config('const.person_confirm_agree_category.disagree')) {
                                $reason = "";
                                if(isset($condition['chk-disagree']) && count($condition['chk-disagree']) > 0) {
                                    foreach ($condition['chk-disagree'] as $key=>$val) {
                                       $reason .= "・".config('const.person_confirm_disagree_type.'.$val);
                                       if ($key < count($condition['chk-disagree'])-1) {
                                           $reason .= "\n";
                                       }
                                    }
                                }
                                $default_content = "本人確認書類の提出ありがとうございます。\n確認させて頂きましたところ、以下の不備がございました\n\n".$reason."\n\n修正を行い、再度申請をお願いいたします。";
                            }
                        @endphp
                        <div class="input-text2 lesson_ttl_textarea">
                            <textarea type="text" id="alert_text" name="alert_text">{!! old('alert_text', isset($condition['alert_text']) && $condition['alert_text'] ? $condition['alert_text'] : $default_content) !!}</textarea>
                        </div>
                    </div>

                    <button id="btn_confirm" class="btn btn-orange wp-100 mb-10 mt-20">確認する</button>
                </div>
                {{ Form::close() }}

                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.staff_confirm.detail') }}/{{$condition['person_confirm_id']}}'" name="btn_back">戻る</button>
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
        #alert_text {
            height: 300px;
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
            $('#btn_confirm').click(function(e) {
                e.preventDefault();
                $('#frm_confirm').attr('action', "{{ route("admin.staff_confirm.do_alert_confirm") }}").submit();
            });
        });
    </script>
@endsection
