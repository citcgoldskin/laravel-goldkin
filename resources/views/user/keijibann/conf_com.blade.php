@extends('user.layouts.app')
@include('user.layouts.header')

<div class="modal">

    <!--main_visual A-21-->
    <div id="completion_wrap" class="no_modal">
        <div class="modal_body completion">
            <div class="modal_inner">

                <div class="toukou_kanryou">
                    <p><img src="{{App\Service\CommonService::getUserAvatarUrl($params['user_avatar'])}}" alt="先生のアイコン"></p>
                    <p>
                        {{$params['user_name']}}<small>さんの</small>
                    </p>
                </div>

                <h2 class="modal_ttl">
                    投稿への提案が<br>
                    完了しました
                </h2>

            </div>
            <div class="modal_txt modal_txt_bottom text-l">
                <p style="text-align:center">
                    提案内容の変更や削除は、掲示板＞提案管理　から行う事ができます。<br>
                    また、提案が購入されるとトークルームで当日の相談ができるようになります。
                </p>
            </div>
        </div>


        <div class="button-area">
            <div class="btn_base btn_white shadow">
                <a href="{{route('keijibann.list')}}">OK</a>
            </div>
        </div>


    </div>
    <!--main_visual A-21 end-->
</div>
