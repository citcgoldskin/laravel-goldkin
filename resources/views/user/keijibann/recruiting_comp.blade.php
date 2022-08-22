@include('user.layouts.app')

<div class="modal">
    <!--main_visual A-21-->
    <div id="completion_wrap" class="no_modal">
        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    募集を<br>
                    投稿しました
                </h2>
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
