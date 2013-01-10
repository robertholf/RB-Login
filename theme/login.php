<?php
// *************************************************************************************************** //
// Respond to Login Request

	if ( $_SERVER['REQUEST_METHOD'] == "POST" && !empty( $_POST['action'] ) && $_POST['action'] == 'log-in' ) {
	
		global $error;
		$login = wp_login( $_POST['user-name'], $_POST['password'] );
		$login = wp_signon( array( 'user_login' => $_POST['user-name'], 'user_password' => $_POST['password'], 'remember' => $_POST['redashboard-me'] ), false );
		
        get_currentuserinfo();
        
		if($login->ID) {
			   wp_set_current_user($login->ID);  // populate
		   get_user_login_info();
		}
	}
	
	//
	function  get_user_login_info(){

	    global $user_ID;  
		$redirect = $_POST["lastviewed"];
	    get_currentuserinfo();
		$user_info = get_userdata( $user_ID ); 

		if($user_ID){
		  // If user_registered date/time is less than 48hrs from now
		  if(!empty($redirect)){
			  header("Location: ". get_bloginfo("wpurl"). "/dashboard/".$redirect);
		  } else {
			if( $user_info->user_level > 7) {
				header("Location: ". get_bloginfo("wpurl"). "/wp-admin/");
			} 
			// Message will show for 48hrs after registration
			elseif( strtotime( $user_info->user_registered ) > ( time() - 172800 ) ) {
				header("Location: ". get_bloginfo("wpurl"). "/dashboard/");
			} else {
				header("Location: ". get_bloginfo("wpurl"). "/dashboard/");
			}
		  }
		} elseif(empty($_POST['user-name']) || empty($_POST['password']) ){
			
		} else{
			 // Reload
			header("Location: ". get_bloginfo("wpurl")."/login/");	
		}
	}



	// ****************************************************************************************** //
	// Already logged in 
	if (is_user_logged_in()) { 
	
	
		global $user_ID; 
		$login = get_userdata( $user_ID );
				 get_user_login_info();	 
			/*
			echo "    <p class=\"alert\">\n";
						printf( __('You have successfully logged in as <a href="%1$s" title="%2$s">%2$s</a>.', rb_login_TEXTDOMAIN), "/dashboard/", $login->display_name );
			echo "		 <a href=\"". wp_logout_url( get_permalink() ) ."\" title=\"". __('Log out of this account', rb_login_TEXTDOMAIN) ."\">". __('Log out &raquo;', rb_login_TEXTDOMAIN) ."</a>\n";
			echo "    </p><!-- .alert -->\n";
			*/
	
	// ****************************************************************************************** //
	// Not logged in
	} else { 

		// *************************************************************************************************** //
		// Prepare Page
		get_header();

		echo "<div class=\"content_wrapper\">\n"; // Theme Wrapper 
			echo "<div class=\"PageTitle\"><h1></h1></div>\n";	 // User Name
				echo "<div id=\"container\" class=\"one-column rb-login-account\">\n";
				echo "  <div id=\"content\">\n";
				
					// Show Login Form
					$hideregister = true;
					include("include-login.php"); 	
		
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
				
		echo "</div>\n"; //END .content_wrapper 
		
		get_footer();
	
	} // Done
?>