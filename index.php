<html>

<head>
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

	# 認証済みの有無
	#  認証済み $authflag = 1
	#  未認証   $authflag = 0
	if ( isset( $_SESSION['user_name'] ) ) {
		$authflag = 1;
	} else {
		if( $_POST['auth_button'] === "ログイン" ) {
			$query = "select user_id from users where user_name = $1 and user_pass = $2";
			$result = pg_query_params( $db, $query, array( $_POST['user_name'], $_POST["user_pass"] ) );

			$ret_value_array = pg_fetch_row ($result);
			if($ret_value_array[0] > 0) {
				$_SESSION['user_id'] = $ret_value_array[0] ;
				$_SESSION['user_name'] = $_POST['user_name'] ;
				$authflag = 1;
			}
		} else {
			$authflag = 0;
		}
	}

	print "<title>Job Manage</title>\n";
	print "<meta http-equiv=\"Content-Language\" content=\"ja\">\n";
	print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
	print "<meta http-equiv=\"Refresh\" content=\"300\">\n";

	print "<script type=\"text/javascript\">\n";
	print "<!--\n";
	print "function disp(){\n";
	print "window.open(\"record2.php\", \"window_name\", \"width=640,height=240,scrollbars=no\");\n";
	print "}\n";
	print "// -->\n";
	print "</script>\n";

	print "</head>\n";

	if ( $authflag == 0 ) {
		include "authpage.php";
	} else {
		include "main.php";
	}
?>

</html>
