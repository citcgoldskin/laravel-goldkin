@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.fraud_piro.create_alert_post", "method"=>"post", "name"=>"frm_create_alert", "id"=>"frm_create_alert"]) }}
        <input type="hidden" name="user_id" value="{{ $own_user->id }}">
        <input type="hidden" name="page_from" value="{{ $page_from ? $page_from : '' }}">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">ぴろしきまる</label>
            <section class="mt-10 pb-0">
                <div class="flex profile search-result-area">
                    <div class="ico ico-user">
                        <img src="{{ $own_user->avatar_path }}">
                    </div>
                    <div>
                        <div class="pb-5 ft-bold">{{ $own_user->user_name }}{{ "（".\App\Service\CommonService::getAge($own_user->user_birthday)."）" }}</div>
                        <div class="pb-5">{{ $own_user->user_sex ? config('const.gender_type.'.$own_user->user_sex) : '' }}&nbsp;{{ $own_user->user_area_name }}</div>
                        <div>ID：{{ $own_user->user_no }}</div>
                    </div>
                </div>
            </section>
            <section>
                <div class="tabs search-result-area">
                    <div class="flex-space mb-10">
                        <div class="ft-bold ft-14">通報したユーザー</div>
                        <div class="ft-bold">全 {{ count($appeal_users) }}件</div>
                    </div>
                    <table>
                        <tbody>
                        @if(count($appeal_users) > 0)
                            @foreach($appeal_users as $appeal_user)
                                @php
                                    $obj_user = $appeal_user->user;
                                @endphp
                                <tr>
                                    <td class="td-detail"><a class="title_orange report-detail" data-id="{{ $appeal_user->id }}">詳細</a></td>
                                    <td>
                                        <div class="flex">
                                            <div class="ico ico-user">
                                                <img src="{{ $obj_user->avatar_path }}">
                                            </div>
                                            <div class="calc_100p_50px">
                                                <div class="pb-5 ft-bold">{{ $obj_user->user_name }}{{ "（".\App\Service\CommonService::getAge($obj_user->user_birthday)."）" }}{{ $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}</div>
                                                <div class="pb-5">理由：{{ $appeal_user->all_reason }}</div>
                                                <div class="pb-5">通報日：{{ \Carbon\Carbon::parse($appeal_user->reported_at)->format('Y.n.j') }}</div>
                                                <div class="appeal-note mt-5 hide" id="appeal_note_{{ $appeal_user->id }}">
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
                        <div class="ft-bold">決定</div>
                        <div class="flex flex-wrap">
                            <div class="mt-5 form_wrap icon_form type_arrow_bottom">
                                <select name="decision_type" id="decision_type">
                                    <option value="">--</option>
                                    @foreach(config('const.punishment_decision') as $key=>$decision)
                                        <option value="{{ $key }}" {{ old('decision_type', isset($punishment_params['decision_type']) && $punishment_params['decision_type'] ? $punishment_params['decision_type'] : '') == $key ? 'selected' : '' }}>{{ $decision }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mt-5 flex {{ (old('decision_type', isset($punishment_params['decision_type']) && $punishment_params['decision_type'] ? $punishment_params['decision_type'] : '') == config('const.punishment_decision_code.lesson_article_stop') || old('decision_type', isset($punishment_params['decision_type']) && $punishment_params['decision_type'] ? $punishment_params['decision_type'] : '') == config('const.punishment_decision_code.buy_sell_stop')) ? '' : 'hide' }}" id="stop_period_area">
                                <div class="flex-space pl-5 pr-5">停止期間</div>
                                <div class="form_wrap icon_form type_arrow_bottom">
                                    <select name="stop_period" id="stop_period">
                                        <option value="">--</option>
                                        @foreach(config('const.stop_period') as $key=>$period)
                                            <option value="{{ $key }}" {{ old('stop_period', isset($punishment_params['stop_period']) && $punishment_params['stop_period'] ? $punishment_params['stop_period'] : '') == $key ? 'selected' : '' }}>{{ $period }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-space pl-5 pr-5">日</div>
                            </div>
                        </div>
                    </div>
                    @error('decision_type')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    @error('stop_period')
                    <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="control-date mt-20">
                        <div class="ft-bold">根拠通報（複数選択可）</div>
                        <div class="mt-5">
                            @foreach(config('const.basis') as $key=>$basis)
                                <div class="chk-rect mb-10">
                                    <input type="checkbox" value="{{ $key }}" name="basis[]" id="chk_basis_{{ $key }}" {{ !is_null(old('basis', isset($punishment_params['basis']) && $punishment_params['basis'] ? $punishment_params['basis'] : null)) && in_array($key, old('basis', isset($punishment_params['basis']) && $punishment_params['basis'] ? $punishment_params['basis'] : null)) ? 'checked' : '' }}>
                                    <label for="chk_basis_{{ $key }}">{{ $basis }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('basis')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="control-date mt-20">
                        <div class="ft-bold">理由（複数選択可）</div>
                        <div class="mt-5">
                            @foreach( $appClass as $key => $reason )
                                <div class="chk-rect mb-10">
                                    <input type="checkbox" value="{{ $reason->id }}" name="reason[]" id="chk_reason_{{ $reason->id }}" {{ !is_null(old('reason', isset($punishment_params['reason']) && $punishment_params['reason'] ? $punishment_params['reason'] : null)) && in_array($reason->id, old('reason', isset($punishment_params['reason']) && $punishment_params['reason'] ? $punishment_params['reason'] : null)) ? 'checked' : '' }}>
                                    <label for="chk_reason_{{ $reason->id }}">{{ $reason->name }}</label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @error('reason')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="wp-100 pos-relative mt-20">
                        {{--<button class="btn btn-orange wp-100 mb-10" name="btn_hiro" onclick="location.href='{{ route('admin.fraud_piro.create', ['user'=>$own_user->id]) }}'">通知文を表示する</button>--}}
                        <button class="btn btn-orange wp-100 mb-10" name="btn_hiro" type="submit">通知文を作成する</button>
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
                    $('.search-condition').addClass("hide");
                } else {
                    $(this).addClass('opened');
                    $(this).addClass('type_arrow_top');
                    $(this).removeClass('closed');
                    $(this).removeClass('type_arrow_bottom');
                    $('.search-condition').removeClass("hide");
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

            $(".report-detail").click(function() {
                let appeal_id = $(this).attr('data-id');
                console.log("appeal_id", appeal_id);
                if ($('#appeal_note_'+appeal_id).hasClass('hide')) {
                    $('#appeal_note_'+appeal_id).removeClass('hide');
                }

            });
        });
    </script>
@endsection
