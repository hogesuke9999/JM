<?php
	print "<form method=\"POST\" action=\"showjoblist3.php\">\n";
	print "<input type=\"hidden\" name=\"ListType\" value=\"4\">\n";
	print "<input type=\"hidden\" name=\"date_year\" value=\"" . $date_year . "\">\n";
	print "<input type=\"hidden\" name=\"date_mon\"  value=\"" . $date_mon  . "\">\n";
	print "<input type=\"hidden\" name=\"date_day\"  value=\"" . $date_day  . "\">\n";
	print "<input type=\"submit\" name=\"BTN\" value=\"更新\">\n";

	if( $_POST["BTN"] === "更新") {
		print "更新します<br>\n";
		foreach ( $_POST["project_name_inputname"] as $key => $value ) {
			$insert = "insert into project ( project_name, job_description, user_id ) values ( $1, $2, $3 );";
			if ( empty($value) ) {
				if ( $_POST["project_name"][$key] === "-") {
					print "N)" . $key . " -> " . "何も選択されていません<br>\n";
				} else {
					print "S)" . $key . " -> " . $_POST["project_name"][$key] . "<br>\n";
					$result_insert = pg_query_params( $db, $insert, array( $_POST["project_name"][$key], $key, $_SESSION['user_id'] ) );
				}
			} else {
				print "I)" . $key . " -> " . $value . "<br>\n";
				$result_insert = pg_query_params($db, $insert, array( $value, $key, $_SESSION['user_id'] ) );
			}
		}

		foreach ( $_POST["category_name_inputname"] as $key => $value ) {
			$insert = "insert into job_category ( job_category_name, job_description, user_id ) values ( $1, $2, $3 );";
			if ( empty($value) ) {
				if ( $_POST["category_name"][$key] === "-") {
					print "N)" . $key . " -> " . "何も選択されていません<br>\n";
				} else {
					print "S)" . $key . " -> " . $_POST["category_name"][$key] . "<br>\n";
					$result_insert = pg_query_params($db, $insert, array( $_POST["category_name"][$key], $key, $_SESSION['user_id'] ) );
				}
			} else {
				print "I)" . $key . " -> " . $value . "<br>\n";
				$result_insert = pg_query_params($db, $insert, array( $value, $key, $_SESSION['user_id'] ) );
			}
		}
	}

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

	print "<table border=\"1\">\n";
	print "<tr><th>案件名</th><th>区分</th><th>内容</th><th>時間</th></tr>\n";
	asort($job_time);
	foreach ($job_time as $key => $value) {
		print "<tr>\n";

		print "<td>\n";
			$query_project = "select project_name from project where job_description = $1;";
			$result_project = pg_query_params( $db, $query_project, array($key) );
			if( pg_num_rows($result_project) > 0 ) {
				$project_array = pg_fetch_row($result_project, 0);
				print $project_array[0];
			} else {
				# 案件名 の 選択リストの生成
				print "<select name=\"project_name[" . $key . "]\">\n";
				print "<option value=\"-\" selected>-</option>\n";

				$query_project_list = "select distinct project_name from project;";
				$result_project_list = pg_query( $db, $query_project_list );

				while ( $project_name_item = pg_fetch_row($result_project_list) ) {
					print "<option value=\"" . $project_name_item[0] . "\">" . $project_name_item[0] . "</option>\n";
				}

				print"</select>\n";
				print "<br>\n";
				print "<input type=\"text\" name=\"project_name_inputname[" . $key . "]\" value=\"\" size=\"32\">\n";
			}
		print "</td>\n";

		print "<td>\n";
			$query_category = "select job_category_name from job_category where job_description = $1;";
			$result_category = pg_query_params( $db, $query_category, array($key) );
			if( pg_num_rows($result_category) > 0 ) {
				$category_array = pg_fetch_row($result_category, 0);
				print $category_array[0];
			} else {
				# 案件名 の 選択リストの生成
				print "<select name=\"category_name[" . $key . "]\">\n";
				print "<option value=\"-\" selected>-</option>\n";

				$query_category_list = "select distinct job_category_name from job_category;";
				$result_category_list = pg_query( $db, $query_category_list );

				while ( $category_name_item = pg_fetch_row($result_category_list) ) {
					print "<option value=\"" . $category_name_item[0] . "\">" . $category_name_item[0] . "</option>\n";
				}

				print"</select>\n";
				print "<br>\n";
				print "<input type=\"text\" name=\"category_name_inputname[" . $key . "]\" value=\"\" size=\"32\">\n";
			}
		print "</td>\n";

		print "<td>" . $key . "</td>\n";
		print "<td>" . number_format( $value / 60 , 2 ) . "</td>\n";
		print "</tr>\n";
	}
	print "</table>\n";

	print "</form>\n";
?>
