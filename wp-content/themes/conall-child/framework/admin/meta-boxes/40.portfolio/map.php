<?php

$edgt_pages = array();
$pages = get_pages(); 
foreach($pages as $page) {
	$edgt_pages[$page->ID] = $page->post_title;
}

//Portfolio Images

$edgtPortfolioImages = new ConallEdgeClassMetaBox("portfolio-item", esc_html__( "Portfolio Images (multiple upload)", 'conall' ), '', '', 'portfolio_images');
conall_edge_framework()->edgtMetaBoxes->addMetaBox("portfolio_images",$edgtPortfolioImages);

	$edgt_portfolio_image_gallery = new ConallEdgeClassMultipleImages("edgt_portfolio-image-gallery",esc_html__( "Portfolio Images", 'conall' ),esc_html__( "Choose your portfolio images", 'conall' ));
	$edgtPortfolioImages->addChild("edgt_portfolio-image-gallery",$edgt_portfolio_image_gallery);

//Portfolio Images/Videos 2

$edgtPortfolioImagesVideos2 = new ConallEdgeClassMetaBox("portfolio-item", esc_html__( "Portfolio Images/Videos (single upload)", 'conall' ));
conall_edge_framework()->edgtMetaBoxes->addMetaBox("portfolio_images_videos2",$edgtPortfolioImagesVideos2);

	$edgt_portfolio_images_videos2 = new ConallEdgeClassImagesVideosFramework(esc_html__( "Portfolio Images/Videos 2", 'conall' ));
	$edgtPortfolioImagesVideos2->addChild("edgt_portfolio_images_videos2",$edgt_portfolio_images_videos2);

//Portfolio Additional Sidebar Items

$edgtAdditionalSidebarItems = conall_edge_create_meta_box(
    array(
        'scope' => array('portfolio-item'),
        'title' => esc_html__( 'Additional Portfolio Sidebar Items', 'conall' ),
        'name' => 'portfolio_properties'
    )
);

	$edgt_portfolio_properties = conall_edge_add_options_framework(
	    array(
	        'label' => esc_html__( 'Portfolio Properties', 'conall' ),
	        'name' => 'edgt_portfolio_properties',
	        'parent' => $edgtAdditionalSidebarItems
	    )
	);