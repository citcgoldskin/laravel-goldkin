// JavaScript Document
var WEEK_DAYS = ['月', '火', '水', '木', '金', '土', '日'];
function setAbridgedTxt(){
    var $setElm = $('p.info_ttl');
    var cutFigure = '32'; // カットする文字数
    var afterTxt = ' …'; // 文字カット後に表示するテキスト

    $setElm.each(function () {
        var textLength = $(this).text().length;
        var textTrim = $(this).text().substr(0, (cutFigure))

        if (cutFigure < textLength) {
            $(this).html(textTrim + afterTxt).css({
                visibility: 'visible'
            });
        } else if (cutFigure >= textLength) {
            $(this).css({
                visibility: 'visible'
            });
        }
    });
}

function showFormatNum(num)
{
    var numStr = num.toString();

}

// flash-message close btn click event.
$(function () {
    $(document).ready(function () {
        $('.alert .close').click(function () {
            $('.sec-fls-msg').hide();
        });

        $('.alert .close').click(function () {
            $(this).parent().hide();
        });
    });
});
