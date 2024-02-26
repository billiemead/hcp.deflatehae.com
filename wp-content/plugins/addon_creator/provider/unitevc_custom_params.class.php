<?php
	
	class UniteVCCustomParams{
		
		
		/**
		 * decode content
		 */
		public static function decodeContent($content){
			
			if(empty($content))
				return($content);
			
			$content = rawurldecode(base64_decode($content));
			
			$arr = @json_decode($content);
			$arr = UniteFunctionsUC::convertStdClassToArray($arr);
			
			return $arr;
		}
		
		
		/**
		 * add special param
		 */
		public static function addSpecialParam($param, $value){
			
			try{
				$name = UniteFunctionsUC::getVal($param, "name");
				
				$settings = new UniteCreatorSettings();
				$settings->addByCreatorParam($param, $value);
				
				$output = new UniteSettingsOutputVC_UC();
				$output->init($settings);
				
				$html = $output->VCgetSettingHtmlByName($name);
				
				return($html);
				
			}catch(Exception $e){
				HelperHtmlUC::outputException($e);
			}
							
		}
		
		
		/**
		 * add items editor
		 */
		public static function addItemsEditor($param, $value){
			
			try{
				
				$arrItems = self::decodeContent($value);
				
				$paramName = UniteFunctionsUC::getVal($param, "name");
				
				$name = UniteFunctionsUC::getVal($param, "addon_name");
				
				$addon = new UniteCreatorAddon();
				$addon->initByName($name);
				$addon->setArrItems($arrItems);
				
				ob_start();

					//put debug and errors messages divs
					$globalDivs = HelperHtmlUC::getGlobalDebugDivs();
					echo $globalDivs;
					
					//put the items manager
					$objManager = new UniteCreatorManagerInline();
					$objManager->setStartAddon($addon);
					$objManager->outputHtml();
					
					//put init items function
					?>
					
					<input type="hidden" name="<?php echo $paramName?>" class="wpb_vc_param_value">
					
					<script type="text/javascript">
						
						g_ucGeneralSettings.initVCItems();
						
					</script>
					<?php
				
				$contents = ob_get_contents();
				ob_clean();
				
				return $contents;
				
			}catch(Exception $e){
				HelperHtmlUC::outputException($e);
			}
		}
		
		
		/**
		 * add init settings param, 
		 * param that inits the settings that being output by visual composer
		 */
		public static function addInitSettingsParam(){
			
			
			ob_start();
			
			?>
			
			<span id="unite_settings_init_base"></span>
			
			<script type="text/javascript">
				
				var ucObjSettingsInnerDiv = jQuery("#unite_settings_init_base");
				g_ucGeneralSettings.initVCSettings(ucObjSettingsInnerDiv);
				
			</script>
			<?php 
			
			$contents = ob_get_contents();
			
			ob_clean();
			
			return $contents;
			
		}
		
		
		
		/**
		 * generate vc dependency
		 */
		private static function generateDependency($settings){
			
			$dependency = vc_generate_dependencies_attributes($settings);
			
			return($dependency);
		}
		
		
		/**
		 * add param
		 */
		private static function addParam($type, $functionName){
			
			if(function_exists("vc_add_shortcode_param"))
				vc_add_shortcode_param($type , array("UniteVCCustomParams", $functionName ));
			else
				add_shortcode_param($type , array("UniteVCCustomParams", $functionName ));
		}
		
		
		/**
		 * create all custom params
		 */
		public static function createCustomParams(){
			
			if(function_exists("add_shortcode_param") == false && function_exists("vc_add_shortcode_param") == false)
				return(false);
			
			$objSettings = new UniteCreatorSettings();
			$arrParamTypes = $objSettings->getArrUCSettingTypes();
			
			//add simple param
			foreach($arrParamTypes as $type){
					self::addParam($type, "addSpecialParam");
			}
			
			//add items params
			self::addParam("uc_items", "addItemsEditor");
			self::addParam("uc_init_settings","addInitSettingsParam");
		}
		
		
		
	}

?>