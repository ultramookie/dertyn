<?php
        include_once("header.php");
	include_once("db.php");
	include_once("dertyn.php");

if ((stripslashes(!$_POST['checksubmit'])) && (checkCookie()) ) {
	showPatternAddform();
} else if (checkCookie()) {
	$pattern = stripslashes($_POST['pattern']);
	addPattern($pattern);
} else {
	echo "please <a href='login.php'>login</a> in order to change the site settings!";
}

?>
</body>
</html>
