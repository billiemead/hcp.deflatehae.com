<?php
/**
 * Lottier for WPBakery
 * Lottie animations in just a few clicks without writing a single line of code
 * Exclusively on https://1.envato.market/lottier-wpbakery
 *
 * @encoding        UTF-8
 * @version         1.1.5
 * @copyright       (C) 2018 - 2023 Merkulove ( https://merkulov.design/ ). All rights reserved.
 * @license         Envato License https://1.envato.market/KYbje
 * @contributors    Nemirovskiy Vitaliy (nemirovskiyvitaliy@gmail.com), Dmitry Merkulov (dmitry@merkulov.design)
 * @support         help@merkulov.design
 **/

namespace Merkulove\LottierWpbakery\Unity;

/** Exit if accessed directly. */
if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

use WP_Query;

/**
 * SINGLETON: Class used to implement Assignments tab on plugin settings page.
 *
 * @since 1.0.0
 *
 **/
final class TabAssignments extends Tab  {

    /**
     * Slug of current tab.
     *
     * @const TAB_SLUG
     **/
    const TAB_SLUG = 'assignments';

	/**
	 * The one true TabAssignments.
	 *
	 * @since 1.0.0
     * @access private
     * @var TabAssignments
	 **/
	private static $instance;

	/**
	 * Sets up a new TabAssignments instance.
	 *
	 * @since 1.0.0
	 * @access public
	 **/
	private function __construct() {

		/** Load JS and CSS for Backend Area. */
		$this->enqueue_backend();

	}

	/**
	 * Load JS and CSS for Backend Area.
	 *
	 * @since 1.0.0
	 * @access public
	 **/
	public function enqueue_backend() {

		/** Add admin javascript. */
		add_action( 'admin_enqueue_scripts', [ $this, 'add_admin_scripts' ] );

	}

	/**
	 * Add JS for admin area.
	 *
	 * @return void
	 **@since   1.0.0
	 */
	public function add_admin_scripts() {

		$screen = get_current_screen();
		if ( null === $screen ) { return; }

        /** Add styles only on plugin settings page */
        if ( ! in_array( $screen->base, Plugin::get_menu_bases(), true ) ) { return; }

        wp_enqueue_script( 'jquery-datetimepicker-full', Plugin::get_url() . 'src/Merkulove/Unity/assets/js/jquery.datetimepicker.full' . Plugin::get_suffix() . '.js', [ 'jquery' ], Plugin::get_version(), true );
        wp_enqueue_script( 'mdp-lottier-wpbakery-assignments', Plugin::get_url() . 'src/Merkulove/Unity/assets/js/assignments' . Plugin::get_suffix() . '.js', [ 'jquery-datetimepicker-full', 'jquery' ], Plugin::get_version(), true );

        /** Add code editor for Custom PHP. */
        wp_enqueue_code_editor( ['type' => 'application/x-httpd-php'] );

	}

	/**
	 * Generate Assignments Tab.
	 *
	 * @since 1.0.0
	 * @access public
	 **/
	public function add_settings() {

        /** Assignments Tab. */
        $this->add_settings_base( self::TAB_SLUG );

	}

    /**
     * Render form with all settings fields.
     *
     * @since 1.0.0
     * @access public
     *
     * @return void
     **/
    public function do_settings() {

        /** No status tab, nothing to do. */
        if ( ! $this->is_enabled( self::TAB_SLUG ) ) { return; }

        /** Render title. */
        $this->render_title( self::TAB_SLUG );

        /** Render fields. */
        $this->do_settings_base( self::TAB_SLUG );

        /** Render Assignments. */
        $this->render_assignments();

    }

	/**
	 * Render Assignments field.
	 *
	 * @since    1.0.0
	 **/
	public function render_assignments() {

		/**
		 * Output options list for select
		 */
		$options  = [];
		$defaults = [
			'search' => 'Search'
        ];

		$selected = is_array( $options ) ? $options : [ '*' ];

		if ( count( $selected ) > 1 && in_array( '*', $selected, true ) ) {
			$selected = [ '*' ];
		}

		// set default options
		foreach ( $defaults as $val => $label ) {
			$attributes = in_array( $val, $selected, true ) ? [
				'value'    => $val,
				'selected' => 'selected'
            ] : [ 'value' => $val ];
			$options[]  = sprintf( '<option value="%s">%s</option>', $attributes["value"], $label );
		}

		// set pages
		if ( $pages = get_pages() ) {
			$options[] = '<optgroup label="Pages">';

			array_unshift( $pages, (object) [ 'post_title' => 'Pages (All)' ] );

			foreach ( $pages as $page ) {
				$val        = isset( $page->ID ) ? 'page-' . $page->ID : 'page';
				$attributes = in_array( $val, $selected, true ) ? [
					'value'    => $val,
					'selected' => 'selected'
                ] : [ 'value' => $val ];
				$options[]  = sprintf( '<option value="%s">%s</option>', $attributes["value"], $page->post_title );
			}

			$options[] = '</optgroup>';
		}

		// set posts
		$options[] = '<optgroup label="Post">';
		foreach ( [ 'home', 'single', 'archive' ] as $view ) {
			$val        = $view;
			$attributes = in_array( $val, $selected, true ) ? [
				'value'    => $val,
				'selected' => 'selected'
            ] : [ 'value' => $val ];
			$options[]  = sprintf( '<option value="%s">%s (%s)</option>', $attributes["value"], 'Post', ucfirst( $view ) );
		}
		$options[] = '</optgroup>';

		// set custom post types
		foreach ( array_keys( get_post_types( ['_builtin' => false ] ) ) as $posttype ) {
			$obj   = get_post_type_object( $posttype );
			if ( null === $obj ) { continue; }

			$label = ucfirst( $posttype );

			if ( $obj->publicly_queryable ) {
				$options[] = '<optgroup label="' . $label . '">';

				foreach ( [ 'single', 'archive', 'search' ] as $view ) {
					$val        = $posttype . '-' . $view;
					$attributes = in_array( $val, $selected, true ) ? [
						'value'    => $val,
						'selected' => 'selected'
                    ] : [ 'value' => $val ];
					$options[]  = sprintf( '<option value="%s">%s (%s)</option>', $attributes["value"], $label, ucfirst( $view ) );
				}

				$options[] = '</optgroup>';
			}
		}

        // all posts in categories
        foreach ( array_keys( get_taxonomies() ) as $tax ) {

            if ( in_array( $tax, [ "post_tag", "nav_menu" ] ) ) {
                continue;
            }

            if ( $categories = get_categories( [ 'taxonomy' => $tax ] ) ) {
                $options[] = '<optgroup label="Posts in category (' . ucfirst( str_replace( [
                        "_",
                        "-"
                    ], " ", $tax ) ) . ')">';

                foreach ( $categories as $category ) {
                    $val        = 'in-cat-' . $category->cat_ID;
                    $attributes = in_array( $val, $selected, true ) ? [
                        'value'    => $val,
                        'selected' => 'selected'
                    ] : [ 'value' => $val ];
                    $options[]  = sprintf( '<option value="%s">%s</option>',
                        $attributes["value"],
                        esc_html__( 'In', 'lottier-wpbakery' ) . ' ' . $category->cat_name
                    );
                }

                $options[] = '</optgroup>';
            }
        }

		// set categories
		foreach ( array_keys( get_taxonomies() ) as $tax ) {

			if ( in_array( $tax, [ "post_tag", "nav_menu" ] ) ) {
				continue;
			}

			if ( $categories = get_categories( [ 'taxonomy' => $tax ] ) ) {
				$options[] = '<optgroup label="Archive by category (' . ucfirst( str_replace( [
						"_",
						"-"
                    ], " ", $tax ) ) . ')">';

				foreach ( $categories as $category ) {
					$val        = 'cat-' . $category->cat_ID;
					$attributes = in_array( $val, $selected, true ) ? [
						'value'    => $val,
						'selected' => 'selected'
                    ] : [ 'value' => $val ];
					$options[]  = sprintf( '<option value="%s">%s</option>', $attributes["value"], $category->cat_name );
				}

				$options[] = '</optgroup>';
			}
		}

		?>

        <input type='hidden'
               id="mdp-assignInput"
               name='<?php echo esc_attr( 'mdp_lottier_wpbakery_' . self::TAB_SLUG . '_settings' ); ?>[assignments]'
               value='<?php echo esc_attr( Settings::get_instance()->options['assignments'] ); ?>'>

        <div id="mdp-assign-box">

            <div class="mdp-all-fields">

                <div class="mdp-panel mdp-panel-box mdp-matching-method mdp-margin <?php $this->is_need_to_hide( 'matching_method' ); ?>">
                    <h4><?php esc_html_e( 'Matching Method', 'lottier-wpbakery' ); ?></h4>
                    <p><?php esc_html_e( 'Should all or any assignments be matched?', 'lottier-wpbakery' ); ?></p>
                    <div class="mdp-button-group mdp-button-group mdp-matchingMethod" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-all mdp-active"><?php esc_html_e( 'ALL', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-any"><?php esc_html_e( 'ANY', 'lottier-wpbakery' ); ?></button>
                    </div>
	                <p><?php esc_html_e('The plugin is enabled on the page if ALL the selected rules matched, or if ANY of the selected rules matched', 'lottier-wpbakery' ); ?></p>
                </div>

                <div class="mdp-panel mdp-panel-box mdp-wp-content mdp-margin <?php $this->is_need_to_hide( 'wordpress_content' ); ?>">

                    <h4><?php esc_html_e( 'WordPress Content', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-wp-content-box">
                        <p class="mdp-margin-bottom-remove mdp-margin-top">
							<?php esc_html_e( 'Select on what page types or categories the assignment should be active.', 'lottier-wpbakery' ); ?>
                        </p>
                        <label>
                        <select class="wp-content chosen-select" multiple="multiple">
                            <option value=""></option>
							<?php echo implode( "", $options ) ?>
                        </select>
                        </label>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-home-page mdp-margin <?php $this->is_need_to_hide( 'home_page' ); ?>">

                    <h4><?php esc_html_e( 'Home Page', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-menu-items mdp-margin <?php $this->is_need_to_hide( 'menu_items' ); ?>">
                    <h4><?php esc_html_e( 'Menu Items', 'lottier-wpbakery' ); ?></h4>
                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-menuitems-selection">
                        <p class="mdp-margin-bottom-remove mdp-margin-top"><?php esc_html_e( 'Select the menu items to assign to.', 'lottier-wpbakery' ); ?></p>
                        <label>
                        <select class="menuitems chosen-select" multiple="">
                            <option value=""></option>
		                    <?php
		                    /** Get all menus */
		                    $menus = get_terms( 'nav_menu', ['hide_empty' => true] );
		                    foreach ( $menus as $menu ) {
			                    ?><optgroup label="<?php echo esc_attr( $menu->name ); ?>"><?php
			                    $menuTree = $this->wpse_nav_menu_2_tree( $menu->slug );
			                    $this->printMenuTree( $menuTree, $menu->slug, 0 );
			                    ?></optgroup><?php
		                    }
		                    ?>
                        </select>
                        </label>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-date-time mdp-margin <?php $this->is_need_to_hide( 'date_time' ); ?>">

                    <h4><?php esc_html_e( 'Date & Time', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-period-picker-box">
                        <p class="mdp-period-picker uk-margin-top">
                            <label><input class="mdp-period-picker-start" id="mdp-period-picker-start" type="text" value=""/></label>
                            <label><input class="mdp-period-picker-end" id="mdp-period-picker-end" type="text" value=""/></label>
                        </p>

                        <p>
							<?php esc_html_e( 'The date and time assignments use the date/time of your servers, not that of the visitors system.', 'lottier-wpbakery' ); ?>
                            <br>
							<?php esc_html_e( 'Current date/time:', 'lottier-wpbakery' ); ?>
                            <strong><?php echo date( "d.m.Y H:i" ); ?></strong>
                        </p>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-languages mdp-margin <?php $this->is_need_to_hide( 'languages' ); ?>">

                    <h4><?php esc_html_e( 'Languages', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="languages-box">
                        <p class="mdp-margin-remove-bottom mdp-margin-top"><?php esc_html_e( 'Select the languages to assign to.', 'lottier-wpbakery' ); ?></p>

                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select class="languages chosen-select" multiple="">
                            <option value=""></option>
                            <?php

                            /** Get Available Translations. */
                            require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
                            $translations = wp_get_available_translations();

                            foreach ( $translations as $key => $language ) :
                                ?><option value="<?php echo esc_attr( $key ); ?>"><?php esc_html_e( $language['native_name'] ); ?></option><?php
                            endforeach; ?>
                        </select>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-user-roles mdp-margin <?php $this->is_need_to_hide( 'user_roles' ); ?>">

                    <h4><?php esc_html_e( 'User Roles', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="user-roles-box">
                        <p class="mdp-margin-remove-bottom mdp-margin-top"><?php esc_html_e( 'Select the user roles to assign to.', 'lottier-wpbakery' ); ?></p>
                        <label>
                        <select class="user-roles chosen-select" multiple="">
                            <option value=""></option>
							<?php // Get user roles
							$roles = get_editable_roles();
							foreach ( $roles as $k => $role ) {
								?>
                                <option value="<?php echo esc_attr( $k ); ?>"><?php esc_attr( $role['name'] ); ?></option><?php
							} ?>
                        </select>
                        </label>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-url mdp-margin <?php $this->is_need_to_hide( 'url' ); ?>">

                    <h4><?php esc_html_e( 'URL', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-url-box">
                        <p class="mdp-margin-bottom-remove mdp-margin-top">
							<?php esc_html_e( 'Enter (part of) the URLs to match.', 'lottier-wpbakery' ); ?><br>
							<?php esc_html_e( 'Use a new line for each different match.', 'lottier-wpbakery' ); ?>
                        </p>

                        <label>
                        <textarea class="mdp-url-field"></textarea>
                        </label>

                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-devices mdp-margin <?php $this->is_need_to_hide( 'devices' ); ?>">

                    <h4><?php esc_html_e( 'Devices', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-devices-box">
                        <p class="mdp-margin-remove-bottom mdp-margin-top"><?php esc_html_e( 'Select the devices to assign to. Keep in mind that device detection is not always 100% accurate. Users can setup their device to mimic other devices.', 'lottier-wpbakery' ); ?></p>
                        <label>
                        <select class="devices chosen-select" multiple="">
                            <option value=""></option>
                            <option value="desktop"><?php esc_html_e( 'Desktop', 'lottier-wpbakery' ); ?></option>
                            <option value="tablet"><?php esc_html_e( 'Tablet', 'lottier-wpbakery' ); ?></option>
                            <option value="mobile"><?php esc_html_e( 'Mobile', 'lottier-wpbakery' ); ?></option>
                        </select>
                        </label>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-os mdp-margin <?php $this->is_need_to_hide( 'operating_systems' ); ?>">

                    <h4><?php esc_html_e( 'Operating Systems', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-os-box">
                        <p class="mdp-margin-remove-bottom mdp-margin-top"><?php esc_html_e( 'Select the operating systems to assign to. Keep in mind that operating system detection is not always 100% accurate. Users can setup their browser to mimic other operating systems.', 'lottier-wpbakery' ); ?></p>
                        <!--suppress HtmlFormInputWithoutLabel -->
                        <select class="os chosen-select" multiple="">
                            <option value=""></option>
                            <option value="Windows"><?php esc_attr( 'Windows (All)' ) ?></option>
                            <option value="Windows nt 10.0"><?php esc_attr( 'Windows 10' ) ?></option>
                            <option value="Windows nt 6.2"><?php esc_attr( 'Windows 8' ) ?></option>
                            <option value="Windows nt 6.1"><?php esc_attr( 'Windows 7' ) ?></option>
                            <option value="Windows nt 6.0"><?php esc_attr( 'Windows Vista' ) ?></option>
                            <option value="Windows nt 5.2"><?php esc_attr( 'Windows Server 2003' ) ?></option>
                            <option value="Windows nt 5.1"><?php esc_attr( 'Windows XP' ) ?></option>
                            <option value="Windows nt 5.01"><?php esc_attr( 'Windows 2000 sp1' ) ?></option>
                            <option value="Windows nt 5.0"><?php esc_attr( 'Windows 2000' ) ?></option>
                            <option value="Windows nt 4.0"><?php esc_attr( 'Windows NT 4.0' ) ?></option>
                            <option value="Win 9x 4.9"><?php esc_attr( 'Windows Me' ) ?></option>
                            <option value="Windows 98"><?php esc_attr( 'Windows 98' ) ?></option>
                            <option value="Windows 95"><?php esc_attr( 'Windows 95' ) ?></option>
                            <option value="Windows ce"><?php esc_attr( 'Windows CE' ) ?></option>
                            <option value="#(Mac OS|Mac_PowerPC|Macintosh)#"><?php esc_attr( 'Mac OS (All)' ) ?></option>
                            <option value="Mac OS X"><?php esc_attr( 'Mac OSX (All)' ) ?></option>
                            <option value="Mac OS X 10.11"><?php esc_attr( 'Mac OSX El Capitan' ) ?></option>
                            <option value="Mac OS X 10.10"><?php esc_attr( 'Mac OSX Yosemite' ) ?></option>
                            <option value="Mac OS X 10.9"><?php esc_attr( 'Mac OSX Mavericks' ) ?></option>
                            <option value="Mac OS X 10.8"><?php esc_attr( 'Mac OSX Mountain Lion' ) ?></option>
                            <option value="Mac OS X 10.7"><?php esc_attr( 'Mac OSX Lion' ) ?></option>
                            <option value="Mac OS X 10.6"><?php esc_attr( 'Mac OSX Snow Leopard' ) ?></option>
                            <option value="Mac OS X 10.5"><?php esc_attr( 'Mac OSX Leopard' ) ?></option>
                            <option value="Mac OS X 10.4"><?php esc_attr( 'Mac OSX Tiger' ) ?></option>
                            <option value="Mac OS X 10.3"><?php esc_attr( 'Mac OSX Panther' ) ?></option>
                            <option value="Mac OS X 10.2"><?php esc_attr( 'Mac OSX Jaguar' ) ?></option>
                            <option value="Mac OS X 10.1"><?php esc_attr( 'Mac OSX Puma' ) ?></option>
                            <option value="Mac OS X 10.0"><?php esc_attr( 'Mac OSX Cheetah' ) ?></option>
                            <option value="#(Mac_PowerPC|Macintosh)#"><?php esc_attr( 'Mac OS (classic)' ) ?></option>
                            <option value="#(Linux|X11)#"><?php esc_attr( 'Linux' ) ?></option>
                            <option value="OpenBSD"><?php esc_attr( 'Open BSD' ) ?></option>
                            <option value="SunOS"><?php esc_attr( 'Sun OS' ) ?></option>
                            <option value="QNX"><?php esc_attr( 'QNX' ) ?></option>
                            <option value="BeOS"><?php esc_attr( 'BeOS' ) ?></option>
                            <option value="OS/2"><?php esc_attr( 'OS/2' ) ?></option>
                        </select>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-browsers mdp-margin <?php $this->is_need_to_hide( 'browsers' ); ?>">

                    <h4><?php esc_html_e( 'Browsers', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-browsers-box">
                        <p class="mdp-margin-remove-bottom mdp-margin-top"><?php esc_html_e( 'Select the browsers to assign to. Keep in mind that browser detection is not always 100% accurate. Users can setup their browser to mimic other browsers.', 'lottier-wpbakery' ); ?></p>

                        <div class="mdp-widget-manager-control-box">
                            <p><?php esc_html_e( 'Browsers', 'lottier-wpbakery' ); ?>:</p>
                            <div>
                                <!--suppress HtmlFormInputWithoutLabel -->
                                <select class="browsers chosen-select" multiple="">
                                    <option value=""></option>
                                    <option value="Chrome"><?php echo esc_attr( 'Chrome' ); ?> (<?php esc_html_e( 'All' ); ?>)</option>
                                    <option value="#Chrome/(6[1-9]|70)\.#"><?php echo esc_attr( 'Chrome 61-70' ); ?></option>
                                    <option value="#Chrome/(5[1-9]|60)\.#"><?php echo esc_attr( 'Chrome 51-60' ); ?></option>
                                    <option value="#Chrome/(4[1-9]|50)\.#"><?php echo esc_attr( 'Chrome 41-50' ); ?></option>
                                    <option value="#Chrome/(3[1-9]|40)\.#"><?php echo esc_attr( 'Chrome 31-40' ); ?></option>
                                    <option value="#Chrome/(2[1-9]|30)\.#"><?php echo esc_attr( 'Chrome 21-30' ); ?></option>
                                    <option value="#Chrome/(1[1-9]|20)\.#"><?php echo esc_attr( 'Chrome 11-20' ); ?></option>
                                    <option value="#Chrome/([1-9]|10)\.#"><?php echo esc_attr( 'Chrome 1-10' ); ?></option>
                                    <option value="Firefox"><?php echo esc_attr( 'Firefox' ); ?> (<?php esc_html_e( 'All' ); ?>)</option>
                                    <option value="#Firefox/(6[1-9]|70)\.#"><?php echo esc_attr( 'Firefox 61-70' ); ?></option>
                                    <option value="#Firefox/(5[1-9]|60)\.#"><?php echo esc_attr( 'Firefox 51-60' ); ?></option>
                                    <option value="#Firefox/(4[1-9]|50)\.#"><?php echo esc_attr( 'Firefox 41-50' ); ?></option>
                                    <option value="#Firefox/(3[1-9]|40)\.#"><?php echo esc_attr( 'Firefox 31-40' ); ?></option>
                                    <option value="#Firefox/(2[1-9]|30)\.#"><?php echo esc_attr( 'Firefox 21-30' ); ?></option>
                                    <option value="#Firefox/(1[1-9]|20)\.#"><?php echo esc_attr( 'Firefox 11-20' ); ?></option>
                                    <option value="#Firefox/([1-9]|10)\.#"><?php echo esc_attr( 'Firefox 1-10' ); ?></option>
                                    <option value="MSIE"><?php echo esc_attr( 'Internet Explorer' ); ?> (<?php esc_html_e( 'All' ); ?>)</option>
                                    <option value="MSIE Edge"><?php echo esc_attr( 'Internet Explorer Edge' ); ?></option>
                                    <option value="Edge/15"><?php echo esc_attr( 'Edge 15' ); ?></option>
                                    <option value="Edge/14"><?php echo esc_attr( 'Edge 14' ); ?></option>
                                    <option value="Edge/13"><?php echo esc_attr( 'Edge 13' ); ?></option>
                                    <option value="Edge/12"><?php echo esc_attr( 'Edge 12' ); ?></option>
                                    <option value="MSIE 11"><?php echo esc_attr( 'Internet Explorer 11' ); ?></option>
                                    <option value="MSIE 10.6"><?php echo esc_attr( 'Internet Explorer 10.6' ); ?></option>
                                    <option value="MSIE 10.0"><?php echo esc_attr( 'Internet Explorer 10.0' ); ?></option>
                                    <option value="MSIE 10."><?php echo esc_attr( 'Internet Explorer 10' ); ?></option>
                                    <option value="MSIE 9."><?php echo esc_attr( 'Internet Explorer 9' ); ?></option>
                                    <option value="MSIE 8."><?php echo esc_attr( 'Internet Explorer 8' ); ?></option>
                                    <option value="MSIE 7."><?php echo esc_attr( 'Internet Explorer 7' ); ?></option>
                                    <option value="#MSIE [1-6]\.#"><?php echo esc_attr( 'Internet Explorer 1-6' ); ?></option>
                                    <option value="Opera"><?php echo esc_attr( 'Opera' ); ?> (<?php esc_html_e( 'All' ); ?>)</option>
                                    <option value="#Opera/(5[1-9]|60)\.#"><?php echo esc_attr( 'Opera 51-60' ); ?></option>
                                    <option value="#Opera/(4[1-9]|50)\.#"><?php echo esc_attr( 'Opera 41-50' ); ?></option>
                                    <option value="#Opera/(3[1-9]|40)\.#"><?php echo esc_attr( 'Opera 31-40' ); ?></option>
                                    <option value="#Opera/(2[1-9]|30)\.#"><?php echo esc_attr( 'Opera 21-30' ); ?></option>
                                    <option value="#Opera/(1[1-9]|20)\.#"><?php echo esc_attr( 'Opera 11-20' ); ?></option>
                                    <option value="#Opera/([1-9]|10)\.#"><?php echo esc_attr( 'Opera 1-10' ); ?></option>
                                    <option value="Safari"><?php echo esc_attr( 'Safari' ); ?> (<?php esc_html_e( 'All' ); ?>)</option>
                                    <option value="#Version/11\..*Safari/#"><?php echo esc_attr( 'Safari 11' ); ?></option>
                                    <option value="#Version/10\..*Safari/#"><?php echo esc_attr( 'Safari 10' ); ?></option>
                                    <option value="#Version/9\..*Safari/#"><?php echo esc_attr( 'Safari 9' ); ?></option>
                                    <option value="#Version/8\..*Safari/#"><?php echo esc_attr( 'Safari 8' ); ?></option>
                                    <option value="#Version/7\..*Safari/#"><?php echo esc_attr( 'Safari 7' ); ?></option>
                                    <option value="#Version/6\..*Safari/#"><?php echo esc_attr( 'Safari 6' ); ?></option>
                                    <option value="#Version/5\..*Safari/#"><?php echo esc_attr( 'Safari 5' ); ?></option>
                                    <option value="#Version/4\..*Safari/#"><?php echo esc_attr( 'Safari 4' ); ?></option>
                                    <option value="#Version/[1-3]\..*Safari/#"><?php echo esc_attr( 'Safari 1-3' ); ?></option>
                                </select>
                            </div>
                        </div>

                        <div class="mdp-widget-manager-control-box">
                            <p><?php esc_html_e( 'Mobile Browsers:', 'lottier-wpbakery' ); ?></p>
                            <div>
                                <!--suppress HtmlFormInputWithoutLabel -->
                                <select class="mobile-browsers chosen-select" multiple="">
                                    <option value=""></option>
                                    <option value="mobile"><?php esc_html_e( 'All' ); ?></option>
                                    <option value="Android"><?php echo esc_html( 'Android' ); ?></option>
                                    <option value="#Android.*Chrome#"><?php echo esc_html( 'Android Chrome' ); ?></option>
                                    <option value="Blackberry"><?php echo esc_html( 'Blackberry' ); ?></option>
                                    <option value="IEMobile"><?php echo esc_html( 'IE Mobile' ); ?></option>
                                    <option value="iPad"><?php echo esc_html( 'iPad' ); ?></option>
                                    <option value="iPhone"><?php echo esc_html( 'iPhone' ); ?></option>
                                    <option value="iPod"><?php echo esc_html( 'iPod Touch' ); ?></option>
                                    <option value="NetFront"><?php echo esc_html( 'NetFront' ); ?></option>
                                    <option value="NokiaBrowser"><?php echo esc_html( 'Nokia' ); ?></option>
                                    <option value="Opera Mini"><?php echo esc_html( 'Opera Mini' ); ?></option>
                                    <option value="Opera Mobi"><?php echo esc_html( 'Opera Mobile' ); ?></option>
                                    <option value="UC Browser"><?php echo esc_html( 'UC Browser' ); ?></option>
                                </select>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-ips mdp-margin <?php $this->is_need_to_hide( 'ip_addresses' ); ?>">

                    <h4><?php esc_html_e( 'IP Addresses', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-ips-box">
                        <p class="mdp-margin-bottom-remove mdp-margin-top">
                            <?php esc_html_e( 'IP Addresses / Ranges', 'lottier-wpbakery' ); ?><br>
                        </p>

                        <!--suppress HtmlFormInputWithoutLabel -->
                        <textarea class="mdp-ips-field"></textarea>
                        <p>
                            <?php esc_html_e( 'List of IP addresses or IP ranges. Example: ', 'lottier-wpbakery' ); ?><br>
                            <?php echo '46.33.233.31'; ?><br>
                            <?php echo '46.0-46.1'; ?><br>
                            <?php echo '46'; ?>
                        </p>
                    </div>

                </div>

                <div class="mdp-panel mdp-panel-box mdp-php mdp-margin <?php $this->is_need_to_hide( 'custom_php' ); ?>">

                    <h4><?php esc_html_e( 'Custom PHP', 'lottier-wpbakery' ); ?></h4>

                    <div class="mdp-button-group mdp-button-group" data-mdp-button-radio="">
                        <button class="mdp-button mdp-button-primary mdp-button-small mdp-ignore mdp-active"><?php esc_html_e( 'Ignore', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-success mdp-button-small mdp-include"><?php esc_html_e( 'Include', 'lottier-wpbakery' ); ?></button>
                        <button class="mdp-button mdp-button-danger mdp-button-small mdp-exclude"><?php esc_html_e( 'Exclude', 'lottier-wpbakery' ); ?></button>
                    </div>

                    <div class="mdp-php-box">
                        <p class="mdp-margin-bottom-remove mdp-margin-top">
							<?php esc_html_e( 'Enter a piece of PHP code to evaluate. The code must return the value true or false.', 'lottier-wpbakery' ); ?>
                            <br>
							<?php esc_html_e( 'For instance:', 'lottier-wpbakery' ); ?>
                        </p>
                        <pre>$day_of_week = date('w');
if( '5' == $day_of_week ) { // Work only on Fridays.
    $result = true;
} else {
    $result = false;
}
return $result;</pre>
                        <label>
                        <textarea id="mdp-php-field" name="mdp-php-field" class="mdp-php-field"></textarea>
                        </label>

                    </div>

                </div>

                <p><?php esc_html_e( 'By selecting the specific assignments you can limit where plugin should or shouldn\'t be published. To have it published on all pages, simply do not specify any assignments.', 'lottier-wpbakery' ); ?></p>

            </div>

        </div>

		<?php
	}

    /**
     * Check if need to hide this assignments section and print class to hide it.
     *
     * @param string $section - Assignment section slug.
     *
     * @since  1.0.0
     * @access private
     *
     * @return string
     **/
	private function is_need_to_hide( $section ) {

        $hidden = 'mdc-hidden';

        if ( ! empty( Plugin::get_tabs()['assignments']['assignments'][$section] ) && Plugin::get_tabs()['assignments']['assignments'][$section] ) {
            $hidden = '';
        }

        return esc_attr( $hidden );

    }

	/**
	 * Checks if a plugin should work on current page.
	 *
	 * @return boolean
	 *
	 * @since 1.0.0
	 * @access public
	 **/
	public function display() {

		/** Assignments. */
        $assignments = Settings::get_instance()->options['assignments'];

		/** Get assignments for plugin. */
		$assignment = json_decode( str_replace('|', '"', $assignments ), false );

		if ( ! $assignment ) { return true; } // If no settings - Show plugin Everywhere

		/** WordPress Content. */
		$wordPressContent = $this->WordPressContent( $assignment );

		/** Home Page. */
		$homePage = $this->HomePage( $assignment );

		/** Menu Items. */
		$menuItems = $this->MenuItems( $assignment );

		/** Date & Time */
		$dateTime = $this->DateTime( $assignment );

        /** Languages. */
        $sub_results[] = $this->Languages( $assignment );

		/** User Roles. */
		$userRoles = $this->UserRoles( $assignment );

		/** URL. */
		$URL = $this->URL( $assignment );

		/** Devices. */
		$devices = $this->Devices( $assignment );

        /** Operating Systems. */
        $sub_results[] = $this->OS( $assignment );

        /** Browsers. */
        $sub_results[] = $this->Browsers( $assignment );

        /** IP Addresses. */
        $sub_results[] = $this->IPs( $assignment );

		/** Custom PHP. */
		$PHP = $this->PHP( $assignment );

		/** Matching Method. */
        return $this->MatchingMethod( $assignment, [$wordPressContent, $homePage, $menuItems, $dateTime, $userRoles, $URL, $devices, $PHP] );

	}

	/**
	 * Plugin Assignments - Date & Time.
	 *
	 * @param $assignment
	 *
	 * @return bool|int
	 * @since 1.0.0
	 * @access protected
	 */
	protected function DateTime( $assignment ) {

		/** If no dateTime - ignore. */
		if ( $assignment->dateTimeStart === "" || $assignment->dateTimeEnd === "" ) {
            return - 1;
		}

		$time = time();
		$s    = strtotime( $assignment->dateTimeStart ) - $time;
		$e    = strtotime( $assignment->dateTimeEnd ) - $time;

		switch ( $assignment->dateTime ) {
			case 1: // Include
				$result = false;
				if ( $s <= 0 && $e >= 0 ) {
					$result = true;
				}
				break;

			case 2: // Exclude
				$result = true;
				if ( $s <= 0 && $e >= 0 ) {
					$result = false;
				}
				break;

			default: // Ignore
				$result = - 1;
				break;
		}

		return $result;
	}

    /**
     * Plugin assignments - Languages.
     *
     * @param $assignment
     *
     * @since 1.0.0
     * @access protected
     *
     * @return bool|int
     **/
    protected function Languages( $assignment ) {

        /** If wrong input array - Ignore. */
        if ( ! is_array( $assignment->languagesVal ) ) { return - 1; }

        switch ( $assignment->languages ) {

            case 1: // Include
                $result = false;
                $locale   = get_user_locale();
                if ( in_array( $locale, $assignment->languagesVal, true ) ) {
                    $result = true;
                }
                break;

            case 2: // Exclude
                $result = true;
                $locale   = get_user_locale();
                if ( in_array( $locale, $assignment->languagesVal, true ) ) {
                    $result = false;
                }
                break;

            case 0;
            default: // Ignore
                $result = - 1;
                break;
        }

        return $result;

    }

	/**
	 * Plugin assignments - WordPress Content.
	 *
	 * @param $assignment
	 *
	 * @since 1.0.0
	 * @access protected
     *
     * @return bool|int
	 **/
	protected function WordPressContent( $assignment ) {

		$result = - 1;

		switch ( $assignment->WPContent ) {

			case 0: // Ignore
				$result = - 1;
				break;

			case 1: // Include
                $result = $this->check_wordpress_content( false, $assignment );
                break;

			case 2: // Exclude
                $result = $this->check_wordpress_content( true, $assignment );
                break;

		}

		return $result;

	}

	private function check_wordpress_content( $flag, $assignment ) {

        $result = $flag;
        if ( ! $assignment->WPContentVal ) {
            return - 1;
        } // If no menu items - ignore

        $query = $this->getQuery();
        foreach ( $query as $q ) {
            if ( in_array( $q, $assignment->WPContentVal, true ) ) {
                return ! $flag;
            }
        }

        return $result;

    }

    /**
	 * Plugin assignments - Home Page.
	 *
	 * @param $assignment
	 *
	 * @since 1.0.0
	 * @access protected
     *
     * @return bool|int
     **/
	protected function HomePage( $assignment ) {

		switch ( $assignment->homePage ) {

			case 1: // Include
				$result = false;
				if ( is_front_page() ) {
					$result = true;
				}
				break;

			case 2: // Exclude
				$result = true;
				if ( is_front_page() ) {
					$result = false;
				}
				break;

			default: // Ignore
                $result = - 1;
                break;
		}

		return $result;

	}

	/**
	 * Plugin assignments - Menu Items.
	 *
	 * @param $assignment
	 *
	 * @return bool|int
	 * @since 1.0.0
	 * @access protected
	 */
	protected function MenuItems( $assignment ) {

		$result = - 1;

		// If wrong input array - Ignore
		if ( ! is_array( $assignment->menuItemsVal ) ) {
			$result = - 1;

			return $result;
		}

		// Current URL
		if ( ! isset( $_SERVER["HTTPS"] ) || ( $_SERVER["HTTPS"] !== 'on' ) ) {
			$currentUrl = 'http://' . $_SERVER["SERVER_NAME"];
		} else {
			$currentUrl = 'https://' . $_SERVER["SERVER_NAME"];
		}
		$currentUrl .= $_SERVER["REQUEST_URI"];

		switch ( $assignment->menuItems ) {
			case 0: // Ignore
				$result = - 1;
				break;

			case 1: // Include
				$result = false;
				if ( ! $assignment->menuItemsVal ) {
					$result = - 1;

					return $result;
				} // If no menu items - ignore

				$menu_items_arr = []; // Assignments menu items
				foreach ( $assignment->menuItemsVal as $val ) {
					if ( $val === "" ) {
						continue;
					}
					list( $menuSlug, $menuItemID ) = explode( "+", $val );
					$menu_items       = wp_get_nav_menu_items( $menuSlug );
					$menu_item        = wp_filter_object_list( $menu_items, [ 'ID' => $menuItemID ] );
					$menu_items_arr[] = reset( $menu_item );
				}

				foreach ( $menu_items_arr as $mItem ) {
					if ( $currentUrl === $mItem->url ) {
						$result = true;

						return $result;
					}
				}
				break;

			case 2: // Exclude
				$result = true;
				if ( ! $assignment->menuItemsVal ) {
					$result = - 1;

					return $result;
				} // If no menu items - ignore

				$menu_items_arr = []; // Assignments menu items

				foreach ( $assignment->menuItemsVal as $val ) {
					list( $menuSlug, $menuItemID ) = explode( "+", $val );
					$menu_items       = wp_get_nav_menu_items( $menuSlug );
					$menu_item        = wp_filter_object_list( $menu_items, [ 'ID' => $menuItemID ] );
					$menu_items_arr[] = reset( $menu_item );
				}

				foreach ( $menu_items_arr as $mItem ) {
					if ( $currentUrl === $mItem->url ) {
						$result = false;

						return $result;
					}
				}
				break;
		}

		return $result;
	}

	/**
	 * Plugin assignments - User Roles.
	 *
	 * @param $assignment
	 *
	 * @return bool|int
	 * @since 1.0.0
	 * @access protected
	 */
	protected function UserRoles( $assignment ) {

		// If wrong input array - Ignore
		if ( ! is_array( $assignment->userRolesVal ) ) {
            return - 1;
		}

		include_once( ABSPATH . 'wp-includes/pluggable.php' );

		switch ( $assignment->userRoles ) {

			case 1: // Include
				$result = false;

				$user   = wp_get_current_user();
				if ( null === $user ) { return false; }

				foreach ( $user->roles as $role ) {
					if ( in_array( $role, $assignment->userRolesVal, true ) ) {
						$result = true;
					}
				}
				break;

			case 2: // Exclude
				$result = true;
				$user   = wp_get_current_user();
				foreach ( $user->roles as $role ) {
					if ( in_array( $role, $assignment->userRolesVal, true ) ) {
						$result = false;
					}
				}
				break;

			default: // Ignore
				$result = - 1;
				break;
		}

		return $result;

	}

	/**
	 * Plugin assignments - Devices.
	 *
	 * @param $assignment
	 *
	 * @return bool|int
	 * @since 1.0.0
	 * @access protected
	 */
	protected function Devices( $assignment ) {

		$detect = new MobileDetect;

		/** Detect current user device. */
		$device = 'desktop';
		if ( $detect->isTablet() ) {
			$device = 'tablet';
		}
		if ( $detect->isMobile() && ! $detect->isTablet() ) {
			$device = 'mobile';
		}

		/** If wrong input array - Ignore. */
		if ( ! is_array( $assignment->devicesVal ) ) {
            return - 1;
		}

		switch ( $assignment->devices ) {

			case 1: // Include
				$result = false;
				if ( in_array( $device, $assignment->devicesVal, true ) ) {
					$result = true;
				}
				break;

			case 2: // Exclude
				$result = true;
				if ( in_array( $device, $assignment->devicesVal, true ) ) {
					$result = false;
				}
				break;

			default: // Ignore
				$result = - 1;
				break;
		}

		return $result;
	}

    /**
     * Plugin assignments - Browsers.
     *
     * @param $assignment
     *
     * @since 1.0.0
     * @access protected
     *
     * @return bool|int
     **/
    protected function Browsers( $assignment ) {

        /** If wrong input array - Ignore. */
        if ( ! ( is_array( $assignment->browsersVal ) || is_array( $assignment->mobileBrowsersVal ) ) ) { return - 1; }

        switch ( $assignment->browsers ) {

            case 1: // Include

                if ( is_array( $assignment->browsersVal ) ) {
                    foreach ( $assignment->browsersVal as $browser ) {
                        if ( ! $this->passBrowser( $browser ) ) { continue; }

                        return true;
                    }
                }

                if ( is_array( $assignment->mobileBrowsersVal ) ) {
                    foreach ( $assignment->mobileBrowsersVal as $browser ) {
                        if ( ! $this->passBrowser( $browser ) ) { continue; }

                        return true;
                    }
                }

                return false;

            case 2: // Exclude

                if ( is_array( $assignment->mobileBrowsersVal ) ) {
                    foreach ( $assignment->mobileBrowsersVal as $browser ) {
                        if ( ! $this->passBrowser( $browser ) ) { continue; }

                        return false;
                    }
                }

                if ( is_array( $assignment->browsersVal ) ) {
                    foreach ( $assignment->browsersVal as $browser ) {
                        if ( ! $this->passBrowser( $browser ) ) { continue; }

                        return false;
                    }
                }

                return true;

            case 0;
            default: // Ignore
                $result = - 1;
                break;
        }

        return $result;

    }

    /**
     * Plugin assignments - IP Addresses.
     *
     * @param $assignment
     *
     * @since 1.0.0
     * @access protected
     *
     * @return bool|int
     **/
    protected function IPs( $assignment ) {

        switch ( $assignment->IPs ) {

            case 1: // Include
                $result = $this->passIPs( $assignment->IPsVal );
                break;

            case 2: // Exclude
                $result = ! $this->passIPs( $assignment->IPsVal );
                break;

            case 0;
            default: // Ignore
                $result = - 1;
                break;
        }

        return $result;

    }

    private function passIPs( $IPsVal ) {

        if ( is_array( $IPsVal ) ) {
            $IPsVal = implode( ',', $IPsVal );
        }

        $IPsVal = explode( ',', str_replace( [' ', "\r", "\n"], ['', '', ','], $IPsVal ) );

        return $this->checkIPList( $IPsVal );

    }

    private function checkIPList( $IPsVal ) {

        foreach ( $IPsVal as $range ) {

            /** Check next range if this one doesn't match. */
            if ( ! $this->checkIP( $range ) ) {
                continue;
            }

            /** Match found. */
            return true;

        }

        /** No matches found. */
        return false;

    }

    private function checkIP( $range ) {

        if ( empty( $range ) ) {
            return false;
        }

        if ( strpos( $range, '-' ) !== false ) {

            /** Selection is an IP range. */
            return $this->checkIPRange( $range );

        }

        /** Selection is a single IP (part). */
        return $this->checkIPPart( $range );

    }

    private function checkIPPart( $range ) {

        $ip = $_SERVER['REMOTE_ADDR'];

        /** Return if no IP address can be found (shouldn't happen, but who knows). */
        if ( empty( $ip ) ) {
            return false;
        }

        $ip_parts = explode( '.', $ip );
        $range_parts = explode( '.', trim( $range ) );

        /** Trim the IP to the part length of the range. */
        $ip = implode( '.', array_slice( $ip_parts, 0, count( $range_parts ) ) );

        /** Return false if ip does not match the range. */
        return ! ( $range !== $ip );

    }

    private function checkIPRange( $range ) {

        $ip = $_SERVER['REMOTE_ADDR'];

        /** Return if no IP address can be found (shouldn't happen, but who knows). */
        if ( empty( $ip ) ) { return false; }

        /** Check if IP is between or equal to the from and to IP range. */
        list( $min, $max ) = explode( '-', trim( $range ), 2 );

        /** Return false if IP is smaller than the range start. */
        if ( $ip < trim( $min ) ) { return false; }

        $max = $this->fillMaxRange( $max, $min );

        /** Return false if IP is larger than the range end. */
        return ! ( $ip > trim( $max ) );

    }

    /**
     * Fill the max range by prefixing it with the missing parts from the min range
     * So 101.102.103.104-201.202 becomes:
     * max: 101.102.201.202
     *
     * @param $max
     * @param $min
     *
     * @return string
     **/
    private function fillMaxRange( $max, $min ) {

        $max_parts = explode( '.', $max );

        if ( count( $max_parts ) === 4 ) { return $max; }

        $min_parts = explode( '.', $min );

        $prefix = array_slice( $min_parts, 0, count( $min_parts ) - count( $max_parts ) );

        return implode( '.', $prefix ) . '.' . implode( '.', $max_parts );

    }

    /**
     * Plugin assignments - Operating Systems.
     *
     * @param $assignment
     *
     * @return bool|int
     * @since 1.0.0
     * @access protected
     **/
    protected function OS( $assignment ) {

        /** If wrong input array - Ignore. */
        if ( ! is_array( $assignment->osVal ) ) { return - 1; }

        switch ( $assignment->os ) {

            case 1: // Include

                foreach ( $assignment->osVal as $browser ) {
                    if ( ! $this->passBrowser( $browser ) ) { continue; }

                    return true;
                }

                return false;

            case 2: // Exclude
                foreach ( $assignment->osVal as $browser ) {
                    if ( ! $this->passBrowser( $browser ) ) { continue; }

                    return false;
                }

                return true;

            case 0;
            default: // Ignore
                $result = - 1;
                break;
        }

        return $result;

    }

    private function passBrowser( $browser = '' ) {

        if ( ! $browser) { return false; }

        if ( $browser === 'mobile' ) { return $this->isMobile(); }

        if ( ! ( strpos( $browser, '#' ) === 0 ) ) {
            $browser = '#' . self::pregQuote( $browser ) . '#';
        }

        // also check for _ instead of .

        $browser = preg_replace( '#\\\.([^]])#', '[\._]\1', $browser );
        $browser = str_replace( '\.]', '\._]', $browser );

        return preg_match( $browser . 'i', $this->getAgent() );

    }

    private function getAgent() {

        $detect = new MobileDetect;
        $agent  = $detect->getUserAgent();

        switch (true) {

            case ( stripos( $agent, 'Trident' ) !== false ):

                // Add MSIE to IE11
                /** @noinspection NotOptimalRegularExpressionsInspection */
                $agent = preg_replace( '#(Trident/[0-9.]+; rv:([0-9.]+))#is', '\1 MSIE \2', $agent );
                break;

            case ( stripos( $agent, 'Chrome' ) !== false ):

                // Remove Safari from Chrome
                $agent = preg_replace( '#(Chrome/.*)Safari/[0-9.]*#is', '\1', $agent );

                // Add MSIE to IE Edge and remove Chrome from IE Edge
                /** @noinspection NotOptimalRegularExpressionsInspection */
                $agent = preg_replace( '#Chrome/.*(Edge/[0-9])#is', 'MSIE \1', $agent );
                break;

            case ( stripos( $agent, 'Opera' ) !== false ):

                $agent = preg_replace( '#(Opera/.*)Version/#is', '\1Opera/', $agent );
                break;

        }

        return $agent;
    }

    public static function pregQuote( $string = '', $delimiter = '#' ) {

        return self::quote( $string, '', $delimiter );

    }

    /**
     * preg_quote the given string or array of strings
     *
     * @param string|array $data
     * @param string       $name
     * @param string       $delimiter
     *
     * @param bool         $capture
     *
     * @return string
     **/
    public static function quote( $data, $name = '', $delimiter = '#', $capture = true ) {

        if ( is_array( $data ) ) {
            $array = self::quoteArray( $data, $delimiter );

            $prefix = '?!';
            if ( $capture ) {
                $prefix = $name ? '?<' . $name . '>' : '';
            }

            return '(' . $prefix . implode( '|', $array ) . ')';
        }

        if ( ! empty( $name ) ) {
            return '(?<' . $name . '>' . preg_quote( $data, $delimiter ) . ')';
        }

        return preg_quote( $data, $delimiter );

    }

    /**
     * preg_quote the given array of strings
     *
     * @param array  $array
     * @param string $delimiter
     *
     * @return array
     **/
    public static function quoteArray($array = [], $delimiter = '#') {

        array_walk($array, static function ( &$part, $delimiter ) {
            $part = self::quote( $part, '', $delimiter );
        }, $delimiter );

        return $array;

    }

    public function isMobile() {
        return $this->getDevice() === 'mobile';
    }

    private function getDevice() {

        $detect = new MobileDetect;

        switch (true)
        {
            case( $detect->isTablet() ):
                return 'tablet';

            case ($detect->isMobile()):
                return 'mobile';

            default:
                return 'desktop';
        }

    }

	/**
	 * Plugin assignments - URL.
	 *
	 * @param $assignment
	 *
	 * @return bool|int
	 * @since 1.0.0
	 * @access protected
	 */
	protected function URL( $assignment ) {

        if ( ! isset( $_SERVER["SERVER_NAME"] ) ) { return false; }

		/** Current URL. */
		if ( ! isset( $_SERVER["HTTPS"] ) || ( $_SERVER["HTTPS"] !== 'on' ) ) {
			$curUrl = 'http://' . $_SERVER["SERVER_NAME"];
		} else {
			$curUrl = 'https://' . $_SERVER["SERVER_NAME"];
		}
		$curUrl .= $_SERVER["REQUEST_URI"];

		$URLVal = (array) preg_split( '/\r\n|[\r\n]/', $assignment->URLVal );
		$URLVal = array_filter( $URLVal, static function ( $value ) {
			if ( trim( $value ) !== '' ) { return $value; }
			return null;
		} );

		switch ( $assignment->URL ) {

			case 1: // Include
				$result = false;
				if ( count( $URLVal ) === 0 ) {
					$result = false;
				} // If no URLS to include - hide widget
				foreach ( $URLVal as $u ) {
					if ( strpos( $curUrl, $u ) !== false ) {
						$result = true;
					}
				}

				break;

			case 2: // Exclude
				$result = true;
				if ( count( $URLVal ) === 0 ) {
					$result = true;
				} // If no URLS to exclude - show widget
				foreach ( $URLVal as $u ) {
					if ( strpos( $curUrl, $u ) !== false ) {
						$result = false;
					}
				}
				break;

			default: // Ignore
				$result = - 1;
				break;
		}

		return $result;
	}

	/**
	 * Plugin assignments - Custom PHP.
	 *
	 * @param $assignment
	 *
	 * @return bool|int
	 * @since 1.0.0
	 * @access protected
	 */
	protected function PHP( $assignment ) {

		/** Replace <?php and other fix stuff. */
		$php = trim( $assignment->PHPVal );

		$p = substr( $php, 0, 5 );
		if ( strtolower( $p ) === '<?php' ) {
			$php = substr( $php, 5 );
		}

		$php = trim( $php );

		if ( $php === '' ) { return - 1; }

		$php .= ';return true;';

		/** Evaluate the script. */
		ob_start();
		$pass = (bool) eval( $php );
		ob_end_clean();

		switch ( $assignment->PHP ) {

			case 1: // Include
				$result = false;
				if ( $pass ) {
					$result = true;
				}

				break;

			case 2: // Exclude
				$result = true;
				if ( $pass ) {
					$result = false;
				}
				break;

			default: // Ignore
				$result = - 1;
				break;
		}

		return $result;

	}

	/**
	 * Plugin assignments - Matching Method.
	 *
	 * @param object $assignment
	 * @param array $checks
	 *
	 * @return bool
	 * @since 1.0.0
	 * @access protected
	 */
	protected function MatchingMethod( $assignment, $checks = [] ) {

		$arrCond = []; // Add condition values

        foreach ( $checks as $check ) {

            // Ignore if -1
            if ( $check !== - 1 ) {

                $arrCond[] = $check;

            }

        }

		if ( ! count( $arrCond ) ) {
			$arrCond[] = true;
		}

		// If all rules are Ignore - Show widget
		// Initialization
		$any_true = false;
		$all_true = true;

		// Processing
		foreach ( $arrCond as $v ) {
			$any_true |= $v;
			$all_true &= $v;
		}

		// Result
		if ( $all_true ) {

			// All elements are TRUE
			$result = true;

		} elseif ( ! $any_true ) {

			// All elements are FALSE
			$result = false;

		} else if ( $assignment->matchingMethod === 0 ) { // ALL RULES

            $result = false;

        } else { // ANY OF RULES

            $result = true;

        }

		return $result;

	}

	/**
	 * Output options list for select.
	 *
	 * @param $arr
	 * @param $menu_slug
	 * @param int $level
	 *
	 * @since    1.0.0
	 */
	public function printMenuTree( $arr, $menu_slug, $level = 0 ) {

		if ( ! is_array( $arr ) ) { return; }

		foreach ( $arr as $item ) {
			?>
            <option
            value="<?php echo esc_attr( $menu_slug . "+" . $item->ID ); ?>"><?php echo str_repeat( "-", $level ) . " " . $item->title . " (" . $item->type_label . ")"; ?></option><?php
			if ( ! empty( $item->wpse_children ) && count( $item->wpse_children ) ) {
				$this->printMenuTree( $item->wpse_children, $menu_slug, $level + 1 );
			}
		}

	}

	/**
	 * Transform a navigational menu to it's tree structure
	 *
	 * @param $menu_id
	 *
	 * @return array|null $tree
	 * @uses  buildTree()
	 * @uses  wp_get_nav_menu_items()
	 *
	 **/
	public function wpse_nav_menu_2_tree( $menu_id ) {
		$items = wp_get_nav_menu_items( $menu_id );
		return  $items ? $this->buildTree( $items, 0 ) : null;
	}

    /**
     * Modification of "Build a tree from a flat array in PHP"
     *
     * Authors: @DSkinner, @ImmortalFirefly and @SteveEdson
     *
     * @link https://stackoverflow.com/a/28429487/2078474
     *
     * @param array $elements
     * @param int   $parentId
     *
     * @return array
     **/
	public function buildTree( array &$elements, $parentId = 0 ) {

		$branch = [];

        foreach ( $elements as &$element ) {

            if ( intval( $element->menu_item_parent ) === intval( $parentId ) ) {
				$children = $this->buildTree( $elements, $element->ID );
				if ( $children ) {
                    $element->wpse_children = $children;
                }

				$branch[$element->ID] = $element;
				unset( $element );
			}

		}

		return $branch;

	}

	/**
	 * Get current query information.
	 *
	 * @return string[]
	 * @global WP_Query $wp_query
	 *
	 */
	public function getQuery() {
		global $wp_query;

		// create, if not set
		if ( empty( $this->query ) ) {

			// init vars
			$obj   = $wp_query->get_queried_object();
			$type  = get_post_type();
			$query = [];

			if ( is_home() ) {
				$query[] = 'home';
			}

			if ( is_front_page() ) {
				$query[] = 'front_page';
			}

			if ( $type === 'post' ) {
                if ( is_single() ) {
                    $query[] = 'single';
                    $post_cats = get_the_category();
                    if ( $post_cats ) {
                        foreach ( $post_cats as $category ) {
                            $query[] = 'in-cat-' . $category->term_id;
                        }
                    }
                }
				if ( is_archive() ) {
					$query[] = 'archive';
				}
			} else if ( is_single() ) {

                $query[] = $type . '-single';

            } elseif ( is_archive() ) {

                $query[] = $type . '-archive';

            }

			if ( is_search() ) {
				$query[] = 'search';
			}

			if ( is_page() ) {
				$query[] = $type;
				$query[] = $type . '-' . $obj->ID;
			}

			if ( is_category() ) {
				$query[] = 'cat-' . $obj->term_id;
			}

			// WooCommerce
			include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {

				/** @noinspection PhpUndefinedFunctionInspection */
				if ( is_shop() && ! is_search() ) {
					$query[] = 'page';

					/** @noinspection PhpUndefinedFunctionInspection */
					$query[] = 'page-' . wc_get_page_id( 'shop' );
				}

				/** @noinspection PhpUndefinedFunctionInspection */
				if ( is_product_category() || is_product_tag() ) {
					$query[] = 'cat-' . $obj->term_id;
				}
			}

			/** @noinspection PhpUndefinedFieldInspection */
			$this->query = $query;
		}

		return $this->query;
	}

	/**
	 * Main TabAssignments Instance.
	 *
	 * Insures that only one instance of TabAssignments exists in memory at any one time.
	 *
	 * @static
	 * @since 1.0.0
     *
     * @return TabAssignments
	 **/
	public static function get_instance() {

		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof self ) ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

} // End Class TabAssignments.
