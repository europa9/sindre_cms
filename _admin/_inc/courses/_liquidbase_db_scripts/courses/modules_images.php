<?php
/**
*
* File: _admin/_inc/courses/_liquibase/courses/modules_images.php
* Version 1.0.0
* Date 11:57 28.03.2021
* Copyright (c) 2021 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

echo"
<p>Create table: $t_courses_modules_images</p>

";


$mysqli->query("DROP TABLE IF EXISTS $t_courses_modules_images");

if (!$mysqli -> query("CREATE TABLE $t_courses_modules_images(
	image_id INT NOT NULL AUTO_INCREMENT,
	PRIMARY KEY(image_id), 
	 image_course_id INT,
	 image_module_id INT,
	 image_title VARCHAR(200),
	 image_text VARCHAR(200),
	 image_path VARCHAR(200),
	 image_file VARCHAR(200),
	 image_thumb_200x113 VARCHAR(200),
	 image_photo_by_name VARCHAR(200),
	 image_photo_by_website VARCHAR(200),
	 image_uploaded_datetime DATETIME,
	 image_uploaded_user_id INT,
	 image_uploaded_ip VARCHAR(200))")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}


echo"


";
?>