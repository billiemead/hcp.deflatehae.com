<?php

defined('_JEXEC') or die('Restricted access');

   class UniteProviderAdminUC extends UniteCreatorAdmin{
   	
	   	private static $arrMenuPages = array();
	   	private static $arrSubMenuPages = array();
	   	private static $capability = "manage_options";
	   	
   		private $mainFilepath;
   		
	   	private static $t;
	   	
	   	const ACTION_ADMIN_MENU = "admin_menu";
	   	const ACTION_ADMIN_INIT = "admin_init";
	   	const ACTION_ADD_SCRIPTS = "admin_enqueue_scripts";
   		const ACTION_AFTER_SETUP_THEME = "after_setup_theme";
   		const ACTION_PRINT_SCRIPT = "admin_print_footer_scripts";
   		const ACTION_AFTER_SWITCH_THEME = "after_switch_theme";
   		
   		
		/**
		 *
		 * the constructor
		 */
		public function __construct($mainFilepath){
			self::$t = $this;
			
			$this->mainFilepath = $mainFilepath;
			
			parent::__construct();
						
			$this->init();
		}		

		
		

		/**
		 * process activate event - install the db (with delta).
		 */
		public static function onActivate(){
			
			self::createTables();
			
			self::importCurrentThemeAddons();
			
		}


		/**
		 * after switch theme
		 */
		public static function afterSwitchTheme(){
			
			self::importCurrentThemeAddons();
		}
		
		
		/**
		 * do all actions on theme setup
		 */
		public static function onThemeSetup(){
			UniteProviderFunctionsUC::integrateVisualComposer();
		}

		
		/**
		 *
		 * create the tables if not exists
		 */
		public static function createTables(){
			
			self::createTable(GlobalsUC::TABLE_ADDONS_NAME);
			self::createTable(GlobalsUC::TABLE_CATEGORIES_NAME);
		}
		
		
		/**
		 *
		 * craete tables
		 */
		public static function createTable($tableName){
		
			global $wpdb;
						
			//if table exists - don't create it.
			$tableRealName = $wpdb->prefix.$tableName;
			if(UniteFunctionsWPUC::isDBTableExists($tableRealName))
				return(false);
			
			$charset_collate = $wpdb->get_charset_collate();
			
			switch($tableName){
				
				case GlobalsUC::TABLE_CATEGORIES_NAME:
					
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					title varchar(255) NOT NULL,
					alias varchar(255),
					ordering int not NULL,
					params text NOT NULL,
					type tinytext,
					parent_id int(9),
					PRIMARY KEY (id)
					)$charset_collate;";
					break;
				
				case GlobalsUC::TABLE_ADDONS_NAME:
					$sql = "CREATE TABLE " .$tableRealName ." (
					id int(9) NOT NULL AUTO_INCREMENT,
					title varchar(255),
					name varchar(128),
					description text,
					ordering int not NULL,
					templates text,
					config text,
					catid int,
					is_active tinyint,
					test_slot1 text,	
					test_slot2 text,	
					test_slot3 text,
					PRIMARY KEY (id)
					)$charset_collate;";
					break;
				default:
					UniteFunctionsMeg::throwError("table: $tableName not found");
				break;
			}
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
		
		/**
		 *
		 * add ajax back end callback, on some action to some function.
		 */
		protected static function addActionAjax($ajaxAction, $eventFunction){
			self::addAction('wp_ajax_'.GlobalsUC::PLUGIN_NAME."_".$ajaxAction, $eventFunction);
			self::addAction('wp_ajax_nopriv_'.GlobalsUC::PLUGIN_NAME."_".$ajaxAction, $eventFunction);
		}
		
		
		/**
		 *
		 * register the "onActivate" event
		 */
		protected function addEvent_onActivate($eventFunc = "onActivate"){
			
			register_activation_hook( $this->mainFilepath, array(self::$t, $eventFunc) );
		}
		
		
		/**
		 *
		 * add menu page
		 */
		protected static function addMenuPage($title,$pageFunctionName,$icon=null){
			self::$arrMenuPages[] = array("title"=>$title,"pageFunction"=>$pageFunctionName,"icon"=>$icon);
		}
		
		/**
		 *
		 * add sub menu page
		 */
		protected static function addSubMenuPage($slug,$title,$pageFunctionName){
			self::$arrSubMenuPages[] = array("slug"=>$slug,"title"=>$title,"pageFunction"=>$pageFunctionName);
		}
		
		/**
		 * add admin menus from the list.
		 */
		public static function addAdminMenu(){
						
			//return(false);
			foreach(self::$arrMenuPages as $menu){
				$title = $menu["title"];
				$pageFunctionName = $menu["pageFunction"];
				$icon = UniteFunctionsUC::getVal($menu, "icon");
				
				add_menu_page( $title, $title, self::$capability, GlobalsUC::PLUGIN_NAME, array(self::$t, $pageFunctionName), $icon );
			}
		
			foreach(self::$arrSubMenuPages as $key=>$submenu){
		
				$title = $submenu["title"];
				$pageFunctionName = $submenu["pageFunction"];
		
				$slug = GlobalsUC::PLUGIN_NAME."_".$submenu["slug"];
		
				if($key == 0)
					$slug = GlobalsUC::PLUGIN_NAME;
		
				add_submenu_page(GlobalsUC::PLUGIN_NAME, $title, $title, 'manage_options', $slug, array(self::$t, $pageFunctionName) );
			}
		
		}
		
		
		/**
		 *
		 * tells if the the current plugin opened is this plugin or not
		 * in the admin side.
		 */
		private function isInsidePlugin(){
			$page = UniteFunctionsUC::getGetVar("page");
		
			if($page == GlobalsUC::PLUGIN_NAME || strpos($page, GlobalsUC::PLUGIN_NAME."_") !== false)
				return(true);
		
			return(false);
		}
		
				
		/**
		 *
		 * add some wordpress action
		 */
		protected static function addAction($action,$eventFunction){
		
			add_action( $action, array(self::$t, $eventFunction) );
		}
		
		
		/**
		 *
		 * validate admin permissions, if no pemissions - exit
		 */
		protected static function validateAdminPermissions(){
			
			if(UniteFunctionsWPUC::isAdminPermissions() == false){
				echo "access denied, no admin permissions";
				return(false);
			}
			
		}

		
		/**
		 *
		 * admin main page function.
		 */
		public static function adminPages(){
			
			self::createTables();
						
			parent::adminPages();
			
		}
		
		
		/**
		 * put scripts on visual composer pages
		 */
		public static function onVCPagesScripts(){
			
			UniteCreatorAdmin::addScripts_settingsBase("nojqueryui");
			
			$globalJsOutput = HelperHtmlUC::getGlobalJsOutput();
			UniteProviderFunctionsUC::printCustomScript($globalJsOutput);
			
			HelperUC::addScriptAbsoluteUrl(GlobalsUC::$url_provider."assets/uc_general_settings.js", "uc_general_settings");
			HelperUC::addStyleAbsoluteUrl( GlobalsUC::$url_provider."assets/jquery-ui-custom-vc.css", "jquery-ui-custom-vc");
			
		}
		
		
		
		/**
		 * add outside plugin scripts
		 */
		public static function onAddOutsideScripts(){
			
			//add outside scripts, only on posts or pages page
			$isPostsPage = UniteFunctionsWPUC::isAdminPostsPage();
			
			if($isPostsPage == false)
				return(false);
			
			$isVCPage = UniteVcIntegrateUC::isVcOnAdminPage();
			
			if($isVCPage == false)
				return(false);

			self::onVCPagesScripts();
		}

		
		/**
		 * print custom scripts
		 */
		public static function onPrintFooterScripts(){
			
			
			//print inline html
			$arrHtml = UniteProviderFunctionsUC::getInlineHtml();
			if(!empty($arrHtml)){
				foreach($arrHtml as $html){
					echo $html;
				}
			}
			
			
			//print custom script
			$arrScrips = UniteProviderFunctionsUC::getCustomScripts();
			if(!empty($arrScrips)){
				echo "<script type='text/javascript'>\n";
				foreach ($arrScrips as $script){
					echo $script."\n";
				}
				echo "</script>";
			}
			
		}
		
		
		/**
		 * import current theme addons
		 */
		public static function importCurrentThemeAddons(){
			
			$pathCurrentTheme = get_template_directory()."/";
			
			$dirAddons = apply_filters("uc_path_theme_addons", GlobalsUC::DIR_THEME_ADDONS);
			
			$pathAddons = $pathCurrentTheme.$dirAddons."/";
			
			if(is_dir($pathAddons) == false)
				return(false);
			
			$exporter = new UniteCreatorExporter();
			$exporter->importAddonsFromFolder($pathAddons);
		
		}
		
		
		
		/**
		 * 
		 * init function
		 */
		protected function init(){
			
			parent::init();
			
			//set permission:
			
			//HelperUC::printGeneralSettings();
			
			$permission = HelperUC::getGeneralSetting("edit_permission");
			if($permission == "editor")
				self::$capability = "edit_posts";
			
			$urlMenuIcon = GlobalsUC::$url_provider."assets/images/icon_menu.png";
			
			self::addMenuPage('Addon Creator', "adminPages", $urlMenuIcon);
			self::addSubMenuPage("addons", __('Addons',UNITECREATOR_TEXTDOMAIN), "adminPages");
			self::addSubMenuPage("assets", __('Assets Manager',UNITECREATOR_TEXTDOMAIN), "adminPages");
			self::addSubMenuPage("settings", __('General Settings',UNITECREATOR_TEXTDOMAIN), "adminPages");

			//add internal hook for adding a menu in arrMenus
			self::addAction(self::ACTION_ADMIN_MENU, "addAdminMenu");
			
			//if not inside plugin don't continue
			if($this->isInsidePlugin() == true){
				self::addAction(self::ACTION_ADD_SCRIPTS, "onAddScripts");
			}else{				
				self::addAction(self::ACTION_ADD_SCRIPTS, "onAddOutsideScripts");
			}
			
			self::addAction(self::ACTION_PRINT_SCRIPT, "onPrintFooterScripts");
			
			self::addAction(self::ACTION_AFTER_SETUP_THEME, "onThemeSetup");
			
			self::addAction(self::ACTION_AFTER_SWITCH_THEME, "afterSwitchTheme");
			
			$this->addEvent_onActivate();
			
			self::addActionAjax("ajax_action", "onAjaxAction");
			
			
		}

		
		
	}

?>