<?php

class UniteCreatorBrowser extends HtmlOutputBaseUC{
	
	private $selectedCatID = null;
	private $inputIDForUpdate = null;		//input for field values update
	
	private $startWithAddon = false;
	private $startAddon = null;
	private $startError = null;
	
	
	/**
	 * get tabs html
	 */
	private function getHtmlTabs($arrCats){
		
		$html = "";

		$addHtml = "";
		if($this->startWithAddon == true)
			$addHtml .= "style='display:none'";
		
		$html .= self::TAB2."<div class=\"uc-browser-tabs-wrapper\" {$addHtml}>".self::BR;
		
		foreach($arrCats as $catID=>$cat){
			
			
			$isSelected = false;
			if($this->selectedCatID === null){
				$isSelected = true;
				$this->selectedCatID = $catID;
			}else
				if($this->selectedCatID == $catID)
					$isSelected = true;
			
			$addClass = "";
			if($isSelected == true)
				$addClass = " uc-tab-selected";
			
			$catTitle = UniteFunctionsUC::getVal($cat, "title");
			$catTitle = htmlspecialchars($catTitle);
		
			$html .= self::TAB3."<a href=\"javascript:void(0)\" onfocus=\"this.blur()\" class=\"uc-browser-tab{$addClass}\" data-catid=\"{$catID}\">{$catTitle}</a>".self::BR;
		}
		
		$html .= "<div class='unite-clear'></div>";
		
		$html .= self::TAB2."</div>";	//tabs
		
		return($html);
	}

	
	
	/**
	 * get content html
	 */
	private function getHtmlContent($arrCats){
		
		$html = "";
		
		$addHtml = "";
		if($this->startWithAddon == true)
			$addHtml .= "style='display:none'";
		
		$html .= self::TAB2."<div class=\"uc-browser-content-wrapper\" {$addHtml}>".self::BR;
		
		//output addons
		foreach($arrCats as $catID=>$cat){
			
			$style = " style=\"display:none\"";
			if($catID === $this->selectedCatID)
				$style = "";
			
			$html .= self::TAB3."<div id=\"uc_browser_content_{$catID}\" class=\"uc-browser-content\" {$style} >".self::BR;
		
			$arrAddons = UniteFunctionsUC::getVal($cat, "addons");
		
			foreach($arrAddons as $addon){
				$html .= $this->getHtmlAddon($addon);
			}
		
			$html .= self::TAB3."</div>".self::BR;
		}
		
		$html .= self::TAB2."<div class='unite-clear'></div>".self::BR;
		
		$html .= self::TAB2."</div>".self::BR; //content wrapper
		
		return($html);
	}

	
	/**
	 * get addon html
	 * @param $addon
	 */
	private function getHtmlAddon($addon){
	
		$html = "";
	
		$name = htmlspecialchars($addon["name"]);
		$title = htmlspecialchars($addon["title"]);
		$description = htmlspecialchars($addon["description"]);
		$urlIcon = GlobalsUC::$urlPlugin."images/icon_puzzle.png";
		
		$id = $addon["id"];
	
		$html .= self::TAB4."<a class=\"uc-browser-addon\" data-id=\"$id\" data-name=\"{$name}\" data-title=\"{$title}\">".self::BR;
		
		$htmlIcon = "<img src='{$urlIcon}' alt='$title'>";
		
		$html .= self::TAB5."<div class=\"uc-browser-addon-icon\">{$htmlIcon}</div>".self::BR;
	
		$html .= self::TAB5."<div class=\"uc-browser-addon-right\">".self::BR;
		$html .= self::TAB6."<div class=\"uc-browser-addon-title\">{$title}</div>".self::BR;
		$html .= self::TAB6."<div class=\"uc-browser-addon-desc\">{$description}</div>".self::BR;
		$html .= self::TAB5."</div>".self::BR;

		$html .= self::TAB4."</a>".self::BR;
	
		return($html);
	}
	
	
	/**
	 * get browser html
	 */
	private function getHtml(){
		
		$objAddons = new UniteCreatorAddons();
		$arrCats = $objAddons->getAddonsWidthCategoriesShort();
		
		$html = "";
		
		$addHtml = "";
		if(!empty($this->inputIDForUpdate))
			$addHtml .= " data-inputupdate=\"".$this->inputIDForUpdate."\"";
		
		if($this->startWithAddon == true){
			
			$addonName = $this->startAddon->getName();
			$addonName = htmlspecialchars($addonName);
			$addHtml .= " data-startaddon='{$addonName}'";
		}
		
		$html .= self::TAB."<div class=\"uc-browser-wrapper\" {$addHtml}>".self::BR;
		
		//output tabs
		$html .= $this->getHtmlTabs($arrCats);

		//output content
		$html .= $this->getHtmlContent($arrCats);
		
		//output back button
		$buttonAddHtml = "style='display:none'";
		if($this->startWithAddon == true)
			$buttonAddHtml = "";
		
		$html .= self::TAB2."<a href='javascript:void(0)' class='uc-browser-button-back unite-button-secondary' {$buttonAddHtml}>".__("Choose Another Addon", UNITECREATOR_TEXTDOMAIN)."</a>".self::BR;
		
		//output config
		$objAddonConfig = new UniteCreatorAddonConfig();
		if($this->startWithAddon)
			$objAddonConfig->setStartAddon($this->startAddon);
			
		$htmlFrame = $objAddonConfig->getHtmlFrame();
		
		$html .= self::BR. $htmlFrame;
		
		$html .= self::TAB."</div>"; //wrapper
		
		return($html);
	}
	
	
	/**
	 * put scripts
	 */
	public function putScripts(){
		
		UniteCreatorAdmin::onAddScriptsBrowser();
	}
	
	
	/**
	 * put browser
	 */
	public function putBrowser($getHTML = false){
		
		$html = $this->getHtml();
		
		return $html;
	}
	
	
	/**
	 * put scripts and browser
	 */
	public function putScriptsAndBrowser($getHTML = false){
		
		try{
			
			$this->putScripts();
			$html = $this->putBrowser($getHTML);
		
			if($getHTML == true)
				return($html);
		
		}catch(Exception $e){
			
			$message = $e->getMessage();
			
			$trace = "";
			if(GlobalsUC::SHOW_TRACE == true)
				$trace = $e->getTraceAsString();
			
			$htmlError = HelperUC::getHtmlErrorMessage($message, $trace);
			
			return($htmlError);
		}
		
	}
	
	
	/**
	 * set input id for values update
	 */
	public function setInputIDForValuesUpdate($inputID){
		$this->inputIDForUpdate = $inputID;
	}
	
	
	
	/**
	 * set init data json format
	 */
	public function setJsonInitData($jsonData){
		
		if(empty($jsonData))
			return(false);
		
		$arrData = @json_decode($jsonData);
		if(!is_object($arrData))
			return(false);
		
		$arrData = UniteFunctionsUC::convertStdClassToArray($arrData);
		
		$addonName = UniteFunctionsUC::getVal($arrData, "name");
		
		if(empty($addonName))
			return(false);
		
		$this->startWithAddon = true;
		
		$settingsValues = UniteFunctionsUC::getVal($arrData, "values");
		
		try{
			
			$this->startAddon = new UniteCreatorAddon();
			$this->startAddon->initByName($addonName);
			if(!empty($settingsValues))
				$this->startAddon->setParamsValues($settingsValues);
			
		}catch(Exception $e){
			$message = $e->getMessage();
			$this->startError = $message;
			
		}
		
		
	}
	
	
}
