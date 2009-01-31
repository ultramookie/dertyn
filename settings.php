<?php
        include_once("header.php");

	include_once("db.php");
	include_once("dertyn.php");

if ( (!$_POST['checksubmit']) && (checkCookie()) ) {
	showSettingsform();
} else if (checkCookie()) {

	$username = getUserName();
	$site = strip_tags($_POST['site']);
	$tagline = strip_tags($_POST['tagline']);
	$url = $_POST['url'];
	$numberIndex = $_POST['index'];
	$numberRSS = $_POST['rss'];
	$rewrite = $_POST['rewrite'];
        $user = $username;
        $pass  = $_POST['pass'];

        $logincheck = checkLogin($user,$pass);

	if ($logincheck == 0) {
  		changeSettings($site,$url,$numberIndex,$numberRSS,$rewrite,$tagline);
	} else {
		echo "the username and/or password you entered was wrong.  please <a href='settings.php'>try again</a>.";
	}

} else {
	echo "please <a href='login.php'>login</a> in order to change the site settings!";
}

        include_once("footer.php");
?>
