
function UniteCreatorAddonConfig(){
	
	var g_objWrapper, g_addonName, g_addonID, g_addonOptions, g_objLoader; 
	var g_objSettingsContainer, g_objItemsWrapper;
	var g_objConfigTable;
	var g_objTitle, g_ucAdmin = new UniteAdminUC(), g_objSettings = new UniteSettingsUC();
	var g_objPreviewWrapper, g_objIframePreview, g_objManager = new UCManagerAdmin();
	var g_objInputUpdate = null;	//field for put settings values
	
	var t = this;
	var g_options = {
			enable_items: false
	}
	
	this.events = {
			onShowPreview: function(){},
			onHidePreview: function(){}
	}
	
	
	/**
	 * validate that addon exists
	 */
	function validateInited(){
		if(!g_addonName)
			throw new Error("Addon name not given");
	}
	
	
	/**
	 * show the addon config
	 */
	this.show = function(){
		g_objWrapper.show();
	}
	
	
	/**
	 * hide the addon config
	 */
	this.hide = function(){
		g_objWrapper.hide();
		hidePreview();
	}
	
	
	/**
	 * set addon to run the config
	 */
	this.runAddon = function(name, title, options){
		
		g_objTitle.html(title);
		g_objLoader.show();
		
		g_objSettingsContainer.hide();
		
		var data = {};
		data.name = name;
		
		g_ucAdmin.ajaxRequest("get_addon_config_html", data, function(response){
			
			g_objLoader.hide();
			
			g_objSettingsContainer.show().html(response.html);
			g_objSettings.updateEvents(g_objSettingsContainer);

			t.setAddonObjects(name, options);
			
			if(g_objInputUpdate)
				updateValuesInput();
			
		});
		
	}

	/**
	 * get data object
	 */
	this.getObjData = function(){
		
		validateInited();
		
		var objValues = g_objSettings.getSettingsValues();
		
		var objData = {};
		objData["name"] = g_addonName;
		objData["id"] = g_addonID;
		objData["config"] = objValues;
		objData["items"] = "";
		
		if(g_options.enable_items)
			objData["items"] = g_objManager.getItemsData();
		
		return(objData);
	}
	
	/**
	 * get addon ID
	 */
	this.getAddonID = function(){
		return(g_addonID);
	}
	
	/**
	 * get json data from the settings
	 */
	function getJsonData(){
		
		var objData = t.getObjData();
		
		var strData = JSON.stringify(objData);
		
		return(strData);
	}

	
	/**
	 * update values field if exists
	 */
	function updateValuesInput(){

		if(!g_objInputUpdate)
			return(false);
		
		if(!g_addonName)
			throw new Error("Addon name should be exists");
		
		var strData = getJsonData();
		
		g_objInputUpdate.val(strData);
	}
	
	
	/**
	 * on settings change event. 
	 * Update field if exists
	 */
	function onSettingsChange(){
		
		if(g_objInputUpdate)
			updateValuesInput();
	}
	
	
	/**
	 * set update input ID, this function should be run before init
	 */
	this.setInputUpdate = function(objInput){
		g_objInputUpdate = objInput;
	}
	
	
	/**
	 * parse options from input
	 */
	function parseInputOptions(optionsInput){
		
		jQuery.each(optionsInput, function(key, value){
			
			if(g_options.hasOwnProperty(key)){
				if(value === "true")
					value = true;
				else
				if(value === "false")
					value = false;
				
				g_options[key] = value;
			}
						
		});

	}
	
	/**
	 * clear addon configuration to default
	 */
	this.clearData = function(){
		validateInited();
		g_objSettings.clearSettings();
		
		if(g_options.enable_items == true){
			g_objManager.clearItemsPanel();
		}
		
	}
	
	
	/**
	 * set addon config
	 */
	this.setData = function(settingsData, itemsData){
		validateInited();
		g_objSettings.setValues(settingsData);
		
		if(g_options.enable_items == true){
			g_objManager.setItemsFromData(itemsData);
		}
		
	}

	
	
	/**
	 * set start addon name
	 */
	this.setAddonObjects = function(addonName, options, addonID){
		
		g_addonName = addonName;
		g_addonID = addonID;
		
		if(typeof options == "undefined")
			throw new Error("You must provide options for this addon");
		
		parseInputOptions(options);
		
		//set states based on the options
		//hide / show items
		if(g_options.enable_items == true){
			
			g_objItemsWrapper.show();
			g_objManager.setCustomOptions(g_options);
			
		}else{
			g_objItemsWrapper.hide();
			
		}
		
	}
	
	
	/**
	 * get ajax preview url
	 */
	function getPreviewUrl(){

		var jsonData = getJsonData();
		jsonData = encodeURIComponent(jsonData);
		var params = "data="+jsonData+"";
		var urlPreview = g_ucAdmin.getUrlAjax("show_preview", params);
		
		return(urlPreview);
	}
	
	/**
	 * validate that preview exists
	 */
	function validatePreviewExists(){
	
		if(!g_objPreviewWrapper)
			throw new Error("The preview container not exists");
		
	}
	
	
	/**
	 * show preview
	 */
	this.showPreview = function(){
		
		validatePreviewExists();
		
		g_objConfigTable.hide();
		g_objPreviewWrapper.show();
		
		var urlPreview = getPreviewUrl();
		g_objIframePreview.attr("src", urlPreview);
		
		t.events.onShowPreview();
	}

	
	/**
	 * hide the preview
	 */
	this.hidePreview = function(){
		g_objIframePreview.attr("src", "");
		g_objPreviewWrapper.hide();

		g_objConfigTable.show();
		
		
		t.events.onHidePreview();
	}
	
	
	/**
	 * show preview in new tab
	 */
	this.showPreviewNewTab = function(){
		
		var urlPreview = getPreviewUrl();
		window.open(urlPreview);
		
	}
	
	
	
	
	/**
	 * init preview button
	 */
	function initPreview(){
		
		g_objPreviewWrapper = g_objWrapper.find(".uc-addon-config-preview");
		if(g_objPreviewWrapper.length == 0){
			g_objPreviewWrapper = null;
			return(false);
		}
		
		g_objIframePreview = g_objPreviewWrapper.find(".uc-preview-iframe");
		
		
	}
	
	
	/**
	 * set on show preview function
	 */
	this.onShowPreview = function(func){
		t.events.onShowPreview = func;
	}
	
	
	/**
	 * set on hide preview function
	 */
	this.onHidePreview = function(func){
		t.events.onHidePreview = func;
	}
	
	
	/**
	 * 
	 * @param objWrapper
	 */
	this.init = function(objWrapper, isPreviewMode){
		
		g_objWrapper = objWrapper;
		
		g_objLoader = g_objWrapper.find(".uc-addon-config-loader");
		g_objSettingsContainer = g_objWrapper.find(".uc-addon-config-settings");
		g_objItemsWrapper = g_objWrapper.find(".uc-addon-config-items");
		g_objTitle = g_objWrapper.find(".uc-addon-config-title");
		g_objConfigTable = g_objWrapper.find(".uc-addon-config-table");
		
		
		//set settings events
		g_objSettings.init(g_objSettingsContainer);
		g_objSettings.setEventOnChange(onSettingsChange);
		
		//init manager
		g_objManager.initManager();
		
		initPreview();
		
	}
	
	
	
}