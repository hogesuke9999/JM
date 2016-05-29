<?php
	# 一覧表示

	print "<table border=1>\n";
	print "<tr>\n";
#    	print "<th>job_id</th>\n";
    	print "<th>開始時間</th>\n";
	    print "<th>終了時間</th>\n";
#	    print "<th>user_id</th>\n";
    	print "<th>内容</th>\n";
	print "</tr>";

	for ($i = 0; $i < pg_num_rows($result); $i++) {
		$row = pg_fetch_row($result, $i);
		if( ! isset( $pre_job_id ) ) {
			# 初回は情報を登録する
			$pre_job_id = $row[0];
			$pre_job_datetime = $row[1];
			$pre_user_id = $row[2];
			$pre_job_description = $row[3];
		} else {
			# 初回以降の処理
			if( $pre_job_description !== $row[3] ) {
				print "<tr>";
#				print "<td>" . $pre_job_id          . "</td>";
				print "<td>" . $pre_job_datetime    . "</td>";
				print "<td>" . $row[1]              . "</td>";
#				print "<td>" . $pre_user_id         . "</td>";
				print "<td>" . $pre_job_description . "</td>";
				print "</tr>";

				$pre_job_id = $row[0];
				$pre_job_datetime = $row[1];
				$pre_user_id = $row[2];
				$pre_job_description = $row[3];
			}
		}
	}

	$pre_job_datetime2 = date( "Y-m-d H:i:s", strtotime( $row[1] ) + 300 );

	print "<tr>";
#	print "<td>" . $pre_job_id          . "</td>";
	print "<td>" . $pre_job_datetime    . "</td>";
	print "<td>" . $pre_job_datetime2   . "</td>";
#	print "<td>" . $pre_user_id         . "</td>";
	print "<td>" . $pre_job_description . "</td>";
	print "</tr>";

	print "</table>";
?>
