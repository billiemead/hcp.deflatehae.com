
if(typeof trace == "undefined"){
	function trace(str){
		console.log(str);
	}
}


/**
 * general settings class
 */
function UCGeneralSettings(){
	var t = this;
	var g_currentManager;
	var g_objCurrentSettings, g_objSettingsWrapper;
	
	
	/**
	 * encode some content
	 */
	function encodeContent(value){
		return base64_encode(rawurlencode(value));
	}
	
	
	/**
	 * decode some content
	 */
	function decodeContent(value){
		return rawurldecode(base64_decode(value));		
	}
	
	
	/**
	 * default parse setting function
	 */
	function parseVcSetting(param){
		
		//trace("parse!!!");
		//trace(param);
		
		var settingName = param.name;
		var objSettingWrapper = g_objSettingsWrapper.find("#uc_vc_setting_wrapper_" + settingName); 
		
		if(objSettingWrapper.length == 0)
			throw new Error("the setting wrapper not found: "+settingName);
		
		var objValues = g_objCurrentSettings.getSettingsValues(objSettingWrapper);
		
		if(objValues.hasOwnProperty(settingName) == false)
			throw new Error("Value for setting: "+settingName+" not found");
		
		var value = objValues[settingName];
		
		
		return(value);
	}
	
		
	
	/**
	 * init visual composer attributes
	 */
	function initVCAtts(){
		
		var objParse = {parse:parseVcSetting};
		
		//text field
		vc.atts.uc_textfield = objParse;
		vc.atts.uc_number = objParse;
		vc.atts.uc_textarea = objParse;
		vc.atts.uc_radioboolean = objParse;
		vc.atts.uc_checkbox = objParse;
		vc.atts.uc_dropdown = objParse;
		vc.atts.uc_colorpicker = objParse;
		vc.atts.uc_image = objParse;
		vc.atts.uc_editor = objParse;
		
		
		//items
		vc.atts.uc_items = {
				parse:function(param){
					if(!g_currentManager)
						return("");
					
					var itemsData = g_currentManager.getItemsDataJson();
					
					itemsData = encodeContent(itemsData);
					
					return(itemsData);
				}
		};
		
		
	}
	
	
	/**
	 * init visual composer items
	 */
	this.initVCItems = function(){
		
		g_currentManager = new UCManagerAdmin();
		g_currentManager.initManager();
		
	}
	
	
	/**
	 * init visual composer settings
	 * the div init issome div inside the settings container
	 */
	this.initVCSettings = function(objDivInit){
		
		
		var objParent = objDivInit.parents(".vc_edit-form-tab");
		if(objParent.length == 0)
			objParent = objDivInit.parents(".wpb_edit_form_elements");
		
		if(objParent.length == 0)
			throw new Error("settings container not found");
		
		g_objSettingsWrapper = objParent;
		
		g_objCurrentSettings = new UniteSettingsUC();
		g_objCurrentSettings.init(g_objSettingsWrapper);
		
	}
	
	
	/**
	 * global init function
	 */
	this.init = function(){
				
		//init vc attrs
		if(typeof vc != "undefined" && vc.atts){
			initVCAtts();
		}

	}
	
}

var g_ucGeneralSettings = new UCGeneralSettings();

jQuery(document).ready(function(){
	
	g_ucGeneralSettings.init();
	
});
