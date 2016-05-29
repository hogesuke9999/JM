<?php
	print "<body>\n";
		print "<h1>Job Manage/認証画面</h1>\n";

		print "<form action=\"index.php\" method=\"post\">\n";

		print "<table>\n";
		print "<tr>\n";
		print "<td>" . "User Name" . "</td>\n";
		print "<td>";
		print "<input type=\"text\" name=\"user_name\" size=\"16\">";
		print "</td>\n";
		print "</tr>\n";
		print "<tr>\n";
		print "<td>" . "Password" . "</td>\n";
		print "<td>";
		print "<input type=\"text\" name=\"user_pass\" size=\"16\">";
		print "</td>\n";
		print "</tr>\n";
		print "</table>\n";

		print "<input type=\"submit\" name=\"auth_button\" value=\"ログイン\">\n";
		print "</form>\n";
	print "</body>\n";
?>
