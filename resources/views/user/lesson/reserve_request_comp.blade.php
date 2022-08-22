@include('user.layouts.app')

<!-- ************************************************************************
本文
************************************************************************* -->

<section>
    <h2 class="request_ttl">以下の内容で予約リクエストを送信しました。</h2>
    <div class="inner_box">
        <ul class="list_area icon_check">
            @if(is_array($reserve_time))
                @foreach($reserve_time as $key => $value)
                    <li class="icon_blue">
                        <strong>{{$value['year']}}</strong>年
                        <strong>{{$value['month']}}</strong>月
                        <strong>{{$value['day']}}</strong>日（{{$value['date']}}）　
                        <strong>{{$value['start_time']}}~{{$value['end_time']}}</strong>
                    </li>
                @endforeach
            @endif
        </ul>
        <div class="balloon balloon_blue">
            <p>リクエスト内容を変更する場合や、あなたのリクエストに先輩から返信があった場合は、トークルームやマイページから確認できます。</p>
        </div>
    </div>
</section>

<div class="button-area">
    <div class="btn_base btn_orange shadow"><a href="{{route('user.talkroom.list')}}">トークルームを確認する</a></div>
    <div class="btn_base btn_white shadow"><a href="{{route('home')}}">ホームへ戻る</a></div>
</div>

@include('user.layouts.footer')

