@extends('user.layouts.app')
@section('title', 'センパイ登録')
@include('user.layouts.header')
@include('user.layouts.header_under')
@section('content')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents" class="short">
  <section>
	  {{ Form::open(["method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
			<ul class="form_area">
				<li class="for-warning">
					<h3 class="must">郵便番号</h3>
					<div class="form_wrap zipcode-wrap shadow-glay">
						<input type="text" value="{{isset($user_info) ? $user_info['user_mail'] : ''}}" placeholder="" class="" name="senpai_mail" id="郵便番号" onKeyUp="$('#郵便番号').zip2addr({pref:'#都道府県',addr:'#市区町村'});" >
					</div>
					<p class="warning"></p>
				</li>

				<li class="for-warning">
					<h3 class="must">都道府県</h3>
					<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
						<select id="都道府県" name="user_area_id">
							<option value="">ご希望の都道府県を選択してください</option>
							@php
								$area_list = array();
								$area_list = \App\Service\AreaService::getPrefectureList();
							@endphp
							@if($area_list->count() > 0)
								@foreach($area_list as $key => $value)
									@php $area_id = isset($user_info) ? $user_info['user_area_id'] : ''; @endphp
									<option value="{{ $value['area_id'] }}" {{$value['area_id']  == $area_id ? "selected='selected'":''}}>{{ $value['area_name'] }}</option>
								@endforeach
							@endif
						</select>
					</div>
					<p class="warning"></p>
				</li>

				<li>
					<ul class="select_float_box half_box">
						<li class="for-warning">
							<h3 class="must">市区町村</h3>
							<div class="form_wrap shadow-glay">
								<input type="text" value="{{isset($user_info) ? $user_info['user_county'] : ''}}" id="市区町村" name="user_county" placeholder="例）大阪市西区江戸堀">
							</div>
							<p class="warning"></p>
						</li>
						<li class="for-warning">
							<h3 class="must">町番地</h3>
							<div class="form_wrap shadow-glay">
								<input type="text" value="{{isset($user_info) ? $user_info['user_village'] : ''}}" id="user_village" name="user_village" placeholder="例）1-2-3">
							</div>
							<p class="warning"></p>
						</li>
					</ul>
				</li>
				<li>
					<h3>マンション名・部屋番号</h3>
					<div class="form_wrap shadow-glay">
						<input type="text" value="{{ isset($user_info) ? $user_info['user_mansyonn'] : ''}}" id="user_mansyonn"  name="user_mansyonn" placeholder="例）センパイハイツ101">
					</div>
				</li>
				<div class="balloon no_arrow">
					 <p class="mark_left mark_kome">
						ユーザー登録の際、記入いただいた情報を表示しています。変更があれば訂正をお願いします
					 </p>
				</div>
				<li>
					<h3 class="must">氏名</h3>
					<ul class="select_float_box half_box">
						<li class="for-warning">
							<h4>姓</h4>
							<div class="form_wrap shadow-glay shadow-glay">
								<input type="text" value="{{ isset($user_info) ? $user_info['user_firstname'] : ''}}" id="user_firstname" name="user_firstname" placeholder="田中">
							</div>
							<p class="warning"></p>
						</li>
						<li class="for-warning">
							<h4>名</h4>
							<div class="form_wrap shadow-glay shadow-glay">
								<input type="text" value="{{ isset($user_info) ? $user_info['user_lastname'] : '' }}" id="user_lastname" name="user_lastname" placeholder="太郎">
							</div>
							<p class="warning"></p>
						</li>
					</ul>
				</li>

				<li>
					<h3 class="must">フリガナ</h3>
					<ul class="select_float_box half_box">
						<li class="for-warning">
							<h4>姓</h4>
							<div class="form_wrap shadow-glay">
								<input type="text" value="{{isset($user_info) ? $user_info['user_sei'] : '' }}" id="user_sei" name="user_sei" placeholder="タナカ">
							</div>
							<p class="warning"></p>
						</li>
						<li class="for-warning">
							<h4>名</h4>
							<div class="form_wrap shadow-glay">
							<input type="text" value="{{ isset($user_info) ? $user_info['user_mei'] : '' }}" id="user_mei" name="user_mei" placeholder="タロウ">
							</div>
							<p class="warning"></p>
						</li>
					</ul>
				</li>
			  @php
                  $birthday = isset($user_info) ? strtotime($user_info['user_birthday']): strtotime(date('Y-m-d'));
				  $birthday_year    = date('Y',$birthday);
				  $birthday_month   = date('n',$birthday);
				  $birthday_day     = date('j',$birthday);
				  $last_day         = date('t',$birthday);
			  @endphp
				<li>
					<h3 class="must">生年月日</h3>
					<ul class="select_float_box three_box select_area">
						<li class="for-warning">
							<div class="form_wrap icon_form type_arrow_bottom mark_year shadow-glay">
								<select id="year" name="birthday_year" onchange="reset_days()">
									@for($i = 1900; $i < (date('Y')+1); $i++)
									<option value="{{$i}}"  {{$i == $birthday_year ? "selected='selected'":''}}>{{$i}}</option>
									@endfor
								</select>
							</div>
							<div>年</div>
							<p class="warning"></p>
						</li>
						<li class="for-warning">
							<div class="form_wrap icon_form type_arrow_bottom mark_month shadow-glay">
								<select id="month" name="birthday_month" onchange="reset_days()">
									@for($i = 1; $i < 13; $i++)
										<option value="{{$i}}"  {{$i == $birthday_month? "selected='selected'":''}}>{{$i}}</option>
									@endfor
								</select>
							</div>
							<div>月</div>
							<p class="warning"></p>
						</li>
					<li class="for-warning">
						<div class="form_wrap icon_form type_arrow_bottom mark_day shadow-glay">
							<select id="day" name="birthday_day">
								@for($i = 1; $i <= $last_day; $i++)
									<option value="{{$i}}"  {{$i == $birthday_day? "selected='selected'":''}}>{{$i}}</option>
								@endfor
							</select>
						</div>
						<div>日</div>
						<p class="warning"></p>
					</li>
					</ul>
				</li>

				<li class="for-warning">
					<h3 class="must">性別</h3>
					<div class="form_wrap icon_form type_arrow_bottom shadow-glay">
						<select id="sex" name="user_sex">
							<option value="">指定なし</option>
							<option value="{{ config('const.sex.man') }}"  {{$user_info['user_sex'] == config('const.sex.man') ? "selected='selected'" :'' }}>男性</option>
							<option value="{{ config('const.sex.woman') }}"  {{$user_info['user_sex'] == config('const.sex.woman') ? "selected='selected'" :'' }}>女性</option>
						</select>
					</div>
					<p class="warning"></p>
				</li>
	 		</ul>
	  {{ Form::close() }}
		<div class="balloon no_arrow">
			<p class="mark_left mark_kome">
				本人確認を行うため正しい情報をご記入ください。
			</p>
			<p class="mark_left mark_kome">
				センパイとして出品するには本人確認が必要です。
			</p>
			<p class="mark_left mark_kome">
				生年月日はこれ以上変更することはできません。
			</p>
		</div>

		<div class="link-area">
			<div class="orange_link"><a href="{{ route('using_rules') }}">利用規約</a></div>
			<div class="orange_link"><a href="{{ route('privacy_policy') }}">プライバシーポリシー</a></div>
		</div>

		<div class="inner_box">
			<div class="agree_required">
				<div class="agree_check_area">
					<label for="agree_btn" class="agree_btn"><input id="agree_btn" type="checkbox"><span class="label_inner">以上の内容に同意する</span></label>
				</div>
			</div>

			<div class="agree_btn_area btn_base shadow-glay">
				<div class="btn_base btn-glay shadow f-white">
					<button type="button" class="btn-glay" onclick="save();">本人確認を行う</button>
				</div>
			</div>
		</div>
  </section>
	  <!-- ※footer.phpにリンク設定の記述あり -->
  </div><!-- /contents-->

@endsection
@section('page_js')
	<script src="{{ asset('assets/user/js/validate.js') }}"></script>
	<script>
		$(document).ready(function () {
            remove_warning();
            $('#agree_btn').on('click', function () {
                var checked = $(".agree_btn_area").hasClass('_check');
                if ( checked ) {
                    $(".btn-glay").prop("disabled", false);
                } else {
                    $(".btn-glay").prop("disabled", true);
                }
            });
        });

        function validate() {
            var no_error = true;
            //if( !checkRequire($("#郵便番号"), "郵便番号")) is_error = true;
            if( !checkSelect($("#都道府県"), "都道府県")) no_error = false;
            //
            if( !checkRequire($("#市区町村"), "市区町村")) no_error = false;
            //
            if( !checkRequire($("#user_village"), "町番地")) no_error = false;
            //
            if( !checkRequire($("#user_firstname"), "姓")) no_error = false;
            //
            if( !checkRequire($("#user_lastname"), "名")) no_error = false;
            //
            if( !checkRequire($("#user_sei"), "姓")) no_error = false;
            //
            if( !checkRequire($("#user_mei"), "名")) no_error = true;

            if( !checkSelect($("#sex"), "性別")) no_error = false;
            return no_error;
        }
		function reset_days() {
			var year = $('#year').val();
			var month = $('#month').val();
            var d = new Date(year, month, 0);
            var last_day = d.getDate();
            var str_html = '';
            for(var i=1; i<=last_day; i++){
                str_html += '<option value="' + i + '">' + i + '</option>'
			}
			$('#day').html(str_html);
        }
        function save() {
            if(validate()){
                var form_data = new FormData($('#form1').get(0));
                form_data.append("_token", "{{csrf_token()}}");
                $.ajax({
                    type: "post",
                    url: '{{ route('user.myaccount.reg_senpai_post') }}',
                    data: form_data,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        if ( result.state == 'success' ) {
                            location.href = '{{ route('user.myaccount.confirm') }}'
                        }else{
							show_dialog('センパイ追加が失敗しました。');
                        }
                    },
                });
            }
        }
	</script>
@endsection

