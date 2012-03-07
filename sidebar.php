<div id="sidebar">
<?php

	/* printSearchForm($numEntries,$pagenum); */
	echo "<p>stuff</p>\n";
	echo "<ul>\n";
	echo "<li>posts: $numOfEntries</li>\n";
	echo "</ul>\n";
	echo "<p>feeds</p>\n";
	echo "<ul>\n";
	echo "<li><a href=\"$siteurl/atom.php\">atom</a></li>\n";
	echo "<li><a href=\"$siteurl/rss.php\">rss</a></li>\n";
	echo "</ul>\n";

	if(checkCookie()) {
		echo "<p>admin</p>\n";
		echo "<ul>\n";
		echo "<li><a href=\"$siteurl/post.php\">new post</a></li>\n";
		echo "<li><a href=\"$siteurl/drafts.php\">drafts</a></li>\n";
		echo "<li><a href=\"$siturl/usermod.php\">password</a></li>\n";
		echo "<li><a href=\"$siteurl/settings.php\">settings</a></li>\n";
		echo "<li><a href=\"$siteurl/logout.php\">logout</a></li>\n";
		echo "</ul>\n";
	}

?>
</div>
