<?php
	include_once("header.php");

	$numEntries = getIndexNum();
	$cookie = $_COOKIE['dertyn'];
	$storedcookie = getCookie();

	$id = stripslashes($_GET['number']);

        if(checkCookie()) {
		showEditForm($id);
        } else {
		echo "please <a href=\"login.php\">login</a>.";
	}

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
		$subject = strip_tags($_POST['subject']);
		$body = strip_tags($_POST['body'],"<p><a><i><b><img><br><ul><li><pre><embed><object>");
		$draft = strip_tags($_POST['draft']);
		$updateID = strip_tags($_POST['id']);
		updateEntry($subject,$body,$updateID,$draft);
		echo " <img src=\"icon_accept.gif\" border=\"0\" /> entry updated. ";
        }

?>

<?php
	include_once("footer.php");
?>

