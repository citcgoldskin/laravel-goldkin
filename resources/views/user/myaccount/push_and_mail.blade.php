
@extends('user.layouts.app')
@section('title', 'プッシュ通知・メール設定')
@section('$page_id', 'mypage')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')
<div id="contents">

  <!--main_-->
<form action="./" method="post" name="form1" id="form1">

    @if(!(Browser::isMobile() || Browser::isTablet()))
	<section class="pb0">

	   <div class="inner_box">
	    <h3>プッシュ通知</h3>
		 <div class="white_box">
			<div class="switch_box">
				@foreach($msg_class as $key => $value)
					@if($value['mc_name'] == "あなた宛のお知らせ")
						<div class="li_flex">
							<div>あなた宛のお知らせ</div>
							<div>必須</div>
						</div>
					@else
						<div class="switch_01">
							<input name="commitment_push" type="checkbox" id="sw1-{{$value['mc_id']}}" value="0"
                               @if(!isset($value['msg_setting']['ms_push']) || ($value['msg_setting']['ms_push'] != 0))
                                    checked="checked"
                                @endif
                            >
							<label for="sw1-{{$value['mc_id']}}"><p>{{$value['mc_name']}}</p></label>
						</div>
					@endif
				@endforeach
			</div>
		  </div>
	   </div>

</section>
    @endif

	<section>

	   <div class="inner_box">
	    <h3>メール</h3>
		 <div class="white_box">
			<div class="switch_box">
				@foreach($msg_class as $key => $value)
					@if($value['mc_name'] == "あなた宛のお知らせ")
						<div class="li_flex">
							<div>あなた宛のお知らせ</div>
							<div>必須</div>
						</div>
					@else
						<div class="switch_01">
							<input type="checkbox" name="commitment_mail" value="0" id="sw2-{{$value['mc_id']}}"
                               @if(!isset($value['msg_setting']['ms_email']) || ($value['msg_setting']['ms_email'] != 0))
                                    checked="checked"
                                @endif
                            >
							<label for="sw2-{{$value['mc_id']}}"><p>{{$value['mc_name']}}</p></label>
						</div>
					@endif
				@endforeach
			</div>
		  </div>
	   </div>

</section>

</form>

</div><!-- /contents -->

<footer>

@include('user.layouts.fnavi')

</footer>
@endsection
@section('page_js')
<script type="text/javascript">
    $('input[name="commitment_push"]').change(function(){
        var bSelected = 0;
        if(this.checked == true)
        {
            bSelected = 1;
        }
        var mc_id = this.id;

        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        form_data.append("ms_mc_id", mc_id.replace("sw1-",""));
        form_data.append("ms_push", bSelected);

        $.ajax({
            type: "post",
            url: "{{route('user.myaccount.ajax_push_and_mail')}}",
            data : form_data,
            dataType: 'json',
            contentType : false,
            processData : false,
            success : function(result) {
            }
        });
    });

    $('input[name="commitment_mail"]').change(function(){
        var bSelected = 0;
        if(this.checked == true)
        {
            bSelected = 1;
        }
        var mc_id = this.id;

        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        form_data.append("ms_mc_id", mc_id.replace("sw2-",""));
        form_data.append("ms_email", bSelected);

        $.ajax({
            type: "post",
            url: "{{route('user.myaccount.ajax_push_and_mail')}}",
            data : form_data,
            dataType: 'json',
            contentType : false,
            processData : false,
            success : function(result) {
            }
        });
    });

</script>
@endsection
