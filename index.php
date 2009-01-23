<?php
	include_once("header.php");
?>

<?php
	$numEntries = getIndexNum();
	$cookie = $_COOKIE['mindof'];
	$storedcookie = getCookie();

        if(checkCookie()) {
		showUpdateForm();
        }

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
		$update = strip_tags($_POST['update']);
		addEntry($update);
		echo " <img src=\"icon_accept.gif\" border=\"0\" /> entry posted. ";
        }

	showEntriesIndex($numEntries);

	echo "<a href=\"" . $siteUrl  . "archive.php?pagenum=2\" class=\"box\">older &#187;</a>";
?>

<?php
	include_once("footer.php");
?>

