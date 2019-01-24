/* UTF-8

© bazykin
Все права разрешены
Development of dreams

http://andreybazykin@gmail.com

------------------------------

© kovrigin
Все права разрешены
красивый дизайн должен иметь красивый код®

http://htmlpluscss.ru

*/
(function($){

	var showAlertUp,
		windowWidth,
		windowHeight,
		windowScrollTop,
		resizeTimeoutId,
		body = $('body'),
		main = $('.main'),
		$window = $(window),
		bxSlider,
		bxSliderOptions = {
			mode: 'fade',
			controls: false,
			speed: 1,
			auto: true,
			adaptiveHeight: true,
			autoDelay: 500000
		};

// slider
	if($('.slide__list.bxslider').length > 0)
		bxSlider = $('.slide__list.bxslider').bxSlider(bxSliderOptions);

	$window.on({
		resize: function(){
			clearTimeout(resizeTimeoutId);
			resizeTimeoutId = setTimeout(function(){
				pageResize();
			}, 100);
		},
		scroll: function(){
			windowScrollTop = $window.scrollTop();
		},
		load: function(){

		}
	});

	function pageResize() {
		windowWidth = $window.width();
		windowHeight = $window.height();

		if(bxSlider) {
			bxSliderOptions.startSlide = bxSlider.getCurrentSlide();
			bxSlider.reloadSlider(bxSliderOptions);
		}
		
		initIndexReviews();

		/*main.css('min-height', windowHeight - $('#header').outerHeight() - $('#footer').outerHeight());

		$('.slide-show').each(function(){
			var h = 0;
			$(this).find('.slide-show__item-box').each(function(){
				var item = $(this).outerHeight(true);
				if(item > h)
					h = item;
			});
			if(h > 0)
				$(this).find('.slide-show__box').height(h);
		});*/
	}

	pageResize();

	$window.trigger('scroll');

	$('.menu-mobile-toggle__line').on('click', function(event) {
		event.preventDefault();
		var header = $(this).closest('header');
		if(!header.hasClass('header--menu-show')) {
			header.addClass('header--menu-show');
			body.addClass('sm-hidden body--menu-show');
			if($('.frontend.home').length > 0) {
				$(".header.header--menu-show").touchwipe({
					//wipeLeft: function() { alert("left"); },
					//wipeRight: function() { alert("right"); },
					//wipeUp: function() { alert("up"); },
					wipeDown: function() { 
						header.removeClass('header--menu-show');
						body.removeClass('sm-hidden body--menu-show');
						pageResize();
					},
					min_move_x: 20,
					min_move_y: 20,
					preventDefaultEvents: true
				});				
			}
		}
		else {
			header.removeClass('header--menu-show');
			body.removeClass('sm-hidden body--menu-show');
		}		
		pageResize();
	});

	$(".mask-tel").mask("+7 999 999-99-99");

	$(".callback-order").hover(function() {
		$(this).addClass('tada');
	}, function() {
		$(this).removeClass('tada');
	});

	$(".input").on('blur', function(event) {
		event.preventDefault();
		if($(this).val().length > 0)
			$(this).addClass('input--actived');
		else
			$(this).removeClass('input--actived');			
	});

	$('a.organizations__show-requisites').on('click', function(event) {
		event.preventDefault();
		var list = $(this).closest('.organizations__list');

		if($(this).closest('.organizations__item').hasClass('organizations__item--active'))
			$(this).closest('.organizations__item').removeClass('organizations__item--active');
		else {
			list.find('.organizations__item--active').removeClass('organizations__item--active');
			$(this).closest('.organizations__item').addClass('organizations__item--active');
		}

	});

	$('.org__head').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var list = $(this).closest('.org__box-nav'),
			thisIndex = $(this).index();

		if(!$(this).closest('.org__head').hasClass('org__head--active')) {

			list.find('.org__head--active').removeClass('org__head--active');
			list.siblings('.org__body').removeClass('org__body--active');

			$(this).addClass('org__head--active');
			list.closest('.org__list').find('.org__body:eq('+thisIndex+')').addClass('org__body--active');

		}
	});

	$('.org__offices-item').on('click', function(event) {
		event.preventDefault();
		/* Act on the event */
		var list = $(this).closest('.org__offices-list');

		if($(this).closest('.org__offices-item').hasClass('org__offices-item--active'))
			$(this).closest('.org__offices-item').removeClass('org__offices-item--active');
		else {
			list.find('.org__offices-item--active').removeClass('org__offices-item--active');
			$(this).closest('.org__offices-item').addClass('org__offices-item--active');
		}
	});

	$('.news__item-title, .news__item-img').hover(function() {
		$(this).closest('.news__item').addClass('news__item--hover');
	}, function() {		
		$(this).closest('.news__item').removeClass('news__item--hover');
	});

	initIndexReviews();

	function initIndexReviews() {

		if($('.reviews--home').length > 0) {

			var nGroupElementCount = 3;

			if(windowWidth > 943)
				nGroupElementCount = 3;
			else if(windowWidth <= 943 && windowWidth > 703)
				nGroupElementCount = 2;
			else
				nGroupElementCount = 1;

			var oReviewsItems = $('.reviews__list').find('.reviews__item').clone(),
				oReviewsContainer = $('.reviews__list'),
				nReviewsCount = 0;

			$(".reviews--home").find(".reviews__dot-nav").remove();
			$('.reviews__list').html("");

			if(oReviewsItems.length > 0) {

				var oReviewsGroup = $('<div class="reviews__list-group reviews__list-group--active"></div>');

				$.each(oReviewsItems, function(index, val) {
					
					if(index % nGroupElementCount == 0 && index != 0) {
						//alert(1);
						oReviewsContainer.append(oReviewsGroup);
						oReviewsGroup = $('<div class="reviews__list-group"></div>');
					}
					 
					 oReviewsGroup.append($(this));
				});

				if(((oReviewsItems.length - 1) % nGroupElementCount) != 0)
					oReviewsContainer.append(oReviewsGroup);

			}

			var nReviewsGroups = $('.reviews__list').find('.reviews__list-group').length,
				oDotsNav = $('<div class="reviews__dot-nav"></div>');

			if(nReviewsGroups > 1) {

				for(i = 0; i < nReviewsGroups; i++)
					oDotsNav.append('<a href="javascript:;" class="reviews__dot-nav-item"></a>');

				oDotsNav.children('a:eq(0)').addClass('reviews__dot-nav-item--active');
				$(oDotsNav).insertAfter('.reviews__list');

				$('<a href="javascript:;" class="reviews__arr-prev reviews__arr-prev--disabled"></a><a href="javascript:;" class="reviews__arr-next"></a>').prependTo('.reviews__list');

				$('.reviews__dot-nav-item').on('click', function(event) {
					event.preventDefault();
					$(this).removeClass('reviews__dot-nav-item--active').siblings('.reviews__dot-nav-item').removeClass('reviews__dot-nav-item--active');
					$(this).addClass('reviews__dot-nav-item--active');
					var index = $(this).index(),
						first = $(this).prev('.reviews__dot-nav-item').length > 0 ? false : true,
						last = $(this).next('.reviews__dot-nav-item').length > 0 ? false : true;

					$('.reviews__list').find('.reviews__list-group').removeClass('reviews__list-group--active');
					var currItem = $('.reviews__list').find('.reviews__list-group').eq(index).addClass('reviews__list-group--active');

					if(first)
						$('.reviews__arr-prev').addClass('reviews__arr-prev--disabled');
					else
						$('.reviews__arr-prev').removeClass('reviews__arr-prev--disabled');
					if(last)
						$('.reviews__arr-next').addClass('reviews__arr-next--disabled');
					else
						$('.reviews__arr-next').removeClass('reviews__arr-next--disabled');
				});

				$('.reviews__arr-prev, .reviews__arr-next').on('click', function(event) {
					event.preventDefault();

					var oCurrentDotNav = $('.reviews__dot-nav-item--active'),
						oOtherDotNav = $(this).hasClass('reviews__arr-prev') ? oCurrentDotNav.prev('.reviews__dot-nav-item') : oCurrentDotNav.next('.reviews__dot-nav-item');

					if(oOtherDotNav.length > 0)
						oOtherDotNav.trigger('click');
				});

			}

		}

	}

// select
	$('select').css('width','100%').each(function () {

		var t = $(this);
		var minimum = t.is('[data-notsearch]') ? Infinity : 10;

		t.attr('data-placeholder', t.children('[value="none"]').text());
		t.children('[value="none"]').attr('value','');

		t.select2({
			language: {
				noResults: function () {
					return 'Совпадений не найдено';
				},
			},
			minimumResultsForSearch: minimum,
		});

		t.on('select2:opening',function(e){
			//console.log(e);
			//body.addClass('body--select2-hide');
		});
		t.on('select2:open',function(e){
			//console.log(e)
			/*setTimeout(function(){
				body.removeClass('body--select2-hide');
				$('.select2-results__options').focus();
			},300);*/
		});

	});

// alert_up
	$.fn.alertUp = function(){

		var box = $('.alert_up');
		var windows = box.children();

		box.on('click',function(event){
			var t = $(event.target);
			if(t.is('.alert_up') || t.is('.alert_up__close')){
				box.addClass('alert_up--hide');
				windows.removeClass('alert_up__window--active');
				body.removeClass('hidden');
				$('.frontend').css('margin-left',0);
			}
		});

		showAlertUp = function (selector) {
			var a_up = windows.filter('.alert_up__window--'+selector);
			body.addClass('hidden');
			$('.frontend').css('margin-left',-getScrollBarWidth());
			box.removeClass('alert_up--hide').toggleClass('flexbox', windowHeight > a_up.outerHeight());
			windows.not(a_up).removeClass('alert_up__window--active');
			a_up.addClass('alert_up__window--active').focus();
		}

		return this.each(function(){
			var selector = $(this).attr('data-alert-up');
			$(this).on('click',function(){
				showAlertUp(selector);
			});
		});

	};

	$('.btn-alert_up').alertUp();

})(jQuery);

function getScrollBarWidth(){
	var div = $('<div class="scroolbarwidth">');
	div.append('<p></p>');
	$('body').append(div);
	var w = div.width() - div.children().width();
	div.remove();
	return w;
}

// cssAnimation('animation/transition')
function cssAnimation(a){var b,c,d=document.createElement("cssanimation");switch(a){case'animation':b={"animation":"animationend","OAnimation":"oAnimationEnd","MozAnimation":"animationend","WebkitAnimation":"webkitAnimationEnd"};break;case'transition':b={"transition":"transitionend","OTransition":"oTransitionEnd","MozTransition":"transitionend","WebkitTransition":"webkitTransitionEnd"}}for(c in b)if(d.style[c]!==undefined)return b[c]};