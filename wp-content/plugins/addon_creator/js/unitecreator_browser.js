

/**
 * browser object
 */
function UniteCreatorBrowser(){
	
	var g_objWrapper, g_objTabsWrapper, g_objContentWrapper, g_objBackButton;
	var g_objConfig = new UniteCreatorAddonConfig();
	
	var g_options = {
		startWidthAddon: false,
		startAddonName:null
	};
	
	
	var t = this;
	
	/**
	 * return if tab selected or not
	 */
	function isTabSelected(objTab){
		if(objTab.hasClass("uc-tab-selected"))
			return(true);
		
		return(false);
	}
	
	
	/**
	 * select some tab
	 */
	function selectTab(objTab){
		
		var objOtherTabs = getObjTabs(objTab);
		
		objOtherTabs.removeClass("uc-tab-selected");
		objTab.addClass("uc-tab-selected");
		
		//show content, hide others
		var catID = objTab.data("catid");
		var objContent = jQuery("#uc_browser_content_"+catID);
		
		g_objWrapper.find(".uc-browser-content").not(objContent).hide();
		objContent.show();
		
	}

	
	/**
	 * on tab click function
	 */
	function onTabClick(){
		var objTab = jQuery(this);
		if(isTabSelected(objTab))
			return(true);
		
		selectTab(objTab);
		
	}
	
	/**
	 * get obj all tabs without some tab
	 */
	function getObjTabs(objWithout){
		var objTabs = g_objWrapper.find(".uc-browser-tabs-wrapper .uc-browser-tab");
		
		if(objWithout)
			objTabs = objTabs.not(objWithout);
				
		return(objTabs);
	}
	
	/**
	 * hide browser
	 */
	function hideBrowser(){
		g_objTabsWrapper.hide();
		g_objContentWrapper.hide();
		g_objBackButton.show();
	}
	
	
	/**
	 * show browser
	 */
	function showBrowser(){
		g_objTabsWrapper.show();
		g_objContentWrapper.show();
		g_objBackButton.hide();
	}
	
	
	/**
	 * on addon click
	 */
	function onAddonClick(){
		var objAddon = jQuery(this);
		var addName = objAddon.data("name");
		var objTitle = objAddon.data("title");
		
		if(g_objConfig){
			hideBrowser();
			g_objConfig.show();
			g_objConfig.runAddon(addName, objTitle);
		}
		
	}
	
	
	/**
	 * init tabs
	 */
	function initTabs(){
		
		var objTabs = getObjTabs();
		
		objTabs.click(onTabClick);
		
	}
	
	
	/**
	 * on back button click
	 */
	function onBackButtonClick(){
		showBrowser();
		
		if(g_objConfig)
			g_objConfig.hide();
	}
	
	
	/**
	 * init events
	 */
	function initEvents(){
		
		g_objWrapper.find(".uc-browser-addon").click(onAddonClick);
		
		g_objBackButton.click(onBackButtonClick);
	}
	
	
	/**
	 * init config object
	 */
	function initConfig(){
		
		var objConfigWrapper = g_objWrapper.find(".uc-addon-config");
		
		if(objConfigWrapper.length == 0){
			g_objConfig = null;
			return(false);
		}
		
		//input for update settings ID's
		var inputIDForUpdate = g_objWrapper.data("inputupdate");
		if(inputIDForUpdate){
			var objInput = jQuery("#"+inputIDForUpdate);
			if(objInput.length == 0)
				trace("error - input "+inputIDForUpdate+"not found");
			else
				g_objConfig.setInputUpdate(objInput);
		}
		
		//set start addon name in case that avilable
		
		if(g_options.startWidthAddon == true)
			g_objConfig.setStartAddon(g_options.startAddonName);
		
		
		g_objConfig.init(objConfigWrapper);
	}
	
	
	/**
	 * init browser object
	 */
	this.init = function(objWrapper){
		
		g_objWrapper = objWrapper;
		
		g_objTabsWrapper = objWrapper.find(".uc-browser-tabs-wrapper");
		g_objContentWrapper = objWrapper.find(".uc-browser-content-wrapper");
		g_objBackButton = objWrapper.find(".uc-browser-button-back");
		
		//set start addon name
		var startAddonName = g_objWrapper.data("startaddon");
		if(startAddonName){
			g_options.startWidthAddon = true;
			g_options.startAddonName = startAddonName;
		}
		
		//init config 
		initConfig();
		initTabs();
		initEvents();
	}
	
}


/**
 * init browsers
 */
function UniteCreatorBrowsersInit(){
	
	jQuery(".uc-browser-wrapper").each(function(index, element){
		
		var objBrowserElement = jQuery(element);
		var objBrowser = new UniteCreatorBrowser();
		objBrowser.init(objBrowserElement);
		
	});
	
}


jQuery(document).ready(function(){
	
	UniteCreatorBrowsersInit();
});