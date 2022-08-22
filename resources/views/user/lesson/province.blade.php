@extends('user.layouts.app')

@section('title', '都道府県を選択')

@php
	use App\Service\CommonService;
@endphp

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_under')

    <div id="contents">

        <!--main_-->
        <form action="./" method="post" name="form1" id="form1">

 <section>

	 <!-- ************************************************************ -->
	@foreach($region_prefectures as $region_id => $region_data)
	  <div class="board_box set-list_wrap">
	   <input id="pref-{{$region_id}}" name="acd" class="acd-check" type="checkbox">
	    <label class="acd-label" for="pref-{{$region_id}}">{{ $region_data['region'] }}</label>
		<div class="acd-content set-list_content">
		 <ul>
			 @foreach($region_data['child'] as $pref_id => $pref_name)
		  		<li>
					@if($prev_url_id == 1 )
						<a href="{{route('user.myaccount.edit_profile.form', ['area_id' => $pref_id])}}">
							{{ $pref_name }}
							<span></span>
						</a>
					@elseif($prev_url_id == 2)
						<a href="{{route('user.lesson.search', ['class_id' => $class_id, 'province_id' => $pref_id])}}">
							{{ $pref_name }} ({{ isset($arr_lesson_cnt[$pref_id]) ? $arr_lesson_cnt[$pref_id] : 0 }})
							<span></span>
						</a>
					@elseif($prev_url_id == 3)
						<a href="{{route('user.lesson.search_condition', ['lesson_count' => isset($arr_lesson_cnt[$pref_id]) ? $arr_lesson_cnt[$pref_id] : 0, 'province_id' => $pref_id])}}">
							{{ $pref_name }} ({{CommonService::showFormatNum(isset($arr_lesson_cnt[$pref_id]) ? $arr_lesson_cnt[$pref_id] : 0)}})
							<span></span>
						</a>
                    @elseif($prev_url_id == 4)
                        <a href="{{route('keijibann.condition', ['cnt' => isset($arr_lesson_cnt[$pref_id]) ? $arr_lesson_cnt[$pref_id] : 0, 'province_id' => $pref_id])}}">
                            {{ $pref_name }} ({{CommonService::showFormatNum(isset($arr_lesson_cnt[$pref_id]) ? $arr_lesson_cnt[$pref_id] : 0)}})
                            <span></span>
                        </a>
					@endif
				</li>
			 @endforeach
		 </ul>
	    </div>
	  </div>
	@endforeach

 </section>

</form>

    </div><!-- /contents -->

@endsection
