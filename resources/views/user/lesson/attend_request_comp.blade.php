@include('user.layouts.app')

@php
    use App\Service\CommonService;
@endphp
<!-- ************************************************************************
本文
************************************************************************* -->

<section>
    <h2 class="request_ttl">以下の内容で出勤リクエストを送信しました。</h2>

    <div class="inner_box">
        <h3>希望レッスン時間</h3>
        <div class="white_box">
            <p class="time_txt ptb0">
                <span class="big_time">{{$data['hope_mintime']}}</span> 分 ～
                <span class="big_time">{{$data['hope_maxtime']}}</span> 分
        </div>
    </div>

    <div class="pt0">
        <h3>希望日時</h3>
        <ul class="list_area icon_check">
            @foreach($data['attend_time'] as $key => $value)
                <li class="icon_blue">
                    <strong>{{date('Y', strtotime($value['date']))}}</strong>年
                    <strong>{{date('n', strtotime($value['date']))}}</strong>月
                    <strong>{{date('j', strtotime($value['date']))}}</strong>日（{{CommonService::getWeekday($value['date'])}}
                    ）　
                    <strong>{{$value['time']}}</strong>
                </li>
            @endforeach
        </ul>
    </div>

    <div class="balloon balloon_blue">
        <p>リクエスト内容を変更する場合や、あなたのリクエストに先輩から返信があった場合は、トークルームやマイページから確認できます。</p>
    </div>
</section>

<div class="button-area type_under2">
    <div class="btn_base btn_orange shadow"><a href="{{route('user.talkroom.list')}}">トークルームを確認する</a></div>
    <div class="btn_base btn_white shadow-glay"><a href="{{route('home')}}">ホームへ戻る</a></div>
</div>



