// JavaScript Document
var WEEK_DAYS = ['日', '月', '火', '水', '木', '金', '土'];
var DAY_TYPES = ['morning', 'noon', 'night'];
function setAbridgedTxt(cutFigure){
    var $setElm = $('p.info_ttl');
   // var cutFigure = '32'; // カットする文字数
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

function showFormatNum(num) {
    return num.toLocaleString();

}

function getYMD(date) {
    return date.getFullYear() + '年' + (date.getMonth() * 1 + 1)  + '月' + date.getDate() + '日';
}

function show_dialog(msg) {
    var html = '<div id="alert_modal_wrap" class="modal-wrap completion_wrap">' +
        '<div id="alert-modal" class="modal-content">' +
        '' +
        '<div class="modal_body completion">' +
        '<div class="modal_inner">' +
        '<h2 class="modal_ttl" id="msg">' +
        msg +
        '</h2>' +
        '</div>' +
        '</div>' +
        '<div class="button-area">' +
        '<div class="btn_base btn_ok">' +
        '<a  class="modal-close button-link" onclick="close_dialog();">OK</a>' +
        '</div>' +
        '</div>' +
        '</div>';
    $('body').append(html);
    $("body").append('<div id="modal-overlay"></div>');
    $(".modal-wrap").fadeIn("fast");
    $("#alert-modal").fadeIn("fast");
    $("#modal-overlay").fadeIn("fast");
}

function close_dialog() {
    $(".modal-wrap").fadeOut();
    $("#alert-modal").fadeOut();
    $("#modal-overlay").fadeOut();
    $('#alert_modal_wrap').remove();
    $("#modal-overlay").remove();
}

function getFeeValue(money, ratio, minValue)
{
    var reVal = money * ratio;
    if(reVal > minValue)
        return Math.floor(reVal);
    return minValue;
}

function withZero(num, targetLength=2) {
    return String(num).padStart(targetLength, '0')
}

$(function() {
    $('.alert .close').click( function() {
        $('.alert').hide();
    });
});
