<html>

<head>
	<?php
		$DB_HOST = "127.0.0.1";
		$DB_PORT = "5432";
		$DB_NAME = "jm";
		$DB_USER = "jm";
		$DB_PASS = "jm#pass";

		$db = pg_connect("host=" . $DB_HOST. " port=" . $DB_PORT . " dbname=" . $DB_NAME . " user=" . $DB_USER . " password=" . $DB_PASS)
 	 	or die("DBの接続に失敗しました<br>");

 		// セッションの初期化
		session_start();
	?>
	<title>Job Manage DB Connect Test</title>
	<meta http-equiv="Content-Language" content="ja">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>

<body>
	<?php
		$query = "select user_id, user_name, user_pass from users";

		$result = pg_query( $db, $query );
#		$ret_value_array = pg_fetch_row ($result);

		if( ! $result ) {
			print "<p>エラーが発生しました</p>\n" ;
		} else {
#			var_dump($result);
			while ($row = pg_fetch_row($result)) {
				print "<p>" . $row[0] . " / " . $row[1] . " / " . $row[2] . "</p>\n" ;
			}
		}
	?>
</body>

</html>
