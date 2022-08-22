$(function () {
$(document).ready(function () {
  hsize = $(window).height();
  $("#startup").css("height", hsize + "px");
});
$(window).resize(function () {
  hsize = $(window).height();
  $("#startup").css("height", hsize + "px");
});
});
$(function () {
  $('.button-01').on('click', function () {
    $(this).addClass('f-weight');
    $('.button-02').removeClass('f-weight');
    $('.button-03').removeClass('f-weight');
    $('.morning').show();
    $('.noon').hide();
    $('.night').hide();
  });
  $('.button-02').on('click', function () {
    $(this).addClass('f-weight');
    $('.button-01').removeClass('f-weight');
    $('.button-03').removeClass('f-weight');
    $('.morning').hide();
    $('.noon').show();
    $('.night').hide();
  });
  $('.button-03').on('click', function () {
    $(this).addClass('f-weight');
    $('.button-01').removeClass('f-weight');
    $('.button-02').removeClass('f-weight');
    $('.morning').hide();
    $('.noon').hide();
    $('.night').show();
  });
});
    $(function() {
$('#no-change').change(function(){
    //クリックイベントで要素をトグルさせる
    $(".hideandseek").toggle();
});
					});

    $(function() {
      var textHeight = $('.cut-text').height(),
        lineHeight = parseFloat($('.cut-text').css('line-height')),
        lineNum = 15,
        textNewHeight = lineHeight * lineNum;
      if (textHeight > textNewHeight) {
        $('.cut-text').css('height', textNewHeight);
        $('.readmore-btn').show();
        $('.readmore-btn').click(function() {
          $(this).hide();
          $('.cut-text').css('height', textHeight);
          return false
        });
      };
    });


$(function() {
  $('input[type=checkbox].check-trigger').change( function() {
    if($('[id=c2]').prop('checked')){
      $('.hide-area').show();
    } else  {
          $('.hide-area').hide();
    }
  });
});

					$(function(){
// URLのパラメータを取得
var urlParam = location.search.substring(1);

// URLにパラメータが存在する場合
if(urlParam) {
  // 「&」が含まれている場合は「&」で分割
  var param = urlParam.split('&');

  // パラメータを格納する用の配列を用意
  var paramArray = [];

  // 用意した配列にパラメータを格納
  for (i = 0; i < param.length; i++) {
    var paramItem = param[i].split('=');
    paramArray[paramItem[0]] = paramItem[1];
  }

  // パラメータidがosakaかどうかを判断する
  if (paramArray.id == 'from-judding') {
    $('.from-judding').show();
			 $('.from-non-approval').hide();
  } else  if (paramArray.id == 'from-non-approval') {
			 $('.from-judding').hide();
			 $('.from-non-approval').show();
		} else {

  }
}
						});
					$(function(){
$('.category_img p').not('.active').css('display', 'none');
$(".category-item").change(function() {
	var extraction_val = $(".category-item").val();
	if(extraction_val == "ランニング・ウォーキング") {
		$('.category_img p.c-icon1').css('display', 'block');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "サイクリング") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'block');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "水泳") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'block');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "筋トレ") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'block');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "ゴルフ") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'block');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'block');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "カラオケ") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'block');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "その他・アウトドア") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'block');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "その他・インドア") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'block');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "パソコン") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'block');
				$('.category_img p.c-icon10').css('display', 'none');
	}else if(extraction_val == "ペット散歩・ふれあい") {
		$('.category_img p.c-icon1').css('display', 'none');
				$('.category_img p.c-icon2').css('display', 'none');
				$('.category_img p.c-icon3').css('display', 'none');
				$('.category_img p.c-icon4').css('display', 'none');
				$('.category_img p.c-icon5').css('display', 'none');
				$('.category_img p.c-icon6').css('display', 'none');
				$('.category_img p.c-icon7').css('display', 'none');
				$('.category_img p.c-icon8').css('display', 'none');
				$('.category_img p.c-icon9').css('display', 'none');
				$('.category_img p.c-icon10').css('display', 'block');
	}
});
			});
$(function () {
	$('.acc-close').hide();
  $('.acd-check').on('click', function () {
			$('.acc-close').fadeToggle();
		 $('.box-hide').slideDown();
    $(this).toggleClass('on');
    $('.display_area').toggleClass('on');
  });
	 $('.acc-close').on('click', function () {
			$(this).fadeToggle();
			$('.acd-check').removeClass('on');
			 $('.display_area').toggleClass('on');
    $(this).prev().slideUp();
			setTimeout(function(){

			$("input.acd-check").removeAttr("checked").prop("checked", false).change();
				    },500);

  });
});
$(function () {
  $('ul.date').hide();
  $('p.term-btn').on('click', function () {
    $(this).parents().toggleClass('on');
    $('ul.date').slideToggle();
  });
});
$(function () {
  $('.calendar-area .date-area .calendar-box table .bg-color-02 a').on('click', function () {
    $('.calendar-area .date-area .calendar-box table .bg-color-02 a').not(this).addClass('no-click');
			$('p.to-talkroom a').removeClass('no-click');
    $(this).next('.c-baloon').fadeIn();
    $('.c-close').on('click', function () {
      $(this).parents('.c-baloon').fadeOut();
      $('.calendar-area .date-area .calendar-box table .bg-color-02 a').not(this).removeClass('no-click');
    })
  })
});

$(function () {
  var $setElm = $('p.info_ttl');
  var cutFigure = '32'; // カットする文字数
  var afterTxt = ' …'; // 文字カット後に表示するテキスト

  $setElm.each(function () {
    var txt = $(this).text().trim();
    var splitedTxt = txt.split('\n');

    var textLength = splitedTxt[0].length;
    var textTrim = splitedTxt[0].substr(0, (cutFigure))

    if (cutFigure < textLength || splitedTxt.length > 1) {
      $(this).html(textTrim + afterTxt).css({
        visibility: 'visible'
      });
    } else if (cutFigure >= textLength) {
      $(this).css({
        visibility: 'visible'
      });
    }
  });
});

$(function () {
  $("textarea.count-text").on('keydown keyup keypress change', function () {
    count = $(this).val().length;
    limit = 0 + count;
    if (limit <= 1000) {
      $("#num").text(showFormatNum(limit));
      if (limit <= 0) {
        $("#num").text('0');
      }
    }
  });
});
$(function () {
  $("textarea.count-text100").on('keydown keyup keypress change', function () {
    count = $(this).val().length;
    limit = 0 + count;
    if (limit <= 100) {
      $("#num100").text(limit);
      if (limit <= 0) {
        $("#num100").text('0');
      }
    }
  });
});
$(function () {
  $("textarea.count-text200").on('keydown keyup keypress change', function () {
    count = $(this).val().length;
    limit = 0 + count;
    if (limit <= 200) {
      $("#num200").text(limit);
      if (limit <= 0) {
        $("#num200").text('0');
      }
    }
  });
});
$(function () {
  $("textarea.count-text200-2").on('keydown keyup keypress change', function () {
    count = $(this).val().length;
    limit = 0 + count;
    if (limit <= 200) {
      $("#num200-2").text(limit);
      if (limit <= 0) {
        $("#num200-2").text('0');
      }
    }
  });
});
$(function () {
	$('.card_ok > p').next().hide();
  $('.card_ok > p').on('click', function () {
    $(this).next().slideToggle();
			$(this).toggleClass('on');
  });
});

$(function () {
  $('input[name="commitment2"]').on('change', function () {
    $('.text-p-04').toggleClass('blockbox');
  });
});

$(function () {
    if($('.tabs #tab-01').prop('checked')){
        $("#tab-01_content").show();
        $("#tab-02_content").hide();
        $("#tab-03_content").hide();
    }
    if($('.tabs #tab-02').prop('checked')){
        $("#tab-01_content").hide();
        $("#tab-02_content").show();
        $("#tab-03_content").hide();
    }
    if($('.tabs #tab-03').prop('checked')){
        $("#tab-01_content").hide();
        $("#tab-02_content").hide();
        $("#tab-03_content").show();
    }

  //$("#tab-01_content").show();
  $('.tabs #tab-01').click(function () {
    //クリックイベントで要素をトグルさせる
    $("#tab-01_content").show();
    $("#tab-02_content").hide();
    $("#tab-03_content").hide();
  });
  $('.tabs #tab-02').click(function () {
    //クリックイベントで要素をトグルさせる
    $("#tab-01_content").hide();
    $("#tab-02_content").show();
    $("#tab-03_content").hide();
  });
  $('.tabs #tab-03').click(function () {
    //クリックイベントで要素をトグルさせる
    $("#tab-01_content").hide();
    $("#tab-02_content").hide();
    $("#tab-03_content").show();
  });
});


/*スムーズスクロール*/
$(function () {
  $('a[href^="#"]').click(function () {
    var speed = 500;
    var href = $(this).attr("href");
    var target = $(href == "#" || href == "" ? 'html' : href);
    var position = target.offset().top - 100;
    $("html, body").animate({
      scrollTop: position
    }, speed, "swing");
    return false;
  });
});


/*モーダル*/
$(function () {
  var nowModalSyncer = null;
  var modalClassSyncer = "modal-syncer";

  var modals = document.getElementsByClassName(modalClassSyncer);

  for (var i = 0, l = modals.length; l > i; i++) {

    modals[i].onclick = function () {

      this.blur();

      var target = this.getAttribute("data-target");

      if (typeof (target) == "undefined" || !target || target == null) {
        return false;
      }

      nowModalSyncer = document.getElementById(target);

      if (nowModalSyncer == null) {
        return false;
      }

      if ($("#modal-overlay")[0]) return false;

      $("body").append('<div id="modal-overlay"></div>');
      $("#modal-overlay").fadeIn("fast");

      centeringModalSyncer();

      $(nowModalSyncer).fadeIn("slow");

      $("#modal-overlay,#modal-close,#modal-close.start-btn").unbind().click(function () {
	$('.start-active').addClass('appear');
        $("#" + target + ",#modal-overlay").fadeOut("fast", function () {

          $('#modal-overlay').remove();

        });

        nowModalSyncer = null;

      });

    }

  }

  //リサイズされたら、センタリングをする関数[centeringModalSyncer()]を実行する
  $(window).resize(centeringModalSyncer);

  //センタリングを実行する関数
  function centeringModalSyncer() {

    //モーダルウィンドウが開いてなければ終了
    if (nowModalSyncer == null) return false;

    //画面(ウィンドウ)の幅、高さを取得
    var w = $(window).width();
    var h = $(window).height();

    //コンテンツ(#modal-content)の幅、高さを取得
    // jQueryのバージョンによっては、引数[{margin:true}]を指定した時、不具合を起こします。
    //		var cw = $( nowModalSyncer ).outerWidth( {margin:true} ) ;
    //		var ch = $( nowModalSyncer ).outerHeight( {margin:true} ) ;
    var cw = $(nowModalSyncer).outerWidth();
    var ch = $(nowModalSyncer).outerHeight();

    //センタリングを実行する
    $(nowModalSyncer).css({
      "left": ((w - cw) / 2) + "px",
      "top": ((h - ch) / 2) + "px"
    });

  }

});


$(function () {
  var $accBtn = $('.acc-btn span'),
    $accContents = $('.hide-contents');

  $accContents.hide(); //contentsを全て隠す
  $accBtn.each(function () {
    var flag = "close"; //flagを初期値を設定
    $(this).click(function () {
      $(this).parent().parent().next().slideToggle(); //すぐ次の要素をスライド
      $(this).toggleClass('on'); //すぐ次の要素をスライド

      if (flag == "close") { //もしflagがcloseだったら
        $(this).text("閉じる");
        flag = "open"; //flagをopenにする
      } else { //もしflagがopenだったら
        $(this).text("詳細を見る");
        flag = "close"; //flagをcloseにする
      }

    });
  });
});
$(function () {
  const $form = $('#form1')
  $('.btn-send').on('click', evt => {
    $form.submit()
    $form[0].reset()
    $('.modal-wrap').fadeIn();
    $('.modal-content').fadeIn();
    $('#modal-overlay').fadeIn();
			 $('#modal-overlay2').fadeIn();
    return false
  })
});
$(function () {
  const $form2 = $('#form1')
  $('.btn-send2').on('click', evt => {
    $form2.submit()
    //$form2[0].reset()
    $('.modal-wrap.coupon-ok,.modal-wrap.ok').fadeIn();
    $('.modal-content.coupon-ok,.modal-content.ok').fadeIn();
    $('#modal-overlay2').fadeIn();
    return false
  })
});
$(function() {
  var $textarea = $('#textarea');
  var lineHeight = parseInt($textarea.css('lineHeight'));
  $textarea.on('input', function(e) {
    var lines = ($(this).val() + '\n').match(/\n/g).length;
    $(this).height(lineHeight * lines);
  });
});
/*ハンバーガーメニュー*/
function toggleNav() {
  var body = document.body;
  var hamburger = document.getElementById('js-hamburger');
  var blackBg = document.getElementById('js-black-bg');
  var closeerea = document.getElementById('cloce_erea');
  var btntodo = document.getElementById('btntodo');

  if (hamburger != null) {
      hamburger.addEventListener('click', function () {
          body.classList.toggle('nav-open');
          btntodo.classList.toggle('on');
      });
  }

  if (blackBg != null) {
      blackBg.addEventListener('click', function () {
          body.classList.remove('nav-open');
      });
  }

  if (closeerea != null ) {
      closeerea.addEventListener('click', function () {
          body.classList.remove('nav-open');
      });
  }

}
toggleNav();
