<?php

defined('_JEXEC') or die('Restricted access');

define("UNITECREATOR_TEXTDOMAIN","unitecreator");

class UniteProviderFunctionsUC{

	private static $arrScripts = array();
	private static $arrInlineHtml = array();
	
	
	/**
	 * init base variables of the globals
	 */
	public static function initGlobalsBase(){
		global $wpdb;
		
		$tablePrefix = $wpdb->base_prefix;
		
		GlobalsUC::$table_addons = $tablePrefix.GlobalsUC::TABLE_ADDONS_NAME;
		GlobalsUC::$table_categories = $tablePrefix.GlobalsUC::TABLE_CATEGORIES_NAME;
		
		$pluginName = "addon_creator";
		$pluginUrlAdminBase = "unitecreator";
					
		GlobalsUC::$pathPlugin = realpath(dirname(__FILE__)."/../")."/";
		
		GlobalsUC::$path_base = ABSPATH;

		$arrUploadDir = wp_upload_dir();
		
		$uploadPath = $arrUploadDir["basedir"]."/";
		
		GlobalsUC::$path_images = $arrUploadDir["basedir"]."/";
		
		GlobalsUC::$path_cache = GlobalsUC::$pathPlugin."cache/";
		
		GlobalsUC::$url_base = site_url()."/";
		GlobalsUC::$urlPlugin = plugins_url($pluginName)."/";
		
		GlobalsUC::$url_component_client = "";
		GlobalsUC::$url_component_admin = admin_url()."admin.php?page=$pluginUrlAdminBase";
			
		GlobalsUC::$url_images = $arrUploadDir["baseurl"]."/";
				
		GlobalsUC::$url_ajax = admin_url()."admin-ajax.php";
		GlobalsUC::$url_ajax_front = GlobalsUC::$url_ajax;
		
		GlobalsUC::$is_admin = self::isAdmin();
		
		GlobalsUC::$url_provider = GlobalsUC::$urlPlugin."provider/";
		
		GlobalsUC::$url_default_addon_icon = GlobalsUC::$url_provider."assets/images/icon_default_addon.png";
		
		self::setAssetsPath();
		
		GlobalsUC::$url_assets_libraries = GlobalsUC::$urlPlugin."assets_libraries/";
		
	}
	
	
	/**
	 * set assets path
	 */
	private static function setAssetsPath(){
		
		//try to get saved path:
		$pathAssets = get_option("unitecreator_assets_path");
		$urlAssets = get_option("unitecreator_assets_url");
		
		//get from cache
		if(!empty($pathAssets) && is_dir($pathAssets) && !empty($urlAssets)){
			
			GlobalsUC::$pathAssets = $pathAssets;
			GlobalsUC::$url_assets = $urlAssets;
			return(false);
		}
		
		//set assets path
		
		$pathBase = WP_CONTENT_DIR.'/';		
		
		$pathRelative = "uploads/";
		
		$urlBase = WP_CONTENT_URL;
		
		$pathUploads = $pathBase."uploads/";
		
		if(is_dir($pathUploads))
			$pathBase = $pathUploads;
		
		$dirAssets = "ac_assets";
		
		$pathAssets = $pathBase.$dirAssets."/";
		if(is_dir($pathAssets) == false)
			@mkdir($pathAssets);
		
		if(is_dir($pathAssets) == false)
			UniteFunctionsUC::throwError("Can't create folder: {$pathAssets}");
		
		$pathAssetsRelative = str_replace(WP_CONTENT_DIR,"",$pathAssets);
		$urlAssets = WP_CONTENT_URL.$pathAssetsRelative;
		
		update_option("unitecreator_assets_path", $pathAssets);
		update_option("unitecreator_assets_url", $urlAssets);
		
		GlobalsUC::$pathAssets = $pathAssets;
		GlobalsUC::$url_assets = $urlAssets;
		
		
	}
	
	
	/**
	 * is admin function
	 */
	public static function isAdmin(){
		
		$isAdmin = is_admin();
		
		return($isAdmin);
	}
	
	public static function _____________SCRIPTS___________(){}
	
	/**
	 * add scripts and styles framework
	 * $specialSettings - (nojqueryui)
	 */
	public static function addScriptsFramework($specialSettings = ""){
		
		UniteFunctionsWPUC::addMediaUploadIncludes();
		
		//add jquery
		self::addAdminJQueryInclude();
		
		//add jquery ui
		HelperUC::addScript("jquery-ui.min","jquery-ui");
		
		//no jquery ui style
		if($specialSettings != "nojqueryui"){
			HelperUC::addStyle("jquery-ui.structure.min","jui-smoothness-structure","css/jui/new");
			HelperUC::addStyle("jquery-ui.theme.min","jui-smoothness-theme","css/jui/new");
		}
				
		//add fancybox
		HelperUC::addScript("jquery.fancybox.pack","fancybox","js/fancybox");
		HelperUC::addStyle("jquery.fancybox","fancybox-css","js/fancybox");
		
		if(function_exists("wp_enqueue_media"))
			wp_enqueue_media();
		
	}
	
	
	/**
	 * add jquery include
	 */
	public static function addAdminJQueryInclude(){
		
		wp_enqueue_script("jquery");
		
	}
	
	
	
	/**
	 *
	 * register script
	 */
	public static function addScript($handle, $url){
	
		if(empty($url))
			UniteFunctionsUC::throwError("empty script url, handle: $handle");
	
		wp_register_script($handle , $url);
		wp_enqueue_script($handle);
	}
	
	
	/**
	 *
	 * register script
	 */
	public static function addStyle($handle, $url){
	
		if(empty($url))
			UniteFunctionsUC::throwError("empty style url, handle: $handle");
	
		wp_register_style($handle , $url);
		wp_enqueue_style($handle);
			
	}
	
	
	/**
	 * print some script at some place in the page
	 */
	public static function printCustomScript($script, $hardCoded = false){
		
		if($hardCoded == false)
			self::$arrScripts[] = $script;
		else
			echo "<script type='text/javascript'>{$script}</script>";
	
	}
	
	/**
	* get all custom scrips
	*/
	public static function getCustomScripts(){
		
		return(self::$arrScripts);
	}
	
	
	public static function _____________GENERAL___________(){}
	
	
	/**
	 * get image url from image id
	 */
	public static function getImageUrlFromImageID($imageID){
		
		$urlImage = UniteFunctionsWPUC::getUrlAttachmentImage($imageID);
				
		return($urlImage);
	}
	
	/**
	 * get image url from image id
	 */
	public static function getThumbUrlFromImageID($imageID, $size = null){
		if($size == null)
			$size = UniteFunctionsWPUC::THUMB_MEDIUM;
		
		$urlThumb = UniteFunctionsWPUC::getUrlAttachmentImage($imageID, $size);
		
		
		return($urlThumb);
	}
	
	
	
	/**
	 * strip slashes from ajax input data
	 */
	public static function normalizeAjaxInputData($arrData){
		
		if(!is_array($arrData))
			return($arrData);
		
		foreach($arrData as $key=>$item){
			
			if(is_string($item))
				$arrData[$key] = stripslashes($item);
			
			//second level
			if(is_array($item)){
				
				foreach($item as $subkey=>$subitem){
					if(is_string($subitem))
						$arrData[$key][$subkey] = stripslashes($subitem);
					
					//third level
					if(is_array($subitem)){

						foreach($subitem as $thirdkey=>$thirdItem){
							if(is_string($thirdItem))
								$arrData[$key][$subkey][$thirdkey] = stripslashes($thirdItem);
						}
					
					}
					
				}
			}
			
		}
		
		return($arrData);
	}
	
	
	/**
	 * put footer text line
	 */
	public static function putFooterTextLine(){
		?>
			&copy; <?php _e("All rights reserved",UNITECREATOR_TEXTDOMAIN)?>, <a href="http://codecanyon.net/user/unitecms" target="_blank">Unite CMS</a>. &nbsp;&nbsp;		
		<?php
	}
	
	
	/**
	 * add jquery include
	 */
	public static function addjQueryInclude($app="", $urljQuery = null){
		wp_enqueue_script("jquery");
	}

		
	
	/**
	 * print some custom html to the page
	 */
	public static function printInlineHtml($html){
		self::$arrInlineHtml[] = $html;
	}
	
	
	
	/**
	 * get custom html
	 */
	public static function getInlineHtml(){
		
		return(self::$arrInlineHtml);
	}
	
	/**
	 * add system contsant data to template engine
	 */
	public static function addSystemConstantData($data){
	
		/*
		 $postID = get_the_ID();
	
		//set post data
		$post = UniteFunctionsWPUC::getPost($postID, true, true);
		$data["post"] = $this->modifyPostData($post);
		*/
	
		return($data);
	}
	
	
	
	/**
	 * integrate visual composer
	 */
	public static function integrateVisualComposer(){
	
		try{
	
			//map addons
			$VCIntegrate = new UniteVcIntegrateUC();
			$VCIntegrate->initVCIntegration();
	
		}catch(Exception $e){
	
			HelperHtmlUC::outputException($e);
		}
	}

	
	/**
	 * get option
	 */
	public static function getOption($option, $default = false, $supportMultisite = false){
	
		if($supportMultisite == true && is_multisite())
			return(get_site_option($option, $default));
		else
			return get_option($option, $default);
	
	}
	
	
	/**
	 * update option
	 */
	public static function updateOption($option, $value, $supportMultisite = false){
	
		if($supportMultisite == true && is_multisite()){
			update_site_option($option, $value);
		}else
			update_option($option, $value);
	
	}
	
	
	/**
	 * put update plugin button
	 */
	public static function putUpdatePluginHtml(){
		?>
		<!-- update plugin button -->
		
		<div class="uc-update-plugin-wrapper">
			<a id="uc_button_update_plugin" class="unite-button-primary" href="javascript:void(0)" ><?php _e("Update Plugin", UNITECREATOR_TEXTDOMAIN)?></a>
		</div>
		
		<!-- dialog update -->
		
		<div id="dialog_update_plugin" title="<?php _e("Update Addon Creator Plugin",UNITECREATOR_TEXTDOMAIN)?>" style="display:none;">	
		
			<div class="unite-dialog-title"><?php _e("Update Addon Creator Plugin",UNITECREATOR_TEXTDOMAIN)?>:</div>	
			<div class="unite-dialog-desc">
				<?php _e("To update the plugin please select the plugin install package.",UNITECREATOR_TEXTDOMAIN) ?>		
			
			<br>
		
			<?php _e("The files will be overwriten", UNITECREATOR_TEXTDOMAIN)?>
		
			<br> <?php _e("File example: addon_creator1.2.zip",UNITECREATOR_TEXTDOMAIN)?>	</div>	
			
			<br>	
		
			<form action="<?php echo GlobalsUC::$url_ajax?>" enctype="multipart/form-data" method="post">
			
				<input type="hidden" name="action" value="unitecreator_ajax_action">		
				<input type="hidden" name="client_action" value="update_plugin">		
				<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("unitecreator_actions"); ?>">
				<?php _e("Choose the update file:",UNITECREATOR_TEXTDOMAIN)?>
				<br><br>
				
				<input type="file" name="update_file" class="unite-dialog-fileinput">		
				
				<br><br>
			
				<input type="submit" class='unite-button-primary' value="<?php _e("Update Plugin",UNITECREATOR_TEXTDOMAIN)?>">	
			</form>
		
		</div>

		<?php 
	}
	
	
	/**
	 * check that inner zip exists, and unpack it if do
	 	*/
	private static function updatePlugin_checkUnpackInnerZip($pathUpdate, $zipFilename){
	
		$arrFiles = UniteFunctionsUC::getFileList($pathUpdate);
	
		if(empty($arrFiles))
			return(false);
	
		//get inner file
		$filenameInner = null;
		foreach($arrFiles as $innerFile){
			if($innerFile != $zipFilename)
				$filenameInner = $innerFile;
		}
	
		if(empty($filenameInner))
			return(false);
	
		//check if internal file is zip
		$info = pathinfo($filenameInner);
		$ext = UniteFunctionsUC::getVal($info, "extension");
		if($ext != "zip")
			return(false);
	
		$filepathInner = $pathUpdate.$filenameInner;
	
		if(file_exists($filepathInner) == false)
			return(false);
	
		dmp("detected inner zip file. unpacking...");
	
		//check if zip exists
		$zip = new UniteZipUG();
	
		if(function_exists("unzip_file") == true){
			WP_Filesystem();
			$response = unzip_file($filepathInner, $pathUpdate);
		}
		else
			$zip->extract($filepathInner, $pathUpdate);
	
	}
	
	
	/**
	 *
	 * Update Plugin
	 */
	public static function updatePlugin(){
	
		try{
						
			//verify nonce:
			$nonce = UniteFunctionsUC::getPostVariable("nonce");
			$isVerified = wp_verify_nonce($nonce, "unitecreator_actions");
						
			if($isVerified == false)
				UniteFunctionsUC::throwError("Security error");
			
	
			$linkBack = HelperUC::getViewUrl_Addons();
			$htmlLinkBack = HelperHtmlUC::getHtmlLink($linkBack, "Go Back");
			
			//check if zip exists
			$zip = new UniteZipUC();
			
			if(function_exists("unzip_file") == false){
	
				if( UniteZipUG::isZipExists() == false)
					UniteFunctionsUC::throwError("The ZipArchive php extension not exists, can't extract the update file. Please turn it on in php ini.");
			}
						
			dmp("Update in progress...");
			
			$arrFiles = UniteFunctionsUC::getVal($_FILES, "update_file");
			
			if(empty($arrFiles))
				UniteFunctionsUC::throwError("Update file don't found.");
	
			$filename = UniteFunctionsUC::getVal($arrFiles, "name");
	
			if(empty($filename))
				UniteFunctionsIG::throwError("Update filename not found.");
	
			$fileType = UniteFunctionsUC::getVal($arrFiles, "type");
	
			$fileType = strtolower($fileType);
	
			$arrMimeTypes = array();
			$arrMimeTypes[] = "application/zip";
			$arrMimeTypes[] = "application/x-zip";
			$arrMimeTypes[] = "application/x-zip-compressed";
			$arrMimeTypes[] = "application/octet-stream";
			$arrMimeTypes[] = "application/x-compress";
			$arrMimeTypes[] = "application/x-compressed";
			$arrMimeTypes[] = "multipart/x-zip";
	
			if(in_array($fileType, $arrMimeTypes) == false)
				UniteFunctionsUC::throwError("The file uploaded is not zip.");
	
			$filepathTemp = UniteFunctionsUC::getVal($arrFiles, "tmp_name");
			if(file_exists($filepathTemp) == false)
				UniteFunctionsUC::throwError("Can't find the uploaded file.");
			
			
			//crate temp folder
			$pathTemp = GlobalsUC::$pathPlugin."temp/";
			UniteFunctionsUC::checkCreateDir($pathTemp);
						
			//create the update folder
			$pathUpdate = $pathTemp."update_extract/";
			UniteFunctionsUC::checkCreateDir($pathUpdate);
						
			if(!is_dir($pathUpdate))
				UniteFunctionsUC::throwError("Could not create temp extract path");
						
			//remove all files in the update folder
			$arrNotDeleted = UniteFunctionsUC::deleteDir($pathUpdate, false);
	
			if(!empty($arrNotDeleted)){
				$strNotDeleted = print_r($arrNotDeleted,true);
				UniteFunctionsUC::throwError("Could not delete those files from the update folder: $strNotDeleted");
			}
						
			//copy the zip file.
			$filepathZip = $pathUpdate.$filename;
	
			$success = move_uploaded_file($filepathTemp, $filepathZip);
			if($success == false)
				UniteFunctionsUC::throwError("Can't move the uploaded file here: ".$filepathZip.".");
						
			//extract files:
			if(function_exists("unzip_file") == true){
				WP_Filesystem();
				$response = unzip_file($filepathZip, $pathUpdate);
			}
			else
				$zip->extract($filepathZip, $pathUpdate);
				
			//check for internal zip in case that cocecanyon original zip was uploaded
			self::updatePlugin_checkUnpackInnerZip($pathUpdate, $filename);
						
			//get extracted folder
			$arrFolders = UniteFunctionsUC::getDirList($pathUpdate);
			if(empty($arrFolders))
				UniteFunctionsUC::throwError("The update folder is not extracted");
	
			//get product folder
			$productFolder = null;
	
			if(count($arrFolders) == 1)
				$productFolder = $arrFolders[0];
			else{
				foreach($arrFolders as $folder){
					if($folder != "documentation")
						$productFolder = $folder;
				}
			}
				
			if(empty($productFolder))
				UniteFunctionsUC::throwError("Wrong product folder.");
	
			$pathUpdateProduct = $pathUpdate.$productFolder."/";
			
			//check some file in folder to validate it's the real one:
			$checkFilepath = $pathUpdateProduct."addon_creator.php";
			
			
			if(file_exists($checkFilepath) == false)
				UniteFunctionsUC::throwError("Wrong update extracted folder. The file: ".$checkFilepath." not found.");
	
			//copy the plugin without the captions file.
			$pathOriginalPlugin = GlobalsUC::$pathPlugin;
	
			$arrBlackList = array();
			UniteFunctionsUC::copyDir($pathUpdateProduct, $pathOriginalPlugin,"",$arrBlackList);
	
			//delete the update
			UniteFunctionsUC::deleteDir($pathUpdate);
	
			dmp("Updated Successfully, redirecting...");
			echo "<script>location.href='$linkBack'</script>";
	
	}catch(Exception $e){
	
		//remove all files in the update folder
		if(isset($pathUpdate) && !empty($pathUpdate))
			UniteFunctionsUC::deleteDir($pathUpdate);
		
		$message = $e->getMessage();
		$message .= " <br> Please update the plugin manually via the ftp";
		echo "<div style='color:#B80A0A;font-size:18px;'><b>Update Error: </b> $message</div><br>";
		echo $htmlLinkBack;
		exit();
	}
	
	}
	
	
	
}
?>