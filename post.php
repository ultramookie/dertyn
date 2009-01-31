<?php
	include_once("header.php");

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
	$numEntries = getIndexNum();
	$cookie = $_COOKIE['dertyn'];
	$storedcookie = getCookie();

        if( (checkCookie()) && ((stripslashes($_POST['checksubmit']))) ) {
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

