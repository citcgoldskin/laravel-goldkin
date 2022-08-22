@extends('admin.layouts.app')
@section('title', '更新')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.category.index')])

        {{ Form::open(["route"=>"admin.freq.category.set_public_category", "method"=>"post", "name"=>"frm_public_category", "id"=>"frm_public_category"]) }}

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <h2>カテゴリー</h2>
                        <ul class="btn-list mt0 sortableArea">
                            @foreach($categories as $category)
                                <li class="flex">
                                    <input type="hidden" value="{{ old('qc_public.'.$category['qc_id'], $category['qc_public']) }}" name="qc_public[{{ $category['qc_id'] }}]" id="qc_public_{{ $category['qc_id'] }}">
                                    <p class="release"><span data-id="{{ $category['qc_id'] }}">{{ config('const.question_category_public_status.'.old('qc_public.'.$category['qc_id'], $category['qc_public'])) }}</span></p>
                                    <input type="text" value="{{ old("qc_name.".$category['qc_id'], $category['qc_name']) }}" name="qc_name[{{ $category['qc_id'] }}]">
                                </li>
                                @error('qc_name.'.$category['qc_id'])
                                    <span class="error_text" style="margin-left: 20%;margin-bottom: 20px; margin-top: 0px; padding-top: 0px;">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            @endforeach
                        </ul>
                        <div class="hidden">
                            <div class="btn mtb">
                                <button type="submit" class="add btn_complete">完了する</button>
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
        .release {
            cursor: pointer;
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

            /*$('.btn_complete').click(function(e) {
                e.preventDefault();
            });*/

            $('p.release span').on('click', function () {
                let category_id = $(this).attr('data-id');
                if ($(this).text() === '公開') {
                    $(this).text('非公開');
                    $('#qc_public_' + category_id).val(0);
                } else {
                    $(this).text('公開');
                    $('#qc_public_' + category_id).val(1);
                }
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
