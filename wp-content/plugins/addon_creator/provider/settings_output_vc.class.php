<?php
/**
 * @package Addon Creator
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */

defined('_JEXEC') or die('Restricted access');



/**
 * settings output for visual composer
 */

	class UniteSettingsOutputVC_UC extends UniteSettingsOutputUC{
				

		public function __construct(){
			
			$this->isParent = true;
			
		}
		
		
		/**
		 * get setting html by name name
		 */
		public function VCgetSettingHtmlByName($name){
			
			$this->validateInited();
			$setting = $this->settings->getSettingByName($name);
			
			
			$type = $setting["type"];
			switch($type){
				case UniteSettingsUC::TYPE_STATIC_TEXT:
					return("");
				break;
			}
			
			
			ob_start();
			?>
			<div id="uc_vc_setting_wrapper_<?php echo $name?>" class="unite-settings unite_settings_wide unite-inputs">
			<?php 
			
			$this->drawInputs($setting);
			$this->drawInputAdditions($setting);
			
			?>
			</div>
			<?php
			
			$contents = ob_get_contents();
			ob_clean();
			
			return($contents);
		}
		
		
	}