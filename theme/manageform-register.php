<?php
     
	echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". get_bloginfo("wpurl") ."/dashboard/account/\" style=\"width: 400px;\">\n";
	echo "<input type=\"hidden\" id=\"UserEmail\" name=\"UserEmail\" value=\"". $current_user->user_email ."\" />\n";
	echo "<input type=\"hidden\" id=\"UserWordPressID\" name=\"UserWordPressID\" value=\"". $current_user->id ."\" />\n";
	
	echo " <table class=\"form-table\">\n";
	echo "  <tbody>\n";
	echo "    <tr>\n";
	echo "		<td scope=\"row\" colspan=\"2\"><h3>". __("Contact Information", rb_login_TEXTDOMAIN) ."</h3></th>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("First Name", rb_login_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"UserContactNameFirst\" name=\"UserNameFirst\" value=\"". $current_user->first_name ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Last Name", rb_login_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"text\" id=\"UserNameLast\" name=\"UserNameLast\" value=\"". $current_user->last_name ."\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	
	$rb_login_options_arr = get_option('rb_login_options');
	$rb_login_option_registerallow = (int)$rb_login_options_arr['rb_login_option_registerallow'];
	
	if ($rb_login_option_registerallow  == 1) {
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\">". __("Username(cannot be changed.)", rb_login_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
	  if (isset($current_user->user_login)) {
		echo "			<input type=\"text\" id=\"UserName\"  disabled=\"disabled\" value=\"".$current_user->user_login."\" />\n";
		echo "                  <input type=\"hidden\" name=\"UserName\" value=\"".$current_user->user_login."\"  />";
	  } else {
		echo "			<input type=\"text\" id=\"UserName\"  name=\"UserName\" value=\"\" />\n";	
	  }
		echo "		</td>\n";
		echo "	  </tr>\n";
	 }
	
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Password (Leave blank to keep same password)", rb_login_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"password\" id=\"UserPassword\" name=\"UserPassword\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "    <tr valign=\"top\">\n";
	echo "		<td scope=\"row\">". __("Password (Retype to Confirm)", rb_login_TEXTDOMAIN) ."</th>\n";
	echo "		<td>\n";
	echo "			<input type=\"password\" id=\"UserPasswordConfirm\" name=\"UserPasswordConfirm\" />\n";
	echo "		</td>\n";
	echo "	  </tr>\n";
	echo "  </tbody>\n";
	echo "</table>\n";
	echo "<p class=\"submit\">\n";
	echo "     <input type=\"hidden\" name=\"action\" value=\"addRecord\" />\n";
	echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Save and Continue", rb_restaurant_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
	echo "</p>\n";
	echo "</form>\n";
?>