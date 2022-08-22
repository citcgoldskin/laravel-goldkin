@extends('admin.layouts.auth')

@section('content')
    <div id="contents">

        {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">ぴろしきまる履</label>
            <section class="mt-10 pb-0">
                <div class="flex profile search-result-area">
                    <div class="ico ico-user">
                        <img src="{{ $punishment->user->avatar_path }}">
                    </div>
                    <div>
                        <div class="pb-5 ft-bold">{{ $punishment->user->user_name }}{{ "（".\App\Service\CommonService::getAge($punishment->user->user_birthday)."）" }}</div>
                        <div class="pb-5">{{ $punishment->user->user_sex ? config('const.gender_type.'.$punishment->user->user_sex) : '' }}&nbsp;{{ $punishment->user->user_area_name }}</div>
                        <div>ID：{{ $punishment->user->user_no }}</div>
                    </div>
                    <span class="pink_mark">{{ $punishment->user->punishment_cnt ? $punishment->user->punishment_cnt : 0 }}</span>
                </div>
            </section>
            <section>
                <div class="tabs search-result-area">
                    <div class="flex-space mb-10">
                        <div class="ft-bold ft-14">通報したユーザー</div>
                    </div>
                    <table>
                        <tbody>
                        @if(count($appeal_users) > 0)
                            @foreach($appeal_users as $appeal_user)
                                @php
                                    $obj_user = $appeal_user->user;
                                @endphp
                                <tr class="report-detail">
                                    <td>
                                        <div class="flex">
                                            <div class="ico ico-user">
                                                <img src="{{ $obj_user->avatar_path }}">
                                            </div>
                                            <div class="user-detail">
                                                <div class="pb-5 ft-bold">{{ $obj_user->user_name }}{{ "（".\App\Service\CommonService::getAge($obj_user->user_birthday)."）" }}{{ $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}</div>
                                                <div class="pb-5">理由：{{ $appeal_user->all_reason }}</div>
                                                <div>通報日：{{ \Carbon\Carbon::parse($appeal_user->reported_at)->format('Y.n.j') }}</div>
                                                <div class="appeal-note mt-5">
                                                    <p class="ft-bold">詳細</p>
                                                    <div class="mt-5">
                                                        {!! nl2br($appeal_user->note) !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>検索結果 0件</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>

                    <div class="control-date mt-20">
                        <div class="ft-bold">処理日</div>
                        <div class="mt-5">{{ \Carbon\Carbon::parse($punishment->decided_at)->format('Y.n.j H:i') }}</div>
                    </div>
                    <div class="control-date mt-20">
                        <div class="ft-bold">内容</div>
                        <div class="mt-5">{{ config('const.punishment_decision.'.$punishment->type) }}</div>
                        @if($punishment->type == config('const.punishment_decision_code.lesson_article_stop') || $punishment->type == config('const.punishment_decision_code.buy_sell_stop'))
                            @if($punishment->stop_period)
                                <div class="mt-5">{{ $punishment->stop_period_date }}</div>
                            @endif
                        @endif
                    </div>
                    <div class="control-date mt-20">
                        <div class="ft-bold">理由</div>
                        @if($punishment->reason)
                            @php
                                $punishment_reasons = json_decode($punishment->reason, true);
                            @endphp
                            @foreach($punishment_reasons as $reason)
                                <p class="mt-5">{{ \App\Service\AppealService::getAppealClassName($reason) }}</p>
                            @endforeach
                        @endif
                    </div>

                    <div class="dv-alert mt-20">
                        <div class="orange-arrow">
                            <h3 class="icon_form type_arrow_bottom type_arrow">通知文を表示する</h3>
                        </div>
                        <div class="alert-content hide">
                            <p class="pd-5">{{ $punishment->alert_title }}</p>
                            <div class="pd-5 alert-text">
                                {!! nl2br($punishment->alert_text) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="history.back();" name="btn_back">戻る</button>
                </div>
            </section>

        </div><!-- /tabs -->

        {{--{{ Form::close() }}--}}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .alert-text {
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
            top: 50%;
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
