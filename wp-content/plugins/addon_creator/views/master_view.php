<?php
/**
 * @package Addon Composer
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

?>

<?php HelperHtmlUC::putGlobalsHtmlOutput(); ?>

	<script type="text/javascript">
		var g_view = "<?php echo self::$view?>";
	</script>

	


<div id="viewWrapper" class="unite-view-wrapper unite-admin unite-inputs">

<?php
	self::requireView($view);
	
	//include provider view if exists
	$filenameProviderView = GlobalsUC::$pathProviderViews.$view.".php";
	if(file_exists($filenameProviderView))
		require_once($filenameProviderView);
?>

</div>

<?php 
	$filepathProviderMasterView = GlobalsUC::$pathProviderViews."master_view.php";
	if(file_exists($filepathProviderMasterView))
		require_once $filepathProviderMasterView;
?>

<div id="uc_dialog_version" title="<?php _e("Version Release Log. Current Version: ".UNITE_CREATOR_VERSION." ", UNITECREATOR_TEXTDOMAIN)?>" style="display:none;">
	<div class="unite-dialog-inside">
		<div id="uc_dialog_version_content" class="unite-dialog-version-content">
			<div id="uc_dialog_loader" class="loader_text"><?php _e("Loading...", UNITECREATOR_TEXTDOMAIN)?></div>
		</div>
	</div>
</div>

<div class="unite-clear"></div>
<div class="unite-plugin-version-line unite-admin">
	<?php UniteProviderFunctionsUC::putFooterTextLine() ?>
	<a id="uc_version_link" href="javascript:void(0)" class="unite-version-link">
		<?php _e("Plugin verson", UNITECREATOR_TEXTDOMAIN)?> <?php echo UNITE_CREATOR_VERSION?>
	</a>
</div>

