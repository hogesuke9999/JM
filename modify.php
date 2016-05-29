<html>
<head>
<title>Job Manager</title>
<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=euc-jp">
<meta http-equiv="Refresh" content="120;URL=setjobdata.php">

<script type="text/javascript">
<!--
	function window_close(){
		window.open('about:blank','_self').close(); // サブウィンドウを閉じる
		window.close();
	}
// -->
</script>

</head>

<body>

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

	print "<h1>Job Manage/Job Dataの編集</h1>\n";

	# ログイン状態の確認
	if ( ! isset( $_SESSION['user_name'] ) ) {
		print "<p>ログインしてください</p>\n";
		print "<a href=# onClick=\"window_close\">閉じる</a>";
	} else {
		print "<form method=\"POST\" action=\"setjobdata.php\">\n";

		$query = "select job_datetime from jobdata where job_id = $1";
		$result = pg_query_params( $db, $query, array( $_GET['job_id'] ) );
		$datetime = pg_fetch_row($result, 0);
		$datetime_unixtime = strtotime($datetime[0]);

		print "job_id = " . $_GET['job_id'] . "<br>\n";
		print "Datetime = " . $datetime[0] . "<br>\n";

		$date_year    = date("Y", $datetime_unixtime);
		$date_mon     = date("m", $datetime_unixtime);
		$date_day     = date("d", $datetime_unixtime);

		$date_hour    = date("H", $datetime_unixtime);
		$date_mimutes = date("i", $datetime_unixtime);
		$date_second  = date("s", $datetime_unixtime);

		print("<input name=\"date_year\"    type=\"text\" size=\"4\" value=\"${date_year}\">年");
		print("<input name=\"date_mon\"     type=\"text\" size=\"2\" value=\"${date_mon}\">月");
		print("<input name=\"date_day\"     type=\"text\" size=\"2\" value=\"${date_day}\">日");
		print("<input name=\"date_hour\"    type=\"text\" size=\"2\" value=\"${date_hour}\">時");
		print("<input name=\"date_mimutes\" type=\"text\" size=\"2\" value=\"${date_mimutes}\">分");
		print("<input name=\"date_second\"  type=\"text\" size=\"2\" value=\"${date_second}\">秒");
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

		print("<input type=\"hidden\" name=\"job_id\" value=\"" . $_GET["job_id"] . "\">");

		print "<br>\n";

		print "その他:";
		print "<input name=\"job_description_input\" type=\"text\" size=\"60\">\n";
		print "<br>\n";

		print "<input type=\"submit\" value=\"修正\" name=\"BTN\">\n";

		print "</form>\n";
	}
?>

</body>

</HTML>
