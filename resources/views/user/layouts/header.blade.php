<!-- タイトル部分にURLの末尾を取得して表示 -->
<?php $uri = rtrim($_SERVER["REQUEST_URI"], '/'); ?>
<?php $uri = substr($uri, strrpos($uri, '/') + 1); ?>

<!--facicon-->
<link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
<!--<link rel="apple-touch-icon-precomposed" href="/img/icon.png">-->
<!--facicon end-->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/reset.css') }}">

<!-- 全ページ共通 -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/common.css') }}?{{ \Carbon\Carbon::now()->format('YmdHis') }}">
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/app.css') }}">

<!-- 全ページ共通 -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/custom.css') }}">

<!-- 各ページ共通 -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/design.css') }}?{{ \Carbon\Carbon::now()->format('YmdHis') }}">

<!-- ページ個別（1ページのみ） -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/page.css') }}">

<!-- タブレット・スマホ -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/responsive.css') }}">

<!-- スライダー -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/swiper_customize.css') }}">

<!-- モーダル -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/modal.css') }}?{{ \Carbon\Carbon::now()->format('YmdHis') }}">

<!-- トークルーム -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/talkroom.css') }}">

<!-- クーポン（アルファ画面） -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/coupon.css') }}">

<!-- よくある質問（ベータ画面） -->
<link rel="stylesheet" media="all" href="{{ asset('assets/user/css/faq.css') }}">
<link rel="stylesheet" media="all" href="{{ asset('assets/vendor/select2/css/select2.min.css') }}">

<!-- Customize Styles -->
@yield('page_css')


<script src="{{ asset('assets/user/js/jquery/jquery-1.11.0.min.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery/jquery-3.1.0.js') }}"></script>
<script src="{{ asset('assets/user/js/swiper-4.3.3.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/user/js/jquery/jquery-ui-1.12.0.min.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery.datetimepicker.full.min.js') }}"></script>
<script src="{{ asset('assets/user/js/jquery/jquery-1.5.1.js') }}"></script>

<!-- スライドショー -->
<script src="{{ asset('assets/user/js/jquery/jquery-3.4.1.min.js') }}"></script>
<script src="{{ asset('assets/user/js/swiper-4.3.3.min.js') }}"></script>
<link href="{{ asset('assets/user/css/swiper.min.css') }}" rel="stylesheet" type="text/css">
<script src="{{ asset('assets/user/js/slider.js') }}"></script>

<script>
$(function () {
	if(document.getElementById('header_under_ttl')){
 		//何か処理を書く
 		$('body').attr('id', 'on_header').attr('class', '{{ $page_id }} {{ $page_id_02 }}');
	}
	if(document.getElementById('header_main_ttl')){
 		//何か処理を書く
 		$('body').attr('id', 'on_header').attr('class', '{{ $page_id }} {{ $page_id_02 }}');
	}
	if(document.getElementById('info_ttl')){
 		//何か処理を書く
 		$('body').attr('id', 'on_header').attr('class', '{{ $page_id }} {{ $page_id_02 }}');
	}
	if(document.getElementById('f-navi')){
 		//何か処理を書く
 		$('body').addClass('on_f-navi {{ $page_id }} {{ $page_id_02 }}');
	}
	if(document.getElementById('footer_button_area')){
 		//何か処理を書く
 		$('body').addClass('on_f-btn {{ $page_id }} {{ $page_id_02 }}');
	}
	if(document.getElementById('f-white_area')){
 		//何か処理を書く
 		$('body').addClass('on_f-white {{ $page_id }} {{ $page_id_02 }}');
	}
	if(document.getElementById('footer_comment_area')){
 		//何か処理を書く
 		$('body').addClass('on_f-btn {{ $page_id }} {{ $page_id_02 }}');
	}
	if(document.getElementById('footer_talkroom')){
 		//何か処理を書く
 		$('body').addClass('on_talkroom {{ $page_id }} {{ $page_id_02 }}');
	}
});
</script>


<!-- 一定時間表示されるモーダル -->
<script>

    function showTimeLimtModal(clickObj) {
        //.modalについたhrefと同じidを持つ要素を探す
        var modalId = clickObj.attr('data-target');
        var modalThis = $('body').find(modalId);
        //bodyの最下にwrapを作る
        $('body').append('<div id="modalWrap" />');
        var wrap = $('#modalWrap'); wrap.fadeIn('200');
        modalThis.fadeIn('200');
        //モーダルの高さを取ってくる
        function mdlHeight() {
            var wh = $(window).innerHeight();
            var attH = modalThis.find('.modalInner').innerHeight();
            modalThis.css({ height: attH });
        }
        mdlHeight();
        $(window).on('resize', function () {
            mdlHeight();
        });
        function clickAction() {
            modalThis.fadeOut('200');
            wrap.fadeOut('200', function () {
                wrap.remove();
            });
        }
        //wrapクリックされたら
        wrap.on('click', function () {
            clickAction(); return false;
        });
        //2秒後に消える
        setTimeout(clickAction, 5000); return false;
    }

    function showAjaxModal(clickObj, url){
        var nowModalSyncer = null;
        var modalClassSyncer = "ajax-modal-syncer";

        clickObj.blur();
        var target = clickObj.attr('data-target');
        if (typeof (target) == "undefined" || !target || target == null) {
            return false;
        }
        nowModalSyncer = $('body').find(target);
        if (nowModalSyncer == null) {
            return false;
        }
        if ($("#modal-overlay")[0]) return false;
        $("body").append('<div id="modal-overlay"></div>');
        $("#modal-overlay").fadeIn("fast");

        //モーダルウィンドウが開いてなければ終了
        if (nowModalSyncer == null) return false;

        //画面(ウィンドウ)の幅、高さを取得
        var w = $(window).width();
        var h = $(window).height();

        //コンテンツ(#modal-content)の幅、高さを取得
        var cw = $(nowModalSyncer).outerWidth();
        var ch = $(nowModalSyncer).outerHeight();

        //センタリングを実行する
        $(nowModalSyncer).css({
            "left": ((w - cw) / 2) + "px",
            "top": ((h - ch) / 2) + "px"
        });
        $(nowModalSyncer).fadeIn("slow");
        $("#modal-overlay,#modal-close,#modal-close.start-btn").unbind().click(function () {
            $('.start-active').addClass('appear');
            $(target + ",#modal-overlay").fadeOut("fast", function () {
                $('#modal-overlay').remove();
            });
            nowModalSyncer = null;
            if ( url != null)
                location.href = url;
        });
    }

    var interval_map_location;
    $(document).ready(function () {
        @if($f_location)
            interval_map_location = setInterval(ajaxMapInterval, 10000);
            // interval_map_location = setInterval(ajaxMapInterval, 5000);
        @else
            clearInterval(interval_map_location);
        @endif

        getNewMsgCnt();
    });

    function ajaxMapInterval() {
        console.log("---- ajaxMapInterval ----");
        if (!navigator.geolocation) {
            return;
        }

        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude =  parseFloat(position.coords.latitude);
            var longitude =  parseFloat(position.coords.longitude);

            // get locations
            $.ajax({
                type: "post",
                url: '{{ route('user.myaccount.upload_map_location') }}',
                data: {
                    _token: "{{ csrf_token() }}",
                    user_id: "{{ Auth::user() ? Auth::user()->id : 0 }}",
                    location: {lat: latitude,lng:longitude}
                },
                dataType: 'json',
                success: function (result) {
                    // console.log("success");
                }
            });
        });
    }

    function getNewMsgCnt(){
        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        $.ajax({
            type: "post",
            url: "{{route('user.get_new_msg')}}",
            data : form_data,
            dataType: 'json',
            contentType : false,
            processData : false,
            success : function(result) {
                if (result.state) {
                    if(result.sale_cnt > 0){
                        $('#sale').html(result.sale_cnt);
                        $('#sale').show();
                    }
                    if(result.notice_cnt > 0){
                        $('#notice').html(result.notice_cnt);
                        $('#notice').show();
                    }
                    if(result.talk_cnt > 0){
                        $('#talk_new').html(result.talk_cnt);
                        $('#talk_new').show();

                    }
                }
            }
        });
        setTimeout(getNewMsgCnt, 60 * 1000);
    }
</script>



