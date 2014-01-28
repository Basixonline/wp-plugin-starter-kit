<?php
define('PLUGIN_MODULE_ABSPATH', ABSPATH . 'wp-content/modules/');
class IZC_Instalation{
	
	public 
	$role,
	$component_name,
	$component_prefix,
	$component_alias,
	$component_default_fields,
	$component_menu,
	$db_table_fields, 
	$db_table_primary_key,
	$error_msg;

	public function __construct(){}
	
	public function run_instalation($type){	
		
		//Arrays to be used by CORE
		$dummy_array = array();

		add_option( 'iz-menus' 					, 	array( '' ) );
		$menus 		= get_option('iz-menus' , array());
		
		
		//Admin Menu
		$admin_menu = array_merge($menus,$this->component_menu);
		update_option('iz-menus',$admin_menu);
		
		
	}
}
?>