<?php
	global $user_ID; 
	global $current_user;
	get_currentuserinfo();
	$UserWordPressID = $current_user->id;

	// Get Settings
	$rb_login_options_arr = get_option('rb_login_options');
		$rb_login_option_registerallow = (int)$rb_login_options_arr['rb_login_option_registerallow'];

	// Get Data
	$query = "SELECT * FROM " . table_login_user . " WHERE UserWordPressID='$UserWordPressID'";
	$results = mysql_query($query) or die ( __("Error, query failed", rb_login_TEXTDOMAIN ));
	$count = mysql_num_rows($results);
	while ($data = mysql_fetch_array($results)) {
		$UserID					=$data['UserID'];
		$UserWordPressID		=$data['UserWordPressID'];
		$UserNameFirst			=stripslashes($data['UserNameFirst']);
		$UserNameLast			=stripslashes($data['UserNameLast']);
		$UserEmail				=stripslashes($data['UserEmail']);

		echo "<form method=\"post\" enctype=\"multipart/form-data\" action=\"". get_bloginfo("wpurl") ."/dashboard/account/\">\n";
		echo "     <input type=\"hidden\" name=\"UserID\" value=\"". $UserID ."\" />\n";
		echo " <table class=\"form-table\">\n";
		echo "  <tbody>\n";
		echo "    <tr colspan=\"2\">\n";
		echo "		<td scope=\"row\"><h3>". __("Contact Information", rb_login_TEXTDOMAIN) ."</h3></th>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\">". __("First Name", rb_login_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"UserNameFirst\" name=\"UserNameFirst\" value=\"". $UserNameFirst ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\">". __("Last Name", rb_login_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"UserNameLast\" name=\"UserNameLast\" value=\"". $UserNameLast ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		echo "    <tr valign=\"top\">\n";
		echo "		<td scope=\"row\">". __("Email Address", rb_login_TEXTDOMAIN) ."</th>\n";
		echo "		<td>\n";
		echo "			<input type=\"text\" id=\"UserEmail\" name=\"UserEmail\" value=\"". $UserEmail ."\" />\n";
		echo "		</td>\n";
		echo "	  </tr>\n";
		if ($rb_login_option_registerallow  == 1) {
			echo "    <tr valign=\"top\">\n";
			echo "		<td scope=\"row\">". __("Username(cannot be changed.)", rb_login_TEXTDOMAIN) ."</th>\n";
			echo "		<td>\n";
			if(isset($current_user->user_login)){
			echo "			<input type=\"text\" id=\"UserName\"  name=\"UserName\" disabled=\"disabled\" value=\"".$current_user->user_login."\" />\n";
			}else{
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
		echo "	</tbody>\n";
		echo " </table>\n";

		echo "<p class=\"submit\">\n";
		echo "     <input type=\"hidden\" name=\"action\" value=\"editRecord\" />\n";
		echo "     <input type=\"submit\" name=\"submit\" value=\"". __("Save and Continue", rb_restaurant_TEXTDOMAIN) ."\" class=\"button-primary\" />\n";
		echo "</p>\n";
		echo "</form>\n";
	}
?>