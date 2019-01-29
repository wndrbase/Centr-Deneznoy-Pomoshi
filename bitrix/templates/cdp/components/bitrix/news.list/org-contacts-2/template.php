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
?>

<?if($arResult["ITEMS"]):?>

	<div class="org__list">

		<h2><?=Loc::getMessage("CDP_O_TITLE")?></h2>

		<ul class="org__box-nav clr notsel">
			<?foreach($arResult["ITEMS"] as $key => $arItem):?>
				<?
				$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
				$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
				?>
				<li class="org__head<?=($key==0?' org__head--active':'')?>" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
					<div class="org__title"><div class="org__text"><?=str_ireplace(Array(Loc::getMessage("CDP_O_OOO_MK"), Loc::getMessage("CDP_O_OOO")), Array(Loc::GetMessage("CDP_O_OOO_MK_1"), Loc::GetMessage("CDP_O_OOO_1")), $arItem["NAME"])?></div></div>
				</li>
			<?endforeach;?>
		</ul>

		<?foreach($arResult["ITEMS"] as $key => $arItem):?>

			<div class="org__body<?=($key==0?' org__body--active':'')?>">

				<div class="org__body-left">

					<?if($arItem['OFFICES']):?>
						<h3><?=Loc::getMessage("CDP_O_OFFICES_ADDRESS")?></h3>

						<ul class="org__offices-list">
							<?foreach($arItem['OFFICES'] as $arOffice):?>
								<li class="org__offices-item">
									<a href="javascript:;"><?=$arOffice['NAME']?></a>
									<?if($arOffice['CITIES']):?>
										<div class="org__region">
											<?foreach($arOffice['CITIES'] as $arCity):?>
												<h4><?=$arCity['NAME']?></h4>
												<?if($arCity['OFFICES']):?>
													<ul class="org__city-address-list">
														<?foreach($arCity['OFFICES'] as $arOffice):?>
															<li class="org__city-address-item"><?=$arOffice?></li>
														<?endforeach;?>
													</ul>
												<?endif;?>
											<?endforeach;?>
										</div>
									<?endif;?>
								</li>
							<?endforeach;?>
						</ul>
					<?endif;?>

				</div>

				<div class="org__body-right">

					<h3><?=Loc::getMessage("CDP_O_CONTACTS_INFO")?></h3>
					<p>
						<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid => $arProperty):
							if($pid == 'CDP_O_MORE_CONTACTS') continue;
						?>
							<?if($pid != 'CDP_O_PHONE'):?>
								<?=$arProperty["NAME"]?>
							<?endif;?>
							<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
								<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
							<?else:?>
								<?if($pid == 'CDP_O_PHONE'):?>
								<a href="tel:<?=preg_replace('/\D/', '', $arProperty["VALUE"])?>"><?=$arProperty["DISPLAY_VALUE"];?></a>
								<?else:?>
								<?=$arProperty["DISPLAY_VALUE"];?>
								<?endif;?>
							<?endif?>
							<br />
						<?endforeach;?>
					</p>

					<?if($arItem["DISPLAY_PROPERTIES"]['CDP_O_MORE_CONTACTS']['VALUE']):?>
					<h3>Контакты для обращений</h3>
					<?=$arItem["DISPLAY_PROPERTIES"]['CDP_O_MORE_CONTACTS']["DISPLAY_VALUE"];?>
					<?endif;?>

				</div>

			</div>

		<?endforeach;?>

	</div>

<?endif;?>