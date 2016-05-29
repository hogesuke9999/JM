<?php
	# サマリー表示

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

	if(isset($job_time[$pre_job_description])){
		$job_time[$pre_job_description] = $job_time[$pre_job_description] + 300;
	} else {
		$job_time[$pre_job_description] = 300;
	}

	print "<table border=1>";
	print "<tr><th>内容</th><th>時間</th></tr>";
	asort($job_time);
	foreach ($job_time as $key => $value) {
		print "<tr><td>" . $key . "</td><td>" . number_format( $value / 60 , 2 ) . "</td></tr>\n";
	}
	print "</table>";
?>
