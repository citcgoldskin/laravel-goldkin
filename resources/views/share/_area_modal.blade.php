<div class="modal_body">
    <div class="close_btn_area">
        <a  class="modal-close"><img src="{{asset('assets/user/img/x-mark.svg')}}" alt="閉じる"></a>
    </div>
    @php
        $city_count = count($data);
    @endphp
    <input type="hidden" name="area_count" value="{{$city_count}}" id="area_count">
    <div class="pd-20 mt-20 modal-content-area">
        @foreach($data as $k => $v)
            <div class="check_area">
                <div class="check-list">
                    <div class="clex-box_02">
                        <input type="hidden" name="area_{{$k + 1}}" id="area_{{$k + 1}}"
                               @if($v['selected'] || $v['selected'])
                               value="1"
                               @else
                               value="0"
                            @endif
                        >
                        <input type="hidden" name="area_{{$k + 1}}_id" value="{{$v['area_id']}}" id="area_{{$k + 1}}_id">
                        <input type="hidden" name="area_{{$k + 1}}_name" value="{{$v['area_name']}}" id="area_{{$k + 1}}_name">
                        <input type="checkbox" name="area" data-area-id="{{$v['area_id']}}" data-area-name="{{$v['area_name']}}" id="c_{{$k + 1}}"
                               @if($v['selected'] || $v['selected'])
                               checked="checked"
                            @endif
                        >
                        <label for="c_{{$k + 1}}" name="area_label" value="0" id="label_{{$k + 1}}" >
                            <p>{{$v['area_name']}}</p>
                        </label>
                    </div>
                </div>

            </div>
        @endforeach
    </div>
    <div id="footer_button_area" class="result">
        <ul>
            <li>
                <div class="btn_base btn_white clear_btn">
                    <a>クリア</a>
                </div>
            </li>
            <li>
                <div class="btn_base btn_white" id="OKbtn">
                    <button id="btn_area_setting">設定する</button>
                </div>
            </li>
        </ul>
    </div>
</div>
