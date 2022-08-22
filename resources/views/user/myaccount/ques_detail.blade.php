@extends('user.layouts.app')
@section('title', '検索結果')

@section('content')
@include('user.layouts.header_under')
<div id="contents">
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        <section id="question">

            <div class="faq_wrap faq-area">
                <div class="q_box">
                    {{$question['que_ask']}}
                </div>

                <div class="a_box">
                    {!! nl2br($question['que_answer']) !!}
                    {{--<textarea class="no_edit_textarea" readonly>{{ $question['que_answer'] }}</textarea>--}}
                </div>
            </div>

            <div class="button_area">
                <div class="btn_base btn_white clear_btn shadow-glay">
                    @if(isset($page_from) && $page_from == 'parent')
                        <a href="{{ route('user.myaccount.ques_cate') }}">戻る</a>
                    @elseif(isset($page_from) && $page_from == 'category')
                        <a href="{{ route('user.myaccount.ques_cate_small', ['id'=>$question['category_id']]) }}">戻る</a>
                    @elseif(isset($page_from) && $page_from == 'question_search')
                        <a href="{{ route('user.myaccount.ques_search', ['id'=>$question['category_id'], 'keyword'=>isset($keyword) ? $keyword : '']) }}">戻る</a>
                    @else
                        <a href="{{ route('user.myaccount.ques_list', ['id'=>$question['que_qc_id']]) }}">戻る</a>
                    @endif
                </div>
            </div>

        </section>

    {{ Form::close() }}
</div><!-- /contents -->

<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

