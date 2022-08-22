@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.staff_confirm.index", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">本人確認一覧</label>

            <section class="tab_area mb0">
                <div class="switch_tab">
                    <div class="type_radio radio-01">
                        <input type="radio" name="search_params[onof-line]" id="off-line" value="{{ config('const.person_confirm_browser.new') }}" {{ (isset($search_params['onof-line']) && $search_params['onof-line'] ? $search_params['onof-line'] : config('const.person_confirm_browser.new')) == config('const.person_confirm_browser.new') ? "checked" : '' }}>
                        <label class="ok" for="off-line">新規</label>
                        @php
                            $cnt_1 = \App\Service\PersonConfirmService::getRequestPersonConfirmCount(config('const.person_confirm_browser.new'));
                        @endphp
                        @if($cnt_1 > 0)
                            <span class="midoku">{{ $cnt_1 }}</span>
                        @endif
                    </div>
                    <div class="type_radio radio-02">
                        <input type="radio" name="search_params[onof-line]" id="on-line" value="{{ config('const.person_confirm_browser.change') }}" {{ (isset($search_params['onof-line']) && $search_params['onof-line'] ? $search_params['onof-line'] : '') == config('const.person_confirm_browser.change') ? "checked" : '' }}>
                        <label class="ok" for="on-line">差し替え</label>
                        @php
                            $cnt_2 = \App\Service\PersonConfirmService::getRequestPersonConfirmCount(config('const.person_confirm_browser.change'))
                        @endphp
                        @if($cnt_2 > 0)
                            <span class="midoku">{{ $cnt_2 }}</span>
                        @endif
                    </div>
                </div>
            </section>

            <section>
                <div class="flex" style="align-items: center;margin-bottom: 10px;">
                    @php
                        $from_page = ($obj_users->currentPage() - 1) * $obj_users->perPage() + 1;
                        $to_page = $obj_users->perPage() * $obj_users->currentPage();
                        if ($to_page > $obj_users->total()) {
                            $to_page = $obj_users->total();
                        }
                    @endphp
                    @if($obj_users->total() <= 1)
                        <div class="wp-50">全{{ $obj_users->total() }}件</div>
                    @else
                        <div class="wp-50">{{ $from_page }}件～{{ $to_page }}件（全{{ $obj_users->total() }}件）</div>
                    @endif
                    <div class="wp-50 form_wrap icon_form type_arrow_bottom">
                        <select class="wp-100" name="search_params[sort_type]" id="sort_type">
                            @foreach(config('const.stop_lesson_sort') as $key=>$val)
                                <option value="{{ $key }}" {{ (isset($search_params['order']) && $search_params['order'] ? $search_params['order'] : config('const.stop_lesson_sort_code.register_new')) == $key ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="tabs search-result-area">
                    <table>
                        <tbody>
                        @if(count($obj_users) > 0)
                            @foreach($obj_users as $obj_person_confirm)
                                <tr>
                                    <td class="td-detail"><a class="title_orange" href="{{ route('admin.staff_confirm.detail', ['person_confirm'=>$obj_person_confirm->pc_id]) }}">詳細</a></td>
                                    <td>
                                        <div class="flex profile" data-id="{{ $obj_person_confirm->pc_id }}">
                                            <div class="ico ico-user">
                                                <img src="{{ \App\Service\CommonService::getUserAvatarUrl($obj_person_confirm->user->user_avatar) }}">
                                            </div>
                                            <div>
                                                <p class="pb-5 ft-bold">{{ $obj_person_confirm->user->user_firstname.'　'.$obj_person_confirm->user->user_lastname }}<span>{{ "（".\App\Service\CommonService::getAge($obj_person_confirm->user->user_birthday)."）" }}</span></p>
                                                <p class="pb-5">{{ $obj_person_confirm->user->user_sex ? config('const.gender_type.'.$obj_person_confirm->user->user_sex) : '' }}&nbsp;{{ \App\Service\AreaService::getOneAreaFullName($obj_person_confirm->user->user_area_id) }}</p>
                                                <p class="">ID：{{ $obj_person_confirm->user->user_no }}</p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td>検索結果 0件</td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
                {{ $obj_users->links('vendor.pagination.senpai-admin-pagination') }}

            </section>

        </div><!-- /tabs -->
        {{ Form::close() }}


    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .sort-type {
            width: 150px;
            float:right;
            margin-bottom: 10px;
        }
        .page-title {
            margin-bottom: 20px;
        }
        .tab-label {
            font-size: 14px;
            font-weight: bold;
            padding: 10px;
            position: relative;
            background: #dbdbdb;
        }
        .active {
            background: white;
        }
        span.midoku {
            margin-left: 10px;
            right: unset;
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
        $(document).ready(function() {
            $('.datepicker').datepicker({
            });
            $('.tab-label').click(function(){
                if($(this).hasClass('tab-new')) {
                    // new
                    if($(this).hasClass('active')) {
                    } else {
                        $(this).addClass('active');
                        $('.new-user').removeClass('hide');
                        $('.tab-old').removeClass('active');
                        $('.old-user').addClass('hide');
                    }
                } else {
                    // old
                    if($(this).hasClass('active')) {
                    } else {
                        $(this).addClass('active');
                        $('.old-user').removeClass('hide');
                        $('.tab-new').removeClass('active');
                        $('.new-user').addClass('hide');
                    }
                }
            });

            $('#off-line, #on-line').click(function() {
                $('#form1').submit();
            });

            // profile
            $('.profile').click(function() {
                let person_confirm_id = $(this).attr('data-id');
                location.href = "{{ route('admin.staff_confirm.detail') }}" + "/" + person_confirm_id;
            });

            $('#sort_type').change(function() {
                let order_type = $(this).val();
                location.href="{{ route('admin.staff_confirm.index') }}?order="+order_type;
            });
        });
    </script>
@endsection
