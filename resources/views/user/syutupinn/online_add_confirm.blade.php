@extends('user.layouts.app')

<div class="modal">

  <!--main_visual A-21-->
	<div id="completion_wrap" class="no_modal">
		<div class="modal_body completion">
			<div class="modal_inner">
				<h2 class="modal_ttl">
					変更申請を<br>
					受け付けました。
				</h2>
			</div>
			<div class="modal_txt modal_txt_bottom">
				<p>
					審査の結果等は「センパイ出品の出品レッスン」または「マイページの出品レッスン管理」からご確認ください。
				</p>
			</div>
		</div>


		<div class="button-area">
			<div class="btn_base btn_white shadow">
				<a onclick="gotoList();">OK</a>
			</div>
		</div>
	</div>
</div>

<script>
    function gotoList() {
        location.href = '{{route('user.syutupinn.lesson_list')}}';
    }
</script>
<!--main_visual A-21 end-->



