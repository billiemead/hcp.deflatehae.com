<?php

class UCVCAddonBase extends WPBakeryShortCode {

	
	
	/**
	 * get params values
	 * @param $atts
	 */
	protected function getParamsValues($atts){
		
		if(empty($atts))
			return(array());
		
		
		$arrValues = $atts;
		
		if(isset($arrValues["uc_items_data"]))
			unset($arrValues["uc_items_data"]);
		
		return($arrValues);
	}
	
	
	/**
	 * get items data
	 */
	private function getItemsData($atts){
		
		if(!isset($atts["uc_items_data"]))
			return(array());
		
		$itemsData = $atts["uc_items_data"];
		if(empty($itemsData))
			return(array());
		
		$arrItems = UniteVCCustomParams::decodeContent($itemsData);
		
		return($arrItems);
	}
	
	
	/**
	 * addon shortcode with all the addon related methods
	 */
	protected function content($atts, $content = null) {
		
		try{
			
			$addonName = UniteFunctionsUC::getVal($this->settings, "addon_name");
			$arrParamValues = $this->getParamsValues($atts);
			$arrItemsData = $this->getItemsData($atts);
			
			//------- init addon
			
			$objAddon = new UniteCreatorAddon();
			$objAddon->initByName($addonName);
			
			if(!empty($arrParamValues))
				$objAddon->setParamsValues($arrParamValues);
			
			if(!empty($arrItemsData))
				$objAddon->setArrItems($arrItemsData);
			
			//------- init output
			
			$output = new UniteCreatorOutput();
			$output->initByAddon($objAddon);
			
			$cssFilesPlace = HelperUC::getGeneralSetting("css_includes_to");
			
			//process only js in include css in body
			$includesProcessType = ($cssFilesPlace == "footer")?"all":"js";
			
			$output->processIncludes($includesProcessType);
			
			//decide if the js will be in footer
			$scriptsHardCoded = false;
			$isInFooter = HelperUC::getGeneralSetting("js_in_footer");
			$isInFooter = UniteFunctionsUC::strToBool($isInFooter);
			
			if($isInFooter == false)
				$scriptsHardCoded = true;
			
			$putCssIncludesInBody = ($cssFilesPlace == "body")?true:false;
			
			$htmlOutput = $output->getHtmlBody($scriptsHardCoded, $putCssIncludesInBody);
			
		}catch(Exception $e){
			
			HelperHtmlUC::outputExceptionBox($e, "Addon Creator Error");
			
		}
		
		
		return $htmlOutput;
	}

}
