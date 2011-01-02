<?php
        include_once("header.php");
?>

<?php

include_once("db.php");
include_once("dertyn.php");

echo "<a href=\"patternadd.php\">add pattern</a>";

if (checkCookie()) {
	showPatternform();
} else {
	echo "please <a href='login.php'>login</a> in order to change the site settings!";
}

?>
</body>
</html>
