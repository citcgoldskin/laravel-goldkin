@extends('admin.layouts.app')

@section('title', '下書き・非公開')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.index')])

        {{ Form::open(["route"=>"admin.freq.no_public_question.delete", "method"=>"post", "name"=>"frm_question", "id"=>"frm_question"]) }}

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <table class="total">
                            <tr>
                                <th>全<em>{{ count($questions) }}</em>件</th>
                                <td><button type="button" class="delete">削除する</button></td>
                            </tr>
                        </table>

                        <ul class="faq-list">
                            @foreach($questions as $question)
                            <li>
                                <div class="check hide">
                                    <input type="checkbox" class="chk-question" name="chk_question[{{ $question->que_id }}]" id="chk_question_{{ $question->que_id }}"><label for="chk_question_{{ $question->que_id }}"></label>
                                </div>
                                <a href="{{ route('admin.freq.no_public_question.detail', ['question'=>$question->que_id]) }}" class="chk-a">
                                    @if($question->que_status == config('const.question_status.draft'))
                                        @if(is_null($question->que_public_at))
                                            <div class="flag-draft"><p>下書き</p></div>
                                        @else
                                            <div class="flag-draft"><p>変下書き</p></div>
                                        @endif
                                    @elseif($question->que_public == config('const.question_category_public.no_public'))
                                        <div class="flag-draft"><p>非公開</p></div>
                                    @endif
                                    <dl>
                                        <dt class="three_dots"><span>Q.</span>{{ $question->que_ask }}</dt>
                                        <dd class="three_dots"><span>A.</span>{{ $question->que_answer }}</dd>
                                    </dl>
                                </a></li>
                            @endforeach

                        </ul>
                        <p id="select_item_validate" class="error_text hide">削除項目を選択してください。</p>
                        <div class="btn mtb checked-delete hide">
                            <button type="button" class="btn_delete modal-syncer" data-target="">削除する</button>
                        </div>
                    </div>
                    {{ $questions->links('vendor.pagination.senpai-admin-pagination') }}
                </div>
            </section>

        </div><!-- /tabs -->
        @include('admin.layouts.modal-layout', [
            'modal_id'=>"modal-caution",
            'modal_type'=>config('const.modal_type.confirm'),
            'modal_title'=>"削除してよろしいですか？",
            'modal_confrim_btn'=>"OK",
            'modal_confrim_cancel'=>"キャンセル",
        ])

        {{ Form::close() }}

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/admin/css/add.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .has-chk {
            width: calc(100% - 35px) !important;
        }
        ul.faq-list li a:after {
            content: "";
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
        });

        $(function(){
            $('.delete').click(function () {
                if($('.checked-delete').hasClass('hide')) {
                    $('.checked-delete').removeClass('hide')
                } else {
                    $('.checked-delete').addClass('hide')
                }
                if($('.check').hasClass('hide')) {
                    $('.check').removeClass('hide')
                    $('.chk-a').addClass('has-chk');
                } else {
                    $('.check').addClass('hide')
                    $('.chk-a').removeClass('has-chk');
                }

                if ($(this).text() === '削除する') {
                    $(this).text('完了');
                } else {
                    $(this).text('削除する');
                }
            });

            $('.chk-question').change(function(){
                let chk_length = $('.chk-question:checked').length;
                if (chk_length > 0) {
                    $('.btn_delete').attr('data-target', "modal-caution");
                } else {
                    $('.btn_delete').attr('data-target', "");
                }
            });

            $('.btn_delete').click(function(e) {
                e.preventDefault();
                if(!$('#select_item_validate').hasClass('hide')) {
                    $('#select_item_validate').addClass('hide');
                }

                let chk_length = $('.chk-question:checked').length;
                if (chk_length < 1) {
                    if($('#select_item_validate').hasClass('hide')) {
                        $('#select_item_validate').removeClass('hide');
                    }
                }
            });
        });

        function modalConfirm(modal_id="") {
            // code
            if(modal_id == "modal-caution") {
                $('#frm_question').submit();
            }
        }
    </script>
@endsection
