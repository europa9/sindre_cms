<?php
/**
*
* File: _admin/_inc/downloads/_liquibase/downloads_comments.php
* Version 2
* Copyright (c) 2021-2023 Sindre Andre Ditlefsen
* License: http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/
/*- Access check ----------------------------------------------------------------------- */
if(!(isset($define_access_to_control_panel))){
	echo"<h1>Server error 403</h1>";
	die;
}

/*- Tables ---------------------------------------------------------------------------- */

echo"<span>Creating table $t_downloads_comments<br /></span>\n";

$mysqli->query("DROP TABLE IF EXISTS $t_downloads_comments");

if (!$mysqli -> query("CREATE TABLE $t_downloads_comments(
	  	 comment_id INT NOT NULL AUTO_INCREMENT,
	 	  PRIMARY KEY(comment_id), 
	  	   comment_download_id INT,
	  	   comment_text TEXT,
	  	   comment_by_user_id INT,
	  	   comment_by_user_name VARCHAR(50),
	  	   comment_by_user_image_path VARCHAR(250),
	  	   comment_by_user_image_file VARCHAR(50),
	  	   comment_by_user_image_thumb_60 VARCHAR(50),
	  	   comment_by_user_ip VARCHAR(200),
	  	   comment_created DATETIME,
	  	   comment_created_saying VARCHAR(50),
	  	   comment_created_timestamp VARCHAR(50),
	  	   comment_updated DATETIME,
	  	   comment_updated_saying VARCHAR(50),
	  	   comment_likes INT,
	  	   comment_dislikes INT,
	  	   comment_number_of_replies INT,
	  	   comment_read_blog_owner INT,
	  	   comment_reported INT,
	  	   comment_reported_by_user_id INT,
	  	   comment_reported_reason TEXT,
	  	   comment_reported_checked INT)")) {
	echo("MySQLI create table error: " . $mysqli -> error); die;
}

?>