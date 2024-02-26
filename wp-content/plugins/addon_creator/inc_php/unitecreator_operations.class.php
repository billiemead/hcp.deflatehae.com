<?php
/**
 * @package Addon Creator
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class UCOperations extends UniteElementsBaseUC{
		
		private static $arrGeneralSettings = null;
		const GENERAL_SETTINGS_OPTION = "unitecreator_general_settings";
		
		
		/**
		 * update general settings 
		 */
		public function updateGeneralSettingsFromData($data){
			
			$arrValues = UniteFunctionsUC::getVal($data, "setting_values");
			
			//validations: 
			
			$vcFolder = UniteFunctionsUC::getVal($arrValues, "vc_folder");
			UniteFunctionsUC::validateNotEmpty($vcFolder);
			
			
			UniteProviderFunctionsUC::updateOption(self::GENERAL_SETTINGS_OPTION, $arrValues);
		}
		
		
		/**
		 * get general settings
		 */
		public function getGeneralSettings(){
			
			if(self::$arrGeneralSettings === null){
				$objSettings = $this->getGeneralSettingsObject();
				self::$arrGeneralSettings = $objSettings->getArrValues();
			}
			
			return(self::$arrGeneralSettings);
		}
		
		
		/**
		 * get general settings object
		 */
		public function getGeneralSettingsObject(){
			
			$filepathSettings = GlobalsUC::$pathSettings."general_settings.xml";
			
			$objSettings = new UniteCreatorSettings();
			$objSettings->loadXMLFile($filepathSettings);
		
			$arrValues = UniteProviderFunctionsUC::getOption(self::GENERAL_SETTINGS_OPTION);
			
			if(!empty($arrValues))
				$objSettings->setStoredValues($arrValues);
			
			return($objSettings);
		}
		
		
		/**
		 * get error message html
		 */
		public function getErrorMessageHtml($message, $trace=""){
			
			$html = '<div style="width:100%;min-width:400px;height:300px;margin-bottom:10px;border:1px solid black;margin:0px auto;overflow:auto;">';
			$html .= '<div style="padding-left:20px;padding-right:20px;line-height:1.5;padding-top:40px;color:red;font-size:16px;text-align:left;">';
			$html .= $message;
			$html .= '</div>';
			
			if(!empty($trace)){
				
				$html .= '<div style="text-align:left;padding-left:20px;padding-top:20px;">';
				$html .= "<pre>{$trace}</pre>";
				$html .= "</div>";
			}
				
			$html.= '</div>';
			
			return($html);
		}
		
		
		
		/**
		 * put error mesage from the module
		 */
		public function putModuleErrorMessage($message, $trace = ""){
			
			?>
			<div style="width:100%;min-width:400px;height:300px;margin-bottom:10px;border:1px solid black;margin:0px auto;overflow:auto;">
				<div style="padding-left:20px;padding-right:20px;line-height:1.5;padding-top:40px;color:red;font-size:16px;text-align:left;">
					<?php echo $message?>
				</div>
				
				<?php if(!empty($trace)):?>
				
				<div style="text-align:left;padding-left:20px;padding-top:20px;">
					<pre><?php echo $trace?></pre>
				</div>
				
				<?php endif?>
			
			</div>	
			<?php
		}
		
		
		/**
		 * create thumbs from image by url
		 * the image must be relative path to the platform base
		 */
		public function createThumbs($urlImage, $thumbWidth = null){
			
			if($thumbWidth === null)
				$thumbWidth = GlobalsUC::THUMB_WIDTH;
			
			$urlImage = HelperUC::URLtoRelative($urlImage);
			
			$info = HelperUC::getImageDetails($urlImage);
			
			//check thumbs path
			$pathThumbs = $info["path_thumbs"];
			
			if(!is_dir($pathThumbs))
				@mkdir($pathThumbs);
			
			if(!is_dir($pathThumbs))
				UniteFunctionsUC::throwError("Can't make thumb folder: {$pathThumbs}. Please check php and folder permissions");
			
			$filepathImage = $info["filepath"];
			
			$filenameThumb = $this->imageView->makeThumb($filepathImage, $pathThumbs, $thumbWidth);
			
			$urlThumb = "";
			if(!empty($filenameThumb)){
				$urlThumbs = $info["url_dir_thumbs"];
				$urlThumb = $urlThumbs.$filenameThumb;
			}
			
			return($urlThumb);
		}
		
		
		/**
		 * return thumb url from image url, return full url of the thumb
		 * if some error occured, return empty string
		 */
		public function getThumbURLFromImageUrl($urlImage, $imageID){
			
			try{
				$imageID = trim($imageID);
				if(!empty($imageID)){
					$urlThumb = UniteProviderFunctionsUC::getThumbUrlFromImageID($imageID);
				}else{
					$urlThumb = $this->createThumbs($urlImage);	
				}
				
				$urlThumb = HelperUC::URLtoFull($urlThumb);
				return($urlThumb);
				
			}catch(Exception $e){
				
				return("");
			}
			
			return("");			
		}
		
		
		/**
		 * get title param array
		 */
		private function getParamTitle(){
			
			$arr = array();
			
			$arr["type"] = "uc_textfield";
			$arr["title"] = "Title";
			$arr["name"] = "title";
			$arr["description"] = "";
			$arr["default_value"] = "";
			$arr["limited_edit"] = true;
			
			return($arr);
		}
		
		
		/**
		 * check that params always have item param on top
		 */
		public function checkAddParamTitle($params){
			
			if(empty($params)){
				$paramTitle = $this->getParamTitle();
				$params[] = $paramTitle;
				return($params);
			}
			
			//search for param title
			foreach($params as $param){
				$name = UniteFunctionsUC::getVal($param, "name");
				if($name == "title")
					return($params);
			}
			
			//if no title param - add it to top
			$paramTitle = $this->getParamTitle();
			array_unshift($params, $paramTitle);
			
			return($params);
		}
		
	}

?>