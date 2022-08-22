@extends('user.layouts.app')

@section('title', '出勤リクエスト')

@section('$page_id', 'home')

@php
	use App\Service\CommonService;
@endphp

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents" class="long">

  <!--main_-->
{{ Form::open(["route"=>["user.lesson.add_attend_request"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}

      <section class="tab_area hide">
		<h3>ご希望のレッスン形式を選択してください</h3>

		<div class="switch_tab">
			<div class="radio-01">
			<input type="radio" name="hope_type" id="off-line" checked="checked" value="0">
			<label class="ok" for="off-line">対面希望</label>
			</div>
			<div class="radio-02">
			<input type="radio" name="hope_type" id="on-line" value="1">
			<label class="ok" for="on-line">オンライン希望</label>
			</div>
		</div>
	</section>


	<section>
		<div class="white_box mt30 plus-fukidashi">
		<input type="hidden" name="lesson_id"
		   @if(isset($lesson['lesson_id']) && !empty($lesson['lesson_id']))
		   		value="{{$lesson['lesson_id']}}"
			@endif>
		<input type="hidden" name="30min_fees" id="min_hour"
		   @if(isset($lesson['lesson_30min_fees']) && !empty($lesson['lesson_30min_fees']))
		   		value="{{$lesson['lesson_30min_fees']}}"
			@endif>
		 <span class="choice_lesson">選択中のレッスン！</span>
			<p class="lesson_ttl">
                @if(isset($lesson['lesson_title']) && $lesson['lesson_title'])
                    {{$lesson['lesson_title']}}
                @endif
            </p>
			<ul class="choice_price">
				{{--<li class="icon_taimen">
					{{CommonService::getLessonMode($lesson['lesson_type'])}}
				</li>--}}
				<li class="price">
					<em>
						@if(isset($lesson['lesson_30min_fees']) && !empty($lesson['lesson_30min_fees']))
							{{CommonService::showFormatNum($lesson['lesson_30min_fees'])}}
						@endif
					</em>円<span> / <em>30</em>分〜</span>
				</li>
			</ul>
		</div>
	  </section>

	  <section>
		<div class="inner_box {{ $lesson->lesson_cond_9 ? '' : 'hide' }}">
		 <h3>参加人数</h3>
		<input type="hidden" name="member_flag" id="member_flag" value="1">
		 <ul class="select_flex">
		  <li>
			<div>男性</div>
		   <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
			   <select name="man_num" class="member" id="man_num">
				   @for ($i = 0; $i <= 50; $i++)
					   <option value="{{$i}}" {{ old('man_num', is_object(Auth::user()) && Auth::user()->user_sex == config('const.sex.man') ? 1 : (is_object(Auth::user()) && Auth::user()->user_sex != config('const.sex.man') && Auth::user()->user_sex != config('const.sex.woman') ? 1 : '') ) == $i ? 'selected' : '' }}>{{ $i }}</option>
				   @endfor
			   </select>
		   </div>
		   <div>名</div>
		  </li>

		  <li>
			<div>女性</div>
		   <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
			   <select name="woman_num" class="member" id="woman_num">
				   @for ($i = 0; $i <= 50; $i++)
					   <option value="{{$i}}" {{ old('woman_num', is_object(Auth::user()) && Auth::user()->user_sex == config('const.sex.woman') ? 1 : '' ) == $i ? 'selected' : '' }}>{{ $i }}</option>
				   @endfor
			   </select>
		   </div>
		   <div>名</div>
		  </li>
		 @error('member_flag')
			<p class="error_text"><strong>{{ $message }}</strong></p>
		 @enderror
		 </ul>
            <div class="balloon balloon_blue">
                <p>※リクエストの入力者は必ず参加してください</p>
            </div>
		</div>
		<div class="inner_box">
		 <h3>希望レッスン時間</h3>
		<input type="hidden" name="hope_time_flag" id="hope_time_flag" value="0">
		 <ul class="select_flex">
		  <li>
		   <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
			   <select name="hope_mintime" class="hope_time" id="hope_mintime">
				   @for ($i = 15; $i <= 300; $i += 15)
					   <option value="{{$i}}" >{{ $i }}</option>
				   @endfor
			   </select>
		   </div>
		   <div>分</div>
		   <div>～</div>
		  </li>
		  <li>
		   <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
			   <select name="hope_maxtime" class="hope_time" id="hope_maxtime">
				   @for ($i = 15; $i <= 300; $i += 15)
					   <option value="{{$i}}" >{{ $i }}</option>
				   @endfor
			   </select>
		   </div>
		   <div>分</div>
		  </li>
		 </ul>
			@error('hope_time_flag')
			<p class="error_text"><strong>{{ $message }}</strong></p>
			@enderror
		</div>

	   <div class="inner_box pb10">
        <h3>希望日時</h3>
	   <input type="hidden" name="time_flag" id="time_flag" value="0">
		   <input type="hidden" name="time_count" id="time_count" value="1">
		<div id="boxes">
			<ul class="time3" id="attend_time_1">
				<li>
					<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
					 	<input type="date"  name="date[]" class="form_btn" value="{{date('Y-m-d')}}">
					</div>
				</li>
				<li>
				 	<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
					 	<select name="from_hour[]" class="fourth" id="from_hour" onchange="changeTime(this)">
							@for ($i = 0; $i < 24; $i++)
								<option value="{{$i}}" >{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
							@endfor
         				</select></div></li>
				<li>：</li>
				<li>
					<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
						<select name="from_minute[]" class="fourth" id="from_minute" onchange="changeTime(this)">
							@for ($i = 0; $i < 60; $i+=15)
								<option value="{{$i}}">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
							@endfor
						</select></div></li>
				<li>～</li>
				<li>
					<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
						 <select name="to_hour[]" class="fourth" id="to_hour" onchange="changeTime(this)">
							 @for ($i = 0; $i < 24; $i++)
								 <option value="{{$i}}" >{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
							 @endfor
						 </select></div></li>
						<li>：</li>
				<li>
					<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
						<select name="to_minute[]" class="fourth" id="to_minute" onchange="changeTime(this)">
							@for ($i = 0; $i < 60; $i+=15)
								<option value="{{$i}}">{{ str_pad($i, 2, "0", STR_PAD_LEFT) }}</option>
							@endfor
						</select></div>
				</li>
			</ul>
			</div>
		   @error('time_flag')
		   	<p class="error_text"><strong>{{ $message }}</strong></p>
		   @enderror
		</div>

		<div class="button-tsuika">
			<div class="btn_base btn_sen">
				<button type="button" id="addbutton">希望日時を追加する</button>
		    </div>
		</div>
<script>
	$(function () {
		let addbutton = document.getElementById("addbutton");
		addbutton.addEventListener("click", function() {
            var time_count = $('#time_count').val();
            time_count++;
		  let boxes = document.getElementById("boxes");
		  let clone = boxes.firstElementChild.cloneNode(true);
            clone.setAttribute( 'id', 'attend_time_' + time_count );
		  boxes.appendChild(clone);
            $('#time_count').val(time_count)
		});
	});
</script>


          <div class="inner_box pb0">
              <h3>レッスン場所</h3>
              <div class="white_box">
                  <div class="lesson_place">
                      <p class="place_point">{{ implode('/', $lesson->lesson_area_names) }}</p>
                  </div>
                  @if(isset($lesson['lesson_pos_detail']) && !empty($lesson['lesson_pos_detail']))
                      <div class="balloon balloon_blue">
                          <p>{{$lesson['lesson_pos_detail']}}</p>
                      </div>
                  @endif

                  @if(isset($lesson['lesson_able_discuss_pos']) && $lesson['lesson_able_discuss_pos'])
                      <div class="kodawari_check check-box">
                          <div class="clex-box_01">
                              <input type="hidden" name="target_reserve" value="{{ old('target_reserve', '') == 1 ? 1 : 0 }}" id="target_reserve">
                              <input type="checkbox" name="c1" value="{{ old('target_reserve', '') == 1 ? 1 : 0 }}" id="c1" {{ old('target_reserve', '') == 1 ? 'checked' : '' }}>
                              <label for="c1" class="nobo" id="target_reserve_label"><p>指定地で予約する</p></label>
                          </div>
                          <div class="clex-box_01">
                              <input type="hidden" name="pos_discuss" value="{{ old('pos_discuss', '') == 1 ? 1 : 0 }}" id="pos_discuss">
                              <input type="checkbox" name="c2" value="{{ old('pos_discuss', '') == 1 ? 1 : 0 }}" id="c2" class="check-trigger" {{ old('pos_discuss', '') == 1 ? 'checked' : '' }}>
                              <label for="c2" class="nobo" id="pos_discuss_label"><p>レッスン場所を相談する</p></label>
                          </div>
                      </div>
                  @endif
              </div>
          </div>

          <div class="hide-area pt30">

              <div class="inner_box text-p-04">
                  <h3>相談可能な地域</h3>
                  @if($lesson->lesson_discuss_area_names && count($lesson->lesson_discuss_area_names) > 0)
                      <ul class="soudan_ok_area">
                          @foreach($lesson->lesson_discuss_area_names as $val)
                              <li>{{ $val }}</li>
                          @endforeach
                      </ul>
                  @endif
              </div>

              <div class="inner_box">
                  <h3>エリアを選択</h3>
                  <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                      <select name="area_id">
                          <option value="">選択してください</option>
                          @if($lesson->lesson_discuss_area_names && count($lesson->lesson_discuss_area_names) > 0)
                              @foreach($lesson->lesson_discuss_area as $lesson_area)
                                  @if($lesson_area->position)
                                      @php
                                          $position = json_decode($lesson_area->position);
                                          $area_content = $position->area_name;
                                      @endphp
                                      <option value="{{ $lesson_area->id }}" {{ old('area_id') == $lesson_area->id ? 'selected' : '' }}>{{ $area_content }}</option>
                                  @endif
                              @endforeach
                          @endif
                      </select>
                  </div>
                  @error('area_id')
                  <p class="error_text"><strong>{{ $message }}</strong></p>
                  @enderror
              </div>

              <div class="inner_box">
                  <h3>続きの住所を入力</h3>
                  <div class="input-text shadow-glay">
                      <input type="text" name="address" size="50" maxlength="50" value="{{ old('address', '') }}">
                  </div>
                  @error('address')
                  <p class="error_text"><strong>{{ $message }}</strong></p>
                  @enderror
              </div>

              <div class="inner_box no-pb">
                  <h3>待ち合わせ場所の詳細
                      <small>（200文字まで）<i>任意</i></small>
                  </h3>
                  <div class="input-text2">
                        <textarea placeholder="待ち合わせ場所の詳細説明があれば入力してください。" cols="50" rows="10" class="shadow-glay"
                                  name="address_detail">{{ old('address_detail', '') }}</textarea>
                  </div>
                  @error('address_detail')
                  <p class="error_text"><strong>{{ $message }}</strong></p>
                  @enderror
              </div>
              <div class="balloon balloon_blue">
                  <p>指定するレッスン場所によっては出張交通費をお願いされる場合があります。</p>
              </div>

          </div>

          <p class="modal-link modal-link_blue">
              <a class="modal-syncer button-link" data-target="modal-business-trip">出張交通費とは？</a>
          </p>




		<div class="inner_box">
			<h3>リクエストの承認期限</h3>
			<div class="form_wrap icon_form type_arrow_right shadow-glay">
				<input type="date" class="form_btn approval" value="{{date('Y-m-d')}}" name="until_confirm" >
			</div>
		</div>


		</section>
		  <div id="footer_button_area" class="under_area">
	    <ul>
		<li class="send-request">
		  <div class="btn_base btn_orange centre">
		   <button type="submit">出勤リクエストを送信する</button>
		  </div>
		 </li>
		</ul>
	  </div>
{{ Form::close() }}

</div><!-- /contents -->

    @include('user.layouts.modal')

    <!-- *******************************************************
    フッター（リクエストボタンあり）
    ******************************************************** -->

    <footer>
        @include('user.layouts.fnavi')
    </footer>

@endsection

@section('page_js')
<script type="text/javascript">
    $(document).ready(function () {
        if($('[id=c2]').prop('checked')){
            $('.hide-area').show();
        } else  {
            $('.hide-area').hide();
        }
    });
    $("#off-label").click(function() {
        $("#off-line").attr('checked');
    });

    $("#on-label").click(function() {
        $("#on-line").attr('checked');
    });

    $("#target_reserve_label").click(function () {
        if ($("#target_reserve").val() == 0) {
            $("#target_reserve").val(1);
        } else {
            $("#target_reserve").val(0);
        }
    });
    $("#pos_discuss_label").click(function () {
        if ($("#pos_discuss").val() == 0) {
            $("#pos_discuss").val(1);
        } else {
            $("#pos_discuss").val(0);
        }
    });

    $(".member").change(function() {
        var man_num = $("#man_num").val();
        var woman_num = $("#woman_num").val();
        if(Number(man_num) > 0 || Number(woman_num) > 0){
            $("#member_flag").val(1);
        }
    });

    function changeTime(obj){
        var boxes = $(obj).parents('#boxes');
        var result = 1;
        var time_count = $('#time_count').val();
        for(var i = 1; i <= time_count; i++){
            var time = boxes.find('#attend_time_' + i);
            var h1 = time.find('#from_hour').val();
            var h2 = time.find('#to_hour').val();
            var m1 = time.find('#from_minute').val();
            var m2 = time.find('#to_minute').val();
            if(Number(h1) > Number(h2)){
                result = 0;
                break;
            }else if(h1 == h2){
                if(Number(m1) >= Number(m2)){
                    result = 0;
                    break;
                }
            }
		}
        $("#time_flag").val(result);
	}

    $(".hope_time").change(function() {
        var min = $("#hope_mintime").val();
        var max = $("#hope_maxtime").val();

        if(Number(max) > 0){
            if(Number(min) <= Number(max)){
                $("#hope_time_flag").val(1);
            }
        }
    });

</script>
@endsection
