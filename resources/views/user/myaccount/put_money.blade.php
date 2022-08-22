@extends('user.layouts.app')
@section('title', '振込申請')
@section('content')

@include('user.layouts.header_under')


<!-- ************************************************************************
本文
************************************************************************* -->
<div id="contents">
    {{ Form::open(['route'=>['user.myaccount.apply_transfer_money'], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" name="transfer_fee" value="{{ config('const.transfer_application_fee') }}">
        <section>

            <div class="inner_box">
                <div class="furikomi-text">
                    <ul>
                        <li>・1回の振り込みにつき振込手数料200円がかかります。</li>
                        <li>・振込手数料を差し引いたt金額がご設定いただいた銀行口座に入金されます。</li>
                    </ul>

                </div>
                <div class="white_box">
                    <ul class="border-none list_box">
                        <li class="due_date">
                            <div>
                                <ul class="flex-text pb20">
                                    <li>振込申請可能な売上金</li>
                                    <li>
                                        <p class="money01">126,000 <span>円</span></p>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <li class="due_date">
                            <div class="flex-text for-warning">
                                <ul class="flex-money">
                                    <li>振込申請金額</li>
                                    <li>	<div class="form_wrap shadow-glay ">
                                            <input type="text" value="" name="transfer_all_price" placeholder="" id="transfer_all_price">
                                        </div></li>
                                    <li>円</li>
                                </ul>
                                @error('transfer_all_price')
                                    <p class="error_text">{{ $message }}</p>
                                @enderror
                                <ul class="clear">
                                    <li><p class="text-01 color_01 text-left">※申請金額は201円以上から可能です</p></li>
                                </ul>
                            </div>
                        </li>

                        <li class="due_date">
                            <div>
                                <ul class="flex-text">
                                    <li>振込手数料</li>
                                    <li>
                                        <p class="money02">200<span>円</span></p>
                                    </li>

                                </ul>
                                <ul class="flex-money">
                                    <li>振込金額</li>
                                    <li>	<div class="form_wrap shadow-glay ">
                                            <input type="text" value="" placeholder="" name="transfer_profit_price" id="transfer_profit_price" readonly>
                                        </div></li>
                                    <li>円</li>
                                </ul>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>

            @php
                $bank_id = is_object($obj_user) && $obj_user->bank ? $obj_user->bank : '';
                $bank_name = $bank_id ? \App\Service\BankService::getBankName($bank_id) : '';
                $bank_account_type = is_object($obj_user) && $obj_user->bank_account_type ? $obj_user->bank_account_type : '';
                $bank_account_type_name = config('const.bank_account_type.'.$bank_account_type);
            @endphp

            <div class="inner_box">

                <h3>振込先の口座情報</h3>
                <div class="white_box">
                    <ul class="border-none list_box">
                        <li class="due_date">
                            <div>
                                <ul class="flex-text">
                                    <li>金融機関名</li>
                                    <li class="fs-14">	<div class="form_wrap shadow-glay ">
                                            <input type="text" value="{{ $bank_name }}" placeholder="" readonly>
                                        </div></li>
                                </ul>
                            </div>
                        </li>

                        <li class="due_date">
                            <div>
                                <ul class="flex-text">
                                    <li>支店コード</li>
                                    <li class="fs-14">	<div class="form_wrap shadow-glay ">
                                            <input type="text" value="{{ is_object($obj_user) && $obj_user->bank_branch ? $obj_user->bank_branch : '' }}" placeholder="">
                                        </div></li>
                                </ul>
                            </div>
                        </li>
                        <li class="due_date">
                            <div>
                                <ul class="flex-text">
                                    <li>口座種別</li>
                                    <li class="fs-14">	<div class="form_wrap shadow-glay ">
                                            <input type="text" value="{{ $bank_account_type_name }}" placeholder="" readonly>
                                        </div></li>
                                </ul>
                            </div>
                        </li>
                        <li class="due_date">
                            <div>
                                <ul class="flex-text">
                                    <li>口座番号</li>
                                    <li class="fs-14">	<div class="form_wrap shadow-glay ">
                                            <input type="text" value="{{ is_object($obj_user) && $obj_user->bank_account_no ? $obj_user->bank_account_no : '' }}" placeholder="" readonly>
                                        </div></li>
                                </ul>
                            </div>
                        </li>
                        <li class="due_date">
                            <div>
                                <ul class="flex-text">
                                    <li>口座名義(カナ)</li>
                                    <li class="fs-14">	<div class="form_wrap shadow-glay ">
                                            <input type="text" value="{{ is_object($obj_user) && $obj_user->bank_account_name ? $obj_user->bank_account_name : '' }}" placeholder="" readonly>
                                        </div></li>
                                </ul>
                            </div>
                        </li>


                    </ul>
                </div>

            </div>


            <div class="inner_box">

                <h3>振込予定日</h3>
                <div class="white_box">
                    <p class="f13">{{ $application_limit_date }}までに振り込み申請をすると{{ $application_send_date }}に振込されます。</p>
                </div>

            </div>
            <div class="furikomi-text">
                <ul>
                    <li>※1度申請いただいた内容はキャンセルできません。</li>
                    <li>※振込先が間違っている場合、再度申請いただく必要があります。その際、再度振込手数料が発生いたします。ご了承ください。</li>
                </ul>
            </div>
        </section>

        <section id="button-area">

            <div class="inner_box">

                <div class="button-area">
                    <div class="btn_base btn_orange shadow">
                        <button type="button" class="modal-syncer button-link" data-target="modal-transfer">振り込み申請を行う</button>
                    </div>
                </div>
            </div>
        </section>

    {{ Form::close() }}
</div><!-- /contents -->



<!-- モーダル部分 *********************************************************** -->

<div class="modal-wrap completion_wrap">
    <div id="modal-transfer" class="modal-content">

        <div class="modal_body">
            <div class="modal_inner">
                <h4 id="circle-orange_ttl">!</h4>
                <h2 class="modal_ttl">
                    振込申請しますか？
                </h2>
            </div>

            <div class="button-area">
                <div class="btn_base btn_orange">
                    <button type="button" onclick="doTransfer();">申請を行う</button>

                </div>
                <div class="btn_base btn_gray-line">
                    <a id="modal-close" class="button-link">キャンセル</a>
                </div>
            </div>
        </div>
    </div>
</div>

<iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
<div class="modal-wrap completion_wrap">
    <div id="modal-mail_henkou" class="modal-content">

        <div class="modal_body completion">
            <div class="modal_inner">
                <h2 class="modal_ttl">
                    振り込み申請を<br>行いました。
                </h2>

            </div>
        </div>


        <div class="button-area type_under">
            <div class="btn_base btn_ok"><a href="">OK</a></div>
        </div>

    </div><!-- /modal-content -->

</div>

<!-- モーダル部分 / ここまで ************************************************* -->


<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
@section('page_js')
    <script>
        $(document).ready(function() {
            $('#transfer_all_price').change(function() {
                let fee = "{{ config('const.transfer_application_fee') }}";
                fee = parseInt(fee);
                console.log("fee", fee);

                let delivery_amount = parseInt($(this).val());
                let transfer_amount = 0;
                if (delivery_amount > 0) {
                    transfer_amount = delivery_amount - fee;
                }
                $('#transfer_profit_price').val(transfer_amount);
            });
        });

        function doTransfer() {
            $('#form1').submit();
        }
    </script>
@endsection
