@extends('user.layouts.app')

@section('title', 'お知らせ')

@section('content')

    @include('user.layouts.header_info2')

    <div id="contents">
		<div class="tabs info_wrap">
			<div class="stick">
              <input id="tab-01" type="radio" name="tab_item" checked="checked">
              <label class="tab_item info" for="tab-01">あなた宛</label>
              <input id="tab-02" type="radio" name="tab_item">
              <label class="tab_item info" for="tab-02">ニュース</label>
		    </div>

	<!-- ********************************************************* -->

	 <div class="tab_content" id="tab-01_content">
		  <ul class="info_list" id="notice_list">
			  @foreach($notice as $key => $value)
			   <li>
				<p class="date">{{$value['msg_date']}}</p>
				<p>{{$value['msg_content']}}</p>
			   </li>
			  @endforeach
		  </ul>
		 <input type="hidden" id="notice_pages" value="{{$notice_pages}}">
	 </div>
	<!-- ********************************************************* -->

	 <div class="tab_content" id="tab-02_content">
		  <ul class="info_list" id="news_list">
			  @foreach($news as $key => $value)
				  <li>
					  <p class="date">{{$value['msg_date']}}</p>
					  <p>{{$value['msg_content']}}</p>
				  </li>
			  @endforeach
		  </ul>
		 <input type="hidden" id="news_pages" value="{{$news_pages}}">
	  </div>
	</div>
	</div><!-- /contents -->

@endsection

@section('page_js')
	<script>
        $(document).ready(function () {
            $('#tab-01_content').show();
            $('#tab-02_content').hide();
        });

        var noticePageCount = parseInt($('#notice_pages').val());
        var newsPageCount = parseInt($('#news_pages').val());
        var isNoticeLoading = false, isNewsLoading = false;
        var noticePageCurrent = 1, newsPageCurrent = 1;

        $(window).scroll(function(){
            var totalHeight = document.documentElement.scrollHeight;// - $(".stick").outerHeight(true);
            var clientHeight = document.documentElement.clientHeight;
            var scrollTop = (document.body && document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;
            // get more app by scrolling
            if ((totalHeight == scrollTop + clientHeight) || (totalHeight <= scrollTop + clientHeight + 3)) {
                if(noticePageCount > noticePageCurrent){
                    if(!isNoticeLoading){
                        getMoreMessages('notice');
                    }
                }
                if(newsPageCount > newsPageCurrent){
                    if(!isNewsLoading){
                        getMoreMessages('news');
                    }
                }
            }
        });

        function getMoreMessages(msg_type)
        {
            var form_data = new FormData();
            var ajax_mode = msg_type + '_ajax';
            var PageCurrent;
            if(msg_type == 'notice'){
                noticePageCurrent++;
                isNoticeLoading = true;
                PageCurrent = noticePageCurrent;
            }else if(msg_type == 'news'){
                newsPageCurrent++;
                isNewsLoading = true;
                PageCurrent = newsPageCurrent;
            }
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("mode", ajax_mode);
            form_data.append("page", PageCurrent);
            $.ajax({
                type: "get",
                url: "{{route('user.notice')}}" + "/" + ajax_mode + "?page=" + PageCurrent,
                dataType: 'json',
                contentType: false,
                processData : false,
                success : function(result)
                {
                    var $message = result.message;
                    appendMessages($message, msg_type);
                }
            });
        }

        function appendMessages(data, msg_type)
        {
            var html = "";
            for(var key in data)
            {
                var message = data[key];
                html += '<li>';
                html += '<p class="date">' + message.msg_date + '</p>';
                html += '<p>' + message.msg_content + '</p>';
                html += '</li>';
            }
            if(msg_type == 'notice'){
                $('#notice_list').append(html);
                isNoticeLoading = false;
            }else if(msg_type == 'news'){
                $('#news_list').append(html);
                isNewsLoading = false;
            }

        }
	</script>
@endsection
