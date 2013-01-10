<?php 
/*
  Plugin Name: RB Login
  Text Domain: rb-login
  Plugin URI: http://rbplugin.com/
  Description: A WordPress Login Framework
  Author: Rob Bertholf
  Author URI: http://rob.bertholf.com/
  Version: 0.1
*/
$rb_login_VERSION = "0.1"; 

if (!session_id())
session_start();

if ( ! isset($GLOBALS['wp_version']) || version_compare($GLOBALS['wp_version'], '2.8', '<') ) { // if less than 2.8 
	echo "<div class=\"error\"><p>". __("This plugin requires WordPress version 2.8 or newer", rb_login_TEXTDOMAIN) .".</p></div>\n";
	return;
}
// *************************************************************************************************** //

// Avoid direct calls to this file, because now WP core and framework has been used
	if ( !function_exists('add_action') ) {
		header('Status: 403 Forbidden');
		header('HTTP/1.1 403 Forbidden');
		exit();
	}
	
	
// Plugin Definitions
	define("rb_login_VERSION", $rb_login_VERSION); // e.g. 1.0
	define("rb_login_BASENAME", plugin_basename(__FILE__) );  // rb-login/rb-login.php
	$rb_login_WPURL = get_bloginfo("wpurl"); // http://domain.com/wordpress
	$rb_login_WPUPLOADARRAY = wp_upload_dir(); // Array  $rb_login_WPUPLOADARRAY['baseurl'] $rb_login_WPUPLOADARRAY['basedir']
	define("rb_login_BASEDIR", get_bloginfo("wpurl") ."/". PLUGINDIR ."/". dirname( plugin_basename(__FILE__) ) ."/" );  // http://domain.com/wordpress/wp-content/plugins/rb-login/
	define("rb_login_UPLOADDIR", $rb_login_WPUPLOADARRAY['baseurl'] ."/media/" );  // http://domain.com/wordpress/wp-content/uploads/media/
	define("rb_login_UPLOADPATH", $rb_login_WPUPLOADARRAY['basedir'] ."/media/" ); // /home/content/99/6048999/html/domain.com/wordpress/wp-content/uploads/media/
	define("rb_login_TEXTDOMAIN", basename(dirname( __FILE__ )) ); //   rb-login

// Call Language Options
	add_action('init', 'rb_login_loadtranslation');
		function rb_login_loadtranslation(){
			load_plugin_textdomain( rb_login_TEXTDOMAIN, false, basename( dirname( __FILE__ ) ) . '/translation/' ); 
		}
	
// *************************************************************************************************** //

// Set Table Names
	if (!defined("table_login_user"))
		define("table_login_user", "rb_login_user");

// Call default functions
	include_once(dirname(__FILE__).'/functions.php');


// Does it need a diaper change?
	include_once(dirname(__FILE__).'/upgrade.php');


// *************************************************************************************************** //
// Creating tables on plugin activation

	function rb_login_install() {
		// Required for all WordPress database manipulations
		global $wpdb, $rb_login_options_arr;
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		
		// Update the options in the database
		update_option("rb_login_options", $rb_login_options_arr);
		// Hold the version in a seprate opgion
		add_option("rb_login_version", $rb_login_VERSION);

		// Subscriptions
		$sql = "CREATE TABLE ". table_login_user ." (
			`UserID` bigint(20) NOT NULL auto_increment,
			`UserWordPressID` bigint(20) NOT NULL default '0',
			`UserName` varchar(255) default NULL,
			`UserNameFirst` varchar(255) default NULL,
			`UserNameLast` varchar(255) default NULL,
			`UserEmail` varchar(150) NOT NULL,
			`UserTwitterID` int(11) default NULL,
			`UserTwitterHandle` varchar(100) NOT NULL default '0',
			`UserTwitterOAuthToken` varchar(200) NOT NULL,
			`UserTwitterOAuthSecret` varchar(200) NOT NULL,
			`UserTwitterUpdated` datetime default NULL,
			`UserTwitterLogged` datetime default NULL,
			`UserKloutID` bigint(20) default NULL,
			`UserFacebookUsername` varchar(100) NOT NULL,
			`UserFacebookOAuthUID` varchar(200) NOT NULL,
			`UserFacebookOAuthToken` varchar(200) NOT NULL,
			`UserImage` varchar(255) default NULL,
			`UserURL` varchar(255) default NULL,
			`UserTimeZone` varchar(100) default NULL,
			`UserDateCreated` timestamp NULL default CURRENT_TIMESTAMP,
			`UserActive` int(10) NOT NULL default '1',
			PRIMARY KEY (`UserID`)
			);";
		dbDelta($sql);
		
	}
//Activate Install Hook
register_activation_hook(__FILE__,'rb_login_install');


// *************************************************************************************************** //
// Register Administrative Settings

if ( is_admin() ){

	/****************  Add Options Page Settings Group ***************/

	add_action('admin_init', 'rb_login_register_settings');
		// Register our Array of settings
		function rb_login_register_settings() {
			register_setting('rb-login-settings-group', 'rb_login_options'); //, 'rb_login_options_validate'
		}
		
		// Validate/Sanitize Data
		function rb_login_options_validate($input) {
			// Sanitize Data
		}	
	
	add_action('admin_menu','set_rb_login_menu');
		//Create Admin Menu
		function set_rb_login_menu(){
			add_users_page( "Login Settings", "Settings", 7, "rb_login_settings", "rb_login_menu_settings");
		}
		
		//Pages
		function rb_login_menu_settings(){
			include_once('admin/settings.php');
		}
		
}

// *************************************************************************************************** //
// Add Widgets

	// Login / Actions Widget
	add_action('widgets_init', create_function('', 'return register_widget("rb_login_widget_loginactions");'));
		class rb_login_widget_loginactions extends WP_Widget {
			
			// Setup
			function rb_login_widget_loginactions() {
				$widget_ops = array('classname' => 'rb_login_widget_profileaction', 'description' => __("Displays Registration/Login and User Management Actions", rb_login_TEXTDOMAIN) );
				$this->WP_Widget('rb_login_widget_profileaction', __("Register/Login Widget", rb_login_TEXTDOMAIN), $widget_ops);
			}
		
			// What Displays
			function widget($args, $instance) {		
				extract($args, EXTR_SKIP);
				echo $before_widget;
				$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);

				$atts = array('count' => $count);
 				
				   if (!is_user_logged_in()) {
					   
						if ( !empty( $title ) ) { echo $before_title . $title . $after_title; };		
						echo "  <form name=\"loginform\" id=\"login\" action=\"". network_site_url("/") ."login/\" method=\"post\">\n";
						echo "    <div class=\"box\">\n";
						echo "      <label for=\"user-name\">". __("Username", rb_login_TEXTDOMAIN). "</label><input type=\"text\" name=\"user-name\" value=\"". wp_specialchars( $_POST['user-name'], 1 ) ."\" id=\"user-name\" />\n";
						echo "    </div>\n";
						echo "    <div class=\"box\">\n";
						echo "      <label for=\"password\">". __("Password", rb_login_TEXTDOMAIN). "</label><input type=\"password\" name=\"password\" value=\"\" id=\"password\" /> <a href=\"". get_bloginfo('wpurl') ."/wp-login.php?action=lostpassword\">". __("forgot password", rb_login_TEXTDOMAIN). "?</a>\n";
						echo "    </div>\n";
						echo "    <div class=\"box\">\n";
						echo "      <input type=\"checkbox\" name=\"redashboard-me\" value=\"forever\" /> ". __("Keep me signed in", rb_login_TEXTDOMAIN). "\n";
						echo "    </div>\n";
						echo "    <div class=\"submit-box\">\n";
						echo "      <input type=\"hidden\" name=\"action\" value=\"log-in\" />\n";
						echo "      <input type=\"submit\" value=\"". __("Sign In", rb_login_TEXTDOMAIN). "\" /><br />\n";
						echo "    </div>\n";
						echo "  </form>\n";     
						 
				   } else {
					  if(current_user_can('level_10')){
						if ( !empty( $title ) ) { echo $before_title . "Admin Logged In" . $after_title; };
						echo "	<ul>";
						echo "	  <li><a href=\"".admin_url("admin.php?page=rb_login_menu")."\">Overview</a></li>";
						echo "	  <li><a href=\"".admin_url("admin.php?page=rb_login_menu_profiles")."\">Manage Users</a></li>";
						echo "	  <li><a href=\"".admin_url("admin.php?page=rb_login_menu_search")."\">Search Users</a></li>";
						echo "	  <li><a href=\"".admin_url("admin.php?page=rb_login_menu_searchsaved")."\">Saved Searches</a></li>";
						echo "	  <li><a href=\"".admin_url("admin.php?page=rb_login_menu_reports")."\">Tools &amp; Reports</a></li>";
						echo "	  <li><a href=\"".admin_url("admin.php?page=rb_login_menu_settings")."\">Settings</a></li>";
						echo "	  <li><a href=\"/wp-login.php?action=logout&_wpnonce=3bb3c87a3d\">Logout</a></li>";	    
						echo "	</ul>";
					  } else{
						// Insert Default Actions
						if ( !empty( $title ) ) { echo $before_title . "User Logged In" . $after_title; };
						echo "You are just a user...";
					  }
				   }

				echo $after_widget;
			}
		
			// Update
			function update($new_instance, $old_instance) {				
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']);
				return $instance;
			}
		
			// Form
			function form($instance) {				
				$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
				$title = esc_attr($instance['title']);
				?>
					<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label></p>
				<?php 
			}
		
		} // class
                 

// *************************************************************************************************** //
// Add Short Codes


/****************************************************************/
//Uninstall
	function rb_login_uninstall() {
		
		register_uninstall_hook(__FILE__, 'rb_login_uninstall_action');
			function rb_login_uninstall_action() {
				//delete_option('create_my_taxonomies');
			}
	
		// Final Cleanup
		delete_option('rb_login_options');
		
		$thepluginfile = "rb-login/rb-login.php";
		$current = get_settings('active_plugins');
		array_splice($current, array_search( $thepluginfile, $current), 1 );
		update_option('active_plugins', $current);
		do_action('deactivate_' . $thepluginfile );
	
		echo "<div style=\"padding:50px;font-weight:bold;\"><p>". __("Almost done...", rb_login_TEXTDOMAIN) ."</p><h1>". __("One More Step", rb_login_TEXTDOMAIN) ."</h1><a href=\"plugins.php?deactivate=true\">". __("Please click here to complete the uninstallation process", rb_login_TEXTDOMAIN) ."</a></h1></div>";
		die;
	}
?>