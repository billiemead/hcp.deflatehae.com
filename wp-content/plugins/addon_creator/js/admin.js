
(function($){ 
    $.mlp = {x:0,y:0}; // Mouse Last Position
    function documentHandler(){
        var $current = this === document ? $(this) : $(this).contents();
        $current.mousemove(function(e){jQuery.mlp = {x:e.pageX,y:e.pageY}});
        $current.find("iframe").load(documentHandler);
    }
    $(documentHandler);
    $.fn.ismouseover = function(overThis) {  
        var result = false;
        this.eq(0).each(function() {  
                var $current = $(this).is("iframe") ? $(this).contents().find("body") : $(this);
                var offset = $current.offset();             
                result =    offset.left<=$.mlp.x && offset.left + $current.outerWidth() > $.mlp.x &&
                            offset.top<=$.mlp.y && offset.top + $current.outerHeight() > $.mlp.y;
        });  
        return result;
    };  
    
    //set do on enter on inputs event
    $.fn.doOnEnter = function(func){
    	var object = jQuery(this);
    	if(object.is("input") == false)
    		throw new Error("The do on enter event allowed only on inputs");
    	
    	if(typeof func != "function")
    		throw new Error("wrong function:"+func);
    	
    	object.keyup(function(event){
    		if(event.keyCode == 13)
    			func();
    	});
    	
    }
    
})(jQuery);

if(typeof window.addEvent == "undefined")
	window.addEvent = function(){};

function UniteAdminUC(){
	
	var t = this;
	
	var g_errorMessageID = null, g_hideMessageCounter = 0;
	var g_ajaxLoaderID = null, g_ajaxHideButtonID = null, g_successMessageID = null;	
	var g_colorPickerCallback = null;
	var g_providerAdmin = new UniteProviderAdminUC();
	
	this.__________GENERAL_FUNCTIONS_____ = function(){};	

	
	/**
	 * debug html on the top of the page (from the master view)
	 */
	this.debug = function(html){
		jQuery("#div_debug").show().html(html);
	};
	
	/**
	 * output data to console
	 */
	this.trace = function(data,clear){
		if(clear && clear == true)
			console.clear();	
		console.log(data);
	};
	
	
	
	
	/**
	 * check if was pressed right mouse button
	 */
	this.isRightButtonPressed = function(event){
		
		if(event.buttons == 2 || event.button == 2)
			return(true);
		
		return(false);
	};

	
	
	
	/**
	 * insert to CodeMirror editor
	 * @param data
	 */
	this.insertToCodeMirror = function(cm, text){
		
	    var doc = cm.getDoc();
	    var cursor = doc.getCursor(); 
	    	    
	    doc.replaceSelection(text); 
	    
	    /*
	    //set marked
	    var to = {
	    		line: cursor.line,
	    		ch: cursor.ch+text.length
	    }
	    
	    var options = {
	    		className:"uc-cm-mark-key"
	    };
	    	    
	    doc.markText(cursor, to, options);
	    */
	}
	
	
	
	/**
	 * get random number
	 */
	this.getRandomNumber = function() {
		  var min = 1;
		  var max = 1000000;
		  return Math.floor(Math.random() * (max - min + 1) + min);
	}	
	
	this.__________EVENTS_____ = function(){};	

	/**
	 * trigger some event
	 */
	this.triggerEvent = function(eventName, opt1){
		
		eventName = "unite_" + eventName;
		
		jQuery("body").trigger(eventName, opt1);
		
	}
	
	
	/**
	 * on some event
	 */
	this.onEvent = function(eventName,func){
		
		eventName = "unite_" + eventName;
		
		jQuery("body").on(eventName, func);
	}
	
	
	this.__________HTML_RELATED_____ = function(){};	

	/**
	 * add some option to select
	 */
	this.addOptionToSelect = function(objSelect, value, text, addDataName, addDataValue){
		
		var option = jQuery('<option>', {
		    value: value,
		    text: text
		});
		
		if(addDataName)
			option.data(addDataName, addDataValue);
		
		objSelect.append(option);
		
	}
	
	
	/**
	 * add text to input, to specific place if available
	 */
	this.addTextToInput = function(objInput, addText){
		
		var type = t.getInputType(objInput);
		if(type != "text" && type != "textarea"){
			trace(objInput);
			throw new Error("wrong input type: " + type);
		}
		
		var input = objInput[0];
		var cursorPos = undefined;
		if(typeof input.selectionStart != "undefined")
			cursorPos = input.selectionStart;
		
		var value = objInput.val();
		
		if(cursorPos === undefined)
			value += addText;
		else	
			value = value.substr(0, cursorPos) + addText + value.substr(cursorPos);
		
		objInput.val(value);
		objInput.focus();
		
		if(cursorPos !== undefined){
			var newPos = cursorPos + addText.length;
			input.setSelectionRange(newPos, newPos);
		}
		
	}
	
	
	/**
	 * load css file on the fly
	 * replace current item if exists
	 */
	this.loadCssFile = function(urlCssFile,replaceID){
		
		var rand = Math.floor((Math.random()*100000)+1);
		
		if(urlCssFile.indexOf("?") == -1)
			urlCssFile += "?rand="+rand;
		else
			urlCssFile += "&rand="+rand;
		
		if(replaceID)
			jQuery("#"+replaceID).remove();
		
		jQuery("head").append("<link>");
		var css = jQuery("head").children(":last");
		css.attr({
		      rel:  "stylesheet",
		      type: "text/css",
		      href: urlCssFile
		});
		
		//replace current element
		if(replaceID)
			css.attr({id:replaceID});
	};	
	
	
	/**
	 * unselect some button / buttons
	 */
	this.enableButton = function(buttonID){
		jQuery(buttonID).removeClass("button-disabled");
	};
	
	
	/**
	 * unselect some button / buttons
	 */
	this.disableButton = function(buttonID){
		jQuery(buttonID).addClass("button-disabled");
	};
	
	/**
	 * return true / false if the button enabled
	 */
	this.isButtonEnabled = function(buttonID){
		if(jQuery(buttonID).hasClass("button-disabled"))
			return(false);
		
		return(true);
	};
	
	
	/**
	 * disable input
	 */
	this.disableInput = function(objInput){
		objInput.addClass("setting-disabled").prop("disabled","disabled");
	}
	
	/**
	 * enable input
	 */
	this.enableInput = function(objInput){
		objInput.removeClass("setting-disabled").prop("disabled","");
	}
	
	/**
	 * get input type (from jquery object)
	 */
	this.getInputType = function(objInput){
		
		if(objInput.is("input[type='text']"))
			return("text");

		if(objInput.is("textarea"))
			return("textarea");
		
		if(objInput.is("input[type='radio']"))
			return("radio");

		if(objInput.is("select"))
			return("select");

		if(objInput.is("input[type='checkbox']"))
			return("checkbox");

		if(objInput.is("input[type='button']"))
			return("button");
		
		//get type by data
		var inputType = objInput.data("inputtype");
		if(inputType)
			return(inputType);
		
		
		//output exception
		var inputName = objInput.prop("name");
		throw new Error("Undefined input: " + inputName);
	}
	
	/**
	 * check if the input is simple input
	 */
	this.isSimpleInputType = function(inputType){
	
		switch(inputType){
			case "text":
			case "textarea":
			case "radio":
			case "select":
			case "checkbox":
				return(true);
			break;
		}
		
		return(false);
	}
	
	this.__________MODIFY_CONTENT_____ = function(){};	
	
	/**
	 * convert object to array
	 */
	this.objToArray = function(obj){
		if(typeof obj != "object")
			throw new Error("objToArray error: not object");
		
		var arr = [];
		jQuery.each(obj,function(key, item){
			arr.push(item);
		});
		
		return(arr);
	}
	
	
	/**
	 * turn string value ("true", "false") to string 
	 */
	this.strToBool = function(str){
		
		switch(typeof str){
			case "boolean":
				return(str);
			break;
			case "undefined":
				return(false);
			break;
			case "number":
				if(str == 0)
					return(false);
				else 
					return(true);
			break;
			case "string":
				str = str.toLowerCase();
				var bool = (str == "true")?true:false;
				return(bool);
			break;
		}
		
		return(false);
	};
	
	/**
	 * boolean to string
	 */
	this.boolToStr = function(str){
		if(typeof str == "string")
			return(str);
		
		str = (str == true)?"true":"false";
		
		return(str);
	}
	
	/**
	 * change rgb & rgba to hex
	 */
	this.rgb2hex = function(rgb) {
		if (rgb.search("rgb") == -1 || jQuery.trim(rgb) == '') return rgb; //ie6
		
		function hex(x) {
			return ("0" + parseInt(x).toString(16)).slice(-2);
		}
		
		if(rgb.indexOf('-moz') > -1){
			var temp = rgb.split(' ');
			delete temp[0];
			rgb = jQuery.trim(temp.join(' '));
		}
		
		if(rgb.split(')').length > 2){
			var hexReturn = '';
			var rgbArr = rgb.split(')');
			for(var i = 0; i < rgbArr.length - 1; i++){
				rgbArr[i] += ')';
				var temp = rgbArr[i].split(',');
				if(temp.length == 4){
					rgb = temp[0]+','+temp[1]+','+temp[2];
					rgb += ')';
				}else{
					rgb = rgbArr[i];
				}
				rgb = jQuery.trim(rgb);
				
				rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
				
				hexReturn += "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3])+" ";
			}
			
			return hexReturn;
		}else{
			var temp = rgb.split(',');
			if(temp.length == 4){
				rgb = temp[0]+','+temp[1]+','+temp[2];
				rgb += ')';
			}
			
			rgb = rgb.match(/^rgba?\((\d+),\s*(\d+),\s*(\d+)(?:,\s*(\d+))?\)$/);
			
			return "#" + hex(rgb[1]) + hex(rgb[2]) + hex(rgb[3]);
		}
		
		
	};
	
	/**
	 * get rgb from hex values
	 */
	this.convertHexToRGB = function(hex) {
		var hex = parseInt(((hex.indexOf('#') > -1) ? hex.substring(1) : hex), 16);
		return [hex >> 16,(hex & 0x00FF00) >> 8,(hex & 0x0000FF)];
	};
	
	/**
	 * strip slashes to some string
	 */
	this.stripslashes = function(str) {
		return (str + '').replace(/\\(.?)/g, function (s, n1) {
			switch (n1) {
				case '\\':
				return '\\';
				case '0':
				return '\u0000';
				case '':
				return '';
				default:
				return n1;
			}
		});
	};
	
	/**
	 * strip html tags
	 */
	this.stripTags = function(input, allowed) {
	    allowed = (((allowed || "") + "").toLowerCase().match(/<[a-z][a-z0-9]*>/g) || []).join(''); // making sure the allowed arg is a string containing only tags in lowercase (<a><b><c>)
	    var tags = /<\/?([a-z][a-z0-9]*)\b[^>]*>/gi,
	        commentsAndPhpTags = /<!--[\s\S]*?-->|<\?(?:php)?[\s\S]*?\?>/gi;
	    return input.replace(commentsAndPhpTags, '').replace(tags, function ($0, $1) {
	        return allowed.indexOf('<' + $1.toLowerCase() + '>') > -1 ? $0 : '';
	    });
	};
	
	
	/**
	 * escape html, turn html to a string
	 */
	this.htmlspecialchars = function(string){
		  return string
		      .replace(/&/g, "&amp;")
		      .replace(/</g, "&lt;")
		      .replace(/>/g, "&gt;")
		      .replace(/"/g, "&quot;")
		      .replace(/'/g, "&#039;");
	};
	
	
	/**
	 * capitalize first letter
	 */
	this.capitalizeFirstLetter = function(str){
		
		str = str.substr(0, 1).toUpperCase() + str.substr(1).toLowerCase();
		return(str);
	};
	
	
	/**
	 * get transparency value from 0 to 100
	 */
	this.getTransparencyFromRgba = function(rgba, inPercent){
		var temp = rgba.split(',');
		if(temp.length == 4){
			inPercent = (typeof inPercent !== 'undefined') ? inPercent : true;
			return (inPercent) ? temp[3].replace(/[^\d.]/g, "") : temp[3].replace(/[^\d.]/g, "") * 100;
		}
		
		return false;
	};
	
	
	this.__________PATHS_AND_URLS_____ = function(){};	
	
	
	/**
	 * get base name from path
	 */
	this.pathinfo = function(path) {
		var obj = {};
		
		if(typeof path == "object"){
			trace(path);
			throw new Error("pathinfo error: path is object");
		}
		
		obj.basename = path.replace(/\\/g,'/').replace(/.*\//, '');
		obj.filename = obj.basename.substr(0,obj.basename.lastIndexOf('.'));
		
		return(obj);
	}
	
	
	/**
	 * strip path slashes from both sides
	 */
	this.stripPathSlashes = function(path){
		return path.replace(/^\/|\/$/g, '');		
	}
	
	
	
	
	
	/**
	 * convert to full url
	 */
	this.urlToFull = function(url, urlBase){
		
		if(!url)
			return(url);
		
		if(!urlBase)
			var urlBase = g_urlBaseUC;
		
		//try to convert assets path from provider
		url = g_providerAdmin.urlAssetsToFull(url);
				
		var urlSmall = url.toLowerCase();
		
		if(urlSmall.indexOf("http://") !== -1 || urlSmall.indexOf("https://") !== -1)
			return(url);
		
		if(url.indexOf(urlBase) !== -1)
			return(url);
		
		url = jQuery.trim(url);
		
		if(!url || url == "")
			return("");
		
		url = urlBase + url;
		return(url);
	}
	
	
	/**
	 * convert to relative url
	 */
	this.urlToRelative = function(url, urlBase){
		
		if(!urlBase)
			var urlBase = g_urlBaseUC;
		
		url = url.replace(urlBase, "");
		return(url);
	}

	/**
	 * get url of some view
	 */
	this.getUrlView = function(view, options){
		var url = g_urlViewBaseUC+"&view="+view;
		
		if(options && options != "")
			url += "&"+options;
		
		return(url);
	};
	
	
	/**
	 * get current view url
	 */
	this.getUrlCurrentView = function(options){
		var url = g_urlViewBaseUC+"&view=" + g_view;
		
		if(options)
			url += "&"+options;
		
		return(url);
	};
	
		
	
	
	
	this.__________VALIDATION_FUNCTIONS_____ = function(){};	
	
	/**
	 * validate that field not empty
	 */
	this.validateNotEmpty = function(val, fieldName){
		if(typeof val == "undefined" || jQuery.trim(val) == "")
			throw new Error("Please fill <b>"+ fieldName + "</b> field");
	}
	
	
	/**
	 * validate name field
	 */
	this.validateNameField = function(val, fieldName){
		
		var errorMessage = "The field <b>"+ fieldName + "</b> allow only english lowercase letters, numbers and underscore. Example: first_name ";
		
		var regex = /^[a-z0-9_]+$/;
	    if(regex.test(val) == false)
			throw new Error(errorMessage);
	}
	
	
	this._____________DIALOGS__________ = function(){};
	
	
	/**
	 * set image browser dialog path
	 */
	this.setAddImagePath = function(path, url){
		
		if(typeof g_providerAdmin.setPathSelectImages == "function")
			g_providerAdmin.setPathSelectImages(path, path, url);
		
	}
	
	
	/**
	 * open "add image" dialog
	 */
	this.openAddImageDialog = function(title, onInsert, isMultiple, source){
		
		g_providerAdmin.openAddImageDialog(title, onInsert, isMultiple, source);
		
	};
	
	
	/**
	 * open video dialog
	 */
	this.openVideoDialog = function(callbackFunction, itemData){
		
		g_ugMediaDialog.openVideoDialog(callbackFunction, itemData);
		
	};
	
	/**
	 * common dialog ajax request
	 */
	this.dialogAjaxRequest = function(dialogID, action, data, funcSuccess){
		dialogID = dialogID.replace("#", "");
		
		g_ucAdmin.setAjaxLoaderID(dialogID + "_loader");
		g_ucAdmin.setErrorMessageID(dialogID + "_error");
		g_ucAdmin.setSuccessMessageID(dialogID + "_success");
		g_ucAdmin.setAjaxHideButtonID(dialogID + "_action");
		
		g_ucAdmin.ajaxRequest(action, data, function(response){
			
			jQuery("#"+dialogID).dialog("close");
			
			if(typeof(funcSuccess) == "function")
				funcSuccess(response);
			
		});
		
	}
	
	/**
	 * open common dialog with all inside actions
	 */
	this.openCommonDialog = function(id, onOpen, options){
		
		if(typeof id == "object"){
			var id = id.prop("id");
			if(!id)
				throw new Error("The dialog should have ID");
			
			id = "#"+id;
		}
		
		if(id.charAt(0) != "#")
			id = "#"+id;
		
		var buttonOpts = {};
		
		buttonOpts[g_uctext.cancel] = function(){
			jQuery(id).dialog("close");
		};

		jQuery(id+"_loader").hide();
		jQuery(id+"_error").hide();
		jQuery(id+"_success").hide();
		jQuery(id+"_action").show();
		
		var dialogOptions = {
				buttons:buttonOpts,
				minWidth:600,
				modal:true,
				open:function(){
					if(typeof onOpen == "function")
						onOpen();
				}
			};
				
		if(options && typeof options == "object")
			dialogOptions = jQuery.extend(dialogOptions, options);
		
		
		jQuery(id).dialog(dialogOptions);
		
	}
	
	
	this.__________AJAX_REQUEST_____ = function(){};
	
	/**
	 * show error message or call once custom handler function
	 */
	this.showErrorMessage = function(htmlError){
		
		if(g_errorMessageID !== null){
			switch(typeof g_errorMessageID){
				case "object":
					g_errorMessageID.show().html(htmlError);
				break;
				case "function":
					g_errorMessageID(htmlError);
				break;
				default:
					jQuery("#"+g_errorMessageID).show().html(htmlError);
				break;
			}
			
		}else
			jQuery("#error_message").show().html(htmlError);
		
		showAjaxButton();
	};

	/**
	 * hide error message
	 */
	var hideErrorMessage = function(){
		
		if(g_errorMessageID !== null){
			switch(typeof g_errorMessageID){
				case "object":
					g_errorMessageID.hide();
				break;
				case "string":
					jQuery("#"+g_errorMessageID).hide();
				break;
			}
			
			if(g_hideMessageCounter > 0){
				g_hideMessageCounter = 0;
				g_errorMessageID = null;
			}else
				g_hideMessageCounter++;
		}else
			jQuery("#error_message").hide();
	};
	
	
	/**
	 * set error message id
	 */
	this.setErrorMessageID = function(id){
		g_errorMessageID = id;
		g_hideMessageCounter = 0;
	};
	
	
	
	/**
	 * set success message id
	 */
	this.setSuccessMessageID = function(id){
		g_successMessageID = id;
	};
	
	/**
	 * show success message
	 */
	this.showSuccessMessage = function(htmlSuccess){
		
		var id = "#success_message";		
		var delay = 2000;
		if(g_successMessageID){
			id = "#"+g_successMessageID;
			delay = 500;
		}
		
		jQuery(id).show().html(htmlSuccess);
		
		setTimeout(t.hideSuccessMessage,delay);
	};
	
	
	/**
	 * hide success message
	 */
	this.hideSuccessMessage = function(){
		
		if(g_successMessageID){
			jQuery("#"+g_successMessageID).hide();
			g_successMessageID = null;	//can be used only once.
		}
		else
			jQuery("#success_message").slideUp("slow").fadeOut("slow");
		
		showAjaxButton();
	};
	
	
	/**
	 * set ajax loader id that will be shown, and hidden on ajax request
	 * this loader will be shown only once, and then need to be sent again.
	 */
	this.setAjaxLoaderID = function(id){
		g_ajaxLoaderID = id;
	};
	
	/**
	 * show loader on ajax actions
	 */
	var showAjaxLoader = function(){
		if(g_ajaxLoaderID)
			jQuery("#"+g_ajaxLoaderID).show();
	};
	
	/**
	 * hide and remove ajax loader. next time has to be set again before "ajaxRequest" function.
	 */
	var hideAjaxLoader = function(){
		if(g_ajaxLoaderID){
			jQuery("#"+g_ajaxLoaderID).hide();
			g_ajaxLoaderID = null;
		}
	};
	
	/**
	 * set button to hide / show on ajax operations.
	 */
	this.setAjaxHideButtonID = function(buttonID){
		g_ajaxHideButtonID = buttonID;
	};
	
	/**
	 * if exist ajax button to hide, hide it.
	 */
	function hideAjaxButton(){
		if(g_ajaxHideButtonID)
			jQuery("#"+g_ajaxHideButtonID).hide();
	};
	
	/**
	 * if exist ajax button, show it, and remove the button id.
	 */
	function showAjaxButton(){
		if(g_ajaxHideButtonID){
			jQuery("#"+g_ajaxHideButtonID).show();
			g_ajaxHideButtonID = null;
		}		
	};

	
	/**
	 * add url param
	 */
	function addUrlParam(url, param, value){
		
		if(url.indexOf("?") == -1)
			url += "?";
		else
			url += "&";
		
		if(typeof value == "undefined")
			url += param;
		else	
			url += param + "=" + value;
		
		return(url);
	}
	
	
	/**
	 * get ajax url with action and params
	 */
	this.getUrlAjax = function(action, params){
		
		var url = g_urlAjaxActionsUC;
		
		url = addUrlParam(url, "action", g_pluginNameUC+"_ajax_action");
		
		if(typeof g_ugNonce == "string")
			url = addUrlParam(url, "nonce", g_ugNonce);
		
		if(action)
			url = addUrlParam(url, "client_action", action);
		
		if(params)
			url = addUrlParam(url, params);
		
		return(url);
	}
	
	/**
	 * add form files to data
	 */
	this.addFormFilesToData = function(formID, objData){

		var objForm = jQuery("#"+formID);
		if(objForm.length == 0)
			throw new Error("form with ID: "+ formID + " not found");
		
    	var objFiles = objForm.find("input[type='file']");
    	if(objFiles.length == 0)
			throw new Error("no file inputs found in form: " + formID);
    	
    	jQuery.each(objFiles, function(index, objFile){
    		var fieldName = objFile.name;
    		
    		jQuery.each(objFile.files, function(index2, file){
    			objData.append(fieldName, file);
    		});
    	});
		
	}
	
	
	/**
	 * Ajax request function. call wp ajax, if error - print error message.
	 * if success, call "success function" 
	 */
	this.ajaxRequest = function(action,data,successFunction){
		
		if(typeof data == "undefined")
			var data = {};
		
		//raw mode - for including file uploads
		var isRawMode = false;
		if(typeof data.append == "function"){
			isRawMode = true;
			var objData = data;
			objData.append("action", g_pluginNameUC+"_ajax_action");
			objData.append("client_action", action);
			
		}else{
			
			//simple mode
			var objData = {
					action:g_pluginNameUC+"_ajax_action",
					client_action:action,
					data:data
				};
		}
			
		
		hideErrorMessage();
		showAjaxLoader();
		hideAjaxButton();
		
		var ajaxOptions = {
				type:"post",
				url:g_urlAjaxActionsUC,
				dataType: 'json',
				data:objData,
				success:function(response){
					hideAjaxLoader();
					
					if(!response){
						t.showErrorMessage("Empty ajax response!");
						return(false);					
					}

					if(response == -1){
						t.showErrorMessage("ajax error!!!");
						return(false);
					}
					
					if(response == 0){
						t.showErrorMessage("ajax error, action: <b>"+action+"</b> not found");
						return(false);
					}
					
					if(response.success == undefined){
						t.showErrorMessage("The 'success' param is a must!");
						return(false);
					}
					
					if(response.success == false){
						t.showErrorMessage(response.message);
						return(false);
					}
					
					//success actions:

					//run a success event function
					if(typeof successFunction == "function"){
						
						//show success message only if custom id exists
						if(response.message && g_successMessageID)
							t.showSuccessMessage(response.message);
						
						successFunction(response);
					}
					else{
						if(response.message)
							t.showSuccessMessage(response.message);
					}
					
					if(response.is_redirect)
						location.href=response.redirect_url;
				
				},		 	
				error:function(jqXHR, textStatus, errorThrown){
					hideAjaxLoader();
					
					if(textStatus == "parsererror")
						t.debug(jqXHR.responseText);
					
					t.showErrorMessage("Ajax Error!!! " + textStatus);
				}
		}
		
		//add some options for raw mode
		if(isRawMode == true){
			ajaxOptions.global = false;
			ajaxOptions.processData = false;
			ajaxOptions.contentType = false;
		}
		
		jQuery.ajax(ajaxOptions);
		
	};//ajaxrequest

	
	
	
	/**
	 * ajax request for creating thumb from image and get thumb url
	 * instead of the url can get image id as well
	 */
	this.requestThumbUrl = function(urlImage, imageID, callbackFunction){
		
		var data = {
				urlImage: urlImage,
				imageID: imageID
		};
		
		t.ajaxRequest("get_thumb_url",data, function(response){
			callbackFunction(response.urlThumb);
		});
		
	};
	
	
	/**
	 * init version dialog
	 */
	function initVersionDialog(){
		
		/**
		 * open the version dialog
		 */
		jQuery("#uc_version_link").click(function(){
			var objDialog = jQuery("#uc_dialog_version");
						
			var buttonOpts = {};
			buttonOpts[g_uctext.cancel] = function(){
				objDialog.dialog("close");
			};

			objDialog.dialog({
				dialogClass:"unite-ui",
				buttons:buttonOpts,
				minWidth:900,
				modal:true,
				open:function(){
					var objContent = jQuery("#uc_dialog_version_content");
					var isContentLoaded = objContent.data("loaded");
					if(isContentLoaded === true)
						return(false);
					
					t.ajaxRequest("get_version_text", {}, function(response){
						var html = "<pre>"+response.text+"</pre>";
						objContent.html(html);
						objContent.data("loaded", true);
					});
					
				}
			});
			
			
		});
		
		
		
	}
	
	this.__________GLOBAL_INIT_____ = function(){};
	
	/**
	 * global init
	 */
	this.globalInit = function(){
		
		g_providerAdmin.init();
		
		if(typeof g_ugMediaDialog != "undefined")
			g_ugMediaDialog.init();
		
		initVersionDialog();
	};
	
	
}

if(!g_ucAdmin)
	var g_ucAdmin;


//user functions:
function trace(data,clear){
	
	if(!g_ucAdmin)
		g_ucAdmin = new UniteAdminUC();
	
	g_ucAdmin.trace(data,clear);
}

function clearTrace(){
	
	console.clear();
}

function debug(data){
	
	if(!g_ucAdmin)
		g_ucAdmin = new UniteAdminUC();
	
	g_ucAdmin.debug(data);
}


//run the init function
jQuery(document).ready(function(){
	
	if(!g_ucAdmin)
		g_ucAdmin = new UniteAdminUC();
	
	g_ucAdmin.globalInit();
	
});

