@extends('admin.layouts.app')
@if(isset($frequent_id) && $frequent_id)
    @section('title', '編集')
@else
    @section('title', '新規追加')
@endif

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.normal_question.detail', ['frequent_type'=>$frequent_type])])

        {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}

        <div class="tabs form_page">

            <section>
                <div id="main-contents">
                    <div class="search-result-area">
                        <table class="total">
                            <tr>
                                <th>カテゴリー</th>
                                <td><div class="select-box w-short">
                                        <select class="extraction {{ $frequent_type != 'all' ? 'disable-op' : '' }}" name="category" id="category">
                                            <option value="0">選択してください</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category['qc_id'] }}" {{ (isset($params['category']) ? $params['category'] : '') == $category['qc_id'] ? 'selected' : '' }}>{{ $category['qc_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div></td>
                            </tr>
                        </table>
                        <div class="sort sub-category-area {{ isset($params['category']) && $params['category'] && $params['category'] != 'all' ? : 'hide' }}">
                            <table class="total">
                                <tr>
                                    <th>絞り込み</th>
                                    <td><div class="select-box w-short">
                                            <select name="sub_category" id="sub_category">
                                                <option value="0">選択してください</option>
                                                @foreach($sub_categories as $category)
                                                    <option value="{{ $category['qc_id'] }}" {{ (isset($params['sub_category']) ? $params['sub_category'] : '') == $category['qc_id'] ? 'selected' : '' }}>{{ $category['qc_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div></td>
                                </tr>
                            </table>
                        </div>
                        <div class="question-area">
                            @if(!is_null($questions) && count($questions) > 0)
                                @if(isset($frequent_id) && $frequent_id)
                                    @include('admin.freq.normal_question.question_list', ['frequent_type'=>$frequent_type, 'questions'=>$questions, 'frequent_id'=>$frequent_id])
                                @else
                                    @include('admin.freq.normal_question.question_list', ['frequent_type'=>$frequent_type, 'questions'=>$questions])
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </section>

        </div><!-- /tabs -->

        {{--{{ Form::open(["route"=>"admin.freq.question.set_no_public", "method"=>"post", "name"=>"frm_no_public", "id"=>"frm_no_public"]) }}
            <input type="hidden" name="question_id" value="{{ $question->que_id }}">
        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"modal-caution",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"非公開にしてもよろしいですか？",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"キャンセル",
        ])--}}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <style>
        .faq-list .selected {
            background: rgba(251,113,34,.15);
            color: #fb7122;
            cursor: unset;
        }
        .disable-op {
            pointer-events: none;
        }
        .w-short {
            width: 210px;
        }
        .info_ttl_wrap {
            justify-content: flex-start;
        }
        .tab_item {
            border-bottom: 2px solid #f1f1f1 !important;
            border-top: 2px solid #dad8d6 !important;
        }
        section {
            padding-top: 10px !important;
        }
        .ui-datepicker {
            font-size: 16px;
        }
        h3 {
            font-weight: normal;
        }
        h3.closed {
            margin-bottom: 0px;
        }
        .search-result-area {
            padding: 15px;
            background: white;
        }
        table {
            width: 100%;
        }
        td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        .td-detail {
            text-align: center;
            vertical-align: middle;
            width: 55px;
        }
        .ico-user {
            width: 50px;
            margin-right: 5px;
        }
    </style>
@endsection
@section('page_js')
    <script>
        $(document).ready(function () {
        });

        $(function(){
            $('.faq-list .selected').click(function(e) {
                e.preventDefault();
            });
            $("#category").change(function() {
                let category = $(this).val();

                if(category == 0) {
                    if(!$('.sub-category-area').hasClass('hide')) {
                        $('.sub-category-area').addClass('hide');
                    }
                } else {
                    if($('.sub-category-area').hasClass('hide')) {
                        $('.sub-category-area').removeClass('hide');
                    }
                }

                $.ajax({
                    type: "post",
                    url: "{{route('admin.freq.get_sub_category')}}",
                    data : {
                        _token: "{{ csrf_token() }}",
                        category_id: category
                    },
                    dataType: 'json',
                    success : function(result) {

                        var html_data = html_data = '<option value="0">選択してください</option>';;
                        if(result.result_code == "success") {
                            let data = result.result;
                            for(let key in data)
                            {
                                html_data += '<option value="' + data[key].qc_id + '">' + data[key].qc_name + '</option>';
                            }
                        }

                        $('#sub_category').html(html_data);
                    }
                });

                {{--location.href="{{ route('admin.freq.question') }}?category="+category+"&sub_category=all";--}}
            });

            $("#sub_category").change(function() {
                let category = $('#category').val();
                let sub_category = $(this).val();

                if(sub_category == 0) {
                    if(!$('.faq-list').hasClass('hide')) {
                        $('.faq-list').addClass('hide');
                    }
                } else {
                    if($('.faq-list').hasClass('hide')) {
                        $('.faq-list').removeClass('hide');
                    }
                }

                $.ajax({
                    type: "post",
                    url: "{{route('admin.freq.get_question_ajax')}}",
                    data : {
                        _token: "{{ csrf_token() }}",
                        frequent_type: '{{ $frequent_type }}',
                        frequent_id: '{{ isset($frequent_id) ? $frequent_id : 0 }}',
                        category_id: category,
                        sub_category_id: sub_category
                    },
                    dataType: 'json',
                    success : function(result) {

                        if(result.result_code == "success") {
                            $('.question-area').html(result.result);
                        }

                    }
                });

                {{--location.href="{{ route('admin.freq.question') }}?category="+category+"&sub_category="+sub_category;--}}
            });
        });

        function modalConfirm(modal_id="") {
            // code
            if(modal_id == "modal-caution") {
                $('#frm_no_public').submit();
            }
        }
    </script>
@endsection
