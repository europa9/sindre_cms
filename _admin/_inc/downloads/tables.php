<?php
/**
*
* File: _admin/_inc/downloads/tables.php
* Version 2
* Copyright (c) 2008-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Functions ------------------------------------------------------------------------ */
function fix_utf($value){
	$value = str_replace("Ã¸", "�", $value);
	$value = str_replace("Ã¥", "�", $value);

	return $value;
}
function fix_local($value){
	$value = htmlentities($value);

	return $value;
}
/*- Tables ---------------------------------------------------------------------------- */
$t_downloads_liquidbase				= $mysqlPrefixSav . "downloads_liquidbase";
$t_downloads_index 				= $mysqlPrefixSav . "downloads_index";
$t_downloads_comments				= $mysqlPrefixSav . "downloads_comments";

$t_downloads_main_categories 			= $mysqlPrefixSav . "downloads_main_categories";
$t_downloads_main_categories_translations 	= $mysqlPrefixSav . "downloads_main_categories_translations";

$t_downloads_sub_categories 			= $mysqlPrefixSav . "downloads_sub_categories";
$t_downloads_sub_categories_translations 	= $mysqlPrefixSav . "downloads_sub_categories_translations";




if($action == ""){
	echo"
	<h1>Tables</h1>



	<!-- Where am I? -->
		<p><b>You are here:</b><br />
		<a href=\"index.php?open=$open&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Downloads</a>
		&gt;
		<a href=\"index.php?open=$open&amp;page=tables&amp;editor_language=$editor_language&amp;l=$l\">Tables</a>
		</p>
	<!-- //Where am I? -->



	<!-- liquidbase-->
	";
	if (!$mysqli -> query("CREATE TABLE IF NOT EXISTS $t_downloads_liquidbase(
		liquidbase_id INT NOT NULL AUTO_INCREMENT,
		PRIMARY KEY(liquidbase_id), 
		 liquidbase_dir VARCHAR(200), 
		 liquidbase_file VARCHAR(200), 
		 liquidbase_run_datetime DATETIME, 
		 liquidbase_run_saying VARCHAR(200))")) {
		echo("MySQLI create table error: " . $mysqli -> error); die;
	}
	$query = "SELECT count(liquidbase_id) FROM $t_downloads_liquidbase";
	$result = $mysqli->query($query);
	$row = $result->fetch_row();
	list($sql_count_liquidbase_id) = $row;

	if ($sql_count_liquidbase_id == 0){

		// If refererer then refresh to that page
		if(isset($_GET['refererer'])) {
			$refererer = $_GET['refererer'];
			$refererer = strip_tags(stripslashes($refererer));

			echo"
			<table>
			 <tr> 
			  <td style=\"padding-right: 6px;\">
				<p>
				<img src=\"_design/gfx/loading_22.gif\" alt=\"Loading\" />
				</p>
			  </td>
			  <td>
				<h1>Loading...</h1>
			  </td>
			 </tr>
			</table>

		
			<meta http-equiv=\"refresh\" content=\"4;url=index.php?open=$open&amp;page=$refererer&amp;editor_language=$editor_language&amp;l=$l&amp;ft=success&amp;fm=module_installed\">
			";
		}
	}
	echo"
	<!-- liquidbase-->


	<!-- Feedback -->
		";
		if($ft != "" && $fm != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><p>$fm</p></div>";
		}
		echo"
	<!-- //Feedback -->

	<!-- Run -->
		";

		// Open that year folder
		$path = "_inc/downloads/_liquidbase_db_scripts";
		if ($handle = opendir($path)) {
			while (false !== ($liquidbase_name = readdir($handle))) {
				if ($liquidbase_name === '.') continue;
				if ($liquidbase_name === '..') continue;
				
				if(!(is_dir("_inc/downloads/_liquidbase_db_scripts/$liquidbase_name"))){

					// Has it been executed?
					$inp_liquidbase_module = "";
					$inp_liquidbase_name =  "$liquidbase_name";
					
					$query = "SELECT liquidbase_id FROM $t_downloads_liquidbase WHERE liquidbase_dir='$inp_liquidbase_module' AND liquidbase_file='$inp_liquidbase_name'";
					$result = $mysqli->query($query);
					$row = $result->fetch_row();
					list($get_liquidbase_id) = $row;
					if($get_liquidbase_id == ""){
						// Date
						$datetime = date("Y-m-d H:i:s");
						$run_saying = date("j M Y H:i");


						// Insert
						$stmt = $mysqli->prepare("INSERT INTO $t_downloads_liquidbase 
							(liquidbase_id, liquidbase_dir, liquidbase_file, liquidbase_run_datetime, liquidbase_run_saying) 
							VALUES 
							(NULL,?,?,?,?)");
						$stmt->bind_param("ssss", $inp_liquidbase_module, $inp_liquidbase_name, $datetime, $run_saying); 
						$stmt->execute();


						// Run code
						include("_inc/downloads/_liquidbase_db_scripts/$liquidbase_name");
					} // not runned before
				} // is dir
			} // whule open files
		} // handle modules
		echo"
	<!-- //Run -->

	<!-- liquidbase scripts -->
		<table class=\"hor-zebra\">
		 <thead>
		  <tr>
		   <th scope=\"col\">
			<span>Directory</span>
		   </th>
		   <th scope=\"col\">
			<span>File</span>
		   </th>
		   <th scope=\"col\">
			<span>Run date</span>
		   </th>
		   <th scope=\"col\">
			<span>Actions</span>
		   </th>
		  </tr>
		</thead>
		<tbody>
	";

	$query = "SELECT liquidbase_id, liquidbase_dir, liquidbase_file, liquidbase_run_datetime, liquidbase_run_saying FROM $t_downloads_liquidbase ORDER BY liquidbase_id DESC";
	$result = $mysqli->query($query);
	while($row = $result->fetch_row()) {
		list($get_liquidbase_id, $get_liquidbase_dir, $get_liquidbase_file, $get_liquidbase_run_datetime, $get_liquidbase_run_saying) = $row;

		// Style
		if(isset($style) && $style == ""){
			$style = "odd";
		}
		else{
			$style = "";
		}
	
		echo"
		 <tr>
		  <td class=\"$style\">
			<span>$get_liquidbase_dir</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_liquidbase_file</span>
		  </td>
		  <td class=\"$style\">
			<span>$get_liquidbase_run_saying</span>
		  </td>
		  <td class=\"$style\">
			<span>
			<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;liquidbase_id=$get_liquidbase_id&amp;editor_language=$editor_language\">$l_delete</a></span>
		  </td>
		 </tr>
		";

	}
	echo"
		 </tbody>
		</table>

	<!-- //liquidbase scripts -->
	";
}
elseif($action == "delete"){
	if(isset($_GET['liquidbase_id'])) {
		$liquidbase_id = $_GET['liquidbase_id'];
		$liquidbase_id  = strip_tags(stripslashes($liquidbase_id));
	}
	else{
		$liquidbase_id = "";
	}	
	$stmt = $mysqli->prepare("SELECT liquidbase_id, liquidbase_file, liquidbase_run_datetime FROM $t_downloads_liquidbase WHERE liquidbase_id=?"); 
	$stmt->bind_param("s", $liquidbase_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_liquidbase_id, $get_liquidbase_file, $get_liquidbase_run_datetime) = $row;

	if($get_liquidbase_id != ""){
		if($process == "1"){

			$mysqli->query("DELETE FROM $t_downloads_liquidbase WHERE liquidbase_id=$get_liquidbase_id") or die($mysqli->error);
			
			$url = "index.php?open=$open&page=$page&ft=success&fm=deleted";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Delete_liquidbase $get_liquidbase_file</h1>


		<p>
		Are you sure you want to dlete the liquidbase script run? 
		This will cause the script to run again after deletion. 
		</p>

		<p>
		<a href=\"index.php?open=$open&amp;page=$page&amp;action=delete&amp;liquidbase_id=$get_liquidbase_id&amp;editor_language=$editor_language&amp;process=1\" class=\"btn_warning\">Confirm delete</a>
		</p>
		";
	}
}
?>