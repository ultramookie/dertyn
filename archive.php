<?php
	include_once("header.php");
?>

<?php

	$numEntries = getIndexNum();
	$siteUrl = getSiteUrl();

        if (!$_GET['pagenum']) {
                $pagenum = 1;
        } else {
                $pagenum = $_GET['pagenum'];
        }

	showEntriesArchive($numEntries,$pagenum);

	$prev = $pagenum-1;
	$pagenum++;

	echo "<div class=\"bottomnav\"><a href=\"" . $siteurl . "/archive.php?pagenum=$prev\"><< back</a> ( <a href=\"" . $siteUrl . "\">home</a> ) <a href=\"" . $siteUrl  . "/archive.php?pagenum=$pagenum\">next >></a></div>";
?>

<?php
	include_once("footer.php");
?>

