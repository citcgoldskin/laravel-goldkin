
@extends('user.layouts.app')
@section('title', 'その他')
@section('$page_id', 'mypage')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')
<div id="contents">

  <form action="./" method="post" name="form1" id="form1">

  <section>

	  <ul class="list_area">

	   <li class="icon_form type_arrow_right">
	    <a href="{{route('user.myaccount.company_abstract')}}">会社概要</a>
       </li>

	   <li class="icon_form type_arrow_right">
	    <a href="{{route('using_rules')}}">利用規約</a>
       </li>

	   <li class="icon_form type_arrow_right">
	    <a href="{{route('privacy_policy')}}">プライバシーポリシー</a>
       </li>

	   <li class="icon_form type_arrow_right">
	    <a href="{{route('user.myaccount.sale_method')}}">特定商取引法に基づく表記</a>
       </li>

	   <li class="icon_form type_arrow_right">
	    <a href="{{route('user.myaccount.pay_method')}}">資金決済法に基づく表記</a>
       </li>

	   <li class="li_flex">
	    <div>バージョン</div>
		<div>4.26.0</div>
       </li>
	 </ul>

  </section>

  <section class="pt15">

	  <ul class="list_area">

	   <li class="icon_form type_arrow_right">
	    <a href="{{route('user.myaccount.quit')}}">退会する</a>
       </li>
	 </ul>

  </section>

	</form>


  </div><!-- /contents-->



<footer>

@include('user.layouts.fnavi')

</footer>

@endsection
