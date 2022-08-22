@extends('user.layouts.app')
@section('title', 'リクエストのキャンセル')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
    @include('user.layouts.header_under')

    <div id="contents">
    <!--main_-->
    {{ Form::open(["route" => "user.talkroom.cancelConfirm", "method" => "post", "name" => "form1", "id" => "form1"]) }}

    <input type="hidden" name="req_id" value="{{ $req_info['lr_id'] }}">
        <section>

            <div class="inner_box">
                <h3>キャンセルするリクエストを選択してください。（複数選択可）</h3>
                @error('cancel_list')
                <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
                <div class="white_box">
                    <div class="check-box">
                        @foreach( $req_info['lesson_request_schedule'] as $key => $value )
                        <div class="clex-box_02">
                            <input type="checkbox" name="cancel_list[]" value="{{ $value['lrs_id'] }}" id="{{ $value['lrs_id'] }}">
                            <label for="{{ $value['lrs_id'] }}">
                                <p>
                                    <strong>{{ date('Y', strtotime($value['lrs_date'])) }}</strong>年
                                    <strong>{{ date('n', strtotime($value['lrs_date'])) }}</strong>月
                                    <strong>{{ date('j', strtotime($value['lrs_date'])) }}</strong>日
                                    （{{ \App\Service\CommonService::$week_arr[date('w', strtotime($value['lrs_date']))] }}）
                                    　<strong>{{ \App\Service\CommonService::getStartAndEndTime($value['lrs_start_time'], $value['lrs_end_time']) }}</strong>
                                </p>
                            </label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>


        </section>

        <div class="white-bk">
            <div class="button-area">
                <div class="btn_base btn_orange shadow-orange shadow">
                    <button type="submit">キャンセル内容を確認</button>
                </div>
            </div>
        </div>

    {{ Form::close() }}

</div><!-- /contents -->
    @include('user.layouts.modal')

    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection


