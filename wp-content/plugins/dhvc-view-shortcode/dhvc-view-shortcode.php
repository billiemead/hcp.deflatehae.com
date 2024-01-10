<?php
/*
* Plugin Name: DHVC View Shortcode
* Plugin URI: http://sitesao.com/item/easy-view-shortcode-visual-composer/
* Description: Easy view and copy shortcode in WPBakery Page Builder plugin
* Version: 1.1.2
* Author: Sitesao
* Author URI: http://sitesao.com/
* License: License GNU General Public License version 2 or later;
* Copyright 2014  Sitesao
*/
if ( ! defined( 'ABSPATH' ) ) exit;
if(!defined('DHVC_VIEW_SHORTCODE_VERSION'))
	define('DHVC_VIEW_SHORTCODE_VERSION','1.1.1');

if(!class_exists('DHVCViewShortcode')):
class DHVCViewShortcode {
	public function __construct(){
		add_action('init',array(&$this,'init'));
	}
	
	public function init(){
		if (!defined('WPB_VC_VERSION')) {
			add_action('admin_notices', array(&$this,'notice'));
			return;
		}
		if(is_admin()){
			add_action( 'admin_print_scripts-post.php', array( &$this, 'enqueue_scripts' ),100 );
			add_action( 'admin_print_scripts-post-new.php', array( &$this, 'enqueue_scripts' ),100 );
			//Disnable auto save
			add_action( 'admin_print_scripts', array( &$this, 'disable_autosave' ) );
		}
	}
	public function disable_autosave(){
		global $post;
	
		//if ( $post && get_post_type( $post->ID ) === 'page-section' ) {
		wp_dequeue_script( 'autosave' );
		//}
	}
	
	public function notice(){
		$plugin = get_plugin_data(__FILE__);
		echo '<div class="updated">
			    <p>' . sprintf(__('<strong>%s</strong> requires <strong><a href="http://bit.ly/1gKaeh5" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.'), $plugin['Name']) . '</p>
			  </div>';
	}
	
	public function enqueue_scripts(){
		wp_enqueue_style('dhvc-view-shortcode',untrailingslashit( plugins_url( '/', __FILE__ ) ).'/style.css',null,DHVC_VIEW_SHORTCODE_VERSION);
		wp_enqueue_script('dhvc-view-shortcode',untrailingslashit( plugins_url( '/', __FILE__ ) ).'/script.js',array('jquery'),DHVC_VIEW_SHORTCODE_VERSION,true);
	}
	
}
new DHVCViewShortcode();
endif;
