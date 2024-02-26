<?php

defined('_JEXEC') or die;

$headerTitle = __("General Settings", UNITECREATOR_TEXTDOMAIN);
require HelperUC::getPathTemplate("header");

$operations = new UCOperations();

$objSettings = $operations->getGeneralSettingsObject();

$objOutput = new UniteSettingsOutputWideUC();
$objOutput->init($objSettings);

$randomString = UniteFunctionsUC::getRandomString(5, true);


require HelperUC::getPathTemplate("settings");

