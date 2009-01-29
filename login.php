<?php
include_once("db.php");
include_once("dertyn.php");

if (stripslashes($_POST['checksubmit'])) {
        $user = stripslashes($_POST['user']);
        $pass  = stripslashes($_POST['pass']);

	$logincheck = checkLogin($user,$pass);

	if ($logincheck == 0) {
		setLoginCookie($user);
		header("Location: $siteurl");
	}
}

include_once("header.php");

echo "<p>\n";

if (!(stripslashes($_POST['checksubmit']))) {
	showLoginform();
} else {
	if ($logincheck == 0) {
		echo "thanks for logging in $user!<br /><b>return to <a href='$siteurl'>$sitename</a></b>.";
	} else {
		$errmsg = $user . $pass;
		echo "login failed.  try again.";
		logerr($errmsg, "login");
	}
}

echo "</p>\n";

?>

<?php
	include_once("footer.php");
?>

