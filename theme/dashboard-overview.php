<?php
/*
Template Name: Member Details
 * @name		Member Details
 * @type		PHP page
 * @desc		Member Details
*/

session_start();
header("Cache-control: private"); //IE 6 Fix
global $wpdb;

/* Get User Info ******************************************/ 
global $current_user;
get_currentuserinfo();

// Get Settings
$rb_login_options_arr = get_option('rb_login_options');
	$rb_login_option_registerallow = (int)$rb_login_options_arr['rb_login_option_registerallow'];
	$rb_login_option_overviewpagedetails = (int)$rb_login_options_arr['rb_login_option_overviewpagedetails'];

// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->id, "rb_login_interact_profiletype", true);
	if ($profiletype == 1) { $profiletypetext = __("User", rb_login_TEXTDOMAIN); } else { $profiletypetext = __("Admin", rb_login_TEXTDOMAIN); }

// Change Title
add_filter('wp_title', 'rb_loginive_override_title', 10, 2);
	function rb_loginive_override_title(){
		return "Dashboard";
	}

/* Display Page ******************************************/ 
get_header();
	
	echo "<div id=\"container\" class=\"one-column rb-login rb-login-overview\">\n";
	echo "  <div id=\"content\">\n";
		// get profile Custom fields value
		$rb_login_new_registeredUser = get_user_meta($current_user->id,'rb_login_new_registeredUser',true);
	
		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) { 
			
			/* Check if the user is regsitered *****************************************/ 
			$sql = "SELECT UserID FROM ". table_login_user ." WHERE UserWordPressID =  ". $current_user->ID ."";
			$results = mysql_query($sql);
			$count = mysql_num_rows($results);
			if ($count > 0) {
			// Record Exists

				$data = mysql_fetch_array($results);  // is there record?
				
				// Menu
				include("include-menu.php"); 	
				echo " <div class=\"dashboard-inner inner\">\n";

				echo "	 <h1>". __("Welcome Back", rb_login_TEXTDOMAIN) ." ". $current_user->first_name ."!</h1>";



				
				echo " </div>\n";

			} else {
			// No Record Exists, register them
					
				echo "<h1>". __("Welcome", rb_login_TEXTDOMAIN) ." ". $current_user->first_name ."!</h1>";
				  if ($rb_login_option_registerallow == 1) {
					// Users CAN register themselves
					echo "". __("We have you registered as", rb_login_TEXTDOMAIN) ." <strong>". $profiletypetext ."</strong>";
					echo "<h2>". __("Setup Your Account", rb_login_TEXTDOMAIN) ."</h2>";
					
					// Register User
					include("manageform-register.php"); 	
					
				  } else {
					// Cant register
					echo "<strong>". __("Self registration is not permitted.", rb_login_TEXTDOMAIN) ."</strong>";
				  }
			}
			
		} else {
			// Show Login Form
			include("include-login.php"); 	
		}
		
	echo "  </div><!-- #content -->\n";
	echo "</div><!-- #container -->\n";
	
// Get Sidebar 
$rb_login_options_arr = get_option('rb_login_options');
	$rb_login_option_profilemanage_sidebar = $rb_login_options_arr['rb_login_option_profilemanage_sidebar'];
	$LayoutType = "";
	if ($rb_login_option_profilemanage_sidebar) {
		echo "	<div id=\"sidebar\" class=\"manage\">\n";
			$LayoutType = "profile";
			get_sidebar(); 
		echo "	</div>\n";
	}
// Get Footer
get_footer();
?>
