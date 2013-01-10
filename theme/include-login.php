<?php
	/* Load registration file. */
	require_once( ABSPATH . WPINC . '/registration.php' );
	
	/* Check if users can register. */
	$registration = get_option( 'rb_login_options' );
	$rb_login_option_registerallow = $registration["rb_login_option_registerallow"];
	// Facebook Login Integration
	$rb_login_option_fb_registerallow = $registration['rb_login_option_fb_registerallow'];
	$rb_login_option_fb_app_id = $registration['rb_login_option_fb_app_id'];
	$rb_login_option_fb_app_secret = $registration['rb_login_option_fb_app_secret'];
	$rb_login_option_fb_app_uri = $registration['rb_login_option_fb_app_uri'];
      $rb_login_option_registerallowAgentProducer = $registration['rb_login_option_registerallowAgentProducer'];
	if (( current_user_can("create_users") || $rb_login_option_registerallow )) {
		$widthClass = "half";
	} else {
		$widthClass = "full";
	}

echo "     <div id=\"interact\">\n";

			if ( $error ) {
			echo "<p class=\"error\">". $error ."</p>\n";
			}
			
echo "        <div id=\"dashboard-sign-in\" class=\"fl ". $widthClass ."\">\n";
echo "          <h1>". __("Members Sign in", rb_login_TEXTDOMAIN). "</h1>\n";
echo "          <form name=\"loginform\" id=\"login\" action=\"". network_site_url("/"). "login/\" method=\"post\">\n";
echo "            <div class=\"box\">\n";
echo "              <label for=\"user-name\">". __("Username", rb_login_TEXTDOMAIN). "</label><input type=\"text\" name=\"user-name\" value=\"". wp_specialchars( $_POST['user-name'], 1 ) ."\" id=\"user-name\" />\n";
echo "            </div>\n";
echo "            <div class=\"box\">\n";
echo "              <label for=\"password\">". __("Password", rb_login_TEXTDOMAIN). "</label><input type=\"password\" name=\"password\" value=\"\" id=\"password\" /> <a href=\"". get_bloginfo('wpurl') ."/wp-login.php?action=lostpassword\">". __("forgot password", rb_login_TEXTDOMAIN). "?</a>\n";
echo "            </div>\n";
echo "            <div class=\"box\">\n";
echo "              <input type=\"checkbox\" name=\"redashboard-me\" value=\"forever\" /> ". __("Keep me signed in", rb_login_TEXTDOMAIN). "\n";
echo "            </div>\n";
echo "            <div class=\"submit-box\">\n";
echo "              <input type=\"hidden\" name=\"action\" value=\"log-in\" />\n";
echo "              <input type=\"submit\" value=\"". __("Sign In", rb_login_TEXTDOMAIN). "\" /><br />\n";
		if($rb_login_option_fb_registerallow == 1){
				echo " <div class=\"fb-login-button\" scope=\"email\" data-show-faces=\"false\" data-width=\"200\" data-max-rows=\"1\"></div>";
						echo "  <div id=\"fb-root\"></div>
						
							<script>
							window.fbAsyncInit = function() {
							    FB.init({
								appId      : '".$rb_login_option_fb_app_id."',  ";
						  if(empty($rb_login_option_fb_app_uri)){  // set default
							   echo "\n channelUrl : '".network_site_url("/")."dashbaord/', \n";
						   }else{
							  echo "channelUrl : '".$rb_login_option_fb_app_uri."',\n"; 
						   }
						 echo "	status     : true, // check login status
								cookie     : true, // enable cookies to allow the server to access the session
								xfbml      : true  // parse XFBML
							    });
							  };
					  		// Load the SDK Asynchronously
							(function(d, s, id) {
							  var js, fjs = d.getElementsByTagName(s)[0];
							  if (d.getElementById(id)) return;
							  js = d.createElement(s); js.id = id;
							  js.src = '//connect.facebook.net/en_US/all.js#xfbml=1&appId=".$rb_login_option_fb_app_id."'
							  fjs.parentNode.insertBefore(js, fjs);
							}(document, 'script', 'facebook-jssdk'));</script>";
		}
echo "            </div>\n";
echo "          </form>\n";
echo "        </div> <!-- dashboard-sign-in -->\n";

			if (( current_user_can("create_users") || $rb_login_option_registerallow == 1)) {

echo "        <div id=\"not-a-member\" class=\"fr\">\n";
echo "          <div id=\"talent-register\">\n";
echo "            <h1>". __("Not a member", rb_login_TEXTDOMAIN). "?</h1>\n";
echo "            <h2>". __("Talent", rb_login_TEXTDOMAIN). " - ". __("Register here", rb_login_TEXTDOMAIN). "</h2>\n";
echo "            <ul>\n";
echo "              <li>". __("Create your free profile page", rb_login_TEXTDOMAIN). "</li>\n";
echo "              <li>". __("Apply to Auditions & Jobs", rb_login_TEXTDOMAIN). "</li>\n";
echo "            </ul>\n";
echo "            <a href=\"". get_bloginfo("wpurl") ."/register/user/\" id=\"register-talent\">". __("Register as User", rb_login_TEXTDOMAIN). "</a>\n";
echo "          </div> <!-- talent-register -->\n";
echo "          <div class=\"clear line\"></div>\n";
echo "        </div> <!-- not-a-member -->\n";
			}
			
echo "      <div class=\"clear line\"></div>\n";
echo "      </div>\n";
?>