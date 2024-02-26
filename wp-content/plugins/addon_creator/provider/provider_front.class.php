<?php

class UniteProviderFrontUC{
	
	private static $t;
	const ACTION_FOOTER_SCRIPTS = "wp_print_footer_scripts";
	const ACTION_AFTER_SETUP_THEME = "after_setup_theme";
	
	
	/**
	 *
	 * add some wordpress action
	 */
	protected static function addAction($action,$eventFunction){
	
		add_action( $action, array(self::$t, $eventFunction) );
	}
	
	
	/**
	 * add shortcodes of all active addons
	 */
	private function addShortcodes(){
		$objAddons = new UniteCreatorAddons();
		$arrAddons = $objAddons->getArrAddons();
		foreach($arrAddons as $addon){
			$shortcode = $addon->getName();
			UniteFunctionsWPUC::addShortcode($shortcode, "uc_run_shortcode");
		}
		
	}
	
	/**
	 * do all actions on theme setup
	 */
	public static function onThemeSetup(){
		
		UniteProviderFunctionsUC::integrateVisualComposer();
		
	}
	
	
	/**
	 *
	 * the constructor
	 */
	public function __construct(){
		self::$t = $this;
		
		self::addAction(self::ACTION_AFTER_SETUP_THEME, "onThemeSetup");
		self::addAction(self::ACTION_FOOTER_SCRIPTS, "onPrintFooterScripts");
		
		//$this->addShortcodes();
	}
	
	
	/**
	 * print footer scripts
	 */
	public static function onPrintFooterScripts(){
				
		$arrScrips = UniteProviderFunctionsUC::getCustomScripts();
		
		if(empty($arrScrips))
			return(true);
	
		echo "\n<!--   Addon Creator Scripts  --> \n";
		echo "<script type='text/javascript'>\n";
		foreach ($arrScrips as $script){
			echo $script."\n";
		}
		echo "</script>\n";
	
	}
	
	
		
}


?>