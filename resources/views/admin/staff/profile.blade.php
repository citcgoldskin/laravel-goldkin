@extends('admin.layouts.auth')

@section('content')
    <div id="contents">

        {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">プロファイル</label>
            <section>
                <div class="tabs profile-area flex">
                    <div class="wp-35">
                        <div>
                            <img src="{{ $obj_user->avatar_path }}">
                        </div>
                        <div class="mt-10">
                            <button id="btn_pirosikimaru" class="btn wp-100" name="btn_pirosikimaru" onclick="location.href='{{ route('admin.fraud_piro.create', ['user'=>$obj_user->id, 'page_from'=>'profile']) }}'">ぴろしきまる</button>
                        </div>
                        <div class="mt-5">
                            <button id="btn_mark" class="btn wp-100 modal-syncer" name="btn_mark" data-target="modal-caution">要注意マーク</button>
                        </div>
                    </div>
                    <div class="wp-65 pl-10">
                        {{--<div class="mb-10">
                            <div class="profile-label pb-5">購入実績</div>
                            <div>xxx</div>
                        </div>--}}
                        <div class="mb-10">
                            <div class="profile-label pb-5">ID</div>
                            <div>{{ $obj_user->user_no }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">会員種別</div>
                            <div>{{ config('const.user_type_label.'.$obj_user->user_is_senpai) }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">氏名</div>
                            <div>{{ $obj_user->user_name }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">フリガナ</div>
                            <div>{{ $obj_user->user_name_kana }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">ニックネーム</div>
                            <div>{{ $obj_user->name }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">年代</div>
                            <div>{{ $obj_user->age }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">性別</div>
                            <div>{{ $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">メールアドレス</div>
                            <div>{{ $obj_user->email }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">郵便番号</div>
                            <div>{{ $obj_user->user_mail }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">都道府県</div>
                            <div>{{ $obj_user->user_area_id ? \App\Service\AreaService::getOneArea($obj_user->user_area_id)['area_name'] : '' }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">市区町村</div>
                            <div>{{ $obj_user->user_county }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">町番地</div>
                            <div>{{ $obj_user->user_village }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">マンション名・部屋番号</div>
                            <div>{{ $obj_user->user_mansyonn }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">電話番号</div>
                            <div>{{ $obj_user->user_phone }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">生年月日</div>
                            <div>{{ $obj_user->user_birthday }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">登録地</div>
                            <div>{{ $obj_user->user_area_name }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">登録日</div>
                            <div>{{ \Carbon\Carbon::parse($obj_user->created_at)->format('Y-m-d') }}</div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">通報</div>
                            @php
                                $report_cnt = \App\Service\AppealService::getAppealCountByCondition(['user_id'=>$obj_user->id]);
                            @endphp
                            <div><a href="{{ route('admin.fraud_report.detail', ['user'=>$obj_user->id]) }}" class="title_orange" id="report_detail">{{ $report_cnt }}件</a></div>
                        </div>
                        <div class="mb-10">
                            <div class="profile-label pb-5">プロファイル</div>
                            <div>{{ $obj_user->user_intro }}</div>
                        </div>
                    </div>
                </div>
            </section>
            <section>
                <div class="profile-area">
                    <div class="ft-bold ft-14 mb-10">評価</div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="th-title" colspan="2">
                                    <div class="tb-title">
                                        <div>センパイの評価</div>
                                        <div>{{ \App\Service\UserService::getEvalutionCountByType($obj_user->id, config('const.staff_type.senpai')) }}件 / {{ \App\Service\UserService::getEvalutionValueCountByType($obj_user->id, config('const.staff_type.senpai')) }}点</div>
                                    </div>
                                </td>
                            </tr>
                            @foreach($senpai_eval_types as $k => $v)
                                <tr>
                                    <td class="question-label">{{$v['et_question']}}</td>
                                    <td class="mark-val">{{ (int)\App\Service\UserService::getEvalutionPercentByType($obj_user->id, config('const.staff_type.senpai'), $v['et_id']) }}％</td>
                                </tr>
                            @endforeach
                            </tr>
                        </tbody>
                    </table>

                    <table class="mt-20">
                        <tbody>
                        <tr>
                            <td class="th-title" colspan="2">
                                <div class="tb-title">
                                    <div>コウハイの評価</div>
                                    <div>{{ \App\Service\UserService::getEvalutionCountByType($obj_user->id, config('const.staff_type.kouhai')) }}件 / {{ \App\Service\UserService::getEvalutionValueCountByType($obj_user->id, config('const.staff_type.kouhai')) }}点</div>
                                </div>
                            </td>
                        </tr>
                        @foreach($kouhai_eval_types as $k => $v)
                            <tr>
                                <td class="question-label">{{$v['et_question']}}</td>
                                <td class="mark-val">{{ (int)\App\Service\UserService::getEvalutionPercentByType($obj_user->id, config('const.staff_type.kouhai'), $v['et_id']) }}％</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    {{--<div class="mt-10">
                        <button id="btn_back" class="btn btn-back wp-100" onclick="{{ route('admin.top.index') }}" name="btn_back">戻る</button>
                    </div>--}}
                </div>
            </section>

            <section>
                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.staff.index') }}'" name="btn_back">戻る</button>
                </div>
            </section>

            {{ Form::open(["route"=>"admin.staff.caution", "method"=>"post", "name"=>"frm_caution", "id"=>"frm_caution"]) }}
            <input type="hidden" name="page_from" value="profile">
            <input type="hidden" name="caution_user_id" value="{{ $obj_user->id }}">
            {{ Form::close() }}

            @include('admin.layouts.modal-layout', [
                'modal_id'=>"modal-caution",
                'modal_type'=>config('const.modal_type.confirm'),
                'modal_title'=>"このユーザーに要注意マークをつけますか?",
                'modal_confrim_btn'=>"OK",
                'modal_confrim_cancel'=>"キャンセル",
            ])

        </div><!-- /tabs -->

        {{--{{ Form::close() }}--}}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
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
        .profile-label {
            font-weight: bold;
        }
        .tb-title {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
        }
        .th-title {
            background: #FAF8F4;
        }
        .question-label {
            width: 80%;
            border-right: none;
        }
        .mark-val {
            width: 20%;
            font-weight: bold;
            border-left: none;
            text-align: right;
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

            $('#report_detail').click(function(e){
                e.preventDefault();
                @if($report_cnt > 0)
                    location.href = "{{ route('admin.fraud_report.detail', ['user'=>$obj_user->id]) }}";
                @endif
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
