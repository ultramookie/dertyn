<?php
	include_once("header.php");
	include_once("editor.php");

?>

<?php
	$numEntries = getIndexNum();
	$cookie = $_COOKIE['dertyn'];
	$storedcookie = getCookie();

        if( (checkCookie()) && ($_POST['checksubmit']) ) {
		$subject = strip_tags($_POST['subject']);
		$body = $_POST['body'];
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

