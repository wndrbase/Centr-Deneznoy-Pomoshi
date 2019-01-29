<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
?>

<?if($arResult["ITEMS"]):
	global $arOfficesFilter;
	$nZoom = intval(AB_S1::$GEODATA['CITY_ID']) > 0 ? (count($arResult["ITEMS"])>1?12:16) : 4;
?>
	<div class="map">
		<div class="overtitle"><?=Loc::getMessage("CPD_MO_TITLE")?></div>
		<div class="h1"><?=Loc::getMessage("CPD_MO_TITLE_2")?></div>
		<div id="map"></div>

		<script type="text/javascript">
			var myMap,
				myCollection,
				PLACEMARKS = [];

			ymaps.ready(function(){
				myMap = new ymaps.Map('map', {
					center: [54.92714186, 46.71386719],
					zoom: <?=$nZoom?>,
					controls: []
				});

				var zoomControl = new ymaps.control.ZoomControl({
					options: {
						visible: true
					}
				});

				myMap.controls.add(zoomControl);
			});
		</script>
	</div>

	<?foreach($arResult["ITEMS"] as $key => $arItem):?>

		<?if(!$arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE']) continue;?>

		<?if($key == 0):

			if(intval(AB_S1::$GEODATA['CITY_ID']) > 0) {
				$sFirstLN = explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[0];
				$sFirstLT = explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[1];
			}
			else {
				$sFirstLN = 54.92714186;
				$sFirstLT = 46.71386719;
			}

		endif;?>

		<script type="text/javascript">
			ymaps.ready(function(){
				// Создаем метку и задаем изображение для ее иконки
            	var myPlacemark = new ymaps.Placemark([<?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[0]?>, <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[1]?>], {
            		hintContent: '<?=$arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_ADDRESS']['VALUE']?>'//,
            		//balloonContent: 'Это красивая метка'
            	},
            	{
                	/*preset: 'islands#blueCircleDotIconWithCaption',
					iconCaptionMaxWidth: '50'*/
					iconLayout: 'default#image',
					iconImageHref:  '<?=SITE_TEMPLATE_PATH?>/img/placemark.png', // картинка иконки
                    iconImageSize: [29, 31], // размеры картинки
                    iconImageOffset: [-14, -15] // смещение картинки
				});
	        	//Добавление метки на карту
	        	myMap.geoObjects.add(myPlacemark);
	        	PLACEMARKS.push(myPlacemark);
			});
		</script>

	<?endforeach;?>

	<script type="text/javascript">
		ymaps.ready(function(){
			//myMap.setCenter([<?=$sFirstLN?>, <?=$sFirstLT?>], <?=(count($arResult["ITEMS"])>1&&!AB_S1::$GEODATA['OFFICE_ID']?10:16)?>);
			myMap.setCenter([<?=$sFirstLN?>, <?=$sFirstLT?>], <?=$nZoom?>);
		});
	</script>

<?endif;?>
