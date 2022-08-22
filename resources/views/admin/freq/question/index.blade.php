@extends('admin.layouts.app')

@section('title', 'よくある質問一覧')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.index')])

        {{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}

        <div class="tabs form_page">

            <section>
                <div id="main-contents">
                    <div class="search-result-area">
                        <table class="total">
                            <tr>
                                <th>全<em>{{ count($questions) }}</em>件</th>
                                <td><div class="select-box w-short">
                                        <select class="extraction" name="category" id="category">
                                            <option value="all" {{ (isset($params['category']) ? $params['category'] : '') == 'all' ? 'selected' : '' }}>すべて</option>
                                            @foreach($categories as $category)
                                                <option value="{{ $category['qc_id'] }}" {{ (isset($params['category']) ? $params['category'] : '') == $category['qc_id'] ? 'selected' : '' }}>{{ $category['qc_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div></td>
                            </tr>
                        </table>
                        <div class="sort sub-category-area {{ count($sub_categories) > 0 ? : 'hide' }}">
                            <table class="total">
                                <tr>
                                    <th>絞り込み</th>
                                    <td><div class="select-box w-short">
                                            <select name="sub_category" id="sub_category">
                                                <option value="all" {{ (isset($params['sub_category']) ? $params['sub_category'] : '') == 'all' ? 'selected' : '' }}>すべて</option>
                                                @foreach($sub_categories as $category)
                                                    <option value="{{ $category['qc_id'] }}" {{ (isset($params['sub_category']) ? $params['sub_category'] : '') == $category['qc_id'] ? 'selected' : '' }}>{{ $category['qc_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div></td>
                                </tr>
                            </table>
                        </div>
                        <ul class="faq-list">
                            @if(count($questions) > 0)
                                @foreach($questions as $question)
                                    <li><a href="{{ route('admin.freq.question.detail', ['question'=>$question->que_id]) }}">
                                        <dl>
                                            <dt class="three_dots"><span>Q.</span>{{ $question->que_ask }}</dt>
                                            <dd class="three_dots"><span>A.</span>{{ $question->que_answer }}</dd>
                                        </dl>
                                    </a></li>
                                @endforeach
                            @else
                                <li>データが存在しません。</li>
                            @endif
                        </ul>
                    </div>
                {{ $questions->links('vendor.pagination.senpai-admin-pagination') }}
                </div>
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
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
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function () {
        });

        $(function(){

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

                location.href="{{ route('admin.freq.question') }}?category="+category+"&sub_category=all";

                /*$.ajax({
                    type: "post",
                    url: "{{route('admin.freq.get_sub_category')}}",
                    data : {
                        _token: "{{ csrf_token() }}",
                        category_id: category
                    },
                    dataType: 'json',
                    success : function(result) {

                        var html_data = html_data = '<option value="0">すべて</option>';;
                        if(result.result_code == "success") {
                            let data = result.result;
                            for(let key in data)
                            {
                                html_data += '<option value="' + data[key].qc_id + '">' + data[key].qc_name + '</option>';
                            }
                        }

                        $('#sub_category').html(html_data);
                    }
                });*/
            });

            $("#sub_category").change(function() {
                let category = $('#category').val();
                let sub_category = $(this).val();

                location.href="{{ route('admin.freq.question') }}?category="+category+"&sub_category="+sub_category;
            });
        });
    </script>
@endsection
