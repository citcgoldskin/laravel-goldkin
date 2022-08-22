@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        {{ Form::open(["route"=>"admin.fraud_piro.index", "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
        <input type="hidden" name="clear_condition" id="clear_condition" value="">
        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">ぴろしきまる</label>
            <section>
                <div class="staff-search-area">
                    <div class="">
                        <h3 class="icon_form type_arrow_top type_arrow opened">絞り込み</h3>
                    </div>
                    <div class="search-condition">
                        <div class="inner_box for-warning">
                            <h3>会員種別</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[user_type]" id="user_type">
                                    <option value="">--</option>
                                    @foreach(config('const.user_type_label') as $key=>$user_type)
                                        <option value="{{ $key }}" {{ (isset($search_params['user_type']) && !is_null($search_params['user_type']) ? $search_params['user_type'] : '') == $key ? 'selected' : '' }}>{{ $user_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>性別</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[user_sex]" id="user_sex">
                                    <option value="">--</option>
                                    @foreach(config('const.gender_type') as $k => $v)
                                        <option value="{{ $k }}" {{ $k == (isset($search_params['user_sex']) ? $search_params['user_sex'] : '')  ? "selected": ""}}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>エリア</h3>
                            @php
                                $prefecture_list = \App\Service\AreaService::getPrefectureList();
                            @endphp
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[area]" id="area">
                                    <option value="">--</option>
                                    @foreach($prefecture_list as $prefecture)
                                        <option value="{{ $prefecture->area_id }}" {{ (isset($search_params['area']) && !is_null($search_params['area']) ? $search_params['area'] : '') == $prefecture->area_id ? 'selected' : '' }}>{{ $prefecture->area_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>期間</h3>
                            <div class="flex flex-wrap period-area">
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay mr-10">
                                    <input type="text" name="search_params[from_date]" id="from_date" class="form_btn datepicker" value="{{ isset($search_params['from_date']) ? $search_params['from_date'] : '' }}">
                                </div>
                                <div class="flex-space mr-10">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom shadow-glay">
                                    <input type="text" name="search_params[to_date]" id="to_date" class="form_btn datepicker" value="{{ isset($search_params['to_date']) ? $search_params['to_date'] : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ID</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="user_no" name="search_params[user_no]" value="{{ isset($search_params['user_no']) ? $search_params['user_no'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ニックネーム</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="nickname" name="search_params[nickname]" value="{{ isset($search_params['nickname']) ? $search_params['nickname'] : '' }}">
                            </div>
                        </div>
                        {{--<div class="inner_box for-warning">
                            <h3>ぴろしきまるのみ表示</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[hirosi]" id="hirosi">
                                    <option value="">--</option>
                                </select>
                            </div>
                        </div>--}}
                        <div class="flex" style="margin-top: 20px">
                            <div class="wp-35" style="padding-right: 5px;">
                                <button id="btn_clear" class="btn btn-clear wp-100" name="btn_clear">クリア</button>
                            </div>
                            <div class="wp-65 pos-relative">
                                <button id="btn_search" class="btn btn-search wp-100" name="btn_search">この条件で検索</button>
                            </div>
                        </div>
                    </div>
                </div>

            </section>

            <section>
                {{--<div class="chk-rect mb-10">
                    <input type="checkbox" value="" name="chk-not-read" id="chk-not-read">
                    <label for="chk-not-read">未読のみを表示する</label>
                </div>--}}
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
                            @foreach($obj_users as $obj_piro)
                                <tr>
                                    <td class="td-detail"><a class="title_orange" href="{{ route('admin.fraud_piro.detail', ['punishment'=>$obj_piro->id]) }}">詳細</a></td>
                                    <td>
                                        <div class="flex profile" data-id="{{ $obj_piro->id }}">
                                            <div class="ico ico-user">
                                                <img src="{{ \App\Service\CommonService::getUserAvatarUrl($obj_piro->user->user_avatar) }}">
                                            </div>
                                            <div>
                                                <p class="pb-5 ft-bold">{{ $obj_piro->user->user_firstname.'　'.$obj_piro->user->user_lastname }}<span>{{ "（".\App\Service\CommonService::getAge($obj_piro->user->user_birthday)."）" }}</span></p>
                                                <p class="pb-5">{{ $obj_piro->user->user_sex ? config('const.gender_type.'.$obj_piro->user->user_sex) : '' }}&nbsp;{{ \App\Service\AreaService::getOneAreaFullName($obj_piro->user->user_area_id) }}</p>
                                                <p class="pb-5">{{ config('const.punishment_decision.'.$obj_piro->type) }}</p>
                                                <p class="">決定日：{{ \Carbon\Carbon::parse($obj_piro->decided_at)->format('Y.n.j') }}</p>
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
        .period-area .form_wrap {
            width: 140px;
        }
        .cnt_count {
            justify-content: flex-end;
            font-weight: bold;
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
        .ui-datepicker-current {
            display: none;
        }
    </style>
@endsection
@section('page_js')
    <script src="{{ asset('assets/vendor/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/jquery-ui/i18n/datepicker-ja.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.datepicker').datepicker({
                showButtonPanel: true,
                closeText: '指定なし',
                onClose: function (dateText, inst) {
                    if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
                        document.getElementById(this.id).value = '';
                    }
                }
            });

            // 検索条件
            $('.type_arrow').click(function(){
                if($(this).hasClass('opened')) {
                    $(this).removeClass('opened');
                    $(this).removeClass('type_arrow_top');
                    $(this).addClass('closed');
                    $(this).addClass('type_arrow_bottom');
                    $('.search-condition').addClass("hide");
                } else {
                    $(this).addClass('opened');
                    $(this).addClass('type_arrow_top');
                    $(this).removeClass('closed');
                    $(this).removeClass('type_arrow_bottom');
                    $('.search-condition').removeClass("hide");
                }
            });

            $('#btn_clear').click(function() {
                $('#clear_condition').val(1);
                $('#form1').submit();
            });

            // profile
            $('.profile').click(function() {
                let user_id = $(this).attr('data-id');
                location.href = "{{ route('admin.fraud_piro.detail') }}" + "/" + user_id;
            });

            // 未読のみを表示する
            $('#chk-not-read').change(function() {

            });

            $('#sort_type').change(function() {
                let order_type = $(this).val();
                location.href="{{ route('admin.fraud_piro.index') }}?order="+order_type;
            });
        });
    </script>
@endsection
