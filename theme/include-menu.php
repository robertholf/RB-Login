<?php

		echo " <div class=\"manage-menu\">\n";
		echo "   <div id=\"subMenuTab\">\n";
					if ( ($_SERVER["REQUEST_URI"]) == "/dashboard/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-left tab-". $tabclass ."\">\n";
		echo " 			<a href=\"". get_bloginfo("wpurl") ."/dashboard/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("Overview", rb_login_TEXTDOMAIN) ."</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";
					if ( ($_SERVER["REQUEST_URI"]) == "/dashboard/account/") { $tabclass = "active"; } else { $tabclass = "inactive"; }
		echo " 		<div class=\"tab-right tab-". $tabclass ."\">\n";
		echo " 			<a  href=\"". get_bloginfo("wpurl") ."/dashboard/account/\">\n";
		echo " 			  <div class=\"subMenuTabBG\"><div class=\"subMenuTabBorders\"><div class=\"subMenuTabText\">".__("My Account", rb_login_TEXTDOMAIN) ."</div></div></div>\n";
		echo " 			</a>\n";
		echo " 		</div>\n";
		echo "   </div>\n";
		echo " </div>\n";
?>