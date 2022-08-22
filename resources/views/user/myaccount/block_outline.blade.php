
@extends('user.layouts.app')
@section('title', 'ブロック一覧')
@section('$page_id', 'mypage')
@php
	use App\Service\CommonService;
@endphp
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents">

 <section>
	<div class="inner_box">
		 <p class="form_txt">
		  現在、以下のユーザーをブロックしています。<br>
		  ブロックしたユーザーはあなたのサービスや投稿を閲覧できなくなり、予約や提案を送信することができなくなります。
		 </p>
	</div>

	<div class="inner_box top_border">
    	<h3>ユーザー</h3>
	  	<ul class="block_list">
			@foreach($data as $key => $value)
			   <li>
					<div><img src="{{ CommonService::getUserAvatarUrl($value['user']['user_avatar'])}}" alt=""></div>
					<div>{{$value['user']['name']}}</div>
					<div>
						 <p class="btn_base btn_gray">
						  	<a data-target="#msg-1" class="block" id="{{$value['bl_id']}}" val="{{$value['bl_id']}}">ブロック解除</a>
						 </p>
					</div>
			   </li>
			@endforeach
	 	 </ul>
	</div>
</section>
</div><!-- /contents -->

  <!-- 以下がモーダルで呼ばれる -->

  <!-- ※実際に構築される時は「id="msg-ユーザーの名前"」等にされると繰り返し条件でできると思います -->
<div class="modalBox" id="msg-1">
    <div class="modalInner">
    </div>
</div>

<footer>
	@include('user.layouts.fnavi')
</footer>
@endsection

@section('page_js')
	<script>
        $('.block').on('click',function(){
            var bl_id = $(this).attr('val');
            $.ajax({
                type: "post",
                url: " {{ route('user.myaccount.del_block') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bl_id: bl_id
                },
                dataType: "json",
                success: function(data) {
                    if (data.result) {
                        $('.modalInner').html('<p>' + data.block_name + '<br>さんのブロックを解除しました</p>');
                        showTimeLimtModal($('#' + bl_id));
                    }else{

                    }

                    location.href = '{{ route('user.myaccount.block_outline') }}';
                },
                error: function(){
                    $('.modalInner').html('<p></p>'); //todo insert msg
                },
                complete: function() {
                }
            });
        });
	</script>
@endsection

