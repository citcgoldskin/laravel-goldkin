@extends('admin.layouts.auth')

@section('content')
    <div id="contents">

        {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">通報者情報の詳細</label>
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
                    <span class="yellow_mark">{{ $own_user->caution_cnt ? $own_user->caution_cnt : 0 }}</span>
                    <span class="pink_mark">{{ $own_user->punishment_cnt ? $own_user->punishment_cnt : 0 }}</span>
                </div>
            </section>
            <section>
                {{ Form::open(["route"=>"admin.fraud_report.set_not_read", "method"=>"post", "name"=>"frm_read", "id"=>"frm_read"]) }}
                <div class="tabs search-result-area">
                    <div class="flex-space mb-10">
                        <div class="ft-bold ft-14">通報したユーザー</div>
                        <div>全 {{ count($appeal_users) }}件</div>
                    </div>
                    <table>
                        <tbody>
                        @if(count($appeal_users) > 0)
                            @foreach($appeal_users as $appeal_user)
                                @php
                                    $obj_user = $appeal_user->user;
                                @endphp
                                <input type="hidden" name="appeal_id[]" value="{{ $appeal_user->id }}">
                                <tr class="modal-syncer report-detail {{ $appeal_user->status != config('const.msg_state.read') ? 'unread' : '' }}" data-target="modal-sales-commission" data-id="{{ $appeal_user->id }}">
                                    <td class="td-detail"><a class="title_orange">詳細</a></td>
                                    <td>
                                        <div class="flex profile">
                                            <div class="ico ico-user">
                                                <img src="{{ $obj_user->avatar_path }}">
                                            </div>
                                            <div>
                                                <div class="pb-5 ft-bold">{{ $obj_user->user_name }}{{ "（".\App\Service\CommonService::getAge($obj_user->user_birthday)."）" }}{{ $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}</div>
                                                <div class="pb-5">理由：{{ $appeal_user->all_reason }}</div>
                                                <div>通報日：{{ \Carbon\Carbon::parse($appeal_user->reported_at)->format('Y.n.j') }}</div>
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
                </div>
                {{ Form::close() }}

                <div class="profile-area">
                    <div class="flex-space">
                        <div class="ft-bold ft-14 mb-10">通報理由</div>
                    </div>
                    <div class="flex-space">
                        <div class="pie">
                            <div class="pie_cover"><div class="pie_label">全<span style="font-size: 20px;">{{ \App\Service\AppealService::getAppealCountByCondition(['user_id'=>$own_user->id]) }}</span>件</div></div>
                        </div>
                        <div class="color-list">
                            @foreach($appeal_classes as $key=>$appeal_classe)
                                <div class="flex color-group">
                                    <div class="flex color-area">
                                        <span class="color-block" style="background: {{ config('const.appeal_class_color.'.($key+1)) }};"></span><div>{{ $appeal_classe->name }}</div>
                                    </div>
                                    <div class="ft-bold">{{ \App\Service\AppealService::getAppealCountByCondition(['user_id'=>$own_user->id, 'type'=>$appeal_classe->id]) }}件</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="profile-area">
                    <div class="flex-space">
                        <div class="ft-bold ft-14 mb-10">ぴろしきまる</div>
                    </div>
                    <table class="mb-10">
                        <tbody>
                        @if(count($punishment_history) > 0)
                            @foreach($punishment_history as $punishment)
                                <tr>
                                    <td class="td-detail"><a class="title_orange" href="{{ route('admin.fraud_piro.detail', ['punishment'=>$punishment->id]) }}">履歴</a></td>
                                    <td>
                                        <div class="flex profile" style="position: relative">
                                            <div>
                                                <div>{{ config('const.punishment_decision.'.$punishment->type) }}</div>
                                                @if($punishment->type == config('const.punishment_decision_code.lesson_article_stop') || $punishment->type == config('const.punishment_decision_code.buy_sell_stop'))
                                                    @if($punishment->stop_period)
                                                        <div class="mt-5">{{ $punishment->stop_period_date }}</div>
                                                    @endif
                                                @endif
                                                <div class="mt-5">処理日：{{ \Carbon\Carbon::parse($punishment->decided_at)->format('Y.n.j H:i') }}</div>
                                            </div>
                                            {{--<span class="pink_mark mark_history">{{ $own_user->punishment_cnt ? $own_user->punishment_cnt : 0 }}</span>--}}
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

                    <div class="wp-100 pos-relative mt-20">
                        <button class="btn btn-orange wp-100 mb-10" name="btn_hiro" onclick="location.href='{{ route('admin.fraud_piro.create', ['user'=>$own_user->id, 'page_from'=>'report']) }}'">ぴろしきまる</button>
                        <button class="btn btn-orange wp-100 mb-10 modal-syncer" id="btn_mark" name="btn_mark" data-target="modal-caution">要注意マークをつける</button>
                        <button class="btn btn-orange wp-100 mb-10" name="btn_read" id="btn_read">既読にする</button>
                    </div>
                </div>
                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.fraud_report.index') }}'" name="btn_back">戻る</button>
                </div>
            </section>

        </div><!-- /tabs -->

        {{--{{ Form::close() }}--}}

        {{ Form::open(["route"=>"admin.staff.caution", "method"=>"post", "name"=>"frm_caution", "id"=>"frm_caution"]) }}
            <input type="hidden" name="page_from" value="report">
            <input type="hidden" name="caution_user_id" value="{{ $own_user->id }}">
        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"modal-caution",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"このユーザーに要注意マークをつけますか?",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"キャンセル",
        ])

        <div class="modal-wrap coupon_modal">
            <div id="modal-sales-commission" class="modal-content ajax-modal-container">
            </div>

            {{--<div id="modal-warning" class="modal-content">
                <div class="modal_body">
                    <div class="close_btn_area">
                        <a  class="modal-close"><img src="{{asset('assets/user/img/x-mark.svg')}}" alt="閉じる"></a>
                    </div>
                    <div class="modal-content-style-1">
                        <div class="modal_inner">
                            <h2 class="modal_ttl">要注意マークをつけました。</h2>
                        </div>
                        <div class="button-area">
                            <div class="btn_base btn_ok">
                                <a  class="modal-close button-link">OK</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>

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
            right: 5px !important;
            top: 5px !important;
        }
        .pie {
            width: 100px; height: 100px;
            border-radius: 50%;
            /*background: conic-gradient(yellow 0% 25%, green 25% 50%, blue 50% 75%, #666 75% 100%);*/
            /*background: conic-gradient(yellow 0.09turn, green 0.09turn, blue 0.27turn, #666 0.27turn, #666 0.54turn, #000 0.54turn);*/
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
            width: 100%;
            text-align: center;
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

    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {
            let appeal_count_color_info = "{{ $appeal_count_color_info }}";
            appeal_count_color_info = appeal_count_color_info.replace(/&quot;/g,'"');
            appeal_count_color_info = JSON.parse(appeal_count_color_info);
            let pie_val = '';
            let percent = 0;
            Object.keys(appeal_count_color_info).map(i=>{
                if( i == 1) {
                    pie_val = 'conic-gradient(';
                    percent = 0;
                }
                if (i != 1) {
                    percent += appeal_count_color_info[i-1].percent;
                }
                pie_val += appeal_count_color_info[i].color + " " + percent + "% " + (appeal_count_color_info[i].percent + percent) + "%";
                if (i < Object.keys(appeal_count_color_info).length) {
                    pie_val += ','
                } else {
                    pie_val += ')';
                }
            });
            $('.pie').css('background', pie_val)
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
            $('#btn_mark').click(function(e) {
               e.preventDefault();
            });
            $('#btn_read').click(function(e) {
               e.preventDefault();
               $('#frm_read').submit();
            });

            $('.report-detail').click(function() {
                if ($(this).hasClass('unread')) {
                    $(this).removeClass('unread');
                }
                let appeal_id = $(this).attr('data-id');
                console.log("appeal_id", appeal_id);
                $.ajax({
                    type: "post",
                    url: '{{ route('admin.fraud_report.get_detail') }}',
                    data: {
                        _token: "{{ csrf_token() }}",
                        appeal_id: appeal_id,
                    },
                    dataType: 'json',
                    success: function (result) {
                        if(result.result_code == 'success') {
                            $('#modal-sales-commission').html('');
                            $('#modal-sales-commission').append(result.report_detail);

                        } else {
                        }
                    }
                });
            });
            $('.ajax-modal-container').on('click', '.modal-close', function() {
                $('.start-active').addClass('appear');
                $("#modal-sales-commission,#modal-overlay").fadeOut("fast", function () {

                    $('#modal-overlay').remove();

                });
            });

        });

        function modalConfirm(modal_id="") {
            // code
            if(modal_id == "modal-caution") {
                $('#frm_caution').submit();
            }
        }
    </script>
@endsection
