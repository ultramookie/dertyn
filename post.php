<?php
	include_once("header.php");
?>

<?php
	$numEntries = getIndexNum();
	$cookie = $_COOKIE['dertyn'];
	$storedcookie = getCookie();

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
		$subject = strip_tags($_POST['subject']);
		$body = strip_tags($_POST['body'],"<p><a><i><b><img><br><ul><li><pre><embed><object>");
		$draft = strip_tags($_POST['draft']);
		if(strlen($subject) > 0) {
			addEntry($subject,$body,$draft);
			echo "<p><img src=\"icon_accept.gif\" border=\"0\" /> entry posted.</p>";
		} else {
			echo "<p><b>Please enter a title!</b></p>";
			showUpdateForm($body);
		}

        } else if(checkCookie()) {
			showUpdateForm();
       	} else {
			echo "please <a href=\"login.php\">login</a>.";
	}

?>

<?php
	include_once("footer.php");
?>

