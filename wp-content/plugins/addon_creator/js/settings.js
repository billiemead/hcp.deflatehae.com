
function UniteSettingsUC(){
	
	var arrControls = {};
	var g_IDPrefix = "#unite_setting_";
	var g_colorPicker;
	var g_objParent = null;
	var g_objProvider = new UniteProviderAdminUC();
	
	var g_events = {
			CHANGE: "change"
	};
	
	if(!g_ucAdmin)
		var g_ucAdmin = new UniteAdminUC();
	
	var t=this;
	
	
	/**
	 * validate that the parent exists
	 */
	function validateInited(){
		
		if(!g_objParent || g_objParent.length == 0)
			throw new Error("The parent not given, settings not inited");
		
	}
	
	
	/**
	 * compare control values
	 */
	function iscValEQ(controlValue, value){
		
		if(typeof value != "string"){
			
			return jQuery.inArray( controlValue, value) != -1;
		}else{
			return (value.toLowerCase() == controlValue);
		}

	}
	
	
	
	
	
	
	//init color picker
	function initColorPicker(){
		
		var colorPickerWrapper = jQuery('#divColorPicker');
		if(colorPickerWrapper.length == 0){
			jQuery("body").append('<div id="divColorPicker" style="display:none;"></div>');
			colorPickerWrapper = jQuery('#divColorPicker');
		}
		
		//init the wrapper itself
		var isInited = colorPickerWrapper.data("inited");
		if(isInited !== true){
			
			colorPickerWrapper.click(function(){
				return(false);	//prevent body click
			});
			
			jQuery("body").click(function(){
				colorPickerWrapper.hide();
			});
			
			colorPickerWrapper.data("inited", true);
		}
		
		g_colorPicker = jQuery.farbtastic('#divColorPicker');
		
		g_objParent.find(".unite-color-picker").focus(function(){
			g_colorPicker.linkTo(this);
			
			var bodyWidth = jQuery("body").width();
			
			colorPickerWrapper.show();
			var input = jQuery(this);
			var offset = input.offset();
			
			var wrapperWidth = colorPickerWrapper.width();
			var inputWidth = input.width();
			var inputHeight = input.height();
			
			var posLeft = offset.left - wrapperWidth / 2 + inputWidth/2;
			
			var posRight = posLeft + wrapperWidth;
			if(posRight > bodyWidth)
				posLeft = bodyWidth - wrapperWidth;
			
			var posTop = offset.top - colorPickerWrapper.height() - inputHeight + 10;
			
			colorPickerWrapper.css({
				"left":posLeft,
				"top":posTop
			});

			
		}).click(function(){			
			return(false);	//prevent body click
		});
		
	}
	
	/**
	 * close all accordion items
	 */
	function closeAllAccordionItems(formID){
		jQuery("#"+formID+" .unite-postbox .inside").slideUp("fast");
		jQuery("#"+formID+" .unite-postbox .unite-postbox-title").addClass("box_closed");
	}
	
	/**
	 * init side settings accordion - started from php
	 */
	this.initAccordion = function(formID){
		var classClosed = "box_closed";
		jQuery("#"+formID+" .unite-postbox .unite-postbox-title").click(function(){
			var handle = jQuery(this);
			
			//open
			if(handle.hasClass(classClosed)){
				closeAllAccordionItems(formID);
				handle.removeClass(classClosed).siblings(".inside").slideDown("fast");
			}else{	//close
				handle.addClass(classClosed).siblings(".inside").slideUp("fast");
			}
			
		});
	};
	
	
	
	/**
	 * init tipsy
	 */
	function initTipsy(gravity){
		
		if(typeof jQuery("body").tipsy != "function")
			return(false);
		
		if(!gravity)
			var gravity = "e";
		
		//init tipsy
		jQuery(g_objParent+" .setting_uctext").tipsy({
			html:true,
			gravity:gravity,
	        delayIn: 70
		});
		
	}
	
	
	/**
	 * get all settings inputs
	 */
	function getObjInputs(objParent){
		validateInited();
		
		if(!objParent)
			var objParent = g_objParent;
		
		var objInputs = objParent.find("input, textarea, select").not("input[type='button']");
		return(objInputs);
	}
	
	
	/**
	 * get input type
	 */
	function getInputType(objInput){
		var type = objInput[0].type;
		
		switch(type){
			case "select-one":
			case "select-multiple":
				type = "select";
			break;
			case "text":
				if(objInput.hasClass("unite-color-picker"))
					type = "color";
				else
					if(objInput.hasClass("unite-setting-image-input"))
						type = "image";
			break;
			case "textarea":
				if(objInput.hasClass("mce_editable"))
					type = "editor_tinymce";
			break;
		}
		
		return(type);
	}
	
		
	
	/**
	 * get settings values object by the parent
	 */
	this.getSettingsValues = function(objParent){
		
		validateInited();
		
		var obj = new Object();
		
		var name,value,type,flagUpdate,inputID;
		
		var objInputs = getObjInputs(objParent);
		
		jQuery.each(objInputs, function(index, input){
			
			var objInput = jQuery(input);
			name = objInput.attr("name");
			type = getInputType(objInput);
			value = objInput.val();
			inputID = objInput.prop("id");
			
			flagUpdate = true;

			switch(type){
				case "checkbox":
					value = objInput.is(":checked");
				break;
				case "radio":
					if(objInput.is(":checked") == false) 
						flagUpdate = false;				
				break;
				case "button":
					flagUpdate = false;
				break;
				case "editor_tinymce":
					var objEditor = tinyMCE.EditorManager.get(inputID);
					value = objEditor.getContent();
				break;
				case "image":
					var source = objInput.data("source");
					
					//convert to relative url if not addon
					if(source != "addon")
						value = g_ucAdmin.urlToRelative(value);
				break;
			}
			
			if(flagUpdate == true && name != undefined) 
				obj[name] = value;
			
		});
		
		
		return(obj);
	};
	
	
	
	/**
	 * clear input
	 */
	function clearInput(objInput, dataname, checkboxDataName){
		
		var name = objInput.attr("name");
		var type = getInputType(objInput);
		var inputID = objInput.prop("id");
		var defaultValue;
		
		if(!dataname)
			var dataname = "default";
		
		if(!checkboxDataName)
			var checkboxDataName = "defaultchecked";
		
		switch(type){
			case "select":
			case "textarea":
			case "text":
				defaultValue = objInput.data(dataname);
				objInput.val(defaultValue);
			break;
			case "color":
				defaultValue = objInput.data(dataname);
				objInput.val(defaultValue);
				g_colorPicker.linkTo(objInput);							
			break;
			case "checkbox":
				defaultValue = objInput.data(checkboxDataName);
				defaultValue = g_ucAdmin.strToBool(defaultValue);
				
				if(defaultValue == true)
					objInput.attr("checked", true);
				else
					objInput.attr("checked", false);
			break;
			case "radio":
				defaultValue = objInput.data(checkboxDataName);
				defaultValue = g_ucAdmin.strToBool(defaultValue);
				
				if(defaultValue == true)
					objInput.attr("checked", "checked");
			break;
			case "editor_tinymce":
				var defaultValue = objInput.val();
				var objEditor = tinyMCE.EditorManager.get(inputID);
				objEditor.setContent(defaultValue);
			break;
			case "image":
				defaultValue = objInput.data(dataname);
				objInput.val(defaultValue);
				objInput.trigger("change");
			break;
			default:
				trace("for clear - wrong type: " + type);
			break;
		}
		
	}

	
	/**
	 * set input value
	 */
	function setInputValue(objInput, value){
		
		var type = getInputType(objInput);
		var inputID = objInput.prop("id");
		
		switch(type){
			case "select":
			case "textarea":
			case "text":
				objInput.val(value);
			break;
			case "color":
				objInput.val(value);
				g_colorPicker.linkTo(objInput);							
			break;
			case "checkbox":
				value = g_ucAdmin.strToBool(value);
				
				if(value == true)
					objInput.attr("checked", true);
				else
					objInput.attr("checked", false);
			break;
			case "radio":
				value = g_ucAdmin.strToBool(value);
				
				if(value == true)
					objInput.attr("checked", "checked");
			break;
			case "editor_tinymce":
			case "editor_tinymce":
				var objEditor = tinyMCE.EditorManager.get(inputID);
				objEditor.setContent(value);
			break;
			case "image":
				objInput.val(value);
				objInput.trigger("change");
			break;
			default:
				trace("for setvalue - wrong type: " + type);
			break;
		}
		
		
	}
	
	
	/**
	 * clear settings
	 */
	this.clearSettings = function(dataname, checkboxDataName){
		
		validateInited();
		
		var objInputs = getObjInputs();
		
		jQuery.each(objInputs, function(index, input){
			var objInput = jQuery(input);
			clearInput(objInput, dataname, checkboxDataName);
		});
	}
	
	
	/**
	 * get field names by type
	 */
	this.getFieldNamesByType = function(type){
		
		validateInited();
		
		var objInputs = getObjInputs();
		var arrFieldsNames = [];
		
		jQuery.each(objInputs, function(index, input){
			var objInput = jQuery(input);
			var name = objInput.attr("name");
			
			var inputType = getInputType(objInput);
			if(inputType == type)
				arrFieldsNames.push(name);
		});
		
		return(arrFieldsNames);
	}

	
	/**
	 * clear settings
	 */
	function clearSettingsInit(){
		
		t.clearSettings("initval","initchecked");
		
	}
	
	
	/**
	 * set values, clear first
	 */
	this.setValues = function(objValues){
		
		validateInited();
		
		//if empty values - exit
		if(typeof objValues != "object"){
			this.clearSettings();
			return(false);
		}
		
		var objInputs = getObjInputs();
		
		jQuery.each(objInputs, function(index, input){
			var objInput = jQuery(input);
			clearInput(objInput);
			var name = objInput.attr("name");
			
			if(typeof name == "undefined")
				return(true);
				
			if(objValues.hasOwnProperty(name)){
				var value = objValues[name];
				setInputValue(objInput, value);
			}
			
		});
		
	}
	
	function _______IMAGE_SETTING_____(){}
	
	
	/**
	 * set image preview
	 */
	function setImagePreview(){
		
		var objInput = jQuery(this);
		
		if(objInput.length == 0)
			throw new Error("wrong image input given");
		
		var source = objInput.data("source");			
		
		var url = objInput.val();
		
		if(source == "addon"){
			var urlFull = objInput.data("urlfull");
			if(!urlFull){
				urlFull = g_ucAdmin.urlToFull(url);
				objInput.data("urlfull", urlFull);
			}
			
			url = urlFull;
		}else{
			url = g_ucAdmin.urlToFull(url);
		}
				
		var objPreview = objInput.siblings(".unite-setting-image-preview");
		
		url = jQuery.trim(url);
		if(url == ""){
			objPreview.hide();
		}else{
			objPreview.css("background-image","url('"+url+"')");
			objPreview.show();
		}
		
	}
	
	
	/**
	 * on change image click - change the image
	 */
	function onChooseImageClick(){
		var objButton = jQuery(this);
		
		if(g_ucAdmin.isButtonEnabled(objButton) == false)
			return(true);
		
		var objInput = objButton.siblings(".unite-setting-image-input");
		var source = objInput.data("source");
		
		g_ucAdmin.openAddImageDialog(g_uctext.choose_image,function(urlImage){
			
			if(source == "addon"){		//in that case the url is an object
				var inputValue = urlImage.url_assets_relative;
				var fullUrl = urlImage.full_url;				
				objInput.data("urlfull", fullUrl);
				
				setInputValue(objInput, inputValue);
			}else
				setInputValue(objInput, urlImage);
			
			objInput.trigger("change");
			
		},false, source);
		
	}
	
	
	/**
	 * on clear image click
	 */
	function onClearImageClick(){
		
		var objButton = jQuery(this);
		
		if(g_ucAdmin.isButtonEnabled(objButton) == false)
			return(true);
		
		var objInput = objButton.siblings(".unite-setting-image-input");
		
		objInput.val("");
		objInput.trigger("change");
		
	}
	
	
	/**
	 * update image url base
	 */
	this.updateImageFieldState = function(objInput, urlBase){
		
		var objError = objInput.siblings(".unite-setting-image-error");
		var objButton = objInput.siblings(".unite-button-choose");
		var objButtonClear = objInput.siblings(".unite-button-clear");
		var objPreview = objInput.siblings(".unite-setting-image-preview");
		
		objInput.trigger("change");
		
		if(!urlBase){				//set error mode
			
			if(objError.length)
				objError.show();
			
			g_ucAdmin.disableInput(objInput);
			g_ucAdmin.disableButton(objButton);
			g_ucAdmin.disableButton(objButtonClear);
			objPreview.hide();
			
		}else{						//activate image input
			if(objError.length)
				objError.hide();
			
			g_ucAdmin.enableInput(objInput);
			g_ucAdmin.enableButton(objButton);
			g_ucAdmin.enableButton(objButtonClear);
			
			var backgroundImage = objPreview.css("background-image");
						
			if(backgroundImage && backgroundImage != "none")
				objPreview.show();
		}
		
		
	}
	
	
	/**
	 * on update assets path
	 * update all image addon inputs url base
	 */
	function onUpdateAssetsPath(event, urlBase){
		
		validateInited();
		
		var objInputs = getObjInputs();

		objInputs.each(function(index, input){
			
			var objInput = jQuery(input);
			var type = getInputType(objInput);
			if(type != "image")
				return(true);
			
			var source = objInput.data("source");
			
			if(source == "addon")
				t.updateImageFieldState(objInput, urlBase);
			
		});
		
	}
	
	
	function _______EVENTS_____(){}
	
	
	/**
	 * update events (in case of ajax set)
	 */
	this.updateEvents = function(){
		
		initSettingsEvents();
		
		initTipsy("s");
		
		if(typeof g_objProvider.onSettingsUpdateEvents == "function")
			g_objProvider.onSettingsUpdateEvents(g_objParent);
		
	};

	
	/**
	 * set on change event, this function should run before init
	 */
	this.setEventOnChange = function(func){
		onEvent(g_events.CHANGE, func);
	}

		
	
	/**
	 * run on setting change
	 */
	function onSettingChange(){
		
		triggerEvent(g_events.CHANGE);
		
	}

	
	/**
	 * on selects change - impiment the hide/show, enabled/disables functionality
	 */
	function onControlSettingChange(){
		
		var controlValue = this.value.toLowerCase();
		var controlID = this.name;
		
		if(!arrControls[controlID]) 
			return(false);
		
		var arrChildControls = arrControls[controlID];
				
		jQuery(arrChildControls).each(function(){
			var childInputID = this.name;
			
			var objChildInput = jQuery(g_IDPrefix + childInputID);
			
			var objChildRow = jQuery(g_IDPrefix + childInputID + "_row");
			
			if(objChildRow.length == 0)
				return(true);
			
			var value = this.value;
			
			var inputTagName = "";
			if(objChildInput.length)
				inputTagName = objChildInput.get(0).tagName;
			
			var isChildRadio = (inputTagName == "SPAN" && objChildInput.length && objChildInput.hasClass("radio_wrapper"));
			
			switch(this.type){
				case "enable":
				case "disable":
					
					if(objChildInput.length > 0){
						
						//disable
						if(this.type == "enable" && iscValEQ(controlValue,value) == false || this.type == "disable" && iscValEQ(controlValue,value) == true){
							objChildRow.addClass("setting-disabled");
							
							if(objChildInput.length)
								objChildInput.prop("disabled","disabled").css("color","");
							
							if(isChildRadio)
								objChildInput.children("input").prop("disabled","disabled").addClass("disabled");
						}//enable						
						else{	
							
							objChildRow.removeClass("setting-disabled");
							
							if(objChildInput.length)
								objChildInput.prop("disabled","");
							
							if(isChildRadio)
								objChildInput.children("input").prop("disabled","").removeClass("disabled");
							
							//color the input again
							if(objChildInput.length && objChildInput.hasClass("unite-color-picker")) 
								g_colorPicker.linkTo(objChildInput);							
		 				}
						
					}
				break;
				case "show":
					if(iscValEQ(controlValue,value) == true) 
						objChildRow.show();									
					else 
						objChildRow.hide();					
				break;
				case "hide":
					if(iscValEQ(controlValue,value) == true) 
						objChildRow.hide();
					else 
						objChildRow.show();
				break;
			}
			
		});
	}
	
	
	/**
	 * trigger event
	 */
	function triggerEvent(eventName, params){
		if(!params)
			var params = null;
		
		g_objParent.trigger(eventName, params);
	}
	
	
	/**
	 * on event name
	 */
	function onEvent(eventName, func){
		validateInited();				
		g_objParent.on(eventName,func);
	}

	
	/**
	 * combine controls to one object, and init control events.
	 */
	function initControlsEvents(){
		
		//combine controls
		for(key in g_settingsObjUC){
			var obj = g_settingsObjUC[key];
			
			for(controlKey in obj.controls){
				arrControls[controlKey] = obj.controls[controlKey];
			}
		}
		
		//init events
		g_objParent.find("select").change(onControlSettingChange);
		g_objParent.find("input[type='radio']").change(onControlSettingChange);
				
	}
	
	
	/**
	 * init image chooser
	 */
	this.initImageChooser = function(objImageSettings){
		
		if(objImageSettings.length == 0)
			return(false);
		
		objImageSettings.find(".unite-button-choose").click(onChooseImageClick);
		objImageSettings.find(".unite-button-clear").click(onClearImageClick);
		
		var objInput = objImageSettings.find("input");
				
		objInput.change(setImagePreview);
	}
	
	
	/**
	 * init settings events
	 */
	function initSettingsEvents(){
		
		var objInputs = g_objParent.find("input,textarea,select").not("input[type='radio']");
		
		objInputs.change(onSettingChange);
		
		var objInputsClick = g_objParent.find("input[type='radio']");
		objInputsClick.click(onSettingChange);
		
		//init image input events
		var objImageSettings = g_objParent.find(".unite-setting-image");
		
		t.initImageChooser(objImageSettings);
		
		initControlsEvents();
	}
	
	
	/**
	 * init global events - not repeating
	 */
	function initGlobalEvents(){
		
		g_ucAdmin.onEvent("update_assets_path", onUpdateAssetsPath);
		
	}
	
	
	/**
	 * init the settings function, set the tootips on sidebars.
	 */
	this.init = function(objParent){
		
		if(!g_ucAdmin)
			g_ucAdmin = new UniteAdminUC();
		
		g_objParent = objParent;
		
		validateInited();
		
		initColorPicker();	//put the color picker automatically
		
		initGlobalEvents();
		
		t.updateEvents();
		
		clearSettingsInit();
		
	};


} // UniteSettings class end


