<?php
// *************************************************************************************************** //
// Admin Head Section 

	add_action('admin_head', 'rb_login_admin_head');
		function rb_login_admin_head(){
		  if( is_admin() ) {
			echo "<link rel=\"stylesheet\" href=\"". rb_login_BASEDIR ."style/admin.css\" type=\"text/css\" media=\"screen\" />\n";
		  }
		}

// *************************************************************************************************** //
// Page Head Section

	add_action('wp_head', 'rb_login_inserthead');
		// Call Custom Code to put in header
		function rb_login_inserthead() {
		  if( !is_admin() ) {
			echo "<link rel=\"stylesheet\" href=\"". rb_login_BASEDIR ."style/style.css\" type=\"text/css\" media=\"screen\" />\n";
		  }
		  if(!wp_script_is('jquery')) {
			echo "<script type=\"text/javascript\" src=\"". rb_login_BASEDIR ."style/jquery.1.8.js\"></script>";
			} 
		}


// *************************************************************************************************** //
// Functions

	function rb_login_whitespace($string) {
		return preg_replace('/\s+/', ' ', $string);
	}

	function rb_login_safenames($filename) {
		$filename = rb_login_whitespace(trim($filename));
		$filename = str_replace(' ', '-', $filename);
		$filename = preg_replace('/[^a-z0-9-.]/i','',$filename);
		$filename = str_replace('--', '-', $filename);
		return strtolower($filename);
	}

	// Format a string in proper case.
	function rb_login_strtoproper($someString) {
		return ucwords(strtolower($someString));
	}

	function rb_login_random() {
		return preg_replace("/([0-9])/e","chr((\\1+112))",rand(100000,999999));
	}
	
	function rb_login_get_userrole() {
		global $current_user;
		get_currentuserinfo();
		$user_roles = $current_user->roles;
		$user_role = array_shift($user_roles);
		return $user_role;
	};
	
	function rb_login_convertdatetime($datetime) {
	  if (isset($datetime)) {
		// Convert
		list($date, $time) = explode(' ', $datetime);
		list($year, $month, $day) = explode('-', $date);
		list($hours, $minutes, $seconds) = explode(':', $time);
		
		$UnixTimestamp = mktime($hours, $minutes, $seconds, $month, $day, $year);
		return $UnixTimestamp;
	  }
	}
	
	function rb_login_makeago($timestamp, $offset){
	  if (isset($timestamp) && !empty($timestamp) && ($timestamp <> "0000-00-00 00:00:00") && ($timestamp <> "943920000")) {
		// Offset
		$timezone_offset = (int)$offset; // Server Time
		$time_altered = time() + $timezone_offset *60 *60;
	
		// Math
		$difference = $time_altered - $timestamp;
		
		//printf("\$timestamp: %d, \$difference: %d\n", $timestamp, $difference);
		$periods = array("sec", "min", "hr", "day", "week", "month", "year", "decade");
		$lengths = array("60","60","24","7","4.35","12","10");
		for($j = 0; $difference >= $lengths[$j]; $j++)
		$difference /= $lengths[$j];
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		$text = "$difference $periods[$j] ago";
			if ($j > 10) { exit; }
		return $text;
	  } else {
		return "--";
	  }
	}


// *************************************************************************************************** //
// Handle Folders

	// Adding a new rule
	add_filter('rewrite_rules_array','rb_login_rewriteRules');
		function rb_login_rewriteRules($rules) {
			$newrules = array();
			$newrules['register/(.*)$'] = 'index.php?type=doregister&typeofprofile=$matches[1]';
			$newrules['register'] = 'index.php?type=doregister';
			$newrules['login'] = 'index.php?type=dologin';
			$newrules['dashboard/(.*)$'] = 'index.php?type=$matches[1]';
			$newrules['dashboard/(.*)/(.*)$'] = 'index.php?type=$matches[0]';
			$newrules['dashboard'] = 'index.php?type=dashboard';
			return $newrules + $rules;
		}
		
	// Get Veriables & Identify View Type
	add_action( 'query_vars', 'rb_login_query_vars' );
		function rb_login_query_vars( $query_vars ) {
			$query_vars[] = 'type';
			$query_vars[] = 'typeofprofile';
			return $query_vars;
		}
	
	// Set Custom Template
	add_filter('template_include', 'rb_login_template_include', 1, 1); 
		function rb_login_template_include( $template ) {
			if ( get_query_var( 'type' ) ) {
			  if (get_query_var( 'type' ) == "dologin") {
				return dirname(__FILE__) . '/theme/login.php'; 
			  } elseif (get_query_var( 'type' ) == "doregister") {
				return dirname(__FILE__) . '/theme/register.php'; 
			  } elseif (get_query_var( 'type' ) == "dashboard") {
				return dirname(__FILE__) . '/theme/dashboard-overview.php'; 
			  } elseif (get_query_var( 'type' ) == "account") {
				return dirname(__FILE__) . '/theme/dashboard-account.php'; 
			  } elseif (get_query_var( 'type' ) == "subscription") {
				return dirname(__FILE__) . '/theme/dashboard-subscription.php'; 
			  } elseif (get_query_var( 'type' ) == "manage") {
				return dirname(__FILE__) . '/theme/dashboard-profile.php'; 
			  }
			}
			return $template;
		}
	
	// Remember to flush_rules() when adding rules
	add_filter('init','rb_login_flushrules');
		function rb_login_flushRules() {
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
		}
	
// *************************************************************************************************** //
// Handle Emails

	// Redefine user notification function  
	if ( !function_exists('wp_new_user_notification') ) {  
		function wp_new_user_notification( $user_id, $plaintext_pass = '' ) {  
			$user = new WP_User($user_id);  
	  
			$user_login = stripslashes($user->user_login);  
			$user_email = stripslashes($user->user_email);  
	  
			$message  = sprintf(__('New user registration on your blog %s:'), get_option('blogname')) . "\r\n\r\n";  
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";  
			$message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";  
	  
			@wp_mail(get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message);  
	  
			if ( empty($plaintext_pass) )  
				return;  

			$message  = __('Hi there,') . "\r\n\r\n";  
			$message .= sprintf(__("Thanks for joining %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n"; 
			$message .= get_option('home') ."/login/\r\n"; 
			$message .= sprintf(__('Username: %s'), $user_login) . "\r\n"; 
			$message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n"; 
			$message .= sprintf(__('If you have any problems, please contact us at %s.'), get_option('admin_email')) . "\r\n\r\n"; 
			$message .= __('Regards,')."\r\n";
			$message .= get_option('blogname') . __(' Team') ."\r\n"; 
			$message .= get_option('home') ."\r\n"; 
	 
	 		$headers = 'From: '. get_option('blogname') .' <'. get_option('admin_email') .'>' . "\r\n";
			wp_mail($user_email, sprintf(__('%s Registration Successful! Login Details'), get_option('blogname')), $message, $headers);  
	  
		}  
	}  

// *************************************************************************************************** //
// Functions

	// Move Login Page	
	add_filter("login_init", "rb_login_login_movepage", 10, 2);
		function rb_login_login_movepage( $url ) {
			global $action;
		
			if (empty($action) || 'login' == $action) {
				wp_safe_redirect(get_bloginfo("wpurl"). "/login/");
				die;
			}
		}
	
	// Rewrite Login
	add_action( 'init', 'rb_login_login_rewrite' );
		function rb_login_login_rewrite() {
			add_rewrite_rule(get_bloginfo("wpurl"). "register/?$", 'wp-login.php', 'top');
		}
		
	// Redirect after Login
	add_filter('login_redirect', 'rb_login_login_redirect', 10, 3);	
		function rb_login_login_redirect() {
			global $user_ID;
			if( $user_ID ) {
				$user_info = get_userdata( $user_ID ); 
				// If user_registered date/time is less than 48hrs from now
				// Message will show for 48hrs after registration
				if ( strtotime( $user_info->user_registered ) > ( time() - 172800 ) ) {
					header("Location: ". get_bloginfo("wpurl"). "/dashboard/account/");
				} elseif( current_user_can( 'manage_options' )) {
					header("Location: ". get_bloginfo("wpurl"). "/wp-admin/");
				} else {
					header("Location: ". get_bloginfo("wpurl"). "/dashboard/");
				}
			}
		}
	

// *************************************************************************************************** //
// Shortcodes





?>