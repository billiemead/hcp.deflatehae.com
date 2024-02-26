<?php

class UniteCreatorManagerAddons extends UniteCreatorManager{
	
	private $filterActive = "";
	
	
	/**
	 * construct the manager
	 */
	public function __construct(){
		$this->type = self::TYPE_ADDONS;
	
		$this->init();
	}
	
	
	/**
	 * get single item menu
	 */
	protected function getMenuSingleItem(){
		
		$arrMenuItem = array();
		$arrMenuItem["edit_addon"] = __("Edit Addon",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["edit_addon_blank"] = __("Edit In New Tab",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["quick_edit"] = __("Quick Edit",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["remove_item"] = __("Delete",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["duplicate"] = __("Duplicate",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["test_addon"] = __("Test Addon",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["test_addon_blank"] = __("Test In New Tab",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["export_addon"] = __("Export Addon",UNITECREATOR_TEXTDOMAIN);
		
		return($arrMenuItem);
	}

	
	/**
	 * get item field menu
	 */
	protected function getMenuField(){
		$arrMenuField = array();
		$arrMenuField["add_addon"] = __("Add Addon",UNITECREATOR_TEXTDOMAIN);
		$arrMenuField["select_all"] = __("Select All",UNITECREATOR_TEXTDOMAIN);
		
		return($arrMenuField);
	}

	
	/**
	 * get multiple items menu
	 */
	protected function getMenuMulitipleItems(){
		$arrMenuItemMultiple = array();
		$arrMenuItemMultiple["delete"] = __("Delete",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItemMultiple["duplicate"] = __("Duplicate",UNITECREATOR_TEXTDOMAIN);
		return($arrMenuItemMultiple);
	}
	
	
	/**
	 * get category menu
	 */
	protected function getMenuCategory(){
	
		$arrMenuCat = array();
		$arrMenuCat["edit_category"] = __("Edit Category",UNITECREATOR_TEXTDOMAIN);
		$arrMenuCat["delete_category"] = __("Delete Category",UNITECREATOR_TEXTDOMAIN);
		$arrMenuCat["export_cat_addons"] = __("Export Addons",UNITECREATOR_TEXTDOMAIN);
		$arrMenuCat["import_cat_addons"] = __("Import Addons",UNITECREATOR_TEXTDOMAIN);
	
		return($arrMenuCat);
	}
	
	
	/**
	 * get no items text
	 */
	protected function getNoItemsText(){
		if($this->hasCats == true)
			$text = __("Empty Category", UNITECREATOR_TEXTDOMAIN);
		else
			$text = __("No Addons Found", UNITECREATOR_TEXTDOMAIN);
			
		return($text);
	}
	
	
	/**
	 * put items buttons
	 */
	protected function putItemsButtons(){
		?>
 			<a data-action="add_addon" type="button" class="unite-button-secondary unite-button-blue button-disabled uc-button-item uc-button-add"><?php _e("Add Addon",UNITECREATOR_TEXTDOMAIN)?></a>
 			<a data-action="import_addon" type="button" class="unite-button-secondary unite-button-blue button-disabled uc-button-item uc-button-add"><?php _e("Import Addon",UNITECREATOR_TEXTDOMAIN)?></a>
 			<a data-action="select_all_items" type="button" class="unite-button-secondary button-disabled uc-button-item uc-button-select" data-textselect="<?php _e("Select All",UNITECREATOR_TEXTDOMAIN)?>" data-textunselect="<?php _e("Unselect All",UNITECREATOR_TEXTDOMAIN)?>"><?php _e("Select All",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="duplicate_item" type="button" class="unite-button-secondary button-disabled uc-button-item"><?php _e("Duplicate",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="remove_item" type="button" class="unite-button-secondary button-disabled uc-button-item"><?php _e("Delete",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="edit_addon" type="button" class="unite-button-primary button-disabled uc-button-item uc-single-item"><?php _e("Edit Addon",UNITECREATOR_TEXTDOMAIN)?> </a>
	 		<a data-action="quick_edit" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php _e("Quick Edit",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="test_addon" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php _e("Test Addon",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="export_addon" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php _e("Export Addon",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="activate_addons" type="button" class="unite-button-secondary button-disabled uc-button-item uc-notactive-item"><?php _e("Activate",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="deactivate_addons" type="button" class="unite-button-secondary button-disabled uc-button-item uc-active-item"><?php _e("Deactivate",UNITECREATOR_TEXTDOMAIN)?></a>
		<?php
	}
	
	
	/**
	 * put filters - function for override
	 */
	protected function putItemsFilters(){
		$classActive = "class='uc-active'";
		$filter = $this->filterActive;
		if(empty($filter))
			$filter = "all";
		
		?>
		
		<div class="uc-items-filters">
			
			<div class="uc-filters-set-title"><?php _e("Show Addons", UNITECREATOR_TEXTDOMAIN)?>:</div>
			
			<div id="uc_filters_active" class="uc-filters-set">
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="all" <?php echo ($filter == "all")?$classActive:""?> ><?php _e("All", UNITECREATOR_TEXTDOMAIN)?></a>
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="active" <?php echo ($filter == "active")?$classActive:""?> ><?php _e("Active", UNITECREATOR_TEXTDOMAIN)?></a>
				<a href="javascript:void(0)" onfocus="this.blur()" data-filter="not_active" <?php echo ($filter == "not_active")?$classActive:""?> ><?php _e("Not Active", UNITECREATOR_TEXTDOMAIN)?></a>
			</div>
			
			<div class="unite-clear"></div>
		</div>
		
		<?php 
	}
	
	
	/**
	 * put quick edit dialog
	 */
	private function putDialogQuickEdit(){
		?>
			<!-- dialog quick edit -->
		
			<div id="dialog_edit_item_title"  title="<?php _e("Quick Edit",UNITECREATOR_TEXTDOMAIN)?>" style="display:none;">
			
				<div class="dialog_edit_title_inner unite-inputs mtop_20 mbottom_20" >
			
					<div class="unite-inputs-label-inline">
						<?php _e("Title", UNITECREATOR_TEXTDOMAIN)?>:
					</div>
					<input type="text" id="dialog_quick_edit_title" class="unite-input-wide">
					
					<div class="unite-inputs-sap"></div>
							
					<div class="unite-inputs-label-inline">
						<?php _e("Name", UNITECREATOR_TEXTDOMAIN)?>:
					</div>
					<input type="text" id="dialog_quick_edit_name" class="unite-input-wide">
					
					<div class="unite-inputs-sap"></div>
					
					<div class="unite-inputs-label-inline">
						<?php _e("Description", UNITECREATOR_TEXTDOMAIN)?>:
					</div>
					
					<textarea class="unite-input-wide" id="dialog_quick_edit_description"></textarea>
					
				</div>
				
			</div>
		
		<?php 
	}


	/**
	 * put import addons dialog
	 */
	private function putDialogImportAddons(){
		?>
		
			<div id="dialog_import_addons"  title="<?php _e("Import Addon",UNITECREATOR_TEXTDOMAIN)?>" style="display:none;">
				
				<div class="unite-dialog-top"></div>
				
				<div class="unite-inputs-label">
					<?php _e("Select addon export file", UNITECREATOR_TEXTDOMAIN)?>:
				</div>
				
				<div class="unite-inputs-sap-small"></div>
				
				<form id="dialog_import_addons_form" name="form_import_addon">
					<input id="dialog_import_addons_file" type="file" name="import_addon">
				</form>	
				
				<?php 
					$prefix = "dialog_import_addons";
					$buttonTitle = __("Import Addon", UNITECREATOR_TEXTDOMAIN);
					$loaderTitle = __("Uploading addon file...", UNITECREATOR_TEXTDOMAIN);
					$successTitle = __("Addon Added Successfully", UNITECREATOR_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>
				
					
			</div>		
		<?php 
	}
	
	
	/**
	 * put add addon dialog
	 */
	private function putDialogAddAddon(){
		?>
			<!-- add addon dialog -->
			
			<div id="dialog_add_addon" class="unite-inputs" title="<?php _e("Add Addon",UNITECREATOR_TEXTDOMAIN)?>" style="display:none;">
			
				<div class="unite-dialog-top"></div>
			
				<div class="unite-inputs-label">
					<?php _e("Addon Title", UNITECREATOR_TEXTDOMAIN)?>:
				</div>
				
				<input type="text" id="dialog_add_addon_title" class="dialog_addon_input unite-input-regular" />
				
				<div class="unite-inputs-sap"></div>
				
				<div class="unite-inputs-label">
					<?php _e("Addon Name")?>:
				</div>
				
				<input type="text" id="dialog_add_addon_name" class="dialog_addon_input unite-input-alias" />
				
				<div class="unite-inputs-sap"></div>
				
				<div class="unite-inputs-label">
					<?php _e("Addon Description")?>:
				</div>
				
				<textarea id="dialog_add_addon_description" class="dialog_addon_input unite-input-regular"></textarea>
				
				<?php 
					$prefix = "dialog_add_addon";
					$buttonTitle = __("Add Addon", UNITECREATOR_TEXTDOMAIN);
					$loaderTitle = __("Adding Addon...", UNITECREATOR_TEXTDOMAIN);
					$successTitle = __("Addon Added Successfully", UNITECREATOR_TEXTDOMAIN);
					HelperHtmlUC::putDialogActions($prefix, $buttonTitle, $loaderTitle, $successTitle);
				?>			
				
			</div>
		
		<?php 
	}	
	

	/**
	 * put scripts
	 */
	private function putScripts(){
	
		$script = "
			jQuery(document).ready(function(){
				var selectedCatID = \"{$this->selectedCategory}\";
				var managerAdmin = new UCManagerAdmin();
				managerAdmin.initManager(selectedCatID);
			});
		";
	
		UniteProviderFunctionsUC::printCustomScript($script);
	}
	
	
	/**
	 * put additional html here
	 */
	protected function putAddHtml(){
		$this->putDialogQuickEdit();
		$this->putDialogAddAddon();
		$this->putDialogImportAddons();
		$this->putScripts();
	}
	
	
	/**
	 * put init items
	 */
	protected function putInitItems(){
		
		$objAddons = new UniteCreatorAddons();
		
		$htmlAddons = $objAddons->getCatAddonsHtml(null, $this->filterActive);
		
		echo $htmlAddons;
	}
	
	
	/**
	 * init the addons manager
	 */
	protected function init(){
		
		$this->hasCats = false;
		
		parent::init();
		
		$this->itemsLoaderText = __("Getting Addons",UNITECREATOR_TEXTDOMAIN);
		$this->textItemsSelected = __("addons selected",UNITECREATOR_TEXTDOMAIN);
				
		$this->filterActive = HelperUC::getState(UniteCreatorAddons::STATE_FILTER_ACTIVE);
	}
	
	
}