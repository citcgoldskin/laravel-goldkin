@extends('user.layouts.app')

@section('title', 'クレジットカード情報の入力')

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->
    <style>
        body {
            background: #F7F7F7;
        }
    </style>

    <div id="contents" class="short">

        <!--main_-->
        {{ Form::open(["route"=>["user.lesson.add_credit_card"], "method"=>"post", "name"=>"frm_add_card", "id"=>"frm_add_card"]) }}

        <input type="hidden" name="nonce" id="nonce">
        <section>
            {{--<div class="inner_box">
                <h3>カード会社</h3>
                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                    <select name="company_id" id="company_id">
                        <option value="0">選択してください</option>
                        @foreach($company as $k => $v)
                            <option value="{{$v['company_id']}}">{{$v['company_name']}}</option>
                        @endforeach
                    </select>
                </div>
                @error('company_id')
                <p class="error_text"><strong>{{ $message }}</strong></p>
                @enderror
            </div>--}}

            <div class="inner_box">
                <h3>カード番号</h3>
                <div class="input-text">
                    <input type="text" name="number" id="sq-card-number">
                    <p class="gray_txt">※スペースを入れずにカードの表記通りにご入力ください。</p>
                </div>
            </div>

            <div class="inner_box">
                <h3>有効期限</h3>
                <div class="input-text">
                    <input type="text" name="expiration_date" id="sq-expiration-date">
                </div>
            </div>

            <div class="inner_box security_number">
                <h3>セキュリティーコード</h3>
                <div class="input-text">
                    <input type="text" name="cvv" id="sq-cvv">
                    <p class="black_txt">※カード裏面の署名欄に記載されている3桁の数字です。</p>
                </div>
            </div>

            <div class="inner_box">
                <h3>郵便番号</h3>
                <div class="input-text">
                    <input type="text" name="post_code" id="sq-postal-code">
                </div>
            </div>

            <div class="inner_box security_number">
                <h3>名義人</h3>
                <div class="input-text">
                    <input type="text" name="card_holder">
                </div>
            </div>
        </section>

        <div class="white-bk">
            <div class="button-area">
                <div class="btn_base btn_orange shadow">
                    <button type="submit" onclick="requestCardNonce(event)">追加する</button>
                </div>
            </div>
        </div>

        {{ Form::close() }}
    </div><!-- /contents -->
@endsection

@section('page_css')
    <style>

    </style>
@endsection

@section('page_js')
    @if(app()->isProduction())
        <script type="text/javascript" src="https://js.squareup.com/v2/paymentform"></script>
    @else
        <script type="text/javascript" src="https://js.squareupsandbox.com/v2/paymentform"></script>
    @endif

    <script type="text/javascript">
        function clearCheckbox() {
            $("input.category_ids").removeAttr("checked");
        }

        var application_id = "{{ app()->isProduction() ? env('SQUARE_APP_ID') : env('SQUARE_SANDBOX_APP_ID') }}"; // アプリケーションIDと置き換えます
        var location_id = "{{ app()->isProduction() ? env('SQUARE_LOCATION_ID') : env('SQUARE_SANDBOX_LOCATION_ID') }}"; // アプリケーションIDと置き換えます

        // ボタンを押したタイミングで実行される関数
        function requestCardNonce(event) {
            event.preventDefault();
            paymentForm.requestCardNonce();
        }

        var paymentForm = new SqPaymentForm({
            applicationId: application_id,
            locationId: location_id,
            inputClass: 'shadow-glay',
            inputStyles: [
                {
                    fontSize: '16px',
                    padding: '5px',
                    placeholderColor: '#a0a0a0',
                    backgroundColor: 'transparent',
                }
            ],
            cardNumber: {
                elementId: 'sq-card-number',
                placeholder: '•••• •••• •••• ••••'
            },
            cvv: {
                elementId: 'sq-cvv',
                placeholder: 'CVV'
            },
            expirationDate: {
                elementId: 'sq-expiration-date',
                placeholder: 'MM/YY'
            },
            postalCode: {
                elementId: 'sq-postal-code'
            },
            callbacks: {
                // 後ほど記述
                cardNonceResponseReceived: function(errors, nonce, cardData) {
                    console.log('nonce:');
                    console.log(nonce);

                    console.log('cardData:');
                    console.log(cardData);
                    if (errors) {
                        // エラーの場合
                        console.log("Encountered errors:");
                        errors.forEach(function(error) {
                            console.log('  ' + error.message);
                        });
                    } else {
                        $('#nonce').val(nonce);
                        $('#frm_add_card').submit();
                    }
                },
            }
        });
    </script>
@endsection



