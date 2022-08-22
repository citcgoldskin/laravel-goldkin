@extends('user.layouts.app')

@section('title', $title)

@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->

<div id="contents">

    <!--main_-->
    <form action="{{$_SERVER['HTTP_REFERER']}}" method="get" name="form1" id="form1">
        <input type="hidden" name="prev_url_id" value="{{ isset($condition['prev_url_id']) ? $condition['prev_url_id'] : '' }}">
        <input type="hidden" name="bank_id" value="{{ isset($condition['bank_id']) ? $condition['bank_id'] : '' }}">
        <input type="hidden" name="bank_account_type" id="bank_account_type" value="{{ isset($condition['bank_account_type']) ? $condition['bank_account_type'] : '' }}">
        <input type="hidden" name="bank_branch" value="{{ isset($condition['bank_branch']) ? $condition['bank_branch'] : '' }}">
        <input type="hidden" name="bank_account_no" value="{{ isset($condition['bank_account_no']) ? $condition['bank_account_no'] : '' }}">
        <input type="hidden" name="bank_account_name" value="{{ isset($condition['bank_account_name']) ? $condition['bank_account_name'] : '' }}">
        <section>

            <div class="white_box_02">
                <div class="check-box">
                    @foreach(config('const.bank_account_type') as $key=>$val)
                        <div class="clex-box_04 sub {{ (isset($condition['bank_account_type']) && $condition['bank_account_type'] ? $condition['bank_account_type'] : '') == $key ? 'on' : '' }}">
                            <input type="submit" data-id="{{$key}}" value="{{$val}}" name="act_type_name" class="input_act_type">
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </form>

</div><!-- /contents -->

@endsection

@section('page_js')
    <script>
        $(document).ready(function () {
            $('.input_act_type').click(function() {
                let account_type = $(this).attr('data-id');
                $('#bank_account_type').val(account_type);
                $('#form1').submit();
            });
        });
    </script>
@endsection
