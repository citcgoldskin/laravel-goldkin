@extends('user.layouts.app')

@section('title', 'やることリスト')

@section('content')

    @include('user.layouts.header_info2')

    <div id="contents">

    <div class="list-area">

        <ul class="todo_list">
            @foreach($data as $key => $value)
                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{$value['user_avatar']}}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                @if($value['user_type'] == 'コウハイ')
                                    <div class="color-kouhai">{{$value['user_type']}}</div>
                                @else
                                    <div class="color-senpai">{{$value['user_type']}}</div>
                                @endif
                                <div>{{$value['msg_date']}}</div>
                            </div>
                            <p>{{$value['msg_content']}}</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
    <input type="hidden" id="pages" value="{{$pages}}">
</div><!-- /contents -->

@endsection

@section('page_js')
    <script>
        var pageCount = parseInt($('#pages').val());
        var isLoading = false;
        var pageCurrent = 1;
        $(window).scroll(function(){
            if(pageCount <= 1) return;
            if(pageCount <= pageCurrent) return;

            var totalHeight = document.documentElement.scrollHeight;// - $("#f-navi").outerHeight(true);
            var clientHeight = document.documentElement.clientHeight;
            var scrollTop = (document.body && document.body.scrollTop) ? document.body.scrollTop : document.documentElement.scrollTop;
            // get more app by scrolling
            if ((totalHeight == scrollTop + clientHeight) || (totalHeight <= scrollTop + clientHeight + 3)) {
                if (isLoading) return;
                getMoreMessages();
            }
        });

        function getMoreMessages()
        {
            pageCurrent++;
            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("mode", "ajax");
            form_data.append("page", pageCurrent);
            isLoading = true;
            $.ajax({
                type: "get",
                url: "{{route('user.todo')}}" + "/ajax" + "?page=" + pageCurrent,
                dataType: 'json',
                contentType: false,
                processData : false,
                success : function(result)
                {
                    var $message = result.message;
                    appendMessages($message);
                }
            });
        }

        function appendMessages(data)
        {
            var html = "";
            for(var key in data)
            {
                var message = data[key];
                html += '<li><a href="">';
                html += '<div class="icon-area">';
                html += '<img src="' + message.user_avatar + '" alt="やることリストアイコン">';
                html += '</div>';
                html += '<div class="text-area">';
                html += '<div class="text-small">';
                if(message.user_type == 'コウハイ'){
                    html += '<div class="color-kouhai">';
                }else{
                    html += '<div class="color-senpai">';
                }
                html += message.user_type + '</div>';
                html += '<div>' + message.msg_date + '</div>';
                html += '</div>';
                html += '<p>' + message.msg_content + '</p>';
                html += '</div>';
                html += '</a></li>';
            }
            $('.todo_list').append(html);
            isLoading = false;
        }
    </script>
@endsection
