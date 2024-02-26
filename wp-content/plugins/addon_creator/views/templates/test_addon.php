<?php

$headerTitle = __("Test Addon",UNITECREATOR_TEXTDOMAIN);
$headerTitle .= " - ".$addonTitle;

//$headerAddHtml = "<a href='{$urlEditAddon}' class='unite-link'>".__("Edit This Addon",UNITECREATOR_TEXTDOMAIN)."</a>";

require HelperUC::getPathTemplate("header");

$slot1AddHtml = "";
if($isTestData1 == false)
	$slot1AddHtml = "style='display:none'";


$styleShow = "";
$styleHide = "style='display:none'";


?>

<div id="uc_testaddon_wrapper" class="uc-testaddon-wrapper">

<div class="uc-testaddon-panel">
		
		<a href="<?php echo $urlEditAddon?>" class="unite-button-secondary" ><?php _e("Edit This Addon", UNITECREATOR_TEXTDOMAIN)?></a>
		<a class="unite-button-secondary uc-button-cat-sap" href="<?php echo HelperUC::getViewUrl_Addons()?>"><?php _e("Back to Addons List", UNITECREATOR_TEXTDOMAIN);?></a>
		
		<a id="uc_button_preview" href="javascript:void(0)" class="unite-button-secondary" <?php echo $isPreviewMode?$styleHide:$styleShow?>><?php _e("To Preview", UNITECREATOR_TEXTDOMAIN)?></a>
		<a id="uc_button_close_preview" href="javascript:void(0)" class="unite-button-secondary" <?php echo $isPreviewMode?$styleShow:$styleHide?>><?php _e("Hide Preview", UNITECREATOR_TEXTDOMAIN)?></a>
		
		<a id="uc_button_preview_tab" href="javascript:void(0)" class="unite-button-secondary uc-button-cat-sap"><?php _e("Preview New Tab", UNITECREATOR_TEXTDOMAIN)?></a>
		
		<!-- 
		<input type="text" class="mleft_10 mbottom_0 unite-input-medium" value="<?php echo $urlTestWithData?>" onfocus="this.select()" >
		-->

		<span id="uc_testaddon_slot1" class="uc-testaddon-slot" <?php echo $slot1AddHtml?>>
			<a id="uc_testaddon_button_restore" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Restore Data", UNITECREATOR_TEXTDOMAIN)?></a>
			<span id="uc_testaddon_loader_restore" class="loader-text" style="display:none"><?php _e("loading...")?></span>
			<a id="uc_testaddon_button_delete" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Delete Data", UNITECREATOR_TEXTDOMAIN)?></a>
			<span id="uc_testaddon_loader_delete" class="loader-text" style="display:none"><?php _e("deleting...")?></span>
		</span>
		
		<a id="uc_testaddon_button_save" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Save Data", UNITECREATOR_TEXTDOMAIN)?></a>
		<span id="uc_testaddon_loader_save" class="loader-text" style="display:none"><?php _e("saving...")?></span>
		
		<a id="uc_testaddon_button_clear" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Clear", UNITECREATOR_TEXTDOMAIN)?></a>
		
	
</div>

<?php $addonConfig->putHtmlFrame(); ?>

</div>


<?php 

$addScript = '
		var objTestAddonView = new UniteCreatorTestAddon();
		objTestAddonView.init(objConfig);
';

$addonConfig->putInitScript($addScript); 

?>

			



