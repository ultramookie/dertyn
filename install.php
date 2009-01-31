<html>
<head>
<title>dertyn installation</title>
</head>
<body>
<h2>dertyn installation.</h2>
be sure you have moved config-example.php to config.php and changed all the right values.<br />
this is a one step installation process.<br />
please fill out the following information:<br /><br />
<?php

include_once("db.php");
include_once("dertyn.php");

	
if (!$_POST['checksubmit']) {
	showAddform();
} else {
	$user = $_POST['user'];
	$email = $_POST['email'];
	$newpass1 = $_POST['pass1'];
	$newpass2 = $_POST['pass2'];
	$site = strip_tags($_POST['site']);
	$tagline = strip_tags($_POST['tagline']);
	$url = $_POST['url'];

	if ((strcmp($newpass1,$newpass2)) == 0) {
       		addUser($user,$email,$newpass1,$site,$url,$tagline);
       	} else {
               	echo "either your password was typed wrong or your new passwords did not match.  <a href='". $_SERVER['PHP_SELF'] . "'>try again</a>";
       	}
}

?>
</body>
</html>
