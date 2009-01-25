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
<div id="wrap">

<div id="header">
<h2><b><a href="<? echo "$siteurl"; ?>"><? echo "$sitename"; ?></a></b></h2>
</div>

<div id="sidebar">
<?php

	echo "<ul>\n";
	echo "<li>posts: $numOfEntries</li>\n";
	echo "<li><a href=\"$siteurl/comments.php\">comments</a></li>\n";
	echo "<li><a href=\"$siteurl/search.php\">search</a></li>\n";
	echo "<li><a href=\"$siteurl/rss.php\">rss</a></li>\n";

	if(checkCookie()) {
		$username = getUserName();
		echo "<li><a href=\"$siturl/usermod.php\">password</a></li>\n";
		echo "<li><a href=\"$siteurl/post.php\">post</a></li>\n";
		echo "<li><a href=\"$siteurl/settings.php\">admin</a></li>\n";
		echo "<li><a href=\"$siteurl/logout.php\">logout</a></li>\n";
	} else {
		echo "<li><a href=\"$siteurl/login.php\">login</a></li>\n";
	}

	echo "</ul>\n";
?>
</div>
<div id="main">
