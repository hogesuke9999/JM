<?php
	# 編集モード表示
	print "<form method=\"POST\" action=\"modify_multi.php\">\n";
	print "<input type=\"submit\" name=\"BTN\" value=\"一括編集\">\n";
	print "<input type=\"submit\" name=\"BTN\" value=\"一括削除\">\n";

	print "<table border=1>\n";
	print "<tr>";
		print "<th>";
		print "</th>";
#		print "<th>job_id</th>";
		print "<th>開始時間</th>";
		print "<th>終了時間</th>";
#		print "<th>user_id</th>";
		print "<th>内容</th>";
		print "<th></th>";
	print "</tr>\n";

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
			print "<td><input type=\"checkbox\" name=\"job_id[" . $pre_job_id . "]\"></td>";
#			print "<td>" . $pre_job_id                    . "</td>";
			print "<td>" . $pre_job_datetime              . "</td>";
			print "<td>" . $row[1]                        . "</td>";
#			print "<td>" . $pre_user_id                   . "</td>";
			print "<td>" . $pre_job_description           . "</td>";
			print "<td>";
				print "<a href=\"modify.php?job_id=" . $pre_job_id . "\" target=\"_blank\">編集</a>";
				print " / ";
				print "<a href=\"delete.php?job_id=" . $pre_job_id . "\">削除</a>";
			print "</td>";
			print "</tr>\n";

			if(isset($job_time[$pre_job_description])){
				$job_time[$pre_job_description] = $job_time[$pre_job_description] + ( strtotime($row[1]) - strtotime($pre_job_datetime) );
			} else {
				$job_time[$pre_job_description] = ( strtotime($row[1]) - strtotime($pre_job_datetime) );
			}

			$pre_job_id = $row[0];
			$pre_job_datetime = $row[1];
			$pre_user_id = $row[2];
			$pre_job_description = $row[3];
		}
	}

	$pre_job_datetime2 = date( "Y-m-d H:i:s", strtotime( $pre_job_datetime ) + 300);

	print "<tr>";
	print "<td><input type=\"checkbox\" name=\"job_id[" . $pre_job_id . "]\"></td>";
#	print "<td>" . $pre_job_id             . "</td>";
	print "<td>" . $pre_job_datetime       . "</td>";
	print "<td>" . $pre_job_datetime2      . "</td>";
#	print "<td>" . $pre_user_id            . "</td>";
	print "<td>" . $pre_job_description    . "</td>";
	print "<td>";
		print "<a href=\"modify.php?job_id=" . $pre_job_id . "\" target=\"_blank\">編集</a>";
		print " / ";
		print "<a href=\"delete.php?job_id=" . $pre_job_id . "\">削除</a>";
	print "</td>";
	print "</tr>\n";
	print "</table>\n";
	print "</form>\n";

?>
