<?php
	# 編集モード表示
	print "<form method=\"POST\" name=\"modify_multi\" action=\"modify_multi.php\">\n";
	print "<input type=\"submit\" name=\"BTN\" value=\"一括編集\">\n";
	print "<input type=\"submit\" name=\"BTN\" value=\"一括削除\">\n";
#	print "<br>\n";

#	print "<input type=\"text\" id=\"range1\" name=\"range1\" value=\"\">";
#	print " ～ ";
#	print "<input type=\"text\" id=\"range2\" name=\"range2\" value=\"\">\n";
#	print "<br>\n";

#	print "<input type=\"text\" id=\"range_point1\" name=\"range_point1\" value=\"\">";
#	print " ～ ";
#	print "<input type=\"text\" id=\"range_point2\" name=\"range_point2\" value=\"\">\n";
#	print "<br>\n";

#	print "<input type=\"text\" id=\"range\" name=\"range\" value=\"\">";
#	print "<br>\n";

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

	$array_job_id = "";

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
			print "<td>";
			print "<a href=\"#\" onClick=\"setrange(" . $pre_job_id . ")\">→</a>";
			print "<input type=\"checkbox\" id=\"job" . $pre_job_id . "\" name=\"job_id[" . $pre_job_id . "]\">";
			print $pre_job_id;
			print "</td>";
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

			$array_job_id = $array_job_id . $pre_job_id . ", ";

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
	print "<td>";
	print "<a href=\"#\" onClick=\"setrange(" . $pre_job_id . ")\">→</a>";
	print "<input type=\"checkbox\" id=\"job" . $pre_job_id . "\" name=\"job_id[" . $pre_job_id . "]\">";
	print $pre_job_id;
	print "</td>";
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

	$array_job_id = $array_job_id . $pre_job_id;
	print "<script type=\"text/javascript\">";
	print "var array_job_id = [" . $array_job_id . "];";
	print "</script>";


?>
