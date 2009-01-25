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
		if(strlen($subject) > 0) {
			addEntry($subject,$body);
			echo " <img src=\"icon_accept.gif\" border=\"0\" /> entry posted. ";
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

