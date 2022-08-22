function onRemoveError(){
    $("span.error_text").remove();
}
function addError(ele, msg) {
    //ele.after($("<span></span>").addClass('error_text').html(msg))
    var msg_html = '<span class="error_text"><strong>'+ msg + '</strong></span>';
    $(ele).parents('.for-warning').children('.warning').html(msg_html);
}

function addDetailError(ele, msg) {
    //ele.after($("<span></span>").addClass('error_text').html(msg))
    var msg_html = '<span class="error_text"><strong>'+ msg + '</strong></span>';
    $(ele).parents('.for-detail-warning').children('.warning').html(msg_html);
}

function isNormalInteger(str) {
    var n = Math.floor(Number(str));
    return n !== Infinity && String(n) === str && n >= 0;
}
function checkRequire(ele, field) {
    if($.trim(ele.val()) == "") {
        addError(ele, field + "を入力してください。")
        return false;
    }
    return true;
}

function checkUploadRequire(ele, field) {
    if($.trim(ele.val()) == "") {
        addError(ele, field + "をアップロードしてください。")
        return false;
    }
    return true;
}

function checkSelect(ele, field) {
    var v = $.trim(ele.val());
    if (!v) {
        addError(ele, field + "を選択してください。")
        return false;
    }
    return true;
}

function checkMultiSelect(ele, field) {
    var v = $.trim(ele.val());
    if (v == null) {
        addError(ele, field + "を選択してください。")
        return false;
    }
    return true;
}

function checkInteger(ele,field) {
    var v = $.trim(ele.val());
    if(v == "") {
        addError(ele, field + "を入力してください。")
        return false;
    }　else {
        if (!isNormalInteger(v) || parseInt(v) == 0) {
            addError(ele, "数値を正確に入力してください。");
            return false;
        }
    }
    return true;

}

function checkMin(ele,field, min) {
    var v = ele.val();
    min = parseInt(min);
    if($.trim(v) == "") {
        addError(ele, field + "を入力してください。")
        return false;
    }　else {
        if (!isNormalInteger(v) || parseInt(v) < min) {
            addError(ele, "数値を正確に入力してください。");
            return false;
        }
    }
    return true;
}

function checkMax(ele,field, max) {
    var v = ele.val();
    max = parseInt(max);
    if($.trim(v) == "") {
        addError(ele, field + "を入力してください。")
        return false;
    }　else {
        if (!isNormalInteger(v) || parseInt(v) > max) {
            addError(ele, "数値を正確に入力してください。");
            return false;
        }
    }
    return true;
}

function remove_warning(){
    $('.for-warning input').keydown(function(){
        $(this).parents('.for-warning').children('.warning').html('');
    });
    $('.for-warning input').change(function(){
        $(this).parents('.for-warning').children('.warning').html('');
    });
    $('.for-warning select').change(function(){
        $(this).parents('.for-warning').children('.warning').html('');
    });
    $('.for-warning textarea').keydown(function(){
        $(this).parents('.for-warning').children('.warning').html('');
    });
}
