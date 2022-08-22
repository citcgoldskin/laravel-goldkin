@extends('user.layouts.app')

@section('title', 'センパイ出品')

@section('content')

    @include('user.layouts.gnavi_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

	<div id="contents">

		<section class="tab_area tab_white mb0">
			<div class="switch_tab three_tab">
				<div class="type_radio radio-01">
					<input type="radio" name="onof-line" id="off-line" checked="checked" onclick="location.href='C-3_4php'">
					<label class="ok" for="off-line">出品レッスン</label>
				</div>
				<div class="type_radio radio-02">
					<input type="radio" name="onof-line" id="on-line-1" onclick="location.href='C-16.php'">
					<label class="ok" for="on-line-1">出勤カレンダー</label>
				</div>
				<div class="type_radio radio-03">
					<input type="radio" name="onof-line" id="on-line-2" onclick="location.href='C-20.php'">
					<label class="ok" for="on-line-2">リクエスト<span class="midoku">99</span></label>
				</div>
			</div>
		</section>

		<div class="page_img img-guide">
			<a href="C-5_7.php">
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
				<ul class="lesson_list_wrap">
					@php
						$lesson_list = $lesson_list1->toArray()['data'];
					@endphp
					@foreach($lesson_list as $k=>$v)
						<li class="lesson_box">
							<a href="{{route('user.syutupinn.add')}}/{{$v['lesson_id']}}">
								<div class="img-box">
									@php
										$pic_arr = explode(',', $v['lesson_image']);
									@endphp
									<img src="{{asset('storage/lesson/'.$pic_arr[0])}}" alt="イメージ">
								</div>
								<div class="lesson_info_box">
									<p class="lesson_name info_ttl">{{$v['lesson_title']}}</p>
									<div class="listing_area">
										<p class="listing_area_ttl">出品エリア</p>
										<p>
											@if($v['senpai']['area']['area_name'] != "")
												{{$v['senpai']['area']['area_name']}}
												{{$v['senpai']['senpai_county'] != "" ? '／'.$v['senpai']['senpai_county'] : ''}}{{$v['senpai']['senpai_village'] != '' ? '／'.$v['senpai']['senpai_village'] : ''}}{{$v['senpai']['senpai_mansyonn'] != '' ? '／'.$v['senpai']['senpai_mansyonn'] : ''}}
											@endif
										</p>
									</div>
									<ul class="icon_area">
										<li><p class="icon_img"><img src="{{asset('storage/class_icon/'.$v['lesson_class']['class_icon'])}}" alt="{{$v['lesson_class']['class_name']}}"></p></li>
										<li>@if(intval($v['fav_count']) > 0)<span class="heart">{{$v['fav_count']}}</span>@endif</li>
									</ul>
								</div>
							</a>
						</li>
					@endforeach
				</ul>
			</div><!-- /tab_content -->


			<!-- ********************************************************* -->

			<div class="tab_content" id="tab-02_content">
				<ul class="lesson_list_wrap">
					@php
						$lesson_list = $lesson_list2->toArray()['data'];
					@endphp
					@foreach($lesson_list as $k=>$v)
						<li class="lesson_box">
							<a href="{{route('user.syutupinn.add')}}/{{$v['lesson_id']}}">
								<div class="img-box">
									@if($v['lesson_state'] == 0)
										<span class="status shitagaki">下書き</span>
									@endif
									@if($v['lesson_state'] == 1)
										<span class="status">非公開</span>
									@endif
									@if($v['lesson_state'] == 2)
									    <span class="status-check">審査中</span>
									@endif
									@if($v['lesson_state'] == 4)
									 	<span class="status-bad">非承認</span>
									@endif
									@php
										$pic_arr = explode(',', $v['lesson_image']);
									@endphp
									<img src="{{asset('storage/lesson/'.$pic_arr[0])}}" alt="イメージ">
								</div>
								<div class="lesson_info_box">
									<p class="lesson_name info_ttl">{{$v['lesson_title']}}</p>
									<div class="listing_area">
										<p class="listing_area_ttl">出品エリア</p>
										<p>
											@if($v['senpai']['area']['area_name'] != "")
												{{$v['senpai']['area']['area_name']}}
												{{$v['senpai']['senpai_county'] != "" ? '／'.$v['senpai']['senpai_county'] : ''}}{{$v['senpai']['senpai_village'] != '' ? '／'.$v['senpai']['senpai_village'] : ''}}{{$v['senpai']['senpai_mansyonn'] != '' ? '／'.$v['senpai']['senpai_mansyonn'] : ''}}
											@endif
										</p>
									</div>
									<ul class="icon_area">
										<li><p class="icon_img"><img src="{{asset('storage/class_icon/'.$v['lesson_class']['class_icon'])}}" alt="{{$v['lesson_class']['class_name']}}"></p></li>
										<li>@if(intval($v['fav_count']) > 0)<span class="heart">{{$v['fav_count']}}</span>@endif</li>
									</ul>
								</div>
							</a>
						</li>
					@endforeach
				</ul>
			</div><!-- /tab_content -->


			<!-- ********************************************************* -->

			<div class="tab_content" id="tab-03_content">
				<ul class="lesson_list_wrap">
					@php
						$lesson_list = $lesson_list3->toArray()['data'];
					@endphp
					@foreach($lesson_list as $k=>$v)
						<li class="lesson_box">
							<a href="{{route('user.syutupinn.add')}}/{{$v['lesson_id']}}">
								<div class="img-box">
									@if($v['lesson_state'] == 0)
										<span class="status shitagaki">下書き</span>
									@endif
									@if($v['lesson_state'] == 1)
										<span class="status">非公開</span>
									@endif
									@if($v['lesson_state'] == 2)
										<span class="status-check">審査中</span>
									@endif
									@if($v['lesson_state'] == 4)
										<span class="status-bad">非承認</span>
									@endif

									@php
										$pic_arr = explode(',', $v['lesson_image']);
									@endphp
									<img src="{{asset('storage/lesson/'.$pic_arr[0])}}" alt="イメージ">
								</div>
								<div class="lesson_info_box">
									<p class="lesson_name info_ttl">{{$v['lesson_title']}}</p>
									<div class="listing_area">
										<p class="listing_area_ttl">出品エリア</p>
										<p>
											@if($v['senpai']['area']['area_name'] != "")
												{{$v['senpai']['area']['area_name']}}
												{{$v['senpai']['senpai_county'] != "" ? '／'.$v['senpai']['senpai_county'] : ''}}{{$v['senpai']['senpai_village'] != '' ? '／'.$v['senpai']['senpai_village'] : ''}}{{$v['senpai']['senpai_mansyonn'] != '' ? '／'.$v['senpai']['senpai_mansyonn'] : ''}}
											@endif
										</p>
									</div>
									<ul class="icon_area">
										<li><p class="icon_img"><img src="{{asset('storage/class_icon/'.$v['lesson_class']['class_icon'])}}" alt="{{$v['lesson_class']['class_name']}}"></p></li>
										<li>@if(intval($v['fav_count']) > 0)<span class="heart">{{$v['fav_count']}}</span>@endif</li>
									</ul>
								</div>
							</a>
						</li>
					@endforeach
				</ul>
			</div><!-- /tab_content -->


		</div><!-- /tabs -->

	</div><!-- /contents -->

	<div class="rb_btn_area">
		<a href="{{ route('user.syutupinn.add') }}">
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

	<script>
        var pageCount1 = parseInt($('#pages1').val());
        var pageCount2 = parseInt($('#pages2').val());
        var pageCount3 = parseInt($('#pages3').val());
        var pageCurrent1 = 1;
        var pageCurrent2 = 1;
        var pageCurrent3 = 1;
        var isLoading = false;
        var cur_tab = 1;
        $(document).ready(function () {
            $('#tab-01').click(function () {
                cur_tab = 1;
            });
            $('#tab-02').click(function () {
                cur_tab = 2;
            });
            $('#tab-03').click(function () {
                cur_tab = 3;
            });
            $(window).scroll(function(){
                var obj;
				if(cur_tab == 1) {
				    obj = $("#tab-01_content");
                }else if(cur_tab == 2) {
				    obj = $("#tab-02_content");
                }else if(cur_tab == 3) {
				    obj = $("#tab-03_content");
                }
                var totalHeight = document.documentElement.scrollHeight - $(obj).outerHeight(true);
                var clientHeight = document.documentElement.clientHeight;
                var scrollTop = (document.body && document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;
                // get more app by scrolling
                if ((totalHeight == scrollTop + clientHeight) || (totalHeight <= scrollTop + clientHeight + 3)) {
                    if (isLoading) return;
                    showList(cur_tab);
                }
                return;
            });
        });

        function showList(cur_tab) {
            if(cur_tab == 1) {
                if(pageCount1 <= 1) return;
                if(pageCount1 <= pageCurrent1) {return;}
                pageCurrent1++;
                getAjaxList(cur_tab, pageCurrent1);
            }
            if(cur_tab == 2) {
                if(pageCount2 <= 1) return;
                if(pageCount2 <= pageCurrent2) return;
                pageCurrent2++;
                getAjaxList(cur_tab, pageCurrent2);
            }
            if(cur_tab == 3) {
                if(pageCount3 <= 1) return;
                if(pageCount3 <= pageCurrent3) return;
                pageCurrent3++;
                getAjaxList(cur_tab, pageCurrent3);
            }
        }
        function getAjaxList(tag, curPage){
            isLoading = true;
            $.ajax({
                type: "get",
                url: "{{route('user.syutupinn.ajax_list')}}" + "/" + tag + "?page=" + curPage,
                dataType: 'json',
                contentType: false,
                processData : false,
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
                            if(row['lesson_state'] == 0){
                                state_info = '<span class="status shitagaki">下書き</span>';
                            }else if(row['lesson_state'] == 1){
                                state_info = '<span class="status">非公開</span>';
                            }else if(row['lesson_state'] == 2){
                                state_info = '<span class="status-check">審査中</span>';
                            }else if(row['lesson_state'] == 4){
                                state_info = '<span class="status-bad">非承認</span>';
                            }
                            var hart_info = '';
                            if(parseInt(row['fav_count']) > 0){
                                hart_info = '<span class="heart">' + row['fav_count'] + '</span>';
							}
							var first_pic = row['lesson_image'].split(',');
                            str_html += '<li class="lesson_box">' +
                                '<a href="{{route('user.syutupinn.add')}}/'+row['lesson_id']+'">' +
                                '<div class="img-box">' + state_info +
                                '<img src="{{asset('storage/lesson')}}/'+first_pic[0]+'" alt="イメージ">' +
                                '</div>' +
                                '<div class="lesson_info_box">' +
                                '<p class="lesson_name info_ttl">'+row['lesson_title']+'</p>' +
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
                                '</li>'
                        }
                        if(tag == 1) { $('#tab-01_content ul.lesson_list_wrap').append(str_html);}
                        if(tag == 2) { $('#tab-02_content ul.lesson_list_wrap').append(str_html);}
                        if(tag == 3) { $('#tab-03_content ul.lesson_list_wrap').append(str_html);}
                        isLoading = false;
                        setAbridgedTxt();
                    }
                }
            });
        }
	</script>
@endsection
