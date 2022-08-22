@extends('admin.layouts.app')
@section('title', '削除')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.category.index')])

        {{ Form::open(["route"=>"admin.freq.category.destroy_category", "method"=>"post", "name"=>"frm_destroy_category", "id"=>"frm_destroy_category"]) }}
        <input type="hidden" name="del_id" id="del_id" value="">

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>カテゴリー</h2>
                        <ul class="btn-list mt0 sortableArea">
                            @foreach($categories as $category)
                                <li><button type="button" class="delete-btn modal-syncer" data-target="modal-caution" data-id="{{ $category['qc_id'] }}">{{ $category['qc_name'] }}</button></li>
                            @endforeach
                        </ul>
                        <div class="hidden">
                            <div class="btn mtb">
                                <button class="add btn_complete" onclick="location.href='{{ route('admin.freq.category.index') }}'">完了する</button>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div><!-- /tabs -->

        {{ Form::close() }}

        @include('admin.layouts.modal-layout', [
            'modal_id'=>"modal-caution",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"カテゴリーを削除してもよろしいですか？",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"キャンセル",
        ])

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
            $(".menu_fraud").click(function (e) {
                e.preventDefault();
                /*$("#overlay").fadeToggle('slow');
                $(".hamburger").toggleClass("menu-close");*/
                $('.main-menu').addClass('hide');
                $('.second-menu-1').removeClass('hide');
            });
            $(".new-text").on("input", function() {
                var input = $(this).val(); //input に入力された文字を取得
                if(input){ //もし文字が入っていれば
                    $(".hidden").show();
                }else{
                    $(".hidden").hide();
                }
            });

            $('.btn_complete').click(function(e) {
                e.preventDefault();
                location.href = "{{ route('admin.freq.category.index') }}";
            });

            $('.delete-btn').click(function() {
                let del_id = $(this).attr('data-id');
                $('#del_id').val(del_id);
            });
        });
        function modalConfirm(modal_id="") {
            // code
            if(modal_id == "modal-caution") {
                $('#frm_destroy_category').submit();
            }
        }

    </script>
@endsection
