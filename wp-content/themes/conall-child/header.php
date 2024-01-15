<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php
    /**
     * @see conall_edge_header_meta() - hooked with 10
     * @see edgt_user_scalable - hooked with 10
     */
    do_action('conall_edge_header_meta');
    
    wp_head(); ?>
</head>
<body <?php body_class();?> itemscope itemtype="http://schema.org/WebPage">
<?php conall_edge_get_side_area(); ?>
<?php if(conall_edge_options()->getOptionValue('smooth_page_transitions') == "yes") { ?>
<div class="edgtf-smooth-transition-loader edgtf-mimic-ajax">
    <div class="edgtf-st-loader">
        <div class="edgtf-st-loader1">
            <?php conall_edge_loading_spinners(); ?>
        </div>
    </div>
</div>
<?php } ?>

<div class="edgtf-wrapper">
    <div class="edgtf-wrapper-inner">
        <?php conall_edge_get_header(); ?>

        <?php if (conall_edge_options()->getOptionValue('show_back_button') == "yes") { ?>
            <a id='edgtf-back-to-top'  href='#'>
                <span class="edgtf-icon-stack">
                     <?php conall_edge_icon_collections()->getBackToTopIcon('font_awesome');?>
                </span>
            </a>
        <?php } ?>
        <?php conall_edge_get_full_screen_menu(); ?>

        <div class="edgtf-content" <?php conall_edge_content_elem_style_attr(); ?>>
            <div class="edgtf-content-inner">