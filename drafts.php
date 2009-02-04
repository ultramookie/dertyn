<?php
	include_once("header.php");

	$siteUrl = getSiteUrl();
	
        if (!$_GET['pagenum']) {
                $pagenum = 1;
        } else {
                $pagenum = $_GET['pagenum'];
        }

	showDraftsIndex($pagenum);

	$prev = $pagenum-1;
	$pagenum++;

	if($pagenum == 2) {
		echo "<div class=\"bottomnav\">( <a href=\"$siteUrl\">home</a> ) <a href=\"" . $siteUrl  . "/drafts.php?pagenum=$pagenum\">next >></a></div>";
	} else {
		echo "<div class=\"bottomnav\"><a href=\"" . $siteUrl . "/drafts.php?pagenum=$prev\"><< back</a> ( <a href=\"$siteUrl\">home</a> ) <a href=\"" . $siteUrl  . "/drafts.php?pagenum=$pagenum\">next >></a></div>";
	}
	
	include_once("footer.php");
?>

