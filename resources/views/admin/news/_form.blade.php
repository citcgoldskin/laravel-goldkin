<div id="main-contents">
    @php
        $category = old('category', isset($record['category']) ? $record['category'] : []);
        $title = old('title', isset($record['title']) ? $record['title'] : '');
        $content = old('content', isset($record['content']) ? $record['content'] : '');
    @endphp
    <div class="search-result-area">
        <h2>カテゴリー</h2>
        <ul class="faq-list normal-list wrap">
            @foreach(config('const.news_category') as $key => $name)
                <li><input type="radio" name="category" id="category_{{ $key }}" value="{{ $key }}" {{ $key == $category ? 'checked' : '' }}><label for="category_{{ $key }}">{{ $name }}</label></li>
            @endforeach
        </ul>
        @error('category')
            <span  class="error_text">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <h2>表題</h2>
        <textarea name="title" id="title">{{ $title }}</textarea>
        @error('title')
            <span  class="error_text">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

        <h2>本文</h2>
        <textarea name="content" id="content">{{ $content }}</textarea>
        @error('content')
            <span  class="error_text">
                <strong>{{ $message }}</strong>
            </span>
        @enderror

    </div>
</div>
