<?php
/**
 * Plugin Name:	Live Update
 * Plugin URI:	http://github.com/stompweb/live-update
 * Description:	Edit Post Data & Meta Fields on the front end
 * Version: 	0.1
 * Author: 		Steven Jones
 * Author URI: 	http://stomptheweb.co.uk
 * License:		GPLv3
 */

if(!defined('LU_PLUGIN_URL')) {
	define('LU_PLUGIN_URL', plugin_dir_url( __FILE__ ));
}

if(!defined('LU_PLUGIN_DIR')) {
	define('LU_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
}

if(!defined('LU_PLUGIN_FILE')) {
	define('LU_PLUGIN_FILE', __FILE__);
}

/* 
 * Load Plugin Files
 */
require_once dirname( __FILE__ ) . '/includes/init.php';
require_once dirname( __FILE__ ) . '/includes/functions.php';
require_once dirname( __FILE__ ) . '/includes/example-fields.php';
require_once dirname( __FILE__ ) . '/includes/save.php';

/* 
 * Load Assets
 */
function lu_stylesheets() {

	if ( !current_user_can( 'edit_posts' ) ) {
		return;
	}
    
    wp_register_style( 'live-update', LU_PLUGIN_URL . 'assets/css/style.css' );

    // If it exists, load the user's stylesheet for editing
    $custom_css = locate_template( 'css/live-update.css' );
	if ( !empty( $custom_css ) ) {
		wp_register_style( 'custom-live-update', get_stylesheet_directory_uri() . '/css/live-update.css' );      
	}
  	
    if (is_singular()) {
    	wp_enqueue_style( 'jquery-style', 'http://ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/smoothness/jquery-ui.css');
    	wp_enqueue_style( 'live-update' );
    	wp_enqueue_style( 'dashicons' );

    	if ( !empty( $custom_css ) ) {
			wp_enqueue_style( 'custom-live-update' );
    	}
    }

}
add_action( 'wp_enqueue_scripts', 'lu_stylesheets' );

function lu_register_scripts() {

    wp_register_script('live-update', LU_PLUGIN_URL . 'assets/js/main.min.js', null, null, true);
    
}
add_action( 'init', 'lu_register_scripts' );

function lu_enqueue_scripts() {

	if ( !current_user_can( 'edit_posts' ) ) {
		return;
	}

	if (is_singular()) {
		wp_enqueue_script('jquery');
		wp_enqueue_media();
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-resizable');
		wp_enqueue_script('live-update');
		wp_localize_script('live-update', 'lu', array( 
			'ajaxurl' => admin_url( 'admin-ajax.php'), 
			'post_id' => get_the_ID(),
			'nonce'   => wp_create_nonce('lu-nonce'),
		));
	}
	
}
add_action( 'wp_enqueue_scripts', 'lu_enqueue_scripts' );