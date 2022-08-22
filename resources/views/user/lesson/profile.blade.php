@extends('user.layouts.app')

@section('title', 'プロフィール')

@section('$page_id', 'home')

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents">

      <!--main_-->
    {{--<form action="./" method="post" name="form1" id="form1">--}}

          <section class="pt15">
               <div class="swiper-container shadow-glay">
              <div class="swiper-inner">
              <div class="profile">
                  <ol class="swiper-wrapper pb0">
                        <!-- Slides -->
                        <li class="swiper-slide">
                          <div class="swip_contents_block">
                              <div class="slider_box">
                                <div class="img-box">
                                    <img src="img/A-15_16_17/img_01.jpg" alt="プロフィールイメージ画像">
                                </div>
                              </div>
                          </div>
                        </li>
                        <li class="swiper-slide">
                          <div class="swip_contents_block">
                              <div class="slider_box">
                                <div class="img-box">
                                    <img src="img/A-15_16_17/img_01.jpg" alt="プロフィールイメージ画像">
                                </div>
                              </div>
                          </div>
                        </li>
                        <li class="swiper-slide">
                          <div class="swip_contents_block">
                              <div class="slider_box">
                                <div class="img-box">
                                    <img src="img/A-15_16_17/img_01.jpg" alt="プロフィールイメージ画像">
                                </div>
                              </div>
                          </div>
                        </li>
                        <li class="swiper-slide">
                          <div class="swip_contents_block">
                              <div class="slider_box">
                                <div class="img-box">
                                    <img src="img/A-15_16_17/img_01.jpg" alt="プロフィールイメージ画像">
                                </div>
                              </div>
                          </div>
                        </li>
                      </ol>
                      <!-- If we need pagination -->
                      <div class="swiper-pagination"></div>

                      <!-- If we need navigation buttons -->
                    </div>
                  </div>
              </div>

                      <!-- If we need navigation buttons -->


                 <ul class="profile_box">
                  <li>
                   <img src="img/A-14/img_01.png" class="プロフィールアイコン">
                  </li>
                  <li>
                   <div class="c-like-box">
                      <div class="clex-box_01">
                      <input type="checkbox" name="favourite" @if($senpai_info['favourite'] == 1) checked="checked" @endif id="c1">
                      <label for="c1" class="nobo"><p>LIKED</p></label>
                      </div>
                   </div>
                  </li>
                 </ul>

              <div class="profile_base">
                <ul>
                    <li class="profile_name"><p>{{$senpai_info['name']}}<span>（{{$senpai_info['age']}}）
                                @if($senpai_info['sex'] == 1)女性@else男性@endif</span></p></li>
                    <li class="honnin_kakunin"><p>本人確認済み</p></li>
                </ul>
                <ul class="profile_info">
                 <li class="target_area">{{$senpai_info['area_name']}}</li>
                 <li class="jisseki">
                    <p>購入実績 <span>{{$senpai_info['buy_request_count']}}</span><span>件</span></p>
                    <p>販売実績 <span>{{$senpai_info['sell_request_count']}}</span><span>件</span></p>
                 </li>
                </ul>
                <div class="self-introduction">
                    <p class="cut-text">{{$senpai_info['intro']}}</p>
                     <p class="readmore-btn"><a href="" class="shadow-glay">続きを読む</a></p>
                </div>


              </div>


          </section>


          <section>
            <h3>出品レッスン（{{$senpai_info['lesson']['count']}}件）</h3>

            <ul class="lesson_list_wrap">
                @foreach($senpai_info['lesson']['arr'] as $key => $value)
                <li class="lesson_box">
                 <a href="{{route('user.lesson.detail', ['lesson_id' => $value['id']])}}">
                    <div class="img-box">
                        <img src="{{ asset('assets/user/img/A-2_3/img_07.jpg') }}" alt="ウォーキング画像">
                        <p>ランニング・ウォーキング</p>
                    </div>
                    <div class="lesson_info_box">
                     <p class="lesson_name">{{$value['title']}}</p>
                     <p class="lesson_price"><em>{{$value['fee']}}</em><span>円 / <em>30</em>分〜</span></p>
                     <div class="teacher_name">
                      <div><img src="{{ asset('assets/user/img/A-2_3/img_04.png') }}" alt=""></div>
                      <div>{{$senpai_info['name']}}（<em>{{$senpai_info['age']}}</em>）</div>
                     </div>
                    </div>
                   </a>
                 </li>
                @endforeach
            </ul>

          </section>

    {{--</form>	--}}

    </div><!-- /contents -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

    @include('user.layouts.footer')

@endsection

<script type="text/javascript">
    $('input[name="favourite"]').change(function(){
        var bSelected = 0;
        if(this.checked == true){
            bSelected = 1;
        }

        var form_data = new FormData();
        form_data.append("_token", "{{csrf_token()}}");
        form_data.append("senpai_id", {{$senpai_info['senpai_id']}});
        form_data.append("bSelected", bSelected);
        $.ajax({
            type: "post",
            url: "{{route('user.lesson.postSenpaiFavourite')}}",
            data : form_data,
            dataType: 'json',
            contentType : false,
            processData : false,
            success : function(result) {
            }
        });
    });
</script>
