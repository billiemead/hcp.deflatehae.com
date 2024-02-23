<?php do_action('conall_edge_before_mobile_header'); ?>

<header class="edgtf-mobile-header hcp-mobile-header">
    <div class="edgtf-mobile-header-inner">
        <?php do_action( 'conall_edge_after_mobile_header_html_open' ) ?>
        <div class="edgtf-mobile-header-holder">
            <div class="edgtf-grid">
                <div class="edgtf-vertical-align-containers">
                    <?php if($show_logo) : ?>
                        <div class="edgtf-position-center">
                            <div class="edgtf-position-center-inner">
                                <?php conall_edge_get_mobile_logo(); ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if($show_navigation_opener) : ?>
                        <div class="edgtf-mobile-menu-opener">
                            <a class="mobile-hamburger" href="javascript:void(0)">
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php conall_edge_get_mobile_nav(); ?>
    </div>
</header>
</div>
<?php do_action('conall_edge_after_mobile_header'); ?>