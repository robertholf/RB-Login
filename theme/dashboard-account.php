<?php
/*
Template Name: Edit Member Details
 * @name		Edit Member Details
 * @type		PHP page
 * @desc		Edit Member Details
*/

session_start();
header("Cache-control: private"); //IE 6 Fix
global $wpdb;

/* Get User Info ******************************************/ 
global $current_user, $wp_roles;
get_currentuserinfo();

// Get Settings
$rb_login_options_arr = get_option('rb_login_options');
	$rb_login_option_profilenaming 		= (int)$rb_login_options_arr['rb_login_option_profilenaming'];
$rb_login_options_arr = get_option('rb_login_options');
	$rb_login_option_registerallow = (int)$rb_login_options_arr['rb_login_option_registerallow'];


// Were they users or agents?
$profiletype = (int)get_user_meta($current_user->id, "rb_login_interact_profiletype", true);
if ($profiletype == 1) { $profiletypetext = __("Agent/Producer", rb_login_TEXTDOMAIN); } else { $profiletypetext = __("Model/Talent", rb_login_TEXTDOMAIN); }


	// Change Title
	add_filter('wp_title', 'rb_loginive_override_title', 10, 2);
		function rb_loginive_override_title(){
			return __("Manage User", rb_login_TEXTDOMAIN);
		}   
	
 
	/* Load the registration file. */
	require_once( ABSPATH . WPINC . '/registration.php' );
	require_once( ABSPATH . 'wp-admin/includes' . '/template.php' ); // this is only for the selected() function


// Form Post
if (isset($_POST['action'])) {

	$UserID					=$_POST['UserID'];
	$UserWordPressID		=$_POST['UserWordPressID'];
	$UserName				=$_POST['UserName'];
	$UserNameFirst			=trim($_POST['UserNameFirst']);
	$UserNameLast			=trim($_POST['UserNameLast']);
	$UserPassword			=$_POST['UserPassword'];
	$UserPasswordConfirm	=$_POST['UserPasswordConfirm'];
	$UserEmail				=$_POST['UserEmail'];
	if ($rb_login_option_registerapproval == 1) {
		// 0 Inactive | 1 Active | 2 Archived | 3 Pending Approval
		$UserActive			= 0; 
	} else {
		$UserActive			= 3; 
	}

	// Error checking
	$error = "";
	$have_error = false;
	if(trim($UserNameFirst) == ""){
		$error .= "<b><i>".__("Name is required.", rb_login_TEXTDOMAIN) ."</i></b><br>";
		$have_error = true;
	}
	
	/* Update user password. */
	if ( !empty($UserPassword) && !empty($UserPasswordConfirm) ) {
		if ( $UserPassword == $UserPasswordConfirm ) {
			wp_update_user( array( 'ID' => $current_user->id, 'user_pass' => esc_attr( $UserPassword ) ) );
		} else {
			$have_error = true;
			$error .= __("The passwords you entered do not match.  Your password was not updated.", rb_login_TEXTDOMAIN);
		}
	}
	
	// Get Post State
	$action = $_POST['action'];
	switch($action) {

	// *************************************************************************************************** //
	// Add Record
	case 'addRecord':
		if(!$have_error){
			
			$UserActive		= 3;

			// Create Record
			$insert = "INSERT INTO " . table_login_user .
			" 	(UserWordPressID,UserNameFirst,UserNameLast,UserEmail,UserActive)" .
			"	VALUES (". $UserWordPressID .",'" . $wpdb->escape($UserNameFirst) . "','" . $wpdb->escape($UserNameLast) . "','" . $wpdb->escape($UserEmail) . "',". $UserActive .")";
		    $results = $wpdb->query($insert) or die(mysql_error());
			$UserID = $wpdb->insert_id;
 			
			// delete temporary storage
			delete_user_meta($UserWordPressID, 'rb_login_new_registeredUser');
             
			/* Update WordPress user information. */
			update_user_meta( $current_user->id, 'first_name', esc_attr( $UserNameFirst ) );
			update_user_meta( $current_user->id, 'last_name', esc_attr( $UserNameLast ) );
			update_user_meta( $current_user->id, 'nickname', esc_attr( $UserNameFirst ) );
			update_user_meta( $current_user->id, 'display_name', esc_attr( $UserNameFirst ." ". $UserNameLast ) );
			update_user_meta( $current_user->id, 'user_email', esc_attr( $UserEmail ) );
			
			// Link to Wordpress user_meta
			 if ( username_exists( $UserName ) ) {
				$isLinked =  mysql_query("UPDATE ". table_login_user ." SET UserWordPressID =  ". $current_user->ID ." WHERE UserID = ".$UserID." ");
				if($isLinked){
					 wp_redirect(get_bloginfo("wpurl") . "/dashboard/");
				 } else {
				    die(mysql_error());	 
				 }
			 }else{
				$user_data = array(
				    'ID' => $current_user->id,
				    'user_pass' => wp_generate_password(),
				    'user_login' => $UserName,
				    'user_email' => $UserEmail,
				    'display_name' => $UserNameFirst ." ". $UserNameLast,
				    'first_name' => $UserNameFirst,
				    'last_name' => $UserNameLast,
				    'role' =>  get_option('default_role') // Use default role or another role, e.g. 'editor'
				);
				$user_id = wp_insert_user( $user_data );
				wp_set_password($UserPassword, $user_id);
			 }

			$alerts = "<div id=\"message\" class=\"updated\"><p>". __("New User added successfully", rb_login_TEXTDOMAIN) ."!</p></div>"; 
			/* Redirect so the page will show updated info. */
			if ( !$error ) {
				wp_redirect(get_bloginfo("wpurl") . "/dashboard/manage/");
				//exit;
			}
		} else {
        	$alerts = "<div id=\"message\" class=\"error\"><p>". __("Error creating record, please ensure you have filled out all required fields.", rb_login_TEXTDOMAIN) ."<br />". $error ."</p></div>"; 
		}
	break;
	
	// *************************************************************************************************** //
	// Edit Record
	case 'editRecord':
		if(!$have_error){
			
			// Update Record
			$update = "UPDATE " . table_login_user . " SET 
			UserNameFirst='" . $wpdb->escape($UserNameFirst) . "',
			UserNameLast='" . $wpdb->escape($UserNameLast) . "',
			UserEmail='" . $wpdb->escape($UserEmail) . "'
			WHERE UserID=$UserID";
		    $results = $wpdb->query($update);
              
			/* Update WordPress user information. */
			update_user_meta( $current_user->id, 'first_name', esc_attr( $UserNameFirst ) );
			update_user_meta( $current_user->id, 'last_name', esc_attr( $UserNameLast ) );
			update_user_meta( $current_user->id, 'nickname', esc_attr( $UserNameFirst ." ". $UserNameLast ) );
			update_user_meta( $current_user->id, 'display_name', esc_attr( $UserNameFirst ." ". $UserNameLast ) );
			update_user_meta( $current_user->id, 'user_email', esc_attr( $UserEmail ) );
		
			$alerts = "<div id=\"message\" class=\"updated\"><p>". __("User updated successfully", rb_login_TEXTDOMAIN) ."!</a></p></div>";
		} else {
			$alerts = "<div id=\"message\" class=\"error\"><p>". __("Error updating record, please ensure you have filled out all required fields.", rb_login_TEXTDOMAIN) ."<br />". $error ."</p></div>"; 
		}
		
		wp_redirect( $rb_login_WPURL ."/dashboard/" );
		//exit;
	break;
	}
}

/* Display Page ******************************************/ 
get_header();
		// get profile Custom fields value

	echo "<div id=\"container\" class=\"one-column rb-login-account\">\n";
	echo "  <div id=\"content\">\n";
	
		// ****************************************************************************************** //
		// Check if User is Logged in or not
		if (is_user_logged_in()) { 
			
			echo "<div id=\"manage\" class=\"admin account\">\n";
			$rb_login_new_registeredUser = get_user_meta($current_user->id,'rb_login_new_registeredUser');
			
			// Menu
			include("include-menu.php"); 	
			echo " <div class=\"account-inner inner\">\n";

			// Show Errors & Alerts
			echo $alerts;

			/* Check if the user is regsitered *****************************************/ 
			// Verify Record
			$sql = "SELECT UserID FROM ". table_login_user ." WHERE UserWordPressID =  ". $current_user->ID ."";
			$results = mysql_query($sql);
			$count = mysql_num_rows($results);
			if ($count > 0) {
			  while ($data = mysql_fetch_array($results)) {
			
				// Manage User
				include("manageform-account.php"); 	
						
						
			  } // is there record?
			} else {

			  if ($rb_login_option_registerallow  == 1) {
				// Users CAN register themselves
				
				// No Record Exists, register them
				echo "". __("Records show you are not currently linked to a model or agency profile.  Lets setup your profile now!", rb_login_TEXTDOMAIN) ."";
				
				// Register User
				include("manageform-register.php"); 	
				
				
			  } else {
				// Cant register
				echo "<strong>". __("Self registration is not permitted.", rb_login_TEXTDOMAIN) ."</strong>";
			  }

				
			}

			echo " </div>\n"; // .manage-inner
			echo "</div>\n"; // #manage
		} else {
			echo "<p class=\"warning\">\n";
					_e('You must be logged in to edit your profile.', 'frontendprofile');
			echo "</p><!-- .warning -->\n";
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
