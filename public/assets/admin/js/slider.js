/*スライダー*/



/* おすすめサービス */
$(function(){
  var elmSwiper = document.querySelectorAll('.recommend');
  var elmPager = document.querySelectorAll('.recommend-pagination');
  if(elmSwiper.length > 0){
    for (let i = 0; i < elmSwiper.length; i++) {
      elmSwiper[i].className += i;
      elmPager[i].className += i;
      var swiper = new Swiper('.recommend' + i, {
		loop: true,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 2000,
							freeMode:true,
        pagination: {
          el: '.recommend-pagination' + i,
          clickable: true,
        },
      });
    }
  }
});


/* お気に入りのセンパイ */
$(function(){
  var elmSwiper = document.querySelectorAll('.favorite');
  var elmPager = document.querySelectorAll('.favorite-pagination');
  if(elmSwiper.length > 0){
    for (let i = 0; i < elmSwiper.length; i++) {
      elmSwiper[i].className += i;
      elmPager[i].className += i;
      var swiper = new Swiper('.favorite' + i, {
		loop: true,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 2000,
        pagination: {
          el: '.favorite-pagination' + i,
          clickable: true,
        },
      });
    }
  }
});

  
  
/* 購入したサービス */
$(function(){
    var service_buy = new Swiper('.service_buy', {
		loop: true,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 10000,
										freeMode:true,
     });
  });
  
  
/* 閲覧したサービス */
$(function(){
    var service_browsing = new Swiper('.service_browsing', {
		loop: true,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 10000,
										freeMode:true,
    });
  });


/* 予約リクエスト */
$(function(){
    var request = new Swiper('.request', {
		loop: true,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 2000,
		pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
     });
  });


/* プロフィール */
$(function(){
    var profile = new Swiper('.profile', {
		loop: true,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 2000,
		pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
     });
  });
  
  

/* レッスンイメージ */
$(function(){
    var lesson_photo = new Swiper('.lesson_photo', {
		loop: false,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 2000,
		pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
     });
  });


/* クーポン */
$(function(){
    var coupon = new Swiper('.coupon', {
		loop: true,
        slidesPerView: "auto",
		spaceBetween: 0,
		initialSlide: 0,
		speed: 2000,
		autoplay: {
			delay: 5000,
		},
		pagination: {
            el: '.swiper-pagination',
            type: 'bullets',
        },
     });
  });





