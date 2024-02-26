<?php

class UniteCreatorAddonConfig extends HtmlOutputBaseUC{
	
	private $startWithAddon = false;
	private $isPreviewMode = false;
	private $startAddon = null;
	private $hasItems = null;
	
	
	/**
	 * validate start addon
	 */
	private function valdiateStartAddon(){
		
		if($this->startWithAddon == false)
			UniteFunctionsUC::throwError("No start addon found");
	
	}
	
	/**
	 * get preview html
	 */
	private function getHtmlPreview(){
		$html = "";
		
		//preview
		$html .= self::TAB2."<div class='uc-addon-config-preview' style='display:none'>".self::BR;
		$html .= 	self::TAB3."<div class='uc-addon-config-preview-title'>Preview".self::BR;
		$html .= 	self::TAB3."</div>".self::BR;
		
		$html .= 	self::TAB3."<div class='uc-preview-content'>".self::BR;
		
		$html .= 	self::TAB4."<iframe class='uc-preview-iframe'>".self::BR;
		$html .= 	self::TAB4."</iframe>".self::BR;
		
		$html .= 	self::TAB3."</div>".self::BR;
		
		
		return($html);
	}
	
	
	/**
	 * get items html
	 */
	public function getHtmlItems($putMode = false){
		
		$objManager = new UniteCreatorManagerInline();
		if($this->startWithAddon)
			$objManager->setStartAddon($this->startAddon);
		
		
		$html = "";
		
		$html .= self::TAB3."<div class='uc-addon-config-items'>".self::BR;
		
		$html .= self::TAB3."<div class='uc-addon-config-title'>".__("Edit Items", UNITECREATOR_TEXTDOMAIN)."</div>".self::BR;
		
		if($putMode == true){
			echo $html;
			$html = "";
			$objManager->outputHtml();
		
		}else{
			
			$html .= "items html here!";
		
		}
		
		$html .= self::TAB3."</div>".self::BR;
		
		$html .= self::TAB3."<span class='uc-addon-config-loader loader_text' style='display:none'>".__("Loading Settings...",UNITECREATOR_TEXTDOMAIN)."</span>".self::BR;
		
		if($putMode == true){
			echo $html;
		}else		
			return($html);
	}

	
	/**
	 * get item settings html
	 */
	private function getHtmlSettings($putMode = false){
		
		$html = "";
		
		$html .= 	self::TAB3."<div class='uc-addon-config-settings unite-settings'>".self::BR;
		
		if($putMode == true){
			echo $html;
			$html = "";
		}
		
		if($this->startWithAddon == true){
		
			if($putMode == true)
				$this->startAddon->putHtmlConfig();
			else{
				$htmlConfig = $this->startAddon->getHtmlConfig();
				$html .= $htmlConfig;
			}
		}
		
		$html .= self::TAB3."</div>".self::BR;	//settings
		
		
		if($putMode == true)
			echo $html;
		else		
			return($html);
		
	}
	
	
	/**
	 * put html frame of the config
	 */
	public function getHtmlFrame($putMode = false){
		
		$title = __("Addon Title", UNITECREATOR_TEXTDOMAIN);

		$addHtml = "style='display:none'";
		$htmlConfig = "";
		
		if($this->startWithAddon == true){
			$addHtml = "";
			$title = $this->startAddon->getTitle(true);
			
		}
		
		$html = "";
		
		//settings
		$html .= self::TAB. "<div id='uc_addon_config' class='uc-addon-config' {$addHtml}>".self::BR;
		
		//set preview style
		$styleConfigTable = "";
		if($this->isPreviewMode == true)
			$styleConfigTable = "style='display:none'";
		
		$html .= self::TAB2."<table id='uc_addon_config_table' class='uc-addon-config-table' {$styleConfigTable}>".self::BR;
		$html .= self::TAB3."<tr>".self::BR;
		$html .= self::TAB4."<td class='uc-addon-config-cell-left'>".self::BR;
		$html .= self::TAB5."<div class='uc-addon-config-left'>".self::BR;
		
		//put title
		$html .= 	self::TAB3."<div class='uc-addon-config-title'>$title</div>".self::BR;
		
		//put settings
		if($putMode == true){
			echo $html;
			$html = "";
			$this->getHtmlSettings(true);
		}else{
			$html .= $this->getHtmlSettings();
		}
		
		$html .= self::TAB5."</div'>".self::BR;
		$html .= self::TAB4."</td>".self::BR;

		//end cell left
		$html .= self::TAB4."<td class='uc-addon-config-cell-right'>".self::BR;
			
		//put items
		if($putMode == true){
			echo $html;
			$html = "";
			$this->getHtmlItems(true);
		}else{
			$html .= $this->getHtmlItems();
		}
		
		$html .= self::TAB4."</td>".self::BR;
		
		//end right cell
		
		$html .= self::TAB3."</tr>".self::BR;
		$html .= self::TAB2."</table>".self::BR;
		
		//end preview table
				
		$html .= $this->getHtmlPreview();
		
		$html .= self::TAB."</div>".self::BR;	//main wrapper
		
		if($putMode == true)
			echo $html;
		else
			return($html);
	}
	
	
	/**
	 * put html frame
	 */
	public function putHtmlFrame(){
		$this->getHtmlFrame(true);
	}
	
	
	/**
	 * put single mode init script
	 */
	public function putInitScript($addScript = ""){
		
		$this->valdiateStartAddon();
		
		$addonName = $this->startAddon->getName();
		$addonID = $this->startAddon->getID();
		$arrOptions = $this->startAddon->getOptions();
		
		$jsonOptions = UniteFunctionsUC::jsonEncodeForClientSide($arrOptions);
		
		$strPreviewMode = UniteFunctionsUC::boolToStr($this->isPreviewMode);
		
		$script = '
			jQuery(document).ready(function(){
				var objConfigWrapper = jQuery("#uc_addon_config");
				var objConfig = new UniteCreatorAddonConfig();
				var objOptionsJson = '.$jsonOptions.';
				var objOptions = jQuery.parseJSON(objOptionsJson); 
				
				objConfig.init(objConfigWrapper,'.$strPreviewMode.');
				objConfig.setAddonObjects("'.$addonName.'", objOptions, "'.$addonID.'");';
		
		if(!empty($addScript)){
			$script .= $addScript;
		}
		
		if($this->isPreviewMode == true){
			$script .= '
				objConfig.showPreview();
			';
		}
		
		$script .= '
			});
		';
		
		
		
		UniteProviderFunctionsUC::printCustomScript($script);
	}
	
	
	/**
	 * set to start with preview
	 */
	public function startWithPreview($isPreview){
		
		$this->isPreviewMode = $isPreview;
	}
	
	
	/**
	 * set start addon
	 */
	public function setStartAddon($objAddon){
		$this->startWithAddon = true;
		$this->startAddon = $objAddon;
		$this->hasItems = $this->startAddon->isHasItems();
	}
	
	
	
	
}