<?php get_header(); ?>

<div class="edgtf-container edgtf-404-page">
    <?php do_action('conall_edge_after_container_open'); ?>
    <div class="edgtf-container-inner clearfix">
        <div class="edgtf-page-not-found">
            <h1 class=""><span>SORRY, THAT PAGE DOES NOT EXIST</span></h1>
            <h4 class=""><span>Let's get you back on track</span></h4>
            <a class="edgtf-btn pv-btn w-176" href="<?php echo get_home_url(); ?>">Back to Home</a>
        </div>
    </div>
    <?php do_action('conall_edge_before_container_close'); ?>
</div>
<?php get_footer(); ?>