@extends('user.layouts.app')

@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents">

    <!--main_-->
    {{ Form::open(["name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" name="prev_url_id" value="{{ isset($condition['prev_url_id']) ? $condition['prev_url_id'] : '' }}">
        <input type="hidden" name="bank_id" id="bank_id" value="{{ isset($condition['bank_id']) ? $condition['bank_id'] : '' }}">
        <input type="hidden" name="bank_account_type" value="{{ isset($condition['bank_account_type']) ? $condition['bank_account_type'] : '' }}">
        <input type="hidden" name="bank_branch" value="{{ isset($condition['bank_branch']) ? $condition['bank_branch'] : '' }}">
        <input type="hidden" name="bank_account_no" value="{{ isset($condition['bank_account_no']) ? $condition['bank_account_no'] : '' }}">
        <input type="hidden" name="bank_account_name" value="{{ isset($condition['bank_account_name']) ? $condition['bank_account_name'] : '' }}">

        <section>
            <div class="inner_box">
                <h3>{{ $alpha }}</h3>
                <ul class="white_box_03">
                    @foreach($bank_list as $k=> $v)
                        <li>
                            <p><a href="{{ route('user.myaccount.account', ['prev_url_id'=>$condition['prev_url_id'], 'condition' => $condition, 'bank_name' => $v['name'], 'bank_id'=>$v['id']]) }}" class="a_bank" data-bank="{{ $v['id'] }}">{{$v['name']}}</a></p>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    {{ Form::close() }}

</div><!-- /contents -->

@endsection

@section('page_js')
    <script>
        $(document).ready(function () {
            $('.a_bank').click(function(e) {
                e.preventDefault();
                let bank_id = $(this).attr('data-bank');
                $('#bank_id').val(bank_id);

                $('#form1').attr('action', "{{ route('user.myaccount.account', ['prev_url_id'=>$condition['prev_url_id']]) }}");
                $('input[name="_token"]').prop('disabled', true);
                $('#form1').attr('method', "get");
                $('#form1').submit();
            });
        });
    </script>
@endsection
