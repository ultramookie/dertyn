<?php
	include_once("header.php");
?>

<?php
	$numEntries = getIndexNum();
	$cookie = $_COOKIE['durden'];
	$storedcookie = getCookie();

        if(checkCookie()) {
		showUpdateForm();
        }

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
		$subject = strip_tags($_POST['subject']);
		$body = strip_tags($_POST['body'],"<p><a><i><b><img><br><ul><li>");
		addEntry($subject,$body);
		echo " <img src=\"icon_accept.gif\" border=\"0\" /> entry posted. ";
        }

	showEntriesIndex($numEntries);

	echo "<a href=\"" . $siteUrl  . "archive.php?pagenum=2\" class=\"box\">older &#187;</a>";
?>

<?php
	include_once("footer.php");
?>

