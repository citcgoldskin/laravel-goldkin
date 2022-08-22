@extends('admin.layouts.app')
@section('title', '各予約一覧')

@section('content')
    <div id="contents">
        @include('admin.layouts.header_under', ['action_url'=>route('admin.freq.index')])

        {{--{{ Form::open(["route"=>"admin.freq.new_question.create", "method"=>"post", "name"=>"frm_new_question", "id"=>"frm_new_question"]) }}--}}

        <div class="tabs form_page">

            <section>
                <div id="main-contents">
                    <div class="search-result-area">
                        <div class="btn_area">
                            <p class="tab_btn {{ isset($params['reserve_type']) && $params['reserve_type'] == config('const.reserve_type_code.public') ? 'active' : '' }}" data-type="{{ config('const.reserve_type_code.public') }}">公開</p>
                            <p class="tab_btn {{ isset($params['reserve_type']) && $params['reserve_type'] == config('const.reserve_type_code.update') ? 'active' : '' }}" data-type="{{ config('const.reserve_type_code.update') }}">変更</p>
                            <p class="tab_btn {{ isset($params['reserve_type']) && $params['reserve_type'] == config('const.reserve_type_code.delete') ? 'active' : '' }}" data-type="{{ config('const.reserve_type_code.delete') }}">削除</p>
                        </div>
                        <div class="panel_area">
                            <div class="tab_panel {{ isset($params['reserve_type']) && $params['reserve_type'] == config('const.reserve_type_code.public') ? 'active' : '' }}">
                                <p>全<em>{{ count($questions) }}</em>件</p>
                                <ul class="faq-list">
                                    @if(count($questions) > 0)
                                        @foreach($questions as $question)
                                            <li> <a href="{{ route('admin.freq.reserve_question.edit', ['question'=>$question->que_id, 'reserve_type'=>config('const.reserve_type_code.public')]) }}">
                                                    <dl>
                                                        <dt class="three_dots"><span>Q.</span>{{ $question->que_ask }}</dt>
                                                        <dd class="three_dots"><span>A.</span>{{ $question->que_answer }}</dd>
                                                    </dl>
                                                    <p class="timer">{{ \Carbon\Carbon::parse($question->que_public_at)->format('n月j日 H:i') }} 公開予定</p>
                                                </a> </li>
                                        @endforeach
                                    @else
                                        <li>データが存在しません。</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="tab_panel {{ isset($params['reserve_type']) && $params['reserve_type'] == config('const.reserve_type_code.update') ? 'active' : '' }}">
                                <p>全<em>{{ count($questions) }}</em>件</p>
                                <ul class="faq-list">
                                    @if(count($questions) > 0)
                                        @foreach($questions as $question)
                                            @php
                                                $update_question = $question->que_update_data ? json_decode($question->que_update_data, true) : null;
                                            @endphp
                                            <li> <a href="{{ route('admin.freq.reserve_question.edit', ['question'=>$question->que_id, 'reserve_type'=>config('const.reserve_type_code.update')]) }}">
                                                    <dl>
                                                        <dt class="three_dots"><span>Q.</span>{{ isset($update_question) && $update_question['que_ask'] ? $update_question['que_ask'] : '' }}</dt>
                                                        <dd class="three_dots"><span>A.</span>{{ isset($update_question) && $update_question['que_answer'] ? $update_question['que_answer'] : '' }}</dd>
                                                    </dl>
                                                    <p class="timer">{{ \Carbon\Carbon::parse($question->que_update_at)->format('n月j日 H:i') }} 変更予定</p>
                                                </a> </li>
                                        @endforeach
                                    @else
                                        <li>データが存在しません。</li>
                                    @endif
                                </ul>
                            </div>
                            <div class="tab_panel {{ isset($params['reserve_type']) && $params['reserve_type'] == config('const.reserve_type_code.delete') ? 'active' : '' }}">
                                <p>全<em>{{ count($questions) }}</em>件</p>
                                <ul class="faq-list">
                                    @if(count($questions) > 0)
                                        @foreach($questions as $question)
                                            <li> <a href="{{ route('admin.freq.reserve_question.edit', ['question'=>$question->que_id, 'reserve_type'=>config('const.reserve_type_code.delete')]) }}">
                                                    <dl>
                                                        <dt class="three_dots"><span>Q.</span>{{ $question->que_ask }}</dt>
                                                        <dd class="three_dots"><span>A.</span>{{ $question->que_answer }}</dd>
                                                    </dl>
                                                    <p class="timer">{{ \Carbon\Carbon::parse($question->que_delete_at)->format('n月j日 H:i') }} 削除予定</p>
                                                </a> </li>
                                        @endforeach
                                    @else
                                        <li>データが存在しません。</li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                    {{ $questions->links('vendor.pagination.senpai-admin-pagination') }}
                </div>
            </section>

        </div><!-- /tabs -->

        {{--{{ Form::close() }}--}}

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
    <script>
        $(document).ready(function () {

            $(' .tab_btn').click(function() {
                let data_type = $(this).data('type');
                location.href='{{ route('admin.freq.reserve_question') }}'+'/?reserve_type='+data_type;
                /*var index = $('.tab_btn').index(this);
                $('.tab_btn,  .tab_panel').removeClass('active');
                $(this).addClass('active');
                $('.tab_panel').eq(index).addClass('active');*/
            });
        });

    </script>
@endsection
