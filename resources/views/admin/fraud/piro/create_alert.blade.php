@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.fraud_piro.confirm_post", "method"=>"post", "name"=>"frm_confirm", "id"=>"frm_confirm"]) }}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">通知文作成</label>
            <section>
                <div class="tabs search-result-area">

                    <div class="control-date mt-20">
                        <div class="ft-bold">表題</div>
                        @php
                            $default_title = "サービスのご利用に関するお知らせ";
                        @endphp
                        <div class="mt-5">
                            <input type="text" name="alert_title" id="alert_title" value="{{ old('alert_title', isset($punishment_params['alert_title']) && $punishment_params['alert_title'] ? $punishment_params['alert_title'] : $default_title) }}">
                        </div>
                    </div>
                    @error('alert_title')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="control-date mt-20">
                        <div class="ft-bold">通知文</div>
                        @php
                            $default_content = "センパイをご利用いただき誠にありがとうございます。\nお客様のサービス利用で禁止事項に抵触する行為が確認できたため、以下の措置をとらせていただきます。\n\nアカウント凍結\n\n弊社の措置に関しましては利用規約をご確認ください。\n\n利用規約に記載のとおり運営の判断により行った措置に基づき利用者に生じた一切損害について責任を負いません。\n\n";
                        @endphp
                        <div class="mt-5">
                            <textarea name="alert_text" id="alert_text">{{ old('alert_text', isset($punishment_params['alert_text']) && $punishment_params['alert_text'] ? $punishment_params['alert_text'] : $default_content) }}</textarea>
                        </div>
                    </div>
                    @error('alert_text')
                        <span class="error_text">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                    <div class="wp-100 pos-relative mt-20">
                        <button class="btn btn-orange wp-100 mb-10" name="btn_confirm_alert" type="submit">確認画面へ</button>
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
        #alert_text {
            min-height: 300px;
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
    <script>
        $(document).ready(function() {
            $('#btn_back').click(function(e) {
                e.preventDefault();
                location.href="{{ route('admin.fraud_piro.create', ['user'=>$obj_user->id]) }}";
            });
        });
    </script>
@endsection
