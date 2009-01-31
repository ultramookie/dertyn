<?php
	include_once("header.php");
	include_once("editor.php");

	$numEntries = getIndexNum();
	$cookie = $_COOKIE['dertyn'];
	$storedcookie = getCookie();

	$id = $_GET['number'];
?>

<?php
        if(checkCookie()) {
		showEditForm($id);
        } else {
		echo "please <a href=\"login.php\">login</a>.";
	}

        if( (checkCookie()) && ($_POST['checksubmit']) ) {
		$subject = strip_tags($_POST['subject']);
		$body = $_POST['body'];
		$draft = strip_tags($_POST['draft']);
		$updateID = strip_tags($_POST['id']);
		updateEntry($subject,$body,$updateID,$draft);
		echo " <img src=\"icon_accept.gif\" border=\"0\" /> entry updated. ";
        }

?>

<?php
	include_once("footer.php");
?>

