
@extends('user.layouts.app')
@section('title', $title)
@section('$page_id', 'pdf')
{{--@include('user.layouts.header_under_pdf')--}}
@section('content')

    @include('user.layouts.header_under')
<style>
body{
background: #FFF;
	}
</style>

  <!-- ************************************************************************
本文
************************************************************************* -->

  <div id="contents" class="pdf-box">
    <div class="white-bg">
      <p class="day">{{ \Carbon\Carbon::parse($date)->format('Y年n月t日') }}</p>
      <p class="name">{{ $obj_user->name }} 様</p>
      <div class="flex-box">
        <div class="left-box">
          <p class="address_01">〒{{ $obj_user->user_mail }}</p>
          <p class="address_02">{{ \App\Service\AreaService::getOneAreaFullName($obj_user->user_area_id) }}{{ $obj_user->user_county }}<br>
              {{ $obj_user->user_village.$obj_user->user_mansyonn }}</p>
        </div>
        <div class="right-box">
          <p class="company01">センパイ</p>
          <p class="address_01">{{ config('const.company_info.zip') }}</p>
          <p class="address_02">{{ config('const.company_info.address1') }}<br>
              {{ config('const.company_info.address2') }}</p>
          <p class="company02">{{ config('const.company_info.name') }}</p>
        </div>
      </div>

		<dl class="money al-end">
		<dt>売上合計</dt>
		<dd>{{ number_format($price) }} 円</dd>
		</dl>
		      <p class="day2">集計期間：{{ \Carbon\Carbon::parse($date)->format('Y年n月1日') }}〜{{ \Carbon\Carbon::parse($date)->format('Y年n月t日') }}</p>

    </div>
  </div>
  <!-- /contents -->
@endsection
