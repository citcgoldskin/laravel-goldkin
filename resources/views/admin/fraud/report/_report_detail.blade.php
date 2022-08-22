@php
    $obj_user = is_object($obj_appeal) ? $obj_appeal->user : null;
@endphp
<div class="modal_body">
    <div class="close_btn_area">
        <a  class="modal-close"><img src="{{asset('assets/user/img/x-mark.svg')}}" alt="閉じる"></a>
    </div>
    <div class="pd-20 mt-20">
        <div class="flex">
            <div class="ico ico-user">
                <img src="{{ is_object($obj_user) ? $obj_user->avatar_path : '' }}">
            </div>
            <div>
                <div class="user_info pb-5">{{ is_object($obj_user) ? $obj_user->user_name : '' }}{{ "（".(is_object($obj_user) ? \App\Service\CommonService::getAge($obj_user->user_birthday) : '')."）" }}{{ is_object($obj_user) && $obj_user->user_sex ? config('const.gender_type.'.$obj_user->user_sex) : '' }}</div>
                <div class="pb-5">理由：{{ is_object($obj_appeal) ? $obj_appeal->all_reason : '' }}</div>
                <div class="report_date">{{ is_object($obj_appeal) ? \Carbon\Carbon::parse($obj_appeal->updated_at)->format('Y.n.j') : '' }}</div>
            </div>
        </div>
        <div class="mt-20">
            <div class="ft-bold pb-5">通報の理由</div>
            <div class="report_detail">{!! nl2br(is_object($obj_appeal) ? $obj_appeal->note : '') !!}</div>
        </div>
    </div>
</div>
