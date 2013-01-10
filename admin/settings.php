<?php
global $wpdb;

// *************************************************************************************************** //
// Top Client

    echo "<div class=\"wrap\">\n";
    echo "  <div id=\"rb-overview-icon\" class=\"icon32\"></div>\n";
    echo "  <h2>". __("Settings", rb_login_TEXTDOMAIN) . "</h2>\n";
    echo "  <strong>\n";
    echo "  	<a class=\"button-primary\" href=\"?page=". $_GET["page"] ."&ConfigID=0\">". __("Overview", rb_login_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=1\">". __("Features", rb_login_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=2\">". __("Interest Types", rb_login_TEXTDOMAIN) . "</a> | \n";
    echo "  	<a class=\"button-secondary\" href=\"?page=". $_GET["page"] ."&ConfigID=99\">". __("Uninstall", rb_login_TEXTDOMAIN) . "</a>\n";
    echo "  </strong>\n";
  
if( isset($_REQUEST['action']) && !empty($_REQUEST['action']) ) {
	if($_REQUEST['action'] == 'douninstall') {
		rb_login_uninstall();
	}
}

if(!isset($_REQUEST['ConfigID']) && empty($_REQUEST['ConfigID'])){ $ConfigID=0;} else { $ConfigID=$_REQUEST['ConfigID']; }

if ($ConfigID == 0) {
	
// *************************************************************************************************** //
// Overview Page

    echo "	  <h3>Overview</h3>\n";
    echo "      <ul>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=0\">". __("Overview", rb_login_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=1\">". __("Features", rb_login_TEXTDOMAIN) . "</a></li>\n";
    echo "  	  <li><a href=\"?page=". $_GET["page"] ."&ConfigID=99\">". __("Uninstall", rb_login_TEXTDOMAIN) . "</a></li>\n";
    echo "      </ul>\n";

}
elseif ($ConfigID == 1) {

// *************************************************************************************************** //
// Manage Settings

    echo "<h3>". __("Settings", rb_login_TEXTDOMAIN) . "</h3>\n";

		echo "<form method=\"post\" action=\"options.php\">\n";
		settings_fields( 'rb-login-settings-group' ); 
		$rb_login_options_arr = get_option('rb_login_options');
		   // Facebook Connect integration
		   	 $rb_login_option_fb_registerallow = $rb_login_options_arr['rb_login_option_fb_registerallow'];
	       	if (empty($rb_login_option_fb_registerallow)) { $rb_login_option_fb_registerallow = "1"; }
		
				 
		 echo "<table class=\"form-table\">\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Database Version', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"\" value=\"". rb_login_VERSION ."\" disabled /></td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Display', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"rb_login_options[rb_login_option_profilemanage_sidebar]\" value=\"1\" ".checked((int)$rb_login_options_arr['rb_login_option_profilemanage_sidebar'], 1,false)."/> ". __("Show Sidebar on Member Management/Login Pages", rb_login_TEXTDOMAIN) ."<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">&nbsp;</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"rb_login_options[rb_login_option_profilemanage_toolbar]\" value=\"1\" ".checked((int)$rb_login_options_arr['rb_login_option_profilemanage_toolbar'], 1,false)."/> ". __("Hide Toolbar on All Pages", rb_login_TEXTDOMAIN) ."<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Registration Process', rb_login_TEXTDOMAIN); echo "</h3></th>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Show User Registration when creating Users', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <select name=\"rb_login_options[rb_login_option_useraccountcreation]\">\n";
		 echo "       <option value=\"0\" ". selected((int)$rb_login_options_arr['rb_login_option_useraccountcreation'], 0,false) ."> ". __("Yes, show username and password fields", rb_login_TEXTDOMAIN) ."</option>\n";
		 echo "       <option value=\"1\" ". selected((int)$rb_login_options_arr['rb_login_option_useraccountcreation'], 1,false) ."> ". __("No, do not show username and password fields", rb_login_TEXTDOMAIN) ."</option>\n";
		 echo "     </select>\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('New User Registration', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"rb_login_options[rb_login_option_registerallow]\" value=\"1\" ".checked((int)$rb_login_options_arr['rb_login_option_registerallow'], 1,false)."/> Users may register profiles (uncheck to prevent self registration)<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Enable registration of Agent/Producer', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"rb_login_options[rb_login_option_registerallowAgentProducer]\" value=\"1\" ".checked((int)$rb_login_options_arr['rb_login_option_registerallowAgentProducer'], 1,false)."/> Show registration form (uncheck to hide registration form)<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Email Confirmation', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <select name=\"rb_login_options[rb_login_option_registerconfirm]\">\n";
		 echo "       <option value=\"0\" ". selected((int)$rb_login_options_arr['rb_login_option_registerconfirm'], 0,false) ."> ". __("Password Sent Via Email", rb_login_TEXTDOMAIN) ."</option>\n";
		 echo "       <option value=\"1\" ". selected((int)$rb_login_options_arr['rb_login_option_registerconfirm'], 1,false) ."> ". __("No Email Verification, Password Self Generated", rb_login_TEXTDOMAIN) ."</option>\n";
		 echo "     </select>\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('New User Approval', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <select name=\"rb_login_options[rb_login_option_useraccountcreation]\">\n";
		 echo "       <option value=\"0\" ". selected((int)$rb_login_options_arr['rb_login_option_registerapproval'], 0,false) ."> ". __("New profiles must be manually approved", rb_login_TEXTDOMAIN) ."</option>\n";
		 echo "       <option value=\"1\" ". selected((int)$rb_login_options_arr['rb_login_option_registerapproval'], 1,false) ."> ". __("New profiles are automatically approved", rb_login_TEXTDOMAIN) ."</option>\n";
		 echo "     </select>\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Facebook Login/Registration Integration', rb_login_TEXTDOMAIN); echo "</h3></th>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Login &amp Registration', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"rb_login_options[rb_login_option_fb_registerallow]\" value=\"1\" "; checked((int)$rb_login_options_arr['rb_login_option_fb_registerallow'], 1,false); echo "/> Users may login/register profiles using facebook (uncheck to disable feature)<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Application ID', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"rb_login_options[rb_login_option_fb_app_id]\" value=\"".$rb_login_options_arr['rb_login_option_fb_app_id']."\" />";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Application Secret', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"rb_login_options[rb_login_option_fb_app_secret]\" value=\"".$rb_login_options_arr['rb_login_option_fb_app_secret']."\" />";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Login Redirect URI', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"rb_login_options[rb_login_option_fb_app_login_uri]\" value=\"".$rb_login_options_arr['rb_login_option_fb_app_login_uri']."\" />(default: ".network_site_url("/")."login/ )";
		 echo "   </td>\n";
		 echo " </tr>\n";
		  echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Registration Redirect URI', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
             echo "    <input  name=\"rb_login_options[rb_login_option_fb_app_register_uri]\" value=\"".$rb_login_options_arr['rb_login_option_fb_app_register_uri']."\" />(default: ".network_site_url("/")."register/ )";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\" colspan=\"2\"><h3>". __('Membership Subscription', rb_login_TEXTDOMAIN); echo "</h3></th>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Notifications', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td>\n";
		 echo "     <input type=\"checkbox\" name=\"rb_login_options[rb_login_option_subscribeupsell]\" value=\"1\" "; checked((int)$rb_login_options_arr['rb_login_option_subscribeupsell'], 1,false); echo "/> Display Upsell Messages for Subscription)<br />\n";
		 echo "   </td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Embed Overview Page ID', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"rb_login_options[rb_login_option_overviewpagedetails]\" value=\"". $rb_login_options_arr['rb_login_option_overviewpagedetails'] ."\" /></td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('Embed Registration Page ID', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"rb_login_options[rb_login_option_subscribepagedetails]\" value=\"". $rb_login_options_arr['rb_login_option_subscribepagedetails'] ."\" /></td>\n";
		 echo " </tr>\n";
		 echo " <tr valign=\"top\">\n";
		 echo "   <th scope=\"row\">". __('PayPal Email Address', rb_login_TEXTDOMAIN) ."</th>\n";
		 echo "   <td><input name=\"rb_login_options[rb_login_option_subscribepaypalemail]\" value=\"". $rb_login_options_arr['rb_login_option_subscribepaypalemail'] ."\" /></td>\n";
		 echo " </tr>\n";
		 echo "</table>\n";
		 echo "<input type=\"submit\" class=\"button-primary\" value=\"". __('Save Changes') ."\" />\n";
		 
		 echo "</form>\n";

}	 // End	
elseif ($ConfigID == 99) {
	
	echo "    <h3>". __("Uninstall", rb_login_TEXTDOMAIN) ."</h3>\n";
	echo "    <div>". __("Are you sure you want to uninstall?", rb_login_TEXTDOMAIN) ."</div>\n";
	echo "	<div><a href=\"?page=". $_GET["page"] ."&action=douninstall\">". __("Yes! Uninstall", rb_login_TEXTDOMAIN) ."</a></div>\n";

}	 // End	
echo "</div>\n";
?>