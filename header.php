<?php 
include_once("db.php");
include_once("dertyn.php");

$siteurl = getSiteUrl();

?>
<html>
<head>
<title><? echo "$sitename"; ?> </title>
<link rel="stylesheet" type="text/css" media="screen" href="<? echo "$siteurl"; ?>/style.css"/>

</head>
<body>
<div class="main">
<h2 class="title"><b><a href="<? echo "$siteurl"; ?>" class="title"><? echo "$sitename"; ?></a></b></h2>
<p class="menu">
<?php
	if(checkCookie()) {
		$username = getUserName();
		echo "<a href=\"$siturl/usermod.php\" class=\"menu\">" . $username . "</a> | <a href=\"$siteurl/post.php\" class=\"menu\">post</a> | posts: " . $numOfEntries . " | <a href=\"$siteurl/settings.php\" class=\"menu\">admin</a> | <a href=\"$siteurl/comments.php\" class=\"menu\">comments</a> | <a href=\"$siteurl/search.php\" class=\"menu\">search</a> | <a href=\"$siteurl/rss.php\" class=\"menu\">rss</a> | <a href=\"$siteurl/logout.php\" class=\"menu\">logout</a>";
	} else {
		echo "posts: " . $numOfEntries . " | <a href=\"$siteurl/login.php\" class=\"menu\">login</a> | <a href=\"$siteurl/comments.php\" class=\"menu\">comments</a> | <a href=\"$siteurl/search.php\" class=\"menu\">search</a> | <a href=\"$siteurl/rss.php\" class=\"menu\">rss</a>";
	}
?>

</p>
