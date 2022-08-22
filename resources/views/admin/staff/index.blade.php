@extends('admin.layouts.app')

@section('content')
    <div id="contents">

        <div class="tabs form_page">
            <label class="page-title" style="width: 100%;">一覧情報</label>
            <section>
                {{ Form::open(["route"=>["admin.staff.index"], "method"=>"get", "name"=>"form1", "id"=>"form1"]) }}
                <input type="hidden" name="clear_condition" id="clear_condition" value="">
                <div class="staff-search-area">
                    <div class="">
                        <h3 class="icon_form type_arrow_top type_arrow opened">絞り込み</h3>
                    </div>
                    <div class="search-condition">
                        <div class="inner_box for-warning">
                            <h3>メールアドレス</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="email" name="search_params[email]" value="{{ isset($search_params['email']) ? $search_params['email'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ID</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="user_no" name="search_params[user_no]" value="{{ isset($search_params['user_no']) ? $search_params['user_no'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>電話番号</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="user_phone" name="search_params[user_phone]" value="{{ isset($search_params['user_phone']) ? $search_params['user_phone'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>氏名</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="user_name" name="search_params[user_name]" value="{{ isset($search_params['user_name']) ? $search_params['user_name'] : '' }}">
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>ニックネーム</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="nickname" name="search_params[nickname]" value="{{ isset($search_params['nickname']) ? $search_params['nickname'] : '' }}">
                            </div>
                        </div>
                        {{--<div class="inner_box for-warning">
                            <h3>登録地</h3>
                            <div class="input-text2 lesson_ttl_textarea">
                                <input type="text" id="nickname" name="search_params[nickname]" value="{{ isset($search_params['nickname']) ? $search_params['nickname'] : '' }}">
                            </div>
                        </div>--}}

                        <div class="inner_box for-warning">
                            <h3>年代</h3>
                            <div class="flex flex-wrap date-area">
                                <div class="form_wrap icon_form type_arrow_bottom" style="width:120px;">
                                    <select name="search_params[from_age]" id="from_age">
                                        <option value="">--</option>
                                        @foreach(range(10, 70, 10) as $val)
                                            @php
                                                $age = '';
                                                if (isset($search_params['from_age']) && $search_params['from_age']) {
                                                    $age = (int)($search_params['from_age'] / 10);
                                                    if ($age == 0) {
                                                        $age = 10;
                                                    } else if($age > 7) {
                                                        $age = 70;
                                                    } else {
                                                        $age = $age * 10;
                                                    }
                                                }
                                            @endphp
                                            <option value="{{$val}}" {{ old('from_age', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mr-10 ml-10">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom" style="width:120px;">
                                    <select name="search_params[to_age]" id="to_age">
                                        <option value="">--</option>
                                        @foreach(range(10, 70, 10) as $val)
                                            @php
                                                $age = '';
                                                if (isset($search_params['to_age']) && $search_params['to_age']) {
                                                    $age = (int)($search_params['to_age'] / 10);
                                                    if ($age == 0) {
                                                        $age = 10;
                                                    } else if($age > 7) {
                                                        $age = 70;
                                                    } else {
                                                        $age = $age * 10;
                                                    }
                                                }
                                            @endphp
                                            <option value="{{$val}}" {{ old('to_age', $age) == $val ? 'selected' : '' }}>{{ $val.($val == 70 ? "代以上" : "代") }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>性別</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[user_sex]" id="user_sex">
                                    <option value="">--</option>
                                    @foreach(config('const.gender_type') as $k => $v)
                                        @if($k)
                                            <option value="{{ $k }}" {{ $k == (isset($search_params['user_sex']) ? $search_params['user_sex'] : '')  ? "selected": ""}}>{{ $v }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>登録日</h3>
                            <div class="flex flex-wrap date-area">
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 120px;">
                                    <input type="text" value="{{ isset($search_params['from_register_at']) ? $search_params['from_register_at'] : "" }}" class="input-text datepicker datepicker_period mr10" name="search_params[from_register_at]" id="from_register_at">
                                </div>
                                <div class="mr-10 ml-10">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 120px;">
                                    <input type="text" value="{{ isset($search_params['to_register_at']) ? $search_params['to_register_at'] : "" }}" class="input-text datepicker datepicker_period mr10" name="search_params[to_register_at]" id="to_register_at">
                                </div>
                            </div>
                        </div>
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
                            <h3>購入件数</h3>
                            <div class="flex flex-wrap date-area">
                                <div class="input-text2 lesson_ttl_textarea" style="width: 120px;">
                                    <input type="text" id="from_number_purchase" name="search_params[from_number_purchase]" value="{{ isset($search_params['from_number_purchase']) ? $search_params['from_number_purchase'] : '' }}">
                                </div>
                                <div class="mr-10 ml-10">～</div>
                                <div class="input-text2 lesson_ttl_textarea" style="width: 120px;">
                                    <input type="text" id="to_number_purchase" name="search_params[to_number_purchase]" value="{{ isset($search_params['to_number_purchase']) ? $search_params['to_number_purchase'] : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>販売回数</h3>
                            <div class="flex flex-wrap date-area">
                                <div class="input-text2 lesson_ttl_textarea" style="width: 120px;">
                                    <input type="text" id="from_number_sale" name="search_params[from_number_sale]" value="{{ isset($search_params['from_number_sale']) ? $search_params['from_number_sale'] : '' }}">
                                </div>
                                <div class="mr-10 ml-10">～</div>
                                <div class="input-text2 lesson_ttl_textarea" style="width: 120px;">
                                    <input type="text" id="to_number_sale" name="search_params[to_number_sale]" value="{{ isset($search_params['to_number_sale']) ? $search_params['to_number_sale'] : '' }}">
                                </div>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>平均評価（センパイ）</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[avg_senpai]" id="avg_senpai">
                                    <option value="">--</option>
                                    @foreach(config('const.average_evalution') as $key=>$value)
                                        <option value="{{ $key }}" {{ (isset($search_params['avg_senpai']) && $search_params['avg_senpai'] ? $search_params['avg_senpai'] : '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>平均評価（コウハイ）</h3>
                            <div class="form_wrap icon_form type_arrow_bottom">
                                <select name="search_params[avg_kouhai]" id="avg_kouhai">
                                    <option value="">--</option>
                                    @foreach(config('const.average_evalution') as $key=>$value)
                                        <option value="{{ $key }}" {{ (isset($search_params['avg_kouhai']) && $search_params['avg_kouhai'] ? $search_params['avg_kouhai'] : '') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        {{--<div class="inner_box for-warning">
                            <h3>集計期間</h3>
                            <div class="flex flex-wrap date-area">
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 120px;">
                                    <input type="text" value="{{ isset($search_params['from_period_aggregation']) ? $search_params['from_period_aggregation'] : "" }}" class="input-text datepicker datepicker_period mr10" name="search_params[from_period_aggregation]" id="from_period_aggregation">
                                </div>
                                <div class="mr-10 ml-10">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 120px;">
                                    <input type="text" value="{{ isset($search_params['to_period_aggregation']) ? $search_params['to_period_aggregation'] : "" }}" class="input-text datepicker datepicker_period mr10" name="search_params[to_period_aggregation]" id="to_period_aggregation">
                                </div>
                            </div>
                        </div>
                        <div class="inner_box for-warning">
                            <h3>登録期間</h3>
                            <div class="flex flex-wrap date-area">
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 120px;">
                                    <input type="text" value="{{ isset($search_params['from_period_register']) ? $search_params['from_period_register'] : "" }}" class="input-text datepicker datepicker_period mr10" name="search_params[from_period_register]" id="from_period_register">
                                </div>
                                <div class="mr-10 ml-10">～</div>
                                <div class="form_wrap icon_form type_arrow_bottom" style="width: 120px;">
                                    <input type="text" value="{{ isset($search_params['to_period_register']) ? $search_params['to_period_register'] : "" }}" class="input-text datepicker datepicker_period mr10" name="search_params[to_period_register]" id="to_period_register">
                                </div>
                            </div>
                        </div>--}}

                        <div class="flex" style="margin-top: 20px">
                            <div class="wp-35" style="padding-right: 5px;">
                                <button id="btn_clear" class="btn btn-clear wp-100" name="btn_clear">クリア</button>
                            </div>
                            <div class="wp-65 pos-relative">
                                <button type="submit" id="btn_search" class="btn btn-search wp-100" name="btn_search">この条件で検索</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
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
                            @foreach(config('const.user_sort') as $key=>$val)
                                <option value="{{ $key }}" {{ (isset($search_params['order']) && $search_params['order'] ? $search_params['order'] : config('const.user_sort_code.register_new')) == $key ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="tabs search-result-area">
                    <table>
                        <tbody>
                        @if(count($obj_users) > 0)
                            @foreach($obj_users as $obj_user)
                            <tr>
                                <td class="td-detail"><a class="title_orange" href="{{ route('admin.staff.detail', ['staff'=>$obj_user->id]) }}">詳細</a></td>
                                <td>
                                    <div class="flex profile" data-id="{{ $obj_user->id }}">
                                        <div class="ico ico-user">
                                            <img src="{{ $obj_user->avatar_path }}">
                                        </div>
                                        <div>
                                            <div class="pb-5 ft-bold">{{ $obj_user->user_name }}<span>{{ "（".\App\Service\CommonService::getAge($obj_user->user_birthday)."）" }}</span></div>
                                            <div class="pb-5">{{ $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}&nbsp;{{ $obj_user->user_area_name }}</div>
                                            <div>ID：{{ $obj_user->user_no }}</div>
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

    </div>
@endsection
@section('page_css')
    <link href="{{ asset('assets/vendor/jquery-ui/jquery-ui.css') }}" rel="stylesheet">
    <style>
        .date-area {
            align-items: center;
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
        .profile {
            cursor: pointer;
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
                let staff_id = $(this).data('id');
                let url = "{{ route('admin.staff.detail', ['staff'=>'staff_id']) }}";
                url = url.replace("staff_id", staff_id);
                location.href = url;
            });

            $('#sort_type').change(function() {
                let order_type = $(this).val();
                location.href="{{ route('admin.staff.index') }}?order="+order_type;
            });
        });
    </script>
@endsection
