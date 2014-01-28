<?php
//JS Dependancies
define('SESSION_ID',rand(0,99999999999));
wp_enqueue_script('jquery');
/***************************************/
/**********  CORE CLASSES  *************/
/***************************************/

include_once( 'class.install.php');
include_once( 'class.admin_menu.php');
include_once( 'class.functions.php');
include_once( 'class.template.php');
/***************************************/
/**************  ADMIN  ****************/
/***************************************/
if(is_admin() && ( isset($_GET['page']) && stristr($_GET['page'],'xpsk') ) ){
	/***************/
	/*** WP Core ***/
	/***************/
	//JS
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-widget');
	wp_enqueue_script('jquery-ui-mouse');
	wp_enqueue_script('jquery-ui-resizable');
	wp_enqueue_script('jquery-ui-position');
	wp_enqueue_script('jquery-ui-sortable');
	wp_enqueue_script('jquery-ui-draggable');
	wp_enqueue_script('jquery-ui-droppable');
	wp_enqueue_script('jquery-ui-accordion');
	wp_enqueue_script('jquery-ui-autocomplete');
	wp_enqueue_script('jquery-ui-menu');
	wp_enqueue_script('jquery-ui-datepicker');
	wp_enqueue_script('jquery-ui-slider');
	wp_enqueue_script('jquery-ui-button');
	wp_enqueue_script('jquery-ui-tooltip');
	wp_enqueue_script('admin-widgets');
	wp_enqueue_script('wp-admin-response');
	wp_enqueue_script('admin-tags');
	wp_enqueue_script('underscore');
	wp_enqueue_script('backbone');
	wp_enqueue_script('json2');
	//CSS
	wp_enqueue_style('widgets');
	wp_enqueue_style ('jquery-ui');
	
	/***************/
	/*** XPSK Core ***/
	/***************/
	//JS
	wp_register_script('xpsk-module-widgets', WP_PLUGIN_URL . '/xpsk/js/module-widgets.js');
	wp_enqueue_script ('xpsk-module-widgets');
	
	wp_register_script('xpsk-linked-modules', WP_PLUGIN_URL . '/xpsk/js/linked-modules.js');
	wp_enqueue_script ('xpsk-linked-modules');

	//Generic functions
	wp_register_script('xpsk-core-functions', WP_PLUGIN_URL . '/xpsk/js/functions.js');
	wp_enqueue_script('xpsk-core-functions');

	//CSS
	wp_print_scripts();
	wp_print_styles();
}
/***************************************/
/**************  PUBLIC  ***************/
/***************************************/
//JS
wp_register_script('public-functions', WP_PLUGIN_URL . '/xpsk/js/public.js');
wp_enqueue_script('public-functions');

wp_register_script('core-public-functions', WP_PLUGIN_URL . '/xpsk/js/public-functions.js');
wp_enqueue_script('core-public-functions');

//CSS
wp_register_style('defaults', WP_PLUGIN_URL . '/xpsk/css/defaults.css');
wp_enqueue_style('defaults');
?>