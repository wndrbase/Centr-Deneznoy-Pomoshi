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

global $AR_REGIONS_AND_CITIES;

$sWrapperClass = $arParams['WRAPPER_CLASS'] ? " " . $arParams['WRAPPER_CLASS'] : "";
?>

<?if($arResult["ITEMS"]):?>
	<div class="offices<?=$sWrapperClass?> clr">

		<?if($AR_REGIONS_AND_CITIES):?>
			<form class="offices__filter clr" id="offices-address">
				<span class="offices__filter-label"><?=Loc::getMessage("CDP_BA_TITLE")?>:</span>
				<span class="offices__filter-box">
					<select name="REGION_ID">
						<option value="none"<?=(AB_S1::$GEODATA['REGION_ID']?'':' selected="selected"')?>><?=Loc::getMessage("CDP_BA_FORM_OPTION_SELECT_REGION")?></option>
						<?foreach($AR_REGIONS_AND_CITIES as $key => $value):?>
							<option value="<?=$key?>"<?=(AB_S1::$GEODATA['REGION_ID']&&AB_S1::$GEODATA['REGION_ID']==$key?' selected="selected"':'')?>><?=$value['NAME']?></option>
						<?endforeach;?>
					</select>
				</span>
				<span class="offices__filter-box offices__filter-box--last">
					<select name="CITY_ID">
						<option value="none"><?=Loc::getMessage("CDP_BA_FORM_OPTION_SELECT_CITY")?></option>
						<?if(AB_S1::$GEODATA['REGION_ID'] && AB_S1::$GEODATA['CITY_ID']):?>
							<?foreach($AR_REGIONS_AND_CITIES[AB_S1::$GEODATA['REGION_ID']]['CITIES'] as $key => $value):?>
								<option value="<?=$key?>"<?=(AB_S1::$GEODATA['CITY_ID']==$key?' selected="selected"':'')?>><?=$value['NAME']?></option>
							<?endforeach;?>
						<?endif;?>
					</select>
				</span>
			</form>
		<?endif;?>

		<div class="offices__map">
			<div id="map"></div>
			<script>
				function initMap() {

					<?foreach($arResult["ITEMS"] as $key => $arItem):?>
						<?if($key == 0):?>
							gMap = new google.maps.Map(document.getElementById('map'), {
								zoom: 10,
								center: {lat: <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[0]?>, lng: <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[1]?>}
							});
						<?endif;?>

						var marker = new google.maps.Marker({
							position: {lat: <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[0]?>, lng: <?=explode(",", $arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PLACEMARK']['VALUE'])[1]?>},
							map: gMap,
							title: '<?=$arItem["NAME"]?>',
							icon: {
								url: "<?=SITE_TEMPLATE_PATH?>/img/placemark.png",
								scaledSize: new google.maps.Size(29, 31)
							}
						});

						gMarkersArray.push(marker);

					<?endforeach;?>
				}

				initMap();
			</script>
		</div>

		<ul class="offices__list">
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<li class="offices__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PHONE']['VALUE']):?>
						<div class="offices__line">
							<span class="offices__line-label"><?=Loc::getMessage("CDP_BA_PHONE")?></span>
							<span class="offices__line-value"><?=$arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_PHONE']['VALUE']?></span>
						</div>
					<?endif;?>
					<?if($arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_ADDRESS']['VALUE']):?>
						<div class="offices__line">
							<span class="offices__line-label"><?=Loc::getMessage("CDP_BA_ADDRESS")?></span>
							<span class="offices__line-value"><?=$arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_ADDRESS']['VALUE']?></span>
						</div>
					<?endif;?>
					<?if($arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_SCHEDULE']['~VALUE']['TEXT']):?>
						<div class="offices__line">
							<span class="offices__line-label"><?=Loc::getMessage("CDP_BA_SCHEDULE")?></span>
							<span class="offices__line-value"><?=$arItem['DISPLAY_PROPERTIES']['CDP_G_OFFICE_SCHEDULE']['~VALUE']['TEXT']?></span>
						</div>
					<?endif;?>
				</li>
			<?endforeach;?>
		</ul>

	</div>
<?endif;?>