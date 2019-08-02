jQuery(document).ready(function($) {

	window.onscroll = function() { scrollFunction(); };

	function scrollFunction() {

		if (document.body.scrollTop > 140 || document.documentElement.scrollTop > 140) {

			if(!$('.header__nav').hasClass('header__nav--fixed')){

				$('.header__mobile-container').parent().height($('.header__mobile-container').height());

				setTimeout(function(){

					$('.header__nav').addClass('header__nav--fixed');

					$('.header__mobile-container').addClass('header__mobile-container--fixed');

				},100);

			}

			$('.callback-order').addClass('callback-order--fixed');

		} else {

			$('.header__mobile-container').removeClass('header__mobile-container--fixed');

			$('.header__nav').removeClass('header__nav--fixed');

			$('.header__mobile-container').parent().height('auto');

			$('.callback-order').removeClass('callback-order--fixed');

		}

	}

	scrollFunction();

	if(mobileAndTabletCheck())
		$('.footer__full-version').css('display', 'block');

	var btnScrollTop = document.getElementById('btn-scroll-top');

	if (btnScrollTop) {
		window.addEventListener('scroll', function () {
			window.requestAnimationFrame(function () {
				btnScrollTop.classList.toggle('show', window.innerHeight < window.pageYOffset);
			});
		});

		btnScrollTop.addEventListener('click', function(){
			document.body.scrollIntoView({ behavior: 'smooth' });
		});
	}

	function updateSelect2(selector) {
		var t = $(selector);
		var minimum = t.is('[data-notsearch]') ? Infinity : 10;

		t.attr('data-placeholder',t.children('[value="none"]').text());
		t.children('[value="none"]').attr('value','');

		t.select2({
			language: {
				noResults: function () {
					return 'Совпадений не найдено';
				},
			},
			minimumResultsForSearch: minimum,
		});
	}

	if(/*IS_ADMIN && */!getCookie("BITRIX_SM_GEOLOCATION_USE")) {
		// Геолокация доступна
		if (navigator.geolocation) {

			navigator.geolocation.getCurrentPosition(
			    // Функция обратного вызова при успешном извлечении локации
			    function(position) {
			    	//console.log(position);
			    	$.get('https://geocode-maps.yandex.ru/1.x/?format=json&geocode='+position.coords.longitude+','+position.coords.latitude, function(data) {
			    		//Если по запросу есть найденные объекты геолокации
			    		if(data.response.GeoObjectCollection.featureMember) {
			    			bFindGeoProvince = bFindGeoLocality = false;
			    			for(i in data.response.GeoObjectCollection.featureMember) {
			    				if(data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind == "province" && data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.AddressDetails.Country.AdministrativeArea) {
			    					//console.log(data.response.GeoObjectCollection.featureMember[i].GeoObject.name);
			    					bFindGeoProvince = true;
			    					setCookie("BITRIX_SM_GEOLOCATION_REGION_NAME", data.response.GeoObjectCollection.featureMember[i].GeoObject.name, {expires: 86400*365, path: "/", domain: DOCUMENT_DOMAIN});
			    				}
			    				if(data.response.GeoObjectCollection.featureMember[i].GeoObject.metaDataProperty.GeocoderMetaData.kind == "locality") {
			    					//console.log(data.response.GeoObjectCollection.featureMember[i].GeoObject.name);
			    					bFindGeoLocality = true;
			    					setCookie("BITRIX_SM_GEOLOCATION_CITY_NAME", data.response.GeoObjectCollection.featureMember[i].GeoObject.name, {expires: 86400*365, path: "/", domain: DOCUMENT_DOMAIN});
			    				}
			    			}
			    			if(bFindGeoProvince && bFindGeoLocality) {
			    				setCookie("BITRIX_SM_GEOLOCATION_USE", 1, {expires: 86400*365, path: "/", domain: DOCUMENT_DOMAIN});
			    				window.location.reload()
			    			}
			    		}

			    	});
			        /*
			        В объекте position изложена подробная информация
			        о позиции устройства:

			        position = {
			            coords: {
			                latitude - Широта.
			                longitude - Долгота.
			                altitude - Высота в метрах над уровнем моря.
			                accuracy - Погрешность в метрах.
			                altitudeAccuracy - Погрешность высоты над уровнем моря в метрах.
			                heading - Направление устройства по отношению к северу.
			                speed - Скорость в метрах в секунду.
			            }
			            timestamp - Время извлечения информации.
			        }
			        */

			    },

			    // Функция обратного вызова при неуспешном извлечении локации
			    function(error){

			        /*
			        При неудаче, будет доступен объект error:

			        error = {
			            code - Тип ошибки
			                    1 - PERMISSION_DENIED
			                    2 - POSITION_UNAVAILABLE
			                    3 - TIMEOUT

			            message - Детальная информация.
			        }
			        */

			    }
			);


		}
	}

	/*if(!getCookie("BITRIX_SM_TEST_MODE_USE"))
		showAlertUp('test-mode');*/

	$("#form-test-mode").on('submit', function(event) {
		event.preventDefault();

		var thisForm = $(this),
			bErrors = false;

		if(parseInt(thisForm.find('input[name="CB_TEST_MODE"]:checked').val()) != 1) {
			thisForm.find('input[name="CB_TEST_MODE"]').closest('label').addClass('error-blink').one('click', function(event) {
				$(this).removeClass('error-blink');
			});
			setTimeout(function(){
				thisForm.find('input[name="CB_TEST_MODE"]').closest('label').removeClass('error-blink');
			}, 2000);
		}
		else {
			setCookie("BITRIX_SM_TEST_MODE_USE", 1, {expires: 86400*365, path: "/", domain: DOCUMENT_DOMAIN});
			location.reload();
		}

		return false;
	});

	$('.reviews__list').on('click', '.reviews__item-read', function(event) {
		event.preventDefault();
		var thisBtn = $(this),
			oList = thisBtn.closest('.reviews__list'),
			sShortText = thisBtn.attr('data-link-text-short'),
			sFullText = thisBtn.attr('data-link-text-full');

		if(!thisBtn.hasClass('reviews__item-read--active')) {
			oList.find('.reviews__item-read--active').removeClass('reviews__item-read--active').html(sFullText);
			oList.find('.reviews__item-text--full:visible').hide();
			oList.find('.reviews__item-text--short:hidden').show();
			oList.find('.reviews__item-box--full').removeClass('reviews__item-box--full');
		}

		if(thisBtn.hasClass('reviews__item-read--active'))
			thisBtn.html(sFullText);
		else
			thisBtn.html(sShortText);

		thisBtn.siblings('.reviews__item-text--full').toggle();
		thisBtn.siblings('.reviews__item-text--short').toggle();
		thisBtn.closest('.reviews__item-box').toggleClass('reviews__item-box--full');
		thisBtn.toggleClass('reviews__item-read--active');
	});

	$('.jobs-list').on('click', '.jobs-list__read-full', function(event) {
		event.preventDefault();
		var thisBtn = $(this),
			oList = thisBtn.closest('.jobs-list'),
			oBox = thisBtn.closest('.jobs-list__box'),
			sShortText = thisBtn.attr('data-link-text-short'),
			sFullText = thisBtn.attr('data-link-text-full');

		if(!thisBtn.hasClass('jobs-list__read-full--open')) {
			oList.find('.job-list__hider--open').removeClass('job-list__hider--open');
			oList.find('.jobs-list__box--open').removeClass('jobs-list__box--open');
			oList.find('.jobs-list__read-full--open').removeClass('jobs-list__read-full--open').html(sFullText);
		}

		if(thisBtn.hasClass('jobs-list__read-full--open'))
			thisBtn.html(sFullText);
		else
			thisBtn.html(sShortText);

		oBox.toggleClass('jobs-list__box--open');
		thisBtn.siblings('.job-list__hider').toggleClass('job-list__hider--open');
		thisBtn.toggleClass('jobs-list__read-full--open');
	});

	//Скрытие уведомления
	$('.alert_up__geo-window-close').on('click',function() {

		event.preventDefault();

		$.ajax({
			url: SITE_TEMPLATE_PATH + '/ajax/off_geocity_window.php',
			type: 'POST',
			data: {
				OFF_GEOCITY_WINDOW: 1,
				sessid: BX.bitrix_sessid()
			},
		})
		.done(function(data) {
			var data = JSON.parse(data);
			if(data.SUCCESS) {
				console.log(data.MESSAGE);
			}
		})
		.fail(function() {
		})
		.always(function() {
		});

	});

	//Подтверждение геолокации
	$('#accept-geocity').on('click',function() {

		event.preventDefault();

		var nCityID = parseInt($(this).data("city-id")),
			bx_sessid = $(this).find('input[name="sessid"]').val();

		$.ajax({
			url: SITE_TEMPLATE_PATH + '/ajax/set_geolocation.php',
			type: 'POST',
			data: {
				CITY_ID: nCityID,
				sessid: bx_sessid
			},
		})
		.done(function(data) {
			var data = JSON.parse(data);
			if(data.SUCCESS) {
				//console.log(data.MESSAGE);
				if(!data.WINDOW_CLOSE)
					location.reload();
			}
			else {
				alert(data.MESSAGE);
			}
		})
		.fail(function() {
		})
		.always(function() {
		});

	});

	//Установка геолокации через окно
	$('form#form-set-geolocation').on('submit', function(event) {
		event.preventDefault();

		var nCityID = parseInt($(this).find('select[name="CITY_ID"]').val()),
			bx_sessid = $(this).find('input[name="sessid"]').val();

		if(isNaN(nCityID)) {
			alert("Город не выбран");
			return false;
		}

		$.ajax({
			url: SITE_TEMPLATE_PATH + '/ajax/set_geolocation.php',
			type: 'POST',
			data: {
				CITY_ID: nCityID,
				sessid: bx_sessid
			},
		})
		.done(function(data) {
			var data = JSON.parse(data);
			if(data.SUCCESS) {
				console.log(data.MESSAGE);
				location.reload();
			}
			else {
				alert(data.MESSAGE);
			}
		})
		.fail(function() {
		})
		.always(function() {
		});

		return false;
	});

	//Выбор геолокации в сплывающем окне и в адресах офисов
	$(document).on('change', 'form#form-set-geolocation select[name="REGION_ID"], form#offices-address select[name="REGION_ID"]', function(event) {
		event.preventDefault();

		var thisForm = $(this).closest('form'),
			nRegionID = parseInt($(this).val());

		thisForm.find('select[name="CITY_ID"]').html('<option value="none" selected>Выбрать город</option>').attr("disabled", "disabled");

		for(k in REGIONS_AND_CITIES[nRegionID].CITIES) {
			thisForm.find('select[name="CITY_ID"]').append('<option value="'+REGIONS_AND_CITIES[nRegionID].CITIES[k].ID+'">'+REGIONS_AND_CITIES[nRegionID].CITIES[k].NAME+'</option>');
		}

		thisForm.find('select[name="CITY_ID"]').removeAttr('disabled');
		updateSelect2('select[name="CITY_ID"]');

		/*Сортировка в алфавитном порядке*/
		var mylist = thisForm.find('select[name="CITY_ID"]');
		var listitems = mylist.children('option').get();
		listitems.sort(function(a, b) {
		   var compA = $(a).text().toUpperCase();
		   var compB = $(b).text().toUpperCase();
		   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
		})
		$.each(listitems, function(idx, itm) {
		   console.log(itm);
		   mylist.append(itm);
		});
		/*Сортировка в алфавитном порядке*/
	});

	//Выбор города в адресах офисов сопровождается получением офисов
	$('form#offices-address select[name="CITY_ID"]').on('change', function(event) {
		event.preventDefault();

		var thisForm = $(this).closest('form'),
			nCityID = parseInt($(this).val());


		if(isNaN(nCityID))
			return false;

		$.get(SITE_TEMPLATE_PATH + '/ajax/get_offices_address.php?AJAX_GET_OFFICES_ADDRESS=Y&CITY_ID='+nCityID+'&sessid=' + BX.bitrix_sessid(), function(data) {

			$("ul.offices__list").html(data);

			ymaps.ready(function(){

				myMap.geoObjects.removeAll();

				var optionsCount = $("ul.offices__list li").length;

				$("ul.offices__list li").each(function(index, el) {

					if($(this).attr("data-ll").length > 0) {

						var lat = parseFloat($(this).attr("data-ll").split(",")[0]),
							lon = parseFloat($(this).attr("data-ll").split(",")[1]);

						if(index == 0) {
							myMap.setCenter([lat, lon], optionsCount == 1 ? 16 : 10);
						}

						// Создаем метку и задаем изображение для ее иконки
		            	var myPlacemark = new ymaps.Placemark([lat, lon], {}, {
		                	/*preset: 'islands#blueCircleDotIconWithCaption',
							iconCaptionMaxWidth: '50'*/
							iconLayout: 'default#image',
							iconImageHref: SITE_TEMPLATE_PATH + '/img/placemark.png', // картинка иконки
		                    iconImageSize: [29, 31], // размеры картинки
		                    iconImageOffset: [-14, -15] // смещение картинки
						});
			        	//Добавление метки на карту
			        	myMap.geoObjects.add(myPlacemark);

					}

				});

            });

			/*clearOverlays();

			var optionsCount = $("ul.offices__list li").length;

			$("ul.offices__list li").each(function(index, el) {

				if($(this).attr("data-ll").length > 0) {

					var lat = parseFloat($(this).attr("data-ll").split(",")[0]),
						lon = parseFloat($(this).attr("data-ll").split(",")[1]);

					if(index == 0) {
						gMap.setCenter({lat: lat, lng: lon});
						gMap.setZoom(optionsCount == 1 ? 16 : 10);
					}

					var marker = new google.maps.Marker({
						position: {lat: lat, lng: lon},
						map: gMap,
						title: '1',
						icon: {
							url: SITE_TEMPLATE_PATH + "/img/placemark.png",
							scaledSize: new google.maps.Size(29, 31)
						}
					});

					gMarkersArray.push(marker);

				}

			});*/

		});

	});

	$(document).on('change', 'form#form-calculator select[name="REGION_ID"], form#form-calculator-mobile select[name="REGION_ID"]', function(event) {
		event.preventDefault();

		var nRegionID = parseInt($(this).val());

		$('form#form-calculator select[name="REGION_ID"], form#form-calculator-mobile select[name="REGION_ID"]').val(nRegionID).trigger('change.select2');
		$('form#form-calculator .calculator__select-office-btn').html('Выбрать офис');

		$('form#form-calculator select[name="CITY_ID"]').html('<option value="none" selected="selected">Выбрать город</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator select[name="CITY_ID"]');
		$('form#form-calculator select[name="OFFICE_ID"]').html('<option value="none" selected="selected">Выбрать отделение</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator select[name="OFFICE_ID"]');
		$('form#form-calculator select[name="LOAN_VIEW"]').html('<option value="none" selected="selected">Статус клиента</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator select[name="LOAN_VIEW"]');

		$('form#form-calculator-mobile select[name="CITY_ID"]').html('<option value="none" selected="selected">Выбрать город</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator-mobile select[name="CITY_ID"]');
		$('form#form-calculator-mobile select[name="OFFICE_ID"]').html('<option value="none" selected="selected">Выбрать офис</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator-mobile select[name="OFFICE_ID"]');
		$('form#form-calculator-mobile select[name="LOAN_VIEW"]').html('<option value="none" selected="selected">Статус клиента</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator-mobile select[name="LOAN_VIEW"]');


		for(k in REGIONS_AND_CITIES[nRegionID].CITIES) {
			$('form#form-calculator select[name="CITY_ID"]').append('<option value="'+REGIONS_AND_CITIES[nRegionID].CITIES[k].ID+'">'+REGIONS_AND_CITIES[nRegionID].CITIES[k].NAME+'</option>');
			$('form#form-calculator-mobile select[name="CITY_ID"]').append('<option value="'+REGIONS_AND_CITIES[nRegionID].CITIES[k].ID+'">'+REGIONS_AND_CITIES[nRegionID].CITIES[k].NAME+'</option>');
		}

		$('form#form-calculator select[name="CITY_ID"]').removeAttr('disabled');
		$('form#form-calculator-mobile select[name="CITY_ID"]').removeAttr('disabled');


		//Сортировка в алфавитном порядке
		var thisFormID = $(this).closest('form');
		var mylist = thisFormID.find('select[name="CITY_ID"]');
		var listitems = mylist.children('option').get();
		listitems.sort(function(a, b) {
		   var compA = $(a).text().toUpperCase();
		   var compB = $(b).text().toUpperCase();
		   return (compA < compB) ? -1 : (compA > compB) ? 1 : 0;
		})
		$.each(listitems, function(idx, itm) { mylist.append(itm); });
		//Сортировка в алфавитном порядке
	});

	$(document).on('change', 'form#form-calculator select[name="CITY_ID"], form#form-calculator-mobile select[name="CITY_ID"]', function(event) {
		event.preventDefault();

		var nCityID = parseInt($(this).val());

		if(isNaN(nCityID))
			return false;

		$('form#form-calculator select[name="CITY_ID"], form#form-calculator-mobile select[name="CITY_ID"]').val(nCityID).trigger('change.select2');
		$('form#form-calculator .calculator__select-office-btn').html('Выбрать офис');


		$('form#form-calculator select[name="OFFICE_ID"]').html('<option value="none" selected="selected">Выбрать отделение</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator select[name="OFFICE_ID"]');
		$('form#form-calculator select[name="LOAN_VIEW"]').html('<option value="none" selected="selected">Статус клиента</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator select[name="LOAN_VIEW"]');

		$('form#form-calculator-mobile select[name="OFFICE_ID"]').html('<option value="none" selected="selected">Выбрать офис</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator-mobile select[name="OFFICE_ID"]');
		$('form#form-calculator-mobile select[name="LOAN_VIEW"]').html('<option value="none" selected="selected">Статус клиента</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator-mobile select[name="LOAN_VIEW"]');

		$.get(SITE_TEMPLATE_PATH + '/ajax/calc_get_offices.php?AJAX_CALC_OFFICES=Y&CITY_ID='+nCityID+'&sessid=' + BX.bitrix_sessid(), function(data) {
			$('form#form-calculator select[name="OFFICE_ID"]').append(data);
			updateSelect2('form#form-calculator select[name="OFFICE_ID"]');
			$('form#form-calculator select[name="OFFICE_ID"]').removeAttr('disabled');

			$('form#form-calculator-mobile select[name="OFFICE_ID"]').append(data);
			updateSelect2('form#form-calculator-mobile select[name="OFFICE_ID"]');
			$('form#form-calculator-mobile select[name="OFFICE_ID"]').removeAttr('disabled').trigger('change');
		});

	});

	$(document).on('change', 'form#form-calculator select[name="OFFICE_ID"], form#form-calculator-mobile select[name="OFFICE_ID"]', function(event) {
		event.preventDefault();

		var nOfficeID = parseInt($(this).val());

		if(isNaN(nOfficeID))
			return false;

		$('form#form-calculator select[name="OFFICE_ID"], form#form-calculator-mobile select[name="OFFICE_ID"]').val(nOfficeID).trigger('change.select2');
		$('form#form-calculator .calculator__select-office-btn').html('Выбрать офис');

		$('form#form-calculator select[name="LOAN_VIEW"]').html('<option value="none" selected="selected">Статус клиента</option>').attr("disabled", "disabled");
		$('form#form-calculator-mobile select[name="LOAN_VIEW"]').html('<option value="none" selected="selected">Статус клиента</option>').attr("disabled", "disabled");

		$.get(SITE_TEMPLATE_PATH + '/ajax/calc_get_loans.php?AJAX_CALC_LOANS=Y&OFFICE_ID='+nOfficeID+'&sessid=' + BX.bitrix_sessid(), function(data) {
			$('form#form-calculator select[name="LOAN_VIEW"]').append(data);
			updateSelect2('form#form-calculator select[name="LOAN_VIEW"]');
			$('form#form-calculator select[name="LOAN_VIEW"]').removeAttr('disabled');

			$('form#form-calculator-mobile select[name="LOAN_VIEW"]').append(data);
			updateSelect2('form#form-calculator-mobile select[name="LOAN_VIEW"]');
			$('form#form-calculator-mobile select[name="LOAN_VIEW"]').removeAttr('disabled');
		});

	});

	$(document).on('change', 'form#form-calculator select[name="LOAN_VIEW"], form#form-calculator-mobile select[name="LOAN_VIEW"]', function(event) {
		event.preventDefault();

		var nLoanViewID = parseInt($(this).val());

		$('form#form-calculator select[name="LOAN_VIEW"], form#form-calculator-mobile select[name="LOAN_VIEW"]').val(nLoanViewID).trigger('change.select2');

		var calculatorBtnValues = $('.calculator__data-values'),
			thisOption = $(this).find("option:selected"),
			nRate = parseFloat(thisOption.attr("data-rate-stock")) > 0 ? parseFloat(thisOption.attr("data-rate-stock")) : (parseFloat(thisOption.attr("data-rate")) > 0 ? parseFloat(thisOption.attr("data-rate")) : calculatorBtnValues.attr("data-rate")),
			sLoanViewUID = thisOption.attr("data-loan-view-uid"),
			sStockUID = thisOption.attr("data-stock-uid"),
			/*nTimeMin = parseInt(thisOption.attr("data-time-min")) > 0 ? parseInt(thisOption.attr("data-time-min")) : calculatorBtnValues.attr("data-date-min"),
			nTimeMax = parseInt(thisOption.attr("data-time-max")) > 0 ? parseInt(thisOption.attr("data-time-max")) : calculatorBtnValues.attr("data-date-max"),
			nSumMin = parseInt(thisOption.attr("data-sum-min")) > 0 ? parseInt(thisOption.attr("data-sum-min")) : calculatorBtnValues.attr("data-sum-min"),
			nSumMax = parseInt(thisOption.attr("data-sum-max")) > 0 ? parseInt(thisOption.attr("data-sum-max")) : calculatorBtnValues.attr("data-sum-max");*/
			nTimeMin = parseInt(thisOption.attr("data-time-min")) > 0 ? parseInt(thisOption.attr("data-time-min")) : 1,
			nTimeMax = parseInt(thisOption.attr("data-time-max")) > 0 ? parseInt(thisOption.attr("data-time-max")) : 60,
			nSumMin = parseInt(thisOption.attr("data-sum-min")) > 0 ? parseInt(thisOption.attr("data-sum-min")) : 1000,
			nSumMax = parseInt(thisOption.attr("data-sum-max")) > 0 ? parseInt(thisOption.attr("data-sum-max")) : 30000;

			calculatorBtnValues.attr("data-rate", nRate);
			calculatorBtnValues.attr("data-loan-view-uid", sLoanViewUID);
			calculatorBtnValues.attr("data-stock-uid", sStockUID);
			calculatorBtnValues.attr("data-date-min", nTimeMin);
			calculatorBtnValues.attr("data-date-max", nTimeMax);
			calculatorBtnValues.attr("data-sum-min", nSumMin);
			calculatorBtnValues.attr("data-sum-max", nSumMax);

			calculatorBtnValues.trigger('change');

	});

	$('form#form-calculator .calculator__btn, .header__apply-btn--calculator').on('click', function(event) {
		event.preventDefault();

		/*Тут запихнуть проверку на выбранный офис и статус клиента*/

		if(
			!$('form#form-calculator select[name="REGION_ID"]').val() ||
			!$('form#form-calculator select[name="CITY_ID"]').val() ||
			!$('form#form-calculator select[name="OFFICE_ID"]').val() ||
			!$('form#form-calculator select[name="LOAN_VIEW"]').val()

		) {
			showAlertUp('calc-geo');
			$('<div id="resume-order-load">').appendTo('body').hide();
		}
	else
		showAlertUp('order-loan');
	});

	$('form#order-loan').on('submit', function(event) {
		event.preventDefault();

		var calcForm = $('form#form-calculator'),
			thisForm = $(this),
			thisFormInputs = thisForm.find('input[type="text"], input[type="tel"], input[type="email"]'),
			bErrors = false;

			thisFormInputs.removeClass('input--error');

			thisFormInputs.each(function(index, el) {
				var thisField = $(this);
				if(thisField.val().length == 0) {
					thisField.addClass('input--error').one('click', function(event) {
						$(this).removeClass('input--error');
					});
					bErrors = true;
				}
			});

			if(!bErrors) {

				calcForm.find('input[name="USER_FIRST_NAME"]').val(thisForm.find('input[name="FIRST_NAME"]').val());
				calcForm.find('input[name="USER_LAST_NAME"]').val(thisForm.find('input[name="LAST_NAME"]').val());
				calcForm.find('input[name="USER_MIDDLE_NAME"]').val(thisForm.find('input[name="MIDDLE_NAME"]').val());
				calcForm.find('input[name="USER_BIRTHDAY"]').val(thisForm.find('input[name="BIRTHDAY"]').val());
				calcForm.find('input[name="USER_PHONE"]').val(thisForm.find('input[name="PHONE"]').val());
				calcForm.find('input[name="USER_EMAIL"]').val(thisForm.find('input[name="EMAIL"]').val());
				calcForm.find('input[name="g-recaptcha-response"]').val(grecaptcha.getResponse(widgetLoanId));

				var selectedLoanView = calcForm.find('select[name="LOAN_VIEW"] option:selected'),
					selectedOffice = calcForm.find('select[name="OFFICE_ID"] option:selected');

				calcForm.find('input[name="LOAN_NAME"]').val(selectedLoanView.text());
				calcForm.find('input[name="OFFICE_NAME"]').val(selectedOffice.text());
				calcForm.find('input[name="LOAN_VIEW_UID"]').val(selectedLoanView.attr('data-loan-view-uid'));
				calcForm.find('input[name="LOAN_STOCK_UID"]').val(selectedLoanView.attr('data-stock-uid'));

				var calcFormData = calcForm.serialize();

				calcFormData += "&sessid=" + BX.bitrix_sessid();

				if(parseInt(thisForm.find('input[name="FZ_ACCEPT"]:checked').val()) != 1) {
					thisForm.find('input[name="FZ_ACCEPT"]').closest('label').addClass('error-blink').one('click', function(event) {
						$(this).removeClass('error-blink');
					});
					setTimeout(function(){
						thisForm.find('input[name="FZ_ACCEPT"]').closest('label').removeClass('error-blink');
					}, 2000);
				}
				else {

					$.ajax({
					 	url: SITE_TEMPLATE_PATH + "/ajax/loan.php",
					 	type: 'POST',
					 	data: calcFormData,
					})
					.done(function(data) {
					 	var data = JSON.parse(data);
					 	console.log(data);
					 	if(data.SUCCESS) {
							thisForm.find('input[type="text"], input[type="tel"], input[type="email"]').removeClass('input--actived').val("");
							var oMsgSuccess = $('<div class="msg msg--success"><div>'+data.MESSAGE+'</div></div>').prependTo(thisForm);

							// <div class="msg msg--success"><div>Заявка #123 принята. Наш сотрудник свяжется с вами в ближайшее время.</div></div>

                            var button = thisForm.find('.btn'),
                                buttonText = button.find('.btn__tick');

                            if (!buttonText.data('text'))
                                buttonText.data('text', buttonText.text());


                            buttonText.html('<svg width="29" height="23" viewBox="0 0 58 45" xmlns="http://www.w3.org/2000/svg"><path fill="#fff" fill-rule="nonzero" d="M19.11 44.64L.27 25.81l5.66-5.66 13.18 13.18L52.07.38l5.65 5.65"/></svg>');
                            button.addClass('btn--circle').prop('disabled', true);

							setTimeout(function(){
                                buttonText.html(buttonText.data('text'));
                                button.removeClass('btn--circle').prop('disabled', false);
								oMsgSuccess.remove();
							}, 5000);
						} else {

							grecaptcha.reset(widgetLoanId);

							if(data.CAPTCHA) {

								thisForm.find('.g-recaptcha').addClass('error-blink').one('click', function(event) {
									$(this).removeClass('error-blink');
								});

								setTimeout(function(){
									thisForm.find('.g-recaptcha').removeClass('error-blink');
								}, 2000);

							}
							else
								console.log(data.MESSAGE);

						}
					})
					.fail(function() {
					})
					.always(function() {
					});
				 }

			}

			return false;
	});

	$("form#form-calculator-mobile").on('submit', function(event) {
		event.preventDefault();

		var thisForm = $(this),
			bErrors = false;

		thisForm.find('select').each(function(index, el) {
			if($(this).val() == 'none' || $(this).val().length == 0) {
				bErrors = true;
				$(this).next().find('span.select2-selection--single').addClass('animated blink');
			}
		});

		if(!bErrors) {
			//$('form#form-calculator .calculator__select-office-btn').html(thisForm.find('select[name="OFFICE_ID"] option:selected').text());
			$(this).find('a.alert_up__close').trigger('click');
			if($("#resume-order-load").length > 0)
				showAlertUp('order-loan');
			$("#resume-order-load").remove();
		}

		return false;
	});

	$('.jobs-list__response-btn').on('click', function(event) {
		var sJobName = $(this).siblings('.jobs-list__name').html(),
			sJobCity = $(this).siblings('.job-list__hider').find('.jobs-list__city').html();
		$('.alert_up__window--jobs .job-name').html('&laquo;'+sJobName+'&raquo;');
		$('.alert_up__window--jobs input[name="USER_JOB_NAME"]').val(sJobName);
		$('.alert_up__window--jobs .job-city').html(sJobCity);
		$('.alert_up__window--jobs input[name="USER_JOB_CITY"]').val(sJobCity);

		var arrCities = arr = sJobCity.split(', ');

		$("#jobs-city-select").html('<option value="none" selected="selected">Выбрать город</option>');

		for(p in arrCities)
			$("#jobs-city-select").append(new Option(arrCities[p], arrCities[p]));

		updateSelect2($("#jobs-city-select"));

		/*

		$('form#form-calculator .calculator__select-office-btn').html('Выбрать офис');

		$('form#form-calculator select[name="CITY_ID"]').html('<option value="none" selected="selected">Выбрать город</option>').attr("disabled", "disabled");
		updateSelect2('form#form-calculator select[name="CITY_ID"]');
		 */
	});

	//AJAX подгрузка новостей и акций
	$('#ajax-load-news, #ajax-load-reviews').on('click', function(event) {
		event.preventDefault();

		var thisBtn = $(this);
			nTotalNewsCount = parseInt($(this).data("all-news-count")),
			nNewsPerPage = parseInt($(this).data("news-per-page")),
			nCurrentPage = parseInt($(this).attr("data-current-page")),
			nMaxPages = Math.ceil(nTotalNewsCount/nNewsPerPage),
			sUrl = $(this).data("page-url");

		nNextPage = nCurrentPage + 1;

		if(nNextPage <= nMaxPages){

			$.get(sUrl + (stripos(sUrl, "?") === false ? "?" : "&") + "AJAX=Y&PAGEN_1=" + nNextPage, function(data) {

				$(data).appendTo(thisBtn.siblings('ul')).hide().slideDown('fast');

				nCurrentPage++;

				if(nCurrentPage == nMaxPages)
					thisBtn.hide();
				else
					thisBtn.attr("data-current-page", nCurrentPage);
			});

		}
		else thisBtn.hide();

	});

	$('form#form-job input[name="USER_RESUME"]').on('change', function(event) {
		event.preventDefault();

		var formData = new FormData(),
			thisForm =  $(this).closest('form'),
			thisInput = $(this),
			inputLine = $(this).closest('.input-line'),
			spanInfo = $(this).closest('label').next(),
			btnSubmit = thisForm.find('input[type="submit"]').closest('label');

			inputLine.removeClass('input-line--error');
			spanInfo.html('Файл не выбран');

			if($(this).get(0).files[0].size > (5 * 1000 * 1000)) {
				inputLine.addClass('input-line--error').attr('data-msg-error', 'Размер файла превышает 5 Мб');
				return false;
			}

    		formData.append('file', $(this).get(0).files[0]);

	        $.ajax({
		    	url: SITE_TEMPLATE_PATH + "/ajax/upload_resume.php?FILE_UPLOAD=Y&sessid="+ BX.bitrix_sessid(),
				type: "POST",
				data: formData,
				contentType: false,
				cache: false,
				processData: false,
				xhr: function()	{
					var xhr = new window.XMLHttpRequest();
					// прогресс загрузки на сервер
					xhr.upload.addEventListener("progress", function(evt) {

						btnSubmit.addClass('btn--disabled');
						btnSubmit.children('input').attr("disabled", "disabled");
						thisInput.closest('label').addClass('btn--disabled');
						thisInput.attr("disabled", "disabled");

						if (evt.lengthComputable) {

							var percentComplete = evt.loaded / evt.total,
								val = parseInt(percentComplete * 100);

							spanInfo.html("Загрузка " + val + "%");

						}

					}, false);

					return xhr;
				}
		    })
		    .done(function(data) {

		    	var data = JSON.parse(data);

				btnSubmit.removeClass('btn--disabled');
				btnSubmit.children('input').removeAttr("disabled", "disabled");
				thisInput.closest('label').removeClass('btn--disabled');
				thisInput.removeAttr("disabled", "disabled");

		    	if(data.SUCCESS) {
		    		thisForm.find('input[name="USER_RESUME_FILE"]').val(data.FILE);
		    		thisInput.closest('label').hide();
		    		$("#remove-resume-file").show();
		    		spanInfo.html(data.MESSAGE);
		    	}
		    	else if(data.ERROR) {
		    		inputLine.addClass('input-line--error').attr('data-msg-error', data.MESSAGE);
		    		spanInfo.html('Файл не выбран');
		    	}

		    })
		    .fail(function(data, xhr, test) {
		    	console.log(data, xhr, test);
		    })
		    .always(function() {
		    });

	});

	$('#remove-resume-file').on('click', function(event) {
		event.preventDefault();

		var thisForm = $(this).closest('form'),
			inputFileBtn = thisForm.find('input[name="USER_RESUME"]').closest('label');
			inputFile = thisForm.find('input[name="USER_RESUME_FILE"]'),
			spanInfo = inputFileBtn.next();

		inputFile.val("");
		thisForm.find('input[name="USER_RESUME"]').val("");
		inputFileBtn.show();
		$(this).hide();
		spanInfo.html('Файл не выбран');
	});


	//Отклик на вакансию
	$('form#form-job').on('submit', function(event) {
		event.preventDefault();

		var thisForm = $(this),
			formData = thisForm.serialize(),
			inputFile = thisForm.find('input[type="file"]'),
			inputFileBtn = thisForm.find('input[name="USER_RESUME"]').closest('label'),
			spanInfo = thisForm.find('input[name="USER_RESUME"]').closest('label').next('span');

		thisForm.find('input[type="text"], input[type="tel"]').removeClass('input--error');
		thisForm.find('input[type="file"]').closest('.input-line').removeClass('input-line--error');

		if(parseInt(thisForm.find('input[name="FZ_ACCEPT"]:checked').val()) != 1) {
			thisForm.find('input[name="FZ_ACCEPT"]').closest('label').addClass('error-blink').one('click', function(event) {
				$(this).removeClass('error-blink');
			});
			setTimeout(function(){
				thisForm.find('input[name="FZ_ACCEPT"]').closest('label').removeClass('error-blink');
			}, 2000);
		}
		else {

			$.ajax({
				url: SITE_TEMPLATE_PATH + '/ajax/job.php',
				type: 'POST',
				data: formData,
			})
			.done(function(data) {
				var data = JSON.parse(data);

				if(data.SUCCESS) {
					thisForm.find('input[type="text"], input[type="tel"]').removeClass('input--actived').val("");
					spanInfo.html('Файл не выбран');
					inputFile.val("");
					$('#remove-resume-file').hide();
					inputFileBtn.show();

					var oMsgSuccess = $('<div class="msg msg--success"><div>'+data.MESSAGE+'</div></div>').prependTo(thisForm);
					setTimeout(function(){
						oMsgSuccess.remove();
					}, 5000);
				} else {
					console.log(data);
					for(p in data.MESSAGE) {
						var thisField = $('input[name="'+data.MESSAGE[p].FIELD+'"]');

						if(data.MESSAGE[p].FIELD == 'USER_RESUME_FILE') {
							inputFile.closest('.input-line').addClass('input-line--error').attr('data-msg-error', data.MESSAGE[p].MESSAGE);
							spanInfo.html('Файл не выбран');
							inputFile.val("");
							$('#remove-resume-file').hide();
							inputFileBtn.show();
						}
						else {

							thisField.addClass('input--error').one('click', function(event) {
								$(this).removeClass('input--error');
							});

						}
					}
				}

			})
			.fail(function() {
			})
			.always(function() {
			});

		}


		return false;
	});

	//Обратный звонок
	$('form#form-callback').on('submit', function (event) {
		event.preventDefault();

		var thisForm = $(this),
			formData = thisForm.serialize();

		thisForm.find('.input--error').removeClass('input--error');

		if (parseInt(thisForm.find('input[name="FZ_ACCEPT"]:checked').val()) != 1) {
			thisForm.find('input[name="FZ_ACCEPT"]').closest('label').addClass('error-blink').one('click', function (event) {
				$(this).removeClass('error-blink');
			});
			setTimeout(function () {
				thisForm.find('input[name="FZ_ACCEPT"]').closest('label').removeClass('error-blink');
			}, 2000);
		} else {
			$.ajax({
				url: SITE_TEMPLATE_PATH + '/ajax/callback.php',
				type: 'POST',
				data: formData,
			}).done(function (data) {
				var data = JSON.parse(data);

				if (data.SUCCESS) {
					if (window.yaCounter45766392) {
						window.yaCounter45766392.reachGoal('FORM_FEEDBACK_SEND');
					}

					if (window.ym) {
						//ym(45766392, 'reachGoal', 'FORM_FEEDBACK_SEND');
					}

					if (window.gtag) {
						gtag('event', 'send', {
							event_category: 'form',
							event_label: 'FORM_FEEDBACK_SEND'
						});
					}

					thisForm.find('input[type="text"], input[type="tel"]').removeClass('input--actived').val("");
					var oMsgSuccess = $('<div class="msg msg--success"><div>' + data.MESSAGE + '</div></div>').prependTo(thisForm);
					setTimeout(function () {
						oMsgSuccess.remove();
					}, 5000);
				} else {
					grecaptcha.reset(widgetCallbackId);

					if (data.CAPTCHA) {
						thisForm.find('.g-recaptcha').addClass('error-blink').one('click', function (event) {
							$(this).removeClass('error-blink');
						});

						setTimeout(function () {
							thisForm.find('.g-recaptcha').removeClass('error-blink');
						}, 2000);
					} else {

						for (var p in data.MESSAGE) {
							var thisField = data.MESSAGE[p].FIELD != 'USER_CITY' ? $('input[name="' + data.MESSAGE[p].FIELD + '"]') : $('select[name="' + data.MESSAGE[p].FIELD + '"]').next().find('span.select2-selection--single');

							thisField.addClass('input--error').one('click', function (event) {
								$(this).removeClass('input--error');
							});
						}
					}
				}
			});
		}

		return false;
	});

	//Обратная связь
	$('form#form-feedback').on('submit', function(event) {
		event.preventDefault();

		var thisForm = $(this),
			formData = thisForm.serialize();

		thisForm.find('.input--error').removeClass('input--error');

		if(parseInt(thisForm.find('input[name="FZ_ACCEPT"]:checked').val()) != 1) {
			thisForm.find('input[name="FZ_ACCEPT"]').closest('label').addClass('error-blink').one('click', function(event) {
				$(this).removeClass('error-blink');
			});
			setTimeout(function(){
				thisForm.find('input[name="FZ_ACCEPT"]').closest('label').removeClass('error-blink');
			}, 2000);
		}
		else {
			$.ajax({
				url: SITE_TEMPLATE_PATH + '/ajax/feedback.php',
				type: 'POST',
				data: formData,
			})
			.done(function(data) {
				var data = JSON.parse(data);

				if(data.SUCCESS) {
					thisForm.find('input[type="text"], input[type="tel"], textarea').removeClass('input--actived').val("");

					thisForm.find('.input-line--dropfile.input-line--file-uploaded').remove();
					thisForm.find('input[name="USER_FILES_PATH[]"]').remove();
					var objDropZone = thisForm.find('.input-line.input-line--dropfile'),
						sDropZoneDefaultText = objDropZone.children('span').data('text');
					objDropZone.children('span').html(sDropZoneDefaultText).show();
					objDropZone.removeClass('input-line--dropfile-drop').show();

					$("select[name='USER_SUBJECT']").select2("val", "none");
					$("select[name='USER_SUBJECT']").next('span').removeClass('select2-container--below');
					var oMsgSuccess = $('<div class="msg msg--success"><div>'+data.MESSAGE+'</div></div>').prependTo(thisForm);
					setTimeout(function(){
						oMsgSuccess.remove();
					}, 5000);
				} else {

					grecaptcha.reset(widgetFeedbackId);

					if(data.CAPTCHA) {

						thisForm.find('.g-recaptcha').addClass('error-blink').one('click', function(event) {
							$(this).removeClass('error-blink');
						});

						setTimeout(function(){
							thisForm.find('.g-recaptcha').removeClass('error-blink');
						}, 2000);

					}
					else {

						for(p in data.MESSAGE) {

							var thisField = data.MESSAGE[p].FIELD != 'USER_CITY' ? $('input[name="'+data.MESSAGE[p].FIELD+'"]') : $('select[name="'+data.MESSAGE[p].FIELD+'"]').next().find('span.select2-selection--single');

							thisField.addClass('input--error').one('click', function(event) {
								$(this).removeClass('input--error');
							});

						}

					}

				}

			})
			.fail(function() {
			})
			.always(function() {
			});
		}


		return false;
	});

	//Аренда
	$('form#form-rent').on('submit', function(event) {
		event.preventDefault();

		var thisForm = $(this),
			formData = thisForm.serialize();

		thisForm.find('input[type="text"], input[type="tel"], textarea').removeClass('input--error');
		if(parseInt(thisForm.find('input[name="FZ_ACCEPT"]:checked').val()) != 1) {
			thisForm.find('input[name="FZ_ACCEPT"]').closest('label').addClass('error-blink').one('click', function(event) {
				$(this).removeClass('error-blink');
			});
			setTimeout(function(){
				thisForm.find('input[name="FZ_ACCEPT"]').closest('label').removeClass('error-blink');
			}, 2000);
		}
		else {
			$.ajax({
				url: SITE_TEMPLATE_PATH + '/ajax/rent.php',
				type: 'POST',
				data: formData,
			})
			.done(function(data) {
				var data = JSON.parse(data);

				if(data.SUCCESS) {
					thisForm.find('input[type="text"], input[type="tel"], textarea').removeClass('input--actived').val("");
					var oMsgSuccess = $('<div class="msg msg--success"><div>'+data.MESSAGE+'</div></div>').prependTo(thisForm);
					setTimeout(function(){
						oMsgSuccess.remove();
					}, 5000);
				} else {
					for(p in data.MESSAGE) {
						var thisField = $('[name="'+data.MESSAGE[p].FIELD+'"]');
						thisField.addClass('input--error').one('click', function(event) {
							$(this).removeClass('input--error');
						});
					}
				}

			})
			.fail(function() {
			})
			.always(function() {
			});
		}

		return false;
	});

	$('.input--only-l').on('keypress', function() {
		var that = this;

		setTimeout(function() {
			var res = /[^а-я ]/ig.exec(that.value);
			console.log(res);
			that.value = that.value.replace(res, '');
		}, 0);
	});

	var objMainNewsListSlickConfig = {
		dots: true,
		arrows: false,
		infinite: true,
		speed: 700,
		slidesToShow: 1,
		slidesToScroll: 1,
	    mobileFirst: true,
		responsive: [
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 2,
					dots: true,
					arrows: false
				}
			},
			{
				breakpoint: 960,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 3,
					dots: true,
					arrows: false
				}
			},
			{
				breakpoint: 1300,
				settings: {
					slidesToShow: 4,
					slidesToScroll: 4,
					dots: false,
					arrows: true
				}
			}
		]
	}

	function updateSlick() {

		if($('.frontend.home .news__list').hasClass('slick-initialized'))
			$('.frontend.home .news__list').slick('unslick');

		$('.frontend.home .news__list').slick(objMainNewsListSlickConfig);

	}

	updateSlick();

	if($('#dropZone').length > 0) {

		$('#dropZone').on('click', '.link-file-btn', function(event) {
			event.preventDefault();
			$('#dropZone').find('.input-line__input-file').click();
		});

		var dropZone = $('#dropZone'),
			maxFileSize = 7000000, // максимальный размер файла - 7 мб.
			maxFileCount = 3,
			acceptedFileTypes = ["image/jpeg", "image/jpg", "image/png", "application/vnd.openxmlformats-officedocument.wordprocessingml.document", "text/plain", "application/pdf", "application/vnd.oasis.opendocument.text"];

		if (typeof(window.FileReader) == 'undefined') {
			dropZone.children('span').html("");
		}

		dropZone[0].ondragover = function() {
			dropZone.addClass('input-line--dropfile-hover');
			clearDropZone();
			return false;
		};

		dropZone[0].ondragleave = function() {
			dropZone.removeClass('input-line--dropfile-hover');
			return false;
		};

		var uploadContactFormFiles = function(droppedFiles) {

			if(droppedFiles.length == 0)
				return false;
			else if(droppedFiles.length > maxFileCount) {
				dropZone.children('span').html('Вы превысили максимальное количество файлов! Максимальное количество файлов не должно превышать '+maxFileCount+'. <span class="link-file-btn">Повторите попытку</span>');
	            dropZone.removeClass('input-line--dropfile-drop').addClass('input-line--dropfile-error');
	            return false;
			}

			dropZone.removeClass('input-line--dropfile-hover');
			dropZone.addClass('input-line--dropfile-drop');

	        var form_data = new FormData();

		    for(i = 0; i < droppedFiles.length; i++) {

		        // Проверяем размер файла
		        if (droppedFiles[i].size > maxFileSize) {
		            dropZone.children('span').html('Файл слишком большой! Максимальный размер файла не должен превышать '+(maxFileSize/1000000)+' Мб. <span class="link-file-btn">Повторите попытку</span>');
		            dropZone.removeClass('input-line--dropfile-drop').addClass('input-line--dropfile-error');
		            return false;
		        }
		        else if($.inArray(droppedFiles[i].type, acceptedFileTypes) == -1) {
		            dropZone.children('span').html('Неверный формат. Разрешены файлы формата jpg, png, pdf, doc, docx, odt и txt. <span class="link-file-btn">Повторите попытку</span>');
		            dropZone.removeClass('input-line--dropfile-drop').addClass('input-line--dropfile-error');
		            return false;
		        }

		        form_data.append('USER_FILES['+i+']', droppedFiles[i]);

			}

	        $.ajax({
		    	url: SITE_TEMPLATE_PATH + "/ajax/dropzone_files.php?DROPZONE_FILES_UPLOAD=Y&sessid="+ BX.bitrix_sessid(),
				type: "POST",
				data: form_data,
				contentType: false,
				cache: false,
				processData: false,
				xhr: function()	{
					var xhr = new window.XMLHttpRequest();

					xhr.upload.addEventListener("progress", function(evt) {

						dropZone.children('span').hide();
						dropZone.children('.input-line__dropfile-pg-container').show();

						if (evt.lengthComputable) {

							var percentComplete = evt.loaded / evt.total,
								val = parseInt(percentComplete * 100),
								$pbartext = dropZone.find('.input-line__dropfile-pg-text'),
								$pbarload = dropZone.find('.input-line__dropfile-pg-load span');

							if (isNaN(val)) {
								val = 100;
							}
							else{

								if (val < 0)
									val = 0;
								if (val > 100)
									val = 100;

								$pbartext.html(val + "%");
								$pbarload.css('width', val + "%");
							}

						}

					}, false);

					return xhr;
				}
		    })
		    .done(function(data) {

		    	var data = JSON.parse(data);

				dropZone.children('.input-line__dropfile-pg-container').hide();

		    	if(data.SUCCESS) {

		    		var divFilesExt = '<div class="input-line--dropfile input-line--file-uploaded">';

		    		for(i in data.RESPONSE.FILES_EXT) {
		    			divFilesExt += '<div class="input-line__dropfile-file" data-filepath="'+data.RESPONSE.FILES_PATH[i]+'"><div class="remove">x</div>' + data.RESPONSE.FILES_EXT[i] + '</div>';
		    			dropZone.closest('form').append('<input type="hidden" name="USER_FILES_PATH[]" value="'+data.RESPONSE.FILES_PATH[i]+'">');
		    		}

		    		divFilesExt += '</div>';

		    		$(divFilesExt).insertAfter(dropZone);
		    		dropZone.hide();

		    		$('.input-line__dropfile-file > .remove').one('click', function(event) {

		    			var objDropFileNewContainer = $(this).closest('.input-line--file-uploaded'),
		    				objHiddenInput = $(this).parent().attr("data-filepath");

		    			dropZone.closest('form').find('input[value="'+objHiddenInput+'"]').remove();

		    			$(this).parent().remove();

		    			if(objDropFileNewContainer.find('.input-line__dropfile-file').length == 0) {
		    				dropZone.removeClass('input-line--dropfile-drop');
		    				objDropFileNewContainer.remove();
		    				dropZone.children('span').show();
		    				dropZone.show();
		    			}

		    		});
		    	}
		    	else {
		            dropZone.children('span').html(data.RESPONSE);
		            dropZone.removeClass('input-line--dropfile-drop').addClass('input-line--dropfile-error');
		    	}

		    })
		    .fail(function() {
		    })
		    .always(function() {
		    });
		}

		$(dropZone[0]).on('drop', function(event) {
			event.preventDefault();
			uploadContactFormFiles(event.originalEvent.dataTransfer.files);
		});

		$("#dropZone input[name='USER_FILES[]']").on('change', function(event) {

			event.preventDefault();
			clearDropZone();
			uploadContactFormFiles(this.files);

		});

		$(document).on('submit', 'form#form-send-competition-photo', function(event) {
			event.preventDefault();

			var thisForm = $(this),
				thisBtnSubmit = thisForm.find('[type="submit"]'),
				image = thisForm.find("input[name='COMPETITION_USER_PHOTO']").val(),
				competitionId = parseInt($(".gifts-detail").data("competition-id"));

			thisForm.find('.input-line--dropfile').removeClass('input-line--dropfile-error');
			thisForm.find('.input-line--error-message').remove();


			if(image.length == 0) {
				thisForm.find('.input-line--dropfile').addClass('input-line--dropfile-error');
				return false;
			}

			$.ajax({
				url: SITE_TEMPLATE_PATH + "/ajax/competition_work_photo.php",
				type: 'GET',
				data: "AJAX_SEND_COMPETITION_WORK=Y&USER_IMAGE="+image+"&COMPETITION_ID="+competitionId+"&sessid="+ BX.bitrix_sessid(),
			})
			.done(function(data) {
		    	var data = JSON.parse(data);

		    	if(data.SUCCESS) {
		    		thisForm.find('.input-line--dropfile').remove();
		    		thisForm.find('.alert-lk__delete-box').remove();
		    		$('<p class="h5">'+data.MESSAGE+'</p>').insertBefore(thisBtnSubmit);
		    		thisBtnSubmit.remove();
		    	}
		    	else
		    		$('<div class="input-line--error-message">'+data.MESSAGE+'</div>').insertBefore(thisBtnSubmit);
			})
			.fail(function() {

			})
			.always(function() {

			});

			return false;
		});

		function clearDropZone() {
			var sDropZoneDefaultText = dropZone.children('span').data('text');
			dropZone.removeClass('input-line--dropfile-error').children('span').html(sDropZoneDefaultText);
		}

	}

});

function stripos( f_haystack, f_needle, f_offset ){	// Find position of first occurrence of a case-insensitive string
	//
	// +	 original by: Martijn Wieringa

	var haystack = f_haystack.toLowerCase();
	var needle = f_needle.toLowerCase();
	var index = 0;

	if(f_offset == undefined) {
		f_offset = 0;
	}

	if((index = haystack.indexOf(needle, f_offset)) > -1) {
		return index;
	}

	return false;
}

function stristr( haystack, needle, bool ) {	// Case-insensitive strstr()
	//
	// +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)

	var pos = 0;

	pos = haystack.toLowerCase().indexOf( needle.toLowerCase() );
	if( pos == -1 ){
		return false;
	} else{
		if( bool ){
			return haystack.substr( 0, pos );
		} else{
			return haystack.slice( pos );
		}
	}
}

function setCookie(name, value, options) {
  options = options || {};

  var expires = options.expires;

  if (typeof expires == "number" && expires) {
    var d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }
  if (expires && expires.toUTCString) {
    options.expires = expires.toUTCString();
  }

  value = encodeURIComponent(value);

  var updatedCookie = name + "=" + value;

  for (var propName in options) {
    updatedCookie += "; " + propName;
    var propValue = options[propName];
    if (propValue !== true) {
      updatedCookie += "=" + propValue;
    }
  }

  document.cookie = updatedCookie;
}

function getCookie(name) {
  var matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function phone_format(number, code) {
	code = code === undefined ? "+7" : code;
	return code + " " + number.substr(-10).replace(/(\d\d\d)(\d\d\d)(\d\d)(\d\d)/, "($1) $2-$3-$4");
}

function clearOverlays() {
	for (var i = 0; i < gMarkersArray.length; i++ ) {
		gMarkersArray[i].setMap(null);
	}
	gMarkersArray.length = 0;
}