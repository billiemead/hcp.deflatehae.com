<?php


class UniteCreatorExporter extends HtmlOutputBaseUC{
	
	private $addon;
	private $objAddons;
	private $pathExport;
	private $filepathAddonZip;
	private $pathExportAddon;
	private $pathExportAddonAssets;
	
	private $pathImport;
	private $pathImportAddon;
	private $pathImportAddonAssets;
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		$this->objAddons = new UniteCreatorAddons();
	}
	
	
	/**
	 * validate that the addon exists
	 */
	private function validateInited(){
		if(empty($this->addon))
			UniteFunctionsUC::throwError("export error: addon not inited");
	}
	
	
	
	
	/**
	 * init by addon
	 * @param $addon
	 */
	public function initByAddon(UniteCreatorAddon $addon){
		
		$this->addon = $addon;
		
	}
	
	
	private function ______________EXPORT_ADDON___________(){}
	
	
	/**
	 * create export folder if not exists
	 */
	private function prepareExportFolders(){
	
		$pathCache = GlobalsUC::$path_cache;
	
		if(is_dir($pathCache) == false){
			@mkdir($pathCache);
			if(!is_dir($pathCache))
				UniteFunctionsUC::throwError("Cache path: {$pathCache} could not be created. Please check your permissions");
		}
	
		$pathExport = $pathCache."export/";
	
		if(is_dir($pathExport) == false){
			@mkdir($pathExport);
			if(!is_dir($pathExport))
				UniteFunctionsUC::throwError("Export path: {$pathExport} could not be created. Please check your permissions");
		}
	
		//clear folder
		UniteFunctionsUC::deleteDir($pathExport, false);
	
		$this->pathExport = $pathExport;
		$this->pathExportAddon = $pathExport."addon_".UniteFunctionsUC::getRandomString(10)."/";
		$this->pathExportAddonAssets = $this->pathExportAddon."assets/";
	
		//create index.html
		UniteFunctionsUC::writeFile("", $this->pathExport."index.html");
	
		mkdir($this->pathExportAddon);
		if(!is_dir($this->pathExportAddon))
			UniteFunctionsUC::throwError("Export addon path: {$this->pathExportAddon} could not be created. Please check your permissions");
	
		$addonName = $this->addon->getName();
	
		$tempFilenameZip = $addonName."_".UniteFunctionsUC::getRandomString(10).".zip";
	
		$this->filepathAddonZip = $this->pathExport.$tempFilenameZip;
	}
	
		
	
	/**
	 * modify includes in config that it will be saved in new way
	 */
	private function modifyConfig($config){
		
		$includes = UniteFunctionsUC::getVal($config, "includes");
		
		$arrJs = UniteFunctionsUC::getVal($includes, "js");
		$arrCss = UniteFunctionsUC::getVal($includes, "css");
		
		$arrJs = HelperUC::arrUrlsToRelative($arrJs, true);
		$arrCss = HelperUC::arrUrlsToRelative($arrCss, true);
		
		if(!empty($arrJs))
			$includes["js"] = $arrJs;
		
		if(!empty($arrCss))
			$includes["css"] = $arrCss;
		
		$config["includes"] = $includes;
		
		return($config);
	}
	
	
	
	/**
	 * get xml from addon
	 */
	private function getJsonFromAddon(){
		
		$arrAddon = array();
		$arrAddon["name"] = $this->addon->getName();;
		$arrAddon["title"] = $this->addon->getTitle();
		$arrAddon["description"] = $this->addon->getDescription();
		
		$arrConfig = $this->addon->getConfig();
		$arrAddon["config"] = $this->modifyConfig($arrConfig);
				
		
		$arrTemplates = $this->addon->getTemplates();
		
		$arrTemplateNames = array();
		foreach($arrTemplates as $name=>$content){
			if(!empty($content))
				$arrTemplateNames[] = $name;
		}
		
		$arrAddon["templates"] = $arrTemplateNames;
		
		$json = json_encode($arrAddon, JSON_PRETTY_PRINT);
		
		
		return($json);
	}
	
	
	/**
	 * write file if not empty content
	 */
	private function writeFile($filename, $content){
		
		$filepath = $this->pathExportAddon.$filename;
		if(!empty($content))
			UniteFunctionsUC::writeFile($content, $filepath);
	}
	
	
	/**
	 * write export data
	 */
	private function createExportFiles(){
	
		//write addon main file
		$strJson = $this->getJsonFromAddon();
		$this->writeFile("addon.json", $strJson);
		
		//write template files
		$arrTemplates = $this->addon->getTemplates();		
		
		//write templates
		foreach($arrTemplates as $name=>$content){
			$filename = $name.".tpl";
			$this->writeFile($filename, $content);
		}
		
		/*
		//write data
		$testData = $this->addon->getAllTestData(true);
		if(!empty($testData))
			$this->writeFile("data.json", $testData);
		*/
	}
	
	
	/**
	 * check if assets path is ready for export
	 */
	private function isPathAssetsReadyForExport($pathAssets){
		$isUnderAssets = HelperUC::isPathUnderAssetsPath($pathAssets);
		if(!$isUnderAssets)
			return(false);
		
		if(is_dir($pathAssets) == false)
			return(false);
		
		$isPathAssets = HelperUC::isPathAssets($pathAssets);
		if($isPathAssets == true)
			return(false);
		
		return(true);
	}
	
	
	/**
	 * copy addon assets
	 */
	private function copyAssets(){
		
		$options = $this->addon->getOptions();
		$dirAssets = $this->addon->getOption("path_assets");
		
		if(empty($dirAssets))
			return(false);
		
		$pathAssets = GlobalsUC::$pathAssets.$dirAssets.'/';
		
		$isReady = $this->isPathAssetsReadyForExport($pathAssets);
		if($isReady == false)
			return(false);
				
		//make assets folder
		@mkdir($this->pathExportAddonAssets);
		
		if(is_dir($this->pathExportAddonAssets) == false)
			UniteFunctionsUC::throwError("Can't create assets folder");
		
		$pathAssetsDest = $this->pathExportAddonAssets.$dirAssets."/";
		@mkdir($pathAssetsDest);
		
		if(is_dir($pathAssetsDest) == false)
			UniteFunctionsUC::throwError("Can't create export assets folder: $dirAssets");
		
		UniteFunctionsUC::copyDir($pathAssets, $pathAssetsDest);
				
	}
	
	
	/**
	 * make export zip file
	 */
	private function makeExportZipFile(){
		
		$zip = new UniteZipUC();
		$zip->makeZip($this->pathExportAddon, $this->filepathAddonZip);
		
		if(file_exists($this->filepathAddonZip) == false)
			UniteFunctionsUC::throwError("zip file {$this->filepathAddonZip} could not be created");
	}

	/**
	 * delete export addon folder
	 */
	private function deleteExportAddonFolder(){
		
		if(!empty($this->pathExportAddon) && is_dir($this->pathExportAddon))
			UniteFunctionsUC::deleteDir($this->pathExportAddon);
		
	}
	
	
	/**
	 * download the zip file
	 */
	private function downloadFile(){
		
		$addonName = $this->addon->getName();
		$filename = $addonName.".zip";
		
		UniteFunctionsUC::downloadFile($this->filepathAddonZip, $filename);
	}
	
		
	
	/**
	 * export addon - create export file and send it to download.
	 */
	public function export(){
		$this->validateInited();
		
		try{
			$this->prepareExportFolders();
			$this->createExportFiles();
			$this->copyAssets();
			$this->makeExportZipFile();
			$this->deleteExportAddonFolder();
			$this->downloadFile();
			exit();
			
		}catch(Exception $e){
			
			$this->deleteExportAddonFolder();
			
			throw $e;
		}
		
	}
	
	private function ______________IMOPORT_ADDON___________(){}
	
	/**
	 * validate that the temp file array
	 */
	private function validateArrTempFile($arrTempFile){
		
		$filename = UniteFunctionsUC::getVal($arrTempFile, "name");
		UniteFunctionsUC::validateNotEmpty($filename, "addon file name");
		
		$info = pathinfo($filename);
		$ext = UniteFunctionsUC::getVal($info, "extension");
		
		if($ext != "zip")
			UniteFunctionsUC::throwError("Wrong import addon file type: {$filename}, should be zip type only.");
		
	}
	
	
	/**
	 * prepare import addon folders
	 */
	private function prepareImportFolders(){
		
		//create cache folder
		$pathCache = GlobalsUC::$path_cache;
		
		if(is_dir($pathCache) == false){
			@mkdir($pathCache);
			if(!is_dir($pathCache))
				UniteFunctionsUC::throwError("Cache path: {$pathCache} could not be created. Please check your permissions");
		}
		
		//create import folder
		$pathImport = $pathCache."import/";
		
		if(is_dir($pathImport) == false){
			@mkdir($pathImport);
			if(!is_dir($pathImport))
				UniteFunctionsUC::throwError("Import path: {$pathImport} could not be created. Please check your permissions");
		}

		//clear folder
		UniteFunctionsUC::deleteDir($pathImport, false);
		
		$this->pathImport = $pathImport;
		$this->pathImportAddon = $pathImport."addon_".UniteFunctionsUC::getRandomString(10)."/";
		$this->pathImportAddonAssets = $this->pathImportAddon."assets/";
		
		//create index.html
		UniteFunctionsUC::writeFile("", $this->pathImport."index.html");

		mkdir($this->pathImportAddon);
		if(!is_dir($this->pathImportAddon))
			UniteFunctionsUC::throwError("Import addon path: {$this->pathImportAddon} could not be created. Please check your permissions");

	}
	
	
	/**
	 * delete import addon folder
	 */
	private function deleteImportAddonFolder(){
	
		if(!empty($this->pathImportAddon) && is_dir($this->pathImportAddon))
			UniteFunctionsUC::deleteDir($this->pathImportAddon);
	}
	
	
	/**
	 * unpack import addon from temp file
	 */
	function extractImportAddonFile($arrTempFile){
		
		$filepath = UniteFunctionsUC::getVal($arrTempFile, "tmp_name");
		
		$zip = new UniteZipUC();
		$extracted = $zip->extract($filepath, $this->pathImportAddon);
		
		if($extracted == false)
			UniteFunctionsUC::throwError("The import addon zip didn't extracted");
		
	}
	
	
	
	
	/**
	 * import templates
	 */
	private function importAddonData_addTemplates($arrImport, $addonData){
		
		//prepare templates data
		$templateNames = UniteFunctionsUC::getVal($arrImport, "templates");
		
		$arrTemplates = array();
		foreach($templateNames as $templateName){
		
			$filenameTemplate = $templateName.".tpl";
			$filepathTemplate = $this->pathImportAddon.$filenameTemplate;
			if(is_file($filepathTemplate) == false)
				UniteFunctionsUC::throwError("Template {$filenameTemplate} not found!");
		
			$templateContent = file_get_contents($filepathTemplate);
			$arrTemplates[$templateName] = $templateContent;
		}
		
		$addonData["templates"] = json_encode($arrTemplates);
		
		return($addonData);
	}

	
	/**
	 * add test data
	 */
	private function importAddonData_addTestData($addonData){
		
		$filenameTestData = "data.json";
		$filepathTestData = $this->pathImportAddon.$filenameTestData;
		
		if(file_exists($filepathTestData) == false)
			return($addonData);
		
		$testContent = file_get_contents($filepathTestData);
		if(empty($testContent))
			return($addonData);
		
		$testContent = @json_decode($testContent);
		
		if(empty($testContent))
			return(false);
		
		$testContent = UniteFunctionsUC::convertStdClassToArray($testContent);
		
		foreach($testContent as $key=>$arrContent){
			if(empty($arrContent))
				continue;
			
			$jsonContent = $arrContent;
			if(is_array($jsonContent))
				$jsonContent = json_encode($jsonContent);
			
			$addonData[$key] = $jsonContent;
		}
		
		return($addonData);
	}
	
	
	/**
	 * import addon data
	 */
	private function importAddonData($catID, $overwrite = true){
		
		$filenameAddon = "addon.json";
		$filepathData = $this->pathImportAddon.$filenameAddon;
		
		if(is_file($filepathData) == false)
			UniteFunctionsUC::throwError("Addon import file: $filenameAddon don't found");
		
		$contents = file_get_contents($filepathData);
		
		if(empty($contents))
			UniteFunctionsUC::throwError("Empty import file {$filenameAddon} contents");
		
		$arrImport = @json_decode($contents);
		
		if(empty($arrImport))
			UniteFunctionsUC::throwError("Wrong import file {$filenameAddon} content");
			
		$arrImport = UniteFunctionsUC::convertStdClassToArray($arrImport);
		
		if(is_array($arrImport) == false)
			UniteFunctionsUC::throwError("Wrong addon import data, should be array");
		
		$addonName = UniteFunctionsUC::getVal($arrImport, "name");
		
		
		//check if the addon exists, if needed
		if($overwrite == false){
			$isExists = $this->objAddons->isAddonExistsByName($addonName);
			if($isExists == true)
				return(false);
		}
		
		
		//prepare data
		$addonData = array();
		$addonData["name"] = $addonName;
		$addonData["title"] = UniteFunctionsUC::getVal($arrImport, "title");
		$addonData["description"] = UniteFunctionsUC::getVal($arrImport, "description");
		$addonData["catid"] = $catID;
		
		$config = UniteFunctionsUC::getVal($arrImport, "config");
		if(is_array($config) == false)
			UniteFunctionsUC::throwError("Wrong addon config data");
			
		$addonData["config"] = json_encode($config);
		
		//---- import templates ---- 
		
		$addonData = $this->importAddonData_addTemplates($arrImport, $addonData);
		
		
		// ------ import test data ----------
		
		$addonData = $this->importAddonData_addTestData($addonData);
		
		
		$objAddon = new UniteCreatorAddon();
		$objAddon->importAddonData($addonData);
		
		return(true);
	}
	
	
	/**
	 * copy import assets folder
	 */
	private function copyImportAssetsFolder(){
		
		if(is_dir($this->pathImportAddonAssets) == false)
			return(false);
		
		UniteFunctionsUC::copyDir($this->pathImportAddonAssets, GlobalsUC::$pathAssets);
		
	}
	
	
	/**
	 * import addon
	 * tempFile can be array or filepath
	 */
	public function import($catID, $arrTempFile, $overwrite = true){
		
		if(empty($catID))
			$catID = 0;
		
		//crate array from filepath
		if(getType($arrTempFile) == "string"){
			$filepath = $arrTempFile;
			$arrInfo = pathinfo($filepath);
			$filename = UniteFunctionsUC::getVal($arrInfo, "basename");
			
			$arrTempFile = array();
			$arrTempFile["tmp_name"] = $filepath;
			$arrTempFile["name"] = $filename;
		}
		
		$this->validateArrTempFile($arrTempFile);
		
		try{
			
			$this->prepareImportFolders();
			$this->extractImportAddonFile($arrTempFile);
			$isImported = $this->importAddonData($catID, $overwrite);
			if($isImported == true)
				$this->copyImportAssetsFolder();
			
			$this->deleteImportAddonFolder();
			
		}catch(Exception $e){
			
			$this->deleteImportAddonFolder();
		
			throw $e;
		}
		
		
	}
	
	private function ______________BULK_IMPORT___________(){}
	
	
	
	/**
	 * import all addons from folder
	 */
	public function importAddonsFromFolder($path){
		
		if(is_dir($path) == false)
			return(false);
		
		$arrFiles = UniteFunctionsUC::getFileListTree($path, "zip");
		
		try{
		
			foreach($arrFiles as $filepath){
				if(file_exists($filepath) == false)
					continue;
				
				$this->import(null, $filepath, false);
			}
			
		}catch(Exception $e){
			
			//HelperHtmlUC::outputException($e);
			//exit();
		}
		
	}
	
	
	
}