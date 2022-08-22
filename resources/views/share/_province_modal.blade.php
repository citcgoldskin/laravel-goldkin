<div class="modal_body">
    <div class="close_btn_area">
        <a  class="modal-close"><img src="{{asset('assets/user/img/x-mark.svg')}}" alt="閉じる"></a>
    </div>
    <div class="pd-20 mt-20 modal-content-area">
        @foreach($region_prefectures as $region_id => $region_data)
            <div class="board_box set-list_wrap">
                <input id="pref-{{$region_id}}" name="acd" class="acd-check" type="checkbox">
                <label class="acd-label" for="pref-{{$region_id}}">{{ $region_data['region'] }}</label>
                <div class="acd-content set-list_content">
                    <ul>
                        @foreach($region_data['child'] as $pref_id => $pref_name)
                            <li>
                                <a class="province_name" data-id="{{ $pref_id }}" data-name="{{ $pref_name }}">{{ $pref_name }}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</div>
