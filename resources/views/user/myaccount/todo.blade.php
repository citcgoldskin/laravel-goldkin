@extends('user.layouts.app')
@section('title', 'やることリスト')
@section('content')
@include('user.layouts.header_info')
<div id="contents">

    <div class="list-area">

        <ul class="todo_list">
            @foreach($message as $key => $value)
                <li>
                    <a href="">
                        <div class="icon-area">
                            <img src="{{asset(\App\Service\CommonService::getUserAvatarUrl($value['user']['user_avatar']))}}" alt="やることリストアイコン">
                        </div>
                        <div class="text-area">
                            <div class="text-small">
                                <div class="color-kouhai">{{$value['msg_tpl']['mt_name']}}</div>
                                <div>{{$value['msg_date']}}</div>
                            </div>
                            <p>{{$value['msg_content']}}</p>
                        </div>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

</div><!-- /contents -->
@endsection
