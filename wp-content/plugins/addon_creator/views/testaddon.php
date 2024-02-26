<?php

defined('_JEXEC') or die;


$addonID = UniteFunctionsUC::getGetVar("id");

if(empty($addonID))
	UniteFunctionsUC::throwError("Addon ID not given");

$addon = new UniteCreatorAddon();
$addon->initByID($addonID);
$addonTitle = $addon->getTitle();

$urlEditAddon = HelperUC::getViewUrl_EditAddon($addonID);

$urlTestWithData = HelperUC::getViewUrl_TestAddon($addonID, "loaddata=test");


//init addon config
$addonConfig = new UniteCreatorAddonConfig();
$addonConfig->setStartAddon($addon);

$isTestData1 = $addon->isTestDataExists(1);

//get addon data
$addonData = null;
$isLoadData = UniteFunctionsUC::getGetVar("loaddata");

if($isLoadData == "test" && $isTestData1 == true)
	$addon->setValuesFromTestData(1);

$isPreviewMode = UniteFunctionsUC::getGetVar("preview");
$isPreviewMode = UniteFunctionsUC::strToBool($isPreviewMode);
$addonConfig->startWithPreview($isPreviewMode);


require HelperUC::getPathTemplate("test_addon");