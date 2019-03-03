<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Партнёрам");
?><div class="rent">

	<div class="center clr">
		<div class="h1">Сотрудничество с нами</div>
	
		<ul class="blue-icons-list">
			<li class="blue-icons-list__item">
				<div class="blue-icons-list__ico blue-icons-list__ico--lock"></div>
				<div class="blue-icons-list__title">Выполнение обязательств</div>
				<?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
			</li>
			<li class="blue-icons-list__item">
				<div class="blue-icons-list__ico blue-icons-list__ico--wallet"></div>
				<div class="blue-icons-list__title">Своевременная оплата</div>
				<?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
			</li>
			<li class="blue-icons-list__item">
				<div class="blue-icons-list__ico blue-icons-list__ico--folder"></div>
				<div class="blue-icons-list__title">Официальное оформление</div>
				<?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
			</li>
			<li class="blue-icons-list__item">
				<div class="blue-icons-list__ico blue-icons-list__ico--user"></div>
				<div class="blue-icons-list__title">Сотрудничаем со всеми</div>
				<?/*<div class="blue-icons-list__text">Пожалуйста, обращайтесь в ближайший офис или головной офис в Северодвинске.</div>*/?>
			</li>
		</ul>

		<p class="margin-collapse-15">Мы рассмотрим любые предложения от арендодателей в лице:</p>

		<ul class="content-list">
			<li>Собственников капитальных сооружений и временных строений всех уровней;</li>
			<li>Муниципальных образований, распоряжающихся собственностью;</li>
			<li>Арендаторов помещений (для сдачи в субаренду) в том числе торговые сети, предлагающие в субаренду свободные торговые площади.</li>
		</ul>

		<p>К рассмотрению принимаются предложения аренды помещений в городах и населенных пунктах Центрального, Приволжского, Северо-Западного, Южного федеральных округов РФ.</p>

		<p class="margin-collapse-15">Основные технические характеристики помещений:</p>

		<ul class="content-list">
			<li>общая площадь 5 — 50 м<sup>2</sup>;</li>
			<li>этажность: первые этажи;</li>
			<li>расположение: отдельные помещения, торговые павильоны, помещения на первых этажах торговых центров, на «красной» линии улиц, улицы с оживленным пешеходным движением.</li>
		</ul>

		<p>В случае Вашей заинтересованности и соответствию вышеперечисленным требованиям, нашей компанией будет предоставлена обратная связь путем письменного извещения или телефонного звонка.</p>

		<p>Предложите недвижимость, заполнив анкету.</p>

	</div>

	<form class="rent__form" id="form-rent">
		<div class="center clr">
			<div class="h1">Заполните анкету</div>
			<div class="rent__form-box">
				<div class="input-line input-line--left"><input type="text" name="USER_CITY" class="input" placeholder="Город"></div>
				<div class="input-line input-line--right"><input type="tel" name="USER_PHONE" class="input mask-tel" placeholder="Контактный телефон"></div>
				<div class="input-line input-line--left"><input type="text" name="USER_ADDRESS" class="input" placeholder="Адрес объекта"></div>
				<div class="input-line input-line--right"><input type="text" name="USER_SQUARE" class="input" placeholder="Площадь, м2"></div>
				<div class="input-line">
					<textarea name="USER_MESSAGE" class="input" placeholder="Ваше сообщение"></textarea>							
				</div>
				<div class="input-line">
					<label class="checkbox"><input type="checkbox" name="FZ_ACCEPT" value="1">Я согласен(а) на обработку персональных данных и ознакомлен(а) с <a href="/upload/medialibrary/04d/PDn-TSDP-sayt.pdf" target="_blank">Политикой конфиденциальности ООО МКК "ЦДП"</a>, <a href="/upload/medialibrary/2e8/PDn-TSentr-sayt.pdf" target="_blank">Политикой конфиденциальности ООО МКК "ЦДП-ЦЕНТР"</a>, <a href="/upload/medialibrary/0c0/PDn-Don-sayt.pdf" target="_blank">Политикой конфиденциальности ООО МКК "ЦДП-ДОН"</a></label>
				</div>
				<div class="input-line input-line--submit">
					<input type="hidden" name="AJAX_RENT" value="Y">
					<label href="javascript:;" class="btn rent__form-btn">Отправить<input type="submit"></label>
				</div>
			</div>
		</div>
	</form>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>