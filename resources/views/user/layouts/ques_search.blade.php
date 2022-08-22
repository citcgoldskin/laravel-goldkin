<ul class="form_area">
    <li>
        <div class="form_wrap icon_form type_search">
            <input name="keyword" type="text" placeholder="キーワードで検索" class="search_white" value="{{ isset($keyword) ? $keyword : ''}}">
            <button type="submit" class="search"></button>
        </div>
    </li>
    <input type="hidden" name="id" value="{{isset($id)? $id : 0}}">
</ul>