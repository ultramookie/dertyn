<?php

include_once("header.php");
include_once("db.php");
include_once("dertyn.php");

if (!($_POST['checksubmit'])) {
	showForgotform();
} else if ($_POST['checksubmit']) {
	$email = getEmail();
	$user = getUser();
	$postemail = $_POST['email'];
	$postuser = $_POST['user'];

	$errmsg = "user " . $postuser . ",email " . $postemail;
	$errmsg = "ruser " . $user . ",remail " . $email;

	if ( ( (strcmp($email,$postemail)) == 0) && ( (strcmp($user,$postuser)) == 0) ) {
		sendRandomPass($email);
	} else {
		echo "things didn't match.  <a href=\"forgot.php\">try again</a>!";
		logerr($errmsg, "forgot");
	}
}

?>

<?php
	include_once("footer.php");
?>

