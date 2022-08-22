@extends('user.layouts.app')
@section('title', '売上金の振込申請期限')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <!--main_-->
    {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

        <section>

            <div class="inner_box">
                <div class="white_box uriage_list">
                    <ul class="list_box">
                        @foreach($period_application as $key=>$application)
                            <li>
                                <div>
                                    <p>{{ $key }}<small>まで</small></p>
                                    <p class="price_mark">{{ number_format($application) }}</p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="kome_txt gray_txt">
                    <p class="mark_left mark_kome">
                        振込申請期限は売上の発生月を含めて６ヶ月間です。申請期限を過ぎた場合、自動で振込みされます。<br>
                        但し、銀行口座を登録されていない場合、売上金は消滅します。
                    </p>
                </div>

            </div>

        </section>

    {{ Form::close() }}

</div><!-- /contents -->


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
