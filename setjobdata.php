<html>
<head>
	<meta http-equiv="Content-Language" content="ja">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

	<title>Job Manager</title>
	<script type="text/javascript">
	<!--
	function window_close(){
		window.open('about:blank','_self').close(); // サブウィンドウを閉じる
		window.close();
	}
	// -->
	</script>
</head>

<body onBlur="focus()" onLoad="setTimeout('window_close();', 2000)">

<h1>Job Manager/DataBaseへの書き込み</h1>
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

	switch($_POST["BTN"]) {
		case "登録":
			if($_POST["job_description_input"] != "") {
				$query = "insert into jobdata ( job_datetime, user_id, job_description ) values( now(), $1, $2 )";
				$result = pg_query_params($db, $query, array($_SESSION['user_id'], $_POST["job_description_input"] ) );
			} else {
				if($_POST["job_description_select"] != "") {
					$query = "insert into jobdata ( job_datetime, user_id, job_description ) values( now(), $1, $2 )";
					$result = pg_query_params($db, $query, array($_SESSION['user_id'], $_POST["job_description_select"] ) );
				} else {
					$query = "insert into jobdata ( job_datetime, user_id, job_description ) values( now(), $1, $2 )";
					$result = pg_query_params($db, $query, array($_SESSION['user_id'], '退席中' ) );
				}
			}
			print $_SESSION['user_name'] . " (" . $_SESSION['user_id'] . ")さんの仕事を登録しました<br>";
			break;
		case "修正":
			$job_id = $_POST["job_id"];
			$datetime = date(
				"Y-m-d H:i:s",
				mktime (
					$_POST["date_hour"],
					$_POST["date_mimutes"],
					$_POST["date_second"],
					$_POST["date_mon"],
					$_POST["date_day"],
					$_POST["date_year"],
					0
				)
			);

			if($_POST["job_description_input"] != "") {
				$job_description = $_POST["job_description_input"];
			} else {
				if($_POST["job_description_select"] != "") {
					$job_description = $_POST["job_description_select"];
				}
			}
			$query = "update jobdata set job_datetime = $1, job_description = $2 where job_id = $3";
			$result = pg_query_params($db, $query, array( $datetime, $job_description, $job_id) );

			print "datetime        = " . $datetime        . "<br>\n";
			print "job_description = " . $job_description . "<br>\n";
			print "job_id          = " . $job_id          . "<br>\n";
			print "SQL             = " . $query           . "<br>\n";

			break;
		default:
			$query = "insert into jobdata ( job_datetime, user_id, job_description ) values( now(), $1, $2 )";
			$result = pg_query_params($db, $query, array($_SESSION['user_id'], '退席中' ) );
			print $_SESSION['user_name'] . " (" . $_SESSION['user_id'] . ")さんの仕事を登録しました<br>";
			break;
		}
?>

</body>
</html>
