<ul class="faq-list">
    @if($questions->count() > 0)
        @if(isset($frequent_id) && $frequent_id)
            @php
                $obj_frequent = \App\Models\QuestionFrequent::find($frequent_id);
            @endphp
        @endif
        @foreach($questions as $question)
            <li><a class="{{ isset($frequent_id) && $frequent_id && is_object($obj_frequent) && $obj_frequent->question_id == $question->que_id ? 'selected' : '' }}" href="{{ route('admin.freq.normal_question.question_detail', ['frequent_type'=>$frequent_type, 'question'=>$question->que_id, 'frequent_id'=>isset($frequent_id) ? $frequent_id : 0]) }}">
                    <dl>
                        <dt class="three_dots"><span>Q.</span>{{ $question->que_ask }}</dt>
                        <dd class="three_dots"><span>A.</span>{{ $question->que_answer }}</dd>
                    </dl>
                </a></li>
        @endforeach
    @else
        <li>データが存在しません。</li>
    @endif
</ul>
