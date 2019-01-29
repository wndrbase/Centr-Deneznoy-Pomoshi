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
$this->setFrameMode(true);

use Bitrix\Main\Localization\Loc;
?>

<?if($arResult["ITEMS"]):?>
	<div class="organizations">
		<ul class="organizations__list clr">
			<?foreach($arResult["ITEMS"] as $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<li class="organizations__item" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
						<div class="organizations__name"><?=str_ireplace(Array(Loc::getMessage("CDP_O_OOO_MK"), Loc::getMessage("CDP_O_OOO")), Array(Loc::GetMessage("CDP_O_OOO_MK_1"), Loc::GetMessage("CDP_O_OOO_1")), $arItem["NAME"])?></div>
					<?endif;?>					
					<a href="javascript:;" class="organizations__show-requisites"><?=Loc::getMessage("CDP_O_SHOW_REQUISITES")?></a>
					<div class="organizations__requisites">
						<p>
							<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty):?>
								<?=$arProperty["NAME"]?> 
								<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
									<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
								<?else:?>
									<?=$arProperty["DISPLAY_VALUE"];?>
								<?endif?>
								<br />
							<?endforeach;?>
						</p>
					</div>
				</li>
			<?endforeach;?>
		</ul>
	</div>
<?endif;?>
