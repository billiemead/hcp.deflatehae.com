


<?php do_action('conall_edge_before_header_top'); ?>

<div id="header-container" class="pv-header-container">
<div class="pv-top-bar">
    <!-- <div class="edgtf-grid pv-container"> -->
		<?php do_action( 'conall_edge_after_header_top_html_open' ); ?>
        <div class="pv-container">
            <div class="edgtf-vertical-align-containers pv-header-top-fullwidth">
                <div class="edgtf-position-right">
                    <div class="edgtf-position-right-inner">
                        <ul class="menu-site-selector-list">
                            <div class="menu-selector-box-wrapper">
                                <div class="edgtf-grid">
                                    <div class="menu-selector-box left">
                                        <li class="menu-selector-list-item">
                                            <a class="menu-selector-link yellow" href="https://hcp.deflatehae.com/" target="_blank">For healthcare professionals</a>
                                        </li>
                                        <img class="menu-selector-line" src="/wp-content/themes/conall-child/images/selector-line.svg" />
                                    </div>
                                    <div class="menu-selector-box right">
                                        <li class="menu-selector-list-item">
                                            <span class="menu-selector-link white" href="https://deflatehae.com/">For people with HAE and caregivers</span>
                                        </li>
                                        <img class="menu-selector-caret" src="/wp-content/themes/conall-child/images/selector-caret.svg" />
                                    </div>
                                </div>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <!-- </div> -->
</div>

<?php do_action('conall_edge_after_header_top'); ?>

<?php do_action('conall_edge_before_page_header'); ?>

<header class="edgtf-page-header hcp-header" <?php conall_edge_inline_style(array($menu_area_background_color, $menu_area_border_bottom_color)); ?>>
    <?php if($show_fixed_wrapper) : ?>
        <div class="pv-fixed-wrapper">
    <?php endif; ?>
    <div class="edgtf-menu-area">
		<?php do_action( 'conall_edge_after_header_menu_area_html_open' )?>
                <div class="edgtf-container pv-container">
                    <div class="edgtf-container-inner">
                        <div class="edgtf-vertical-align-containers">
                            <div class="edgtf-position-left">
                                <div class="edgtf-position-left-inner">
                                    <?php if(!$hide_logo) {
                                        conall_edge_get_logo();
                                    } ?>
                                </div>
                            </div>
                            <div class="edgtf-position-right">
                                <div class="edgtf-position-right-inner">
                                    <?php conall_edge_get_main_menu(); ?>
                                    <?php if(is_active_sidebar('edgtf-header-simple-widget-area')) : ?>
                                        <?php dynamic_sidebar('edgtf-header-simple-widget-area'); ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <?php if($show_fixed_wrapper) : ?>
            </div>
        <?php endif; ?>
    <?php if($show_sticky) {
        conall_edge_get_sticky_header();
    } ?>
</header>

<?php do_action('conall_edge_after_page_header'); ?>