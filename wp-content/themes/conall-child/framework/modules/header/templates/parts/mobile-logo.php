<?php do_action('conall_edge_before_mobile_logo'); ?>

<div class="edgtf-mobile-logo-wrapper hcp-logo">
    <a href="<?php echo esc_url(home_url('/')); ?>" <?php conall_edge_inline_style($logo_styles); ?>>
        <img class="edgtf-normal-mobile-logo" src="/wp-content/themes/conall-child/images/header-logo.svg" alt="<?php esc_attr_e('mobile logo','conall'); ?>"/>
    </a>
</div>

<?php do_action('conall_edge_after_mobile_logo'); ?>