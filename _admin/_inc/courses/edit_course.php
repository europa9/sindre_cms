<?php
/**
*
* File: _admin/_inc/comments/courses_edit.php
* Version 
* Date 20:17 30.10.2017
* Copyright (c) 2008-2017 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Variables ------------------------------------------------------------------------ */
$tabindex = 0;
if(isset($_GET['course_id'])){
	$course_id = $_GET['course_id'];
	$course_id = strip_tags(stripslashes($course_id));
}
else{
	$course_id = "";
}

$stmt = $mysqli->prepare("SELECT course_id, course_title, course_title_clean, course_is_active, course_front_page_intro, course_description, course_contents, course_language, course_main_category_id, course_main_category_title, course_sub_category_id, course_sub_category_title, course_intro_video_embedded, course_image_file, course_image_thumb, course_icon_16, course_icon_32, course_icon_48, course_icon_64, course_icon_96, course_icon_260, course_modules_count, course_lessons_count, course_quizzes_count, course_users_enrolled_count, course_read_times, course_read_times_ip_block, course_created, course_updated FROM $t_courses_index WHERE course_id=?"); 
$stmt->bind_param("s", $course_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_row();
list($get_current_course_id, $get_current_course_title, $get_current_course_title_clean, $get_current_course_is_active, $get_current_course_front_page_intro, $get_current_course_description, $get_current_course_contents, $get_current_course_language, $get_current_course_main_category_id, $get_current_course_main_category_title, $get_current_course_sub_category_id, $get_current_course_sub_category_title, $get_current_course_intro_video_embedded, $get_current_course_image_file, $get_current_course_image_thumb, $get_current_course_icon_16, $get_current_course_icon_32, $get_current_course_icon_48, $get_current_course_icon_64, $get_current_course_icon_96, $get_current_course_icon_260, $get_current_course_modules_count, $get_current_course_lessons_count, $get_current_course_quizzes_count, $get_current_course_users_enrolled_count, $get_current_course_read_times, $get_current_course_read_times_ip_block, $get_current_course_created, $get_current_course_updated) = $row;

if($get_current_course_id == ""){
	echo"<p>Server error 404.</p>";
}
else{
	// Find category
	$query = "SELECT category_id, category_title, category_dir_name, category_description, category_language, category_created, category_updated FROM $t_courses_categories WHERE category_id=$get_current_course_category_id";
	$result = mysqli_query($link, $query);
	$row = mysqli_fetch_row($result);
	list($get_current_category_id, $get_current_category_title, $get_current_category_dir_name, $get_current_category_description, $get_current_category_language, $get_current_category_created, $get_current_category_updated) = $row;
	if($get_current_category_id == ""){
		echo"Category not found?";
		die;
	}

	if($action == ""){
		if($process == "1"){
			$inp_title = $_POST['inp_title'];
			$inp_title = output_html($inp_title);
			$inp_title_mysql = quote_smart($link, $inp_title);

			$inp_short_introduction = $_POST['inp_short_introduction'];
			$inp_short_introduction = output_html($inp_short_introduction);
			$inp_short_introduction_mysql = quote_smart($link, $inp_short_introduction);

			$inp_long_introduction = $_POST['inp_long_introduction'];

			$inp_contents = $_POST['inp_contents'];

			$inp_language = $_POST['inp_language'];
			$inp_language = output_html($inp_language);
			$inp_language_mysql = quote_smart($link, $inp_language);

			$inp_dir_name = $_POST['inp_dir_name'];
			$inp_dir_name = output_html($inp_dir_name);
			$inp_dir_name_mysql = quote_smart($link, $inp_dir_name);
	
			$inp_category_id = $_POST['inp_category_id'];
			$inp_category_id = output_html($inp_category_id);
			$inp_category_id_mysql = quote_smart($link, $inp_category_id);

			$inp_intro_video_embedded = $_POST['inp_intro_video_embedded'];
			$inp_intro_video_embedded = output_html($inp_intro_video_embedded);
			$inp_intro_video_embedded_mysql = quote_smart($link, $inp_intro_video_embedded);



			$datetime = date("Y-m-d H:i:s");


			$result = mysqli_query($link, "UPDATE $t_courses_index SET 
				course_title=$inp_title_mysql, 
				course_short_introduction=$inp_short_introduction_mysql,
				course_language=$inp_language_mysql, 
				course_dir_name=$inp_dir_name_mysql, 
				course_category_id=$inp_category_id_mysql, 
				course_intro_video_embedded=$inp_intro_video_embedded_mysql, 
				course_updated='$datetime'
				WHERE course_id=$get_current_course_id") or die(mysqli_error($link));


			// Long intro and content
			$sql = "UPDATE $t_courses_index SET course_long_introduction=?, course_contents=? WHERE course_id=$get_current_course_id";
			$stmt = $link->prepare($sql);
			$stmt->bind_param("ss", $inp_long_introduction, $inp_contents);
			$stmt->execute();
			if ($stmt->errno) {
				echo "FAILURE!!! " . $stmt->error; die;
			}

			// Header
			$url = "index.php?open=$open&page=edit_course&category_id=$inp_category_id&course_id=$get_current_course_id&editor_language=$editor_language&ft=success&fm=changes_saved#course$get_current_course_id";
			header("Location: $url");
			exit;
		}

		echo"
		<h1>Edit course $get_current_category_title</h1>
				

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
			<a href=\"index.php?open=courses&amp;page=menu&amp;editor_language=$editor_language&amp;l=$l\">Courses</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=default&amp;editor_language=$editor_language&amp;l=$l\">Categories</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=open_category&amp;category_id=$get_current_category_id&amp;editor_language=$editor_language&amp;l=$l\">$get_current_category_title</a>
			&gt;
			<a href=\"index.php?open=courses&amp;page=$page&amp;course_id=$get_current_course_id&amp;editor_language=$editor_language&amp;l=$l\">Edit $get_current_category_title</a>
			</p>
		<!-- //Where am I? -->


		<!-- Edit course form -->
		<!-- TinyMCE -->
			<script type=\"text/javascript\" src=\"_javascripts/tinymce/tinymce.min.js\"></script>
			<script>
				tinymce.init({
					selector: 'textarea.editor',
					plugins: 'print preview searchreplace autolink directionality visualblocks visualchars fullscreen image link media template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists wordcount imagetools textpattern help',
					toolbar: 'formatselect | bold italic strikethrough forecolor backcolor permanentpen formatpainter | link image media pageembed | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent | removeformat | addcomment',
					image_advtab: true,
					content_css: [
						'//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
						'//www.tiny.cloud/css/codepen.min.css'
					],
					link_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
					image_list: [
						{ title: 'My page 1', value: 'http://www.tinymce.com' },
						{ title: 'My page 2', value: 'http://www.moxiecode.com' }
					],
						image_class_list: [
						{ title: 'None', value: '' },
						{ title: 'Some class', value: 'class-name' }
					],
					importcss_append: true,
					height: 500,
					file_picker_callback: function (callback, value, meta) {
						/* Provide file and text for the link dialog */
						if (meta.filetype === 'file') {
							callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
						}
						/* Provide image and alt text for the image dialog */
						if (meta.filetype === 'image') {
							callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
						}
						/* Provide alternative source and posted for the media dialog */
						if (meta.filetype === 'media') {
							callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
						}
					}
				});
				</script>
		<!-- //TinyMCE -->

			<script>
			\$(document).ready(function(){
				\$('[name=\"inp_title\"]').focus();
			});
			</script>
			
			<form method=\"post\" action=\"index.php?open=courses&amp;page=$page&amp;course_id=$get_current_course_id&amp;editor_language=$editor_language&amp;l=$l&amp;process=1\" enctype=\"multipart/form-data\">

			<p><b>Title:</b><br />
			<input type=\"text\" name=\"inp_title\" value=\"$get_current_course_title\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Short introduction:</b><br />
			<textarea name=\"inp_short_introduction\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">";
			$get_current_course_short_introduction = str_replace("<br />", "\n", $get_current_course_short_introduction);
			echo"$get_current_course_short_introduction</textarea>
			</p>

			<p><b>Long introduction:</b><br />
			<textarea name=\"inp_long_introduction\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"editor\">$get_current_course_long_introduction</textarea>
			</p>

			<p><b>Description:</b><br />
			<textarea name=\"inp_contents\" rows=\"8\" cols=\"60\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" class=\"editor\">$get_current_course_contents</textarea>
			</p>


			<p><b>Language:</b><br />
			<select name=\"inp_language\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
			$query = "SELECT language_active_id, language_active_name, language_active_iso_two, language_active_flag, language_active_default FROM $t_languages_active";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_language_active_id, $get_language_active_name, $get_language_active_iso_two, $get_language_active_flag, $get_language_active_default) = $row;
				echo"	<option value=\"$get_language_active_iso_two\""; if($get_language_active_iso_two == "$get_current_course_language"){ echo" selected=\"selected\""; } echo">$get_language_active_name</option>\n";
			}
			echo"
			</select>

			<p><b>Directory name:</b><br />
			<input type=\"text\" name=\"inp_dir_name\" value=\"$get_current_course_dir_name\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><b>Category:</b><br />
			<select name=\"inp_category_id\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\">\n";
			$query = "SELECT category_id, category_title, category_description, category_language, category_created, category_updated FROM $t_courses_categories ORDER BY category_title ASC";
			$result = mysqli_query($link, $query);
			while($row = mysqli_fetch_row($result)) {
				list($get_category_id, $get_category_title, $get_category_description, $get_category_language, $get_category_created, $get_category_updated) = $row;

				echo"	<option value=\"$get_category_id\""; if($get_category_id == "$get_current_course_category_id"){ echo" selected=\"selected\""; } echo">$get_category_title</option>\n";
			}
			echo"
			</select>

			<p><b>Intro video embedded:</b><br />
			<input type=\"text\" name=\"inp_intro_video_embedded\" value=\"$get_current_course_intro_video_embedded\" size=\"25\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" />
			</p>

			<p><input type=\"submit\" value=\"Save changes\" class=\"btn_default\" tabindex=\"";$tabindex=$tabindex+1;echo"$tabindex\" /></p>

			</form>
		<!-- //Edit course form -->
		";
	} // action ==""
} // found
?>