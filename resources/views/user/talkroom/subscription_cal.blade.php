@extends('user.layouts.app')
@section('title', '予約カレンダー')
@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

        <!--main_-->
        <form action="./" method="post" name="form1" id="form1">
            <div class="no-space01 white_box">
                <div class="calendar-area calendar-border">
                    <div class="date-area">
                        <ul>
                            <li><a href="{{ route('user.talkroom.subscription_cal', [$year_pagination['previous']]) }}">前月</a></li>
                            <li>{{ $year_pagination['current_label'] }}</li>
                            <li><a href="{{ route('user.talkroom.subscription_cal', [$year_pagination['next']]) }}">翌月</a></li>
                        </ul>
                    </div>
                    <table border="1">
                        <tr>
                            <th>日</th>
                            <th>月</th>
                            <th>火</th>
                            <th>水</th>
                            <th>木</th>
                            <th>金</th>
                            <th>土</th>
                        </tr>
                    @foreach($year_pagination['calendar'] as $k => $v)
                         <tr>
                            @for( $i = 0 ; $i <= 6; $i++)
                            <td>
                                @if (isset($v[$i]))
                                    {{ $v[$i] }}
                                    <p class="cnumber-flex">
                                        <span class="calendar-number-01">1</span>
                                        <span class="calendar-number-02">2</span>
                                    </p>
                                @endif
                            </td>
                            @endfor
                         </tr>
                    @endforeach
                    </table>
                </div>
                <div class="base_txt mt20 mb20 ml20">
                    <p><span class="color01">●</span>…自分がコウハイとして受講する予約</p>
                    <p><span class="color02">●</span>…自分がセンパイとして実施する予約</p>
                </div>

            </div>
            <section>
                <div class="inner_box border-box">
                    <h3 class="mb20">4月21日（水）</h3>
                    <div class="trainer-box">
                        <div class="time-box bar01">
                            <p class="en-color01">15:00</p>
                            <p class="en-color01">16:00</p>
                        </div>
                        <div class="trainer-name shadow-glay">
                            <a href="D-4.php">
                                <ul>
                                    <li><img src="{{ asset('assets/user/img/icon_photo_04.png') }}"></li>
                                    <li>森田プロトレーナー</li>
                                </ul>
                                <span class="icon_trainer icon_attendance">受講</span>
                            </a>
                        </div>
                    </div>
                    <div class="trainer-box">
                        <div class="time-box bar02">
                            <p class="en-color02">16:30</p>
                            <p class="en-color02">16:00</p>
                        </div>
                        <div class="trainer-name shadow-glay">
                            <a href="D-3.php">
                                <ul>
                                    <li><img src="{{ asset('assets/user/img/icon_photo_05.png') }}"></li>
                                    <li>名前</li>
                                </ul>
                                <span class="icon_trainer icon_lesson">レッスン</span>
                            </a>
                        </div>
                    </div>

                </div>
            </section>
        </form>
    </div>
    <!-- /contents -->

    @include('user.layouts.modal')

    <footer>
       @include('user.layouts.fnavi')
    </footer>

@endsection
