<?php

add_action('wp_ajax_build_admin_table_pagination',array('IZC_Template','build_admin_table_pagination'));

class IZC_Template{
	
	public $page_header, $page_menu, $page_body, $page_footer;
	
	public $sidebar_header,$sidebar_body, $sidebar_footer;
	
	public $form_fields, $component_table;
	
	public $plugin_alias;
	
	public $data_fields;
	
	public function print_template(){
		echo $this->page_header.$this->page_body.$this->page_footer;
	}
	
	public function print_sidebar_template(){
		echo $this->sidebar_header.$this->sidebar_body.$this->sidebar_footer;
	}
	
	public function build_header($title='',$subtitle='',$extra_menu_items=array(),$description='',$plugin_alias=''){
		
		
		
		$this->page_header  .= '<div id="plugin_alias" style="display:none;">';
		$this->page_header  .= $this->plugin_alias;
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="component_table" style="display:none;">';
		$this->page_header  .= $this->component_table;
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="edit_Id" style="display:none;">';
		$this->page_header  .= $_REQUEST['Id'];
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="redirect" style="display:none;">';
		$this->page_header  .= $_REQUEST['redirect_after_edit'];
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="nde_Id" style="display:none;">';
		$this->page_header  .= $_REQUEST['nde_Id'];
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="upload_path" style="display:none;">';
		$this->page_header  .=	ABSPATH.'wp-content/uploads/wa-core/';
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="modules_uri" style="display:none;">';
		$this->page_header  .=	get_option('siteurl').'/wp-content/modules/';
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="site_url" style="display:none;">';
		$this->page_header  .=	get_option('siteurl');
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div id="upload_uri" style="display:none;">';
		$this->page_header  .=	get_option('siteurl').'/wp-content/uploads/wa-core/';
		$this->page_header  .= '</div>';
		
		$this->page_header  .= '<div class="wrap">';
		
		
		
		if($title)
			$this->page_header .= '<h1 class="'.$this->plugin_alias.'">';$this->page_header .= $title;$this->page_header .= '</h1>';
			
		if($subtitle)
			$this->page_header .= '<h2>';$this->page_header .= $subtitle;$this->page_header .= '</h2>';
		
		if($description)
			$this->page_header .= '<h5>';$this->page_header .= $description;$this->page_header .= '</h5>';
		
		$menus = get_option('iz-menus');
		//echo '<pre>';
		//print_r($menus[IZC_Functions::unformat_name($this->plugin_alias)]);
		
		/*$this->page_header .= '<ul class="subsubsub">';
		$this->page_header .= '<li><a '.(($_REQUEST['page']==$menus[IZC_Functions::unformat_name($this->plugin_alias)]['menu_page']['menu_slug']) ? 'class="current"' : '').' href="?page='.$menus[IZC_Functions::unformat_name($this->plugin_alias)]['menu_page']['menu_slug'].'" >'.$menus[IZC_Functions::unformat_name($this->plugin_alias)]['menu_page']['menu_title'].'</a> |</li>';
	 	
		foreach($menus[IZC_Functions::unformat_name($this->plugin_alias)]['sub_menu_page'] as $submenu)
			{
			$this->page_header .= '<li><a '.(($_REQUEST['page']==$submenu['menu_slug']) ? 'class="current"' : '').' href="?page='.$submenu['menu_slug'].'"	 >'.$submenu['menu_title'].'</a> |</li>';
			}
		
		foreach($extra_menu_items as $key=>$val)
			{
			$this->page_header .= '<li><a '.(($_REQUEST['page']==$submenu['menu_slug']) ? 'class="current"' : '').' href="?page='.$val.'"	 >'.$key.'</a> |</li>';
			}
			
		
		$this->page_header .= '</ul>';*/
		$this->page_header .= $menu.'<br class="clear" />';
		$this->page_header .= '<div class="header_info"></div>';
		//$this->page_header .= '<div class="header_info_sec"></div>';
		$this->page_header .= '<div id="ajax-response" class="iz-ajax-response"></div>';
		
		return $this->page_header;
	}
	
	public function build_menu($menu=''){

		$this->page_menu  = '<ul class="subsubsub">';
		$this->page_menu .= $menu;
		$this->page_menu .= '</ul>';
		return $this->page_menu;
	}
	
	public function build_body($body=''){
		$this->page_body .= $body;
		return $this->page_body;
	}
	
	public function build_footer($text=''){
		$this->page_footer  = '<div class="footer">';
		$this->page_footer .= $text;
		$this->page_footer .= '</div>';
		$this->page_footer .= '</div>';
		return $this->page_footer;
	}
	
	
	public function build_sidebar_head(){
		$this->sidebar_header .= '<div class="iz-sidebar-head">';
		$this->sidebar_header .= '<div class="iz-top ieHax"></div>';
		$this->sidebar_header .= '</div>';
	}
	
	public function build_sidebar_body($body='',$title=''){
		$this->sidebar_body .= '<div class="iz-mid ieHax">';
		$this->sidebar_body .= '<div class="sidebar-title">';
		$this->sidebar_body .= '<h1>'.$title.'</h1>';
		$this->sidebar_body .= '</div>';
		$this->sidebar_body .= $body;
		$this->sidebar_body .= '</div>';
	}
	
	public function build_sidebar_footer($text=''){
		$this->sidebar_footer .= '<div style="clear:both"> </div>';
		$this->sidebar_footer .= '<div class="iz-bottom ieHax">';
		$this->sidebar_footer .= $text;
		$this->sidebar_footer .= '</div>';
	}

	
	public function build_form(){
		
		global  $wpdb;
		
		$edit = (isset($_REQUEST['Id'])) ? true : false;
		
		
		$output .= 	'<div id="col-left">';
			$output .= '<div class="col-wrap">';
				$output .= '<div class="form-wrap">';
				$output .= '<h3 class="sub_sub_heading">'.(($edit) ? 'Edit '.IZC_Database::get_title($_REQUEST['Id'], $this->component_table) : 'Add item').'</h3>';
					
					$output .= 	'<form id="addtag" name="addItem" method="post" action="" class="validate">';
						$output .= '<input type="hidden" name="action" value="'.((isset($_REQUEST['Id'])) ? 'do_edit' : 'do_insert' ).'">';
						//Used on EDIT
						$output .= '<input type="hidden" name="edit_Id" value="'.$_REQUEST['Id'].'">';
						$output .= '<input type="hidden" name="page" value="'.$_REQUEST['page'].'">';
						$output .= '<input type="hidden" name="selected_Id" value="'.IZC_Database::get_parent($_REQUEST['Id'],$this->component_table).'">';
						
						//Called by pagenation ajax
						$output .= '<input type="hidden" name="orderby" value="">';
						$output .= '<input type="hidden" name="order" value="">';
						$output .= '<input type="hidden" name="current_page" value="">';
						
						$output .= '<input type="hidden" name="plugin" value="'.$this->plugin_alias.'">';
						$output .= '<input type="hidden" name="plugin_alias" value="'.$this->plugin_alias.'">';
						/*************************/
						/**** Linked Modules *****/
						/*************************/
						
						//Contruction of hidden fields used by public JS on EDIT
						$db = new IZC_Database();
						$db->plugin_table = $this->component_table;
						$foreign_fields = $db->get_foreign_fields('_Id');
						$output .= '<div class="iz-forms-holder">';
						foreach($foreign_fields as $foreign_field)
								{
								//echo $filter;
								$output .= '<input type="hidden" name="selected_'.$foreign_field.'" value="'.IZC_Database::get_foreign_Id($_REQUEST['Id'],$foreign_field,$this->component_table).'">';
								}
						
						/*************************/
						
						/*************************/
						/**** Module Filters *****/
						/*************************/
						
						//Contruction for hidden fields used by public JS on EDIT
						$filters = get_option('iz-filters',array());
						
						foreach($filters[$this->plugin_alias] as $filter)
								{
								switch($filter['type'])
									{
									case 'dropdown':
									$output .= '<input type="hidden" name="selected_'.$filter['name'].'" value="'.IZC_Database::get_parent($_REQUEST['Id'],$this->component_table).'">';;
									break;
									case 'text':
									$get_val = $wpdb->get_var('SELECT '.$filter['name'].' FROM '.$wpdb->prefix . $this->component_table .' WHERE Id='.$_REQUEST['Id']);
									$output .= '<input type="hidden" name="get_'.$filter['name'].'" value='.$get_val.'>';
									}
								}
						/*************************/
						
						$output .= '<input type="hidden" name="table" value="'.$this->component_table.'">';
					
						$i=0;
						
						foreach($this->form_fields as $key=>$val)	
							{
							
							if($key!='Parent')
									{
									$is_foreing_key = $wpdb->query('SHOW FIELDS FROM '.$wpdb->prefix . $this->component_table. ' LIKE "%'.IZC_Functions::format_name($key).'_Id"');
									$table_fields[$i] = ($is_foreing_key) ? 'wam_'.IZC_Functions::format_name($key).'_Id': $key;
									$i++;
									}
					
							if(is_array($val))
								{
								//$output .= '<fieldset class="'.$form_elements['type'].' '.IZC_Functions::unformat_name($key).'">';
									$output .= '<label>'.IZC_Functions::unformat_name(IZC_Functions::unformat_name($key)).'</label>';
										//$output .= '<div class="iz-form-item">';
											$output .= $this->build_field($val);
										//$output .= '</div>';
								//$output .= '</fieldset>';
									
								
								}
							else
								{	
								$output .= $val;
								}
							}
							
						$db->plugin_alias = $this->plugin_alias;
						$plugin_field = $db->get_foreign_fields('plugin');
						
						
						/*if(!empty($plugin_field))
							{
							$output .= $db->share_item();
							}*/
						$output .= 	'</div>';
						$output .=	'<p class="submit"><input type="submit" value="        '.(($edit) ? 'Save' : 'Add Item').'        " class="button button-primary iz-submit" data-action="'.((isset($_REQUEST['Id'])) ? 'iz-update' : 'iz-insert').'" id="submit" name="submit">';
						$output .= ($edit) ? '&nbsp;&nbsp;&nbsp;<input type="button" class="cancel" value="   Cancel   " onclick="window.location.href = \''.get_option('siteurl').'/wp-admin/admin.php?page='.$_GET['page'].'\';">' : '';
						$output .= '</p>';
					$output .= '<input type="hidden" name="fields" value=\''.json_encode($table_fields).'\'>';
					$output .= 	'</form>';	
					
				$output .= 	'</div>';
			$output .= 	'</div>';
		$output .= 	'</div>';
		
		return $output;
	}
	
	public function build_admin_table_pagination(){

		if(isset($_POST['table']))
			$table = $_POST['table'];
		else
			$table = $this->component_table;
		
		$total_records = IZC_Template::get_total_records($table,$_POST['additional_params']);
		
		$total_pages = ((is_float($total_records/10)) ? (floor($total_records/10))+1 : $total_records/10);
		
		$output .= '<span class="displaying-num">'.IZC_Template::get_total_records($table).' items</span>';
		if($total_pages>1)
			{				
			$output .= '<span class="pagination-links">';
			$output .= '<a class="first-page iz-first-page">«</a>&nbsp;';
			$output .= '<a title="Go to the next page" class="iz-prev-page prev-page">‹</a>&nbsp;';
			$output .= '<span class="paging-input"> ';
			$output .= '<span class="current-page">'.($_POST['current_page']+1).'</span> of <span class="total-pages">'.$total_pages.'</span>&nbsp;</span>';
			$output .= '<a title="Go to the next page" class="iz-next-page next-page">›</a>&nbsp;';
			$output .= '<a title="Go to the last page" class="iz-last-page last-page">»</a></span>';
			}
		echo $output;
		die();
	}
	
	public function get_total_records($table,$additional_params=array()){
		global $wpdb;
		
		$tree = $wpdb->query('SHOW FIELDS FROM '. $wpdb->prefix . $table .' LIKE "parent_Id"');
		
		$additional_params = json_decode(str_replace('\\','',$_POST['additional_params']),true);
		
		if(is_array($additional_params))
			{
			foreach($additional_params as $column=>$val)
				$where_str .= ' AND '.$column.'="'.$val.'"';
			}
		
		return $wpdb->get_var('SELECT count(*) FROM '.$wpdb->prefix . $table.' WHERE Id<>"" '. (($tree) ? ' AND parent_Id=0' : '').' '. (($_POST['plugin_alias']) ? ' AND (plugin="'.$_POST['plugin_alias'].'" || plugin="shared")' : '').' '.$where_str );
	}
	
	public function build_data_list(){

		$output = '';
		$output .= '<div class="col-wrap">';
			$output .= '<p>&nbsp;</p>';
			$output .= '<form method="post" action="" id="posts-filter">';
				$output .= '<input type="hidden" name="table" value="'.$this->component_table.'">';
				
				$output .= '<div class="tablenav top">';

					$output .= '<div class="alignleft actions"><div class="hide-form-wrap">&lt;&nbsp;&nbsp;Hide Form</div>';
						$output .= '<select name="action">';
							$output .= '<option selected="selected" value="-1">Bulk Actions</option>';
							$output .= '<option value="batch-delete">Delete</option>';
						$output .= '</select>';
						$output .= '<input type="submit" value="Apply" class="button-secondary action" id="doaction" name="">';
					$output .= '</div>';
					$output .= '<div class="table-options">';
						
						$output .= '<div class="tab">';
							$output .= '<p>Table Options</p>';						
						
							$output .= '<div class="hide-cols-wrapper">';	
								$i = 0;
								
								foreach($this->data_fields as $header)	
									{
									$output .= '<span class="the-col">';
									$output .= '<input id="'.$header.'_'.$i.'" type="checkbox" name="'.$header.'" value="'.$i.'" checked="checked" />&nbsp;&nbsp;';
									$output .= '<label for="'.$header.'_'.$i.'">'.IZC_Functions::unformat_name(str_ireplace('id','',$header)).'</label>';
									$output .= '</span>';
									$i++;
									}
							$output	.= '</div>';
						$output	.= '</div>';
					$output	.= '</div>';
				
					$output	.= '<div class="tablenav-pages">';
					//Populated from Ajax response: build_admin_table_pagination
					$output	.= '</div>';
					
				$output .= '</div>';
	
				$output .= '<br class="clear">';

				$output .= '<table cellspacing="0" class="wp-list-table resiable-columns widefat fixed tags iz-list-table resizabletable" id="iz_col_resize">';
				
					$output .= '<thead>';
					$output .= '<tr>';
					$output .= '<th style="" class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>';	
					
					/*foreach($this->data_fields as $header)	
							{
							$output .= '<th valign="bottom" class="manage-column sortable '.((isset($_REQUEST['orderby'])) ? (($_REQUEST['order']=='desc' && $_REQUEST['orderby']==$header) ? 'desc' : 'asc') : 'desc').'  column-'.$header.'"><a href="?page='.$_REQUEST['page'].'&orderby='.$header.'&amp;order='.(($_REQUEST['order']=='asc') ? 'desc' : 'asc').'"><span>'.IZC_Functions::unformat_name(str_replace('Id','',$header)).'</span><span class="sorting-indicator"></span></a></th>';
							}*/
							
					//echo '<pre>';
					//print_r($this->data_fields);
					foreach($this->data_fields as $header)	
							{
							if(is_array($header))
								$header = $header['grouplabel'];
							
							if(strstr($header,'call_user_func'))
								{
								$header = explode('__',$header);
								$header = $header[2];
								}
														
								$output .= '<th valign="bottom" class="manage-column sortable column-'.IZC_Functions::format_name($header).'"><a class=""><span class="sortable-column" data-col-name="'.IZC_Functions::format_name($header).'" data-col-order="asc">'.IZC_Functions::unformat_name(str_replace('Id','',str_ireplace('wam','',str_replace('wa_form_builder__','',$header)))).'</span></a></th>'; //<span class="sorting-indicator"></span>
							}
					//$output .= '<th class="manage-column column-cb check-column" scope="col"></th>';	
					$output .= '</tr>';
					$output .= '</thead>';
					
					$output .= '<tfoot>';
					$output .= '<tr>';
					$output .= '<th style="" class="manage-column column-cb check-column" scope="col"><input type="checkbox"></th>';
					
					foreach($this->data_fields as $header)	
							{
								if(strstr($header,'call_user_func'))
								{
								$header = explode('__',$header);
								$header = $header[2];
								}
								$output .= '<th valign="bottom" class="manage-column sortable column-'.IZC_Functions::format_name($header).'"><a class=""><span class="sortable-column" data-col-name="'.IZC_Functions::format_name($header).'" data-col-order="asc">'.IZC_Functions::unformat_name(str_replace('Id','',str_ireplace('wam','',str_replace('wa_form_builder__','',$header)))).'</span></a></th>'; //<span class="sorting-indicator"></span>
							}
					
					//$output .= '<th class="manage-column column-cb check-column" scope="col"></th>';	
					$output .= '</tr>';
					$output .= '</tfoot>';
					
					$output .= '<tbody class="list:tag" id="the-list">';
					//Populated from Ajax response
					$output .= '</tbody>';
				
				$output .= '</table>';
				
				$output .= '<div class="tablenav top">';
				$output .= '<div class="alignleft actions">';
				$output .= '<select name="action2">';
				$output .= '<option selected="selected" value="-1">Bulk Actions</option>';
				$output .= '<option value="batch-delete">Delete</option>';
				$output .= '</select>';
				$output .= '<input type="submit" value="Apply" class="button-secondary action" id="doaction" name="">';
				$output .= '</div>';
				
				$output	.= '<div class="tablenav-pages">';
				//Populated from Ajax response: build_admin_table_pagination
				$output	.= '</div>';
				
				$output .= '<br class="clear"></div>';
			
			$output .= '</form>';
		
		$output .= 	'</div>';
		
		return $output;
	}
	
	public function build_dropdown($table='',$label='',$plugin_alias='',$null_option_value='',$label_prefix='',$origen='module',$selected=''){
		
		$db = new IZC_Database();
		$db->module_table = ($table) ? $table : $this->component_table;
		$db->plugin_alias = ($plugin_alias) ? $plugin_alias : '';
		$db->origen = $origen;
		$db->prefix = $label_prefix;

		$output .= '<div class="form-field">';
		$output .= '<label for="parent_Id">'.(($label) ? (($label=='none') ? '' : $label ) : 'Parent' ).'</label>';
			$output .= '<select id="'.(($table) ? $table.'_Id' : 'parent_Id' ).'" name="'.(($table) ? $table.'_Id' : 'parent_Id' ).'">';
			$output .= (!$null_option_value) ? '<option value="0">---- Select ----</option>' : '<option value="0">'.$null_option_value.'</option>';
			$output .= $db->populate_dropdown_list($selected);
			$output .= '</select>';
		$output .= '</div>';
		return $output;
	}
	
	public function build_custom_dropdown($table='',$label='',$field_name=''){
		
		$db = new IZC_Database();
		$db->module_table = ($table) ? $table : $this->component_table;

		$output .= '<div class="form-field">';
			$output .= '<select id="'.$field_name.'" name="'.str_replace('xpsk_','',$table).'_Id">';
			$output .= $db->populate_custom_dropdown_list($table,$field_name);
			$output .= '</select>';
		$output .= '</div>';
		return $output;
	}
	
	public function build_unordered_list($table='',$label=''){
		
		$db = new IZC_Database();
		$db->module_table = ($table) ? $table : $this->component_table;
		
		$output .= '<div class="iz-unordered-list '.$table.'">';
			$output .= '<ul class="'.$db->module_alias.'">';
			$output .= $db->list_items();
			$output .= '</ul>';
		$output .= '</div>';
		return $output;
	}
	
	
	public function build_field($args){
		
		//echo '<pre>';
	//	print_r($args);
		
		$label = ($args['grouplabel']) ? IZC_Functions::format_name($args['grouplabel']) : IZC_Functions::format_name($args['name']);
		$items = $args['items'];
		$description = $args['description'];
		
		if( $args['origen']=='custom')
			$label = 'wa_form_builder__'.$label;
		
		if( $args['origen']=='plugin'){
			$plugin_alias = $label;
			$label = 'xpsk_'.$label.'_Id';
			
 		}
		
		
		global $wpdb;

		if(isset($_POST['edit_Id']))
			$row  = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix . $_POST['plugin_table'] .' WHERE Id='.$_POST['edit_Id']);
		
		if(isset($_REQUEST['Id']))
			$row  = $wpdb->get_row('SELECT * FROM '.$wpdb->prefix . $_REQUEST['table'] .' WHERE Id='.$_REQUEST['Id']);
		
		switch($args['type'])
			{
			case 'text':
				if($args['origen']=='plugin')
					{
					 $output .= $this->build_dropdown('xpsk_'.$plugin_alias,'none',$plugin_alias,$null_option_value='',$label_prefix='',$origen='plugin');
					}
				elseif($args['range']==1)
					{
					$output .= '<div class="iz-form-item range">
					<label class="range">from:</label>
					<input class="range" type="text" name="'.$label.'_rangefrom" value="'.$row->$label.'">
					</div>
					<div class="iz-form-item range">
					<label class="range">to:</label>
					
					<input class="range" type="text" name="'.$label.'_rangeto" value="'.$row->$label.'"></div>';
					}
				else
					{
					$output .= '<input type="text" name="'.$label.'" value="'.$row->$label.'"><p class="description">'.$description.'</p></div>';
					}
			break;
			case 'textgroup':
				if(is_array($items)){
					foreach($items as $item){
							
						//Create a new array containing the items, becuase we need the $items array  for the build_field function
						$item_options = $items;
						
						//reset the items array
						$items = array();
						
						//Do stuff with the passed settings
						$item_id 		 = $item['id'];
						$item_val 		 = $item['val'];
						$item_req 		 = $item['required'];
						$item_visibility = $item['visibility'];
						$item_format 	 = $item['format'];
						
						//create a JSON object to contain the validation data
						$validation = "{'required':'$item_req', 'visibility':'$item_visibility', 'format':'$item_format', 'val':'$item_val', 'id':'$item_id' }";
												
						$opt_count++;
						
						$group_label = $label;
						
						$item_label = explode('__',$item_val);
						$label2 = array_reverse($item_label);
						
						$output .= '<div class="form-field"><label>'.IZC_Functions::unformat_name($label2[0]).'</label>';
						$output .= '<input type="text" data-validation="'.$validation.'" name="'.IZC_Functions::format_name($group_label.'__'.$item_val).'" value="'.$row->$item_val.'"></div>';
						}
					}
			break;
			case 'dropdown':
				$output  .= '<select name="'.$label.'">';
				if(is_array($items)){
					$output .= '<option value="0">---- Select ----</option>';
					foreach($items as $item){						
						$output .= '<option value="'.$item.'" '.(($item==$row->$label) ? 'selected="selected"' : '' ).'>'.$item.'</option>';
						}
					}
				$output  .= '</select><p class="description">'.$description.'</p>';
			break;
			case 'check':
				if(is_array($items)){
					foreach($items as $item){
						$item_label = explode('__',$item);
						$display_label = array_reverse($item_label);
						$output .= '<input type="checkbox" name="'.$label.'__'.$item.'" '.(($row->$item=='on') ? 'checked="checked"' : '' ).'><label>'.IZC_Functions::unformat_name($display_label[0]).'</label>';
						}
					}
			break;
			case 'radio':
				if(is_array($items)){
					foreach($items as $item){
						
						$label_alias = IZC_Functions::unformat_name($item);
								
						$output .= '<input type="radio" '.(($item==$row->$label) ? 'checked="checked"' : '' ).' name="'.$label.'" value="'.$item.'"><label>'.$label_alias.'</label>';
						}
					$output .= '<p class="description">'.$description.'</p>';
					}
			break;
			case 'textarea':
				$output .= '<textarea name="'.$label.'">'.$row->$label.'</textarea><p class="description">'.$description.'</p>';	
			break;
			
			case 'file':
				$output .= '<input class="iz_default_uploader" data-file-types="'.(($args['file_types']) ? $args['file_types'] : 'all').'" type="file" name="'.$label.'">';
			break;
			
			case 'date':
				if($args['range']==1)
					{
					//FROM
					$from = $label.'_rangefrom';
					
					$output .= '<div class="form-field range">';
						$output .= '<label class="range">from:</label>';
						$output .= '<input id="date_range_from" class="range" type="text" name="'.$from.'" value="'.$row->$from.'">';
					$output .= '</div>';
					//TO
					$to = $label.'_rangeto';
					
					$output .= '<div class="form-field range">';
						$output .= '<label class="range">to:</label>';
						
						$output .= '<input id="date_range_to" class="range" type="text" name="'.$to.'" value="'.$row->$to.'">';
					$output .= '</div>';
					}
				else
					{
					$output .= '<input id="single_date_'.$label.'" class="datepicker" type="text" name="'.$label.'" value="'.$row->$label.'">';
					}
			break;
			
			case 'time':
				if($args['range']==1)
					{
					//FROM
					$output .= '<div class="iz-form-item range">';
						$output .= '<label class="range">from:</label>';
						$output .= '<input id="time_range_from" class="range" type="text" name="'.$label.'_rangefrom" value="'.$row->$label.'">';
					$output .= '</div>';
					//TO
					$output .= '<div class="iz-form-item range">';
						$output .= '<label class="range">to:</label>';
						$output .= '<input id="time_range_to" class="range" type="text" name="'.$label.'_rangeto" value="'.$row->$label.'">';
					$output .= '</div>';
					}
				else
					{
					$output .= '<div class="iz-form-item"><input type="text" name="'.$label.'" value="'.$row->$label.'"></div>';
					}
			break;
			
			
			}	
		return $output;
	}
	public function add_js($code){
		echo '<script type="text/javascript">'.$code.'</script>';	
	}
	
	public function add_script($src){
		echo '<script src="'.$src.'"></script>';	
	}
	public function add_style_sheet($src){
		echo '<link rel="stylesheet" type="text/css" href="'.$src.'" />';	
	}
	

	/** PAGES **/
	public function build_landing_page($config){
		
		$panel 		= new IZC_Panels();
		$admin		= new Plugin_Admin($config);
		$template 	= new IZC_Template();
	
		/*page menu */	
		
		 
		$template ->component_table =  $config->plugin_prefix.$config->plugin_alias;
		$template ->plugin_alias =  $config->plugin_alias;
		$template -> build_header( $config->plugin_name ,$config->sub_heading, $config->sub_sub_menu);
		$template -> build_body( '<div id="filter_plugin_alias" style="display:none;">'.$config->plugin_alias.'</div><div id="col-container"><div id="col-right">'.$admin->list_data().'</div>'.$admin->add_new().'</div>' );
		$template -> build_footer('');	
		$template -> print_template();
	}
	
	public function build_module_page($config){
		
		
		$modules 	= new IZC_Modules($config);
		$template 	= new IZC_Template();
		
		//$downloader = new WP_Upgrader();
		$module_downloader = new IZC_Plugin_Modules_Upgrader();
		$module_downloader->plugin_alias = $config->plugin_alias;
		//$module_downloader->plugin_alias = IZC_Functions::format_name($config->plugin_name);
		//$module_downloader->install('http://intisul.co.za/test/categories.zip');
		
		/*$get_available_modules = $module_downloader->get_available_modules();

		foreach($get_available_modules as $set_available_module)
			$get_available_module .= '<div class="available_module"><a href="">'.$set_available_module.'</a></div>';

		echo $get_available_module;*/
		$modules_menu = 
		'<li class="all">		<a '.(($_REQUEST['module_status']=='all' || !array_key_exists('module_status',$_REQUEST)) ? 'class="current"' : '').' 	href="?page='.$_REQUEST['page'].'&module_status=all" >All <span class="count">('.$modules->count_plugin_modules.')</span></a> |</li>
		 <li class="active">	<a '.(($_REQUEST['module_status']=='active') ? 'class="current"' : '').' href="?page='.$_REQUEST['page'].'&module_status=active"	 >Active <span class="count">('.$modules->count_active_modules.')</span></a> |</li>
		 <li class="inactive">	'.(($modules->count_inactive_modules>0) ? '<a '.(($_REQUEST['module_status']=='inactive') ? 'class="current"' : '').' href="?page='.$_REQUEST['page'].'&module_status=inactive">Inactive <span class="count">('.$modules->count_inactive_modules.')</span></a>' : '&nbsp;Inactive <span class="count">('.$modules->count_inactive_modules.')</span>').'</li>
		 ';
		$template -> build_header( $config->plugin_name ,'Modules',$template->build_menu($modules_menu));
		$template -> build_body($modules->print_modules());
		$template -> build_footer('');	
		$template -> print_template();
	}
	
	
}






?>