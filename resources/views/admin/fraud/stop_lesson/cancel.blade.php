@extends('admin.layouts.app')

@section('content')
    <div id="contents">
        {{ Form::open(["route" => "admin.fraud_stop_lesson.do_cancel", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            <input type="hidden" name="lesson_id" value="{{ $lesson_id }}">
            <section class="pt20">
                <div class="lesson_info_area">
                    <label class="page-title" style="width: 100%;">このレッスンの公開停止を取り消します。</label>

                </div>

                <div class="chk-area mt-50 pd-20">
                    <div class="chk-rect">
                        <input type="radio" class="chk-cancel-stop check-trigger" value="{{ config('const.stop_lesson_cancel.now') }}" name="radio-cancel" id="chk-cancel-stop">
                        <label for="chk-cancel-stop" id="cancel_stop">今すぐ公開停止を取り消す</label>
                    </div>
                    <div class="hide-cancel-stop pd-20">
                        当該レッスンを即時公開状態にします
                    </div>
                    <div class="chk-rect">
                        <input type="radio" class="chk-cancel-stop-reserve check-trigger" value="{{ config('const.stop_lesson_cancel.reserve') }}" name="radio-cancel" id="chk-cancel-stop-reserve">
                        <label for="chk-cancel-stop-reserve" id="cancel_stop_reserve">公開停止の取り消しを予約する</label>
                    </div>
                    <div class="hide-cancel-stop-reserve pd-20">
                        <p class="fs-14">当該レッスンを</p>
                        <div class="">
                            <ul class="time">
                                <li style="width: 120px;">
                                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                        <input name="cancel_date" id="cancel_date" type="text"  class="form_btn datepicker" value="" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                                    </div>
                                </li>
                                <li class="fs-14">日</li>
                                <li style="width: 55px;">
                                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                        <select name="cancel_hour" id="cancel_hour" class="fourth">
                                            <option value=""></option>
                                            @for ($i = 0; $i < 24; $i++)
                                                <option value="{{$i}}">{{ $i }}</option>
                                            @endfor
                                        </select></div>
                                </li>
                                <li class="fs-14">時</li>
                                <li style="width: 55px;">
                                    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                        <select name="cancel_minute" id="cancel_minute" class="fourth">
                                            <option value=""></option>
                                            @for ($i = 0; $i < 60; $i++)
                                                <option value="{{$i}}">{{ $i }}</option>
                                            @endfor
                                        </select></div>
                                </li>
                                <li class="fs-14">分</li>
                            </ul>
                            <p class="mt-10 fs-14">に取り消す</p>
                        </div>
                    </div>
                    <p id="warning_date_validate" class="error_text hide">予約日時を現在時刻以降に設定してください。</p>
                    <p id="warning_select" class="error_text hide">取り消方法を選択してください。</p>
                    <p id="warning_reserve_date" class="error_text hide">予約日時を正確に入力してください。</p>
                </div>

            </section>
        {{ Form::close() }}

            <section id="info_area">
                <button id="btn_create_alert" class="btn btn-orange modal-syncer wp-100 mb-10 mt-20" data-target="">設定する</button>
                <button class="btn wp-100 mb-10 mt-20" onclick="location.href='{{ route('admin.fraud_stop_lesson.detail') }}/{{$lesson_id}}'">戻る</button>
            </section>

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"btn_set",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"設定を完了します。<br>よろしいですか?",
            'modal_content_area'=>"modal_content_show",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"戻る",
        ])

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .hide-cancel-stop, .hide-cancel-stop-reserve {
            display: none;
        }
        .ui-datepicker {
            font-size: 16px;
        }

    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                minDate: 0,
            });

            $("#btn_create_alert").click(function () {
                if(!$('#warning_select').hasClass('hide')) {
                    $('#warning_select').addClass('hide');
                }
                if(!$('#warning_reserve_date').hasClass('hide')) {
                    $('#warning_reserve_date').addClass('hide');
                }
                if(!$('#warning_date_validate').hasClass('hide')) {
                    $('#warning_date_validate').addClass('hide');
                }

                // check 今すぐ公開停止を取り消す
                let is_checked_1 = $('#chk-cancel-stop').prop('checked');
                // check 公開停止の取り消しを予約する
                let is_checked_2 = $('#chk-cancel-stop-reserve').prop('checked');

                if (!is_checked_1 && !is_checked_2) {
                    if($('#warning_select').hasClass('hide')) {
                        $('#warning_select').removeClass('hide');
                    }
                    return;
                }

                if (is_checked_2) {
                    let cancel_date = $('#cancel_date').val();
                    let cancel_hour = $('#cancel_hour').val();
                    let cancel_minute = $('#cancel_minute').val();
                    if (cancel_date == "" || cancel_hour == "" || cancel_minute == "") {
                        if($('#warning_reserve_date').hasClass('hide')) {
                            $('#warning_reserve_date').removeClass('hide');
                        }
                        return;
                    }

                    let date_validate = new Date(cancel_date + " " + cancel_hour + ":" + cancel_minute);
                    let now = new Date();
                    if(date_validate <= now) {
                        if($('#warning_date_validate').hasClass('hide')) {
                            $('#warning_date_validate').removeClass('hide');
                        }
                        return;
                    }

                    //btn_set
                    return;
                }
            });
            $("#cancel_stop_reserve").click(function () {
            });
            $('.chk-cancel-stop').change( function() {
                if($('[id=chk-cancel-stop]').prop('checked')){
                    $('.hide-cancel-stop').show();
                    $('.hide-cancel-stop-reserve').hide();
                    $('#modal_content_show').empty().html('今すぐ公開停止を取り消す。');
                    $('#btn_create_alert').attr('data-target', "btn_set");
                } else  {
                    $('.hide-cancel-stop').hide();
                }
            });

            $('.chk-cancel-stop-reserve').change( function() {
                if($('[id=chk-cancel-stop-reserve]').prop('checked')){
                    $('.hide-cancel-stop-reserve').show();
                    $('.hide-cancel-stop').hide();
                    let cancel_date = $('#cancel_date').val('');
                    let cancel_hour = $('#cancel_hour').val('');
                    let cancel_minute = $('#cancel_minute').val('');
                    let msg = '当該レッスンを' + cancel_date + '日' + cancel_hour + '時' + cancel_minute + '分に取り消す';
                    $('#modal_content_show').empty().html(msg);
                    $('#btn_create_alert').attr('data-target', "");
                } else  {
                    $('.hide-cancel-stop-reserve').hide();
                }
            });

            $('#cancel_date, #cancel_hour, #cancel_minute').change(function() {
                console.log("changed!!!");
                let cancel_date = $('#cancel_date').val();
                let cancel_hour = $('#cancel_hour').val();
                let cancel_minute = $('#cancel_minute').val();
                if (cancel_date != "" && cancel_hour != "" && cancel_minute != "") {
                    let date_validate = new Date(cancel_date + " " + cancel_hour + ":" + cancel_minute);
                    let now = new Date();
                    if(date_validate > now) {
                        $('#btn_create_alert').attr('data-target', "btn_set");
                    }
                } else {
                    $('#btn_create_alert').attr('data-target', "");
                }
                let msg = '当該レッスンを' + cancel_date + '日' + cancel_hour + '時' + cancel_minute + '分に取り消す';
                $('#modal_content_show').empty().html(msg);
            });

            /*$('.modal-syncer').click(function(e) {
                alert("test");
                e.preventDefault();
                if(!$('[id=chk-cancel-stop]').prop('checked') && !$('[id=chk-cancel-stop-reserve]').prop('checked')) {
                    return false;
                }
            });*/
        });

        // modal confirm function
        function modalConfirm(modal_id="") {
            $('#form1').submit();
        }
    </script>
@endsection
