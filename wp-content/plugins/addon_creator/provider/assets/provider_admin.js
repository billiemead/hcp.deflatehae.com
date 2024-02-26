
//global variables
var g_dataProviderUC = {
		pathSelectImages: null,
		pathSelectImagesBase:null,
		urlSelectImagesBase:null,
		objBrowserImages: null
};

function UniteProviderAdminUC(){
	
	var t = this;
	
	var g_temp = {
			keyUrlAssets: "[url_assets]/"
	};
	
	
	/**
	 * open new add image dialog
	 */
	function openNewImageDialog(title,onInsert,isMultiple){
		
		if(isMultiple == undefined)
			isMultiple = false;
		
		// Media Library params
		var frame = wp.media({
			//frame:      'post',
            //state:      'insert',
			title : title,
			multiple : isMultiple,
			library : { type : 'image'},
			button : { text : 'Insert' }
		});

		// Runs on select
		frame.on('select',function(){
			var objSettings = frame.state().get('selection').first().toJSON();
			
			var selection = frame.state().get('selection');
			var arrImages = [];
			
			if(isMultiple == true){		//return image object when multiple
			    selection.map( function( attachment ) {
			    	var objImage = attachment.toJSON();
			    	var obj = {};
			    	obj.url = objImage.url;
			    	obj.id = objImage.id;
			    	arrImages.push(obj);
			    });
				onInsert(arrImages);
			}else{		//return image url and id - when single
				onInsert(objSettings.url, objSettings.id);
			}
			    
		});

		// Open ML
		frame.open();
	}
	
	
	/**
	 * open old add image dialog
	 */
	function openOldImageDialog(title,onInsert){
		var params = "type=image&post_id=0&TB_iframe=true";
		
		params = encodeURI(params);
		
		tb_show(title,'media-upload.php?'+params);
		
		window.send_to_editor = function(html) {
			 tb_remove();
			 var urlImage = jQuery(html).attr('src');
			 if(!urlImage || urlImage == undefined || urlImage == "")
				var urlImage = jQuery('img',html).attr('src');
			
			onInsert(urlImage,"");	//return empty id, it can be changed
		}
	}
	
	
	
	/**
	 * open "add image" dialog
	 */
	this.openAddImageDialog = function(title, onInsert, isMultiple, source){
		
		if(source == "addon"){
			openAddonImageSelectDialog(title, onInsert);
			return(false);
		}
		
		if(typeof wp != "undefined" && typeof wp.media != "undefined")
			openNewImageDialog(title,onInsert,isMultiple);
		else{
			openOldImageDialog(title,onInsert);
		}
				
	};
	
	
	/**
	 * get shortcode
	 */
	this.getShortcode = function(alias){
				
		var shortcode = "[uniteaddon "+alias +"]";
		
		if(alias == "")
			shortcode = "";
		
		return(shortcode);
	};
	
	
	/**
	 * init update plugin button
	 */
	function initUpdatePlugin(){

		var objButton = jQuery("#uc_button_update_plugin");
		
		if(objButton.length == 0)
			return(false);
		
		//init update plugin button
		objButton.click(function(){

			jQuery("#dialog_update_plugin").dialog({
				minWidth:600,
				minHeight:400,
				modal:true,
			});
			
		});
		
	}

	
	/**
	 * init internal image select dialog
	 */
	function initImageSelectDialog(){
		
		//init assets browser
		var g_browserImagesWrapper = jQuery("#uc_dialogimage_browser");
		
		if(g_browserImagesWrapper.length == 0)
			return(false);
		
		if(typeof UCAssetsManager == "undefined")
			return(false);
		
		g_dataProviderUC.objBrowserImages = new UCAssetsManager();
		g_dataProviderUC.objBrowserImages.init(g_browserImagesWrapper);
		
		//update folder for next time open
		g_dataProviderUC.objBrowserImages.eventOnUpdateFilelist(function(){
			
			var path = g_dataProviderUC.objBrowserImages.getActivePath();
			
			t.setPathSelectImages(path);
			
		});
		
		
		//on select some file
		g_dataProviderUC.objBrowserImages.eventOnSelectOperation(function(event, objItem){
						
			//get relative url
			var urlRelative = objItem.file;
			
			if(g_dataProviderUC.pathSelectImagesBase){
				urlRelative = objItem.url.replace(g_dataProviderUC.pathSelectImagesBase+"/", "");
			}
			
			var objInput = jQuery("#uc_dialog_image_select_url");
			
			objItem.url_assets_relative = g_temp.keyUrlAssets + urlRelative;
			
			objInput.val(urlRelative);
			objInput.data("item", objItem).val(urlRelative);
			
			//enable button
			g_ucAdmin.enableButton("#uc_dialog_image_select_button");
			
		});
		
		
		/**
		 * on select button click - close the dialog and run oninsert function
		 */
		jQuery("#uc_dialog_image_select_button").click(function(){
			
			if(g_ucAdmin.isButtonEnabled("#uc_dialog_image_select_button") == false)
				return(false);
			
			var objDialog = jQuery("#uc_dialog_image_select");
			var objInput = jQuery("#uc_dialog_image_select_url");
			
			var objItem = objInput.data("item");
			
			if(!objItem)
				throw new Error("please select some image");
						
			var funcOnInsert = objDialog.data("func_oninsert");
			
			if(typeof funcOnInsert != "function")
				throw new Error("on insert should be a function");
			
			funcOnInsert(objItem);
			
			objDialog.dialog("close");
			
		});
		
	}
	
	
	/**
	 * open addon image select dialog
	 */
	function openAddonImageSelectDialog(title, onInsert){
		
		var objDialog = jQuery("#uc_dialog_image_select");
		
		objDialog.data("func_oninsert", onInsert);
		
		objDialog.dialog({
			minWidth:900,
			minHeight:450,
			modal:true,
			title:title,
			open:function(){
				
				//clear the input
				var objInput = jQuery("#uc_dialog_image_select_url");
				objInput.data("url","").val("");
				
				//disable the button
				g_ucAdmin.disableButton("#uc_dialog_image_select_button");
				
				//set base start path (even if null)
				g_dataProviderUC.objBrowserImages.setCustomStartPath(g_dataProviderUC.pathSelectImagesBase);
				
				var loadPath = g_dataProviderUC.pathSelectImages;
				if(!loadPath)
					loadPath = g_pathAssetsUC;
				
				g_dataProviderUC.objBrowserImages.loadPath(loadPath, true);
				
			}
		});
		
		
	}
	
	/**
	 * convert url assets related to full url
	 */
	this.urlAssetsToFull = function(url){
		
		if(!url)
			return(url);
		
		if(!g_dataProviderUC.urlSelectImagesBase)
			return(url);
		
		var key = g_temp.keyUrlAssets;
		
		if(url.indexOf(key) == -1)
			return(url);
		
		url = url.replace(key, g_dataProviderUC.urlSelectImagesBase);
				
		return(url);
	}
	
	
	/**
	 * set select images path
	 */
	this.setPathSelectImages = function(path, basePath, baseUrl){
		
		if(basePath != null)
			g_dataProviderUC.pathSelectImagesBase = basePath;
			g_dataProviderUC.urlSelectImagesBase = baseUrl;
		
		g_dataProviderUC.pathSelectImages = path;
		
	}
	
	
	/**
	 * init the provider admin object
	 */
	this.init = function(){
		
		initUpdatePlugin();
		
		initImageSelectDialog();
		
	}
	
}

