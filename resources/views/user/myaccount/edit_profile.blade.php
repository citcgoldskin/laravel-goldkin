@extends('user.layouts.app')
@section('title', 'プロフィール情報編集')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

    <div id="contents">
        @php
            $avatar_file = "";
            $file_name = public_path('storage/avatar/'.$user_info['user_avatar']);
            if (file_exists($file_name)) {
                $avatar_file = asset("storage/avatar/".$user_info['user_avatar']);
            } else {
                $avatar_file = asset('assets/user/img/icon_02.svg');
            }
        @endphp
        {{ Form::open(["name"=>"form1", "id"=>"form1", "files"=>true]) }}
            <section>
                <div class="profile_img">
                    <p class="user_avatar"><img src="{{ $avatar_file }}" alt="" class="w85"></p>
                    <p><a id="change_user_avatar">プロフィール画像を変更</a></p>
                    <input type="file" class="camera_mark" id="user_avatar" name="user_avatar" onchange="setPreviewPic(this);">
                </div>
            </section>
            <section>
                <ul class="form_area">
                    <li>
                        <h3 class="must">ニックネーム</h3>
                        <div class="form_wrap shadow-glay for-warning">
                            <input type="text" value="{{ $user_info['name'] }}" id="name" name="name" placeholder="例）センパイ太郎">
                            <p class="warning"></p>
                        </div>
                    </li>

                    <li>
                        <h3 class="must">登録地</h3>
                        <div class="form_wrap icon_form type_arrow_right shadow-glay for-warning">
                            <button type="button" class="form_btn" id="area_button" onclick="showSelArea();">{{ \App\Service\AreaService::getOneAreaFullName($user_info['user_area_id']) }}</button>
                            <input type="hidden" id="area_id" name="area_id" value="{{ $user_info['user_area_id'] }}">
                            <p class="warning"></p>
                        </div>
                    </li>

                    <li>
                        <h3>年代</h3>
                        <div class="form_txt">
                            <p>{{ isset($user_info['age']) ? $user_info['age'] : '' }}</p>
                        </div>
                    </li>

                    <li>
                        <h3>性別</h3>
                        <div class="form_txt">
                            <p>
                                @if ( $user_info['user_sex'] == config('const.sex.woman') )
                                    女性
                                @elseif ( $user_info['user_sex'] == config('const.sex.man') )
                                    男性
                                @else
                                    {{--指定なし--}}
                                @endif
                            </p>
                        </div>
                    </li>

                    <li>
                        <h3>自己紹介<small>（1,000字以内）</small></h3>
                        <div class="input-text2">
                            <textarea placeholder="" cols="50" rows="10" class="count-text shadow-glay" maxlength="1000" name="user_intro" id="user_intro"></textarea>
                            <p class="max_length"><span id="num">0</span>／1,000</p>
                        </div>
                    </li>

                </ul>
            </section>
            <div class="white-bk">
                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <a class="ajax_submit">プロフィールを保存する</a>
                    </div>
                </div>

            </div>

        {{ Form::close() }}

    </div><!-- /contents-->

    <div id="area_contents">
        <section>
            <!-- ************************************************************ -->
            @foreach($prefectures as $key => $value)
                <div class="board_box set-list_wrap">
                    <input id="pref-{{$key + 1}}" name="acd" class="acd-check" type="checkbox">
                    <label class="acd-label" for="pref-{{$key + 1}}">{{ $value['region'] }}</label>
                    <div class="acd-content set-list_content">
                        <ul>
                            @foreach($value['child'] as $k => $v)
                                <li>
                                    <a href="#" onclick="selArea('{{ $k}}', '{{ $v }}')">
                                        <span>{{ $v }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endforeach

        </section>
    </div>


    <!-- モーダル部分 *********************************************************** -->
    <input type="hidden" class="ajax-modal-syncer" data-target="#modal-mail_henkou" id="modal_result">
    <div class="modal-wrap completion_wrap">
        <div id="modal-mail_henkou" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">

                    <h2 class="modal_ttl">
                        プロフィールを<br>
                        保存しました
                    </h2>

                </div>

                <div class="button-area type_under">
                    <div class="btn_base btn_gray-line effect_none">
                        <a href="{{ route('user.myaccount.index') }}">OK</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- モーダル部分 / ここまで ************************************************* -->



    <footer>

        @include('user.layouts.fnavi')

    </footer>

@endsection

@section('page_js')
    <script src="{{ asset('assets/user/js/validate.js') }}"></script>
    <script>

        $(document).ready(function () {
            var text = "{{ $user_info['user_intro'] }}";
            $("#num").text(text.length);
            $('#user_intro').text(text);

            var html = '<img src="'+'{{$avatar_file}}'+'" class="w85">';
            $('.user_avatar').html(html);
        })

        $(document).on('keydown', '#form1', function (e) {
            if (e.keyCode == 13) { // press enter key
                e.preventDefault();
                ajaxSubmit($("#form1").get(0));
                    }
        })
        $('.ajax_submit').click(function () {
            ajaxSubmit($("#form1").get(0));
        });

        File.prototype.convertToBase64 = function(callback){
            var reader = new FileReader();
            reader.onloadend = function (e) {
                callback(e.target.result, e.target.error);
            };
            reader.readAsDataURL(this);
        };

        $('#change_user_avatar').click(function () {
            $('#user_avatar').click();
        })

        function setPreviewPic(obj) {
            var selectedFile = obj.files[0];
            selectedFile.convertToBase64(function(base64){
                var html = '<img src="'+base64+'" class="w85">';
                $('.user_avatar').html(html);
            })
        }

        function ajaxSubmit(objForm) {
            var postData = new FormData(objForm);
            postData.append("_token", "{{csrf_token()}}");

            $.ajax({
                type: "post",
                url: '{{ route('user.myaccount.edit_profile') }}',
                data: postData,
                dataType: 'json',
                contentType: false,
                processData: false,
                success: function (result) {
                    if ( result.result_code == 'success' ) {
                        showAjaxModal($('#modal_result'));
                    } else {
                        if ( result.res.name != undefined ) {
                            addError($('#name'), result.res.name);
                            addError($('#area_id'), result.res.area_id);
                        }
                    }
                },
            });
        }

        function showSelArea() {
            $('#contents').hide();
            $('#area_contents').show();
            $('.header_area h1').html("都道府県を選択");
        }

        function hideSelArea() {
            $('#contents').show();
            $('#area_contents').hide();
            $('.header_area h1').html("プロフィール情報編集");
        }

        function selArea(area_id, area_name) {
            $('#area_button').html(area_name);
            $('#area_id').val(area_id);

            hideSelArea();
        }

    </script>
@endsection
