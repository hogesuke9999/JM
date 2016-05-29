<html>
<head>
	<title>Job Manage</title>
	<meta http-equiv="Content-Language" content="ja">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="Refresh" content="120;URL=setjobdata.php">
	</head>
</head>

<body>

<h1>Job Manage/Job Dataの入力</h1>

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

	print "<form method=\"POST\" action=\"setjobdata.php\">\n";

	$date_year    = date( "Y", time() );
	$date_mon     = date( "m", time() );
	$date_day     = date( "d", time() );

	$date_hour    = date( "H", time() );
	$date_mimutes = date( "i", time() );

	print("<input name=\"date_year\"    type=\"text\" size=\"4\" value=\"${date_year}\">年");
	print("<input name=\"date_mon\"     type=\"text\" size=\"2\" value=\"${date_mon}\">月");
	print("<input name=\"date_day\"     type=\"text\" size=\"2\" value=\"${date_day}\">日");
	print("<input name=\"date_hour\"    type=\"text\" size=\"2\" value=\"${date_hour}\">時");
	print("<input name=\"date_mimutes\" type=\"text\" size=\"2\" value=\"${date_mimutes}\">分");
	print("<br>");

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

	print "</form>\n";
?>

</body>
</html>
