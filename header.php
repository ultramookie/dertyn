<?php 
include_once("db.php");
include_once("dertyn.php");

$siteurl = getSiteUrl();
$rewriteCheck = getrewriteCheck();
$totalComments = getTotalNumComments();

$id = stripslashes($_GET['id']);

$numEntries = getIndexNum();
$pagenum = 1;

	if ($rewriteCheck == 1) {
		$pid = getPid($id);
	}  else {
		$pid = $id;
	}

	if($pid > 0) {
		$subject = getSubject($pid);
		$title = "$subject - $sitename";
	} else {
		$title = $sitename;
	}
						
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title><? echo "$title"; ?> </title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="screen" href="<? echo "$siteurl"; ?>/style.css" />
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<? echo "$siteurl"; ?>/rss.php"  />
<meta name="generator" content="Dertyn <? echo "$version"; ?>" />
</head>
<body>
<div id="wrap">

<div id="header">
<h2><b><a href="<? echo "$siteurl"; ?>"><? echo "$sitename"; ?></a></b></h2>
</div>

<div id="sidebar">
<?php

	printSearchForm($numEntries,$pagenum);
	echo "<ul>\n";
	echo "<li>posts: $numOfEntries</li>\n";
	echo "<li><a href=\"$siteurl/comments.php\">comments</a>: $totalComments</li>\n";
	echo "<li><a href=\"$siteurl/rss.php\">rss</a></li>\n";
	if(!checkCookie()) {
		echo "<li><a href=\"$siteurl/login.php\">login</a></li>\n";
	}
	echo "</ul>\n";

	if(checkCookie()) {
		echo "admin menu\n";
		echo "<ul>\n";
		echo "<li><a href=\"$siteurl/post.php\">post</a></li>\n";
		echo "<li><a href=\"$siteurl/drafts.php\">drafts</a></li>\n";
		echo "<li><a href=\"$siturl/usermod.php\">password</a></li>\n";
		echo "<li><a href=\"$siteurl/settings.php\">settings</a></li>\n";
		echo "<li><a href=\"$siteurl/logout.php\">logout</a></li>\n";
		echo "</ul>\n";
	}

?>
</div>
<div id="main">
