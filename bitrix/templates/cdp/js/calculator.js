(function($){

	/*String.prototype.replaceArray = function(find, replace) {
		var replaceString = this;
		for (var i = 0; i < find.length; i++) {
			replaceString = replaceString.replace(find[i], replace[i]);
		}
		return replaceString;
	};

	function evil(fn) {
		return new Function('return ' + fn)();
	}*/

	var noUiSliderSum = document.getElementById('calc-sum-slider'),
		noUiSliderDate = document.getElementById('calc-date-slider'),

		noUiSliderInitialized = false,

		btn = $('.calculator__data-values'), // кнопка изменения вида займа
		btnActive, // активная кнопка

		// слайдер суммы
		sumMin,
		sumMax,
		sumStep,
		sumValue,

		sumStartText = $("#calc-sum-slider").siblings('.calculator__slider-from'),
		sumEndText = $("#calc-sum-slider").siblings('.calculator__slider-to'),

		inputSum = $("#sum-set"),

		// слайдер срока
		dateMin,
		dateMax,
		dateStep,
		dateValue,

		dateStartText = $("#calc-date-slider").siblings('.calculator__slider-from'),
		dateEndText = $("#calc-date-slider").siblings('.calculator__slider-to'),

		inputDate = $("#date-set"),

		// ставка
		rate,

		// сумма возврата вывод
		sumSetTotal = $(".calculator__total-sum span"),
		// комиссия
		sumSetCommission = $(".calculator__sum-commission"),

		// дата возврата
		dateSetTotal = $('.calculator__date-comeback'),

		dateSetDays = $('.calculator__box-days'),

		FormatSum = wNumb({ thousand: ' ', decimals: 0}),

		isStock = false,

		resizeTimeoutId;


	// выбор типа калькулятора
	btn.on('change', function(){
		btnActive = $(this),
		calculatorInit(true);
	}).filter(':checked').trigger('change');

	// перестроить слайдер
	$(window).on('resize', function(){
		clearTimeout(resizeTimeoutId);
		resizeTimeoutId = setTimeout(function(){
			calculatorInit(false);
		}, 1000);
	});

	function calculatorInit(bReInit) {

		bReInit = bReInit === undefined ? false : bReInit;

		if(bReInit) {

			sumMin = parseInt(btnActive.attr("data-sum-min"));
			sumMax = parseInt(btnActive.attr("data-sum-max"));
			sumStep = parseInt(btnActive.attr("data-sum-step"));
			sumValue = parseInt(btnActive.attr("data-sum-value"));

			dateMin = parseInt(btnActive.attr("data-date-min"));
			dateMax = parseInt(btnActive.attr("data-date-max"));
			dateStep = parseInt(btnActive.attr("data-date-step"));
			dateValue = parseInt(btnActive.attr("data-date-value"));

			rate = parseFloat(btnActive.attr("data-rate"));

		}


		//Если это акция Новый клиент
		if(btnActive.attr('data-stock-uid') == '6310fda2-c258-11e8-889a-000c296183dd') {
			sumMax = 10000;
			isStock = true;
		}
		else {
			sumMax = 30000;
			sumValue = 12000;
			isStock = false;
		}

		sumStartText.html(FormatSum.to(sumMin));
		sumEndText.html(FormatSum.to(sumMax));

		dateStartText.html(dateMin);
		dateEndText.html(dateMax);


		if(bReInit && !noUiSliderInitialized) {

			noUiSlider.create(noUiSliderSum, {
				start: sumValue,
				connect: [true, false],
				margin: 0,
				step: sumStep,
				range: {
					'min': sumMin,
					'max': sumMax
				}
			}).on('slide', function(floatValues, change, intValues){
				sumValue = intValues[0];
				$('#sum-set').val(FormatSum.to(intValues[0]));
				result();
			});

			noUiSlider.create(noUiSliderDate, {
				start: dateValue,
				connect: [true, false],
				margin: 0,
				step: dateStep,
				range: {
					'min': dateMin,
					'max': dateMax
				}
			}).on('slide', function(floatValues, change, intValues){
				dateValue = parseInt(floatValues[0]);
				$('#date-set').val(parseInt(floatValues[0]));
				result();
			});

			noUiSliderInitialized = true;
		}
		else {

			dateValue = Math.ceil(dateMin + ((dateMax-dateMin) / 2));

			noUiSliderDate.noUiSlider.updateOptions(
				{
					start: dateValue,
					range: {
						'min': dateMin,
						'max': dateMax
					}
				},
				true // Boolean 'fireSetEvent'
			);

			sumValue = Math.ceil(sumMin + ((sumMax-sumMin) / 2));

			noUiSliderSum.noUiSlider.updateOptions(
				{
					start: sumValue,
					range: {
						'min': sumMin,
						'max': sumMax
					}
				},
				true // Boolean 'fireSetEvent'
			);


		}

		result();

	}

	function result() {

		btnActive.attr("data-sum-value", sumValue);
		inputSum.val(FormatSum.to(sumValue));

		btnActive.attr("data-date-value", dateValue);
		inputDate.val(dateValue);

		// расчет переплаты (комиссии)
		//Если это Акция Новый клиент
		if(btnActive.attr('data-stock-uid') == '6310fda2-c258-11e8-889a-000c296183dd') {
			diffValue = rate * sumValue * (dateValue-7) / 100;
		}
		else
			diffValue = rate * sumValue * dateValue / 100;

		sumSetCommission.html(FormatSum.to(diffValue));
		sumSetTotal.html(FormatSum.to(sumValue + diffValue));

		// вывод дней в склонении
		dateSufixArr = dateSetDays.attr("data-declension").split(',');
		dateSetDays.text(declension(dateValue, dateSufixArr));

		// вывели дату возврата
		var arMonths = ['января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'],
			refundDate = new Date();
		refundDate.setDate(refundDate.getDate() + dateValue);
		//dateSetTotal.text(('0' + refundDate.getDate()).slice(-2) + '.' + ('0' + (refundDate.getMonth() + 1)).slice(-2) + '.' + refundDate.getFullYear());
		dateSetTotal.text(refundDate.getDate() + ' ' +arMonths[refundDate.getMonth()]);

	}

	// склонение
	function declension(num, expressions) {
		var r;
		var count = num % 100;
		if (count > 4 && count < 21)
			r = expressions['2'];
		else {
			count = count % 10;
			if (count == 1)
				r = expressions['0'];
			else if (count > 1 && count < 5)
				r = expressions['1'];
			else
				r = expressions['2'];
		}
		return r;
	}

	// ввод суммы и даты в инпуте
	inputSum.add(inputDate).on('change keydown blur', function(event) {
		if (event.keyCode == 13) {
			$(this).trigger('blur');
		}
		if (event.type == 'blur') {
			var v = this.value;
			if (v.match(/[^0-9]/g))
				v = v.replace(/[^0-9]/g, '');
			if($(this).is('#sum-set')){
				if(v>sumMax)
					v = sumMax;
				if(v<sumMin)
					v = sumMin;
				sumValue = parseInt(v);
				noUiSliderSum.noUiSlider.set(v);
			}
			else {
				if(v>dateMax)
					v = dateMax;
				if(v<dateMin)
					v = dateMin;
				var arithmetic = (v - dateMin) % dateStep;
				if (arithmetic > 0){
					v = parseInt((v - dateMin) / dateStep) * dateStep + dateMin;
					if (arithmetic * 2 > dateStep){
						v += dateStep;
					}
				}
				dateValue = parseInt(v);
				noUiSliderDate.noUiSlider.set(v);
			}
			result();
		}
	});

})(jQuery);