@extends('user.layouts.app')

@section('title', 'ご利用ガイド')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_under')

    <div id="contents" class="short">

        <form action="./" method="post" name="form1" id="form1">

        <section id="talkroom_menu">

            <div class="tabs info_wrap mt0">
                @php
                    $is_logged = isset($user_info) ?  true : false;
                    $is_senpai = $is_logged && $user_info['user_is_senpai'] == config('const.staff_type.senpai') ? true : false;
                @endphp
                @if(!$is_logged)
                    <input id="tab-01" type="radio" name="tab_item" {{ $type==config('const.menu_type.kouhai') ? 'checked="checked"':''}} {{ $is_logged && $is_senpai ? 'disabled' : '' }}>
                    <label class="tab_item" for="tab-01">コウハイメニュー</label>
                    <input id="tab-02" type="radio" name="tab_item" {{ $type==config('const.menu_type.senpai') ? 'checked="checked"':''}} {{ $is_logged && !$is_senpai ? 'disabled' : '' }}>
                    <label class="tab_item" for="tab-02">センパイメニュー</label>
                @endif
                <!-- ********************************************************* -->
                <div class="tab_content" id="tab-01_content" >
                    <section>
                        <div class="inner_box">

                            <ul class="guide_list guide_feature">
                                @for ($i = 1; $i < 4; $i++)
                                    <li><img src=" {{ asset('assets/user/img/first-guide/lesson/guide_0'.$i.'.png') }} " alt=""></li>
                                @endfor
                            </ul>
                        </div>

                        <div class="inner_box">
                            <h3 class="guide_ttl">レッスンのはじめかた</h3>
                            <ul class="guide_list">
                                @for ($i = 1; $i < 6; $i++)
                                    <li><img src=" {{ asset('assets/user/img/first-guide/lesson/step_0'.$i.'.png') }} " alt=""></li>
                                @endfor
                            </ul>


                        </div>
                    </section>

                    <section>
                        <!-- ************************************************************ -->
                        <div class="inner_wrap">

                            <h3 class="guide_ttl">よくあるご質問</h3>
                            @php $i = 1; @endphp
                            @foreach(\App\Service\QuestionService::getQuestiones('コウハイメニュー') as $k => $v)
                            <div class="faq-box">
                                @php
                                    echo '<input id="faq-check' . $i . '" name="acd" class="acd-check" type="checkbox">';
                                    echo '<label class="acd-label faq-label" for="faq-check' . $i++ . '">';
                                @endphp
                                    <p class="faq_mark icon_q">{{ $v['que_ask']  }}</p>
                                </label>
                                <div class="acd-content faq-content">
                                    @php echo $v['que_answer']; @endphp
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>

                </div>
                <!-- ********************************************************* -->
                <div class="tab_content" id="tab-02_content" style="display: block">

                    <section>
                        <div class="inner_box">

                            <ul class="guide_list guide_feature">
                                @for ($i = 1; $i < 4; $i++)
                                <li><img src=" {{ asset('assets/user/img/first-guide/listing/guide_0'.$i.'.png') }} " alt=""></li>
                                @endfor
                            </ul>
                        </div>

                        <div class="inner_box">
                            <h3 class="guide_ttl">出品のはじめかた</h3>
                            <ul class="guide_list">
                                @for ($i = 1; $i < 6; $i++)
                                <li><img src=" {{ asset('assets/user/img/first-guide/listing/step_0'.$i.'.png') }} " alt=""></li>
                                @endfor
                            </ul>
                        </div>
                    </section>

                    <section>
                        <!-- ************************************************************ -->
                        <div class="inner_wrap">

                            <h3 class="guide_ttl">よくあるご質問</h3>
                            @foreach(\App\Service\QuestionService::getQuestiones('センパイメニュー') as $k => $v)
                            <div class="faq-box">
                                @php
                                    echo '<input id="faq-check' . $i . '" name="acd" class="acd-check" type="checkbox">';
                                    echo '<label class="acd-label faq-label" for="faq-check' . $i++ . '">';
                                @endphp
                                    <p class="faq_mark icon_q">{{ $v['que_ask']  }}</p>
                                </label>
                                <div class="acd-content faq-content">
                                    @php echo $v['que_answer']; @endphp
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </section>
                </div>
            </div>
        </section>
    </form>

    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection

@section('page_js')
<script>
    @if ($type == config('const.menu_type.kouhai'))
        $("#tab-01_content").show();
        $("#tab-02_content").hide();
    @else
        $("#tab-01_content").hide();
        $("#tab-02_content").show();
    @endif
</script>
@endsection

