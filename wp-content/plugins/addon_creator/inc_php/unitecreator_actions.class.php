<?php

class UniteCreatorActions{

	
	/**
	 * on ajax action
	 */
	public function onAjaxAction(){
		
		$actionType = UniteFunctionsUC::getPostGetVariable("action");
		
		if($actionType != "unitecreator_ajax_action")
			return(false);
		
		$operations = new UCOperations();
		$addons = new UniteCreatorAddons();
		$assets = new UniteCreatorAssetsWork();
		$categories = new UniteCreatorCategories();
		
		$action = UniteFunctionsUC::getPostGetVariable("client_action");
		
		$data = UniteFunctionsUC::getPostGetVariable("data");
		if(empty($data))
			$data = $_REQUEST;
		
		if(is_string($data)){
			$arrData = (array)json_decode($data);
		
			if(empty($arrData)){
				$arrData = stripslashes(trim($data));
				$arrData = (array)json_decode($arrData);
			}
						
			$data = $arrData;
		}
		
		$data = UniteFunctionsUC::convertStdClassToArray($data);
		
		$data = UniteProviderFunctionsUC::normalizeAjaxInputData($data);
		
		
		try{
		
			switch($action){
				case "create_addon":
					$addonID = $addons->createFromData($data);
					$urlRedirect = HelperUC::getViewUrl_EditAddon($addonID);
					HelperUC::ajaxResponseSuccessRedirect(__("The addon created successfully",UNITECREATOR_TEXTDOMAIN), $urlRedirect);
					break;
				case "update_addon":
					$response = $addons->updateAddonFromData($data);
					HelperUC::ajaxResponseSuccess(__("Updated.",UNITECREATOR_TEXTDOMAIN),$response);
					break;
				case "delete_addon":
					$addons->deleteAddonFromData($data);
					HelperUC::ajaxResponseSuccess(__("The addon deleted successfully",UNITECREATOR_TEXTDOMAIN));
				break;
				case "add_category":
					$catData = $categories->addFromData();
					HelperUC::ajaxResponseData($catData);
				break;
				case "remove_category":
					$response = $categories->removeFromData($data);
				
					HelperUC::ajaxResponseSuccess(__("The category deleted successfully",UNITECREATOR_TEXTDOMAIN),$response);
				break;
				case "update_category":
				
					$categories->updateFromData($data);
					HelperUC::ajaxResponseSuccess(__("Category updated",UNITECREATOR_TEXTDOMAIN));
				break;
				case "update_cat_order":
					$categories->updateOrderFromData($data);
					HelperUC::ajaxResponseSuccess(__("Order updated",UNITECREATOR_TEXTDOMAIN));
				break;
				case "get_cat_addons":
					$responeData = $addons->getCatAddonsHtmlFromData($data);
					
					HelperUC::ajaxResponseData($responeData);
				break;
				case "add_addon":
					$response = $addons->createFromManager($data);
					
					HelperUC::ajaxResponseSuccess(__("Addon added successfully",UNITECREATOR_TEXTDOMAIN), $response);
				break;
				case "update_addon_title":
					$addons->updateAddonTitleFromData($data);
					
					HelperUC::ajaxResponseSuccess(__("Addon updated successfully",UNITECREATOR_TEXTDOMAIN));
				break;
				case "update_addons_activation":
					$addons->activateAddonsFromData($data);
					
					HelperUC::ajaxResponseSuccess(__("Addons updated successfully",UNITECREATOR_TEXTDOMAIN));
				break;
				case "remove_addons":
					$response = $addons->removeAddonsFromData($data);
					
					HelperUC::ajaxResponseSuccess(__("Addons Removed",UNITECREATOR_TEXTDOMAIN), $response);
				break;
				case "update_addons_order":
					$addons->saveOrderFromData($data);

					HelperUC::ajaxResponseSuccess(__("Order Saved",UNITECREATOR_TEXTDOMAIN));
				break;
				case "move_addons":
					$response = $addons->moveAddonsFromData($data);
					HelperUC::ajaxResponseSuccess(__("Done Operation",UNITECREATOR_TEXTDOMAIN),$response);
				break;
				case "duplicate_addons":
					$response = $addons->duplicateAddonsFromData($data);
					HelperUC::ajaxResponseSuccess(__("Addons Duplicated",UNITECREATOR_TEXTDOMAIN),$response);
				break;
				case "get_addon_config_html":
					$html = $addons->getAddonConfigHTML($data);
					
					HelperUC::ajaxResponseData(array("html"=>$html));
				break;
				case "show_preview":
					$addons->showAddonPreviewFromData($data);
					exit();
				break;
				case "save_test_addon":
					$addons->saveTestAddonData($data);
					HelperUC::ajaxResponseSuccess(__("Saved",UNITECREATOR_TEXTDOMAIN));
				break;
				case "get_test_addon_data":
					$response = $addons->getTestAddonData($data);
					HelperUC::ajaxResponseData($response);
				break;
				case "delete_test_addon_data":
					$addons->deleteTestAddonData($data);
					HelperUC::ajaxResponseSuccess(__("Test data deleted",UNITECREATOR_TEXTDOMAIN));
				break;
				case "export_addon":
					$addons->exportAddon($data);
					exit();
				break;
				case "import_addons":
					$response = $addons->importAddons($data);
					
					HelperUC::ajaxResponseSuccess(__("Addons Imported",UNITECREATOR_TEXTDOMAIN),$response);
				break;
				case "get_version_text":
					$content = HelperHtmlUC::getVersionText();
					HelperUC::ajaxResponseData(array("text"=>$content));
				break;
				case "update_plugin":
				
					if(method_exists("UniteProviderFunctionsUC", "updatePlugin"))
						UniteProviderFunctionsUC::updatePlugin();
					else{
						echo "Functionality Don't Exists";
						exit();
					}
				
				break;
				case "update_general_settings":
					$operations->updateGeneralSettingsFromData($data);
					
					HelperUC::ajaxResponseSuccess(__("Settings Saved",UNITECREATOR_TEXTDOMAIN),$response);
				break;
				default:

					$found = $assets->checkAjaxActions($action, $data);
					
					if(!$found)
						HelperUC::ajaxResponseError("wrong ajax action: <b>$action</b> ");
				break;
			}
		
		}
		catch(Exception $e){
			$message = $e->getMessage();
		
			$errorMessage = $message;
			if(GlobalsUC::SHOW_TRACE == true){
				$trace = $e->getTraceAsString();
				$errorMessage = $message."<pre>".$trace."</pre>";
			}
		
			HelperUC::ajaxResponseError($errorMessage);
		}
		
		//it's an ajax action, so exit
		HelperUC::ajaxResponseError("No response output on <b> $action </b> action. please check with the developer.");
		exit();
		
	}
	
}