<?php

class UniteCreatorManagerInline extends UniteCreatorManager{

	private $startAddon;
	
	
	/**
	 * construct the manager
	 */
	public function __construct(){
		
		$this->type = self::TYPE_ITEMS_INLINE;
		
		$this->init();
	}
	
	/**
	 * validate that the start addon exists
	 */
	private function validateStartAddon(){
		
		if(empty($this->startAddon))
			UniteFunctionsUC::throwError("The start addon not given");
		
	}
	
	
	/**
	 * init the data from start addon
	 */
	private function initStartAddonData(){
		
		//set init data
		$arrItems = $this->startAddon->getArrItems();
		
		$strItems = "";
		if(!empty($arrItems)){
			$strItems = json_encode($arrItems);
			$strItems = htmlspecialchars($strItems);
		}
		
		$addHtml = " data-init-items=\"{$strItems}\" ";
		
		$this->setManagerAddHtml($addHtml);
		
	}
	
	
	/**
	 * set start addon
	 */
	public function setStartAddon($addon){
		$this->startAddon = new UniteCreatorAddon();	//just for code completion
		$this->startAddon = $addon;
		
		$this->initStartAddonData();
	}
	
	
	/**
	 * get single item menu
	 */
	protected function getMenuSingleItem(){
		
		$arrMenuItem = array();
		$arrMenuItem["edit_item"] = __("Edit Item",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["remove_items"] = __("Delete",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItem["duplicate_items"] = __("Duplicate",UNITECREATOR_TEXTDOMAIN);
		
		return($arrMenuItem);
	}

	/**
	 * get multiple items menu
	 */
	protected function getMenuMulitipleItems(){
		$arrMenuItemMultiple = array();
		$arrMenuItemMultiple["remove_items"] = __("Delete",UNITECREATOR_TEXTDOMAIN);
		$arrMenuItemMultiple["duplicate_items"] = __("Duplicate",UNITECREATOR_TEXTDOMAIN);
		return($arrMenuItemMultiple);
	}
	
	
	/**
	 * get item field menu
	 */
	protected function getMenuField(){
		$arrMenuField = array();
		$arrMenuField["add_item"] = __("Add Item",UNITECREATOR_TEXTDOMAIN);
		$arrMenuField["select_all"] = __("Select All",UNITECREATOR_TEXTDOMAIN);
		
		return($arrMenuField);
	}
	
	
	/**
	 * put items buttons
	 */
	protected function putItemsButtons(){
		
		$this->validateStartAddon();
		
		$itemType = $this->startAddon->getItemsType();
		
		//put add item button according the type
		switch($itemType){
			default:
			case UniteCreatorAddon::ITEMS_TYPE_DEFAULT:
			?>
 			<a data-action="add_item" type="button" class="unite-button-primary button-disabled uc-button-item uc-button-add"><?php _e("Add Item",UNITECREATOR_TEXTDOMAIN)?></a>
			<?php 
			break;
			case UniteCreatorAddon::ITEMS_TYPE_IMAGE:
			?>
 			<a data-action="add_images" type="button" class="unite-button-primary button-disabled uc-button-item uc-button-add"><?php _e("Add Images",UNITECREATOR_TEXTDOMAIN)?></a>
			<?php 
			break;
		}
		
		?>
	 		<a data-action="select_all_items" type="button" class="unite-button-secondary button-disabled uc-button-item uc-button-select" data-textselect="<?php _e("Select All",UNITECREATOR_TEXTDOMAIN)?>" data-textunselect="<?php _e("Unselect All",UNITECREATOR_TEXTDOMAIN)?>"><?php _e("Select All",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="duplicate_items" type="button" class="unite-button-secondary button-disabled uc-button-item"><?php _e("Duplicate",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="remove_items" type="button" class="unite-button-secondary button-disabled uc-button-item"><?php _e("Delete",UNITECREATOR_TEXTDOMAIN)?></a>
	 		<a data-action="edit_item" type="button" class="unite-button-secondary button-disabled uc-button-item uc-single-item"><?php _e("Edit Item",UNITECREATOR_TEXTDOMAIN)?> </a>
		<?php 
	}
	
	
	/**
	 * put add edit item dialog
	 */
	private function putAddEditDialog(){
		
		?>
			<div title="<?php _e("Edit Item",UNITECREATOR_TEXTDOMAIN)?>" class="uc-dialog-edit-item" style="display:none;">
				<div class="uc-item-config-settings">
					<?php 
						if($this->startAddon)
							$this->startAddon->putHtmlItemConfig()
					 ?>
				</div>
			</div>
		<?php 
	}
	
	
	/**
	 * put additional html here
	 */
	protected function putAddHtml(){
			
		$this->putAddEditDialog();
	
	}
	
	
	/**
	 * init the addons manager
	 */
	protected function init(){
		
		$this->hasCats = false;
		
		parent::init();
	}
	
	
}