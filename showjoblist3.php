<html>

<head>
<meta http-equiv="Content-Language" content="ja">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="showjoblist.css">
<title>Job Manager</title>
	<script type="text/javascript">
	<!--
	range1 = 0;
	range2 = 0;
	range_point1 = 0;
	range_point2 = 0;

	stylesheet = document.styleSheets.item(0);

	function disp(){
		window.open("window03.php", "window_name", "width=500,height=180,scrollbars=no");
	}

	function setrange(range){
		if(range1 == 0 ) {
//			document.getElementById('range1').value=range;
			range1 = range;

			var i;
			var num = array_job_id.length;
			for(i=0;i < num;i++){
					if(array_job_id[i] == range) {
//						document.getElementById('range_point1').value = i;
						range_point1 = i;
					}
			}
		} else {
//			 document.getElementById('range2').value=range;
			range1 = 0;

			var i;
			var num = array_job_id.length;
			for(i=0;i < num;i++){
					if(array_job_id[i] == range) {
//						document.getElementById('range_point2').value = i;
						range_point2 = i;
					}
			}
			var range_job_id = "";
			var j;
			if(range_point1 > range_point2) {
				for(j=range_point2; j<= range_point1; j++) {
					range_job_id = range_job_id + "," + array_job_id[j];
					if(document.getElementById('job'+array_job_id[j]).checked) {
						document.getElementById('job'+array_job_id[j]).checked = false;
					} else {
						document.getElementById('job'+array_job_id[j]).checked = true;
					}
				}
//				document.getElementById('range').value=range_job_id;
			} else {
				for(j=range_point1; j <= range_point2; j++) {
					range_job_id = range_job_id + "," + array_job_id[j];
					if(document.getElementById('job'+array_job_id[j]).checked) {
						document.getElementById('job'+array_job_id[j]).checked = false;
					} else {
						document.getElementById('job'+array_job_id[j]).checked = true;
					}
				}
//				document.getElementById('range').value=range_job_id;
			}
		}
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

	if ( isset( $_POST["ListType"] ) ) {
		$_SESSION['ListType'] = $_POST["ListType"];
	} else {
		if ( ! isset( $_SESSION['ListType'] ) ) {
			$_SESSION['ListType'] = 1;
		}
	}

	print "<form method=\"POST\" action=\"showjoblist3.php\">\n";

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

	print "<select name=\"ListType\">\n";
		print "<option value=\"1\"";
		if( $_SESSION['ListType'] == 1 ) {
			print " selected";
		}
		print ">一覧表示</option>\n";

		print "<option value=\"2\"";
		if( $_SESSION['ListType'] == 2 ) {
			print " selected";
		}
#		print ">編集モード(開発中)</option>\n";
		print ">編集モード</option>\n";

		print "<option value=\"3\"";
		if( $_SESSION['ListType'] == 3 ) {
			print " selected";
		}
		print ">サマリー</option>\n";

		print "<option value=\"4\"";
		if( $_SESSION['ListType'] == 4 ) {
			print " selected";
		}
#		print ">編集モード(開発中)</option>\n";
#		print ">編集モード</option>\n";
		print ">サマリー(開発中)</option>\n";

	print "</select>\n";

	print "<input type=\"submit\" name=\"BTN\" value=\"選択\">\n";
	print "</form>";

	$query = "
		select job_id, to_char(job_datetime, 'YYYY-mm-dd HH24:MI:SS'), user_id, job_description from JobData
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
		switch ( $_SESSION['ListType'] ) {
			case 1:
#				print "<h2>一覧表示</h2>\n";
				include "showjoblist_list.php";
				break;
			case 2:
#				print "<h2>編集モード</h2>\n";
				include "showjoblist_edit3.php";
				break;
			case 3:
#				print "<h2>サマリー</h2>\n";
				include "showjoblist_summary.php";
				break;
			case 4:
#				print ">サマリー(開発中)</option>\n";
				include "showjoblist_summary2.php";
				break;
		}
	}
	print "<hr>";
	print "<a href=index.php>戻る</a>";
?>

</body>

</html>
