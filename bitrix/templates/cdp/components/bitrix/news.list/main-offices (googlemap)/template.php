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
	$nZoom = intval(AB_S1::$GEODATA['CITY_ID']) > 0 ? 12 : 3;
?>
	<div class="map">				
		<div class="overtitle"><?=Loc::getMessage("CPD_MO_TITLE")?></div>
		<div class="h1"><?=Loc::getMessage("CPD_MO_TITLE_2")?></div>
		<div id="map"></div>
	</div>
	<script>
		function initMap() {

			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<?if(!$arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE']) continue;?>
				<?if($key == 0):
					if(intval(AB_S1::$GEODATA['CITY_ID']) > 0) { ?>
						var arCenter = [<?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[0]?>, <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[1]?>];	
					<? } else { ?>		
						var arCenter = [54.92714186, 46.71386719];			
					<? } ?>
					var center = {lat: arCenter[0], lng: arCenter[1]};
					var map = new google.maps.Map(document.getElementById('map'), {
						zoom: <?=$nZoom?>,
						center: center
					});
				<?endif;?>
				
				var marker = new google.maps.Marker({
					position: {lat: <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[0]?>, lng: <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[1]?>},
					map: map,
					title: '<?=$arItem["NAME"]?>',
					icon: {
						//url: "img/placemark.svg",
						//scaledSize: new google.maps.Size(29, 31)
						url: "<?=SITE_TEMPLATE_PATH?>/img/placemark.png",
						scaledSize: new google.maps.Size(29, 31)
					}
				});

			<?endforeach;?>

			// contentString - это переменная в которой хранится содержимое информационного окна.
		    // Может содержать, как HTML-код, так и обычный текст.
		    // Если используем HTML, то в этом случае у нас есть возможность стилизовать окно с помощью CSS.
		    /*var contentString = '<div class="map-box">' +
		                            '<h3>Lorem ipsum dolor</h3>' +
		                            '<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Iure, sed.</p>' +
		                        '</div>';
		 
		    // Создаем объект информационного окна и помещаем его в переменную infoWindow
		    var infoWindow = new google.maps.InfoWindow({
		        content: contentString
		    });
		 
		    // Отслеживаем клик по нашему маркеру
		    google.maps.event.addListener(marker, "click", function() {
		        // infoWindow.open - показывает информационное окно.
		        // Параметр map - это переменная содержащие объект карты (объявлена на 8 строке)
		        // Параметр marker - это переменная содержащие объект маркера (объявлена на 23 строке)
		        infoWindow.open(map, marker);
		    });
		 
		    // Отслеживаем клик в любом месте карты
		    google.maps.event.addListener(map, "click", function() {
		        // infoWindow.close - закрываем информационное окно.
		        infoWindow.close();
		    });*/
		}

		initMap();
	</script>
<?endif;?>
