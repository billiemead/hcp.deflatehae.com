function UCManagerActionsAddons(){
	
	var g_objCats, g_objItems, g_manager;
	var g_options;

	if(!g_ucAdmin){
		var g_ucAdmin = new UniteAdminUC();
	}
	
	/**
	 * on item button click
	 */
	this.runItemAction = function(action, data){
		
		switch(action){
			case "add_addon":
				openAddAddonDialog();
			break;
			case "update_order":
				updateItemsOrder();
			break;
			case "select_all_items":
				g_objItems.selectUnselectAllItems();
			break;
			case "duplicate_item":
				duplicateItems();
			break;
			case "item_default_action":
			case "edit_addon":
				editAddon();
			break;
            case "edit_addon_blank":
            	editAddon(true);
            break;
			case "quick_edit":
				quickEdit();
			break;
			case "remove_item":
				removeSelectedAddons();				
			break;
			case "test_addon":
				testAddon();
			break;
			case "test_addon_blank":
				testAddon(true);
			break;
			case "move_items":
				moveAddons(data);
			break;
			case "copymove_move":
				onMoveOperationClick();
			break;
			case "get_cat_items":
		    	getSelectedCatAddons(data);	//data - catID
			break;
			case "import_addon":
				openImportAddonDialog();
			break;
			case "export_addon":
				exportAddon();
			break;
			case "activate_addons":
				activateAddons(true);
			break;
			case "deactivate_addons":
				activateAddons(false);
			break;
			default:
				trace("wrong item action: " + action);
			break;
		}
		
	}
	
	/**
	 * copy / move items
	 */
	function moveAddons(data){
		
		//set status text
		var text = g_uctext.moving_addons;
		
		g_manager.ajaxRequestManager("move_addons",data , g_uctext.moving_addons, function(response){
			setHtmlListCombo(response);
		});
		
	}
	
	
	
	/**
	 * init items
	 */
	function initItems(){
		
		initAddAddonDialog();
			
		initQuickEditDialog();
		g_manager.initBottomOperations();
		
		initImportAddonDialog();
	
	}

	/**
	 * set combo lists from response
	 */
	function setHtmlListCombo(response){
		var htmlItems = response.htmlItems;
		var htmlCats = response.htmlCats;
		
		g_objItems.setHtmlListItems(htmlItems);
		
		if(g_objCats)
			g_objCats.setHtmlListCats(htmlCats);
	}

	
	/**
	 * make some copy/move operation and close the dialog
	 */
	function onMoveOperationClick(){
		
		var objDrag = g_objItems.getObjDrag();
		
		var data = {};
		data.targetCatID = objDrag.targetItemID;
		data.selectedCatID = g_objCats.getSelectedCatID();
		data.arrAddonIDs = objDrag.arrItemIDs;
		
		moveAddons(data);

		g_objItems.resetDragData();
	}
	
	
	function ___________ADDONS_DIALOGS________________(){}

	
	/**
	 * init add addon dialog actions
	 */
	function initAddAddonDialog(){

		jQuery("#dialog_add_addon_action").click(addAddon);
		
		jQuery("#dialog_add_addon_name").add("#dialog_add_addon_title").keyup(function(event){
			if(event.keyCode == 13)
				addAddon();
		});
		
	}

	
	/**
	 * init quick edit dialog
	 */
	function initQuickEditDialog(){
		
		// set update title onenter function
		jQuery("#dialog_quick_edit_title").add("#dialog_quick_edit_name").keyup(function(event){
			if(event.keyCode == 13)
				updateItemTitle();
		});
		
	}
	
	
	/**
	 * init import addon dialog
	 */
	function initImportAddonDialog(){
		
		jQuery("#dialog_import_addons_action").click(function(){
			
	        var data = {};
	        data.catid = 0;
	        
	        if(g_objCats)
	        	data.catid = g_objCats.getSelectedCatID();
	    	
	        var objData = new FormData();
	        var jsonData = JSON.stringify(data);
	    	objData.append("data", jsonData);
	    	
	    	g_ucAdmin.addFormFilesToData("dialog_import_addons_form", objData);
	    	
			g_ucAdmin.dialogAjaxRequest("dialog_import_addons", "import_addons", objData, function(response){
				
				setHtmlListCombo(response);
				
			});
	    	
			
		});
				
		
	}
	
	
	/**
	 * on add addon click - open add addon dialog
	 */
	function openAddAddonDialog(){
		
		jQuery(".dialog_addon_input").val("");
		
		g_ucAdmin.openCommonDialog("#dialog_add_addon", function(){
			jQuery("#dialog_add_addon_title").select();			
		});
		
	}
	
	
	/**
	 * open import addon dialog
	 */
	function openImportAddonDialog(){
		
		g_ucAdmin.openCommonDialog("#dialog_import_addons");
		
	}
	
	
	function ___________ADDONS_RELATED_OPERATIONS________________(){}	//sap for outline	

	
	
	/**
	 * on dialog add addon click
	 */
	function addAddon(){
		
		var selectedCatID = 0;
		
		if(g_objCats)
			selectedCatID = g_objCats.getSelectedCatID();
		
		var data = {
				title: jQuery("#dialog_add_addon_title").val(),
				name: jQuery("#dialog_add_addon_name").val(),
				description: jQuery("#dialog_add_addon_description").val(),
				catid: selectedCatID
		};
		
		g_ucAdmin.dialogAjaxRequest("dialog_add_addon", "add_addon", data, function(response){

			var objItem = g_objItems.appendItem(response.htmlItem);
			
			//update categories list
			if(g_objCats)
				g_objCats.setHtmlListCats(response.htmlCats);
			
			g_objItems.selectSingleItem(objItem);
			
			//var urlAddon = response["url_addon"];
			//location.href = urlAddon;
			
		});
		
	}
	
	
	/**
	 * get item data from server
	 */
	function getItemData(itemID, callbackFunction){
		
		var data = {itemid:itemID};
		g_manager.ajaxRequestManager("get_item_data",data,g_uctext.loading_item_data,callbackFunction);
	}
	
	
	/**
	 * get category items
	 */
	function getSelectedCatAddons(selectedCatID){
		
		if(!selectedCatID)
			var selectedCatID = 0;
		
		jQuery("#items_loader").show();
		jQuery("#uc_list_items").hide();
		jQuery("#no_items_text").hide();
		
		var data = {};
		data["catID"] = selectedCatID;
		data["filter_active"] = getFitlerActive();
		
		
		g_ucAdmin.ajaxRequest("get_cat_addons",data,function(response){
			g_objItems.setHtmlListItems(response.itemsHtml);
			g_objItems.checkSelectRelatedItems();
		});
	}
	
	
	/**
	 * remove items
	 */
	function removeAddons(arrIDs){
		
		var data = {};
		data.arrAddonsIDs = arrIDs;
		
		data.catid = 0;
		
		if(g_objCats)
			data.catid = g_objCats.getSelectedCatID();
		
		g_manager.ajaxRequestManager("remove_addons",data, g_uctext.removing_addons, function(response){
			setHtmlListCombo(response);
		});
		
	}
	
	
    /**
     * remove selected items
     */
    function removeSelectedAddons(){
		if(g_ucAdmin.isButtonEnabled(this) == false)
			return(false);
		
		if(confirm(g_uctext.confirm_remove_addons) == false)
			return(false);
		
		var arrIDs = g_objItems.getSelectedItemIDs();
		
		removeAddons(arrIDs);
    }


    /**
     * run addons view url
     */
    function runAddonsViewUrl(view, isNewWindow){

    	var itemID = g_objItems.getSelectedItemID();
		if(itemID == null)
			return(false);
		
		var urlViewEdit = g_ucAdmin.getUrlView(view, "id="+itemID);
		
		if(isNewWindow === true){
			window.open(urlViewEdit);
		}else{
			location.href = urlViewEdit;
		}
    	
    }
	
    
	/**
	 * edit item operation. open quick edit dialog
	 */
	function editAddon(isNewWindow){
		
		runAddonsViewUrl("addon", isNewWindow);
	}
	
	
	/**
	 * test addon
	 */
	function testAddon(isNewWindow){
		runAddonsViewUrl("testaddon", isNewWindow);
	}
	
	/**
	 * export selected addon
	 */
	function exportAddon(){
		var arrIDs = g_objItems.getSelectedItemIDs();
		
		if(arrIDs.length == 0)
			return(false);
		
		var addonID = arrIDs[0];
		
		var params = "id="+addonID;
		var urlExport = g_ucAdmin.getUrlAjax("export_addon", params);
		
		location.href=urlExport;
	}
	
	
	/**
	 * edit item title function
	 */
	function quickEdit(){
		
		var arrIDs = g_objItems.getSelectedItemIDs();
		
		if(arrIDs.length == 0)
			return(false);
		
		var itemID = arrIDs[0];
		
		var objItem = g_objItems.getItemByID(itemID);
		if(objItem.length == 0)
			throw new Error("item not found: "+itemID);
		
		var title = objItem.data("title");
		var name = objItem.data("name");
		var description = objItem.data("description");
		
		var objDialog = jQuery("#dialog_edit_item_title");
		
		jQuery("#dialog_quick_edit_title").val(title).focus();
		jQuery("#dialog_quick_edit_name").val(name);
		jQuery("#dialog_quick_edit_description").val(description);
		
		var buttonOpts = {};
		
		buttonOpts[g_uctext.cancel] = function(){
			jQuery("#dialog_edit_item_title").dialog("close");
		};
		
		buttonOpts[g_uctext.update] = function(){
			updateItemTitle();
		}
		
		objDialog.data("itemid",itemID);
		
		objDialog.dialog({
			dialogClass:"unite-ui",			
			buttons:buttonOpts,
			minWidth:500,
			modal:true,
			open:function(){
				jQuery("#dialog_quick_edit_title").select();
			}
		});
		
	}
	
	
	/**
	 * update item title - on dialog update press
	 */
	function updateItemTitle(){
		
		var objDialog = jQuery("#dialog_edit_item_title");
		var itemID = objDialog.data("itemid");
		
		var objItem = g_objItems.getItemByID(itemID);
		if(objItem.length == 0)
			throw new Error("item not found: "+itemID);
		
		var titleHolder = objItem.find(".uc-item-title");
		var descHolder = objItem.find(".uc-item-description");
		
		var newTitle = jQuery("#dialog_quick_edit_title").val();
		var newName = jQuery("#dialog_quick_edit_name").val();
		var newDesc = jQuery("#dialog_quick_edit_description").val();
		
		var data = {
			itemID: itemID,
			title: newTitle,
			name: newName,
			description: newDesc
		};
		
		objDialog.dialog("close");
		
		//update the items
		objItem.data("title", newTitle);
		objItem.data("name", newName);
		objItem.data("description", newDesc);
		
		titleHolder.html(newTitle);
		
		var showDesc = "";
		if(newDesc)
			showDesc = newDesc;
		
		descHolder.html(showDesc);
		
		g_manager.ajaxRequestManager("update_addon_title",data,g_uctext.updating_addon_title);
	}
	
	
	/**
	 * duplicate items
	 */
	function duplicateItems(){
		
		var arrIDs = g_objItems.getSelectedItemIDs();
		if(arrIDs.length == 0)
			return(false);
		
		var selectedCatID = 0;
		
		if(g_objCats)
			selectedCatID = g_objCats.getSelectedCatID();
		
		if(selectedCatID == -1)
			return(false);
		
		var data = {
				arrIDs: arrIDs,
				catID: selectedCatID
		};
		
		g_manager.ajaxRequestManager("duplicate_addons",data,g_uctext.duplicating_addons,function(response){
			setHtmlListCombo(response);
		});	
	}
	
	
	/**
	 * update items order in server
	 */
	function updateItemsOrder(){
		
		var arrIDs = g_objItems.getArrItemIDs();
		
		var data = {addons_order:arrIDs};
		g_manager.ajaxRequestManager("update_addons_order",data,g_uctext.updating_addons_order);
	}
	
	
	/**
	 * activate selected addons
	 */
	function activateAddons(isActive){
		var arrIDs = g_objItems.getSelectedItemIDs();
		
		g_objItems.acivateSelectedItems(isActive, true);
		
		var data = {addons_ids:arrIDs,is_active:isActive};
		g_manager.ajaxRequestManager("update_addons_activation",data,g_uctext.updating_addons);
	}
	
	
	function ___________FILTERS________________(){}	
	
	
	/**
	 * get active / not active filter
	 */
	function getFitlerActive(){
		var objFilter = jQuery("#uc_filters_active a.uc-active");
		
		if(objFilter.length == 0)
			throw new Error("there must be active filter");
		
		var filter = objFilter.data("filter");
		
		return(filter);
	}
	
	
	/**
	 * init filters
	 */
	function initFilters(){
		
		//init active / not active filters
		
		jQuery("#uc_filters_active a").click(function(){
			var classActive = "uc-active";
			var objFilter = jQuery(this);
			if(objFilter.hasClass(classActive))
				return(true);
			
			var filter = objFilter.data("filter");
			
			jQuery("#uc_filters_active a").not(objFilter).removeClass(classActive);
			objFilter.addClass(classActive);
			
			getSelectedCatAddons();
			
		});
		
	}
	
	
	/**
	 * init the actions
	 */
	this.init = function(objManager){
		g_manager = objManager;
		
		g_objCats = g_manager.getObjCats();
		g_objItems = g_manager.getObjItems();
		
		g_objItems.setSpacesBetween(15,15);
		
		g_manager.initItems();
		
		initItems();
		
		initFilters();
	}
	
	
}