@extends('user.layouts.app')

@section('title', $class_name)

@php
    use App\Service\SenpaiService;
    use App\Service\LessonService;
    use App\Service\CommonService;
@endphp

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

        <!--main_-->
        {{ Form::open(['route' => ['user.lesson.set_search_order'], "name"=>"form1", "id"=>"form1", "method"=>"post"]) }}

        <div class="top-menu_wrap">

            @section('province_name', $province_name)
            @include('user.layouts.top_menu_location_list')

            <div class="top-menu ">

                <p class="conditions_wrap sort">
                    　
                    <button type="button"
                            onclick="location.href='{{route('user.lesson.search_condition', ['lesson_count' => $lesson_count, 'province_id' => $province_id])}}'">
                        条件で絞り込む
                    </button>
                </p>
                <nav>
                    <ul class="conditions_box">
                        <li class="attendance_wrap">
                            @if(isset($is_attend) && $is_attend == 0)
                                <input type="hidden" name="is_attend" id="is_attend" value="0">
                            @else
                                <input type="hidden" name="is_attend" id="is_attend" value="1">
                            @endif
                            <p class="change_btn">出勤ありのみ表示</p></li>
                        <li>
                            <div class="form_wrap icon_form type_arrow_bottom pop height-in">
                                @php
                                    $order_arr = array('1' => '人気順', '2' => '単価の安い順', '3' => '単価の高い順', '4' => '出勤日の多い順', '5' => '取引件数順', '6' => '新着順', '7' => 'お気に入りの多い順');
                                @endphp
                                <select id="order_type" name="order_type">
                                    @foreach($order_arr as $key => $value)
                                        @if(isset($order_type) && $order_type == $key)
                                            <option selected='selected' value="{{$key}}">{{$value}}</option>
                                        @else
                                            <option value="{{$key}}">{{$value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>

        <!--main_visual end-->

        <section class="pt20">
            <h3 class="search_result_ttl">検索結果 <span>{{CommonService::showFormatNum($lesson_count)}}</span>件</h3>
            <ul class="lesson_list_wrap">
                @foreach($lesson_pages as $k => $v)
                    <li class="lesson_box">
                        <a href="{{route('user.lesson.lesson_view', ['lesson_id' => $v['lesson_id']])}}">
                            <div class="img-box">
                                @php
                                    $lesson_image = LessonService::getLessonFirstImage($v);
                                @endphp
                                <img src="{{ CommonService::getLessonImgUrl($lesson_image) }}" alt="ウォーキング画像">
                                <p>
                                    {{$v['lesson_class']['class_name']}}
                                </p>
                            </div>
                            <div class="lesson_info_box">
                                <p class="lesson_name">
                                    {{$v['lesson_title']}}
                                </p>
                                <p class="lesson_price">
                                    <em>{{CommonService::showFormatNum($v['lesson_30min_fees'])}}</em><span>円 / <em>30</em>分〜</span>
                                </p>
                                <div class="teacher_name">
                                    <div class="icon_s30"><img
                                            src="{{ CommonService::getUserAvatarUrl($v['senpai']['user_avatar']) }}"
                                            alt=""></div>
                                    <div>{{$v['senpai']['name']}}
                                        （<em>{{CommonService::getAge($v['senpai']['user_birthday'])}}</em>）
                                    </div>
                                </div>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>

        </section>

        @if($lesson_pages instanceof \Illuminate\Pagination\LengthAwarePaginator )
            {{ $lesson_pages->links('vendor.pagination.senpai-pagination') }}
        @endif
        {{ Form::close()}}

    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

<script>

</script>
