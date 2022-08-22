@extends('user.layouts.app')

@section('title', 'リクエスト中のレッスン')

@section('$page_id', 'home')

@section('content')

    @include('user.layouts.header_under')
    @php
        use App\Service\CommonService;
    @endphp

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

      <!--main_-->
    @if(count($request_list))
        {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

            <section>

                <div class="inner_box">
                    <ul class="list_area2">
                        @foreach($request_list as $k=>$v)
                            <li class="icon_form type_arrow_right">
                                @php
                                    if ( $v['lrs_state'] == config('const.schedule_state.request') ) {
                                        $href = route('user.talkroom.requestConfirm',['request_id'=>$v['lesson_request']['lr_id']]);
                                    } elseif ( $v['lrs_state'] == config('const.schedule_state.confirm') ) {
                                        $href = route('user.lesson.check_reserve',['lrs_id'=>$v['lrs_id']]);
                                    } else {
                                        $href = route('user.talkroom.list');
                                    }
                                @endphp
                                <a href="{{$href}}">{{CommonService::getYMD($v['lrs_date'])}} {{CommonService::getStartAndEndTime($v['lrs_start_time'], $v['lrs_end_time'])}}　{{CommonService::showFormatNum($v['lrs_amount'])}}円</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        {{ Form::close() }}
    @endif


    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection
