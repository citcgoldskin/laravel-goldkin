@extends('user.layouts.app')

@section('title', 'ホーム')

@section('content')

    @include('user.layouts.gnavi')
    <div id="contents">

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
                                <a href="{{route('user.lesson.search', ['class_id' => $v['class_id']])}}">
                                    <img src="{{\App\Service\CommonService::getLessonClassImgUrl($v['class_image'])}}" alt="カテゴリー画像">
                                    <p>@php echo str_replace('・','<br>',$v['class_name']);@endphp</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        <section class="slider_area">
            <h3 class="fs-16">おすすめのサービス</h3>
            <!-- Slider main container -->
            @if (count($recommend_list) > 0)
            <div class="swiper-container">
                <div class="recommend-inner">
                    <div class="recommend">
                        <ol class="swiper-wrapper recommend_list" style="box-sizing: border-box">
                            <!-- Slides -->
                        @foreach($recommend_list as $k=>$v)
                            <li class="swiper-slide">
                                <div class="swip_contents_block">
                                    <div class="lesson_box">
                                        <a href="{{ route('user.lesson.lesson_view', ['lesson_id' => $v['lesson_id']]) }}">
                                            <div class="img-box">
                                                @php
                                                    $pic_arr = \App\Service\CommonService::unserializeData($v['lesson_image']);
                                                @endphp
                                                <img src="{{count($pic_arr) > 0 ? \App\Service\CommonService::getLessonImgUrl($pic_arr[0]) : ''}}" alt="ウォーキング画像">
                                                <h4>{{$v['lesson_class']['class_name']}}</h4>

                                            </div>
                                            <div class="lesson_info_box">
                                                <p class="lesson_name ttl-block">{{$v['lesson_title']}}</p>
                                                <p class="lesson_price"><em>{{\App\Service\CommonService::showFormatNum($v['lesson_30min_fees'])}}</em><span>円 / <em>30</em>分〜</span></p>
                                                <div class="teacher_name">
                                                    <div><img src="{{ \App\Service\CommonService::getUserAvatarUrl($v['senpai']['user_avatar']) }}" alt=""></div>
                                                    <div>{{$v['senpai']['name']}}（<em>{{ \App\Service\CommonService::getAge($v['senpai']['user_birthday']) }}</em>）</div>

                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                        </ol>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination recommend-pagination"></div>

                        <!-- If we need navigation buttons -->
                    </div>
                </div>
            </div>
            @endif
        </section>

        <section class="slider_area">
            <h3 class="fs-16">お気に入りのセンパイ</h3>
            <!-- Slider main container -->
            @if(Auth::check() && $fav_senpais->count() > 0)
            <div class="swiper-container">
                <div class="favorite-inner">
                    <div class="favorite">
                        <ol class="swiper-wrapper favorite-senpai_list">
                            <!-- Additional required wrapper -->
                            <!-- Slides -->
                            @foreach($fav_senpais as $k=>$v)
                                <li class="swiper-slide">
                                    <div class="swip_contents_block">
                                        <div class="lesson_box no-shadow">
                                            <a href="{{route('user.myaccount.profile', ['user_id' =>$v->f_user->id])}}">
                                                <img src="{{ \App\Service\CommonService::getUserAvatarUrl($v->f_user->user_avatar) }}" alt="{{$v->f_user->name}}（{{ \App\Service\CommonService::getAge($v->f_user->user_birthday) }}）">
                                                <p>{{$v->f_user->name}}<span>（<em>{{ \App\Service\CommonService::getAge($v->f_user->user_birthday) }}</em>）</span></p>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                        <!-- If we need pagination -->
                        <div class="swiper-pagination favorite-pagination"></div>

                        <!-- If we need navigation buttons -->
                    </div>
                </div>
            </div>
            @endif
        </section>

        <section class="slider_area">
            <h3 class="fs-16">購入したサービス</h3>
            @if(Auth::check() && count($reserved_list))
            <!-- Slider main container -->
            {{--<div class="swiper-container">
                <div class="service-inner">
                    <div class="service_buy">
                        <ol class="swiper-wrapper service_list">
                            <!-- Slides -->
                            @foreach($reserved_list as $k=>$v)
                                <li class="swiper-slide">
                                    <div class="swip_contents_block">
                                        <div class="lesson_box">
                                            <a href="{{ route('user.lesson.lesson_view', ['lesson_id' => $v['lesson_id']]) }}">
                                                <div class="img-box">
                                                    @php
                                                        $pic_arr = \App\Service\CommonService::unserializeData($v['lesson_image']);
                                                    @endphp
                                                    <img src="{{count($pic_arr) > 0 ? \App\Service\CommonService::getLessonImgUrl($pic_arr[0]) : ''}}" alt="ウォーキング画像">
                                                    <h4>{{$v['lesson_class']['class_name']}}</h4>
                                                </div>
                                                <div class="lesson_info_box">
                                                    <p class="lesson_name ttl-block">{{$v['lesson_title']}}</p>
                                                    <p class="lesson_price"><em>{{\App\Service\CommonService::showFormatNum($v['lesson_30min_fees'])}}</em><span>円 / <em>30</em>分〜</span></p>
                                                    <div class="teacher_name">
                                                        <div><img src="{{ \App\Service\CommonService::getUserAvatarUrl($v['senpai']['user_avatar']) }}" alt=""></div>
                                                        <div>{{$v['senpai']['name']}}（<em>{{ \App\Service\CommonService::getAge($v['senpai']['user_birthday']) }}</em>）</div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ol>
                        <!-- If we need pagination -->
                        <!-- If we need navigation buttons -->
                    </div>
                </div>
            </div>--}}
            @endif
        </section>

        <section class="slider_area pt10">
            <h3 class="fs-16">閲覧したサービス</h3>
            <!-- Slider main container -->
            @if( Auth::check() && count($brows_list))
            <div class="swiper-container">
                <div class="service-inner">
                    <div class="service_browsing">
                        <ol class="swiper-wrapper service_list">
                            <!-- Slides -->
                            @foreach($brows_list as $k=>$val)
                                @php
                                    $v = $val[0];
                                @endphp
                                @if(is_object($v->lesson))
                                    <li class="swiper-slide">
                                        <div class="swip_contents_block">
                                            <div class="lesson_box">
                                                <a href="{{ route('user.lesson.lesson_view', ['lesson_id' => $v->lesson['lesson_id']]) }}">
                                                    <div class="img-box">
                                                        @php
                                                            $pic_arr = \App\Service\CommonService::unserializeData($v->lesson['lesson_image']);
                                                        @endphp
                                                        <img src="{{count($pic_arr) > 0 ? \App\Service\CommonService::getLessonImgUrl($pic_arr[0]) : ''}}" alt="ウォーキング画像">
                                                        <h4>{{$v->lesson['lesson_class']['class_name']}}</h4>

                                                    </div>
                                                    <div class="lesson_info_box">
                                                        <p class="lesson_name ttl-block">{{$v->lesson['lesson_title']}}</p>
                                                        <p class="lesson_price"><em>{{\App\Service\CommonService::showFormatNum($v->lesson['lesson_30min_fees'])}}</em><span>円 / <em>30</em>分〜</span></p>
                                                        <div class="teacher_name">
                                                            <div><img src="{{ \App\Service\CommonService::getUserAvatarUrl($v->lesson['senpai']['user_avatar']) }}" alt=""></div>
                                                            <div>{{$v->lesson['senpai']['name']}}（<em>{{ \App\Service\CommonService::getAge($v->lesson['senpai']['user_birthday']) }}</em>）</div>

                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        </div>
                                    </li>
                                @endif
                            @endforeach
                        </ol>
                        <!-- If we need pagination -->
                        <!-- If we need navigation buttons -->
                    </div>
                </div>
            </div>
            @endif
        </section>
    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

