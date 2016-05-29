<?php

// データベースへの接続
function DBconnect($db) {

	$db = pg_connect("host=" . $DB_HOST. " port=" . $DB_PORT . " dbname=" . $DB_NAME . " user=" . $DB_USER . " password=" . $DB_PASS)
	 or die("DBの接続に失敗しました<br>");


	  if (@pg_connect("127.0.0.1","",$db) == false) {
	    print("データベースに接続できませんでした。");
	    exit;
	  }
}

// データベースの切断
function DBclose() {
	  pg_close();		// データベースとの接続切断
}

// データベースの実行
function DBexec($sql) {
	  $result = pg_query($sql);	// selectを実行
		printf( $sql);
	  if ($result == false) {
	    printf("SQL:\"$sql\"の実行に失敗しました。");
	    exit;
	  }
	  return $result ;
}

?>
