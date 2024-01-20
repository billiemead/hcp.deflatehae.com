<?php if($show_header_top) : ?>

<?php do_action('conall_edge_before_header_top'); ?>

<div class="edgtf-top-bar">
    <?php if($top_bar_in_grid) : ?>
    <div class="edgtf-grid pv-container">
    <?php endif; ?>
		<?php do_action( 'conall_edge_after_header_top_html_open' ); ?>
        <div class="edgtf-vertical-align-containers edgtf-<?php echo esc_attr($column_widths); ?> pv-header-top-fullwidth">
            <div class="edgtf-position-right">
                <div class="edgtf-position-right-inner">
                    <ul class="menu-site-selector-list fix">
                        <li class="menu-selector-list-item">
                            <a class="menu-selector-link yellow" href="#">For healthcare professionals</a>
                        </li>
                        <img class="menu-selector-line" src="/wp-content/themes/conall-child/images/selector-line.svg" />
                        <li class="menu-selector-list-item">
                            <a class="menu-selector-link white" href="#">For people with HAE and caregivers</a>
                        </li>
                        <img class="menu-selector-caret" src="/wp-content/themes/conall-child/images/selector-caret.svg" />
                    </ul>
                </div>
            </div>
        </div>
    <?php if($top_bar_in_grid) : ?>
    </div>
    <?php endif; ?>
</div>

<?php do_action('conall_edge_after_header_top'); ?>

<?php endif; ?>