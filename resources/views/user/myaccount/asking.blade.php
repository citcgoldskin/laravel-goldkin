
@extends('user.layouts.app')
@section('title', 'お問い合わせ')
@section('$page_id', 'mypage')

<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')

    @include('user.layouts.header_under')

    <div id="contents" class="short">

        <form action="./" method="post" name="form1" id="form1" target="senddata">

  <section class="pb0">
   <div class="inner_box">
	  <ul class="form_area">

	   <li>
	    <h3>ユーザー種別</h3>
	    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
	     <select name="user_type" id="user_type">
		  <option value="">選択してください</option>
			 @foreach(config('const.user_type_label') as $k => $v)
				 <option value="{{$k}}">{{$v}}</option>
			 @endforeach
         </select>
	    </div>
       </li>

	   <li>
	    <h3>お困りの内容を選択してください</h3>
	    <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
			<select class="question" name="ques_class" id="ques_class">
				<option value="">選択してください</option>
                {{--@foreach(config('const.ask_type') as $k=>$v)
                    <option value="{{$k}}">{{$v['title']}}</option>
                @endforeach--}}
			</select>
	    </div>
       </li>
	 </ul>
	</div>

  </section>

<script>
    function appendQuestion(data, key)
    {
        var html = "";
        html += '<div class="faq-box shadow-glay">';
        html += '<input id="faq-check-' + key + '" name="acd" class="acd-check" type="checkbox">';
        html += '<label class="acd-label faq-label pl20" for="faq-check-' + key + '">';
        html += '<p class="faq_mark icon_q no-after pt0 tal ind">' + data.que_ask + '</p>';
        html += '</label>';
        html += '<div class="acd-content faq-content">';
        html += '<p class="faq_mark icon_a">' + data.que_answer + '</p>';
        html += '</div>';
        html += '</div>';
        return html;
    }

	$(function () {
        $("#user_type").change(function() {
            $('#question').html('');
            $('#button-area').css('display', 'none');
            $('.text-here').css('display', 'none');

            var qc_id = $("#user_type").val();
            if(qc_id == ""){
                $('#ques_class').html('<option value="">選択してください</option>');
                return;
			}
            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("qc_id", qc_id);

            $.ajax({
                type: "post",
                url: "{{route('user.myaccount.ques_class')}}",
                data : form_data,
                dataType: 'json',
                contentType : false,
                processData : false,
                success : function(result) {
                    var html_data = '<option value="0">選択してください</option>';
                    var data = result.ques_class;
                    for(var key in data)
                    {
                        var ques_class = data[key];
                        html_data += '<option value="' + key + '">' + ques_class.title + '</option>';
                    }
                    $('#ques_class').html(html_data);
                }
            });
        });

        $("#ques_class").change(function() {
            var qc_id = $("#ques_class").val();
            if(qc_id == 0){
                $('#question').html('');
                $('#button-area').css('display', 'none');
                $('.text-here').css('display', 'none');
                return;
            }
            var form_data = new FormData();
            form_data.append("_token", "{{csrf_token()}}");
            form_data.append("qc_id", qc_id);

            $.ajax({
                type: "post",
                url: "{{route('user.myaccount.question')}}",
                data : form_data,
                dataType: 'json',
                contentType : false,
                processData : false,
                success : function(result) {
                    /*var html_data = '<section class="pb0 question1">';
                    html_data += '<div class="inner_box">';
                    html_data += '<h3>よくあるご質問</h3>';
                    var data = result.question;
                    for(var key in data)
                    {
                        html_data += appendQuestion(data[key], key);
                    }
                    html_data += '</div>';
                    html_data += '</section>';
                    $('#question').html(html_data);*/

                    $('#button-area').css('display', 'block');
                    $('.text-here').css('display', 'block');
                }
            });
        });
	});
</script>

  	<section class="pb0 question1" id="question"></section>

	<section class="text-here" style="display: none">
		<div class="inner_box">
			<h3>問題が解決しない場合はこちら</h3>
			<div class="input-text2 for-warning">
				<textarea placeholder="お困り内容をご記入ください" cols="50" rows="10" maxlength="1000" class="shadow-glay count-text" name="new_question" id="new_question"></textarea>
				<p class="max_length"><span id="num">0</span>／1,000</p>
				<p class="warning"></p>
			</div>
		</div>
	</section>

	<section id="button-area" style="display: none">
		<div class="button-area">
		  <div class="btn_base btn_orange shadow">
			<button id="asking_btn">お問い合わせを送信</button>
		  </div>
		</div>
	</section>
</form>

    </div><!-- /contents-->

	<!-- モーダル部分 *********************************************************** -->
	<iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
	<div class="modal-wrap completion_wrap">
		<div id="modal-mail_henkou" class="modal-content">
			<div class="modal_body completion">
				 <div class="modal_inner">
					<h2 class="modal_ttl">
					 お問い合わせを<br>
					 送信しました
					</h2>

					<div class="modal_txt">
						<p class="ls1">
						 運営からの返信をお待ちください。<br>
						 返信はお知らせ(あなた宛)に届きます。
						</p>
					</div>
				 </div>
			</div>

			  <div class="button-area type_under">
				  <div class="btn_base btn_ok"><a href="{{route('user.myaccount.index')}}" id="modal-close">OK</a></div>
			  </div>
	  	</div><!-- /modal-content -->
	</div>
	<div id="modal-overlay" style="display: none;"></div>

    <!-- モーダル部分 / ここまで ************************************************* -->
    <footer>
        @include('user.layouts.fnavi')
    </footer>
@endsection

@section('page_js')
	<script src="{{ asset('assets/user/js/validate.js') }}"></script>
    <script>
        $('#asking_btn').on('click',function(){
            $.ajax({
                type: "post",
                url: " {{ route('user.myaccount.add_asking') }}",
                data: {
                    user_type: $('#user_type').val(),
                    qc_id: $('#ques_class').val(),
                    _token: "{{ csrf_token() }}",
                    ask: $('#new_question').val()
                },
                dataType: "json",
                success: function(data) {
                    if ( data.result_code == 'success' ) {
                        if (data.result) {
                            $('.modal-wrap').fadeIn();
                            $('.modal-content').fadeIn();
                            $('#modal-overlay').fadeIn();
                        }
                    }
                    else if(data.result_code == 'failed'){
                        if ( data.res.ask != undefined ) {
                            addError($('#new_question'), data.res.ask);
                        }
                    }
                }
            });
        });


    </script>
@endsection


