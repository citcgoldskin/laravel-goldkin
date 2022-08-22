@extends('user.layouts.app')

@section('title', '売上振込先の口座情報')

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

        {{--<form action="" method="post" name="form1" id="form1" target="senddata">--}}
        {{ Form::open(['id' => 'form1', 'name' => 'form1']) }}
        @php
            $btn_name = '登録';
            $modal_name = '登録';
            if(is_object($obj_user) && $obj_user->bank){
                $btn_name = '変更';
                $modal_name = '更新';
            }

            $bank_id = isset($condition['bank_id']) && $condition['bank_id'] ? $condition['bank_id'] : (is_object($obj_user) && $obj_user->bank ? $obj_user->bank : '');
            $bank_name = "";
            if ($bank_id) {
                $bank_name = \App\Service\BankService::getBankName($bank_id);
            }
            $bank_account_type = isset($condition['bank_account_type']) && $condition['bank_account_type'] ? $condition['bank_account_type'] : (is_object($obj_user) && $obj_user->bank_account_type ? $obj_user->bank_account_type : '');
            $bank_account_type_name = "";
            if ($bank_account_type) {
                $bank_account_type_name = config('const.bank_account_type.'.$bank_account_type);
            }
        @endphp
        <input type="hidden" name="prev_url_id" id="prev_url_id" value="{{$prev_url_id}}">
        <input type="hidden" name="bank_id" id="bank_id" value="{{$bank_id}}">
        {{--<input type="hidden" name="bank_name" id="bank_name" value="{{$bank_name}}">--}}
        {{--<input type="hidden" name="bank_name" id="bank_name" value="{{isset($bank_name) && $bank_name ? $bank_name : (is_object($obj_user) && $obj_user->ob_bank && $obj_user->ob_bank->name ? $obj_user->ob_bank->name : '')}}">--}}
        <input type="hidden" name="bank_account_type" id="bank_account_type" value="{{$bank_account_type}}">
        {{--<input type="hidden" name="bank_account_type_name" id="bank_account_type_name" value="{{$bank_account_type_name}}">--}}
        {{--<input type="hidden" name="bank_account_type_name" id="bank_account_type_name" value="{{isset($bank_account_type_name) && $bank_account_type_name ? $bank_account_type_name : (is_object($obj_user) && $obj_user->ob_bank && $obj_user->ob_bank->bank_account_type ? config('const.bank_account_type.'.$obj_user->ob_bank->bank_account_type) : '')}}">--}}
        <section class="pb40">

            <ul class="form_area">

                <li>
                    <h3>金融機関名</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay for-warning">
                        {{--<button type="button" onClick="location.href='{{ route('user.myaccount.sel_bank', ['prev_url_id' => $prev_url_id, 'bnk_id' => $bnk_id, 'act_id' => $act_id, 'act_type_name' => $act_type_name]) }}'"--}}
                        {{--<button type="button" onClick="location.href='{{ route('user.myaccount.sel_bank_new', ['prev_url_id' => $prev_url_id, 'bank' => is_object($obj_user) && $obj_user->bank ? $obj_user->bank : '' ]) }}'"--}}
                        <button type="button" class="form_btn" id="bnk_name">{{ $bank_name ? $bank_name : '選択してください' }}</button>
                        <p class="warning"></p>
                    </div>
                </li>

                <li>
                    <h3>口座種別</h3>
                    <div class="form_wrap icon_form type_arrow_right shadow-glay for-warning">
                        {{--<button type="button" onClick="location.href='{{ route('user.myaccount.sel_account_type', ['act_type' => $act_type, 'act_id' => $act_id, 'bnk_name' => $bnk_name])}}'"--}}
                        {{--<button type="button" onClick="location.href='{{ route('user.myaccount.sel_account_type', ['act_type' => $act_type, 'act_id' => $act_id, 'bnk_name' => $bnk_name])}}'"--}}
                        <button type="button" class="form_btn" id="act_type_name">{{ $bank_account_type_name ? $bank_account_type_name : '選択してください' }}</button>
                        <p class="warning"></p>
                    </div>
                </li>

                <li>
                    <h3>支店コード</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" name="bank_branch" id="bank_branch" value="{{ old('bank_branch', (isset($condition['bank_branch']) && $condition['bank_branch'] ? $condition['bank_branch'] : (is_object($obj_user) && $obj_user->bank_branch ? $obj_user->bank_branch : ''))) }}">
                        {{--<input type="text" name="act_suboffice_code" id="act_suboffice_code"
                               @if(isset($data['act_suboffice_code']) && !empty($data['act_suboffice_code'])) value="{{$data['act_suboffice_code']}}"
                               @endif placeholder="">--}}
                        <p class="warning"></p>
                    </div>
                </li>

                <li>
                    <h3>口座番号</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" name="bank_account_no" id="bank_account_no" value="{{ old('bank_account_no', (isset($condition['bank_account_no']) && $condition['bank_account_no'] ? $condition['bank_account_no'] : (is_object($obj_user) && $obj_user->bank_account_no ? $obj_user->bank_account_no : ''))) }}">
                        {{--<input type="text" name="act_number" id="act_number"
                               @if(isset($data['act_number']) && !empty($data['act_number'])) value="{{$data['act_number']}}"
                               @endif placeholder="">--}}
                        <p class="warning"></p>
                    </div>
                    <p class="gray_txt">※口座番号が7桁未満の場合は先頭に0をつけてください</p>
                </li>

                <li>
                    <h3>口座名義（カナ）</h3>
                    <div class="form_wrap shadow-glay for-warning">
                        <input type="text" name="bank_account_name" id="bank_account_name" value="{{ old('bank_account_name', (isset($condition['bank_account_name']) && $condition['bank_account_name'] ? $condition['bank_account_name'] : (is_object($obj_user) && $obj_user->bank_account_name ? $obj_user->bank_account_name : ''))) }}">
                        {{--<input type="text" name="act_name" id="act_name"
                               @if(isset($data['act_name']) && !empty($data['act_name'])) value="{{$data['act_name']}}"
                               @endif placeholder="">--}}
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
        {{--</form>--}}
        {{ Form::close() }}


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
        $(document).ready(function () {
            $('#bnk_name').click(function() {
                $('#form1').attr('action', "{{ route('user.myaccount.sel_bank_new') }}");
                $('input[name="_token"]').prop('disabled', true);
                $('#form1').attr('method', "get");
                $('#form1').submit();
            });
            $('#act_type_name').click(function() {
                $('#form1').attr('action', "{{ route('user.myaccount.sel_account_type') }}");
                $('input[name="_token"]').prop('disabled', true);
                $('#form1').attr('method', "get");
                $('#form1').submit();
            });

            $('#account_btn').on('click',function(e){
                e.preventDefault();
                $.ajax({
                    type: "post",
                    url: " {{ route('user.myaccount.add_account') }}",
                    data: {
                        _token: "{{csrf_token()}}",
                        user_id: "{{ $obj_user->id }}",
                        bank: "{{ $bank_id }}",
                        bank_account_type: "{{ $bank_account_type }}",
                        bank_branch: $('#bank_branch').val(),
                        bank_account_no: $('#bank_account_no').val(),
                        bank_account_name: $('#bank_account_name').val(),
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
                            console.log("data", data);
                            if ( data.res.bank != undefined ) {
                                addError($('#bnk_name'), data.res.bank);
                            }
                            if ( data.res.bank_account_type != undefined ) {
                                addError($('#act_type_name'), data.res.bank_account_type);
                            }
                            if ( data.res.bank_branch != undefined ) {
                                addError($('#bank_branch'), data.res.bank_branch);
                            }
                            if ( data.res.bank_account_no != undefined ) {
                                addError($('#bank_account_no'), data.res.bank_account_no);
                            }
                            if ( data.res.bank_account_name != undefined ) {
                                addError($('#bank_account_name'), data.res.bank_account_name);
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection

