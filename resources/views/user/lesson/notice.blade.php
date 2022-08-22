@extends('user.layouts.app')

@section('title', 'センパイ')

@section('content')

    <div id="contents">

        <div class="tabs info_wrap">
            <div class="stick">
                <input id="tab-01" type="radio" name="tab_item" checked="checked">
                <label class="tab_item info" for="tab-01">あなた宛</label>
                <input id="tab-02" type="radio" name="tab_item">
                <label class="tab_item info" for="tab-02">ニュース</label>
            </div>

            <!-- ********************************************************* -->

            <div class="tab_content" id="tab-01_content">
                <ul class="info_list">
                    <li>
                        <p class="date">2021/00/00</p>
                        <p>年齢確認のお願いお知らせが入りますお知らせが入ります…</p>
                    </li>
                    <li>
                        <p class="date">2021/00/00</p>
                        <p>年齢確認のお願いお知らせが入りますお知らせが入ります…</p>
                    </li>
                    <li>
                        <p class="date">2021/00/00</p>
                        <p>年齢確認のお願いお知らせが入りますお知らせが入ります…</p>
                    </li>
                </ul>
            </div>

            <!-- ********************************************************* -->

            <div class="tab_content" id="tab-02_content">

                <ul class="info_list">
                    <li>
                        <p class="date">2021/00/00</p>
                        <p>【重要】センパイ利用規約改訂のお知らせ</p>
                    </li>
                    <li>
                        <p class="date">2021/00/00</p>
                        <p>年齢確認のお願いお知らせが入りますお知らせが入ります…</p>
                    </li>
                    <li>
                        <p class="date">2021/00/00</p>
                        <p>年齢確認のお願いお知らせが入りますお知らせが入ります…</p>
                    </li>
                </ul>

            </div>
        </div>

    </div><!-- /contents -->

@endsection


