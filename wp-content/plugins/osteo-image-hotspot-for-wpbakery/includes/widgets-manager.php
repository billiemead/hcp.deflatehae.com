<?php

namespace Osteo_Image_Hotspot;
defined( 'ABSPATH' ) || die();

if ( !class_exists( 'Osteo_Image_Hotspot_Widgets_Manager' ) ) {

    class Osteo_Image_Hotspot_Widgets_Manager{

            private static $_instance = null;
            public static function instance() {
                if ( is_null( self::$_instance ) ) {
                    self::$_instance = new self();
                }
                return self::$_instance;
            }

        public function __construct(){

            // Add Plugin actions
            add_action("vc_after_init", [ $this, 'init_widgets' ] );
        }

        // Control Widgets
        public function init_widgets(){
            
            require_once OSTEO_IMAGE_HOTSPOT_PATH . 'widgets/image-hotspot.php';
            new Widgets\Osteo_Image_Hotspot();

        }


    }

    Osteo_Image_Hotspot_Widgets_Manager::instance();

}