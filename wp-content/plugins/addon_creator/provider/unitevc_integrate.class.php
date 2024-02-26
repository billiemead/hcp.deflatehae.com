<?php

/**
 * visual composer integration class
 *
 */
	class UniteVcIntegrateUC{
				
		public static function isVCExists(){
			
			if(function_exists('vc_map'))
				return(true);
			
			return(false);
		}
		
		
		/**
		 * check if visual composer in on current page
		 */
		public static function isVcOnAdminPage(){
			
			if(function_exists("vc_editor_post_types") == false)
				return(false);
						
			$arrVcPostTypes = vc_editor_post_types();
			$arrVcPostTypes[] = "templatera";
			
			if(in_array( get_post_type(), $arrVcPostTypes) )
				return(true);
			
			
			return(false);
		}
				
		
		
		
		/**
		 * convert params object to vc params object
		 */
		private function mapParams($arrParams){
			
			$vcParams = array();
			
			foreach($arrParams as $param){
				
				$vcParam = $param;
				$vcParam["param_name"] = $vcParam["name"];
				$vcParam["heading"] = $vcParam["title"];
				
				$vcParam["value"] = UniteFunctionsUC::getVal($vcParam, "default_value");
				
				//validation
				if(empty($vcParam["type"]))
					continue;
				
				if(empty($vcParam["param_name"]))
					continue;

				
				$vcParams[] = $vcParam;
			}
			
			
			return($vcParams);
		}
		
		
		/**
		 * map addon
		 */
		private function mapAddon($data){
			
			$name = UniteFunctionsUC::getVal($data, "name");
			$addonName = UniteFunctionsUC::getVal($data, "addon_name");
			$shortcode = "ucaddon_".$addonName;
			
			$vcFolder =  HelperUC::getGeneralSetting("vc_folder");
			if(empty($vcFolder))
				$vcFolder = "Addon Creator";
			
			//$category = UniteFunctionsUC::getVal($data, "category", $vcFolder);
			$category = $vcFolder;
			
			$description = UniteFunctionsUC::getVal($data, "description");
			$class = "uc-addon";
			$params = UniteFunctionsUC::getVal($data, "params", array());
			$params = $this->mapParams($params);
			
			$settings = array();
			$settings["name"] = __($name, UNITECREATOR_TEXTDOMAIN);
			$settings["base"] = $shortcode;
			$settings["addon_name"] = $addonName;
			
			$settings["class"] = $class;
			$settings["icon"] = UniteFunctionsUC::getVal($data, "icon");
			$settings["category"] = $category;
			$settings["description"] = __($description, UNITECREATOR_TEXTDOMAIN);
			$settings["params"] = $params;
			 
			$settings["content_element"] = true;
			$settings["controls"] = "full";
			$settings["show_settings_on_create"] = true;
			
			//$settings["as_parent"] = array('except' => $shortcode);
			//$settings["is_container"] = true;
			//$settings["js_view"] = "VcColumnView";
			
			
			//create new class (validate alpha numeric shortcode
			$isAlphaNumeric = UniteFunctionsUC::isAlphaNumeric($shortcode);
			if($isAlphaNumeric == false)
				return(false);
			
			$code = "class WPBakeryShortCode_{$shortcode} extends UCVCAddonBase{};";
			eval($code);
						
			//dmp($settings);
			//exit();
			vc_map($settings);
		}
		
		
		/**
		 * get addon params
		 * 
		 */
		private function getAddonParams(UniteCreatorAddon $addon){
			
			$params = $addon->getProcessedMainParams();
			
			if(empty($params))
				$params = array();
			
			$hasSettings = !empty($params);
			$hasItems = $addon->isHasItems();
			
			$addonName = $addon->getName();

			//add init setting param
			if($hasSettings == true){
				$paramInit = array();
				$paramInit["type"] = "uc_init_settings";
				$paramInit["title"] = "";
				$paramInit["name"] = "uc_init_settings";
				$paramInit["addon_name"] = $addonName;
				$params[] = $paramInit;
			}else{		//no settings - add static text
				
				$paramText = array();
				$paramText["type"] = "uc_statictext";
				$paramText["title"] = __("No Settings Available", UNITECREATOR_TEXTDOMAIN);
				$paramText["name"] = "uc_text_nosettings";
				$paramText["addon_name"] = $addonName;
				$params[] = $paramText;
			}
			
			
			//add items param
			if($hasItems == true){
				$paramItems = array();
				$paramItems["type"] = "uc_items";
				$paramItems["title"] = "";
				$paramItems["name"] = "uc_items_data";
				$paramItems["group"] = __("Items", UNITECREATOR_TEXTDOMAIN);
				$paramItems["addon_name"] = $addonName;
				
				$params[] = $paramItems;
			}
			
			
			return($params);
		}
		
		
		/**
		 * map addon from onject
		 */
		private function mapAddonFromObject(UniteCreatorAddon $addon){
			
			$catTitle = "Addon Creator";
			
			$data = array();
			$data["name"] = $addon->getTitle();
			$data["addon_name"] = $addon->getName();
			$data["category"] = $catTitle;
			$data["description"] = $addon->getDescription();
			$data["icon"] = $addon->getUrlIcon();
			
			$params = $this->getAddonParams($addon);
			
			$data["params"] = $params;
			
			$this->mapAddon($data);
		}
		
		
		/**
		 * create custom params
		 */
		private function createCustomParams(){
			UniteVCCustomParams::createCustomParams();
		}
		
		
		/**
		 * map all addons
		 */
		private function mapAllAddons(){
			
			$objAddons = new UniteCreatorAddons();
			
			$arrAddons = $objAddons->getCatAddons(null,false,"active");
			
			foreach($arrAddons as $addon)
				$this->mapAddonFromObject($addon);
			
		}

				
		
		/**
		 * init vc integration
		 */
		public function initVCIntegration(){
						
			if(self::isVCExists() == false)
				return(false);
						
			//include vc related files
			require_once GlobalsUC::$pathProvider . 'unitevc_addon_shortcode.class.php';
			require_once GlobalsUC::$pathProvider . 'unitevc_custom_params.class.php';
			
			//add_action( 'wp_ajax_wpb_show_edit_form', array( &$this, 'build' ) );
			
			$this->createCustomParams();
			$this->mapAllAddons();
			
		}
		
	}

?>