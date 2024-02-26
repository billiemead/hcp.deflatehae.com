<?php
/**
 * @package Addon Creator
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved.
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class UniteCreatorAddon extends UniteElementsBaseUC{

		const FIELDS_ADDONS = "title,name,description,ordering,templates,config,catid,test_slot1,test_slot2,test_slot3,is_active";
		const ITEMS_TYPE_IMAGE = "image";
		const ITEMS_TYPE_DEFAULT = "default";
		const FILENAME_ICON = "icon_addon.png";

		private $id = null;
		private $isInited = false;
		private $title,$html,$htmlItem,$htmlItem2,$css,$js;
		private $data, $config, $arrTemplates;
		private $params = array(),$paramsItems = array(), $options = array();
		private $name, $catid, $ordering, $isActive;
		private $includesCSS, $includesJS, $includesJSLib;
		private $hasItems, $itemsType,  $arrItems, $pathAssets, $urlAssets;
		private $variablesItems = array(), $variablesMain = array();
		private $operations;

		private static $arrCacheRecords = array();
		private static $defaultOptions = null;


		/**
		 *
		 * constructor
		 */
		public function __construct(){
			parent::__construct();
			$this->operations = new UCOperations();

			//get options settings
			if(self::$defaultOptions === null)
				$this->initDefaultOptions();

		}

		/**
		 * init default addon options from settings file
		 */
		private function initDefaultOptions(){

			$filepathAddonSettings = GlobalsUC::$pathSettings."addon_fields.php";
			require $filepathAddonSettings;

			//$generalSettings = new UniteCreatorSettings();
			self::$defaultOptions = $generalSettings->getArrValues();

			if(empty(self::$defaultOptions))
				self::$defaultOptions = array();

		}


		protected function _____________INIT_VALIDATE______________(){}


		/**
		 *
		 * validate that the item inited
		 */
		private function validateInited(){
			if($this->isInited == false)
				UniteFunctionsUC::throwError("The addon is not inited!");
		}


		/**
		 * validate title
		 */
		private function validateTitle($title){
			UniteFunctionsUC::validateNotEmpty($title, "Addon Title");

		}


		/**
		 * validate addon name
		 */
		private function validateName($name){

			$fieldName = __("Addon Name", UNITECREATOR_TEXTDOMAIN);

			UniteFunctionsUC::validateNotEmpty($name, $fieldName);
			UniteFunctionsUC::validateAlphaNumeric($name, $fieldName);

			$this->validateNameNotExists($name);
		}




		/**
		 * validate test data slot num
		 */
		private function validateTestSlot($num){
			$num = (int)$num;
			if($num < 0 || $num > 3)
				UniteFunctionsUC::throwError("Wrong test slot number: $num");
		}


		/**
		 * validate params before save or updata
		 * avoid doubles
		 */
		private function validateParams($arrParams, $type="main"){

			$arrParams = $this->initProcessParams($arrParams);

			$arrNames = array();
			foreach($arrParams as $param){
				$name = UniteFunctionsUC::getVal($param, "name");
				if(empty($name))
					UniteFunctionsUC::throwError("Empty param name found");

				if(isset($arrNames[$name])){
					$message = "Duplicate $type param name found: <b> $name </b>";
					if(in_array($name, array("link","image","thumb","title","enable_link")))
						$message .= ". <br> The <b>$name</b> param is included in the image base params";

					UniteFunctionsUC::throwError($message);
				}

				$arrNames[$name] = true;
			}

		}


		/**
		 * check if addon exists by name
		 */
		public function isAddonExistsByName($name, $checkID = true){

			$name = $this->db->escape($name);

			$where = "name='$name'";

			if($checkID == true){
				if(!empty($this->id)){
					$addonID = $this->id;
					$where .= " and id<>".$this->id;
				}
			}

			$response = $this->db->fetch(GlobalsUC::$table_addons, $where);
			if(!empty($response))
				return(true);

			return(false);
		}


		/**
		 * validate that addon not exists by name
		 */
		private function validateNameNotExists($name){

			$isExists = $this->isAddonExistsByName($name);
			if($isExists == true)
				UniteFunctionsUC::throwError("The addon with name: $name already exists");
		}


		/**
		 * init item by ID
		 */
		public function initByID($id){

			try{
				$record = $this->db->fetchSingle(GlobalsUC::$table_addons,"id={$id}");

			}catch(Exception $e){
				UniteFunctionsUC::throwError("Addon with ID: {$id} not found");
			}

			$this->initByDBRecord($record);
		}


		/**
		 * init addon by name,
		 * for this function there is cache get
		 */
		public function initByName($name, $checkCache=true){

			try{

				//try to get from cache
				if($checkCache == true && array_key_exists($name, self::$arrCacheRecords) == true)
					$record = self::$arrCacheRecords[$name];
				else
					$record = $this->db->fetchSingle(GlobalsUC::$table_addons,"name='$name'");

				$this->initByDBRecord($record);

			}catch(Exception $e){

				UniteFunctionsUC::throwError("Addon with name:<b> {$name} </b> not found");
			}

		}


		/**
		 * normalize includes array
		 */
		private function normalizeIncludeArray($arr){

			if(empty($arr))
				return(array());

			$newArr = array();
			foreach($arr as $item){
				if(is_string($item)){
					$item = trim($item);
					if(empty($item))
						continue;
				}else{			//in case of array
					$url = UniteFunctionsUC::getVal($item, "url");
					$url = trim($url);
					if(empty($url))
						continue;
					$item["url"] = $url;
				}

				$newArr[] = $item;
			}

			return($newArr);
		}


		/**
		 * find doubles in params on init
		 */
		private function initParamsFindDoubles($arrParams){

			if(!is_array($arrParams))
				return(array());

			if(empty($arrParams))
				return(array());

			$arrNames = array();
			foreach($arrParams as $key=>$param){

				if(is_array($param) == false)
					return($arrParams);

				$name = UniteFunctionsUC::getVal($param, "name");
				if(array_key_exists($name, $arrNames) == true)
					$arrParams[$key]["param_error"] = __("Double Name, please remove", UNITECREATOR_TEXTDOMAIN);

				$arrNames[$name] = true;
			}

			return($arrParams);
		}


		/**
		 * parse json params from record
		 */
		private function parseJsonFromRecord($record, $name){

			$data = UniteFunctionsUC::getVal($record, $name);

			if(empty($data))
				return(array());

			if(is_array($data))
				return($data);

			if(is_object($data))
				return UniteFunctionsUC::convertStdClassToArray($data);

			$content = @json_decode($data);

			if(empty($content))
				return($data);


			return UniteFunctionsUC::convertStdClassToArray($content);

		}


		/**
		 * get options path
		 */
		private function initAssetsPath(){
			$path = $this->getOption("path_assets");

			if(empty($path))
				return("");

			$pathAbsolute = UniteFunctionsUC::joinPaths(GlobalsUC::$pathAssets, $path);

			$isUnderAssets = HelperUC::isPathUnderAssetsPath($pathAbsolute);

			if($isUnderAssets == false)
				return("");

			if(is_dir($pathAbsolute) == false)
				return("");

			return($path);
		}


		/**
		 * get the items type on init
		 */
		private function initItemsType(){

			foreach($this->paramsItems as $param){
				$type = UniteFunctionsUC::getVal($param, "type");
				if($type == "uc_imagebase")
					return(self::ITEMS_TYPE_IMAGE);
			}

			return(self::ITEMS_TYPE_DEFAULT);
		}


		/**
		 * process params - add params by type (like image base)
		 */
		private function initProcessParams($arrParams){

			if(empty($arrParams))
				return(array());

			$arrParamsNew = array();
			foreach($arrParams as $param){

				$type = UniteFunctionsUC::getVal($param, "type");
				switch($type){
					case "uc_imagebase":
						$settings = new UniteCreatorSettings();
						$settings->addImageBaseSettings();
						$arrParamsAdd = $settings->getSettingsCreatorFormat();
						foreach($arrParamsAdd as $addParam)
							$arrParamsNew[] = $addParam;
					break;
					default:
						$arrParamsNew[] = $param;
					break;
				}

			}


			return($arrParamsNew);
		}



		/**
		 * convert includes array to full url
		 */
		private function arrIncludesToFullUrl($arrIncludes){

			foreach($arrIncludes as $key => $include){
				if(is_string($include))
					$include = HelperUC::URLtoFull($include, GlobalsUC::$url_assets);
				else{
					$url = UniteFunctionsUC::getVal($include, "url");
					if(!empty($url))
						$include["url"] = HelperUC::URLtoFull($url, GlobalsUC::$url_assets);
				}

				$arrIncludes[$key] = $include;
			}


			return($arrIncludes);
		}


		/**
		 * init addon options
		 *
		 */
		private function initAddonOptions($arrOptions){

			if(empty($arrOptions))
				$arrOptions = array();

			$arrOptions = array_merge(self::$defaultOptions, $arrOptions);


			return($arrOptions);
		}


		/**
		 *
		 * init item by db record
		 */
		public function initByDBRecord($record){

			//cache db record
			$addonName = UniteFunctionsUC::getVal($record, "name");
			self::$arrCacheRecords[$addonName] = $record;


			UniteFunctionsUC::validateNotEmpty($record, "The Addon not exists");

			$this->isInited = true;

			$this->data = $record;

			$this->id = UniteFunctionsUC::getVal($record, "id");

			$this->title = UniteFunctionsUC::getVal($record, "title");
			$this->name = UniteFunctionsUC::getVal($record, "name");
			$this->catid = UniteFunctionsUC::getVal($record, "catid");
			$this->ordering = (int)UniteFunctionsUC::getVal($record, "ordering");
			$this->isActive = (int)UniteFunctionsUC::getVal($record, "is_active");


			//get templates
			$this->arrTemplates = $this->parseJsonFromRecord($record, "templates");


			if(!empty($this->arrTemplates)){

				$this->html = UniteFunctionsUC::getVal($this->arrTemplates, "html");
				$this->htmlItem = UniteFunctionsUC::getVal($this->arrTemplates, "html_item");
				$this->htmlItem2 = UniteFunctionsUC::getVal($this->arrTemplates, "html_item2");
				$this->css = UniteFunctionsUC::getVal($this->arrTemplates, "css");
				$this->js = UniteFunctionsUC::getVal($this->arrTemplates, "js");

			}else{		//get data the old way

				$this->html = UniteFunctionsUC::getVal($record, "html");
				$this->htmlItem = UniteFunctionsUC::getVal($record, "html_item");
				$this->css = UniteFunctionsUC::getVal($record, "css");
				$this->js = UniteFunctionsUC::getVal($record, "js");

				$this->arrTemplates = array();
				$this->arrTemplates["html"] = $this->html;
				$this->arrTemplates["html_item"] = $this->htmlItem;
				$this->arrTemplates["css"] = $this->css;
				$this->arrTemplates["js"] = $this->js;
			}


			$arrIncludes = array();

			$arrConfig = $this->parseJsonFromRecord($record, "config");

			$this->config = $arrConfig;

			if(!empty($arrConfig)){

				$this->params = $this->parseJsonFromRecord($arrConfig, "params");

				$this->paramsItems = $this->parseJsonFromRecord($arrConfig, "params_items");

				$this->options = UniteFunctionsUC::getVal($arrConfig, "options");

				$arrIncludes = UniteFunctionsUC::getVal($arrConfig, "includes");

				$this->variablesItems = UniteFunctionsUC::getVal($arrConfig, "variables_item");
				if(empty($this->variablesItems))
					$this->variablesItems = array();

				$this->variablesMain = UniteFunctionsUC::getVal($arrConfig, "variables_main");
				if(empty($this->variablesMain))
					$this->variablesMain = array();


			}else{		//get old fashion

				$this->params = $this->parseJsonFromRecord($record, "params");
				$this->paramsItems = $this->parseJsonFromRecord($record, "params_items");
				$this->options = $this->parseJsonFromRecord($record, "options");

				$jsonIncludes = UniteFunctionsUC::getVal($record, "includes");
				if(!empty($jsonIncludes))
					$arrIncludes = json_decode($jsonIncludes);
			}

			$this->options = $this->initAddonOptions($this->options);


			//check params for doubles
			$this->params = $this->initParamsFindDoubles($this->params);
			$this->paramsItems = $this->initParamsFindDoubles($this->paramsItems);

			//process params items
			$this->paramsItems = $this->operations->checkAddParamTitle($this->paramsItems);

			//set assets path
			$this->pathAssets = $this->initAssetsPath();

			if($this->pathAssets)
				$this->urlAssets = GlobalsUC::$url_assets.$this->pathAssets."/";

			//init if has items
			$enableItems = $this->getOption("enable_items");
			$this->hasItems = UniteFunctionsUC::strToBool($enableItems);
			$this->itemsType = $this->initItemsType();

			//parse includes

			if(!empty($arrIncludes)){

				$arrIncludes = UniteFunctionsUC::convertStdClassToArray($arrIncludes);

				$this->includesJS = UniteFunctionsUC::getVal($arrIncludes, "js", array());
				$this->includesJSLib = UniteFunctionsUC::getVal($arrIncludes, "jslib", array());
				$this->includesCSS = UniteFunctionsUC::getVal($arrIncludes, "css", array());

				$this->includesJS = $this->arrIncludesToFullUrl($this->includesJS);
				$this->includesCSS = $this->arrIncludesToFullUrl($this->includesCSS);
			}

			$this->includesJS = $this->normalizeIncludeArray($this->includesJS);
			$this->includesCSS = $this->normalizeIncludeArray($this->includesCSS);
			$this->includesJSLib = $this->normalizeIncludeArray($this->includesJSLib);


		}


		protected function _____________GETTERS______________(){}

		/**
		 * get html template
		 * @param $isSpecialChars
		 */
		private function getHtmlTemplate($html, $isSpecialChars = false){

			$this->validateInited();

			if($isSpecialChars == true)
				return(htmlspecialchars($html));

			return($html);
		}


		public function getTitle($isSpecialChars = false){
			return $this->getHtmlTemplate($this->title, $isSpecialChars);
		}

		public function getHtml($isSpecialChars = false){
			return $this->getHtmlTemplate($this->html, $isSpecialChars);
		}

		public function getHtmlItem($isSpecialChars = false){
			return $this->getHtmlTemplate($this->htmlItem, $isSpecialChars);
		}

		public function getHtmlItem2($isSpecialChars = false){
			return $this->getHtmlTemplate($this->htmlItem2, $isSpecialChars);
		}

		public function getCss($isSpecialChars = false){
			return $this->getHtmlTemplate($this->css, $isSpecialChars);
		}

		public function getJs($isSpecialChars = false){
			return $this->getHtmlTemplate($this->js, $isSpecialChars);
		}


		/**
		 * return ID
		 */
		public function getID(){
			return($this->id);
		}

		/**
		 *
		 * get name
		 */
		public function getName(){
			return($this->name);
		}

		/**
		 * get description
		 */
		public function getDescription($isSpecialChars = false){

			$description = $this->getOption("description");

			return $this->getHtmlTemplate($description, $isSpecialChars);
		}


		/**
		 * get icon url if exists
		 */
		public function getUrlIcon(){

			$showIcon = $this->getOption("show_small_icon");
			$showIcon = UniteFunctionsUC::strToBool($showIcon);

			if($showIcon == false)
				return(null);

			$urlIcon = GlobalsUC::$url_default_addon_icon;

			$pathAssets = $this->getPathAssetsFull();

			if(empty($pathAssets))
				return($urlIcon);

			$filepathIcon = $pathAssets.self::FILENAME_ICON;
			if(file_exists($filepathIcon) == false)
				return($urlIcon);

			$urlAssets = $this->getUrlAssets();

			$urlIcon = $urlAssets.self::FILENAME_ICON;

			return($urlIcon);
		}


		/**
		 *
		 * get params
		 */
		public function getParams(){

			return($this->params);
		}

		/**
		 * get items params
		 */
		public function getParamsItems(){

			return($this->paramsItems);
		}


		/**
		 * get addon optinos
		 */
		public function getOptions(){

			return($this->options);
		}


		/**
		 * return if the addon has items
		 */
		public function isHasItems(){

			return($this->hasItems);
		}


		/**
		 * get items type like image / default
		 */
		public function getItemsType(){
			return($this->itemsType);
		}


		/**
		 * get option
		 */
		public function getOption($name){
			$value = UniteFunctionsUC::getVal($this->options, $name);
			return($value);
		}


		/**
		 * get category id
		 */
		public function getCatID(){
			return($this->catid);
		}




		/**
		 * get js includes array
		 */
		public function getJSIncludes(){

			return($this->includesJS);
		}


		/**
		 * get includes of js libraries
		 */
		public function getJSLibIncludes(){

			return($this->includesJSLib);
		}


		/**
		 * get array of library inlcudes url's
		 */
		public function getArrLibraryIncludesUrls($processProvider){

			$this->validateInited();

			$operations = new UCOperations();

			$arrJsIncludes = array();
			$arrCssIncludes = array();

			$objLibrary = new UniteCreatorLibrary();

			foreach($this->includesJSLib as $libName){

				//process provider library instead of get files
				if($processProvider == true){
					$isProcessed = $objLibrary->processProviderLibrary($libName);

					if($isProcessed == true)
						continue;
				}

				$response = $objLibrary->getLibraryIncludes($libName);

				$arrJs = $response["js"];
				$arrCss = $response["css"];
				$arrJsIncludes = array_merge($arrJsIncludes, $arrJs);
				$arrCssIncludes = array_merge($arrCssIncludes, $arrCss);
			}

			$output = array();
			$output["js"] = $arrJsIncludes;
			$output["css"] = $arrCssIncludes;
			return($output);
		}


		/**
		 * get css includes array
		 */
		public function getCSSIncludes(){

			return($this->includesCSS);
		}




		/**
		 * get short array
		 */
		public function getArrShort(){

			$this->validateInited();

			$arr = array();
			$arr["id"] = $this->id;
			$arr["name"] = $this->name;
			$arr["title"] = $this->title;
			$arr["description"] = $this->getOption("description");

			return($arr);
		}


		/**
		 * get assets path - relative
		 */
		public function getPathAssetsFull(){

			$pathAssetsGlobals = trim(GlobalsUC::$pathAssets);

			if(empty($pathAssetsGlobals))
				return(null);

			$path = UniteFunctionsUC::joinPaths($pathAssetsGlobals, $this->pathAssets);

			$path = UniteFunctionsUC::addPathEndingSlash($path);

			return($path);
		}


		/**
		 * return assets path (relative to main assets path)
		 */
		public function getPathAssets(){

			return($this->pathAssets);
		}


		/**
		 * get assets url
		 */
		public function getUrlAssets(){
			return($this->urlAssets);
		}


		/**
		 * get item variables
		 */
		public function getVariablesItem(){
			return($this->variablesItems);
		}


		/**
		 * get item variables
		 */
		public function getVariablesMain(){
			return($this->variablesMain);
		}


		/**
		 * get config
		 */
		public function getConfig(){
			return $this->config;
		}


		/**
		 * get templates html
		 */
		public function getTemplates(){
			return($this->arrTemplates);
		}

		/**
		 * get addon row data
		 */
		public function getRowData(){

			return($this->data);
		}

		private function _____________GET_HTML______________(){}

		/**
		 * get addon config html
		 */
		public function getHtmlConfig($putMode = false){

			$this->validateInited();

			if(empty($this->params)){

				$html = __("no settings for this addon", UNITECREATOR_TEXTDOMAIN);

				if($putMode == true)
					echo $html;
				else
					return($html);

			}

			$arrParams = $this->processParamsForOutput($this->params);

			$objSettings = new UniteCreatorSettings();
			$objSettings->initByCreatorParams($arrParams);

			$objOutput = new UniteCreatorSettingsOutput();
			$objOutput->init($objSettings);
			$objOutput->setShowSaps(false);

			if($putMode == true){
				$objOutput->draw("uc_form_settings_addon", false);
			}else{
				ob_start();
				$objOutput->draw("uc_form_settings_addon", false);
				$html = ob_get_contents();
				ob_clean();
				return($html);
			}

		}


		/**
		 * put config html
		 */
		public function putHtmlConfig(){
			$this->getHtmlConfig(true);
		}


		/**
		 * get item config
		 */
		public function getHtmlItemConfig($putMode = false){

			$this->validateInited();

			//if output item settings, has to be settings
			if(empty($this->paramsItems)){

				UniteFunctionsUC::throwError("Item params not found");
			}

			$this->paramsItems = $this->processParamsForOutput($this->paramsItems);

			$objSettings = new UniteCreatorSettings();
			$objSettings->initByCreatorParams($this->paramsItems);

			$objOutput = new UniteCreatorSettingsOutput();
			$objOutput->init($objSettings);
			$objOutput->setShowSaps(false);

			if($putMode == true){
				$objOutput->draw("uc_form_addon_item_settings", false);
			}else{
				ob_start();
				$objOutput->draw("uc_form_addon_item_settings", false);
				$html = ob_get_contents();
				ob_clean();
				return($html);
			}

		}


		/**
		 * put item config html
		 */
		public function putHtmlItemConfig(){

			$this->getHtmlItemConfig(true);

		}


		/**
		 *
		 * get html for admin browsing
		 */
		public function getHtmlForAdmin(){
			$this->validateInited();

			$title = $this->title;

			$title = htmlspecialchars($title);

			$name = htmlspecialchars($this->name);

			$description = $this->getOption("description");
			$description = htmlspecialchars($description);

			//set html icon
			$urlIcon = $this->getUrlIcon();

			$htmlIcon = "";
			if(!empty($urlIcon))
				$htmlIcon = "<div class='uc-item-icon' style=\"background-image:url('{$urlIcon}')\"></div>";

			$itemID = $this->id;

			$style = "";
			$imageText = "";

			$descOutput = $description;

			$class = "";
			if($this->isActive == false)
				$class = " class=\"uc-item-notactive\"";


			//set html output
			$htmlItem  = "<li id=\"uc_item_{$itemID}\" data-id=\"{$itemID}\" data-title=\"{$title}\" data-name=\"{$name}\" data-description=\"{$description}\" {$class} >";
			$htmlItem .= "	<div class=\"uc-item-title unselectable\" unselectable=\"on\">{$title}</div>";
			$htmlItem .= "	<div class=\"uc-item-description unselectable\" unselectable=\"on\">{$descOutput}</div>";
			$htmlItem .= "	<div class=\"uc-item-icon unselectable\" unselectable=\"on\"></div>";
			$htmlItem .= $htmlIcon;
			$htmlItem .= "</li>";

			return($htmlItem);
		}



		private function _____________ADDON_CONTENT______________(){}


		/**
		 * process param value
		 */
		private function processParamValue($value, $type){

			if(empty($value))
				return($value);

			$urlAssets = $this->getUrlAssets();
			$value = str_replace("[url_assets]/", $urlAssets, $value);

			switch($type){
				case "uc_image":
					$value = HelperUC::URLtoFull($value);
				break;
			}


			return($value);
		}



		/**
		 * process params for output
		 */
		private function processParamsForOutput($arrParams){

			if(is_array($arrParams) == false)
				UniteFunctionsUC::throwError("objParams should be array");

			foreach($arrParams as $key=>$param){

				$type = UniteFunctionsUC::getVal($param, "type");

				if(isset($param["value"]))
					$param["value"] = $this->processParamValue($param["value"], $type);

				if(isset($param["default_value"]))
					$param["default_value"] = $this->processParamValue($param["default_value"], $type);


				$arrParams[$key] = $param;
			}

			return($arrParams);
		}


		/**
		 * get processed params
		 * @param $objParams
		 */
		private function getProcessedParamsValues($arrParams){

			$arrParams = $this->processParamsForOutput($arrParams);

			$data = array();

			foreach($arrParams as $param){

				$type = UniteFunctionsUC::getVal($param, "type");
				$name = UniteFunctionsUC::getVal($param, "name");

				$value = UniteFunctionsUC::getVal($param, "default_value");
				if(array_key_exists("value", $param))
					$value = UniteFunctionsUC::getVal($param, "value");

				$value = $this->processParamValue($value, $type);

				if(empty($name))
					continue;

				if(isset($data[$name]))
					continue;

				if($type != "imagebase_fields")
					$data[$name] = $value;

			}

			return($data);
		}



		/**
		 * get main processed variables
		 */
		private function getMainVariablesProcessed($arrParams){

			//get variables
			$objVariablesOutput = new UniteCreatorVariablesOutput();
			$objVariablesOutput->init($arrParams);

			$arrVars = $this->getVariablesMain();

			$arrOutput = array();

			foreach($arrVars as $var){

				$name = UniteFunctionsUC::getVal($var, "name");
				$content = $objVariablesOutput->getMainVarContent($var);
				$arrOutput[$name] = $content;
			}

			return($arrOutput);
		}


		/**
		 * get main params processed
		 */
		public function getProcessedMainParamsValues(){

			$this->validateInited();

			$objParams = $this->getParams();
			$arrParams = $this->getProcessedParamsValues($objParams);

			$arrVars = $this->getMainVariablesProcessed($arrParams);
			$arrParams = array_merge($arrParams, $arrVars);

			return($arrParams);
		}


		/**
		 * get processed main params
		 */
		public function getProcessedMainParams(){
			$this->validateInited();
			$arrParams = $this->processParamsForOutput($this->params);
			return($arrParams);
		}


		/**
		 * get items array
		 */
		public function getArrItems(){

			$arrItems = $this->getProcessedItemsData(false);

			return($arrItems);
		}


		/**
		 * process items variables, based on variable type and item content
		 */
		private function getItemsVariablesProcessed($arrItem, $index, $numItems){

			$arrVars = $this->getVariablesItem();
			$arrVarData = array();

			//get variables output object
			$arrParams = $this->getProcessedMainParamsValues();

			$objVarOutput = new UniteCreatorVariablesOutput();
			$objVarOutput->init($arrParams);


			foreach($arrVars as $var){
				$name = UniteFunctionsUC::getVal($var, "name");
				UniteFunctionsUC::validateNotEmpty($name, "variable name");

				$content = $objVarOutput->getItemVarContent($var, $arrItem, $index, $numItems);

				$arrVarData[$name] = $content;
			}

			return($arrVarData);
		}


		/**
		 * get item data
		 */
		public function getProcessedItemsData($forTemplate = true){

			if(empty($this->arrItems))
				return(array());

			$operations = new UCOperations();

			$arrItemsNew = array();
			$arrItemParams = $this->getParamsItems();
			$arrItemParams = $this->initProcessParams($arrItemParams);

			$numItems = count($this->arrItems);

			foreach($this->arrItems as $index => $arrItemValues){

				$arrParamsNew = $this->setParamsValuesWork($arrItemValues, $arrItemParams, "items");

				$item = $this->getProcessedParamsValues($arrParamsNew);

				//add constants
				$indexOutput = ($index+1);
				$itemID = $indexOutput;

				$addData = array(
					"item_index" => $indexOutput,
					"item_id" => $itemID
				);

				$item = array_merge($addData, $item);

				//add values by type
				switch($this->itemsType){
					case self::ITEMS_TYPE_IMAGE:
						//add thumb
						$urlImage = UniteFunctionsUC::getVal($item, "image");
						$urlThumb = $operations->createThumbs($urlImage);
						$urlThumb = HelperUC::URLtoFull($urlThumb);
						$item["thumb"] = $urlThumb;
					break;
				}

				//add item variables
				$arrVarsData = $this->getItemsVariablesProcessed($item, $index, $numItems);
				$item = array_merge($item, $arrVarsData);

				if($forTemplate == true)
					$arrItemsNew[] = array("item"=>$item);
				else
					$arrItemsNew[] = $item;
			}


			return($arrItemsNew);
		}


		/**
		 * set params values work
		 * type: main,items
		 */
		private function setParamsValuesWork($arrValues, $arrParams, $type){

			$this->validateInited();

			if(empty($arrValues))
				return($arrParams);

			if(!is_array($arrValues))
				UniteFunctionsUC::throwError("The values shoud be array");


			foreach($arrParams as $key => $param){
				$name = UniteFunctionsUC::getVal($param, "name");
				if(empty($name))
					continue;

				$defaultValue = UniteFunctionsUC::getVal($param, "default_value");
				$arrParams[$key]["value"] = UniteFunctionsUC::getVal($arrValues, $name, $defaultValue);
			}


			return($arrParams);
		}


		/**
		 * set params values
		 */
		public function setParamsValues($arrValues){

			$this->params = $this->setParamsValuesWork($arrValues, $this->params, "main");

		}


		/**
		 * set items array
		 */
		public function setArrItems($arrItems){
			$this->validateInited();

			if($this->hasItems == false)
				return(false);

			$this->arrItems = $arrItems;

		}


		private function _____________SETTERS______________(){}

		/**
		 * update addon in db
		 */
		private function updateInDB($arrUpdate){
			$this->validateInited();
			$this->db->update(GlobalsUC::$table_addons, $arrUpdate, array("id"=>$this->id));

			//init the item again from the new record
			$this->data = array_merge($this->data, $arrUpdate);
			$this->initByDBRecord($this->data);
		}


		/**
		 * get data for create / update
		 */
		private function getCreateUpdateData($data){

			$title = UniteFunctionsUC::getVal($data, "title");
			$html = UniteFunctionsUC::getVal($data, "html");
			$htmlItem = UniteFunctionsUC::getVal($data, "html_item");
			$htmlItem2 = UniteFunctionsUC::getVal($data, "html_item2");
			$css = UniteFunctionsUC::getVal($data, "css");
			$js = UniteFunctionsUC::getVal($data, "js");
			$name = UniteFunctionsUC::getVal($data, "name");

			//get config related fields
			$params = UniteFunctionsUC::getVal($data, "params");
			$paramsItems = UniteFunctionsUC::getVal($data, "params_items");
			$options = UniteFunctionsUC::getVal($data, "options");
			$variablesItem = UniteFunctionsUC::getVal($data, "variables_item");
			$variablesMain = UniteFunctionsUC::getVal($data, "variables_main");

			$includes = UniteFunctionsUC::getVal($data, "includes");

			if(empty($includes)){
				$arrJsIncludes = UniteFunctionsUC::getVal($data, "includes_js");
				$arrJsLib = UniteFunctionsUC::getVal($data, "includes_jslib");
				$arrCssIncludes = UniteFunctionsUC::getVal($data, "includes_css");

				$arrJsIncludes = $this->normalizeIncludeArray($arrJsIncludes);
				$arrJsLib = $this->normalizeIncludeArray($arrJsLib);
				$arrCssIncludes = $this->normalizeIncludeArray($arrCssIncludes);

				$arrJsIncludes = HelperUC::arrUrlsToRelative($arrJsIncludes, true);
				$arrCssIncludes = HelperUC::arrUrlsToRelative($arrCssIncludes, true);

				$includes = array("js"=>$arrJsIncludes, "jslib"=>$arrJsLib, "css"=>$arrCssIncludes);
			}

			//validation
			$this->validateName($name);
			$this->validateTitle($title);
			$this->validateParams($paramsItems,"item");
			$this->validateParams($params,"main");

			//create config variable

			$arrConfig = array();
			$arrConfig["options"] = $options;
			$arrConfig["params"] = $params;
			$arrConfig["params_items"] = $paramsItems;
			$arrConfig["includes"] = $includes;
			$arrConfig["variables_item"] = $variablesItem;
			$arrConfig["variables_main"] = $variablesMain;

			$strConfig = json_encode($arrConfig);

			//create template variables

			$arrTemplates = array();
			$arrTemplates["html"] = trim($html);
			$arrTemplates["html_item"] = trim($htmlItem);
			$arrTemplates["html_item2"] = trim($htmlItem2);
			$arrTemplates["css"] = trim($css);
			$arrTemplates["js"] = trim($js);

			$strTemplates = json_encode($arrTemplates);

			$arr = array();
			$arr["title"] = trim($title);
			$arr["name"] = trim($name);
			$arr["config"] = $strConfig;
			$arr["templates"] = $strTemplates;

			return($arr);
		}

		/**
		 * get last order in category for insert or change
		 */
		private function getLastOrderInCat($catID){

			$addons = new UniteCreatorAddons();
			$maxOrder = $addons->getMaxOrder($catID);

			return($maxOrder+1);
		}


		/**
		 * insert new addon to db. add ordering first
		 * @param $arrInsert
		 */
		private function insertNewAddonToDB($arrInsert){

			$catID = UniteFunctionsUC::getVal($arrInsert, "catid");
			UniteFunctionsUC::validateNotEmpty($catID, "category id");

			//set order
			$newOrder = $this->getLastOrderInCat($catID);
			$arrInsert["ordering"] = $newOrder;
			$arrInsert["is_active"] = 1;

			$newID = $this->db->insert(GlobalsUC::$table_addons, $arrInsert);

			$arrInsert["id"] = $newID;

			return($arrInsert);
		}


		/**
		 *
		 * add addon to database from data.
		 * return item id
		 */
		public function add($data){

			$arrInsert = $this->getCreateUpdateData($data);

			$arrInsert = $this->insertNewAddonToDB($arrInsert);

			$this->initByDBRecord($arrInsert);

			return($id);
		}


		/**
		 * add from small data, only name, alias and catid
		 */
		public function addSmall($title, $name, $description, $catID){

			$this->validateTitle($title);
			$this->validateName($name);

			UniteFunctionsUC::validateNumeric($catID, "Category ID");

			$arrInsert = array();
			$arrInsert["title"] = $title;
			$arrInsert["name"] = $name;

			$arrOptions = array();
			$arrOptions["description"] = $description;

			$arrConfig = array();
			$arrConfig["options"] = $arrOptions;
			$arrInsert["config"] = json_encode($arrConfig);

			$arrTemplates = array();
			$arrTemplates["html"] = "{$title} HTML";

			$strTemplates = json_encode($arrTemplates);

			$arrInsert["templates"] = $strTemplates;
			$arrInsert["catid"] = $catID;

			$arrInsert = $this->insertNewAddonToDB($arrInsert);

			$this->initByDBRecord($arrInsert);

			return($this->id);
		}


		/**
		 * update item data - media in db
		 */
		public function update($data){

			$this->validateInited();

			$arrUpdate = $this->getCreateUpdateData($data);

			$this->updateInDB($arrUpdate);
		}


		/**
		 * update name and title
		 */
		public function updateNameTitle($name, $title, $description){

			$this->validateInited();

			$this->validateName($name);
			$this->validateTitle($title);

			$arrUpdate = array();
			$arrUpdate["name"] = $name;
			$arrUpdate["title"] = $title;

			$this->options["description"] = $description;
			$this->config["options"] = $this->options;

			$arrUpdate["config"] = json_encode($this->config);

			$this->updateInDB($arrUpdate);

			$this->name = $name;
			$this->title = $title;
		}


		/**
		 * import addon by data
		 */
		public function importAddonData($data){

			$name = UniteFunctionsUC::getVal($data, "name");
			$isExists = $this->isAddonExistsByName($name);

			//add new
			if($isExists == false){

				$arrInsert = $this->insertNewAddonToDB($data);
				$data["id"] = $arrInsert["id"];
				$this->initByDBRecord($data);

			}else{		//overwrite existing

				$catID = UniteFunctionsUC::getVal($data, "catid");
				$this->initByName($name);

				//change ordering if moving to new category
				if($this->catid != $catID){
					$newOrder = $this->getLastOrderInCat($catID);
					$data["ordering"] = $newOrder;
				}

				$data["catid"] = $catID;
				$this->updateInDB($data);
			}

		}


		/**
		 * get new name
		 */
		private function getDuplicatedSuffix(){
			$suffixName = "_copy";
			$suffixTitle = " - copy";

			$name = $this->getName();
			$newName = $name.$suffixName;
			$isExists = $this->isAddonExistsByName($newName, true);

			$num = 1;
			while($isExists == true){
				$num++;
				$suffixName = "_copy".$num;
				$suffixTitle = " - copy".$num;
				$newName = $name.$suffixName;
				$isExists = $this->isAddonExistsByName($newName, true);
			}

			$output = array();
			$output["name"] = $suffixName;
			$output["title"] = $suffixTitle;

			return($output);
		}


		/**
		 *
		 * duplicate gallery
		 */
		public function duplicate(){

			$addons = new UniteCreatorAddons();

			$this->validateInited();

			//get new name and title
			$suffix = $this->getDuplicatedSuffix();

			$newTitle = $this->title.$suffix["title"];
			$newName = $this->name.$suffix["name"];

			$this->validateName($newName);

			$addons->shiftOrder($this->catid, $this->ordering);

			$newOrder = $this->ordering+1;

			//insert a new gallery
			$sqlSelect = "select ".self::FIELDS_ADDONS." from ".GlobalsUc::$table_addons." where id={$this->id}";
			$sqlInsert = "insert into ".GlobalsUC::$table_addons." (".self::FIELDS_ADDONS.") ($sqlSelect)";

			$this->db->runSql($sqlInsert);
			$lastID = $this->db->getLastInsertID();
			UniteFunctionsUC::validateNotEmpty($lastID);

			//update the new addon with the title and the name values
			$arrUpdate = array();
			$arrUpdate["title"] = $newTitle;
			$arrUpdate["name"] = $newName;
			$arrUpdate["ordering"] = $newOrder;

			$this->db->update(GlobalsUC::$table_addons, $arrUpdate, array("id"=>$lastID));

			return($lastID);
		}


		private function _____________TEST_SLOT______________(){}


		/**
		 * get test data
		 * @param $num
		 */
		public function getTestData($num){
			$arrData = array();

			$this->validateTestSlot($num);

			$fieldName = "test_slot".$num;
			$jsonData = UniteFunctionsUC::getVal($this->data, $fieldName);

			if(empty($jsonData))
				return(null);

			if(!empty($jsonData)){
				$arrData = @json_decode($jsonData);
				if(empty($arrData))
					$arrData = array();
			}

			$arrData = UniteFunctionsUC::convertStdClassToArray($arrData);

			return($arrData);
		}


		/**
		 * get all test data in array
		 */
		public function getAllTestData($isJson = false){
			$arrData = array();

			$testData1 = $this->getTestData(1);
			$testData2 = $this->getTestData(2);
			$testData3 = $this->getTestData(3);

			if(empty($testData1) && empty($testData2) && empty($testData3))
				return(null);

			$arrData["test_slot1"] = $testData1;
			$arrData["test_slot2"] = $testData2;
			$arrData["test_slot3"] = $testData3;

			if($isJson == true)
				return(json_encode($arrData));

			return($arrData);
		}


		/**
		 * get if some test data exists of some slot
		 * @param $num
		 */
		public function isTestDataExists($num){
			$arrData = $this->getTestData($num);
			if(!empty($arrData))
				return(true);
			else
				return(false);
		}


		/**
		 * save test slot, slot num can be 1,2,3
		 */
		public function saveTestSlotData($slotNum, $arrConfig, $arrItems){

			$this->validateInited();
			$this->validateTestSlot($slotNum);

			if(empty($items))
				$items = "";

			$data = array();
			$data["config"] = $arrConfig;
			$data["items"] = $arrItems;
			$dataJson = json_encode($data);

			$slotName = "test_slot".$slotNum;

			$arrUpdate = array();
			$arrUpdate[$slotName] = $dataJson;

			$this->updateInDB($arrUpdate);
		}


		/**
		 * clear the test data slot
		 */
		public function clearTestDataSlot($slotNum){
			$this->validateInited();
			$this->validateTestSlot($slotNum);

			$slotName = "test_slot".$slotNum;

			$arrUpdate = array();
			$arrUpdate[$slotName] = "";
			$this->updateInDB($arrUpdate);
		}



		/**
		 * set param values and items from some slot
		 */
		public function setValuesFromTestData($slotNum){
			$arrData = $this->getTestData($slotNum);

			if(empty($arrData))
				return(false);

			$config = UniteFunctionsUC::getVal($arrData, "config");
			$items = UniteFunctionsUC::getVal($arrData, "items");

			if(!empty($config))
				$this->setParamsValues($config);

			if(!empty($items))
				$this->setArrItems($items);

		}


	}

?>