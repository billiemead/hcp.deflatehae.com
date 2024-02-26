<?php
/**
 * @package Addon Creator
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');

 
 class UniteBaseAdminClassUC{
 	
		
		protected static $master_view;
		protected static $view;
		
		private static $arrSettings = array();
		private static $tempVars = array();
		
		
		/**
		 * 
		 * main constructor		 
		 */
		public function __construct(){
						
			$this->initView();
			
			//self::addCommonScripts();
		}		
		
		/**
		 * 
		 * get path to settings file
		 * @param $settingsFile
		 */
		protected static function getSettingsFilePath($settingsFile){
			
			$filepath = self::$path_plugin."settings/$settingsFile.php";
			return($filepath);
		}
		
		
		/**
		 * 
		 * set the view from GET variables
		 */
		private function initView(){
			
			$defaultView = GlobalsUC::VIEW_DEFAULT;
			
			//set view
			$viewInput = UniteFunctionsUC::getGetVar("view");
			$page = UniteFunctionsUC::getGetVar("page");
			
			//get the view out of the page
			if(strpos($page,"_") !== false){
				$parts = explode("_", $page);
				$view = $parts[1];
			}
			
			if(!empty($viewInput))
				$view = $viewInput;
			
			if(empty($view)){
				$view = $defaultView;
			}
			
			self::$view = $view;
						
		}
		
		
		
		
		/**
		 * 
		 * set view that will be the master
		 */
		protected static function setMasterView($masterView){
			self::$master_view = $masterView;
		}
		
		/**
		 * 
		 * inlcude some view file
		 */
		protected static function requireView($view){
			try{
				
				
				//require master view file, and 
				if(!empty(self::$master_view) && !isset(self::$tempVars["is_masterView"]) ){
					$masterViewFilepath = GlobalsUC::$pathViews.self::$master_view.".php";
					
					UniteFunctionsUC::validateFilepath($masterViewFilepath,"Master View");
					
					self::$tempVars["is_masterView"] = true;
										
					require $masterViewFilepath;
										
				}
				else{		//simple require the view file.
					
					$viewFilepath = GlobalsUC::$pathViews.$view.".php";
					
					
					UniteFunctionsUC::validateFilepath($viewFilepath,"View");
										
					require $viewFilepath;
					
				}
				
			}catch (Exception $e){
				echo "<br><br>View ($view) Error: <b>".$e->getMessage()."</b>";
				
				if(GlobalsUC::SHOW_TRACE == true)
					dmp($e->getTraceAsString());
			}
		}
		
		
		/**
		 * 
		 * require settings file, the filename without .php
		 */
		protected static function requireSettings($settingsFile){
						
			try{
				require self::$path_plugin."settings/$settingsFile.php";
			}catch (Exception $e){
				echo "<br><br>Settings ($settingsFile) Error: <b>".$e->getMessage()."</b>";
				dmp($e->getTraceAsString());
			}
		}
		
		
		
		
		
				
		
		
				
		
		
		
		
 	
 	
 }
 
 ?>