@extends('user.layouts.app')
@section('title', 'カテゴリーを選択')
@section('sub_title', '（複数可能）')

@section('content')

    @include('user.layouts.header_under')

    <div id="contents">
        {{ Form::open(["route"=>["keijibann.category_post"], "method"=>"post", "name"=>"form1", "id"=>"form1"]) }}
            <!-- 大阪市 ************************************************** -->
            <section>

                <div class="white_box shadow-glay">
                    <div class="ac-margin">
                        <div class="check-list">
                            @foreach($categories as $key => $val)
                                <div class="clex-box_02">
                                    <input type="checkbox" class="category_ids" @php if(isset($search_params['category_id'])) {foreach($search_params['category_id'] as $key_cate => $val_cate) { if($val_cate == $val['class_id']){ echo "checked='checked'";}}} @endphp name="search_params[category_id][]" value="{{$val['class_id']}}" id="c{{$val['class_id']}}">
                                    <label for="c{{$val['class_id']}}"><p>{{$val['class_name']}}（{{$val['recruit_cnt']}}）</p></label>
                                </div>
                            @endforeach
                        </div>

                    </div>

                </div>
                <input type="hidden" name="tot" value="{{$tot}}">
            </section>

            <footer>
                <div id="footer_button_area" class="result">
                    <ul>
                        <li>
                            <div class="btn_base btn_white clear_btn"><button type="reset" onclick="clearCheckbox();">クリア</button></div>
                        </li>
                        <li>
                            <div class="btn_base btn_white settei_btn"><button type="submit">設定する</button></div>
                        </li>
                    </ul>
                </div>
            </footer>

        {{ Form::close() }}
    </div><!-- /contents -->

    <script type="text/javascript">
        function clearCheckbox() {
            $("input.category_ids").removeAttr("checked");
        }
    </script>

@endsection

