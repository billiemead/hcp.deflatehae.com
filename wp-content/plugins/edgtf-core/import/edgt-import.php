<?php
if (!function_exists ('add_action')) {
    header('Status: 403 Forbidden');
    header('HTTP/1.1 403 Forbidden');
    exit();
}
#[AllowDynamicProperties]
class ConallEdgeClassImport {

    public $message = "";
    public $attachments = false;
    function __construct() {
        add_action('admin_menu', array(&$this, 'edgt_admin_import'));
        add_action('admin_init', array(&$this, 'edgt_register_theme_settings'));

    }
    function edgt_register_theme_settings() {
        register_setting( 'edgt_options_import_page', 'edgt_options_import');
    }

    public function import_content($file){
        ob_start();
        require_once(EDGE_CORE_ABS_PATH . '/import/class.wordpress-importer.php');
        $edgt_import = new WP_Import();
        set_time_limit(0);

        $edgt_import->fetch_attachments = $this->attachments;
        $returned_value = $edgt_import->import($file);
        if(is_wp_error($returned_value)){
            $this->message = esc_html__("An Error Occurred During Import", "edgtf-core");
        }
        else {
            $this->message = esc_html__("Content imported successfully", "edgtf-core");
        }
        ob_get_clean();
    }

    public function import_widgets($file, $file2){
        $this->import_custom_sidebars($file2);
        $options = $this->file_options($file);
        foreach ((array) $options['widgets'] as $edgt_widget_id => $edgt_widget_data) {
            update_option( 'widget_' . $edgt_widget_id, $edgt_widget_data );
        }
        $this->import_sidebars_widgets($file);
        $this->message = esc_html__("Widgets imported successfully", "edgtf-core");
    }

    public function import_sidebars_widgets($file){
        $edgt_sidebars = get_option("sidebars_widgets");
        unset($edgt_sidebars['array_version']);
        $data = $this->file_options($file);
        if ( is_array($data['sidebars']) ) {
            $edgt_sidebars = array_merge( (array) $edgt_sidebars, (array) $data['sidebars'] );
            unset($edgt_sidebars['wp_inactive_widgets']);
            $edgt_sidebars = array_merge(array('wp_inactive_widgets' => array()), $edgt_sidebars);
            $edgt_sidebars['array_version'] = 2;
            wp_set_sidebars_widgets($edgt_sidebars);
        }
    }

    public function import_custom_sidebars($file){
        $options = $this->file_options($file);
        update_option( 'edgt_sidebars', $options);
        $this->message = esc_html__("Custom sidebars imported successfully", "edgtf-core");
    }

    public function import_options($file){
        $options = $this->file_options($file);
        update_option( 'edgt_options_conall', $options);
        $this->message = esc_html__("Options imported successfully", "edgtf-core");
    }

    public function import_menus($file){
        global $wpdb;
        $edgt_terms_table = $wpdb->prefix . "terms";
        $this->menus_data = $this->file_options($file);
        $menu_array = array();
        foreach ($this->menus_data as $registered_menu => $menu_slug) {
            $term_rows = $wpdb->get_results($wpdb->prepare("SELECT * FROM $edgt_terms_table where slug=%s", $menu_slug), ARRAY_A);
            if(isset($term_rows[0]['term_id'])) {
                $term_id_by_slug = $term_rows[0]['term_id'];
            } else {
                $term_id_by_slug = null;
            }
            $menu_array[$registered_menu] = $term_id_by_slug;
        }
        set_theme_mod('nav_menu_locations', array_map('absint', $menu_array ) );

    }
    public function import_settings_pages($file){
        $pages = $this->file_options($file);
        foreach($pages as $edgt_page_option => $edgt_page_id){
            update_option( $edgt_page_option, $edgt_page_id);
        }
    }

    public function file_options($file){
        $file_content = $this->edgt_file_contents($file);
        if ($file_content) {
            $unserialized_content = unserialize(base64_decode($file_content));
            if ($unserialized_content) {
                return $unserialized_content;
            }
        }
        return false;
    }

    function edgt_file_contents( $path ) {
		$url      = "http://export.edge-themes.com/" . $path;
		$response = wp_remote_get($url);
		$body     = wp_remote_retrieve_body($response);
		return $body;
    }

    function edgt_admin_import() {
        if (edgt_core_theme_installed()) {
            global $conall_edge_framework;

            $slug = "_tabimport";
            $this->pagehook = add_submenu_page(
                'conall_edge_theme_menu',
                esc_html__( 'Edge Options - Import', 'edgtf-core' ),                   // The value used to populate the browser's title bar when the menu page is active
                esc_html__( 'Import', 'edgtf-core' ),                   // The text of the menu in the administrator's sidebar
                'administrator',                  // What roles are able to access the menu
                'conall_edge_theme_menu'.$slug,                // The ID used to bind submenu items to this menu
                array($conall_edge_framework->getSkin(), 'renderImport')
            );

            add_action('admin_print_scripts-'.$this->pagehook, 'conall_edge_enqueue_admin_scripts');
            add_action('admin_print_styles-'.$this->pagehook, 'conall_edge_enqueue_admin_styles');
        }
    }

	function update_meta_fields_after_import( $folder ) {
		global $wpdb;

		$url       = esc_url( home_url( '/' ) );
		$demo_urls = $this->import_get_demo_urls( $folder );

		foreach ( $demo_urls as $demo_url ) {
			$sql_query   = "SELECT meta_id, meta_value FROM {$wpdb->postmeta} WHERE meta_key LIKE 'edgtf%' AND meta_value LIKE '" . esc_url( $demo_url ) . "%';";
			$meta_values = $wpdb->get_results( $sql_query );

			if ( ! empty( $meta_values ) ) {
				foreach ( $meta_values as $meta_value ) {
					$new_value = $this->recalc_serialized_lengths( str_replace( $demo_url, $url, $meta_value->meta_value ) );

					$wpdb->update( $wpdb->postmeta,	array( 'meta_value' => $new_value ), array( 'meta_id' => $meta_value->meta_id )	);
				}
			}
		}
	}

	function update_options_after_import( $folder ) {
		$url       = esc_url( home_url( '/' ) );
		$demo_urls = $this->import_get_demo_urls( $folder );

		foreach ( $demo_urls as $demo_url ) {
			$global_options    = get_option( 'edgt_options_conall' );
			$new_global_values = str_replace( $demo_url, $url, $global_options );

			update_option( 'edgt_options_conall', $new_global_values );
		}
	}

	function import_get_demo_urls( $folder ) {
		$demo_urls  = array();
		$domain_url = str_replace( '/', '', $folder ) . '.' . '.qodeinteractive.com/';

		$demo_urls[] = ! empty( $domain_url ) ? 'http://' . $domain_url : '';
		$demo_urls[] = ! empty( $domain_url ) ? 'https://' . $domain_url : '';

		return $demo_urls;
	}

	function recalc_serialized_lengths( $sObject ) {
		$ret = preg_replace_callback( '!s:(\d+):"(.*?)";!', array( $this, 'recalc_serialized_lengths_callback' ), $sObject );

		return $ret;
	}

	function recalc_serialized_lengths_callback( $matches ) {
		return "s:" . strlen( $matches[2] ) . ":\"$matches[2]\";";
	}
}

function edgt_init_import_object(){
    global $conall_edge_import_object;
    $conall_edge_import_object = new ConallEdgeClassImport();
}

add_action('init', 'edgt_init_import_object');

if(!function_exists('conall_edge_dataImport')){
    function conall_edge_dataImport(){
        global $conall_edge_import_object;

        if ($_POST['import_attachments'] == 1)
            $conall_edge_import_object->attachments = true;
        else
            $conall_edge_import_object->attachments = false;

        $folder = "conall/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $conall_edge_import_object->import_content($folder.$_POST['xml']);

        die();
    }

    add_action('wp_ajax_edgt_dataImport', 'conall_edge_dataImport');
}

if(!function_exists('conall_edge_widgetsImport')){
    function conall_edge_widgetsImport(){
        global $conall_edge_import_object;

        $folder = "conall/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $conall_edge_import_object->import_widgets($folder.'widgets.txt',$folder.'custom_sidebars.txt');

        die();
    }

    add_action('wp_ajax_edgt_widgetsImport', 'conall_edge_widgetsImport');
}

if(!function_exists('conall_edge_optionsImport')){
    function conall_edge_optionsImport(){
        global $conall_edge_import_object;

        $folder = "conall/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $conall_edge_import_object->import_options($folder.'options.txt');

        die();
    }

    add_action('wp_ajax_edgt_optionsImport', 'conall_edge_optionsImport');
}

if(!function_exists('conall_edge_otherImport')){
    function conall_edge_otherImport(){
        global $conall_edge_import_object;

        $folder = "conall/";
        if (!empty($_POST['example']))
            $folder = $_POST['example']."/";

        $conall_edge_import_object->import_options($folder.'options.txt');
        $conall_edge_import_object->import_widgets($folder.'widgets.txt',$folder.'custom_sidebars.txt');
        $conall_edge_import_object->import_menus($folder.'menus.txt');
        $conall_edge_import_object->import_settings_pages($folder.'settingpages.txt');

	    $conall_edge_import_object->update_meta_fields_after_import( $folder );
	    $conall_edge_import_object->update_options_after_import( $folder );

        die();
    }

    add_action('wp_ajax_edgt_otherImport', 'conall_edge_otherImport');
}