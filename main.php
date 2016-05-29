<?php
    print "<body onLoad=\"setTimeout('disp()', 0)\">\n";
    print "<h1>Job Manage/起動画面</h1>\n";
    print "<p>ユーザ名: " . $_SESSION['user_name'] . " (" . $_SESSION['user_id'] . ")</p>\n";
    print "<p><a href=\"showjoblist3.php\">一覧の表示・修正</a></p>\n";
#    print "<p><a href=\"record.php\"  target=\"_blank\">登録用ページ</a></p>\n";
    print "<p><a href=\"record2.php\" target=\"_blank\">登録用ページNew</a></p>\n";
    print "<p><a href=\"logout.php\">ログアウト</a></p>\n";
    print "</body>\n";
?>
