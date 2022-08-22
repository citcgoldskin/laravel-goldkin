@extends('admin.layouts.app')
@section('title', '並び替え')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.normal_question.detail', ['frequent_type'=>$frequent_type])])

        {{ Form::open(["route"=>"admin.freq.normal_question.set_sort", "method"=>"post", "name"=>"frm_sort_category", "id"=>"frm_sort_category"]) }}
        <input type="hidden" name="frequent_type" value="{{ $frequent_type }}">

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>よく見られている質問</h2>
                        <ul class="btn-list mt0 sortableArea category-sortable-area">
                            @foreach($question_frequents as $key=>$question_frequent)
                                <input type="hidden" name="category_sorts[]" id="category_sort_{{ $question_frequent->id }}" value="{{ $question_frequent->id }}">
                                <li>
                                    <a href="" class="a-sort">
                                        <dl class="qa-area" id="btn_category_{{ $key + 1 }}" data-sort="{{ $key + 1 }}" data-category="{{ $question_frequent->id }}">
                                            <dt class="three_dots"><span>Q.</span>{{ $question_frequent->que_ask }}</dt>
                                            <dd class="three_dots"><span>A.</span>{{ $question_frequent->que_answer }}</dd>
                                        </dl>
                                    </a>
                                    {{--<button type="button" id="btn_category_{{ $key + 1 }}" data-sort="{{ $key + 1 }}" data-category="{{ $question_frequent->id }}">{{ $question_frequent->que_ask }}</button>--}}
                                    @if($key != 0)
                                        <div class="arrow_up {{ $key == (count($question_frequents) -1) ? 'mid-arrow' : '' }}" data-sort="{{ $key + 1 }}"></div>
                                    @endif
                                    @if($key != count($question_frequents) -1)
                                        <div class="arrow_down {{ $key == 0 ? 'mid-arrow' : '' }}" data-sort="{{ $key + 1 }}"></div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="hidden">
                            <div class="btn mtb">
                                <button type="submit" class="add">確定する</button>
                            </div>
                            <button type="reset" class="cancel2" onclick="location.href='{{ route('admin.freq.normal_question.detail', ['frequent_type'=>$frequent_type]) }}'">キャンセル</button>
                        </div>
                    </div>
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
        .qa-area {
            width: calc(100% - 60px);
        }
        .category-sortable-area li a:after {
            content: "" !important;
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
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('.a-sort').click(function(e) {
                e.preventDefault();
            });
            $('.arrow_up').click(function(e) {
                let data_sort = $(this).attr('data-sort');
                let swap_sort = parseInt(data_sort) - 1;

                let btn_category = $('#btn_category_' + data_sort).attr('data-category');
                let btn_name = $('#btn_category_' + data_sort).html();
                let btn_sort = $('#category_sort_' + btn_category).val();

                $('#btn_category_' + data_sort).data('category', $('#btn_category_' + swap_sort).attr('data-category'))
                $('#btn_category_' + data_sort).html($('#btn_category_' + swap_sort).html());
                $('#category_sort_' + btn_category).val($('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val());

                $('#btn_category_' + swap_sort).data('category', btn_category)
                $('#btn_category_' + swap_sort).html(btn_name);
                $('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val(btn_sort);

            });
            $('.arrow_down').click(function(e) {
                let data_sort = $(this).attr('data-sort');
                let swap_sort = parseInt(data_sort) + 1;

                let btn_category = $('#btn_category_' + data_sort).attr('data-category');
                let btn_name = $('#btn_category_' + data_sort).html();
                let btn_sort = $('#category_sort_' + btn_category).val();

                $('#btn_category_' + data_sort).data('category', $('#btn_category_' + swap_sort).attr('data-category'))
                $('#btn_category_' + data_sort).html($('#btn_category_' + swap_sort).html());
                $('#category_sort_' + btn_category).val($('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val());

                $('#btn_category_' + swap_sort).data('category', btn_category)
                $('#btn_category_' + swap_sort).html(btn_name);
                $('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val(btn_sort);
            });
        });

    </script>
@endsection
