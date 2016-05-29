<head>
	<?php
		print "<title>Job Manage</title>\n";
		print "<meta http-equiv=\"Content-Language\" content=\"ja\">\n";
		print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
#		print "<meta http-equiv=\"Refresh\" content=\"300\">\n";
		print "<meta http-equiv=\"Refresh\" content=\"3;URL=showjoblist3.php\">\n";
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

		if($_POST["job_description_input"] != "") {
			$job_description = $_POST["job_description_input"];
		} else {
			if($_POST["job_description_select"] != "") {
				$job_description = $_POST["job_description_select"];
			}
		}

		if(isset($_POST["job_id"])){
			print "<p>編集しました</p>";

			print "<table border=1>\n";
			print "<tr>";
				print "<th>job_id</th>";
				print "<th>日時</th>";
				print "<th>user_id</th>";
				print "<th>内容(変更前)</th>";
				print "<th>内容(変更後)</th>";
			print "</tr>\n";

			foreach ($_POST["job_id"] as $key => $value) {
				if( $value === "on" ) {
					print "<tr>";

					$query = "select job_id, job_datetime, user_id, job_description from JobData where job_id = $1";
					$result = pg_query_params( $db, $query, array( $key	) );

					$row = pg_fetch_row($result, 0);

					print "<td>" . $row[0] . "</td>";
					print "<td>" . $row[1] . "</td>";
					print "<td>" . $row[2] . "</td>";
					print "<td>" . $row[3] . "</td>";
					print "<td>" . $job_description . "</td>";

					$query = "update jobdata set job_description = $1 where job_id = $2";
					$result = pg_query_params( $db, $query, array( $job_description , $key) );

					print "</tr>";
				}
			}
			print "</table>\n";

#		} else {
#			print "<p>選択がありません</p>";
		}
#		phpinfo();
	?>
</body>
