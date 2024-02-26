<?php
/*
Plugin Name: Addon Creator for Visual Composer
Plugin URI: http://addon-creator.com
Description: Addon Creator - create addons for Visual Composer
Author: Unite CMS
Version: 1.1.4
Author URI: http://addon-creator.com
*/

//ini_set("display_errors", "on");
//ini_set("error_reporting", E_ALL);

if(!defined("_JEXEC"))
	define("_JEXEC", true);

$mainFilepath = __FILE__;
$currentFolder = dirname($mainFilepath);


//phpinfo();
require_once $currentFolder.'/includes.php';

require_once  GlobalsUC::$pathProvider."provider_main_file.php";


