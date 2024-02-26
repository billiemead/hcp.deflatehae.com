<?php

class UniteCreatorSettings extends UniteSettingsAdvancedUC{
	
	private $currentAddon;
	
	
	/**
	 * set current addon
	 */
	public function setCurrentAddon(UniteCreatorAddon $addon){
		
		$this->currentAddon = $addon;
		
	}
	
	
	/**
	 * add base url for image settings if needed
	 */
	public function addImage($name,$defaultValue = "",$text = "",$arrParams = array()){
		
		$source = UniteFunctionsUC::getVal($arrParams, "source");
		
		if($source == "addon"){
			
			if(empty($this->currentAddon))
				UniteFunctionsUC::throwError("You must set current addon before init settings for addon related image select option");
			
			$urlAssets = $this->currentAddon->getUrlAssets();
			
			$arrParams["url_base"] = $urlAssets;
		}
		
		parent::addImage($name, $defaultValue, $text, $arrParams);
		
	}
	
	
	/**
	 * get settings types array
	 */
	public function getArrUCSettingTypes(){
		
		$arrTypes = array(
			"uc_textfield",
			"uc_number",
			"uc_textarea",
			"uc_radioboolean",
			"uc_checkbox",
			"uc_dropdown",
			"uc_colorpicker",
			"uc_image",
			"uc_editor",
			"uc_statictext"
		);
		
		return($arrTypes);
	}
	
	/**
	 * add image base settings
	 */
	public function addImageBaseSettings(){
		
		$extra = array("origtype"=>"uc_image");
		$this->addImage("image","","Image",$extra);
		
		$extra = array("origtype"=>"uc_textarea");
		$this->addTextArea("description", "", __("Description", UNITECREATOR_TEXTDOMAIN),$extra);
		
		$extra = array("origtype"=>"uc_radioboolean");
		$this->addRadioBoolean("enable_link", __("Enable Link", UNITECREATOR_TEXTDOMAIN),false, "Yes","No",$extra);
			
		$extra = array("class"=>"unite-input-link", "origtype"=>"uc_textfield");
		$this->addTextBox("link", "", __("Link", UNITECREATOR_TEXTDOMAIN),$extra);
		
	}

	
	
	/**
	 * get settings in creator format
	 */
	public function getSettingsCreatorFormat(){
		
		$arrParams = array();
		foreach($this->arrSettings as $setting){
			$param = array();
			$origType = UniteFunctionsUC::getVal($setting, "origtype");
			UniteFunctionsUC::validateNotEmpty($origType, "settings original type");
			
			$param["type"] = $origType;
			$param["title"] = UniteFunctionsUC::getVal($setting, "text");
			$param["name"] = UniteFunctionsUC::getVal($setting, "name");
			$param["description"] = UniteFunctionsUC::getVal($setting, "description");
			$param["default_value"] = UniteFunctionsUC::getVal($setting, "default_value");
			$arrParams[] = $param;
		}
		
		return($arrParams);
	}
	
	
	/**
	 * add setting by creator param
	 */
	public function addByCreatorParam($param, $inputValue = null){
				
		$type = UniteFunctionsUC::getVal($param, "type");
		$title = UniteFunctionsUC::getVal($param, "title");
		$name = UniteFunctionsUC::getVal($param, "name");
		
		$description = "";	//output by custom unite setting itself
		
		//$description = UniteFunctionsUC::getVal($param, "description");
		$value = UniteFunctionsUC::getVal($param, "default_value");
		$unit = UniteFunctionsUC::getVal($param, "unit");
		
		
		$extra = array();
		if(!empty($description))
			$extra["description"] = $description;
		
		if(!empty($unit))
			$extra["unit"] = $unit;
		
		$extra["origtype"] = $type;
		$extra[UniteSettingsUC::PARAM_CLASSADD] = "wpb_vc_param_value";
		
		switch ($type){
			case "uc_editor":
				$this->addEditor($name, $value, $title, $extra);
				break;
			case "uc_textfield":
				$this->addTextBox($name, $value, $title, $extra);
				break;
			case "uc_number":
				$extra["class"] = UniteCreatorSettingsOutput::INPUT_CLASS_NUMBER;
				$this->addTextBox($name, $value, $title, $extra);
				break;
			case "uc_radioboolean":
				$arrItems = array();
				$arrItems[$param["true_value"]] = $param["true_name"];
				$arrItems[$param["false_value"]] = $param["false_name"];
		
				$this->addRadio($name, $arrItems, $title, $value, $extra);
				break;
			case "uc_textarea":
				$this->addTextArea($name, $value, $title, $extra);
				break;
			case "uc_checkbox":
				$textNear = UniteFunctionsUC::getVal($param, "text_near");
				$isChecked = UniteFunctionsUC::getVal($param, "is_checked");
				$isChecked = UniteFunctionsUC::strToBool($isChecked);
		
				$this->addCheckbox($name, $isChecked, $title, $textNear, $extra);
				break;
			case "uc_dropdown":
				$options = UniteFunctionsUC::getVal($param, "options");
				
				$this->addSelect($name, $options, $title, $value, $extra);
				break;
			case "uc_colorpicker":
				$this->addColorPicker($name, $value, $title, $extra);
				break;
			case "uc_image":
				$this->addImage($name,$value,$title,$extra);
				break;
			case "uc_imagebase":
				$this->addImageBaseSettings();
			break;
			case "uc_statictext":
				$this->addStaticText($title, $name, $extra);
			break;
			default:
				UniteFunctionsUC::throwError("initByCreatorParams error: Wrong setting type: $type");
			break;
		}
		
		//set setting value
		if($inputValue !== null && $type != "uc_statictext"){
			$this->updateSettingValue($name, $inputValue);
		}
		
	}
	
	
	/**
	 * add settings by creator params
	 */
	public function initByCreatorParams($arrParams){
		
		foreach($arrParams as $param){
			$this->addByCreatorParam($param);
		}
		
	}
	
	
}