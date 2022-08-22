@extends('admin.layouts.app')
@section('title', 'ホーム')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['no_action'=>1])

        {{ Form::open(["route"=>"admin.lesson_history_management.lesson", "method"=>"get", "name"=>"frm_history", "id"=>"frm_history"]) }}
        <input type="hidden" name="clear_condition" id="clear_condition" value="">

        <div class="tabs form_page">

            <!--main_-->
            <div id="main_visual">
                @php $home_main_img = \App\Service\SettingService::getSetting('home_main_visual', 'string') ; @endphp
                <img src="{{ asset('storage/home/'.$home_main_img) }}" alt="センパイイメージ画像">
            </div>
            <!--main_visual end-->

            <h2 class="none_text">センパイ　SENPAI</h2>
            @if(count($class_list))
                <section id="section_01">
                    <h3 class="fs-16">カテゴリー</h3>
                    <div class="top_category">
                        <ul>
                            @foreach($class_list as $k => $v)
                                <li>
                                    {{--<a href="{{route('user.lesson.search', ['class_id' => $v['class_id']])}}">--}}
                                    <a href="">
                                        <img src="{{\App\Service\CommonService::getLessonClassImgUrl($v['class_image'])}}" alt="カテゴリー画像">
                                        <p>@php echo str_replace('・','<br>',$v['class_name']);@endphp</p>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </section>
            @endif



        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>

    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {

        });
    </script>
@endsection
