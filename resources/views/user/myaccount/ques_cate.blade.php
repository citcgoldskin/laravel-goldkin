@extends('user.layouts.app')
@section('title', 'よくある質問')

@section('content')
@include('user.layouts.header_under')
<div id="contents">
    {{ Form::open(["route"=>"user.myaccount.ques_search", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}

        <section id="faq-top">
            @include('user.layouts.ques_search')
        </section>


        <section>

            <h3 class="txt_center fs-16">お困りの項目を選択してください</h3>
            <ul class="komaru_list">
                @foreach($classes as $key=>$val)
                    <li class="shadow-glay"><a href="{{route('user.myaccount.ques_cate_small').'/'.$val['qc_id']}}">{{$val['qc_name']}}</a></li>
                @endforeach
            </ul>
        </section>


        <section id="question">

            <h3 class="fs-16">よく見られてる質問</h3>
            @foreach($questions as $key=>$val)
                <div class="form_wrap icon_form type_arrow_right shadow-glay mb15">
                    <button type="button" onClick="location.href='{{route("user.myaccount.ques_detail", ['id'=>$val['que_id'], 'page_from'=>'parent'])}}'" class="form_btn">{{$val['que_ask']}}</button>
                </div>
            @endforeach
        </section>

    {{ Form::close() }}
</div><!-- /contents -->
<footer>
    @include('user.layouts.fnavi')
</footer>

@endsection

