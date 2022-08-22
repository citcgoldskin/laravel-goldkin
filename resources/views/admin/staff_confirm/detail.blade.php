@extends('admin.layouts.auth')

@section('content')
    <div id="contents">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">本人確認</label>
            <section>
                {{ Form::open(["route"=>"admin.staff_confirm.do_alert_create", "method"=>"post", "name"=>"frm_confirm", "id"=>"frm_confirm"]) }}
                <input type="hidden" name="agree_type" id="agree_type" value="">
                <input type="hidden" name="person_confirm_id" id="person_confirm_id" value="{{ $person_confirm->pc_id }}">
                <input type="hidden" name="user_id" id="user_id" value="{{ $obj_user->id }}">
                <div class="profile-area">
                    <div class="flex-space">
                        <div class="ft-bold ft-14 mb-10">申請情報</div>
                        <a class="title_orange" href="{{ route('admin.staff.detail', ['staff'=>$obj_user->id]) }}">詳細</a>
                    </div>
                    <table>
                        <tbody>
                            <tr>
                                <td class="wp-50 td-label">氏名</td>
                                <td class="wp-50">{{ $obj_user->name }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">フリガナ</td>
                                <td>{{ $obj_user->user_name_kana }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">性別</td>
                                <td>{{ $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">郵便番号</td>
                                <td>{{ $obj_user->user_mail }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">都道府県</td>
                                <td>{{ $obj_user->user_area_id ? \App\Service\AreaService::getOneArea($obj_user->user_area_id)['area_name'] : '' }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">市区町村</td>
                                <td>{{ $obj_user->user_county }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">町番地</td>
                                <td>{{ $obj_user->user_village }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">マンション名・部屋番号</td>
                                <td>{{ $obj_user->user_mansyonn }}</td>
                            </tr>
                            <tr>
                                <td class="td-label">生年月日</td>
                                <td>{{ $obj_user->user_birthday }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-20 mb-5 ft-14 ft-bold">本人確認に使用する書類</div>
                    <div class="inner_box">
                        <div class="white_box">
                            <h3>{{ config('const.person_confirm_type.'.$person_confirm->pc_confirm_type) }}</h3>
                            <ul>
                                <li><img src="{{ $person_confirm->confirm_card_image }}" alt="{{ config('const.person_confirm_type.'.$person_confirm->pc_confirm_type) }}">
                                <li>
                                    <p>必要な内容</p>
                                    <p>1.氏名</p>
                                    <p>2.生年月日</p>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="mt-20 mb-5 ft-14 ft-bold">アップロードされた画像</div>
                    @php
                        $exist_upload_image = $person_confirm->pc_confirm_doc && Storage::disk('public')->exists("credit/{$person_confirm->pc_confirm_doc}");
                    @endphp
                    <div class="upload-img">
                        @if($exist_upload_image)
                            <img src="{{ $exist_upload_image ? asset('storage/credit').'/'.$person_confirm->pc_confirm_doc : '' }}">
                        @else
                            <p>アップロードされた画像が存在しません。</p>
                        @endif
                    </div>

                    <div class="mt-20 mb-5 ft-14 ft-bold">チェック項目</div>
                    <div class="chk-area">
                        @foreach(config('const.person_confirm_agree_type') as $key=>$val)
                            <div class="chk-rect">
                                <input type="checkbox" class="chk_agree" value="{{ $key }}" name="chk-agree[]" id="chk-agree-{{ $key }}" {{ isset($condition['agree_type']) && $condition['agree_type'] == config('const.person_confirm_agree_category.agree') ? 'checked' : '' }}>
                                <label for="chk-agree-{{ $key }}">{{ $val }}</label>
                            </div>
                        @endforeach
                    </div>

                    <button id="btn_agree" class="btn btn-orange wp-100 mb-10 mt-20 {{ isset($condition['agree_type']) && $condition['agree_type'] == config('const.person_confirm_agree_category.agree') && $exist_upload_image ? '' : 'action-orange-none' }}">承認して通知文を作成する</button>

                    <div class="mt-20 mb-5 ft-14 ft-bold">確認していない理由（複数選択可）</div>
                    <div class="chk-area">
                        @foreach(config('const.person_confirm_disagree_type') as $key=>$val)
                            <div class="chk-rect">
                                <input type="checkbox" class="chk_disagree" value="{{ $key }}" name="chk-disagree[]" id="chk-disagree-{{ $key }}" {{ isset($condition['agree_type']) && $condition['agree_type'] == config('const.person_confirm_agree_category.disagree') && isset($condition['chk-disagree']) && in_array($key, $condition['chk-disagree']) ? 'checked' : '' }}>
                                <label for="chk-disagree-{{ $key }}">{{ $val }}</label>
                            </div>
                        @endforeach
                    </div>
                    <button id="btn_disagree" class="btn btn-orange wp-100 mb-10 mt-20 {{ isset($condition['agree_type']) && $condition['agree_type'] == config('const.person_confirm_agree_category.disagree') ? '' : 'action-orange-none' }}">承認しないで通知文を作成する</button>

                </div>
                {{ Form::close() }}

                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.staff_confirm.index') }}'" name="btn_back">戻る</button>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .white_box {
            width: 100%;
            margin: 0 auto;
            background: #fff;
            padding: 15px;
            position: relative;
            border-radius: 10px;
            filter: drop-shadow(0px 5px 15px rgba(27,28,32,0.1));
        }
        .white_box h3 {
            border-bottom: 1px dotted rgba(41,39,36,0.1);
            position: relative;
            padding: 0px 0px 10px;
        }
        .white_box li:first-child img {
            width: auto;
            max-height: 56px;
        }
        .white_box li:first-child {
            width: 60px;
            text-align: center;
        }
        .white_box li {
            box-sizing: border-box;
        }
        .white_box li:last-child {
            padding-left: 15px;
        }
        .white_box li:last-child > p {
            font-size: 11px;
            padding: 2px 0px;
        }
        .white_box li:last-child > p:nth-of-type(1) {
            font-size: 12px;
        }

        .white_box ul {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: flex-start;
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

            $('#btn_agree').click(function(e) {
                e.preventDefault();
                $('.chk_disagree').each(function(){
                    $(this).prop('checked', false);
                });
                $('#agree_type').val("{{ config('const.person_confirm_agree_category.agree') }}");
                $('#frm_confirm').attr('action', "{{ route("admin.staff_confirm.do_alert_create") }}").submit();
            });

            $('#btn_disagree').click(function(e) {
                e.preventDefault();
                $('.chk_agree').each(function(){
                    $(this).prop('checked', false);
                });
                $('#agree_type').val("{{ config('const.person_confirm_agree_category.disagree') }}");
                $('#frm_confirm').attr('action', "{{ route("admin.staff_confirm.do_alert_create") }}").submit();
            });

            $('.chk_agree').change(function(e) {
                let all_checked = true;
                $('.chk_agree').each(function(){
                    if (!$(this).prop('checked')) {
                        all_checked = false;
                    }
                });
                if (all_checked) {
                    @if($exist_upload_image)
                        if($('#btn_agree').hasClass('action-orange-none')) {
                            $('#btn_agree').removeClass('action-orange-none')
                        }
                    @endif
                } else {
                    if(!$('#btn_agree').hasClass('action-orange-none')) {
                        $('#btn_agree').addClass('action-orange-none')
                    }
                }
            });

            $('.chk_disagree').change(function(e) {
                let one_checked = false;
                $('.chk_disagree').each(function(){
                    if ($(this).prop('checked')) {
                        one_checked = true;
                    }
                });
                if (one_checked) {
                    if($('#btn_disagree').hasClass('action-orange-none')) {
                        $('#btn_disagree').removeClass('action-orange-none')
                    }
                } else {
                    if(!$('#btn_disagree').hasClass('action-orange-none')) {
                        $('#btn_disagree').addClass('action-orange-none')
                    }
                }
            });

        });
    </script>
@endsection
