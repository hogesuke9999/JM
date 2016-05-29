<html>

<head>
<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Job Manager</title>
	<script type="text/javascript">
	<!--
	function disp(){
		window.open("window03.php", "window_name", "width=500,height=180,scrollbars=no");
	}
	// -->
	</script>
</head>

<body>
<h1>Job Manager/Jobの一覧表示</h1>
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

	print "<form method=\"POST\" action=\"showjoblist.php\">\n";

	if(is_numeric($_POST["date_year"]) && is_numeric($_POST["date_mon"]) && is_numeric($_POST["date_day"])) {
		$date_year = $_POST["date_year"];
		$date_mon  = $_POST["date_mon"];
		$date_day  = $_POST["date_day"];
	} else {
		$date_year = date("Y");
		$date_mon  = date("m");
		$date_day  = date("d");
	}

	$date_day2 = $date_day + 1 ;

	print "<input type=\"text\" size=4 name=\"date_year\" value=\"" . $date_year . "\">年";
	print "<input type=\"text\" size=2 name=\"date_mon\"  value=\"" . $date_mon  . "\">月";
	print "<input type=\"text\" size=2 name=\"date_day\"  value=\"" . $date_day  . "\">日";
	print "<input type=\"submit\" name=\"BTN\" value=\"選択\">\n";

	print "(<input type=\"checkbox\" name=\"optim\" value=\"1\"";
	if( $_POST["optim"] == 1 ) {
		print " checked";
	}
	print ">最適化)\n";

	$query = "
		select job_id, job_datetime, user_id, job_description from JobData
		where user_id = $1
		and job_datetime >= $2
		and job_datetime <= $3
		order by job_datetime;
	";

	$result = pg_query_params(
		$db,
		$query,
		array(
			$_SESSION['user_id'],
			$date_year . "-" . $date_mon . "-" . $date_day . " 00:00:00",
			$date_year . "-" . $date_mon . "-" . $date_day . " 23:59:59"
		)
	);

	if (!$result) {
		die('クエリーが失敗しました。');
	} else {
		$pre_job_id = "";
		$pre_job_datetime = "";
		$pre_user_id = "";
		$pre_job_description = "";

		print "<table border=1>";
#		print "<tr><th>日付</th><th>内容</th><th>修正/削除</th><th>時間</th></tr>";
		print "<tr><th>job_id</th><th>job_datetime1</th><th>job_datetime2</th><th>user_id</th><th>job_description</th></tr>";

		for ($i = 0; $i < pg_num_rows($result); $i++) {
			$row = pg_fetch_row($result, $i);

			if( $pre_job_id === "" ) {
				# 初回は情報を登録する
				$pre_job_id = $row[0];
				$pre_job_datetime = $row[1];
				$pre_user_id = $row[2];
				$pre_job_description = $row[3];
			} else {
				# 初回以降の処理
				if( $pre_job_description === $row[3] ) {
					$pre_job_id = $row[0];
					$pre_job_datetime2 = $row[1];
#					$pre_user_id = $row[2];
#					$pre_job_description = $row[3];
				} else {
					print "<tr>";
					print "<td>" . $pre_job_id          . "</td>";
					print "<td>" . $pre_job_datetime    . "</td>";
					print "<td>" . $row[1]              . "</td>";
					print "<td>" . $pre_user_id         . "</td>";
					print "<td>" . $pre_job_description . "</td>";
					print "</tr>";

					$pre_job_id = $row[0];
					$pre_job_datetime = $row[1];
					$pre_user_id = $row[2];
					$pre_job_description = $row[3];
				}

			}

#			print "<tr>";
#			print "<td>" . $row[0] . "</td>";
#			print "<td>" . $row[1] . "</td>";
#			print "<td>" . $row[2] . "</td>";
#			print "<td>" . $row[3] . "</td>";
#			print "</tr>";

#			$pre_job_description = $row[3];
		}

		print "<tr>";
		print "<td>" . $pre_job_id          . "</td>";
		print "<td>" . $pre_job_datetime    . "</td>";
		print "<td>" . $pre_job_datetime2   . "</td>";
		print "<td>" . $pre_user_id         . "</td>";
		print "<td>" . $pre_job_description . "</td>";
		print "</tr>";

		print "</table>";


		print "<h2>サマリー</h2>";

		print "<table border=1>";
		print "<tr><th>job_id</th><th>job_datetime1</th><th>job_datetime2</th><th>user_id</th><th>job_description</th></tr>";

		$pre_job_id = "";
		$pre_job_datetime = "";
		$pre_user_id = "";
		$pre_job_description = "";

		for ($i = 0; $i < pg_num_rows($result); $i++) {
			$row = pg_fetch_row($result, $i);

			if( $pre_job_id === "" ) {
				# 初回は情報を登録する
				$pre_job_id = $row[0];
				$pre_job_datetime = $row[1];
				$pre_user_id = $row[2];
				$pre_job_description = $row[3];
			} else {
				# 初回以降の処理
				print "<tr>";
				print "<td>" . $pre_job_id          . "</td>";
				print "<td>" . $pre_job_datetime    . "</td>";
				print "<td>" . $row[1]              . "</td>";
				print "<td>" . $pre_user_id         . "</td>";
				print "<td>" . $pre_job_description . "</td>";
				print "</tr>";

				$pre_job_id = $row[0];
				$pre_job_datetime = $row[1];
				$pre_user_id = $row[2];
				$pre_job_description = $row[3];
			}
		}

		print "<tr>";
		print "<td>" . $pre_job_id          . "</td>";
		print "<td>" . $pre_job_datetime    . "</td>";
		print "<td>" . $pre_job_datetime2   . "</td>";
		print "<td>" . $pre_user_id         . "</td>";
		print "<td>" . $pre_job_description . "</td>";
		print "</tr>";
		print "</table>";
	}

	print "</form>";
	print "<hr>";
	print "<a href=index.php>戻る</a>";
?>

</body>

</html>
