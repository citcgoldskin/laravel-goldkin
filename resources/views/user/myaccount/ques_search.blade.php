@extends('user.layouts.app')
@section('title', '検索結果')

@section('content')
@include('user.layouts.header_under')
<div id="contents">
    {{ Form::open(["method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
        <section id="faq-top">
            @include('user.layouts.ques_search')

            <h2 class="faq_ttl">“{{$keyword}}”
                <span>検索結果：</span><big>{{$total_count}}</big><span>件</span>
            </h2>
        </section>


        <section id="question">

            @foreach($questions as $key=>$val)
                <div class="form_wrap icon_form type_arrow_right shadow-glay mb15">
                    <button type="button" onClick="location.href='{{route("user.myaccount.ques_detail", ['id'=>$val['que_id'], 'page_from'=>'question_search', 'keyword'=>$keyword])}}'" class="form_btn">{{$val['que_ask']}}</button>
                </div>
            @endforeach

        </section>

    @if($questions instanceof \Illuminate\Pagination\LengthAwarePaginator )
        {{ $questions->links('vendor.pagination.senpai-pagination') }}
    @endif

    {{ Form::close() }}
</div><!-- /contents -->

<footer>
    @include('user.layouts.fnavi')
</footer>
@endsection

