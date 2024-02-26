<?php
/**
 * @package Addon Creator
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class UniteSettingsOutputUC{
		
		protected $arrSettings = array(); 
		protected $settings;
		protected $formID;
		
		protected static $serial = 0;
		
		protected $showDescAsTips = false;
		protected $showSaps = true;
		protected $wrapperID = "";
		protected $addCss = "";
		protected $settingsMainClass = "";
		protected $isParent = false;		//variable that this class is parent
		
		const INPUT_CLASS_NORMAL = "unite-input-regular";
		const INPUT_CLASS_NUMBER = "unite-input-number";
		const INPUT_CLASS_ALIAS = "unite-input-alias";
		const INPUT_CLASS_LONG = "unite-input-long";
		
		
		
		/**
		 * 
		 * init the output settings
		 */
		public function init(UniteSettingsUC $settings){
			
			if($this->isParent == false)
				UniteFunctionsUC::throwError("The output class must be parent of some other class.");
				
			$this->settings = new UniteSettingsUC();
			$this->settings = $settings;
		}
		
		
		/**
		 * validate that the output class is inited with settings
		 */
		protected function validateInited(){
			if(empty($this->settings))
				UniteFunctionsUC::throwError("The output class not inited. Please call init() function with some settings class");
		}
		
		
		/**
		 * set add css. work with placeholder
		 * [wrapperid]
		 */
		public function setAddCss($css){
		
			$replace = "#".$this->wrapperID;
			$this->addCss = str_replace("[wrapperid]", $replace, $css);
		}
		
		/**
		 *
		 * set show descriptions as tips true / false
		 */
		public function setShowDescAsTips($show){
			$this->showDescAsTips = $show;
		}
		
		
		/**
		 *
		 * show saps true / false
		 */
		public function setShowSaps($show){
			$this->showSaps = $show;
		}
		
		
		/**
		 * get default value add html
		 * @param $setting
		 */
		protected function getDefaultAddHtml($setting){
			
			$defaultValue = UniteFunctionsUC::getVal($setting, "default_value");
			$defaultValue = htmlspecialchars($defaultValue);
			
			$value = UniteFunctionsUC::getVal($setting, "value");
			$value = htmlspecialchars($value);
			
			$addHtml = " data-default=\"{$defaultValue}\" data-initval=\"{$value}\" ";
			
			return($addHtml);
		}
		
		
		/**
		 * prepare draw setting text
		 */
		protected function drawSettingRow_getText($setting){
		
			//modify text:
			$text = UniteFunctionsUC::getVal($setting, "text", "");
			
			// prevent line break (convert spaces to nbsp)
			$text = str_replace(" ","&nbsp;",$text);
		
			switch($setting["type"]){
				case UniteSettingsUC::TYPE_CHECKBOX:
					$text = "<label for='".$setting["id"]."' style='cursor:pointer;'>$text</label>";
					break;
			}
		
			return($text);
		}
		
		
		/**
		 *
		 * get text style
		 */
		protected function drawSettingRow_getTextStyle($setting){
		
			//set text style:
			$textStyle = UniteFunctionsUC::getVal($setting, UniteSettingsUC::PARAM_TEXTSTYLE);
		
			if($textStyle != "")
				$textStyle = "style='".$textStyle."'";
		
			return($textStyle);
		}
		
		/**
		 * get row style
		 */
		protected function drawSettingRow_getRowStyle($setting){
		
			//set hidden
			$rowStyle = "";
			if(isset($setting["hidden"]))
				$rowStyle = "display:none;";
		
			if(!empty($rowStyle))
				$rowStyle = "style='$rowStyle'";
		
			return($rowStyle);
		}
		
		
		/**
		 *
		 * get row class
		 */
		protected function drawSettingRow_getRowClass($setting, $basClass = ""){
			
			//set text class:
			$class = $basClass;
			
			if(isset($setting["disabled"])){
				if(!empty($class))
					$class .= " ";
				
				$class .= "setting-disabled";
			}
			
			if(!empty($class))
				$class = "class='{$class}'";
			
			return($class);
		}
		
		
		/**
		 * 
		 * draw includes of the settings.
		 */
		public function drawHeaderIncludes(){
			
			$arrSections = $this->settings->getArrSections();
			$arrControls = $this->settings->getArrControls();
			
			$formID = $this->formID;
			
			$arrOnReady = array();
			$arrJs = array();
			
			//$arrJs[] = "obj.jsonSettingTypes = '$jsonString'";
			//$arrJs[] = "obj.objSettingTypes = JSON.parse(obj.jsonSettingTypes);";
			
			//put sections vars
			/*
			if(!empty($arrSections)){
				$arrJs[] = "obj.sectionsEnabled = true;";
				$arrJs[] = "obj.numSections = ".count($arrSections).";";
			}
			else 
				$arrJs[] = "obj.sectionsEnabled = false;";
			*/			
			
			//put the settings into form id
			
			$arrJs[] = "if(!g_settingsObjUC) 
							var g_settingsObjUC = {};";
			
			$arrJs[] = "g_settingsObjUC['$formID'] = {}";
						
			//put controls json object:
			if(!empty($arrControls)){
				
				//dmp($arrControls); exit();
				
				$strControls = json_encode($arrControls);
				$arrJs[] = "g_settingsObjUC['$formID'].jsonControls = '".$strControls."'";
				$arrJs[] = "g_settingsObjUC['$formID'].controls = JSON.parse(g_settingsObjUC['$formID'].jsonControls);";
			}
						
			/*
			//put types onready function
			$arrTypes = $this->getArrTypes();			
			//put script includes:
			foreach($arrTypes as $type){
				switch($type){
					case UniteSettingsUC::TYPE_ORDERBOX:
						$arrOnReady[] = "$(function() { $( '.orderbox' ).sortable();}); ";
					break;
					case UniteSettingsUC::TYPE_ORDERBOX_ADVANCED:
						$arrOnReady[] = "init_advanced_orderbox();";
					break; 				
				}
			}
			*/		
			//put js vars and onready func.
			
			echo "<script type='text/javascript'>\n";
				
			//put js 
			foreach($arrJs as $line){
				echo $line."\n";
			}
				
			if(!empty($arrOnReady)):
				//put onready
				echo "$(document).ready(function(){\n";
				foreach($arrOnReady as $line){
					echo $line."\n";
				}				
				echo "});";
			endif;
			echo "\n</script>\n";
			
		}
		
		
		/**
		* draw after body additional settings accesories
		*/
		public function drawAfterBody(){
			$arrTypes = $this->settings->getArrTypes();
			foreach($arrTypes as $type){
				switch($type){
					case self::TYPE_COLOR:
						?>
							<div id='divPickerWrapper' style='position:absolute;display:none;'><div id='divColorPicker'></div></div>
						<?php
					break;
				}
			}
		}
				
		
		/**
		 * 
		 * do some operation before drawing the settings.
		 */
		protected function prepareToDraw(){
			
			$this->settings->setSettingsStateByControls();
		}


		/**
		 * get setting class attribute
		 */
		protected function getInputClassAttr($setting, $defaultClass="", $addClassParam=""){
			
			$class = UniteFunctionsUC::getVal($setting, "class", $defaultClass);
			$classAdd = UniteFunctionsUC::getVal($setting, UniteSettingsUC::PARAM_CLASSADD);
			
			
			if(!empty($classAdd)){
				if(!empty($class))
					$class .= " ";
				$class .= $classAdd;
			}
			
			if(!empty($addClassParam)){
				if(!empty($class))
					$class .= " ";
				$class .= $addClassParam;
			}
			
			
			if(!empty($class))
				$class = "class='$class'";
			
			return($class);
		}
		
		/**
		 * draw text input
		 * @param $setting
		 */
		protected function drawTextInput($setting) {
			
			$disabled = "";
			$style="";
			$readonly = "";
			
			if(isset($setting["style"])) 
				$style = "style='".$setting["style"]."'";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
				
			if(isset($setting["readonly"])){
				$readonly = "readonly='readonly'";
			}
			
			$defaultClass = self::INPUT_CLASS_NORMAL;
			
			$unit = UniteFunctionsUC::getVal($setting, "unit");
			if(!empty($unit))
				$defaultClass = self::INPUT_CLASS_NUMBER;
			
			$class = $this->getInputClassAttr($setting, $defaultClass);
			
			$addHtml = $this->getDefaultAddHtml($setting);
			
			?>
				<input type="text" <?php echo $class?> <?php echo $style?> <?php echo $disabled?><?php echo $readonly?> id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" value="<?php echo $setting["value"]?>" <?php echo $addHtml?> />
			<?php
		}
		
		
		/**
		 * modify image setting values
		 */
		protected function modifyImageSetting($setting){
			
			$value = UniteFunctionsUC::getVal($setting, "value");
			$value = trim($value);
			
			$urlBase = UniteFunctionsUC::getVal($setting, "url_base", null);
			
			if(!empty($value))
				$value = HelperUC::URLtoFull($value, $urlBase);
			
			$defaultValue = UniteFunctionsUC::getVal($setting, "default_value");
			$defaultValue = trim($defaultValue);
			
			if(!empty($defaultValue))
				$defaultValue = HelperUC::URLtoFull($defaultValue, $urlBase);
			
			$setting["value"] = $value;
			$setting["default_value"] = $defaultValue;
			
			
			return($setting);
		}
	
		
		/**
		 * 
		 * draw imaeg input:
		 * @param $setting
		 */
		protected function drawImageInput($setting){
			
			$previewStyle = "display:none";
			
			$setting = $this->modifyImageSetting($setting);
			
			$value = UniteFunctionsUC::getVal($setting, "value");
			
			if(!empty($value)){
				
				$previewStyle = "";
				
				$operations = new UCOperations();
				try{
				
					$urlThumb = $operations->createThumbs($value);
				
				}catch(Exception $e){
					$urlThumb = $value;
				}
				
				$urlThumbFull = HelperUC::URLtoFull($urlThumb);
				if(!empty($previewStyle))
					$previewStyle .= ";";
				
			
				$previewStyle .= "background-image:url('{$urlThumbFull}');";
			}
			
			if(!empty($previewStyle))
				$previewStyle = "style=\"{$previewStyle}\"";
			
			
			$class = $this->getInputClassAttr($setting, "", "unite-setting-image-input unite-input-image");
			
			$addHtml = $this->getDefaultAddHtml($setting);
			
			//add source param
			$source = UniteFunctionsUC::getVal($setting, "source");
			if(!empty($source))
				$addHtml .= " data-source='{$source}'";
			
			?>
				<div class="unite-setting-image"> 
					<input type="text" id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" <?php echo $class?> value="<?php echo $value?>" <?php echo $addHtml?> />
					<a href="javascript:void(0)" class="unite-button-secondary unite-button-choose"><?php _e("Choose", UNITECREATOR_TEXTDOMAIN)?></a>
					<div class='unite-setting-image-preview' <?php echo $previewStyle?>></div>
				</div>
			<?php
		}


		/**
		 * draw color picker
		 * @param $setting
		 */
		protected function drawColorPickerInput($setting){	
			$bgcolor = $setting["value"];
			$bgcolor = str_replace("0x","#",$bgcolor);			
			// set the forent color (by black and white value)
			$rgb = UniteFunctionsUC::html2rgb($bgcolor);
			$bw = UniteFunctionsUC::yiq($rgb[0],$rgb[1],$rgb[2]);
			$color = "#000000";
			if($bw<128) $color = "#ffffff";
			
			$disabled = "";
			if(isset($setting["disabled"])){
				$color = "";
				$disabled = 'disabled="disabled"';
			}
			
			$style="style='background-color:$bgcolor;color:$color'";
			
			$addHtml = $this->getDefaultAddHtml($setting);
			
			$class = $this->getInputClassAttr($setting, "", "unite-color-picker");
			
			?>
				<input type="text" <?php echo $class?> id="<?php echo $setting["id"]?>" <?php echo $style?> name="<?php echo $setting["name"]?>" value="<?php echo $bgcolor?>" <?php echo $disabled?> <?php echo $addHtml?>></input>
			<?php
		}
		
		
		/**
		 * draw editor input
		 */
		protected function drawEditorInput($setting){
			
			$disabled = "";
			if (isset($setting["disabled"]))
				$disabled = 'disabled="disabled"';
			
			$style = "";
			if(isset($setting["style"]))
				$style = "style='".$setting["style"]."'";
			
			$addHtml = $this->getDefaultAddHtml($setting);
			
			// Get an editor object.
			$editor = JFactory::getEditor();
			
			$name = $setting["name"];
			$value = UniteFunctionsUC::getVal($setting, "value");			
			$value = htmlspecialchars($value, ENT_COMPAT, 'UTF-8');
			$width = "100%";
			$height = "200";
			$cols = 5;
			$rows = 3;
			$buttons = false;
			$id = $setting["id"];
			
			$params = array();
			$params["html_height"] = $height;
			
			$html = $editor->display(
					$name, $value , $width, $height, $cols, $rows,
					$buttons, $id, null,
					null, $params);
			
			echo $html;
		}
		
		
		/**
		 * draw setting input by type
		 */
		protected function drawInputs($setting){
			
			switch($setting["type"]){
				case UniteSettingsUC::TYPE_TEXT:
					$this->drawTextInput($setting);
				break;
				case UniteSettingsUC::TYPE_COLOR:
					$this->drawColorPickerInput($setting);
				break;
				case UniteSettingsUC::TYPE_SELECT:
					$this->drawSelectInput($setting);
				break;
				case UniteSettingsUC::TYPE_CHECKBOX:
					$this->drawCheckboxInput($setting);
				break;
				case UniteSettingsUC::TYPE_RADIO:
					$this->drawRadioInput($setting);
				break;
				case UniteSettingsUC::TYPE_TEXTAREA:
					$this->drawTextAreaInput($setting);
				break;
				case UniteSettingsUC::TYPE_IMAGE:
					$this->drawImageInput($setting);
				break;
				case UniteSettingsUC::TYPE_EDITOR:
					$this->drawEditorInput($setting);
				break;
				case UniteSettingsUC::TYPE_CUSTOM:
					if(method_exists($this,"drawCustomInputs") == false){
						UniteFunctionsUC::throwError("Method don't exists: drawCustomInputs, please override the class");
					}
					$this->drawCustomInputs($setting);
				break;
				default:
					throw new Exception("wrong setting type - ".$setting["type"]);
				break;
			}
			
		}		
		
		
		
		/**
		 * draw text area input
		 */
		protected function drawTextAreaInput($setting){
			
			$disabled = "";
			if (isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
			
			$style = "";
			if(isset($setting["style"]))
				$style = "style='".$setting["style"]."'";

			$rows = UniteFunctionsUC::getVal($setting, "rows");
			if(!empty($rows))
				$rows = "rows='$rows'";
				
			$cols = UniteFunctionsUC::getVal($setting, "cols");
			if(!empty($cols))
				$cols = "cols='$cols'";
			
			$addHtml = $this->getDefaultAddHtml($setting);
			
			$class = $this->getInputClassAttr($setting);
			
			?>
				<textarea id="<?php echo $setting["id"]?>" <?php echo $class?> name="<?php echo $setting["name"]?>" <?php echo $style?> <?php echo $disabled?> <?php echo $rows?> <?php echo $cols?> <?php echo $addHtml?> ><?php echo $setting["value"]?></textarea>
			<?php
			if(!empty($cols))
				echo "<br>";	//break line on big textareas.
		}		
		
		
		/**
		 * draw radio input
		 */
		protected function drawRadioInput($setting){
			
			$items = $setting["items"];
			$counter = 0;
			$settingID = $setting["id"];
			$isDisabled = UniteFunctionsUC::getVal($setting, "disabled");
			$isDisabled = UniteFunctionsUC::strToBool($isDisabled);
			$settingName = $setting["name"];
			$defaultValue = UniteFunctionsUC::getVal($setting, "default_value");
			$settingValue = UniteFunctionsUC::getVal($setting, "value");
			
			$class = $this->getInputClassAttr($setting);
			
			?>
			<span id="<?php echo $settingID ?>" class="radio_wrapper">
			<?php 
			foreach($items as $value=>$text):
				$counter++;
				$radioID = $settingID."_".$counter;
				
				$strChecked = "";				
				if($value == $settingValue) 
					$strChecked = " checked";

				$strDisabled = "";
				if($isDisabled)
					$strDisabled = 'disabled = "disabled"';
				
				$addHtml = "";
				if($value == $defaultValue)
					$addHtml .= " data-defaultchecked=\"true\"";
				
				if($value == $settingValue){
					$addHtml .= " data-initchecked=\"true\"";
				}
				
				$props = "style=\"cursor:pointer;\" {$strChecked} {$strDisabled} {$addHtml} {$class}";
				
				?>					
					<input type="radio" id="<?php echo $radioID?>" value="<?php echo $value?>" name="<?php echo $settingName?>" <?php echo $props?>/>
					<label for="<?php echo $radioID?>" ><?php echo $text?></label>
					&nbsp; &nbsp;
				<?php				
			endforeach;
			
			?>
			</span>
			<?php 
		}
		
		
		/**
		 * draw checkbox
		 */
		protected function drawCheckboxInput($setting){
			$checked = "";
			
			$value = UniteFunctionsUC::getVal($setting, "value");
			$value = UniteFunctionsUC::strToBool($value);
			
			if($value == true) 
				$checked = 'checked="checked"';
			
			$textNear = UniteFunctionsUC::getVal($setting, "text_near");
			
			$settingID = $setting["id"];
			
			if(!empty($textNear))
				$textNear = "<label for=\"{$settingID}\">$textNear</label>";
			
			$defaultValue = UniteFunctionsUC::getVal($setting, "default_value");
			$defaultValue = UniteFunctionsUC::strToBool($defaultValue);
			
			$addHtml = "";
			if($defaultValue == true)
				$addHtml .= " data-defaultchecked=\"true\"";
			
			if($value)
				$addHtml .= " data-initchecked=\"true\"";
			
			$class = $this->getInputClassAttr($setting);
			
			?>
				<input type="checkbox" id="<?php echo $settingID?>" <?php echo $class?> name="<?php echo $setting["name"]?>" <?php echo $checked?> <?php echo $addHtml?>/>
			<?php
			
			if(!empty($textNear))
				echo $textNear;
		}		
		
		
		/**
		 * draw select input
		 */
		protected function drawSelectInput($setting){
			
			
			$disabled = "";
			if(isset($setting["disabled"])) 
				$disabled = 'disabled="disabled"';
			
			$args = UniteFunctionsUC::getVal($setting, "args");
			
			$settingValue = $setting["value"];
			
			if(strpos($settingValue,",") !== false)
				$settingValue = explode(",", $settingValue);
			
			$addHtml = $this->getDefaultAddHtml($setting);
			
			$class = $this->getInputClassAttr($setting);
			
			$arrItems = UniteFunctionsUC::getVal($setting, "items",array());
			if(empty($arrItems))
				$arrItems = array();
			
			?>
			<select id="<?php echo $setting["id"]?>" name="<?php echo $setting["name"]?>" <?php echo $disabled?> <?php echo $class?> <?php echo $args?> <?php echo $addHtml?>>
			<?php
			foreach($arrItems as $text=>$value):
				//set selected
				$selected = "";
				$addition = "";
				
				if(is_array($settingValue)){
					if(array_search($value, $settingValue) !== false) 
						$selected = 'selected="selected"';
				}else{
					if($value == $settingValue) 
						$selected = 'selected="selected"';
				}
				
				?>
					<option <?php echo $addition?> value="<?php echo $value?>" <?php echo $selected?>><?php echo $text?></option>
				<?php
			endforeach
			?>
			</select>
			<?php
		}

		
		
		/**
		 * draw text row
		 * @param unknown_type $setting
		 */
		protected function drawTextRow($setting){
			echo "draw text row - override this function";
		}

		
		/**
		 * draw hr row - override
		 */
		protected function drawHrRow($setting){
			echo "draw hr row - override this function";
		}
		
		
		/**
		 * draw input additinos like unit / description etc
		 */
		protected function drawInputAdditions($setting){
			
			$description = UniteFunctionsUC::getVal($setting, "description");
			$unit = UniteFunctionsUC::getVal($setting, "unit");
			$required = UniteFunctionsUC::getVal($setting, "required");
			$addHtml = UniteFunctionsUC::getVal($setting, UniteSettingsUC::PARAM_ADDTEXT);
			
			?>
			
			<?php if(!empty($unit)):?>
			<span class='setting_unit'><?php echo $unit?></span>
			<?php endif?>
			<?php if(!empty($required)):?>
			<span class='setting_required'>*</span>
			<?php endif?>
			<?php if(!empty($addHtml)):?>
			<span class="settings_addhtml"><?php echo $addHtml?></span>
			<?php endif?>					
			<?php if(!empty($description) && $this->showDescAsTips == false):?>
			<span class="description"><?php echo $description?></span>
			<?php endif?>
			
			<?php 
		}


		/**
		 * 
		 * draw settings function
		 * @param $drawForm draw the form yes / no
		 */
		public function draw($formID, $drawForm = false){
			
			UniteFunctionsUC::validateNotEmpty($formID, "formID");
			UniteFunctionsUC::validateNotEmpty($this->settingsMainClass, "settings main class");
			
			$this->formID = $formID;
			
			if(!empty($this->addCss)):
				?>
				<!-- settings add css -->
				<style type="text/css">
					<?php echo $this->addCss?>
				</style>
				<?php 
			endif;
			
			?>
				<div id="<?php echo $this->wrapperID?>" class="unite_settings_wrapper <?php echo $this->settingsMainClass?> unite-settings unite-inputs">
			<?php
			
			if($drawForm == true){
				
				if(empty($formID))
					UniteFunctionsUC::throwError("The form ID can't be empty. you must provide it");
				
				?>
				<form name="<?php echo $formID?>" id="<?php echo $formID?>">
					<?php $this->drawSettings() ?>
				</form>
				<?php 				
			}else
				$this->drawSettings();
			
			?>
			</div>
			<?php 
		}

		
		/**
		 * draw wrapper before settings
		 */
		protected function drawSettings_before(){
		}
		
		
		/**
		* draw wrapper end after settings
		*/
		protected function drawSettingsAfter(){
		}
		

		/**
		 * draw single setting
		 */
		public function drawSingleSetting($name){
			
			$arrSetting = $this->settings->getSettingByName($name);
			
			$this->drawInputs($arrSetting);
			$this->drawInputAdditions($arrSetting);
		}
		
		
		/**
		 * draw all settings
		 */
		public function drawSettings(){
		
			$this->drawHeaderIncludes();
			$this->prepareToDraw();
			$arrSettings = $this->settings->getArrSettings();
			$this->arrSettings = $arrSettings;
		
			$this->drawSettings_before();
		
			foreach($arrSettings as $key=>$setting){
		
				if(isset($setting[UniteSettingsUC::PARAM_NODRAW]))
					continue;
		
				switch($setting["type"]){
					case UniteSettingsUC::TYPE_HR:
						$this->drawHrRow($setting);
						break;
					case UniteSettingsUC::TYPE_STATIC_TEXT:
						$this->drawTextRow($setting);
						break;
					default:
						$this->drawSettingRow($setting);
					break;
				}
			}
		
			$this->drawSettingsAfter();
		}
		
		
	}

?>