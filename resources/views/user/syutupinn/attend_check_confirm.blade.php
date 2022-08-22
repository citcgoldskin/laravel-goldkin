@extends('user.layouts.app')

@section('title', '内容の確認')

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

  <!--main_-->
<form action="" method="post" name="form1" id="form1" target="senddata">

<section>

	   <div class="inner_box">
        <h3>承認するリクエスト</h3>
		<div class="white_box">
		 <ul class="list_box">
		  <li class="due_date">
		  <div>
		   <p>
		    <span>
		    2021<small>年</small>
			2<small>月</small>
			16<small>日</small>
		   </span>
		   </div>
		   <div class="jitai">
		    <p>16:00～17:00</p>
		   </div>
		  </li>

		  <li>
		   <div>
		    <p>レッスン料</p>
		    <p class="price_mark tax-in">4,500</p>
		   </div>
		   <div>

		    <p class="modal-link">
			 <a class="modal-syncer" data-target="modal-service">手数料率</a>
			</p>

		    <p>C</p>

		   </div>

		   <div>
		    <p>手取り金額（目安）</p>
		    <p class="price_mark tax-in">3,600</p>
		   </div>
		  </li>

		 </ul>

		</div>

		 <div class="kome_txt">
		  <p class="mark_left mark_kome">
		   手取り金額については、<br>
		   レッスンのキャンセルや追加予約、コウハイがあなたの発行したクーポンを使用した場合に変動することがあります。
		  </p>
		 </div>
	   </div>


</section>

</form>

 		<div class="white-bk">
		<div class="button-area">
		  <div class="btn_base btn_orange shadow">
		   <button type="submit"  class="btn-send2">この内容で送信</button>
		  </div>
	  </div>
</div>
</div><!-- /contents -->

    <!-- モーダル部分 *********************************************************** -->
	<iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
		<div class="modal-wrap completion_wrap ok">
			<div id="modal-send" class="modal-content ok">

			<div class="modal_body completion">
			 <div class="modal_inner">
			  <h2 class="modal_ttl">
			   リクエストに対する<br>
			   回答を送信しました
			  </h2>

			  <div class="modal_txt">
			   <p>
			    レッスンが購入されると<br>
				トークルームが開きます。
			   </p>
			  </div>
			 </div>
			</div>

	 			 <div class="button-area">
		 			 <div class="btn_base btn_ok">
					  <a href="C-3_4.php">OK</a>
					 </div>
				</div>
			</div>
		</div>
    <div id="modal-overlay2" style="display: none;"></div>
		<!-- モーダル部分 / ここまで ************************************************* -->

    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

