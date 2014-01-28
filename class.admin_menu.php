<?php
class IZC_Admin_menu{

	public function build_menu($plugin_name) {
		
		$menu = get_option('iz-menus', array());
		
		add_menu_page($menu[$plugin_name]['menu_page']['page_title'] , $menu[$plugin_name]['menu_page']['menu_title'] , $menu[$plugin_name]['menu_page']['capability'], $menu[$plugin_name]['menu_page']['menu_slug'], $menu[$plugin_name]['menu_page']['function'] ,$menu[$plugin_name]['menu_page']['icon_url'],$menu[$plugin_name]['menu_page']['position']);
		
		if(is_array($menu[$plugin_name]['sub_menu_page']))
			{
			foreach($menu[$plugin_name]['sub_menu_page'] as $sub_menu)
				{
				add_submenu_page( $sub_menu['parent_slug'], $sub_menu['page_title'],$sub_menu['menu_title'], $sub_menu['capability'], $sub_menu['menu_slug'], $sub_menu['function']);
				}
			}	
	}
	
	public function add_sub_menu_item($plugin_name, $args){

		$menu = get_option('iz-menus', array());
		
			if(is_array($menu[$plugin_name]['sub_menu_page']))
				{
				if(!in_array($menu[$plugin_name]['sub_menu_page'],$args))
					{
					$add_menu = array_merge($menu[$plugin_name]['sub_menu_page'],$args);
					$menu[$plugin_name]['sub_menu_page'] = $add_menu;
					}
				}
		update_option('iz-menus', $menu);
	}
	
	public function remove_sub_menu_item($plugin_name,$menu_name){
		$menu = get_option('iz-menus', array());
		unset($menu[$plugin_name]['sub_menu_page'][$menu_name]);
		update_option('iz-menus', $menu);
	}
}
?>