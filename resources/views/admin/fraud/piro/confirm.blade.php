@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.fraud_piro.register_alert", "method"=>"post", "name"=>"frm_register", "id"=>"frm_register"]) }}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">確認</label>
            <section>
                <div class="tabs search-result-area">

                    <div class="control-date mt-20">
                        <div class="ft-bold">対象ユーザー</div>
                        <div class="mt-5 flex flex-wrap">
                            <span>{{ $obj_user->name."　"}}</span><span>{{"ID：".$obj_user->user_no }}</span>
                        </div>
                    </div>

                    <div class="control-date mt-20">
                        <div class="ft-bold">決定</div>
                        <div class="mt-5">
                            {{ config('const.punishment_decision.'.$punishment_params['decision_type']) }}
                        </div>
                    </div>

                    <div class="control-date mt-20">
                        <div class="ft-bold">根拠通知</div>
                        <div class="mt-5">
                            @foreach($punishment_params['basis'] as $basis)
                            {{ config('const.basis.'.$basis)."　" }}
                            @endforeach
                        </div>
                    </div>

                    <div class="control-date mt-20">
                        <div class="ft-bold">理由</div>
                        <div class="mt-5">
                            @foreach($punishment_params['reason'] as $reason)
                                <p class="pd-5">{{ \App\Service\AppealService::getAppealClassName($reason) }}</p>
                            @endforeach
                        </div>
                    </div>

                    <div class="control-date mt-20">
                        <div class="ft-bold">通知文</div>
                        <div class="mt-5">
                            <div class="orange-arrow">
                                <h3 class="icon_form type_arrow_bottom type_arrow">通知文を表示する</h3>
                            </div>
                            <div class="alert-content hide">
                                {{--<p class="pd-5">{{ $punishment_params['alert_title'] }}</p>--}}
                                <div class="pd-5 alert-text">
                                    {!! nl2br($punishment_params['alert_text']) !!}
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="wp-100 pos-relative mt-50">
                        {{--<button class="btn btn-orange wp-100 mb-10" name="btn_hiro" onclick="location.href='{{ route('admin.fraud_piro.create', ['user'=>$own_user->id]) }}'">通知文を表示する</button>--}}
                        <button class="btn btn-orange wp-100 mb-10" name="btn_register">決定する</button>
                        <button class="btn btn-orange wp-100 mb-10" name="btn_hiro" id="btn_back">戻る</button>
                    </div>
                </div>
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .alert-text {
            border: 1px solid #aaa;
            border-radius: 5px;
            min-height: 200px;
        }
        .type_arrow_top, .type_arrow_bottom {
            width: 140px;
            cursor: pointer;
        }
        .icon_form::after {
            right: -10px;
            top: 11px;
        }
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
        span.yellow_mark {
            right: 20px;
        }
        span.pink_mark {
            right: 20px;
            top: 60px;
        }
        .mark_history {
            right: 15px !important;
            top: 50px !important;
        }
        .pie {
            width: 100px; height: 100px;
            border-radius: 50%;
            background: conic-gradient(yellow 0.09turn, green 0.09turn, blue 0.27turn, #666 0.27turn, #666 0.54turn, #000 0.54turn);
            position: relative;
        }
        .pie_cover {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            background: white;
            width: 85px;
            height: 85px;
        }
        .pie_label {
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-weight: bold;
            position: absolute;
        }
        .color-group {
            justify-content: space-between;
            line-height: 20px;
        }
        .color-area {
            align-items: center;
        }
        .color-block {
            width: 10px;
            height: 10px;
            margin-right: 3px;
        }
        .user-detail {
            width: calc(100% - 50px);
        }
        #stop_period {
            width: 60px;
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
            $('#decision_type').change(function(){
               if ($(this).val() == "{{ config('const.punishment_decision_code.lesson_article_stop') }}" || $(this).val() == "{{ config('const.punishment_decision_code.buy_sell_stop') }}") {
                   if($('#stop_period_area').hasClass('hide')) {
                       $('#stop_period_area').removeClass('hide');
                   }
               } else {
                   if(!$('#stop_period_area').hasClass('hide')) {
                       $('#stop_period_area').addClass('hide');
                       $('#stop_period').val('');
                   }
               }
            });
            $('#btn_back').click(function(e) {
                e.preventDefault();
                location.href="{{ route('admin.fraud_piro.create_alert') }}";
            });
        });
    </script>
@endsection
