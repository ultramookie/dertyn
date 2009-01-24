<?php
// WordPress Importer
// Steve Mookie Kong
//
// Edit below to include your WP database info.
// Point your browser:  
// http://<url_to_your_install>/wp_import.php
// Please remove this file after 
// you are finished.

include("config.php"); 
include("dertyn.php");

$wpserver = "DB_HOST";
$wplogin = "DB_USER";
$wppass = "DB_PASSWORD";
$wpdb = "DB_NAME";
$wpprefix = "wp_";

$posts_imported = 0;
$comments_imported = 0;

$link = mysql_connect("$wpserver","$wplogin","$wppass")
	or die('Could not connect: ' . mysql_error());

mysql_select_db("$wpdb") or die('Could not select database');

$query = "select post_date, post_content, post_title, post_name from " . $wpprefix . "posts where post_status='publish' order by post_date desc";
$wpcontent = mysql_query($query);

$query = " select comment_author, comment_author_url, comment_author_IP, comment_content, comment_date, " . $wpprefix . "posts.post_name from " . $wpprefix . "posts, " . $wpprefix . "comments where comment_post_ID=" . $wpprefix . "posts.ID and comment_approved='1' order by comment_date";
$wpcomments = mysql_query($query);

$link = mysql_connect("$dbhost","$dbuser","$dbpass")
	or die('Could not connect: ' . mysql_error());

mysql_set_charset('utf8',$link);

mysql_select_db("$db") or die('Could not select database');


while ($row = mysql_fetch_array($wpcontent)) {
	$subject = mysql_real_escape_string($row['post_title']);
	$body = mysql_real_escape_string($row['post_content']);
	$slug = mysql_real_escape_string($row['post_name']);
	$entrytime = mysql_real_escape_string($row['post_date']);
	
	$query = "insert into main (subject,body,entrytime,slug) values ('$subject','$body','$entrytime','$slug')";
	$status = mysql_query($query);
	$posts_imported++;
}

echo "Dertyn imported $posts_imported posts...<br />\n";

while ($row = mysql_fetch_array($wpcomments)) {
	$name = mysql_real_escape_string($row['comment_author']);
	$url = mysql_real_escape_string($row['comment_author_url']);
	$comment = mysql_real_escape_string($row['comment_content']);
	$ipaddy = mysql_real_escape_string($row['comment_author_IP']);
	$commenttime = mysql_real_escape_string($row['comment_date']);
	$slug = mysql_real_escape_string($row['post_name']);

	$pid = getPid($slug);
					
	$query = "insert into comments (name,url,comment,ip,commenttime,pid) values ('$name','$url','$comment','$ipaddy','$commenttime','$pid')";
	$status = mysql_query($query);
	$comments_imported++;
						
}

echo "Dertyn imported $comments_imported comments...<br />\n";
echo "Import completed, please removed this file.<br />\n";
?>
