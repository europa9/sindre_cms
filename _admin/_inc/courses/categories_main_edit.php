<?php
/**
*
* File: _admin/_inc/courses/edit_category_main.php
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
/*- Tables ---------------------------------------------------------------------------- */
$t_courses_liquidbase 	 = $mysqlPrefixSav . "courses_liquidbase";


$t_courses_title_translations	 = $mysqlPrefixSav . "courses_title_translations";
$t_courses_index		 = $mysqlPrefixSav . "courses_index";
$t_courses_users_enrolled 	 = $mysqlPrefixSav . "courses_users_enrolled";

$t_courses_categories_main	 = $mysqlPrefixSav . "courses_categories_main";
$t_courses_categories_sub 	 = $mysqlPrefixSav . "courses_categories_sub";
$t_courses_modules		 = $mysqlPrefixSav . "courses_modules";
$t_courses_modules_read		 = $mysqlPrefixSav . "courses_modules_read";

$t_courses_modules_contents 	 = $mysqlPrefixSav . "courses_modules_contents";
$t_courses_modules_contents_read = $mysqlPrefixSav . "courses_modules_contents_read";
$t_courses_modules_contents_comments	= $mysqlPrefixSav . "courses_modules_contents_comments";

$t_courses_modules_quizzes_index  	= $mysqlPrefixSav . "courses_modules_quizzes_index";
$t_courses_modules_quizzes_qa 		= $mysqlPrefixSav . "courses_modules_quizzes_qa";
$t_courses_modules_quizzes_user_records	= $mysqlPrefixSav . "courses_modules_quizzes_user_records";

$t_courses_exams_index  		= $mysqlPrefixSav . "courses_exams_index";
$t_courses_exams_qa			= $mysqlPrefixSav . "courses_exams_qa";
$t_courses_exams_user_tries		= $mysqlPrefixSav . "courses_exams_user_tries";
$t_courses_exams_user_tries_qa		= $mysqlPrefixSav . "courses_exams_user_tries_qa";

/*- Functions ------------------------------------------------------------------------ */
include("_functions/get_extension.php");

/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['main_category_id'])){
	$main_category_id = $_GET['main_category_id'];
	$main_category_id = strip_tags(stripslashes($main_category_id));
}
else{
	$main_category_id = "";
}


if($action == ""){
	$stmt = $mysqli->prepare("SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_icon_path, main_category_icon_16x16, main_category_icon_18x18, main_category_icon_24x24, main_category_icon_32x32, main_category_icon_36x36, main_category_icon_48x48, main_category_icon_96x96, main_category_icon_192x192, main_category_icon_260x260, main_category_header_logo, main_category_webdesign, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=?"); 
	$stmt->bind_param("s", $main_category_id);
	$stmt->execute();
	$result = $stmt->get_result();
	$row = $result->fetch_row();
	list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_icon_path, $get_current_main_category_icon_16x16, $get_current_main_category_icon_18x18, $get_current_main_category_icon_24x24, $get_current_main_category_icon_32x32, $get_current_main_category_icon_36x36, $get_current_main_category_icon_48x48, $get_current_main_category_icon_96x96, $get_current_main_category_icon_192x192, $get_current_main_category_icon_260x260, $get_current_main_category_header_logo, $get_current_main_category_webdesign, $get_current_main_category_created, $get_current_main_category_updated) = $row;

	if($get_current_main_category_id == ""){
		echo"<p>Server error 404.</p>";
	}
	else{

		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);

			$inp_title_clean = clean($inp_title);

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);

			$inp_webdesign = $_POST['inp_webdesign'];
			$inp_webdesign = output_html($inp_webdesign);



			$datetime = date("Y-m-d H:i:s");

			$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
				main_category_title=?, 
				main_category_title_clean=?, 
				main_category_language=?,
				main_category_webdesign=?, 
				main_category_updated=?
				WHERE main_category_id=?");
			$stmt->bind_param("ssssss", $inp_title, $inp_title_clean, $inp_language, $inp_webdesign, $datetime, $get_current_main_category_id); 
			$stmt->execute();


			// Folder
			if(!(is_dir("../_uploads"))){
				mkdir("../_uploads");
			}
			if(!(is_dir("../_uploads/courses"))){
				mkdir("../_uploads/courses");
			}
			if(!(is_dir("../_uploads/courses/main"))){
				mkdir("../_uploads/courses/main");
			}
			if(!(is_dir("../_uploads/courses/main/$inp_title_clean"))){
				mkdir("../_uploads/courses/main/$inp_title_clean");
			}
			if(!(is_dir("../_uploads/courses/main/$inp_title_clean/_gfx"))){
				mkdir("../_uploads/courses/main/$inp_title_clean/_gfx");
			}
			if(!(is_dir("../_uploads/courses/main/$inp_title_clean/_gfx/icons"))){
				mkdir("../_uploads/courses/main/$inp_title_clean/_gfx/icons");
			}
		

			$upload_path = "../_uploads/courses/main/$inp_title_clean/_gfx/icons";


	
			$ft_icon_16 = "info";
           		$fm_icon_16 = "nothing";

			$ft_icon_32 = "info";
           		$fm_icon_32 = "nothing";

			$ft_icon_192 = "info";
           		$fm_icon_192 = "nothing";
			$icon_sizes = array('16', '32', '192');
			for($x=0;$x<sizeof($icon_sizes);$x++){
		
			
				$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];
				

				$image_name = stripslashes($_FILES["inp_icon_$icon_size"]['name']);
				$extension = get_extension($image_name);
				$extension = strtolower($extension);
	
				if($image_name){
					if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {

						if($icon_sizes[$x] == "16"){
							$ft_icon_16 = "warning";
							$fm_icon_16 = "unknown_file_extension_$extension";	
						}
						elseif($icon_sizes[$x] == "32"){
							$ft_icon_32 = "warning";
							$fm_icon_32 = "unknown_file_extension_$extension";	
						}
						elseif($icon_sizes[$x] == "192"){
							$ft_icon_192 = "warning";
							$fm_icon_192 = "unknown_file_extension_$extension";	
						}
					}
					else{
						// Give new name
						$inp_name = $get_current_main_category_id . "_icon_" . $icon_size . ".$extension";
						$uploaded_file = $upload_path . "/" . $inp_name;

						// Upload file
						if (move_uploaded_file($_FILES["inp_icon_$icon_size"]['tmp_name'], $uploaded_file)) {

							$inp_icon_path = "_uploads/courses/main/$inp_title_clean/_gfx/icons";


							// Get image size
							$file_size = filesize($uploaded_file);
						
							// Check with and height
							list($width,$height) = getimagesize($uploaded_file);
	
							if($width == "" OR $height == ""){
								unlink("$uploaded_file");
								if($icon_sizes[$x] == "16"){
									$ft_icon_16 = "warning";
									$fm_icon_16 = "getimagesize_failed";	
								}
								elseif($icon_sizes[$x] == "32"){
									$ft_icon_32 = "warning";
									$fm_icon_32 = "getimagesize_failed";	
								}
								elseif($icon_sizes[$x] == "192"){
									$ft_icon_192 = "warning";
									$fm_icon_192 = "getimagesize_failed";
								}
							}
							else{
								// All ok
								$inp_icon_mysql = quote_smart($link, $inp_name);
							
								if($icon_sizes[$x] == "16"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_16x16=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();


									$ft_icon_16 = "success";
									$fm_icon_16 = "icon_uploaded";
								}
								if($icon_sizes[$x] == "18"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_18x18=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();
								}
								if($icon_sizes[$x] == "24"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_24x24=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();
								}
								if($icon_sizes[$x] == "32"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_32x32=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();

									$ft_icon_32 = "success";
									$fm_icon_32 = "icon_uploaded";
								}
								if($icon_sizes[$x] == "36"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_36x36=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();
								}
								if($icon_sizes[$x] == "48"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_48x48=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();
								}
								if($icon_sizes[$x] == "96"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_96x96=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();
								}
								if($icon_sizes[$x] == "192"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_192x192=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();

									$ft_icon_192 = "success";
									$fm_icon_192 = "icon_uploaded";
								}
								if($icon_sizes[$x] == "260"){
									$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
												main_category_icon_path=?, 
												main_category_icon_260x260=?
												WHERE main_category_id=?");
									$stmt->bind_param("sss", $inp_icon_path, $inp_icon, $get_current_main_category_id); 
									$stmt->execute();
								}


							}
						}
						else{
							$fm_icon = "";
							switch ($_FILES['inp_food_image']['error']) {
								case UPLOAD_ERR_OK:
          								$fm_icon = "There is no error, the file uploaded with success.";
									break;
								case UPLOAD_ERR_NO_FILE:
           								$fm_icon = "no_file_uploaded";
									break;
								case UPLOAD_ERR_INI_SIZE:
          								$fm_icon = "to_big_size_in_configuration";
									break;
								case UPLOAD_ERR_FORM_SIZE:
          								$fm_icon = "to_big_size_in_form";
									break;
								default:
          								$fm_icon = "unknown_error";
									break;
							}
							if($icon_sizes[$x] == "16"){
								$ft_icon_16 = "warning";
								$fm_icon_16 = "$fm_icon";	
							}
							elseif($icon_sizes[$x] == "32"){
								$ft_icon_32 = "warning";
								$fm_icon_32 = "$fm_icon";	
							}
							elseif($icon_sizes[$x] == "192"){
								$ft_icon_192 = "warning";
								$fm_icon_192 = "$fm_icon";
							}

					
						}
					}
				}
			} // for icons


			// Header logo
			if(!(is_dir("../_uploads/courses/main/$inp_title_clean/_gfx/header"))){
				mkdir("../_uploads/courses/main/$inp_title_clean/_gfx/header");
			}
			$ft_header_logo = "info";
           		$fm_header_logo = "nothing";
			$upload_path = "../$inp_title_clean/_gfx/header";
			$image_name = stripslashes($_FILES["inp_header_logo"]['name']);
			$extension = get_extension($image_name);
			$extension = strtolower($extension);

			if($image_name){
				if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
					$ft_header_logo = "warning";
					$fm_header_logo = "unknown_file_extension_$extension";
				}
				else{
					// Give new name
					$inp_name = $inp_title_clean . "_header_logo" . ".$extension";
					$uploaded_file = $upload_path . "/" . $inp_name;

					// Upload file
					if (move_uploaded_file($_FILES["inp_header_logo"]['tmp_name'], $uploaded_file)) {


						// Get image size
						$file_size = filesize($uploaded_file);
						
						// Check with and height
						list($width,$height) = getimagesize($uploaded_file);
	
						if($width == "" OR $height == ""){
							unlink("$uploaded_file");
							$ft_header_logo = "warning";
							$fm_header_logo = "getimagesize_failed";
						}
						else{
							// All ok
							$inp_header_logo = "$inp_name";

							$stmt = $mysqli->prepare("UPDATE $t_courses_categories_main SET 
								main_category_header_logo=?
								WHERE main_category_id=?");
							$stmt->bind_param("ss", $inp_header_logo, $$get_current_main_category_id); 
							$stmt->execute();

							$ft_header_logo = "success";
							$fm_header_logo = "header_logo_uploaded";
						}
					}
					else{
						switch ($_FILES['inp_food_image']['error']) {
							case UPLOAD_ERR_OK:
          							$fm_header_logo = "There is no error, the file uploaded with success.";
								break;
							case UPLOAD_ERR_NO_FILE:
           							$fm_header_logo = "no_file_uploaded";
								break;
							case UPLOAD_ERR_INI_SIZE:
          							$fm_header_logo = "to_big_size_in_configuration";
								break;
							case UPLOAD_ERR_FORM_SIZE:
          							$fm_header_logo = "to_big_size_in_form";
								break;
							default:
          							$fm_header_logo = "unknown_error";
								break;
						}	
						$ft_header_logo = "warning";
					}
				}
			}

			// Get all information
			$query = "SELECT main_category_id, main_category_title, main_category_title_clean, main_category_description, main_category_language, main_category_icon_path, main_category_icon_16x16, main_category_icon_18x18, main_category_icon_24x24, main_category_icon_32x32, main_category_icon_36x36, main_category_icon_48x48, main_category_icon_96x96, main_category_icon_260x260, main_category_header_logo, main_category_webdesign, main_category_created, main_category_updated FROM $t_courses_categories_main WHERE main_category_id=$get_current_main_category_id";
			$result = $mysqli->query($query);
			$row = $result->fetch_row();
			list($get_current_main_category_id, $get_current_main_category_title, $get_current_main_category_title_clean, $get_current_main_category_description, $get_current_main_category_language, $get_current_main_category_icon_path, $get_current_main_category_icon_16x16, $get_current_main_category_icon_18x18, $get_current_main_category_icon_24x24, $get_current_main_category_icon_32x32, $get_current_main_category_icon_36x36, $get_current_main_category_icon_48x48, $get_current_main_category_icon_96x96, $get_current_main_category_icon_260x260, $get_current_main_category_header_logo, $get_current_main_category_webdesign, $get_current_main_category_created, $get_current_main_category_updated) = $row;


			// Header
			$url = "index.php?open=$open&page=categories_main_edit&main_category_id=$get_current_main_category_id&editor_language=$editor_language&ft=success&fm=changes_saved&ft_icon_16=$ft_icon_16&fm_icon_16=$fm_icon_16";
			$url = $url . "&ft_icon_32=$ft_icon_32&fm_icon_32=$fm_icon_32";
			$url = $url . "&ft_icon_192=$ft_icon_192&fm_icon_192=$fm_icon_192&ft_header_logo=$ft_header_logo&fm_header_logo=$fm_header_logo";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Edit main category</h1>
					

		<!-- Feedback -->
		";
		if($ft != ""){
			if($fm == "changes_saved"){
				$fm = "$l_changes_saved";
			}
			else{
				$fm = ucfirst($fm);
			}
			echo"<div class=\"$ft\"><span>$fm</span></div>";
		}
		echo"	
		<!-- //Feedback -->
	



		<!-- Where am I? -->
			<p><b>You are here:</b><br />
			<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses menu</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=categories_main&amp;editor_language=$editor_language&amp;l=$l\">Main categories</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=categories_main_edit&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;l=$l\">Edit main category</a>
			</p>
		<!-- //Where am I? -->


		<!-- Edit course form -->
		

		<script>
		window.onload = function() {
			document.getElementById(\"inp_title\").focus();
		}
		</script>
			
		<form method=\"post\" action=\"index.php?open=$open&amp;page=$page&amp;main_category_id=$get_current_main_category_id&amp;editor_language=$editor_language&amp;process=1\" enctype=\"multipart/form-data\">

		<p><b>Title:</b><br />
		<input type=\"text\" name=\"inp_title\" id=\"inp_title\" value=\"$get_current_main_category_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
		</p>

		<p><b>Language:</b><br />
		<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
		$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_default FROM $t_languages_active";
		$result = $mysqli->query($query);
		while($row = $result->fetch_row()) {
			list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_default) = $row;
			echo"	<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_main_category_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
		}
		echo"
		</select>
		</p>


		<p><b>Webdesign:</b><br />
		<select name=\"inp_webdesign\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">
			<option value=\"same_as_website\""; if($get_current_main_category_webdesign == "same_as_website"){ echo" selected=\"selected\""; } echo">Same as website</option>\n";
		$path = "../_webdesign";
		if(!(is_dir("$path"))){
			echo"$path doesnt exists";
			die;
		}
		if ($handle = opendir($path)) {
			$x = 0;
			while (false !== ($webdesign_name = readdir($handle))) {
				if ($webdesign_name === '.') continue;
				if ($webdesign_name === '..') continue;
				if ($webdesign_name === 'images') continue;
				if ($webdesign_name === '_other_designs') continue;
				if(is_dir("$path/$webdesign_name")){
					echo"	<option value=\"$webdesign_name\""; if($webdesign_name == "$get_current_main_category_webdesign"){ echo" selected=\"selected\""; } echo">$webdesign_name</option>\n";
				}
			}
		}
		echo"
		</select>
		</p>

		<!-- Icon 48, 64, 96 -->
			";

			$icon_sizes = array('16', '32', '192');
			for($x=0;$x<sizeof($icon_sizes);$x++){
				$icon_size = $icon_sizes[$x] . "x" . $icon_sizes[$x];

				echo"
				<!-- Icon x -->
					
					<table>
					 <tr>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
						<p style=\"padding-bottom:0;margin-bottom:0;\"><b>New icon ($icon_size)</b></p>\n";
						if($icon_sizes[$x] == "16"){
							if(isset($_GET['ft_icon_16']) && isset($_GET['fm_icon_16'])){
								$ft = $_GET['ft_icon_16'];
								$ft = strip_tags(stripslashes($ft));
								if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
									echo"Server error 403 feedback error";die;
								}
								$fm = $_GET['fm_icon_16'];
								$fm = str_replace("_", " ", $fm);
								$fm = ucfirst($fm);
								$fm = output_html($fm);
								echo"<div class=\"$ft\"><span>$fm</span></div>";
							}
						}
						elseif($icon_sizes[$x] == "32"){
							if(isset($_GET['ft_icon_32']) && isset($_GET['fm_icon_32'])){
								$ft = $_GET['ft_icon_32'];
								$ft = strip_tags(stripslashes($ft));
								if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
									echo"Server error 403 feedback error";die;
								}
								$fm = $_GET['fm_icon_32'];
								$fm = str_replace("_", " ", $fm);
								$fm = ucfirst($fm);
								$fm = output_html($fm);
								echo"<div class=\"$ft\"><span>$fm</span></div>";
							}
						}
						elseif($icon_sizes[$x] == "192"){
							if(isset($_GET['ft_icon_192']) && isset($_GET['fm_icon_192'])){
								$ft = $_GET['ft_icon_192'];
								$ft = strip_tags(stripslashes($ft));
								if($ft != "error" && $ft != "warning" && $ft != "success" && $ft != "info"){
									echo"Server error 403 feedback error";die;
								}
								$fm = $_GET['fm_icon_192'];
								$fm = str_replace("_", " ", $fm);
								$fm = ucfirst($fm);
								$fm = output_html($fm);
								echo"<div class=\"$ft\"><span>$fm</span></div>";
							}
						}
						echo"
						<p style=\"padding-top:0;margin-top:0;\">
						<input type=\"file\" name=\"inp_icon_$icon_size\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
						</p>
					  </td>
					  <td style=\"vertical-align:top;padding-right: 20px;\">
						";
						if($icon_sizes[$x] == "16" && $get_current_main_category_icon_16x16 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_16x16")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_16x16\" alt=\"$get_current_main_category_icon_16x16\" /></p>\n";
						}
						if($icon_sizes[$x] == "18" && $get_current_main_category_icon_18x18 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_18x18")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_18x18\" alt=\"$get_current_main_category_icon_18x18\" /></p>\n";
						}
						if($icon_sizes[$x] == "24" && $get_current_main_category_icon_24x24 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_24x24")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_24x24\" alt=\"$get_current_main_category_icon_24x24\" /></p>\n";
						}
						if($icon_sizes[$x] == "32" && $get_current_main_category_icon_32x32 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_32x32")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_32x32\" alt=\"$get_current_main_category_icon_32x32\" /></p>\n";
						}
						if($icon_sizes[$x] == "36" && $get_current_main_category_icon_36x36 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_36x36")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_36x36\" alt=\"$get_current_main_category_icon_36x36\" /></p>\n";
						}
						if($icon_sizes[$x] == "48" && $get_current_main_category_icon_48x48 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_48x48")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_48x48\" alt=\"$get_current_main_category_icon_48x48\" /></p>\n";
						}
						if($icon_sizes[$x] == "96" && $get_current_main_category_icon_96x96 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_96x96")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_96x96\" alt=\"$get_current_main_category_icon_96x96\" /></p>\n";
						}
						if($icon_sizes[$x] == "192" && $get_current_main_category_icon_192x192 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_192x192")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_192x192\" alt=\"$get_current_main_category_icon_192x192\" /></p>\n";
						}
						if($icon_sizes[$x] == "260" && $get_current_main_category_icon_260x260 != "" && file_exists("../$get_current_main_category_icon_path/$get_current_main_category_icon_260x260")){
							echo"<p><img src=\"../$get_current_main_category_icon_path/$get_current_main_category_icon_260x260\" alt=\"$get_current_main_category_icon_260x260\" /></p>\n";
						}
						echo"
					  </td>
					 </tr>
					</table>
					
				<!-- //Icon x -->
				";
			}
			echo"
	
		<!-- //Icon 48, 64, 96 -->

		<!-- Header logo -->
			<table>
			 <tr>
			  <td style=\"vertical-align:top;padding-right: 20px;\">
				<p><b>Header logo</b><br />
				";
				if(isset($_GET['ft_header_logo']) && isset($_GET['fm_header_logo'])){
					$ft_header_logo = $_GET['ft_header_logo'];
					$ft_header_logo = strip_tags(stripslashes($ft_header_logo));
					if($ft_header_logo != "error" && $ft_header_logo != "warning" && $ft_header_logo != "success" && $ft_header_logo != "info"){
						echo"Server error 403 feedback error";die;
					}
					$fm_header_logo = $_GET['fm_header_logo'];
					$fm_header_logo = strip_tags(stripslashes($fm_header_logo));

					echo"<span>$fm_header_logo<br /></span>\n";
				}
				echo"
				<input type=\"file\" name=\"inp_header_logo\"  tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
				</p>
			  </td>
			  <td style=\"vertical-align:top;padding-right: 20px;\">";
				if($get_current_main_category_header_logo != "" && file_exists("../$get_current_main_category_title_clean/_gfx/header/$get_current_main_category_header_logo")){
					echo"<p><img src=\"../$get_current_main_category_title_clean/_gfx/header/$get_current_main_category_header_logo\" alt=\"$get_current_main_category_header_logo\" /></p>\n";
				}
				echo"
					
			  </td>
			 </tr>
			</table>
		<!-- //Header logo -->

		<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

		</form>
		<!-- //Edit course form -->
		";
	} // found
} // action == 
?>