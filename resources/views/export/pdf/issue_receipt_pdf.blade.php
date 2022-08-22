<!DOCTYPE html>
<html>
<head>
    <style>
        .font-bold {
            font-family: ipag-bold;
        }
        .font-normal {
            font-family: ipag;
        }
        body {
            background: white;
            font-family: ipag;
            color: black;
            padding-top: 1.4cm;
            padding-left: 1.5cm;
            padding-right: 2cm;
            font-size: 12px;
        }
        table{
            border-spacing: 0px;
            padding: 0px;
            border: 1px solid black;
        }
        table td {
            padding: 0 0.1cm !important;
        }
        td {
            text-align: center;
            height: 0.7cm;
            vertical-align: middle;
        }

        table tr td:first-child {
            border-right: none;
        }
        table tr td {
            /*border-bottom: none;*/
            font-size: 14px;
        }
        @page {
            background: white;
            display: block;
            margin: 0;
            padding: 0;
            box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
        }
        @page[size="A4"] {
                 width: 29.7cm;
                 height: 21cm;
                 margin: 0;
             }
        @media print {
            body, page {
                margin: 0;
                box-shadow: 0;
            }
        }
        .entry-body {
            width: 100%;
        }
        .entry-body tr td{
            border: 1px solid black;
            border-bottom: none;
            border-right: none;
            padding-top: 0.12cm !important;
            padding-bottom: 0.12cm !important;
            word-break: break-all;
            word-wrap: break-word;
            white-space: break-spaces;
            vertical-align: top         /*display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;*/
        }
        .entry-body tr td:last-child {
            border-right: 1px solid black;
        }
        .entry-body tr td div {
            padding: 0.1cm;
            padding-top: 0cm;
            padding-bottom: 0cm;
            font-size: 14px;
            overflow: hidden;
            padding-right: 0.2cm !important;
        }
        .td-lb {
            font-family: ipag;
            vertical-align: middle !important;
            text-align: center;
            height: 0.5cm;
            font-size: 14px;
        }
        .wp-100 {
            width: 100%;
        }
        .wp-13 {
            width: 13%;
        }
        .wp-37 {
            width: 37%;
        }
        .wp-8 {
            width: 8%;
        }
        .wp-12 {
            width: 12%;
        }
        .wp-15 {
            width: 15%;
        }
        .wp-20 {
            width: 20%;
        }
        .wp-25 {
            width: 25%;
        }
        .wp-30 {
            width: 30%;
        }
        .wp-35 {
            width: 35%;
        }
        .wp-40 {
            width: 40%;
        }
        .wp-50 {
            width: 50%;
        }
        .wp-60 {
            width: 60%;
        }
        .wp-65 {
            width: 65%;
        }
        .wp-70 {
            width: 70%;
        }
        .wp-80 {
            width: 80%;
        }
        .wp-90 {
            width: 90%;
        }
        .fs-14 {
            font-size: 14px;
        }
        .fs-32 {
            font-size: 32px;
        }
        .fs-16 {
            font-size: 16px;
        }
        .al-c {
            text-align: center;
        }
        .al-r {
            text-align: right;
        }
        .al-l {
            text-align: left;
        }
        .top-tb{
            border: none;
        }
        .td-br {
            border: 1px solid black;
        }
        .accept-tb {
            border: none;
            margin-top: 0.3cm;
        }
        .accept-tb td {
            padding-top: 0.2cm !important;
            padding-bottom: 0.2cm !important;
        }
        .job-history {
            height: 5cm !important;
        }
        .vertical-al-t {
            vertical-align: top;
        }
        .vertical-al-m {
            vertical-align: middle !important;
        }
        .achievements {
            height: 3.5cm !important;
        }
        .no-br-right {
            border-right: none !important;
        }
        .no-border{
            border: none !important;
        }
        .br-top {
            border-top: 1px solid black !important;
        }
        .br-left {
            border-left: 1px solid black !important;
        }
        .page-break-after{
            page-break-after: always;
        }
        .td-dv {
            overflow: hidden;
            height: 0.5cm;
        }
        .lh-2 {
            height: 1cm !important;
            max-height: 1cm !important;
            overflow:hidden
        }
        .pd-style-1 {
            padding-top: 0.2cm !important;
        }
        .pd-style-2 {
            padding-top: 0.15cm !important;
        }
        .pd-l-none {
            padding-left: none !important;
        }
        .td-dv-pd {
            padding-right: 0.2cm !important;
        }
        .tb-head {
            background-color: rgb( 239, 239, 239);
            font-size: 14px !important;
        }
        .tb-head td {
            vertical-align: middle !important;
        }
        .td-num {
            text-align: right;
        }
        .underline1 {
            /*text-decoration: underline;*/
            border-bottom: 1px solid black;
        }
        .tr-img {
            padding-top: 0.3cm !important;
        }
        .order-label {
            /*padding-left: 2cm;*/
        }
        .order-label div {
            margin: 0.1cm;
            margin-right: 0cm;
        }
        .order-label2 {
            padding-left: 1.2cm !important;
        }
        .order-label2 div {
            margin: 0.1cm;
            margin-right: 0cm;
            margin-top: 0cm;
            margin-bottom: 0.2cm;
            padding: 0.1cm;
            padding-right: 0cm;
        }
        .mark-img img {
            position: absolute;
            width: 90px;
            height: 90px;
            top: -10px;
            right: 0px;
        }

    </style>
</head>
<body class="fs-14">
<page size="A4">
    <table class="wp-100 top-tb">
        <thead>
            <tr class="">
                <td class="wp-60 lh-2"></td>
                <td class="wp-40 lh-2"></td>
            </tr>
            <tr>
                <td class="al-c td-lb fs-32 font-bold" colspan="2" style="padding-bottom:1cm !important;"><b>領 収 書</b></td>
            </tr>
        </thead>
        <tbody>
            <tr class="wp-100 tr-img">
                <td class="td-lb al-l fs-16 wp-60">
                    <div class="header1-left">xxxxxxxxx 様</div>
                </td>
                <td class="td-lb al-r fs-14 wp-40" style="margin-top:0.5cm !important;">
                    <div class="al-r order-label">
                        <div class="">表示日： {{ '2021年04月08日' }}</div>
                        <div class="">領収書No： {{ '210408-2199718' }}</div>
                    </div>
                </td>
            </tr>
            <tr class="wp-100 tr-img">
                <td class="td-lb al-l fs-16 wp-60">
                    <div class="al-c order-label3">
                        <div class="underline1" style="padding-bottom: 0.1cm;font-size: 22px;margin-top: 1cm !important;">¥34,815</div>
                    </div>
                    <div style="padding-top: 0.2cm;">
                        但：xxxxxxxxxxxxxx
                    </div>
                </td>
                <td class="td-lb al-r fs-14 wp-40" style="vertical-align: top !important;">
                    <div class="al-l" style="margin-top: 0cm !important;padding-top: 0cm !important;position:relative;padding-left: 1cm !important;">
                        <div class="mark-img" style="padding: 0.5cm 0cm !important;margin-top:1cm !important;">
                            株式会社ifif
                            <img src="{{ public_path('assets/user/img/lssy.png') }}" alt="" class="wp-90">
                        </div>
                        <div class="">〒550-0002</div>
                        <div class="">大阪府大阪市西区江戸堀1丁目26-24-1005</div>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <table class="entry-body" style="margin-top: 0.5cm !important;border: none !important;border-bottom: 1px solid black !important;">
        <thead>
            <tr class="tb-head">
                <td class="lh-2" style="width: 15%;">決済日</td>
                <td class="lh-2" style="width: 15%;">決済No</td>
                <td class="lh-2" style="width: 55%;">内容</td>
                <td class="lh-2" style="width: 15%;">金額</td>
            </tr>
            <tr>
                <td class="tar">2020/04/08</td>
                <td>2199718</td>
                <td class="al-l">xxxx</td>
                <td class="al-r">¥30,000</td>
            </tr>
            <tr>
                <td class="tar">2020/04/08</td>
                <td>2199718</td>
                <td class="al-l">xxxx</td>
                <td class="al-r">¥3,000</td>
            </tr>
            <tr>
                <td class="tar">2020/04/08</td>
                <td>2199718</td>
                <td class="al-l">xxxx</td>
                <td class="al-r">¥500</td>
            </tr>
            <tr>
                <td class="tar">2020/04/08</td>
                <td>2199718</td>
                <td class="al-l">xxxx</td>
                <td class="al-r">¥300</td>
            </tr>
            <tr>
                <td class="al-r" colspan="3">小計</td>
                <td class="al-r font-bold">¥33,800</td>
            </tr>
            <tr>
                <td class="al-r" colspan="3">サービス手数料</td>
                <td class="al-r">¥1,860</td>
            </tr>
            <tr>
                <td class="al-r" colspan="3">クーポン利用</td>
                <td class="al-r">- ¥845</td>
            </tr>
            <tr style="border-top: 2px solid black !important;">
                <td class="al-r font-bold" colspan="3">合計金額</td>
                <td class="al-r font-bold">¥34,815</td>
            </tr>
        </thead>
    </table>
</page>
</body>
</html>
