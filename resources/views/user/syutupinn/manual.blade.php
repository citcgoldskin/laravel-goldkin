@extends('user.layouts.app')

@section('title', '出品はじめかたガイド')

@section('content')
    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

          <!--main_-->
        <form action="./" method="post" name="form1" id="form1">

        <section class="pb0">
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
                  @php $i = 1; @endphp
                  @foreach(\App\Service\QuestionService::getQuestiones('コウハイメニュー') as $k => $v)
                      <div class="faq-box">
                          @php
                              echo '<input id="faq-check' . $i . '" name="acd" class="acd-check" type="checkbox">';
                              echo '<label class="acd-label faq-label pt0" for="faq-check' . $i++ . '">';
                          @endphp
                          <p class="faq_mark icon_q no-after tal f14">{{ $v['que_ask']  }}</p>
                          </label>
                          <div class="acd-content faq-content">
                              @php echo $v['que_answer']; @endphp
                              </p>
                          </div>
                      </div>
                  @endforeach
        </div>
        </section>

        </form>

    </div><!-- /contents -->

@endsection
