
@extends('user.layouts.app')
@section('title', 'プッシュ通知・メール設定')
@section('$page_id', 'mypage')
@include('user.layouts.header')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

  <!--main_-->
<form action="./" method="post" name="form1" id="form1">

	<section class="pb0">
		
	   <div class="inner_box">
	    <h3>プッシュ通知</h3>
		 <div class="white_box">
			<div class="switch_box">
				<div class="switch_01">
					<input name="commitment" type="checkbox" id="sw1-1" value="1" checked="checked">
					<label for="sw1-1"><p>取引関連</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw1-2">
					<label for="sw1-2"><p>メッセージ</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw1-3">
					<label for="sw1-3"><p>フォロー・お気に入り</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw1-4">
					<label for="sw1-4"><p>機能更新・メンテナンス</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw1-5">
					<label for="sw1-5"><p>おすすめ・サービス</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw1-6">
					<label for="sw1-6"><p>ニュース</p></label>
				</div>
				<div class="li_flex">
					<div>あなた宛のお知らせ</div>
					<div>必須</div>
				</div>
			</div>
		  </div>
	   </div>
		
</section>

	<section>
		
	   <div class="inner_box">
	    <h3>メール</h3>
		 <div class="white_box">
			<div class="switch_box">
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw2-1">
					<label for="sw2-1"><p>取引関連</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw2-2">
					<label for="sw2-2"><p>メッセージ</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw2-3">
					<label for="sw2-3"><p>フォロー・お気に入り</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw2-4">
					<label for="sw2-4"><p>機能更新・メンテナンス</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw2-5">
					<label for="sw2-5"><p>おすすめ・サービス</p></label>
				</div>
				<div class="switch_01">
					<input type="checkbox" name="commitment" value="1" id="sw2-6">
					<label for="sw2-6"><p>ニュース</p></label>
				</div>
				<div class="li_flex">
					<div>あなた宛のお知らせ</div>
					<div>必須</div>
				</div>
			</div>
		  </div>
	   </div>
		
</section>
	
</form>

</div><!-- /contents -->




<footer>

@include('user.layouts.fnavi')

</footer>

@include('user.layouts.footer')

