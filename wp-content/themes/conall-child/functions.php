<?php

setcookie(TEST_COOKIE, 'WP Cookie check', 0, COOKIEPATH, COOKIE_DOMAIN);
if ( SITECOOKIEPATH != COOKIEPATH ) setcookie(TEST_COOKIE, 'WP Cookie check', 0, SITECOOKIEPATH, COOKIE_DOMAIN);

// add title attributes to menu
function pv_add_title_attribute($atts, $item){

	$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : $item->title;
	return $atts;
}
add_filter('nav_menu_link_attributes', 'pv_add_title_attribute', 10, 2);

/*** Child Theme Function  ***/

if ( ! function_exists( 'conall_edge_child_theme_enqueue_scripts' ) ) {
	function conall_edge_child_theme_enqueue_scripts() {
		$parent_style = 'conall-edge-default-style';

		wp_enqueue_style( 'conall-edge-child-style', get_stylesheet_directory_uri() . '/style.css', array( $parent_style ) );
	}

	add_action( 'wp_enqueue_scripts', 'conall_edge_child_theme_enqueue_scripts' );
}

// Register & Enqueue all CSS & JS
function pharvaris_hcp_assets()
{
    wp_register_style('pharvaris-hcp-stylesheet', get_theme_file_uri() . '/dist/css/bundle.css', array(), '1.0.0', 'all');
    wp_enqueue_style('pharvaris-hcp-stylesheet');
    wp_enqueue_script('custom_js', get_theme_file_uri() . '/dist/js/bundle.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('pharvaris_hcp_js', get_stylesheet_directory_uri() . '/pharvaris-hcp-scripts.js', array(), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'pharvaris_hcp_assets', 99);

// * * * * * * * * * * * * *  - - - S V G  S U P P O R T - - - * * * * * * * * * * * * * //

function add_file_types_to_uploads($file_types){
$new_filetypes = array();
$new_filetypes['svg'] = 'image/svg+xml';
$file_types = array_merge($file_types, $new_filetypes );
return $file_types;
}
add_filter('upload_mimes', 'add_file_types_to_uploads');

// * * * * * * * * * * * * *  - - - P A G E  C L A S S E S - - - * * * * * * * * * * * * * //

add_filter('body_class', 'custom_class');
function custom_class($classes)
{
    if (is_front_page()) {
        $classes[] = 'home-page';
    }
    if (is_page('deflate-hae')) {
        $classes[] = 'home-page';
    }
    if (is_page('hae-overview')) {
        $classes[] = 'haeoverview-page';
    }
    if (is_page('living-with-hae')) {
        $classes[] = 'livinghae-page';
    }
    if (is_page('treatment-burdens')) {
        $classes[] = 'treatmentburdens-page';
    }
    if (is_page('community-support')) {
        $classes[] = 'communitysupport-page';
    }
    if (is_page('terms-of-use')) {
        $classes[] = 'terms-page';
    }
    if (is_page('privacy-policy')) {
        $classes[] = 'privacypolicy-page';
    }
    if (is_page('european-privacy-policy')) {
        $classes[] = 'europeanprivacy-page';
    }
    if (is_page('cookie-policy')) {
        $classes[] = 'cookiepolicy-page';
    }
    return $classes;
}

// * * * * * * * * * * * * *  - - - P A G E  &  P O S T  D U P L I C A T I O N - - - * * * * * * * * * * * * * //
/*
 * Function for Post Duplication
 */
function pharvaris_duplicate_post_as_draft()
{
    global $wpdb;
    if (!(isset($_GET['post']) || isset($_POST['post'])  || (isset($_REQUEST['action']) && 'pharvaris_duplicate_post_as_draft' == $_REQUEST['action']))) {
        wp_die('No Post Selected to Duplicate!');
    }

    /*
   * Nonce verification
   */
    if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__)))
        return;

    /*
   * get the original post id
   */
    $post_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
    /*
   * and all the original post data then
   */
    $post = get_post($post_id);

    /*
   * if you don't want current user to be the new post author,
   * then change next couple of lines to this: $new_post_author = $post->post_author;
   */
    $current_user = wp_get_current_user();
    $new_post_author = $current_user->ID;

    /*
   * if post data exists, create the post duplicate
   */
    if (isset($post) && $post != null) {

        /*
     * new post data array
     */
        $args = array(
            'comment_status' => $post->comment_status,
            'ping_status'    => $post->ping_status,
            'post_author'    => $new_post_author,
            'post_content'   => $post->post_content,
            'post_excerpt'   => $post->post_excerpt,
            'post_name'      => $post->post_name,
            'post_parent'    => $post->post_parent,
            'post_password'  => $post->post_password,
            'post_status'    => 'draft',
            'post_title'     => $post->post_title,
            'post_type'      => $post->post_type,
            'to_ping'        => $post->to_ping,
            'menu_order'     => $post->menu_order
        );

        /*
     * insert the post by wp_insert_post() function
     */
        $new_post_id = wp_insert_post($args);

        /*
     * get all current post terms ad set them to the new post draft
     */
        $taxonomies = get_object_taxonomies($post->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
        foreach ($taxonomies as $taxonomy) {
            $post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
        }

        /*
     * duplicate all post meta just in two SQL queries
     */
        $post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
        if (count($post_meta_infos) != 0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($post_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if ($meta_key == '_wp_old_slug') continue;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[] = "SELECT $new_post_id, '$meta_key', '$meta_value'";
            }
            $sql_query .= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }
        /*
     * finally, redirect to the edit post screen for the new draft
     */
        wp_redirect(admin_url('post.php?action=edit&post=' . $new_post_id));
        exit;
    } else {
        wp_die('Post creation failed, could not find original post: ' . $post_id);
    }
}
add_action('admin_action_pharvaris_duplicate_post_as_draft', 'pharvaris_duplicate_post_as_draft');
function pharvaris_duplicate_post_link($actions, $post)
{
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=pharvaris_duplicate_post_as_draft&post=' . $post->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Duplicate this Post" rel="permalink">Duplicate Post</a>';
    }
    return $actions;
}
add_filter('post_row_actions', 'pharvaris_duplicate_post_link', 10, 2);

/*
 * Function for Page Duplication
 */
function pharvaris_duplicate_page_as_draft()
{
    global $wpdb;
    if (!(isset($_GET['post']) || isset($_POST['post'])  || (isset($_REQUEST['action']) && 'pharvaris_duplicate_page_as_draft' == $_REQUEST['action']))) {
        wp_die('No Page Selected to Duplicate!');
    }

    /*
   * Nonce verification
   */
    if (!isset($_GET['duplicate_nonce']) || !wp_verify_nonce($_GET['duplicate_nonce'], basename(__FILE__)))
        return;

    /*
   * get the original page id
   */
    $page_id = (isset($_GET['post']) ? absint($_GET['post']) : absint($_POST['post']));
    /*
   * and all the original page data then
   */
    $page = get_post($page_id);

    /*
   * if you don't want current user to be the new page author,
   * then change next couple of lines to this: $new_page_author = $page->page_author;
   */
    $current_user = wp_get_current_user();
    $new_page_author = $current_user->ID;

    /*
   * if page data exists, create the page duplicate
   */
    if (isset($page) && $page != null) {

        /*
     * new page data array
     */
        $args = array(
            'comment_status' => $page->comment_status,
            'ping_status'    => $page->ping_status,
            'post_author'    => $new_page_author,
            'post_content'   => $page->post_content,
            'post_excerpt'   => $page->post_excerpt,
            'post_name'      => $page->post_name,
            'post_parent'    => $page->post_parent,
            'post_password'  => $page->post_password,
            'post_status'    => 'draft',
            'post_title'     => $page->post_title,
            'post_type'      => $page->post_type,
            'to_ping'        => $page->to_ping,
            'menu_order'     => $page->menu_order
        );

        /*
     * insert the page by wp_insert_post() function
     */
        $new_page_id = wp_insert_post($args);

        /*
     * get all current page terms ad set them to the new page draft
     */
        $taxonomies = get_object_taxonomies($page->post_type); // returns array of taxonomy names for page type, ex array("category", "page_tag");
        foreach ($taxonomies as $taxonomy) {
            $page_terms = wp_get_object_terms($page_id, $taxonomy, array('fields' => 'slugs'));
            wp_set_object_terms($new_page_id, $page_terms, $taxonomy, false);
        }

        /*
     * duplicate all page meta just in two SQL queries
     */
        $page_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$page_id");
        if (count($page_meta_infos) != 0) {
            $sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
            foreach ($page_meta_infos as $meta_info) {
                $meta_key = $meta_info->meta_key;
                if ($meta_key == '_wp_old_slug') continue;
                $meta_value = addslashes($meta_info->meta_value);
                $sql_query_sel[] = "SELECT $new_page_id, '$meta_key', '$meta_value'";
            }
            $sql_query .= implode(" UNION ALL ", $sql_query_sel);
            $wpdb->query($sql_query);
        }
        /*
     * finally, redirect to the edit page screen for the new draft
     */
        wp_redirect(admin_url('post.php?action=edit&post=' . $new_page_id));
        exit;
    } else {
        wp_die('Page creation failed, could not find original page: ' . $page_id);
    }
}
add_action('admin_action_pharvaris_duplicate_page_as_draft', 'pharvaris_duplicate_page_as_draft');
function pharvaris_duplicate_page_link($actions, $page)
{
    if (current_user_can('edit_posts')) {
        $actions['duplicate'] = '<a href="' . wp_nonce_url('admin.php?action=pharvaris_duplicate_page_as_draft&post=' . $page->ID, basename(__FILE__), 'duplicate_nonce') . '" title="Duplicate this Page" rel="permalink">Duplicate Page</a>';
    }
    return $actions;
}
add_filter('page_row_actions', 'pharvaris_duplicate_page_link', 10, 2);

// * * * * * * * * * * * * *  - - - H U B S P O T - - - * * * * * * * * * * * * * //

// Add HubSpot script to page head
function hubspot_javascript()
{
    ?>
        <!-- Start of HubSpot Embed Code -->
            <!-- <script type="text/javascript" id="hs-script-loader" async defer src="//js.hs-scripts.com/24308407.js"></script> -->
        <!-- End of HubSpot Embed Code -->
    <?php
}
add_action('wp_head', 'hubspot_javascript');

// * * * * * * * * * * * * *  - - - M A R K E R . I O - - - * * * * * * * * * * * * * //

// Add marker.io to page head
function marker_io()
{
    ?>
        <script>
            window.markerConfig = {
                project: '659cad079ff6d511940ecd54',
                source: 'snippet'
            };

            !function(e,r,a){if(!e.__Marker){e.__Marker={};var t=[],n={__cs:t};["show","hide","isVisible","capture","cancelCapture","unload","reload","isExtensionInstalled","setReporter","setCustomData","on","off"].forEach(function(e){n[e]=function(){var r=Array.prototype.slice.call(arguments);r.unshift(e),t.push(r)}}),e.Marker=n;var s=r.createElement("script");s.async=1,s.src="https://edge.marker.io/latest/shim.js";var i=r.getElementsByTagName("script")[0];i.parentNode.insertBefore(s,i)}}(window,document);
        </script>
    <?php
}
add_action('wp_head', 'marker_io');

function homepage_section() {
    if (is_front_page()) {

        ?>
        <script>
            //Select the elements you want inside
            const divs = document.querySelectorAll("#home-tradeoff-row, #hae-banner-section, #hae-facts-section");

            // create the div to wrap your elements
            const wrapper = document.createElement("div");
            wrapper.classList.add('section-background');

            // add it to the DOM
            divs[0].before(wrapper);

            // insert the elements into the newly created div
            divs.forEach(div => wrapper.append(div));
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'homepage_section' );

function overview_section() {
    if (is_page('hae-overview')) {
        ?>
        <script>
            //Select the elements you want inside
            const divs = document.querySelectorAll("#overview-banner-section, #overview-facts-section");

            // create the div to wrap your elements
            const wrapper = document.createElement("div");
            wrapper.classList.add('section-background');

            // add it to the DOM
            divs[0].before(wrapper);

            // insert the elements into the newly created div
            divs.forEach(div => wrapper.append(div));
        </script>
        <?php
    }
}
add_action( 'wp_footer', 'overview_section' );
