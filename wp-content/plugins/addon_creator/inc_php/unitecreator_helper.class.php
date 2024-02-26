<?php
/**
 * @package Addon Creator
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


/**
 * 
 * creator helper functions class
 *
 */
	class HelperUC extends UniteHelperBaseUC{

		public static $operations;
		
		
		public static function ________________STATE______________(){}
		
		
		/**
		 * remember state
		 */
		public static function setState($name, $value){
			
			$optionName = "untecreator_state";
			
			$arrState = UniteProviderFunctionsUC::getOption($optionName);
			if(empty($arrState) || is_array($arrState) == false)
				$arrState = array();
			
			$arrState[$name] = $value;
			UniteProviderFunctionsUC::updateOption($optionName, $arrState);
		}
		
		
		/**
		 * get remembered state
		 */
		public static function getState($name){
			
			$optionName = "untecreator_state";
			
			$arrState = UniteProviderFunctionsUC::getOption($optionName);
			$value = UniteFunctionsUC::getVal($arrState, $name, null);
			
			return($value);
		}
		
		/**
		 * print general settings and exit all
		 */
		public static function printGeneralSettings(){
			$arrSettings = self::$operations->getGeneralSettings();
			dmp($arrSettings);
			exit();
		}
		
		/**
		 * get general setting value
		 */
		public static function getGeneralSetting($name){
			$arrSettings = self::$operations->getGeneralSettings();
			
			if(array_key_exists($name,$arrSettings) == false)
				UniteFunctionsUC::throwError("General setting: {$name} don't exists");
			
			$value = $arrSettings[$name];
			
			return($value);
		}
		
		
		public static function ________________URL_AND_PATH______________(){}
		
		/**
		 * convert url to full url
		 */
		public static function URLtoFull($url, $urlBase = null){
			
			if(getType($urlBase) == "boolean")
				UniteFunctionsUC::throwError("the url base should be null or string");
			
			if(is_array($url))
				UniteFunctionsUC::throwError("url can't be array");
			
			$url = trim($url);
			
			if(empty($url))
				return("");
				
			$urlLower = strtolower($url);
			
			if(strpos($urlLower, "http://") !== false || strpos($urlLower, "https://") !== false)
				return($url);
			
			
			if(empty($urlBase))
				$url = GlobalsUC::$url_base.$url;
			else{
				
				$convertUrl = GlobalsUC::$url_base;
				
				//preserve old format:
				$filepath = self::pathToAbsolute($url);
				if(file_exists($filepath) == false)
					$convertUrl = $urlBase;
				
				$url = $convertUrl.$url;
			}
			
			return($url);
		}
		
		/**
		 * convert some url to relative
		 */
		public static function URLtoRelative($url, $isAssets = false){
			
				
			$replaceString = GlobalsUC::$url_base;
			if($isAssets == true)
				$replaceString = GlobalsUC::$url_assets;
			
			//in case of array take "url" from the array
			if(is_array($url)){
				
				if(array_key_exists("url", $url) == false)
					UniteFunctionsUC::throwError("URLtoRelative error: url key not found in array");
				
				$strUrl = UniteFunctionsUC::getVal($url, "url");
				if(empty($strUrl))
					return($url);
				
				$url["url"] = str_replace($replaceString, "", $strUrl);
				
				return($url);
			}
			
			$url = str_replace($replaceString, "", $url);
		
			return($url);
		}
		
		
		/**
		 * change url to assets relative
		 */
		public static function URLtoAssetsRelative($url){
			$url = str_replace(GlobalsUC::$url_assets, "", $url);
			
			return($url);
		}

		
		/**
		 * convert url array to relative
		 */
		public static function arrUrlsToRelative($arrUrls, $isAssets = false){
			if(!is_array($arrUrls))
				return($arrUrls);
			
			foreach($arrUrls as $key=>$url){
				$arrUrls[$key] = self::URLtoRelative($url, $isAssets);
			}
			
			return($arrUrls);
		}
		
		
		/**
		 * convert url's array to full
		 */
		public static function arrUrlsToFull($arrUrls){
			if(!is_array($arrUrls))
				return($arrUrls);
			
			foreach($arrUrls as $key=>$url){
				$arrUrls[$key] = self::URLtoFull($url);
			}
			
			return($arrUrls);
		}

		
		/**
		 * strip base path part from the path
		 */
		public static function pathToRelative($path, $addDots = true){

			$realpath = realpath($path);
			if(!$realpath)
				return($path);
			
			$len = strlen($realpath);
			$realBase = realpath(GlobalsUC::$path_base);
			$relativePath = str_replace($realBase, "", $realpath);
			
			//add dots
			if($addDots == true && strlen($relativePath) != strlen($realpath))
				$relativePath = "..".$relativePath;				
			
			$relativePath = UniteFunctionsUC::pathToUnix($relativePath);
			
			if($addDots == false)
				$relativePath = ltrim($relativePath, "/");
			
			//add slash to end
			$relativePath = UniteFunctionsUC::addPathEndingSlash($relativePath);
			
			return $relativePath;
		}
		
		
		/**
		 * convert relative path to absolute path
		 */
		public static function pathToAbsolute($path){
			
			$basePath = GlobalsUC::$path_base;
			$basePath = UniteFunctionsUC::pathToUnix($basePath);
			
			$path = UniteFunctionsUC::pathToUnix($path);
						
			$realPath = UniteFunctionsUC::realpath($path, false);
			
			if(!empty($realPath))
				return($path);
			
			if(UniteFunctionsUC::isPathUnderBase($path, $basePath)){
				$path = UniteFunctionsUC::pathToUnix($path);
				return($path);
			}
			
			$path = $basePath."/".$path;
			$path = UniteFunctionsUC::pathToUnix($path);
			
			return($path);
		}
		
		
		/**
		 * turn path to relative url
		 */
		public static function pathToRelativeUrl($path){
			$path = self::pathToRelative($path, false);
			$url = str_replace('\\', '/', $path);
			$url = ltrim($url, '/');
			
			return($url);
		}
		
		
		
		/**
		 * convert path to absolute url
		 */
		public static function pathToFullUrl($path){
			if(empty($path))
				return("");
			
			$url = self::pathToRelativeUrl($path);
			$url = self::URLtoFull($url);
			return($url);
		}
		
		
		/**
		 * get details of the image by the image url.
		 */
		public static function getImageDetails($urlImage){
		
			$info = UniteFunctionsUC::getPathInfo($urlImage);
			$urlDir = UniteFunctionsUC::getVal($info, "dirname");
			if(!empty($urlDir))
				$urlDir = $urlDir."/";
		
			$arrInfo = array();
			$arrInfo["url_full"] = GlobalsUC::$url_base.$urlImage;
			$arrInfo["url_dir_image"] = $urlDir;
			$arrInfo["url_dir_thumbs"] = $urlDir.GlobalsUC::DIR_THUMBS."/";
		
			$filepath = GlobalsUC::$path_base.urldecode($urlImage);
			$filepath = realpath($filepath);
		
			$path = dirname($filepath)."/";
			$pathThumbs = $path.GlobalsUC::DIR_THUMBS."/";
		
			$arrInfo["filepath"] = $filepath;
			$arrInfo["path"] = $path;
			$arrInfo["path_thumbs"] = $pathThumbs;
		
			return($arrInfo);
		}
		
		
		/**
		 * get url handle
		 */
		public static function getUrlHandle($url, $addonName){
			
			$prefix = "uc_";
			$randomString = UniteFunctionsUC::getRandomString(5);
			
			$info = pathinfo($url);
			
			$handle = $prefix.$addonName."_".$info["filename"];
			
			$handle = strtolower($handle);
			
			$handle = str_replace(array("ä", "Ä"), "a", $handle);
			$handle = str_replace(array("å", "Å"), "a", $handle);
			$handle = str_replace(array("ö", "Ö"), "o", $handle);
			
			// Remove any character that is not alphanumeric, white-space, or a hyphen
			$handle = preg_replace("/[^a-z0-9\s\_]/i", " ", $handle);
			// Replace multiple instances of white-space with a single space
			$handle = preg_replace("/\s\s+/", " ", $handle);
			// Replace all spaces with underscores
			$handle = preg_replace("/\s/", "_", $handle);
			// Replace multiple underscore with a single underscore
			$handle = preg_replace("/\_\_+/", "_", $handle);
			// Remove leading and trailing underscores
			$handle = trim($handle, "_");
			
			return($handle);
		}
		
		
		public static function ________________VIEW_TEMPLATE______________(){}
		
		/**
		 *
		 * get url to some view.
		 */
		public static function getViewUrl($viewName,$urlParams=""){
			
			$params = "&view=".$viewName;
			
			if(!empty($urlParams))
				$params .= "&".$urlParams;
			
			$link = GlobalsUC::$url_component_admin.$params;
			
			return($link);
		}
		
		/**
		 * get addons view url
		 */
		public static function getViewUrl_Addons(){
			
			return(self::getViewUrl(GlobalsUC::VIEW_ADDONS_LIST));
		}
		
		
		/**
		 * get addons view url
		 */
		public static function getViewUrl_EditAddon($addonID){
		
			return(self::getViewUrl(GlobalsUC::VIEW_EDIT_ADDON, "id={$addonID}"));
		}

		
		/**
		 * get addons view url
		 */
		public static function getViewUrl_TestAddon($addonID, $optParams=""){
			$params = "id={$addonID}";
			if(!empty($optParams))
				$params .= "&".$optParams;
			
			return(self::getViewUrl(GlobalsUC::VIEW_TEST_ADDON, $params));
		}
		
		/**
		 * get filename title from some url
		 * used to get item title from image url
		 */
		public static function getTitleFromUrl($url, $defaultTitle = "item"){
		
			$info = pathinfo($url);
			$filename = UniteFunctionsUC::getVal($info, "filename");
			$filename = urldecode($filename);
		
			$title = $defaultTitle;
			if(!empty($filename))
				$title = $filename;
		
		
			return($title);
		}
		
		
		/**
		 * get file path
		 * @param  $filena
		 */
		private static function getPathFile($filename, $path, $defaultPath, $validateName){
			
			if(empty($path))
				$path = $defaultPath;
			
			$filepath = $path.$filename.".php";
			UniteFunctionsUC::validateFilepath($filepath, $validateName);
			
			return($filepath);
		}
		
		
		/**
		 * require some template from "templates" folder
		 */
		public static function getPathTemplate($templateName, $path = null){
		
			return self::getPathFile($templateName,$path,GlobalsUC::$pathTemplates,"Template");
		}
		
		
		/**
		 * get settings path
		 */
		public static function getPathSettings($settingsName, $path=null){
			
			return self::getPathFile($settingsName,$path,GlobalsUC::$pathSettings,"Settings");
		}
		
		/**
		 * get path provider template
		 */
		public static function getPathTemplateProvider($templateName){
			
			return self::getPathFile($templateName,GlobalsUC::$pathProviderTemplates,"","Provider Template");			
		}
		
		
		/**
		 * get path provider view
		 */
		public static function getPathViewProvider($viewName){

			return self::getPathFile($templateName,GlobalsUC::$pathProviderViews,"","Provider View");
						
		}
		
		
		
		
		public static function ________________SCRIPTS______________(){}
		
		
		/**
		 *
		 * register script helper function
		 * @param $scriptFilename
		 */
		public static function addScript($scriptName, $handle=null, $folder="js"){
			if($handle == null)
				$handle = GlobalsUC::PLUGIN_NAME."-".$scriptName;
			
			UniteProviderFunctionsUC::addScript($handle, GlobalsUC::$urlPlugin .$folder."/".$scriptName.".js");
		}
		
		

		/**
		 *
		 * register script helper function
		 * @param $scriptFilename
		 */
		public static function addScriptAbsoluteUrl($urlScript, $handle){
		
			UniteProviderFunctionsUC::addScript($handle, $urlScript);
			
		}
		
		
		/**
		 *
		 * register style helper function
		 * @param $styleFilename
		 */
		public static function addStyle($styleName,$handle=null,$folder="css"){
			if($handle == null)
				$handle = GlobalsUC::PLUGIN_NAME."-".$styleName;
			
			UniteProviderFunctionsUC::addStyle($handle, GlobalsUC::$urlPlugin .$folder."/".$styleName.".css");
			
		}
		
		
		/**
		 *
		 * register style absolute url helper function
		 */
		public static function addStyleAbsoluteUrl($styleUrl, $handle){
			
			UniteProviderFunctionsUC::addStyle($handle, $styleUrl);
			
		}
		
		
		/**
		 * output system message
		 */
		public static function outputNote($message){
			$message = "system note: <b>&nbsp;&nbsp;&nbsp;&nbsp;".$message."</b>";
			
			$html = "<div style='background-color:#FCE8DE;border:1px solid #DD480D; padding:20px;margin:20px;'>{$message}</div>";
			echo $html;			
		}
		
		
		
		/**
		 * output addon from storred data
		 */
		public static function outputAddonFromData($data){
			
			$addons = new UniteCreatorAddons();
			$objAddon = $addons->initAddonByData($data);
			
			$objOutput = new UniteCreatorOutput();
			$objOutput->initByAddon($objAddon);
			$objOutput->processIncludes();
			$html = $objOutput->getHtmlBody();
			echo $html;
		}
		
		
		/**
		 * get error message html
		 */
		public static function getHtmlErrorMessage($message, $trace="", $prefix="Addon Creator Error: "){
			
			$message = $prefix.$message;
			
			$html = self::$operations->getErrorMessageHtml($message, $trace);
			return($html);
		}
		
		public static function ________________ASSETS_PATH______________(){}
		
		
		/**
		 * return true if some path under base path
		 */
		public static function isPathUnderAssetsPath($path){
			
			$path = self::pathToAbsolute($path);
			
			$assetsPath = GlobalsUC::$pathAssets;
			$assetsPath = self::pathToAbsolute($assetsPath);
						
			$isUnderAssets = UniteFunctionsUC::isPathUnderBase($path, $assetsPath);
			
			return($isUnderAssets);
		}
		
		
		/**
		 * check if some path is assets path
		 */
		public static function isPathAssets($path){

			$assetsPath = GlobalsUC::$pathAssets;
			$assetsPath = self::pathToAbsolute($assetsPath);
			
			$path = self::pathToAbsolute($path);
			
			if(!empty($path) && $path === $assetsPath)
				return(true);
			
			return(false);
		}
		
		
		/**
		 * convert path to assets relative path
		 */
		public static function pathToAssetsRelative($path){
			
			$assetsPath = GlobalsUC::$pathAssets;
			$assetsPath = self::pathToAbsolute($assetsPath);
			
			$path = self::pathToAbsolute($path);
			
			$relativePath = UniteFunctionsUC::pathToRelative($path, $assetsPath);
			
			return($relativePath);
		}
		
		
		/**
		 * path to assets absolute
		 * @param $path
		 */
		public static function pathToAssetsAbsolute($path){
			
			if(self::isPathUnderAssetsPath($path) == true)
				return($path);
			
			$assetsPath = GlobalsUC::$pathAssets;
			$path = UniteFunctionsUC::joinPaths($assetsPath, $path);
			
			return($path);
		}
		
	}
	
	
	//init the operations
	HelperUC::$operations = new UCOperations();
	
	
?>
