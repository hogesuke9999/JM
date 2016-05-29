<html>

<head>
	<?php
		print "<title>Job Manage</title>\n";
		print "<meta http-equiv=\"Content-Language\" content=\"ja\">\n";
		print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
#		print "<meta http-equiv=\"Refresh\" content=\"300\">\n";
	?>
</head>
<body>
    <h1>Job Manager/Jobの一括編集</h1>

	<?php
		# データベース接続用設定
		$DB_HOST = "127.0.0.1";
		$DB_PORT = "5432";
		$DB_NAME = "jm";
		$DB_USER = "jm";
		$DB_PASS = "jm#pass";

		# データベースへの接続
		$db = pg_connect("host=" . $DB_HOST. " port=" . $DB_PORT . " dbname=" . $DB_NAME . " user=" . $DB_USER . " password=" . $DB_PASS)
		 or die("DBの接続に失敗しました<br>");

		# セッションの初期化
	 	session_start();

		if( $_POST["BTN"] === "一括編集" ) {
			if(isset($_POST["job_id"])){
#				print "<p>選択があります</p>";

				print "<form method=\"POST\" action=\"modify_multi_record.php\">\n";

				print "仕事:";
				$query = "select job_description, max(job_datetime) from jobdata where user_id = $1 group by job_description order by max desc limit 100";
				$result = pg_query_params( $db, $query, array( $_SESSION['user_id'] ) );

				if (!$result) {
					die('クエリーが失敗しました。');
				} else {
					if( pg_num_rows($result) > 0) {
						print "<select name=\"job_description_select\">" ;
						for ($i = 0; $i < pg_num_rows($result); $i++) {
							$row = pg_fetch_row($result, $i);
						  	print "<option value=\"" . $row[0] . "\">" . $row[0] . "</option>" ;
						}
						print "</select>\n";
					} else {
						print "-" ;
					}
				}

				print "<br>\n";

				print "その他:\n";
				print "<input name=\"job_description_input\" type=\"text\" size=\"60\">\n";
				print "<br>\n";

				print "<input type=\"submit\" value=\"登録\" name=\"BTN\">\n";

				print "<br>\n";
				print "<br>\n";

				print "<table border=1>\n";
				print "<tr>";
					print "<th></th>";
					print "<th>job_id</th>";
					print "<th>日時</th>";
					print "<th>user_id</th>";
					print "<th>内容</th>";
				print "</tr>\n";

				foreach ($_POST["job_id"] as $key => $value) {
					if( $value === "on" ) {
						print "<tr>";

						$query = "select job_id, job_datetime, user_id, job_description from JobData where job_id = $1";
						$result = pg_query_params( $db, $query, array( $key	) );

						$row = pg_fetch_row($result, 0);

						print "<td><input type=\"checkbox\" name=\"job_id[" . $row[0] . "]\" checked></td>";
						print "<td>" . $row[0] . "</td>";
						print "<td>" . $row[1] . "</td>";
						print "<td>" . $row[2] . "</td>";
						print "<td>" . $row[3] . "</td>";

						print "</tr>";
					}
				}
				print "</table>\n";
				print "</form>\n";
#			} else {
#				print "<p>選択がありません</p>";
			}
#			phpinfo();
		}
	?>
</body>
</html>
