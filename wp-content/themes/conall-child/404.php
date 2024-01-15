<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php
    /**
     * conall_edge_header_meta hook
     *
     * @see conall_edge_header_meta() - hooked with 10
     * @see edgt_user_scalable_meta() - hooked with 10
     */
    do_action('conall_edge_header_meta');
    
    wp_head(); ?>
</head>
<body <?php body_class();?> itemscope itemtype="http://schema.org/WebPage">
	<?php if(conall_edge_options()->getOptionValue('smooth_page_transitions') == "yes") { ?>
		<div class="edgtf-smooth-transition-loader edgtf-mimic-ajax">
		    <div class="edgtf-st-loader">
		        <div class="edgtf-st-loader1">
		            <?php conall_edge_loading_spinners(); ?>
		        </div>
		    </div>
		</div>
	<?php } ?>
	<div class="edgtf-wrapper edgtf-404-page">
	    <div class="edgtf-wrapper-inner">
			<div class="edgtf-content" <?php conall_edge_content_elem_style_attr(); ?>>
	            <div class="edgtf-content-inner">
					<div class="edgtf-page-not-found">
						<h1><span>
							<?php if(conall_edge_options()->getOptionValue('404_title')){
								echo esc_html(conall_edge_options()->getOptionValue('404_title'));
							} else {
								esc_html_e('404', 'conall');
							} ?>
						</span></h1>
						<h4><span>
							<?php if(conall_edge_options()->getOptionValue('404_subtitle')){
								echo esc_html(conall_edge_options()->getOptionValue('404_subtitle'));
							} else {
								esc_html_e('Page not found', 'conall');
							} ?>
						</span></h4>
						<?php
							$params = array();
							if (conall_edge_options()->getOptionValue('404_back_to_home')){
								$params['text'] = conall_edge_options()->getOptionValue('404_back_to_home');
							} else {
								$params['text'] = "BACK TO HOME";
							}
							$params['link'] = esc_url(home_url('/'));
							$params['target'] = '_self';
							$params['type'] = 'solid';
							$params['size'] = 'huge';
						echo conall_edge_execute_shortcode('edgtf_button',$params);?>
					</div>
				</div>	
			</div>
		</div>
	</div>		
	<?php wp_footer(); ?>
</body>
</html>