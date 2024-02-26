<?php 

	class UniteCreatorProviderMasterView{
		
		/**
		 * construct
		 */
		public function __construct(){
			
			$this->putHtml();
			
		}
		
		
		/**
		 * put image select dialog
		 */
		private function putImageSelectDialog(){
			
			$objAssets = new UniteCreatorAssetsWork();
			$objAssets->initByKey("image_browser");
			$objAssets->setOption(UniteCreatorAssets::OPTION_ID, "uc_dialogimage_browser");
			
			?>
			
			<div id="uc_dialog_image_select" class="uc-dialog-image-select unite-inputs" style="display:none"> 
				
				<div class="uc-dialog-image-select-inner">
					
					<?php $objAssets->putHTML(null, true);?>
									
				</div>
				
				<div class="uc-dialog-image-select-bottom">
					
					<?php _e("Selected Image: ", UNITECREATOR_TEXTDOMAIN)?>
					
					<input id="uc_dialog_image_select_url" type="text" readonly class="unite-input-regular"  value="">
					
					<div class="vert_sap10"></div>
					
					<a id="uc_dialog_image_select_button" href="javascript:void(0)" class="unite-button-secondary"><?php _e("Select Image",UNITECREATOR_TEXTDOMAIN)?></a>
				
				</div>
				
			</div>
			
			<?php 
		}
		
		
		/**
		 * put html
		 */
		private function putHtml(){
			
			$this->putImageSelectDialog();
			
		}
		
	}

	$uc_providerMasterView = new UniteCreatorProviderMasterView();
	
?>