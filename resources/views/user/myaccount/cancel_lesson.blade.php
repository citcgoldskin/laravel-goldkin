@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents" class="superlong">

    <!--main_-->
    {{ Form::open(["route"=>"user.myaccount.cancel_lesson_success", "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
    <input type="hidden" name="schedule_id" value="{{ $schedule_info['lrs_id'] }}">

        <section class="type_summary">

            <div class="inner_box">
                <h3>キャンセルするレッスン</h3>
                <div class="white_box">
                    <ul class="list_box">
                        <li>
                            <ul class="reserved_top_box lesson_summary">
                                @php
                                    $lesson_image = NULL;
                                    if ( isset($schedule_info['lesson_request']['lesson']['lesson_image']) && is_array(unserialize($schedule_info['lesson_request']['lesson']['lesson_image']))) {
                                        $lesson_image = unserialize($schedule_info['lesson_request']['lesson']['lesson_image'])[0];
                                    }
                                @endphp
                                <li><img src="{{ \App\Service\CommonService::getLessonImgUrl($lesson_image) }}" alt=""></li>
                                <li>
                                    <p class="lesson_ttl">{{ $schedule_info['lesson_request']['lesson']['lesson_title'] }}</p>
                                </li>
                            </ul>
                        </li>

                        <li>
                            <div>
                                <p>売上金（目安）</p>
                                <p class="price_mark f16"><strong>{{ \App\Service\CommonService::showFormatNum($schedule_info['lrs_amount']) }}</strong></p>
                            </div>
                        </li>

                        <li class="lesson_naiyou gray_txt">
                            <div>
                                <p>レッスン日時：</p>
                                <p>{{ \App\Service\CommonService::getYMD($schedule_info['lrs_date']) }}
                                    {{ \App\Service\CommonService::getStartAndEndTime($schedule_info['lrs_start_time'], $schedule_info['lrs_end_time']) }}</p>
                            </div>
                        </li>

                    </ul>
                </div>
            </div>

            <div class="inner_box">
                <h3 class="must">キャンセルの理由（複数選択可）</h3>
                @error('commitment')
                <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
                <div class="white_box">
                    <div class="check-box">
                        @foreach( $cancel_reason_types as $key => $value)
                        <div class="clex-box_02">
                            <input type="checkbox" name="commitment[]" value="{{ $value['crt_id'] }}" id="{{ 'c' . $value['crt_id'] }}"
                                   @if ( $value['crt_id'] == config('const.senpai_cancel_reason_id') ) class= "click-balloon" onclick="showBalloon()" @endif
                                   @if ( is_array(old('commitment')) ) @if ( in_array($value['crt_id'], old('commitment')) ) checked @endif @endif>
                            <label for="{{ 'c' . $value['crt_id'] }}"><p>{{ $value['crt_content'] }}</p></label>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="balloon_area" id="makeImg">
                <div class="balloon balloon_white">
                    <textarea placeholder="キャンセルの理由を100字以内でご記入ください。" cols="50" rows="10" maxlength="100" name="other_reason"></textarea>
                    @error('other_reason')
                    <p class="error_text"><strong>{{ $message }}</strong></p>
                    @enderror
                </div>
            </div>
            <p class="form_txt gray_txt fs-13">
                ※キャンセルの理由は後輩に通知されます
            </p>


        </section>

        <div id="footer_button_area" class="under_area result">
            <ul>
                <li>
                    <div class="btn_base btn_white clear_btn"><a onclick="location.href='{{ route('user.myaccount.master_lesson_request', ['schedule_id' => $schedule_info['lrs_id']]) }}';">戻る</a></div>
                </li>
                <li>
                    <div class="btn_base btn_orange"><button type="submit">キャンセルを確定する</button></div>
                </li>
            </ul>
        </div>

    {{ Form::close() }}

</div><!-- /contents -->

<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection

@section('page_js')
<script>
    $(document).ready(function () {
        @if ( is_array(old('commitment')) )
            @if ( in_array(config('const.senpai_cancel_reason_id'), old('commitment')) )
            showBalloon();
            @endif
        @endif
    })
</script>
@endsection
