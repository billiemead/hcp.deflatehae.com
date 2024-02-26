<?php
$headerTitle = __("Addons", UNITECREATOR_TEXTDOMAIN);
require HelperUC::getPathTemplate("header");

?>

	<div class="content_wrapper">
		
		<?php $objManager->outputHtml() ?>
			
	</div>

	<?php 
		
		if(method_exists("UniteProviderFunctionsUC", "putUpdatePluginHtml"))
			UniteProviderFunctionsUC::putUpdatePluginHtml();
	
	?>