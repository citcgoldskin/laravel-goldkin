@section('title', 'ブロック一覧')
@section('$page_id', 'mypage')
@include('user.layouts.header')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

<section id="msg_only_page">

 <div class="msg_only">
  <p>ブロックしたユーザーはいません。</p>
 </div>

</section>


</div><!-- /contents -->




<footer>

@include('user.layouts.fnavi')

</footer>

@include('user.layouts.footer')

