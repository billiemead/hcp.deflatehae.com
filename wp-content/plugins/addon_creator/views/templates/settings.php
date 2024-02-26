<?php 
	defined('_JEXEC') or die('Restricted access');
?>

	<div id="uc_tabs" class="uc-tabs">
		<a data-contentid="uc_tab_general" class="uc-tab-selected" href="javascript:void(0)" onfocus="this.blur()"> <?php _e("General Settings", UNITECREATOR_TEXTDOMAIN)?></a>		
		<a data-contentid="uc_tab_developers" href="javascript:void(0)" onfocus="this.blur()"> <?php _e("Theme Developers", UNITECREATOR_TEXTDOMAIN)?></a>
		<div class="unite-clear"></div>
	</div>
	
	<div id="uc_tab_contents" class="uc-tabs-content-wrapper">
		<div id="uc_tab_general" class="uc-tab-content" >
			<?php
			
			$objOutput->draw("uc_general_settings",true);
			
			?>
			
			<div class="uc-button-action-wrapper">
				<a id="uc_button_save_settings" class="unite-button-primary" href="javascript:void(0)"><?php _e("Save Settings", UNITECREATOR_TEXTDOMAIN);?></a>
				
				<div style="padding-top:6px;">
					
					<span id="uc_loader_save" class="loader_text" style="display:none"><?php _e("Saving...", UNITECREATOR_TEXTDOMAIN)?></span>
					<span id="uc_message_saved" class="unite-color-green" style="display:none"></span>
					
				</div>
			</div>
			
			<div class="unite-clear"></div>
			
			<div id="uc_save_settings_error" class="unite_error_message" style="display:none"></div>
			
			
		</div>
		
		<div id="uc_tab_developers" class="uc-tab-content" style="display:none">
			Dear Theme Developer. <br><br>
			
			If you put the addon creator as part of your theme and want
			the addons to auto install on plugin activation or theme switch, <br>
			please create folder <b>"ac_addons"</b> inside your theme and put the addons import zips there. <br>
			example: <b>wp-content/themes/yourtheme/ac_addons</b>
			
			<br><br>
			If you want to put them to another path please copy this code to your theme <b>functions.php</b> file:
			
			<br><br>
			
<textarea cols="80" rows="6" readonly onfocus="this.select()">				
/**
* set Addon Craetor addons install folder. 
* example: 'installs/addons' (will be wp-content/themes/installs/addons)
**/
function set_addons_install_path_<?php echo $randomString?>($value){
	
	//change the 'yourfolder' to the folder you want
	
	return(&quot;yourfolder&quot;);
}

add_filter(&quot;uc_path_theme_addons&quot;, &quot;set_addons_install_path_<?php echo $randomString?>&quot;);
</textarea>
			
		</div>
		
	</div>
	
	

<script type="text/javascript">

	jQuery(document).ready(function(){
	
		var objAdmin = new UniteCreatorAdmin_GeneralSettings();
		objAdmin.initView();
		
	});

</script>


