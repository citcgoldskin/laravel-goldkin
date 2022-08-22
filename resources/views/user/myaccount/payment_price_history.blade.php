<ul class="list_box pdf_list">
    @if(isset($amount_history) && count($amount_history) > 0)
        @foreach($amount_history as $key=>$value)
            <li class="icon_form payment-history-li" data-date="{{$key}}" data-price="{{ $value }}">
                <div>
                    <p>{{ $key }}</p>
                    <p class="price_mark">{{ number_format($value) }}</p>
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
