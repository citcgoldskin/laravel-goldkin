
@extends('user.layouts.app')
@section('title', '売上明細書')
@section('$page_id', 'mypage')
@include('user.layouts.header_under')
<!-- ************************************************************************
本文
************************************************************************* -->
@section('content')
<div id="contents">

  <form action="./" method="post" name="form1" id="form1">

  <section>
   <div class="form_txt">
    <p><small>売上明細書がPDFデータでご確認いただけます。</small></p>
   </div>
  </section>

  <section>

    <ul class="conditions_box pb20">
	 <li><h3>売上期間</h3></li>
	 <li>
	      <div class="form_wrap icon_form type_arrow_bottom mark_year shadow-glay">
	       <select id="year" name="year">
               @if(count($years_list) > 0)
                   @foreach($years_list as $val)
                       <option value="{{ $val['year'] }}" {{ old('year', $current_year) == $val['year'] ? 'selected' : '' }}>{{ $val['year'] }}</option>
                   @endforeach
               @endif
           </select>
	      </div>
	 </li>
	</ul>

	<div class="white_box" id="payment_pdf_list">
	</div>

  </section>

<section>
	 <!-- ************************************************************ -->




	  <div class="inner_wrap">

	   <h3>売上明細書についてよくある質問</h3>
	  <div class="faq-box shadow-glay">
	   <input id="faq-check1" name="acd" class="acd-check" type="checkbox">
	    <label class="acd-label faq-label pl20" for="faq-check1">
		 <p class="faq_mark icon_q no-after pt0 tal ind">売上はいつ振り込まれますか？</p>
		</label>
		<div class="acd-content faq-content">
		 <p class="faq_mark icon_a">
		  回答が入ります
		 </p>
		</div>
	  </div>

	  <div class="faq-box shadow-glay">
	   <input id="faq-check2" name="acd" class="acd-check" type="checkbox">
	    <label class="acd-label faq-label pl20" for="faq-check2">
		 <p class="faq_mark icon_q no-after pt0 tal ind">売上が振り込まれていません。</p>
		</label>
		<div class="acd-content faq-content">
		 <p class="faq_mark icon_a">
		  回答が入ります
		 </p>
		</div>
	  </div>

</div>
</section>
	</form>


  </div><!-- /contents-->



<footer>

@include('user.layouts.fnavi')

</footer>
@endsection

@section('page_js')
    <script>
        $(document).ready(function() {
            getPaymentInfo({{ $current_year }});

            $('#year').change(function() {
                getPaymentInfo($(this).val());
            });

            $('#payment_pdf_list').on('click', '.payment-history-li', function() {
                let date = $(this).attr('data-date');
                let price = $(this).attr('data-price');
                console.log("date", date);
                location.href = "{{ route('user.myaccount.sale_detail_note') }}" + "?date=" + date + "&price=" + price;
            });
        });

        function getPaymentInfo(_year) {
            let condition = {
                _token: "{{ csrf_token() }}",
                year: _year
            };
            $.ajax({
                type: "post",
                url: '{{ route('user.myaccount.get_payment_by_year') }}',
                data: condition,
                dataType: 'json',
                success: function (result) {
                    console.log("result", result);
                    if(result.result_code == 'success') {
                        $('#payment_pdf_list').html('');
                        $('#payment_pdf_list').append(result.payment_list);
                    } else {
                    }
                }
            });
        }
    </script>

@endsection
