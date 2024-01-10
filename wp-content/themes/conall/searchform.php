<form method="get" id="searchform" action="<?php echo esc_url(home_url('/')); ?>">
	<div><label class="screen-reader-text" for="s"><?php esc_html_e( 'Search for:', 'conall' ); ?></label>
		<input type="text" value="" placeholder="<?php esc_attr_e('Search', 'conall'); ?>" name="s" id="s" />
		<input type="submit" id="searchsubmit" value="&#x55;" />
	</div>
</form>