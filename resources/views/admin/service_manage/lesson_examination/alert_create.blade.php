@extends('admin.layouts.auth')
@section('title', '通知文作成')

@section('content')
    <div id="contents">
        @if(isset($condition['agree_type']) && $condition['agree_type'] == config('const.agree_flag.disagree'))
            @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_examination.disagree', ['lesson'=>$condition['lesson_id']])])
        @else
            @include('admin.layouts.header_under', ['action_url'=>route('admin.lesson_examination.detail', ['lesson_id'=>$condition['lesson_id']])])
        @endif

        <div class="tabs form_page">
            <section>
                {{ Form::open(["route"=>"admin.lesson_examination.do_alert_confirm", "method"=>"post", "name"=>"frm_confirm", "id"=>"frm_confirm"]) }}
                <div class="profile-area">
                    <div class="inner_box for-warning">
                        <h3>タイトル</h3>
                        @php
                            $default_title = config('msg_template.service_manage.lesson_agree.alert_title');
                            if (isset($condition['agree_type']) && $condition['agree_type'] == config('const.agree_flag.disagree')) {
                                $default_title = config('msg_template.service_manage.lesson_disagree.alert_title');
                            }
                        @endphp
                        <div class="input-text2 lesson_ttl_textarea">
                            <input type="text" id="alert_title" name="alert_title" value="{{ old('alert_title', isset($condition['alert_title']) && $condition['alert_title'] ? $condition['alert_title'] : $default_title) }}">
                        </div>
                        @error('alert_title')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div>
                        <h3>本文</h3>
                        @php
                            $default_content = config('msg_template.service_manage.lesson_agree.alert_text');
                            if (isset($condition['agree_type']) && $condition['agree_type'] == config('const.agree_flag.disagree')) {
                                $default_content = config('msg_template.service_manage.lesson_disagree.alert_text');
                                if (isset($condition['lesson_image'])) {
                                    $default_content .= "・レッスンイメージ\n";
                                    $default_content .= $condition['reason_lesson_image']."\n\n";
                                }
                                if (isset($condition['lesson_title'])) {
                                    $default_content .= "・レッスンタイトル\n";
                                    $default_content .= $condition['reason_lesson_title']."\n\n";
                                }
                                if (isset($condition['lesson_service_details'])) {
                                    $default_content .= "・サービス詳細\n";
                                    $default_content .= $condition['reason_lesson_service_details']."\n\n";
                                }
                                if (isset($condition['lesson_other_details'])) {
                                    $default_content .= "・持ち物・その他の費用\n";
                                    $default_content .= $condition['reason_lesson_other_details']."\n\n";
                                }
                                if (isset($condition['lesson_buy_and_attentions'])) {
                                    $default_content .= "・購入にあたってのお願い・注意事項\n";
                                    $default_content .= $condition['reason_lesson_buy_and_attentions']."\n\n";
                                }
                                if (isset($condition['lesson_other'])) {
                                    $default_content .= "・その他当社が不適切と判断した点\n";
                                    $default_content .= $condition['reason_lesson_other']."\n\n";
                                }
                            }
                            $default_content = str_replace(':lesson_title', $condition['lesson_content_title'], $default_content);
                        @endphp
                        <div class="input-text2 lesson_ttl_textarea">
                            <textarea type="text" id="alert_text" name="alert_text">{!! old('alert_text', isset($condition['alert_text']) && $condition['alert_text'] ? $condition['alert_text'] : $default_content) !!}</textarea>
                        </div>
                        <div class="reason">
                            {{--@if(isset($condition['lesson_image']))
                                <div>
                                    <div>レッスンイメージ</div>
                                    <div>
                                        <textarea name="reason_lesson_image">{{$condition['reason_lesson_image']}}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if(isset($condition['lesson_title']))
                                <div>
                                    <div>レッスンタイトル</div>
                                    <div>
                                        <textarea>{{$condition['reason_lesson_title']}}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if(isset($condition['lesson_service_details']))
                                <div>
                                    <div>サービス詳細</div>
                                    <div>
                                        <textarea>{{$condition['reason_lesson_service_details']}}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if(isset($condition['lesson_other_details']))
                                <div>
                                    <div>持ち物・その他の費用</div>
                                    <div>
                                        <textarea>{{$condition['reason_lesson_other_details']}}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if(isset($condition['lesson_buy_and_attentions']))
                                <div>
                                    <div>購入にあたってのお願い・注意事項</div>
                                    <div>
                                        <textarea>{{$condition['reason_lesson_buy_and_attentions']}}</textarea>
                                    </div>
                                </div>
                            @endif
                            @if(isset($condition['lesson_other']))
                                <div>
                                    <div>その他当社が不適切と判断した点</div>
                                    <div>
                                        <textarea>{{$condition['reason_lesson_other']}}</textarea>
                                    </div>
                                </div>
                            @endif--}}
                        </div>
                    </div>
                    @error('alert_text')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    {{--<button id="btn_confirm" type="submit" class="btn btn-orange wp-100 mb-10 mt-20">確認する</button>--}}
                    <div class="btn mtb">
                        <button type="submit" onclick="location.href=''">確認する</button>
                    </div>
                </div>
                {{ Form::close() }}
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
    <script>
        $(document).ready(function() {
        });
    </script>
@endsection
