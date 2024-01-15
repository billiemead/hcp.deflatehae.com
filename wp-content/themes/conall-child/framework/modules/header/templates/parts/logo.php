<?php do_action('conall_edge_before_site_logo'); ?>

<div class="edgtf-logo-wrapper hcp-logo">
    <a href="<?php echo esc_url(home_url('/')); ?>" <?php conall_edge_inline_style($logo_styles); ?>>
        <img class="edgtf-normal-logo" src="/wp-content/themes/conall-child/images/header-logo.svg" alt="<?php esc_attr_e('logo','conall'); ?>"/>
    </a>
</div>

<?php do_action('conall_edge_after_site_logo'); ?>