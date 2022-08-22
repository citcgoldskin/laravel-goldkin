<div class="inner_box">
    <h3>お支払い金額</h3>
    <div class="w">
        <ul class="border-none list_box">
            <li class="li-top-box due_date">
                <div>
                    <p>{{ isset($label) ? $label : '' }}</p>
                </div>
            </li>

        </ul>
        <ul class="scroll border-none list_box">
            @if(isset($amount_history) && count($amount_history) > 0)
                @foreach($amount_history as $value)
                    <li class="icon_form w-box due_date payment-history-li" data-lrs-id="{{ $value->lrs_id }}">
                        <div>
                            <ul class="flex-text" style="align-items: center;">
                                <li>
                                    <p>{{ $value->lesson_request->user ? $value->lesson_request->user->name : '' }}</p>
                                    <p>{{ \Carbon\Carbon::parse($value->lrs_complete_date)->format('n/j H:i') }}</p>
                                </li>
                                <li>
                                    <p class="money01">{{ number_format($value->lrs_amount) }} <span>円</span></p>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endforeach
            @else
                <li class="w-box due_date">
                    <div>
                        データが存在しません。
                    </div>
                </li>
            @endif
        </ul>
    </div>
</div>
