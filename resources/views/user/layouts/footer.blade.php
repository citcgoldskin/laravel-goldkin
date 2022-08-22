<script src="{{ asset('assets/user/js/plugin.js') }}"></script>
<script src="{{ asset('assets/user/js/custom.js') }}"></script>

<!-- 同意するボタン -->
<script>
$(function() {
  $(".agree_btn").click(function(){
    $(".agree_change").toggleClass("_check");
    $(".btn_agree").toggleClass("_check");
  });
});
</script>


<script src="{{ asset('assets/user/js/jquery/jquery-1.11.3.min.js') }}"></script>
<script>
$(function() {
	$(".btn-glay").prop("disabled", true);
  $(".label_inner").click(function(){
        $(".btn-glay").prop("disabled", false);
        $(".agree_required").toggleClass("_check");
        $(".agree_btn_area").toggleClass("_check");
  });
});
</script>


<!-- 出勤ありのみ表示ボタンの切り替え -->
<script src="{{ asset('assets/user/js/jquery/jquery-1.12.4.min.js') }}" ></script>
<script>
$(function(){
    $(document).ready(function () {
        if($('#is_attend').val() == 0){
            $('.change_btn').toggleClass('active');
            var text = $('.change_btn').data('text-clicked');
        }else{
            var text = $('.change_btn').data('text-default');
        }
        $('.change_btn').html(text);
    });

    $('.change_btn').on('click', function(event){
        event.preventDefault();
        $(this).toggleClass('active');

        if($(this).hasClass('active')){
            $('#is_attend').val(0);
            var text = $(this).data('text-clicked');
        } else {
            $('#is_attend').val(1);
            var text = $(this).data('text-default');
        }
        $(this).html(text);
        //alert($('#is_attend').val());
        $('#form1').submit();
    });

    $('#order_type').on('change', function(event){
        $('#form1').submit();
    });
});

</script>

<!-- 郵便番号入力で住所検索 -->
<script type="text/javascript" src="{{ asset('assets/user/js/jquery/jquery-3.3.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('assets/user/js/jquery.zip2addr.js') }}"></script>

<!--
<script>
(function($){
$(document).ready(function () {
  hsize = $('footer').height();//フッターの高さを取得
  $("#contents").css("padding-bottom", hsize + "px");//取得したフッターの高さ分#wrapperにpadding-bottomをpxで指定
});
})(jQuery);
</script>
-->


<!-- 「その他」をクリックしたら表示される吹き出し -->
<script type="text/javascript">
    function showBalloon() {
        var wObjballoon = document.getElementById("makeImg");
        if (wObjballoon.className == "balloon_area") {
            wObjballoon.className = "balloon_open";
        } else {
            wObjballoon.className = "balloon_area";
        }
    }
</script>




