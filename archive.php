<?php
	include_once("header.php");
?>

<?php

	$indexNum = getIndexNum();
	$numOfEntries = getNumEntries();
	$numEntries = getIndexNum();
	$siteUrl = getSiteUrl();

        if (!$_GET['pagenum']) {
                $pagenum = 1;
        } else {
                $temppagenum = $_GET['pagenum'];
                if (preg_match('/^[0-9]+$/',$temppagenum)) {
                        $pagenum = $temppagenum;
                } else {
                        $pagenum = 1;
                }
        }

	showEntriesArchive($numEntries,$pagenum);

	$prev = $pagenum-1;
	$pagenum++;

	$numPages = $numOfEntries / $indexNum;

	if ($pagenum > $numPages) {
		echo "<div class=\"bottomnav\"><a href=\"" . $siteUrl . "/archive.php?pagenum=$prev\"><< back</a> ( <a href=\"" . $siteUrl . "\">home</a> )</div>";
	} else {
		echo "<div class=\"bottomnav\"><a href=\"" . $siteUrl . "/archive.php?pagenum=$prev\"><< back</a> ( <a href=\"" . $siteUrl . "\">home</a> ) <a href=\"" . $siteUrl  . "/archive.php?pagenum=$pagenum\">next >></a></div>";
	}

?>

<?php
	include_once("footer.php");
?>

