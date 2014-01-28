<?php
/*
Plugin Name: XPSK - WordPress Plugin Starter Kit
Plugin URI: http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix
Plugin Prefix: wap_ 
Module Ready: Yes
Plugin TinyMCE: popup
Description: Build plugins in no time using this awesome framework! 
Author: Basix
Version: 1.0
Author URI: http://codecanyon.net/item/x-forms-wordpress-form-creator-plugin/5214711?ref=Basix
License: GPLv2
*/

require( dirname(__FILE__) . '/includes.php');

class Setup_Plugin{
	public function __construct($src_file, $dest_dir=false, $create_zip_name_dir=true, $overwrite=true){	
		if($zip = zip_open($src_file))
			{
			if($zip)
				{
				$splitter = ($create_zip_name_dir === true) ? "." : "/";
				if ($dest_dir === false) $dest_dir = substr($src_file, 0, strrpos($src_file, $splitter))."/";
				// Create the directories to the destination dir if they don't already exist
				$this->create_dirs($dest_dir);
				// For every file in the zip-packet
				while ($zip_entry = zip_read($zip))
					{
					// Now we're going to create the directories in the destination directories
					// If the file is not in the root dir
					$pos_last_slash = strrpos(zip_entry_name($zip_entry), "/");
					if ($pos_last_slash !== false)
						{
						// Create the directory where the zip-entry should be saved (with a "/" at the end)
						$this->create_dirs($dest_dir.substr(zip_entry_name($zip_entry), 0, $pos_last_slash+1));
						}
				
					// Open the entry
					if (zip_entry_open($zip,$zip_entry,"r"))
						{
						// The name of the file to save on the disk
						$file_name = $dest_dir.zip_entry_name($zip_entry);
						// Check if the files should be overwritten or not
						if ($overwrite === true || $overwrite === false && !is_file($file_name))
							{
							// Get the content of the zip entry
							$fstream = zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));
							@file_put_contents($file_name, $fstream );
							// Set the rights
							chmod($file_name, 0777);
							//echo "save: ".$file_name."<br />";
							}
						// Close the entry
						zip_entry_close($zip_entry);
						}      
					}
				// Close the zip-file
				zip_close($zip);
				}
			}
		else
			{
			return false;
			}
	//unlink($src_file);
	return true;
	}
	private function create_dirs($path){
	if (!is_dir($path))
		{
		$directory_path = "";
		$directories = explode("/",$path);
		array_pop($directories);
		foreach($directories as $directory)
			{
			$directory_path .= $directory."/";
			if (!is_dir($directory_path))
				{
				mkdir($directory_path);
				chmod($directory_path, 0777);
				}
			}
		}
	}	
}

function XPSK_setup_menu(){
	add_submenu_page( 'plugins.php', 'Create plugin','Create plugin', 'administrator', 'add-new-plugin','XPSKCore_main_page');
}	


class XPSKCore_Config{
	public $plugin_name;
	public $plugin_alias;
	public function __construct()
		{
		$header_info = IZC_Functions::get_file_headers(dirname(__FILE__).DIRECTORY_SEPARATOR.'xpsk.php');
		$this->plugin_name 		= $header_info['Plugin Name'];
		$this->plugin_alias		= IZC_Functions::format_name($this->plugin_name);
		}
}

function XPSKCore_main_page(){
	$config 	= new XPSKCore_Config();
	$template 	= new IZC_Template();
	$template -> build_header($config->plugin_name);
	
	$body = '
	<div class="update-nag">You are using the express version of this plugin! Get tons of extra functionality by <a href="http://codecanyon.net/item/xpsk-wordpress-plugin-starter-kit/5741077?ref=Basix">upgrading to the full version</a>. Take a minute to <a href="http://codecanyon.net/item/xpsk-wordpress-plugin-starter-kit/5741077?ref=Basix">look at these features</a>.</div>
	<div class="icon32" id="icon-plugins"><br></div><h2>Create a New Plugin</h2><div id="col-container">
  <div class="iz-ajax-response" id="ajax-response"></div>
  <div id="col-left">
    <div class="col-wrap">
        <form name="createplugin" method="post" action="plugins.php" class="" id="">
          <input type="hidden" value="xpsk_create_new_plugin" name="action">
		  
			<div id="ajax-response" class="iz-ajax-response"></div>
			<div class="form-fields">			
				<h3>Header Information</h3>
				
				<div class="form-field">
					<label for="plugin_name">Plugin Name</label><input type="text" class="" id="plugin_name" value="" name="plugin_name">
					<p class="field_description"></p>
				</div>
				
				<div class="form-field">
					<label for="plugin_description">Plugin Description</label><textarea id="plugin_description" name="plugin_description"></textarea>
					<p class="field_description"></p>
				</div>
				
				<div class="form-field">
					<label for="plugin_author">Author</label><input type="text" class="" id="plugin_author" value="" name="plugin_author">
					<p class="field_description"></p>
				</div>
				
				<div class="form-field">
					<label for="plugin_author_uri">Author URI</label><input type="text" class="" id="plugin_author_uri" value="" name="plugin_author_uri">
					<p class="field_description"></p>
				</div>
				
				<div class="form-field">
					<label for="plugin_version">Version</label><input type="text" class="" id="plugin_version" value="" name="plugin_version">
					<p class="field_description"></p>
				</div>
				
				<div class="form-field">
					<label for="plugin_uri">Plugin URI</label><input type="text" class="" id="plugin_uri" value="" name="plugin_uri">
					<p class="field_description"></p>				  
				</div>
				
				<div class="form-field">
					<label for="plugin_license">License</label><input type="text" class="" id="plugin_license" value="GPLv2" name="plugin_license">
					<p class="field_description"></p>
				</div>
				
				<div class="form-field">
					<label for="plugin_license_uri">License URI</label><input type="text" class="" id="plugin_license_uri" value="http://www.gnu.org/licenses/gpl-2.0.html" name="plugin_license_uri">
					<p class="field_description"></p>
				</div>
				
				<h3>Plugin Settings and features</h3> 
				 <p class="description">The below fields will add code to your main.php file in the root of your new plugin which will be executed on plugin activation.<br />
				 Before activating your new plugin open /plugins/your_plugin_name/main.php and follow the instructions.<br />
				<strong>NOTE:</strong> If you specify "No" on any of the below plugin features the code will be commented so you can just uncomment if your need it at a later stage</p>
				<div class="form-field">
					<label>Function Prefix</label><input type="text" class="" id="plugin_function_prefix" value="" name="plugin_function_prefix">
					<p class="description">Sets prefix to all functions to prevent function duplication. If empty the plugin name will be used formatted as lowercase whith underscrores.</p>
				</div>
				
				<div class="form-field">
					<label for="plugin_admin_menu">Add Admin Menu Item</label><br />
					<input type="radio" class="" id="plugin_admin_menu" value="yes"  checked="checked" name="plugin_admin_menu">Yes <br />
					<input type="radio" class="" id="plugin_admin_menu" value="no" name="plugin_admin_menu">No
					<p class="description">If yes, code for adding an admin menu item will be addded for this plugin. CODE: add_action(\'admin_menu\', \'your_plugin_prefix_main_menu\');</p>
				</div> 
				
				
				
			</div>
          </div>
          <div class="submit">
            <p class="submit">
              <input type="submit" name="submit" id="submit" data-action="iz-insert" class="iz-submit button-primary iz-plugin-submit" value="      Create Plugin       ">
            </p>
          </div>
        </form>
  </div>
</div>';
	
	$template -> build_body($body);
	$template -> build_footer('');	
	$template -> print_template();
}

add_action('admin_menu', 'XPSK_setup_menu');

if($_POST['action']=='xpsk_create_new_plugin'){
$setup_plugin = new Setup_Plugin(dirname(__FILE__).'/Starter.zip',ABSPATH.'/wp-content/plugins/');

$plugin_alias = IZC_Functions::format_name($_POST['plugin_name']);

$function_prefix = ($_POST['plugin_function_prefix']) ? $_POST['plugin_function_prefix'] : $plugin_alias;

$write = '<?php
/*
Plugin Name: '.$_POST['plugin_name'].'
Plugin URI: '.$_POST['plugin_uri'].'
Description: '.$_POST['plugin_description'].'
Author: '.$_POST['plugin_author'].'
Version: '.$_POST['plugin_version'].'
Author URI: '.$_POST['plugin_author_uri'].'
License: '.$_POST['plugin_license'].'
License URI: '.$_POST['plugin_license_uri'].'


/*Class and function PREFIX = '.$function_prefix.'_ */

/***************************************/
/**********  Configuration  ************/
/***************************************/
class '.$function_prefix.'_Config{
	/*************  General  ***************/
	/************  DONT EDIT  **************/
	/* Leave the below verialbles as is for future functionality that this plugin could perform from XPSK base*/

	/* The displayed name of your plugin */
	public $plugin_name;
	/* The alias of the plugin used by external entities */
	public $plugin_alias;
	/* Admin Menu */
	public $plugin_menu;
	
	/************* Admin Menu **************/
	/* Setup you admin menu, leave as is if you dont know what you are doing! */
	public function build_plugin_menu(){
	
		$plugin_alias  = $this->plugin_alias;
		$plugin_name  = $this->plugin_name;
				
		$this->plugin_menu = array
			(
			$this->plugin_name => array
				(
				/* PLUGIN main menu. Leave the menu slug as is for Javascript localization to the plugin and submenu items */
				\'menu_page\'	=>	array
					(
					//Menu item text
					\'page_title\' 	=> $this->plugin_name,
					\'menu_title\' 	=> $this->plugin_name,
					//Allocates WP role to the menu item
					\'capability\' 	=> \'administrator\',
					//This is the admin page slug. Do not remove the xpsk- prefix. It is used to localize you JS to be kept whitin the plugin. Note:Including JS publicaly can interfare with other plugins or theme functionality
					\'menu_slug\' 	=> \'xpsk-\'.$plugin_alias.\'-main\',
					//Specifies the function to be called when the menu item is opened. By default your DB view will be called by function \''.$function_prefix.'_main_page\'
					\'function\' 		=> \''.$function_prefix.'_main_page\',
					//Change your menu icon by uncommenting the below comment. Add icon (28px X 28px) in the images folder or specify path
					\'icon_url\' 		=> WP_PLUGIN_URL.\'/\'.$plugin_name.\'/images/menu_icon.png\',
					//Changes your menu item position. leave blank to add items to the end of the WP navigation (ordered alphabeticaly)
					\'position \'		=> \'\'
					),
				/* PLUGIN sub menus. Leave the parent page key as is! */
				/*\'sub_menu_page\'		=>	array
					(
					\'Manage Forms\' => array
						(
						\'parent_slug\' 	=> \'WA-\'.$plugin_alias.\'-main\',
						\'page_title\' 	=> \'Manage Forms\',
						\'menu_title\' 	=> \'Manage Forms\',
						\'capability\' 	=> \'administrator\',
						\'menu_slug\' 	=> \'xpsk-\'.$plugin_alias.\'-{ADD UNIQUE STRING HERE}\',
						\'function\' 		=> \'WAFormBuilder_view_forms_page\',
						),
					)*/
				)		
			);
		}
	
	public function __construct()
		{
		/* Dont touch this  unless you know what your doing! */
		$header_info = IZC_Functions::get_file_headers(dirname(__FILE__).DIRECTORY_SEPARATOR.\'main.php\');
		$this->plugin_name 		= $header_info[\'Plugin Name\'];
		$this->plugin_alias		= IZC_Functions::format_name($this->plugin_name);
		$this->build_plugin_menu(); 
		}
}

/***************************************/
/*************  Hooks   ****************/
/***************************************/
/* On plugin activation */
/* Build admin menu. adds the menu as specified in the config class to the admin nav structure */
'.(($_POST['plugin_widget']=='no') ? '//' : '').'add_action(\'admin_menu\', \''.$function_prefix.'_main_menu\');
register_activation_hook(__FILE__, \''.$function_prefix.'_run_instalation\' );
/***************************************/
/*********  Hook functions   ***********/
/***************************************/
/*********  ADMIN MENU   ***********/
/* Convert menu to WP Admin Menu */
function '.$function_prefix.'_main_menu(){
	$config = new '.$function_prefix.'_Config();
	IZC_Admin_menu::build_menu($config->plugin_name);
}
/*********  INSTALLATION   ***********/
function '.$function_prefix.'_run_instalation(){
	$config = new '.$function_prefix.'_Config();	
	$instalation = new IZC_Instalation();
	$instalation->component_menu 			=  $config->plugin_menu;
	$instalation->run_instalation(\'\');	
}

/***************************************/
/*********   Admin Pages   *************/
/***************************************/
/* these are the functions called from your menu itmes for example if this is your menu function. 
If you are using submenu items add there functions here
 */
function '.$function_prefix.'_main_page(){
	
}
/***************************************/
/*********   User Interface   **********/
/***************************************/
/************* OUTPUT FROM SHORTCODE **************/
function '.$function_prefix.'_ui_output($atts){
	
}

//This is to prevent XPSK to be deactivated while this plugin is active!! DO NOT attempt to deactivate XPSK while this plugin is active.
wp_enqueue_style(\'xpsk-block-deactivate\',  WP_PLUGIN_URL . \'/xpsk/css/block_deactivation.css\');
?>';
	$write_header = fopen(ABSPATH.'wp-content/plugins/Starter/main.php','w');
	fwrite($write_header,$write);	
	fclose($write_header);
	sleep(1);
	rename(ABSPATH.'wp-content/plugins/Starter/',ABSPATH.'wp-content/plugins/'.$_POST['plugin_name'].'/');
}	
?>