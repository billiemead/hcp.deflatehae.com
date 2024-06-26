<?php do_action('conall_edge_before_page_title'); ?>
<?php if($show_title_area) { ?>

    <div class="edgtf-title <?php echo conall_edge_title_classes(); ?>" style="<?php echo esc_attr($title_height); echo esc_attr($title_background_color); echo esc_attr($title_background_image); ?>" data-height="<?php echo esc_attr(intval(preg_replace('/[^0-9]+/', '', $title_height), 10));?>" <?php echo esc_attr($title_background_image_width); ?>>
        <div class="edgtf-title-image"><?php if($title_background_image_src != ""){ ?><img src="<?php echo esc_url($title_background_image_src); ?>" alt="<?php esc_attr_e('Title Image', 'conall'); ?>" /> <?php } ?></div>
        <div class="edgtf-title-holder" <?php conall_edge_inline_style($title_holder_height); ?>>
            <div class="edgtf-container clearfix">
                <div class="edgtf-container-inner">
                    <div class="edgtf-title-subtitle-holder" style="<?php echo esc_attr($title_subtitle_holder_padding); ?>">
                        <div class="edgtf-title-subtitle-holder-inner">
                                <h1 <?php conall_edge_inline_style($title_color); ?>><span><?php conall_edge_title_text(); ?></span></h1>
                                <?php if($has_subtitle){ ?>
                                    <span class="edgtf-subtitle" <?php conall_edge_inline_style($subtitle_color); ?>><span><?php conall_edge_subtitle_text(); ?></span></span>
                                <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<?php } ?>
<?php do_action('conall_edge_after_page_title'); ?>