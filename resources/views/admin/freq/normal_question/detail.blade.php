@extends('admin.layouts.app')
@if($frequent_type == 'all')
    @section('title', 'すべて')
@else
    @section('title', \App\Service\QuestionService::getQuestionClassName($frequent_type))
@endif

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.normal_question')])

        {{--{{ Form::open(["route"=>"admin.login", "method"=>"post", "name"=>"frm_login", "id"=>"frm_login"]) }}--}}

        <div class="tabs form_page">

            <section>
                @include('admin.layouts.flash-message')
                <div id="main-contents">
                    <div class="search-result-area">
                        <table class="total">
                            <tr>
                                <th>全<em>{{ count($question_frequents) }}</em>件</th>
                                <td>
                                    <button type="button" onclick="location.href='{{ route('admin.freq.normal_question.sort', ['frequent_type'=>$frequent_type]) }}'" class="edit sort-btn">並びかえ</button>
                                </td>
                            </tr>
                        </table>
                        <table class="total">
                            <tr>
                                <td class="text-left"><button type="button" class="new {{ count($question_frequents) >= 10 ? 'disable-btn' : '' }}" onclick="location.href='{{ route('admin.freq.normal_question.add', ['frequent_type'=>$frequent_type, 'del_session'=>1]) }}'">新規追加</button></td>
                                <td><button type="button" class="edit">編集</button></td>
                            </tr>
                        </table>
                        <ul class="faq-list">
                            @if(count($question_frequents) > 0)
                                @foreach($question_frequents as $question_frequent)
                                    <li>
                                        {{--<a href="{{ route('admin.freq.normal_question.content_data', ['frequent_type'=>$frequent_type, 'question'=>$question_frequent->obj_question->que_id]) }}">--}}
                                        <a href="" class="question-detail" data-frequent-id="{{ $question_frequent->id }}" data-id="{{ $question_frequent->que_id }}" >
                                            <dl>
                                                <dt class="three_dots"><span>Q.</span>{{ $question_frequent->que_ask }}</dt>
                                                <dd class="three_dots"><span>A.</span>{{ $question_frequent->que_answer }}</dd>
                                            </dl>
                                        </a> </li>
                                @endforeach
                            @else
                                <li>データが存在しません。</li>
                            @endif
                        </ul>
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
        .edit-active {
            background: #fb7122;
            color: white !important;
        }
        .sort-btn {
            padding: 8px 14.5px !important;
        }
        .disable-btn {
            pointer-events: none;
            background: #aaa !important;
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
            $('.edit').click(function(e) {
                if($(this).hasClass('edit-active')) {
                    $(this).removeClass('edit-active');
                } else {
                    $(this).addClass('edit-active');
                }
            });

            $('.question-detail').click(function(e) {
                e.preventDefault();
                let question_id = $(this).attr('data-id');
                if ($('.edit').hasClass('edit-active')) {
                    let frequent_id = $(this).attr('data-frequent-id');
                    let url = '{{ route('admin.freq.normal_question.change_content', ['frequent_type'=>$frequent_type, 'question'=>'param1', 'frequent_id'=>'param2']) }}'.replace("param1", question_id.toString()).replace("param2", frequent_id.toString());
                    location.href=url;
                } else {
                    let url = '{{ route('admin.freq.normal_question.content_data', ['frequent_type'=>$frequent_type, 'question'=>'param1']) }}'.replace("param1", question_id.toString());
                    location.href=url;
                }
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
