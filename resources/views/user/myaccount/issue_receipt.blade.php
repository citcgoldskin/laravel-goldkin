@extends('user.layouts.app')
@section('title', $title)
@section('content')
@include('user.layouts.header_under')

<!-- ************************************************************************
本文
************************************************************************* -->


<div id="contents">

    <section style="background-color: white;font-size:16px;padding: 10px;">
        <!--main_-->
        <div class="div-print">
            <div class="btn_base btn_print shadow">
                <a href="{{ route('user.myaccount.receipt') }}" class="ajax_submit">このページを印刷する</a>
            </div>
        </div>

        <div class="">
            <h2>領&nbsp;収&nbsp;書</h2>
            <div class="content-header1">
                <div class="header1-left">xxxxxxxxx 様</div>
                <div class="header1-right">
                    <div>表示日：2021年04月08日</div>
                    <div>領収書No：210408-2199718</div>
                </div>
            </div>
            <div class="content-header2">
                <div class="header2-left" style="width: 60%;padding-right:5%;">
                    <div class="price">¥34,815</div>
                    <div>但：xxxxxxxxxxxxxx</div>
                </div>
                <div class="header2-right" style="width: 40%;">
                    <div class="title-1">
                        株式会社ifif
                        <img src="{{ asset('assets/user/img/lssy.png') }}" alt="">
                    </div>
                    <div class="gyinfo">
                        <p>〒550-0002</p>
                        <p>大阪府大阪市西区江戸堀1丁目26-24-1005</p>
                    </div>
                </div>
            </div>
            <table class="tables">
                <tr>
                    <th class="w100">決済日</th>
                    <th class="w100">決済No</th>
                    <th class="">内容</th>
                    <th class="w150">金額</th>
                </tr>
                <tr>
                    <td class="tar">2020/04/08</td>
                    <td>2199718</td>
                    <td>xxxx</td>
                    <td class="al-r">¥30,000</td>

                </tr>
                <tr>
                    <td class="tar">2020/04/08</td>
                    <td>2199718</td>
                    <td>xxxx</td>
                    <td class="al-r">¥3,000</td>

                </tr>
                <tr>
                    <td class="tar">2020/04/08</td>
                    <td>2199718</td>
                    <td>xxxx</td>
                    <td class="al-r">¥500</td>

                </tr>
                <tr>
                    <td class="tar">2020/04/08</td>
                    <td>2199718</td>
                    <td>xxxx</td>
                    <td class="al-r">¥300</td>

                </tr>
                <tr>
                    <td class="al-r" colspan="3">小計</td>
                    <td class="al-r">¥33,800</td>
                </tr>
                <tr>
                    <td class="al-r" colspan="3">サービス手数料</td>
                    <td class="al-r">¥1,860</td>
                </tr>
                <tr>
                    <td class="al-r" colspan="3">クーポン利用</td>
                    <td class="al-r">- ¥845</td>
                </tr>
                <tr style="border-top: 2px solid black;">
                    <td class="al-r" colspan="3">合計金額</td>
                    <td class="al-r">¥34,815</td>
                </tr>
            </table>
        </div>
    </section>


</div><!-- /contents -->

<style>
    .div-print {
        display: flex;
        justify-content: flex-end;
        align-items: center;
    }
    .btn_print {
        background: #85cf24;
        color: white;
        width: 200px;
        margin: unset !important;
    }
    .content-header1, .content-header2 {
        display: flex;
        justify-content: space-between;
    }
    /*content-header1*/
    .content-header1 {
        margin-top: 20px;
        margin-bottom: 40px;
    }
    .header1-right {
        text-align: right;
    }
    /*content-header2*/
    .header2-right {
        padding-right: 20px;
        position: relative;
    }
    .header2-right img{
        position: absolute;
        width: 90px;
        height: 90px;
        top: -10px;
        right: 0px;
    }
    .title-1 {
        font-size: 22px;
        padding: 20px 0px;
    }
    .price {
        margin-top: 40px;
        padding-bottom: 10px;
        font-size: 28px;
        border-bottom: 1px solid black;
    }
    .tables {
        font-size: 18px;
        width: 100%;
        border: 1px solid #CCC;
        margin-top: 10px;
    }

    .tables th,
    .tables td {
        border: 1px solid #CCC;
        padding: 5px 10px;
    }
    .tables td {
        font-size:16px;
    }
    .tables th {
        text-align: center;
        font-weight: bold;
        background-color: rgb( 46,77,159,.05);
        font-size: 14px;
    }
    .al-r {
        text-align: right;
    }
    .w100 {
        width: 100px;
    }
    .w150 {
        width: 150px;
    }
</style>


@include ('user.layouts.modal')

<footer>

    @include('user.layouts.fnavi')

</footer>

@endsection
