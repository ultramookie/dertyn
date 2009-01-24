<?php
	include_once("header.php");
?>

<?php

	$numEntries = getIndexNum();

	if(!$_GET['search']) {
		printSearchForm();
	} else {
        	if (!$_GET['pagenum']) {
                	$pagenum = 1;
        	} else {
                	$pagenum = $_GET['pagenum'];
        	}

		$search = $_GET['search'];

		showSearchResults($numEntries,$pagenum,$search);

		$pagenum++;

		echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?pagenum=" . $pagenum . "\" class=\"box\">next &#187;</a>";
	}
?>

<?php
	include_once("footer.php");
?>

