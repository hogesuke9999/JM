<html>
<head>
	<?php
		// セッションの初期化
		session_start();

		// セッション変数を全て解除する
		$_SESSION = array();

		if (ini_get("session.use_cookies")) {
			$params = session_get_cookie_params();
			setcookie(session_name(), '', time() - 42000,
			$params["path"], $params["domain"],
			$params["secure"], $params["httponly"]
			);
		}
		
		// 最終的に、セッションを破壊する
		session_destroy();
	?>

	<title>Job Manage</title>
	<meta http-equiv="Content-Language" content="ja">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta http-equiv="refresh" content="5; url=index.php">
</head>

<body>
<h1>Job Manage/Logout</h1>
ログアウトしました
</body>

</html>
