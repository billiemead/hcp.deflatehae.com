<?php
/**
 * @package Addon Creator
 * @author UniteCMS.net / Valiano
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('_JEXEC') or die('Restricted access');


	class GlobalsUC{
		
		const SHOW_TRACE = true;
		const SHOW_TRACE_FRONT = false;
		
		const ENABLE_TRANSLATIONS = false;
		
		const PLUGIN_TITLE = "Addon Creator for Visual Composer";
		const PLUGIN_NAME = "unitecreator";
		
		const TABLE_ADDONS_NAME = "unitecreator_addons";
		const TABLE_CATEGORIES_NAME = "unitecreator_categories";
		
		const VIEW_DEFAULT = "addons";
		const VIEW_ADDONS_LIST = "addons";
		const VIEW_EDIT_ADDON = "addon";
		const VIEW_ASSETS = "assets";
		const VIEW_SETTINGS = "settings";
		const VIEW_TEST_ADDON = "testaddon";
		const VIEW_MEDIA_SELECT = "mediaselect";
		
		const HOURS_REFRESH_INDEX = 24;
		
		const DEFAULT_JPG_QUALITY = 81;
		const THUMB_WIDTH = 300;
		const DIR_THUMBS = "unitecreator_thumbs";
		const DIR_THEME_ADDONS = "ac_addons";
		
		
		public static $table_addons;
		public static $table_categories;
		
		public static $pathSettings;
		public static $filepathItemSettings;
		public static $pathPlugin;
		public static $pathTemplates;
		public static $pathViews;
		public static $pathLibrary;
		public static $pathAssets;
		public static $pathProvider;
		public static $pathProviderViews;
		public static $pathProviderTemplates;
		
		public static $url_base;
		public static $url_images;
		public static $url_component_client;
		public static $url_component_admin;
		public static $url_ajax;
		public static $url_ajax_front;
		public static $url_default_addon_icon;
		
		public static $urlPlugin;
		public static $url_provider;
		public static $url_assets;
		public static $url_assets_libraries;
		
		
		public static $is_admin;
		public static $path_base;
		public static $path_cache;
		public static $path_images;
		
		public static $arrClientSideText = array();
		
		
		/**
		 * init globals
		 */
		public static function initGlobals(){

			UniteProviderFunctionsUC::initGlobalsBase();
			
			self::$pathProvider = self::$pathPlugin."provider/";
			self::$pathTemplates = self::$pathPlugin."views/templates/";
			self::$pathViews = self::$pathPlugin."views/";
			self::$pathLibrary = self::$pathPlugin."library/";
			self::$pathSettings = self::$pathPlugin."settings/";
			
			self::$pathProviderViews = self::$pathProvider."views/";
			self::$pathProviderTemplates = self::$pathProvider."views/templates/";
			
			self::$filepathItemSettings = self::$pathSettings."item_settings.php";
						
			self::initClientSideText();
			
			//GlobalsUC::printVars();
		}

		
		/**
		 * init client side text for globals
		 */
		public static function initClientSideText(){
		
			self::$arrClientSideText = array(
					"add_item"=>__("Add Item",UNITECREATOR_TEXTDOMAIN),
					"update_item"=>__("Update Item",UNITECREATOR_TEXTDOMAIN),
					"edit_item"=>__("Edit Item",UNITECREATOR_TEXTDOMAIN),
					"close"=>__("Close",UNITECREATOR_TEXTDOMAIN),
					"cancel"=>__("Cancel",UNITECREATOR_TEXTDOMAIN),
					"update"=>__("Update",UNITECREATOR_TEXTDOMAIN),
					"restore"=>__("Restore",UNITECREATOR_TEXTDOMAIN),
					"updating"=>__("Updating...",UNITECREATOR_TEXTDOMAIN),
					"restoring"=>__("Restoring...",UNITECREATOR_TEXTDOMAIN),
					"import"=>__("Import",UNITECREATOR_TEXTDOMAIN),
					"adding_category"=>__("Adding Category...",UNITECREATOR_TEXTDOMAIN),
					"do_you_sure_remove"=>__("Do you sure to remove this category and it's addons?",UNITECREATOR_TEXTDOMAIN),
					"removing_category"=>__("Removing Category...",UNITECREATOR_TEXTDOMAIN),
					"updating_categories_order"=>__("Updating Categories Order...",UNITECREATOR_TEXTDOMAIN),
					"removing_addons"=>__("Removing Addons...",UNITECREATOR_TEXTDOMAIN),
					"updating_addon_title"=>__("Updating Title...",UNITECREATOR_TEXTDOMAIN),
					"duplicating_addons"=>__("Duplicating Addons...",UNITECREATOR_TEXTDOMAIN),
					"updating_addons_order"=>__("Updating Addons Order...",UNITECREATOR_TEXTDOMAIN),
					"updating_addons"=>__("Updating Addons...",UNITECREATOR_TEXTDOMAIN),
					"copying_addons"=>__("Copying Addons...",UNITECREATOR_TEXTDOMAIN),
					"moving_addons"=>__("Moving Addons...",UNITECREATOR_TEXTDOMAIN),
					"confirm_remove_addons"=>__("Are you sure you want to delete these addons?",UNITECREATOR_TEXTDOMAIN),
					"uc_textfield"=>__("Text Field",UNITECREATOR_TEXTDOMAIN),
					"uc_textarea"=>__("Text Area",UNITECREATOR_TEXTDOMAIN),
					"uc_checkbox"=>__("Checkbox",UNITECREATOR_TEXTDOMAIN),
					"uc_dropdown"=>__("Dropdown",UNITECREATOR_TEXTDOMAIN),
					"uc_radioboolean"=>__("Radio Boolean",UNITECREATOR_TEXTDOMAIN),
					"uc_number"=>__("Number",UNITECREATOR_TEXTDOMAIN),
					"uc_colorpicker"=>__("Color Picker",UNITECREATOR_TEXTDOMAIN),
					"uc_editor"=>__("Editor",UNITECREATOR_TEXTDOMAIN),
					"uc_image"=>__("Image",UNITECREATOR_TEXTDOMAIN),
					"uc_imagebase"=>__("Image Fields",UNITECREATOR_TEXTDOMAIN),
					"choose_image"=>__("Choose Image",UNITECREATOR_TEXTDOMAIN),
					"edit_file"=>__("Edit File",UNITECREATOR_TEXTDOMAIN),
					"save"=>__("Save",UNITECREATOR_TEXTDOMAIN),
					"delete_op"=>__("Delete",UNITECREATOR_TEXTDOMAIN),
					"duplicate_op"=>__("Duplicate",UNITECREATOR_TEXTDOMAIN),
					"delete_include"=>__("Delete Include",UNITECREATOR_TEXTDOMAIN),
					"add_include"=>__("Add Include",UNITECREATOR_TEXTDOMAIN),
					"include_settings"=>__("Include Settings",UNITECREATOR_TEXTDOMAIN),
					"always"=>__("Always",UNITECREATOR_TEXTDOMAIN),
					"never_include"=>__("Never Include",UNITECREATOR_TEXTDOMAIN),
					"not_selected"=>__("Not Selected",UNITECREATOR_TEXTDOMAIN)
				);
		
		}
		
		
		/**
		 * print all globals variables
		 */
		public static function printVars(){
			$methods = get_class_vars( "GlobalsUC" );
			dmp($methods);
			exit();
		}
		
	}

	//init the globals
	GlobalsUC::initGlobals();
	
?>
