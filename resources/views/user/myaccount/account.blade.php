@extends('user.layouts.app')

@section('title', 'マイページ')

@php
    use App\Service\CommonService;
    use App\Service\BankService;
@endphp

@section('content')

    @include('user.layouts.header_under')

    <!-- ************************************************************************
    本文
    ************************************************************************* -->

    <div id="contents" class="pb0">

        <form action="" method="post" name="form1" id="form1" target="senddata">
        @php
            if(isset($_GET['act_id'])){
                $act_id = $_GET['act_id'];
            }
            if($act_id > 0){
                $btn_name = '変更';
                $modal_name = '更新';
            }
            else{
                $btn_name = '登録';
                $modal_name = '登録';
            }

            if(isset($_GET['bnk_name'])){
                $bnk_name = $_GET['bnk_name'];
            }else if(empty($bnk_name) && isset($data['bank']['bnk_name']) && !empty($data['bank']['bnk_name'])){
                $bnk_name = $data['bank']['bnk_name'];
            }

            $bnk_id = BankService::getBankId($bnk_name);

            if(isset($_GET['act_type_name'])){
                $act_type_name = $_GET['act_type_name'];
            }else if(empty($act_type_name) && isset($data['act_type']) && !empty($data['act_type'])){
                $act_type_name = CommonService::getAccountType($data['act_type']);
            }
            $act_type = CommonService::getAccountTypeId($act_type_name)
        @endphp
        <input type="hidden" name="act_id" id="act_id" value="{{$act_id}}">
        <input type="hidden" name="act_bnk_id" id="act_bank_id" value="{{$bnk_id}}">
        <input type="hidden" name="act_type" id="act_type" value="{{$act_type}}">
        <section class="pb40">

            <ul class="form_area">

                <li>
                    <h3>金融機関名</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay for-warning">
                        <button type="button" onClick="location.href='{{ route('user.myaccount.sel_bank', ['prev_url_id' => $prev_url_id, 'bnk_id' => $bnk_id, 'act_id' => $act_id, 'act_type_name' => $act_type_name]) }}'"
                                class="form_btn" id="bnk_name">
                            @if(empty($bnk_name))
                                選択してください
                            @else
                                {{$bnk_name}}
                            @endif
                        </button>
                        <p class="warning"></p>
                    </div>
                </li>

                <li>
                    <h3>口座種別</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay for-warning">
                        <button type="button" onClick="location.href='{{ route('user.myaccount.sel_account_type', ['act_type' => $act_type, 'act_id' => $act_id, 'bnk_name' => $bnk_name])}}'"
                                class="form_btn" id="act_type_name">
                            @if(empty($act_type_name))
                                選択してください
                            @else
                                {{$act_type_name}}
                            @endif
                        </button>
                        <p class="warning"></p>
                    </div>
                </li>

                <li>
                    <h3>支店コード</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" name="act_suboffice_code" id="act_suboffice_code"
                               @if(isset($data['act_suboffice_code']) && !empty($data['act_suboffice_code'])) value="{{$data['act_suboffice_code']}}"
                               @endif placeholder="">
                        <p class="warning"></p>
                    </div>
                </li>

                <li>
                    <h3>口座番号</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" name="act_number" id="act_number"
                               @if(isset($data['act_number']) && !empty($data['act_number'])) value="{{$data['act_number']}}"
                               @endif placeholder="">
                        <p class="warning"></p>
                    </div>
                    <p class="gray_txt">※口座番号が7桁未満の場合は先頭に0をつけてください</p>
                </li>

                <li>
                    <h3>口座名義（カナ）</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" name="act_name" id="act_name"
                               @if(isset($data['act_name']) && !empty($data['act_name'])) value="{{$data['act_name']}}"
                               @endif placeholder="">
                        <p class="warning"></p>
                    </div>
                </li>
            </ul>

        </section>


        <div class="white-bk pt20">

            <div id="footer_comment_area">
                <ul class="coution_list">
                    <li>登録された氏名と売上金の振込口座名義が一致しない場合、振込申請を行うことができません。</li>
                    <li>振込先口座はご本人名義の口座のみご利用いただけます。</li>
                </ul>
            </div>


            <div class="button-area mt30">
                <div class="btn_base btn_orange shadow ">
                    <button id="account_btn">{{$btn_name}}する</button>
                </div>
            </div>

        </div>
        </form>


    </div><!-- /contents-->

    <!-- モーダル部分 *********************************************************** -->
    <iframe name="senddata" style="width:0px;height:0px;border:0px;"></iframe>
    <div class="modal-wrap completion_wrap">
        <div id="modal-mail_henkou" class="modal-content">

            <div class="modal_body completion">
                <div class="modal_inner">
                    <h2 class="modal_ttl">
                        口座情報を<br>
                        {{$modal_name}}しました
                    </h2>

                </div>
            </div>


            <div class="button-area type_under">
                <div class="btn_base btn_ok">
                    <a id="modal-close"
                        @if($prev_url_id == 1)
                            href="{{route('user.myaccount.set_account')}}"
                        @elseif($prev_url_id == 2)
                            href="{{route('user.myaccount.payment_detail')}}"
                        @elseif($prev_url_id == 4)
                            href="{{route('user.myaccount.payment_mgr')}}"
                        @endif

                    >OK</a>
                </div>
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
        $('#account_btn').on('click',function(){
            var postData = new FormData($("#form1").get(0));
            postData.append("_token", "{{csrf_token()}}");
            postData.append("act_id", $('#act_id').val());
            postData.append("act_bank_id", $('#act_bank_id').val());
            postData.append("bnk_name", "{{$bnk_name}}");
            postData.append("act_type", $('#act_type').val());
            postData.append("act_type_name", "{{$act_type_name}}");
            postData.append("act_suboffice_code", $('#act_suboffice_code').val());
            postData.append("act_number", $('#act_number').val());
            postData.append("act_name", $('#act_name').val());
            $.ajax({
                type: "post",
                url: " {{ route('user.myaccount.add_account') }}",
                data: postData,
                dataType: "json",
                contentType: false,
                processData: false,
                success: function(data) {
                    if ( data.result_code == 'success' ) {
                        if (data.result) {
                            $('.modal-wrap').fadeIn();
                            $('.modal-content').fadeIn();
                            $('#modal-overlay').fadeIn();
                        }
                    }
                    else if(data.result_code == 'failed'){
                        if ( data.res.bnk_name != undefined ) {
                            addError($('#bnk_name'), data.res.bnk_name);
                        }
                        if ( data.res.act_type_name != undefined ) {
                            addError($('#act_type_name'), data.res.act_type_name);
                        }
                        if ( data.res.act_suboffice_code != undefined ) {
                            addError($('#act_suboffice_code'), data.res.act_suboffice_code);
                        }
                        if ( data.res.act_number != undefined ) {
                            addError($('#act_number'), data.res.act_number);
                        }
                        if ( data.res.act_name != undefined ) {
                            addError($('#act_name'), data.res.act_name);
                        }
                    }
                }
            });
        });


    </script>
@endsection

