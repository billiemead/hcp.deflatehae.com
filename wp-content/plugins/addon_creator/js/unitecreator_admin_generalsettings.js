function UniteCreatorAdmin_GeneralSettings(){
	
	var t = this;
	var g_providerAdmin = new UniteProviderAdminUC();
	var g_settings = new UniteSettingsUC();

	
	if(!g_ucAdmin)
		var g_ucAdmin = new UniteAdminUC();
	
	
	/**
	 * on save button click function
	 */
	function onSaveButtonClick(){
		
		var setting_values = g_settings.getSettingsValues();
		
		var data = {setting_values:setting_values};
		
		g_ucAdmin.setAjaxLoaderID("uc_loader_save");
		g_ucAdmin.setSuccessMessageID("uc_message_saved");
		g_ucAdmin.setAjaxHideButtonID("uc_button_save_settings");
		g_ucAdmin.setErrorMessageID("uc_save_settings_error");
		
		g_ucAdmin.ajaxRequest("update_general_settings", data);
		
	}
	
	
	/**
	 * select tab in addon view
	 * tab is the link object to tab
	 */
	function onTabSelect(objTab){

		if(objTab.hasClass("uc-tab-selected"))
			return(false);
		
		var contentID = objTab.data("contentid");
		var tabID = objTab.prop("id");
		
		jQuery("#uc_tab_contents .uc-tab-content").hide();
		
		jQuery("#" + contentID).show();
		
		jQuery("#uc_tabs a").not(objTab).removeClass("uc-tab-selected");
		objTab.addClass("uc-tab-selected");
		
	}
	
	
	/**
	 * init tabs
	 */
	function initTabs(){
		
		jQuery("#uc_tabs a").click(function(){
			var objTab = jQuery(this);
			onTabSelect(objTab);
		});
		
	}
	
	
	/**
	 * init general settings view
	 */
	this.initView = function(){
		
		var objSettingsWrapper = jQuery("#uc_general_settings");
		
		if(objSettingsWrapper.length == 0)
			throw new Error("general settings not found");
		
		initTabs();
		
		g_settings.init(objSettingsWrapper);
		
		//save settings click
		jQuery("#uc_button_save_settings").click(onSaveButtonClick);
		
	}
	
}