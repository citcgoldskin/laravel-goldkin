@extends('admin.layouts.app')
@section('title', '並び替え')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.sub_category.index', ['category'=>$category_id])])

        {{ Form::open(["route"=>"admin.freq.sub_category.set_sort_category", "method"=>"post", "name"=>"frm_sort_category", "id"=>"frm_sort_category"]) }}
        <input type="hidden" name="category_id" value="{{ $category_id }}">

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>サブカテゴリー</h2>
                        <ul class="btn-list mt0 sortableArea category-sortable-area">
                            @foreach($categories as $key=>$category)
                                <input type="hidden" name="category_sorts[]" id="category_sort_{{ $category['qc_id'] }}" value="{{ $category['qc_id'] }}">
                                <li>
                                    <button type="button" id="btn_category_{{ $key + 1 }}" data-sort="{{ $key + 1 }}" data-category="{{ $category['qc_id'] }}">{{ $category['qc_name'] }}</button>
                                    @if($key != 0)
                                        <div class="arrow_up {{ $key == (count($categories) -1) ? 'mid-arrow' : '' }}" data-sort="{{ $key + 1 }}"></div>
                                    @endif
                                    @if($key != count($categories) -1)
                                        <div class="arrow_down {{ $key == 0 ? 'mid-arrow' : '' }}" data-sort="{{ $key + 1 }}"></div>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                        <div class="hidden">
                            <div class="btn mtb">
                                <button type="submit" class="add">確定する</button>
                            </div>
                            <button type="reset" class="cancel2" onclick="location.href='{{ route('admin.freq.sub_category.index', ['category'=>$category_id]) }}'">キャンセル</button>
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
            $('.arrow_up').click(function(e) {
                let data_sort = $(this).attr('data-sort');
                let swap_sort = parseInt(data_sort) - 1;
                /*console.log("data_sort", data_sort);
                console.log("swap_sort", swap_sort);*/

                let btn_category = $('#btn_category_' + data_sort).attr('data-category');
                let btn_name = $('#btn_category_' + data_sort).text();
                let btn_sort = $('#category_sort_' + btn_category).val();
                /*console.log("btn_category", btn_category);
                console.log("btn_name", btn_name);
                console.log("btn_sort", btn_sort);
                console.log('swap_category', $('#btn_category_' + swap_sort).attr('data-category'));
                console.log("swap_text", $('#btn_category_' + swap_sort).text());
                console.log("swap_sort", $('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val());*/

                $('#btn_category_' + data_sort).data('category', $('#btn_category_' + swap_sort).attr('data-category'))
                $('#btn_category_' + data_sort).text($('#btn_category_' + swap_sort).text());
                $('#category_sort_' + btn_category).val($('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val());

                $('#btn_category_' + swap_sort).data('category', btn_category)
                $('#btn_category_' + swap_sort).text(btn_name);
                $('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val(btn_sort);

            });
            $('.arrow_down').click(function(e) {
                let data_sort = $(this).attr('data-sort');
                let swap_sort = parseInt(data_sort) + 1;

                let btn_category = $('#btn_category_' + data_sort).attr('data-category');
                let btn_name = $('#btn_category_' + data_sort).text();
                let btn_sort = $('#category_sort_' + btn_category).val();

                $('#btn_category_' + data_sort).data('category', $('#btn_category_' + swap_sort).attr('data-category'))
                $('#btn_category_' + data_sort).text($('#btn_category_' + swap_sort).text());
                $('#category_sort_' + btn_category).val($('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val());

                $('#btn_category_' + swap_sort).data('category', btn_category)
                $('#btn_category_' + swap_sort).text(btn_name);
                $('#category_sort_' + $('#btn_category_' + swap_sort).attr('data-category')).val(btn_sort);
            });
        });

    </script>
@endsection
