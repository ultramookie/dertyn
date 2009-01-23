<?php
	include_once("header.php");
?>

<?php
	$numEntries = getIndexNum();
	$cookie = $_COOKIE['durden'];
	$storedcookie = getCookie();

        if(checkCookie()) {
		showUpdateForm();
        } else {
		echo "please <a href=\"login.php\">login</a>.";
	}

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
		$subject = strip_tags($_POST['subject']);
		$body = strip_tags($_POST['body'],"<p><a><i><b><img><br><ul><li>");
		addEntry($subject,$body);
		echo " <img src=\"icon_accept.gif\" border=\"0\" /> entry posted. ";
        }

?>

<?php
	include_once("footer.php");
?>

