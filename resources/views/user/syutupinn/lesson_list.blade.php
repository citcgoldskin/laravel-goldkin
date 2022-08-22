@extends('user.layouts.app')

@section('title', 'センパイ出品')

@section('content')

    @if(isset($page_from))
        @include('user.layouts.header_under')
    @else
        @include('user.layouts.gnavi_under')
    @endif

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

	<div id="contents">

		<section class="tab_area tab_white mb0">
			<div class="switch_tab three_tab">
				<div class="type_radio radio-01">
					<input type="radio" name="onof-line" id="off-line" checked="checked" onclick="location.href='{{route('user.syutupinn.lesson_list')}}'">
					<label class="ok" for="off-line">出品レッスン</label>
				</div>
				<div class="type_radio radio-02">
					<input type="radio" name="onof-line" id="on-line-1" onclick="location.href='{{route('user.syutupinn.schedule')}}'">
					<label class="ok" for="on-line-1">出勤カレンダー</label>
				</div>
				<div class="type_radio radio-03">
					<input type="radio" name="onof-line" id="on-line-2" onclick="location.href='{{route('user.syutupinn.request')}}'">
					<label class="ok" for="on-line-2">リクエスト</label>
					@if($req_count != '')
						<span class="midoku">{{$req_count}}</span>
					@endif
				</div>
			</div>
		</section>

		<div class="page_img img-guide">
			<a href="{{route('user.syutupinn.manual')}}">
				<img src="{{asset('assets/user/img/listing_guide_ttl.png')}}" alt="出品はじめかたガイド">
			</a>
		</div>

		<div class="tabs info_wrap three_tab guide-ver">
			<input id="tab-01" type="radio" name="tab_item" checked="checked">
			<label class="tab_item" for="tab-01">出品中</label>
			<input id="tab-02" type="radio" name="tab_item">
			<label class="tab_item" for="tab-02">申請中</label>
			<input id="tab-03" type="radio" name="tab_item">
			<label class="tab_item" for="tab-03">下書き・非公開</label>

			<!-- ********************************************************* -->

			<div class="tab_content" id="tab-01_content">
				@if(isset($lesson_list1) && $lesson_list1->count() >0)
				<ul class="lesson_list_wrap">
					@foreach($lesson_list1 as $k=>$v)
						<li class="lesson_box">
								<a href="{{route('user.syutupinn.regLesson', ['type'=> $v['lesson_type'] , 'lesson_id'=>$v['lesson_id']])}}">
                                @php
                                    $pic_arr = \App\Service\CommonService::unserializeData($v['lesson_image']);
                                @endphp
								<div class="img-box {{ count($pic_arr) > 0 && $pic_arr[0] ? '' : 'no-img-box' }}">
									<img src="{{count($pic_arr) > 0 ? \App\Service\CommonService::getLessonImgUrl($pic_arr[0]) : ''}}" alt="イメージ">
								</div>
								<div class="lesson_info_box">
									<p class="lesson_name ttl-block">{{$v['lesson_title']}}</p>
									<div class="listing_area">
										<p class="listing_area_ttl">出品エリア</p>
										<p class="lesson_area_label">
											{{--@if(isset($v['senpai']['area']) && $v['senpai']['area']['area_name'] != "")
												{{$v['senpai']['area']['area_name']}}
												{{$v['senpai']['senpai_county'] != "" ? '／'.$v['senpai']['senpai_county'] : ''}}{{$v['senpai']['senpai_village'] != '' ? '／'.$v['senpai']['senpai_village'] : ''}}{{$v['senpai']['senpai_mansyonn'] != '' ? '／'.$v['senpai']['senpai_mansyonn'] : ''}}
											@endif--}}
                                            {{ implode('/', $v->lesson_area_names) }}
										</p>
									</div>
									<ul class="icon_area">
										<li>
                                            <p class="icon_img">
                                                @if(isset($v['lesson_class']))
                                                    <img src="{{asset('storage/class_icon/'.$v['lesson_class']['class_icon'])}}" alt="{{$v['lesson_class']['class_name']}}">
                                                @endif
                                            </p>
                                        </li>
										<li>@if(intval($v['fav_count']) > 0)<span class="heart">{{$v['fav_count']}}</span>@endif</li>
									</ul>
								</div>
							</a>
						</li>
					@endforeach
				</ul>
				{{--@else
					<div class="no-data">検索結果 0件</div>--}}
				@endif
		</div><!-- /tab_content -->

			<!-- ********************************************************* -->

			<div class="tab_content" id="tab-02_content">
				@if(isset($lesson_list2) && $lesson_list2->count() >0)
				<ul class="lesson_list_wrap">
					@foreach($lesson_list2 as $k=>$v)
						<li class="lesson_box">
								<a href="{{route('user.syutupinn.regLesson', ['type'=> $v['lesson_type'] , 'lesson_id'=>$v['lesson_id']])}}">
                                    @php
                                        $pic_arr = \App\Service\CommonService::unserializeData($v['lesson_image']);
                                    @endphp
								<div class="img-box {{ count($pic_arr) > 0 && $pic_arr[0] ? '' : 'no-img-box' }}">
									@if($v['lesson_state'] == config('const.lesson_state.draft'))
										<span class="status shitagaki">下書き</span>
									@elseif($v['lesson_state'] == config('const.lesson_state.private'))
										<span class="status">非公開</span>
									@elseif($v['lesson_state'] == config('const.lesson_state.check'))
									    <span class="status-check">審査中</span>
									@elseif($v['lesson_state'] == config('const.lesson_state.reject'))
									 	<span class="status-bad">非承認</span>
									@endif
									<img src="{{count($pic_arr) > 0 ? \App\Service\CommonService::getLessonImgUrl($pic_arr[0]) : ''}}" alt="イメージ">
								</div>
								<div class="lesson_info_box">
									<p class="lesson_name ttl-block">{{$v['lesson_title']}}</p>
									<div class="listing_area">
										<p class="listing_area_ttl">出品エリア</p>
										<p class="lesson_area_label">
											{{--@if(isset($v['senpai']['area']) && $v['senpai']['area']['area_name'] != "")
												{{$v['senpai']['area']['area_name']}}
												{{$v['senpai']['senpai_county'] != "" ? '／'.$v['senpai']['senpai_county'] : ''}}{{$v['senpai']['senpai_village'] != '' ? '／'.$v['senpai']['senpai_village'] : ''}}{{$v['senpai']['senpai_mansyonn'] != '' ? '／'.$v['senpai']['senpai_mansyonn'] : ''}}
											@endif--}}
                                            {{ implode('/', $v->lesson_area_names) }}
										</p>
									</div>
									<ul class="icon_area">
										<li>
                                            <p class="icon_img">
                                                @if(isset($v['lesson_class']))
                                                    <img src="{{asset('storage/class_icon/'.$v['lesson_class']['class_icon'])}}" alt="{{$v['lesson_class']['class_name']}}">
                                                @endif
                                            </p>
                                        </li>
										<li>@if(intval($v['fav_count']) > 0)<span class="heart">{{$v['fav_count']}}</span>@endif</li>
									</ul>
								</div>
							</a>
						</li>
					@endforeach
				</ul>
				{{--@else
					<div class="no-data">検索結果 0件</div>--}}
				@endif
			</div><!-- /tab_content -->

			<!-- ********************************************************* -->

			<div class="tab_content" id="tab-03_content">
				@if(isset($lesson_list3) && $lesson_list3->count() >0)
				<ul class="lesson_list_wrap">
					@foreach($lesson_list3 as $k=>$v)
						<li class="lesson_box">
                            <a href="{{route('user.syutupinn.regLesson',['type'=> $v['lesson_type'] , 'lesson_id'=>$v['lesson_id']])}}">
                                @php
                                    $pic_arr = \App\Service\CommonService::unserializeData($v['lesson_image']);
                                @endphp
								<div class="img-box {{ count($pic_arr) > 0 && $pic_arr[0] ? '' : 'no-img-box' }}">
									@if($v['lesson_state'] == config('const.lesson_state.draft'))
										<span class="status shitagaki">下書き</span>
									@elseif($v['lesson_state'] == config('const.lesson_state.private'))
										<span class="status">非公開</span>
									@elseif($v['lesson_state'] == config('const.lesson_state.check'))
										<span class="status-check">審査中</span>
									@elseif($v['lesson_state'] == config('const.lesson_state.reject'))
										<span class="status-bad">非承認</span>
									@endif
                                    @if($pic_arr[0])
									    <img src="{{count($pic_arr) > 0 ? \App\Service\CommonService::getLessonImgUrl($pic_arr[0]) : ''}}" alt="イメージ">
                                    @endif
								</div>
								<div class="lesson_info_box {{ $pic_arr[0] ? '' : 'lesson_info_box_no_img' }}">
									<p class="lesson_name ttl-block">{{$v['lesson_title']}}</p>
									<div class="listing_area">
										<p class="listing_area_ttl">出品エリア</p>
										<p class="lesson_area_label">
											{{--@if($v['senpai']['area']['area_name'] != "")
												{{$v['senpai']['area']['area_name']}}
												{{$v['senpai']['senpai_county'] != "" ? '／'.$v['senpai']['senpai_county'] : ''}}{{$v['senpai']['senpai_village'] != '' ? '／'.$v['senpai']['senpai_village'] : ''}}{{$v['senpai']['senpai_mansyonn'] != '' ? '／'.$v['senpai']['senpai_mansyonn'] : ''}}
											@endif--}}
                                            {{ implode('/', $v->lesson_area_names) }}
										</p>
									</div>
									<ul class="icon_area">
										<li>
                                            <p class="icon_img">
                                                @if(isset($v['lesson_class']))
                                                    <img src="{{asset('storage/class_icon/'.$v['lesson_class']['class_icon'])}}" alt="{{$v['lesson_class']['class_name']}}">
                                                @endif
                                            </p>
                                        </li>
										<li>@if(intval($v['fav_count']) > 0)<span class="heart">{{$v['fav_count']}}</span>@endif</li>
									</ul>
								</div>
							</a>
						</li>
					@endforeach
				</ul>
				{{--@else
					<div class="no-data">検索結果 0件</div>--}}
				@endif
			</div><!-- /tab_content -->


		</div><!-- /tabs -->

	</div><!-- /contents -->

	<div class="rb_btn_area">
		<a href="{{ route('user.syutupinn.regLesson') }}">
			<div class="btn_inner">
				<p><img src="{{asset('assets/user/img/footer/btn_img_listing.png')}}" alt=""></p>
				<p>出品</p>
			</div>
		</a>
	</div>
	<input type="hidden" id="pages1" value="{{$pages1}}">
	<input type="hidden" id="pages2" value="{{$pages2}}">
	<input type="hidden" id="pages3" value="{{$pages3}}">

	<footer>
		@include('user.layouts.fnavi')
	</footer>

@endsection

@section('page_js')
	<script>
        var PUBLIC_TAB = {{config('const.lesson_tab.public')}};
        var CHECKING_TAB = {{config('const.lesson_tab.checking')}};
        var DRAFT_TAB = {{config('const.lesson_tab.draft')}};

        var LESSON_STATE_DRAFT = {{config('const.lesson_state.draft')}};
        var LESSON_STATE_PRIVATE = {{config('const.lesson_state.private')}};
        var LESSON_STATE_CHECK = {{config('const.lesson_state.check')}};
        var LESSON_STATE_REJECT = {{config('const.lesson_state.reject')}};

        var pageCount1 = parseInt($('#pages1').val());
        var pageCount2 = parseInt($('#pages2').val());
        var pageCount3 = parseInt($('#pages3').val());
        var pageCurrent1 = 1;
        var pageCurrent2 = 1;
        var pageCurrent3 = 1;
        var isLoading = false;
        var cur_tab = PUBLIC_TAB;
        $(document).ready(function () {
            $('#tab-01').click(function () { cur_tab = PUBLIC_TAB; });
            $('#tab-02').click(function () { cur_tab = CHECKING_TAB; });
            $('#tab-03').click(function () { cur_tab = DRAFT_TAB; });
            $(window).scroll(function(){
                var obj;
                if(cur_tab == PUBLIC_TAB) { obj = $("#tab-01_content"); }
                else if(cur_tab == CHECKING_TAB) { obj = $("#tab-02_content"); }
                else if(cur_tab == DRAFT_TAB) { obj = $("#tab-03_content"); }
                var totalHeight = document.documentElement.scrollHeight - $(obj).outerHeight(true);
                var clientHeight = document.documentElement.clientHeight;
                var scrollTop = (document.body && document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;
                if ((totalHeight == scrollTop + clientHeight) || (totalHeight <= scrollTop + clientHeight + 3)) {
                    if (isLoading) return;
                    showList(cur_tab);
                }
                return;
            });
        });

        function showList(cur_tab) {
            if(cur_tab == PUBLIC_TAB) {
                if(pageCount1 <= 1) return;
                if(pageCount1 <= pageCurrent1) return;
                pageCurrent1++;
                getAjaxList(cur_tab, pageCurrent1);
            }
            if(cur_tab == CHECKING_TAB) {
                if(pageCount2 <= 1) return;
                if(pageCount2 <= pageCurrent2) return;
                pageCurrent2++;
                getAjaxList(cur_tab, pageCurrent2);
            }
            if(cur_tab == DRAFT_TAB) {
                if(pageCount3 <= 1) return;
                if(pageCount3 <= pageCurrent3) return;
                pageCurrent3++;
                getAjaxList(cur_tab, pageCurrent3);
            }
        }
        function getAjaxList(tab, curPage){
            isLoading = true;
            $.ajax({
                type: "get",
                url: "{{route('user.syutupinn.ajax_list')}}" + "/" + tab + "?page=" + curPage,
                dataType: 'json',
                success : function(result)
                {
                    if(result.state == 'success'){
                        var str_html = '';
                        for(var i=0; i < result.lesson_list.data.length; i++){
                            var row = result.lesson_list.data[i];
                            var area_info = '';
                            if(row['senpai']['area']['area_name'] != ""){
                                area_info = row['senpai']['area']['area_name'];
                                if(row['senpai']['senpai_county'] != ""){
                                    area_info += + '／' + row['senpai']['senpai_county'];
                                    if(row['senpai']['senpai_village'] != ''){
                                        area_info += + '／' + row['senpai']['senpai_village'];
                                    }
                                }
                            }
                            var state_info = '';
                            if(row['lesson_state'] == LESSON_STATE_DRAFT){
                                state_info = '<span class="status shitagaki">下書き</span>';
                            }else if(row['lesson_state'] == LESSON_STATE_PRIVATE){
                                state_info = '<span class="status">非公開</span>';
                            }else if(row['lesson_state'] == LESSON_STATE_CHECK){
                                state_info = '<span class="status-check">審査中</span>';
                            }else if(row['lesson_state'] == LESSON_STATE_REJECT){
                                state_info = '<span class="status-bad">非承認</span>';
                            }
                            var hart_info = '';
                            if(parseInt(row['fav_count']) > 0){
                                hart_info = '<span class="heart">' + row['fav_count'] + '</span>';
                            }
                            var first_pic = '';
                            if(row['lesson_image'].length > 0)
                                first_pic = row['lesson_image'][0];
                            var go_add ='<a href="{{route('user.syutupinn.regLesson')}}/'+row['lesson_type'] + '/' + row['lesson_id']+'">';

                            str_html += '<li class="lesson_box">' +
                                go_add +
                                '<div class="img-box">' + state_info +
                                '<img src="{{asset('storage/lesson')}}/'+first_pic+'" alt="イメージ">' +
                                '</div>' +
                                '<div class="lesson_info_box">' +
                                '<p class="lesson_name ttl-block">'+row['lesson_title']+'</p>' +
                                '<div class="listing_area">' +
                                '<p class="listing_area_ttl">出品エリア</p>' +
                                '<p>' + area_info + '</p>' +
                                '</div>' +
                                '<ul class="icon_area">' +
                                '<li><p class="icon_img"><img src="{{asset('storage/class_icon')}}/' + row['lesson_class']['class_icon'] + '" alt="' + row['lesson_class']['class_name'] + '"></p></li>' +
                                '<li>' + hart_info + '</li>' +
                                '</ul>' +
                                '</div>' +
                                '</a>' +
                                '</li>';
                        }
                        if(tab == PUBLIC_TAB) { $('#tab-01_content ul.lesson_list_wrap').append(str_html);}
                        if(tab == CHECKING_TAB) { $('#tab-02_content ul.lesson_list_wrap').append(str_html);}
                        if(tab == DRAFT_TAB) { $('#tab-03_content ul.lesson_list_wrap').append(str_html);}
                        isLoading = false;
                        var cutFigure = '32';
                        setAbridgedTxt(cutFigure);
                    }
                }
            });
        }
	</script>
@endsection
