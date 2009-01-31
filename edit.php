<?php
	include_once("header.php");

	$numEntries = getIndexNum();
	$cookie = $_COOKIE['dertyn'];
	$storedcookie = getCookie();

	$id = stripslashes($_GET['number']);
?>

<!-- YUI Editor Rendering -->

<script type="text/javascript">
	var myEditor = new YAHOO.widget.Editor('body', {
	height: '300px',
	width: '522px',
	dompath: true, //Turns on the bar at the bottom
	animate: true, //Animates the opening, closing and moving of Editor windows
	handleSubmit: true
	});
	myEditor.render();
</script>

<?php
        if(checkCookie()) {
		showEditForm($id);
        } else {
		echo "please <a href=\"login.php\">login</a>.";
	}

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
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

