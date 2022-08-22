@extends('admin.layouts.auth')

@section('content')
    <div id="contents">

        {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">ブロック情報の詳細</label>
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
                {{ Form::open(["route"=>"admin.fraud_block.set_not_read", "method"=>"post", "name"=>"frm_read", "id"=>"frm_read"]) }}
                <div class="tabs search-result-area">
                    <div class="flex-space mb-10">
                        <div class="ft-bold ft-14">ブロックしたユーザー</div>
                        <div>全 {{ count($block_users) }}件</div>
                    </div>
                    <table>
                        <tbody>
                        @if(count($block_users) > 0)
                            @foreach($block_users as $block_user)
                                @php
                                    $obj_user = $block_user->user;
                                @endphp
                                <input type="hidden" name="block_id[]" value="{{ $block_user->bl_id }}">
                            <tr class="report-detail">
                                <td>
                                    <div class="flex profile">
                                        <div class="ico ico-user">
                                            <img src="{{ $obj_user->avatar_path }}">
                                        </div>
                                        <div>
                                            <div class="pb-5 ft-bold">{{ $obj_user->user_name }}{{ "（".\App\Service\CommonService::getAge($obj_user->user_birthday)."）" }}</div>
                                            <div class="pb-5">{{ $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}</div>
                                            <div>{{ \Carbon\Carbon::parse($block_user->reported_at)->format('Y.n.j') }}</div>
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
                    <div class="wp-100 pos-relative">
                        <button class="btn btn-orange wp-100 mb-10" name="btn_hiro" onclick="location.href='{{ route('admin.fraud_piro.create', ['user'=>$own_user->id, 'page_from'=>'block']) }}'">ぴろしきまる</button>
                        <button class="btn btn-orange wp-100 mb-10 modal-syncer" id="btn_mark" name="btn_mark" data-target="modal-caution">要注意マークをつける</button>
                        <button class="btn btn-orange wp-100 mb-10" name="btn_read" id="btn_read">既読にする</button>
                    </div>
                </div>
                <div class="mt-10">
                    <button id="btn_back" class="btn btn-back wp-100" onclick="location.href='{{ route('admin.fraud_block.index') }}'" name="btn_back">戻る</button>
                </div>
            </section>

        </div><!-- /tabs -->

        {{--{{ Form::close() }}--}}

        {{ Form::open(["route"=>"admin.staff.caution", "method"=>"post", "name"=>"frm_caution", "id"=>"frm_caution"]) }}
            <input type="hidden" name="page_from" value="block">
            <input type="hidden" name="caution_user_id" value="{{ $own_user->id }}">
        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"modal-caution",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"このユーザーに要注意マークをつけますか?",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"キャンセル",
        ])

    </div>
@endsection
@section('page_css')
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

    </style>
@endsection
@section('page_js')
    <script>
        $(document).ready(function() {
            // 既読にする
            $('#btn_read').click(function(e) {
                e.preventDefault();
                $('#frm_read').submit();
            });

            // 要注意マークをつける
            $('#btn_mark').click(function(e) {
                e.preventDefault();
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
