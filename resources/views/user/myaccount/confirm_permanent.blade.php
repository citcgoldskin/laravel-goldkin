@extends('user.layouts.app')
@section('title', '特別永住者証明書の提出')

@section('content')
@include('user.layouts.header_under')
<div id="contents">

    {{ Form::open(["route"=>"user.myaccount.confirm_drive_put", "method"=>"put", "name"=>"form1", "id"=>"form1", "files"=>true ]) }}
        <section id="coupon_msg">

            <h3>
                特別永住者証明書の画像の提出については<br>
                下記の点に注意してください。
            </h3>

        </section>

        <section id="example">

            <div class="inner_box">
                <div class="white_box">
                    <h3>○ 正しい例</h3>
                    <ul class="ex_correct">
                        <li><img src="{{ asset('assets/user/img/coupon/permanent/img_correct.png') }}" alt="正しい例"></li>
                        <li>
                            <p>画像が鮮明。</p>
                            <p>氏名/生年月日/発行元/有効期限/書面の名称が写っている。</p>
                            <p>プロフィールと内容が同一である。</p>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="inner_box">
                <div class="white_box">
                    <h3>× 誤った例</h3>
                    <ul class="ex_error">
                        <li><img src="{{ asset('assets/user/img/coupon/permanent/img_error.png') }}" alt="誤った例"></li>
                        <li>
                            <p>画像が不鮮明。</p>
                            <p>必要項目が写っていない。</p>
                            <p>有効期限が切れている。</p>
                        </li>
                    </ul>
                </div>
            </div>

        </section>

        <section id="preparation">

            <div class="inner_box">
                <h3>画像に必要な免許証の項目</h3>
                <p class="base_txt">
                    書面の名称／発行元の名称／氏名／生年月日／有効期限
                </p>
            </div>

            <div class="inner_box">
                <h3>画像アップロード（表面のみお願いします）</h3>
                <div class="file_btn_area">
                    <div class="btn_base btn_gray-line">
                        <label>
                            <input type="file" name="image_file" id="image_file">ファイルを選択
                        </label>
                    </div>
                    <span id="file_name">選択されていません</span>
                    <input type="hidden" name="file_name">
                    </ul>
                </div>
            </div>
        </section>

        <section>
            <div class="button-area w100">
                <div class="btn_base btn_orange shadow">
                    <button type="submit" class="btn-send">提出する</button>
                </div>
            </div>
        </section>

    {{ Form::close() }}
</div><!-- /contents -->
<script>
    $(document).ready(function () {
        $("#image_file").on('change', function(e){
            var tgt = e.target || window.event.srcElement,
                files = tgt.files;
            var form_data = new FormData();
            var totalfiles = files.length;
            $("#file_name").html(files[0]['name']);
            $('input[name="file_name"]').val(files[0]['name']);
            form_data.append("image_file", files[0]);
        });
    });
</script>

@endsection

