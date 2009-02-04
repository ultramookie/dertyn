<?php
	include_once("header.php");
	
	showEntriesIndex();

	echo "<div class=\"bottomnav\">( <a href=\"" . $siteUrl . "\">home</a> ) <a href=\"" . $siteUrl  . "archive.php?pagenum=2\">next >></a></div>";
	
	include_once("footer.php");
?>

