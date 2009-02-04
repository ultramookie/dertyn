<?php
	include_once("header.php");
?>

<?php

	echo "<p class=\"subject\">Recent Comments</p>";

	$numEntries = getIndexNum();

        if (!$_GET['pagenum']) {
                $pagenum = 1;
        } else {
                $pagenum = $_GET['pagenum'];
        }

	showRecentComments($numEntries,$pagenum);

	$prev = $pagenum-1;
	$pagenum++;

	if($pagenum == 2) {
		echo "<div class=\"bottomnav\">( <a href=\"" . $siteUrl . "\">home</a> ) <a href=\"" . $siteUrl  . "/comments.php?pagenum=$pagenum\">next >></a></div>";
	} else {
		echo "<div class=\"bottomnav\"><a href=\"" . $siteurl . "/comments.php?pagenum=$prev\"><< back</a> ( <a href=\"" . $siteUrl . "\">home</a> ) <a href=\"" . $siteUrl  . "/comments.php?pagenum=$pagenum\">next >></a></div>";
	}

?>

<?php
	include_once("footer.php");
?>

