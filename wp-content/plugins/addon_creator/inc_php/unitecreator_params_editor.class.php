<?php

class UniteCreatorParamsEditor{
	
	const TYPE_MAIN = "main";
	const TYPE_ITEMS = "items";
	
	private $type = null;
	private $isHiddenAtStart = false;
	private $isItemsType = false;
	
	
	/**
	 * validate that the object is inited
	 */
	private function validateInited(){
		if(empty($this->type))
			UniteFunctionsUC::throwError("UniteCreatorParamsEditor error: editor not inited");
	}
	
	
	/**
	 * output html of the params editor
	 */
	public function outputHtmlTable(){
		
		$this->validateInited();
		
		$style="";
		if($this->isHiddenAtStart == true)
			$style = "style='display:none'";
				
		?>
			<div id="attr_wrapper_<?php echo $this->type ?>" class="uc-attr-wrapper" data-type="<?php echo $this->type?>" <?php echo $style?> >
				
				<table class="uc-table-params unite_table_items">
					<thead>
						<tr>
							<th width="50px">
							</th>
							<th width="200px">
								<?php _e("Title", UNITECREATOR_TEXTDOMAIN)?>
							</th>
							<th width="160px">
								<?php _e("Name", UNITECREATOR_TEXTDOMAIN)?>
							</th>
							<th width="100px">
								<?php _e("Type", UNITECREATOR_TEXTDOMAIN)?>
							</th>
							<th width="270px">
								<?php _e("Param", UNITECREATOR_TEXTDOMAIN)?>
							</th>
							<th width="200px">
								<?php _e("Operations", UNITECREATOR_TEXTDOMAIN)?>
							</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
				
				<div class="uc-text-empty-params mbottom_20" style="display:none">
						<?php _e("No Params Found", UNITECREATOR_TEXTDOMAIN)?>
				</div>
				
				<a class="uc-button-add-param unite-button-secondary" href="javascript:void(0)"><?php _e("Add Attribute", UNITECREATOR_TEXTDOMAIN);?></a>
				
				<?php if($this->isItemsType):?>
				
				<a class="uc-button-add-imagebase unite-button-secondary mleft_10" href="javascript:void(0)"><?php _e("Add Image Base Fields", UNITECREATOR_TEXTDOMAIN);?></a>
				
				<?php endif?>
			</div>
		
		<?php 
	}

	
	/**
	 * set hidden at start. must be run before init
	 */
	public function setHiddenAtStart(){
		$this->isHiddenAtStart = true;
	}
	
	
	/**
	 * 
	 * init editor by type
	 */
	public function init($type){
		
		switch($type){
			case self::TYPE_MAIN:
			break;
			case self::TYPE_ITEMS:
				$this->isItemsType = true;
			break;
			default:
				UniteFunctionsUC::throwError("Wrong editor type: {$type}");
			break;
		}
		
		
		$this->type = $type;
	}
	
	
}