
@extends('user.layouts.app')
@section('title', 'プロフィール')
@section('$page_id', 'home')
@php
    use App\Service\CommonService;
	use App\Service\LessonService;
@endphp
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

@include('user.layouts.header_under')

<div id="contents">

  <!--main_-->
{{--<form action="./" method="post" name="form1" id="form1">--}}

      <section class="pt15">
		   <div class="swiper-container shadow-glay">
          <div class="swiper-inner">
          <div class="profile">
              <ol class="swiper-wrapper pb0">
					<!-- Slides -->
				  @foreach($lesson_images as $k => $v)
					<li class="swiper-slide">
					  <div class="swip_contents_block">
						  <div class="slider_box">
							<div class="img-box">
								<img src="{{CommonService::getLessonImgUrl($v)}}" alt="プロフィールイメージ画像">
							</div>
						  </div>
					  </div>
					</li>
				  @endforeach
				  </ol>
				  <!-- If we need pagination -->
				  <div class="swiper-pagination"></div>

				  <!-- If we need navigation buttons -->
				</div>
			  </div>
		  </div>

				  <!-- If we need navigation buttons -->

          <div style="padding-top: 40px">
			 <ul class="profile_box">
			  <li>
			   <img src="{{$user_info['avatar']}}" class="プロフィールアイコン">
			  </li>
			 @if($user_info['self_user_id'] != $user_info['user_id'])
			  <li>
			   <div class="c-like-box">
				  <div class="clex-box_01">
				  <input type="checkbox" name="favourite" @if($user_info['favourite'] > 0) checked="checked" @endif id="c1">
				  <label for="c1" class="nobo"><p>LIKED</p></label>
				  </div>
			   </div>
			  </li>
			 @endif
		     </ul>
          </div>

		  <div class="profile_base">
			<ul>
				<li class="profile_name">
					<p>{{$user_info['name']}}
						<span>（{{$user_info['age']}}）{{$user_info['sex']}}</span>
					</p></li>
				@if($user_info['self_confirm'] == config('const.pers_conf.confirmed'))
					<li class="honnin_kakunin"><p>本人確認済み</p></li>
				@endif
			</ul>
			<ul class="profile_info">
			 <li class="target_area">{{$user_info['area_name']}}</li>
			 <li class="jisseki">
				<p>購入実績 <span>{{CommonService::showFormatNum($user_info['buy_schedule_count'])}}</span><span>件</span></p>
				<p>販売実績 <span>{{CommonService::showFormatNum($user_info['sell_schedule_count'])}}</span><span>件</span></p>
			 </li>
			</ul>
			<div class="self-introduction">
				<p class="cut-text">{{$user_info['user_intro']}}</p>
				 <p class="readmore-btn"><a href="" class="shadow-glay">続きを読む</a></p>
			</div>


		  </div>
	  </section>

    @if($user_info['senpai_id'] > 0 && count($user_info['lesson']) > 0)
      <section>
        <h3>出品レッスン（{{count($user_info['lesson'])}}件）</h3>

		<ul class="lesson_list_wrap">
			@foreach($user_info['lesson'] as $key => $value)
			<li class="lesson_box">
			 <a href="{{route('user.lesson.lesson_view', ['lesson_id' => $value['lesson_id']])}}">
				<div class="img-box">
					<img src="{{CommonService::getLessonImgUrl(LessonService::getLessonFirstImage($value))}}" alt="ウォーキング画像">
					<p>{{isset($value['lesson_class']) ? $value['lesson_class']['class_name'] : ''}}</p>
				</div>
				<div class="lesson_info_box">
				 <p class="lesson_name">{{$value['lesson_title']}}</p>
				 <p class="lesson_price"><em>{{ CommonService::showFormatNum($value['lesson_30min_fees']) }}</em><span>円 / <em>30</em>分〜</span></p>
				 <div class="teacher_name">
				  <div class="icon_s30"><img src="{{ CommonService::getUserAvatarUrl($value['senpai']['user_avatar']) }}" alt=""></div>
				  <div>{{$value['senpai']['name']}}（<em>{{CommonService::getAge($value['senpai']['user_birthday'])}}</em>）</div>
				 </div>
				</div>
			   </a>
			 </li>
			@endforeach
		</ul>

	  </section>
	@endif
{{--</form>	--}}

</div><!-- /contents -->

<footer>

@include('user.layouts.fnavi')
</footer>
@endsection
@section('page_js')
<script type="text/javascript">
    $('input[name="favourite"]').change(function(){
        var bSelected = 0;
        if(this.checked == true){
            bSelected = 1;
        }

        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        form_data.append("senpai_id", {{$user_info['senpai_id']}});
        form_data.append("bSelected", bSelected);
        $.ajax({
            type: "post",
            url: "{{route('user.myaccount.ajaxSenpaiFavourite')}}",
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
