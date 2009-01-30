<?php

// dertyn main library
// steve "mookie" kong
// http://ultramookie.com
//
// licensed under gplv3
// http://www.gnu.org/licenses/gpl-3.0.html

error_reporting(E_ERROR | E_PARSE);

$sitename = getSiteName();
$tagline = getTagline();
$siteurl = getSiteUrl();
$indexNum = getIndexNum();
$rssNum = getRssNum();
$numOfEntries = getNumEntries();
$lastUpdatedAtom = getLastUpdatedAtom();
$username = getUser();

function logerr($msg,$from) {
	error_log("FAILED ($from): $msg", 0);
}

function showUpdateForm($body) {
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
	echo "Title:<br />";
        echo "<input type=\"text\" name=\"subject\" /><br />";
	echo "Posting:<br />";
	echo "<textarea cols=\"50\" rows=\"24\" name=\"body\">$body</textarea>";
	echo "<br />";
	echo "Save as draft? <input type=\"checkbox\" name=\"draft\" value=\"1\" />";
	echo "<br />";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"submit\" value=\"post\" />";
        echo "</form>";
}

function printSearchForm($numEntries,$pagenum) {
	$siteurl = getSiteUrl();
	echo "<p>\n";
        echo "<form action=\"$siteurl/search.php\" method=\"get\">";
        echo "<input type=\"text\" name=\"search\" />";
        echo "<input type=\"hidden\" name=\"numEntries\" value=\"$numEntries\">";
        echo "<input type=\"hidden\" name=\"pagenum\" value=\"$pagenum\">";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"submit\" value=\"find\" />";
        echo "</form>";
	echo "</p>\n";
}

function query($name,$params = array()) {

	// This function is based on the work of Ryan Grove.
	// His tutorial can be found here:
	// http://wonko.com/post/a_simple_and_elegant_phpmysql_web_application_framework_part_2_g

	$queriesXml = simplexml_load_file('db/queries.xml');
	$queries = array();

	foreach($queriesXml as $query) {
		$queries[(string)$query['name']] = (string)$query;
	}

	if(!isset($queries[$name])) {
		echo "Unknown query $name!\n";
	}

	$sql = $queries[$name];

	if(count($params)) {
		$formattedParams = array();

		// Add a ":" to each parameter name and make it safe
		foreach($params as $paramName => $paramValue) {
			if(!is_numeric($paramValue)) {
				if(is_null($paramValue)) {
					$paramValue = 'NULL';
				} else {
					$paramValue = "'" . mysql_real_escape_string($paramValue) . "'";
				}
			}

			$formattedParams[":$paramName"] = $paramValue;
		}

		$sql = strtr($sql,$formattedParams);
	}

	return mysql_query($sql);
}

function showSearchResults($num,$pnum,$search) {

        if($pnum == 1) {
                $offset = 0;
        } else {
                $offset = ($pnum - 1) * $num;
        }

	$params = array(
			'num' => $num,
			'offset' => $offset, 
			'search' => $search
	);

	$result = query("main.showSearchResults",$params);

	$numrows = mysql_num_rows($result);

	if($numrows > 0) {
        	while ($row = mysql_fetch_array($result)) {
			printEntry($row['id']);
       		}
	} else {
		echo "Search term $search not found.<br />";
		printSearchForm();
	}
}

function printComment($cid,$pid) {

	$params = array(
			'cid' => $cid
		);

	$result = query("comments.printComment",$params);

	while ($row = mysql_fetch_array($result)) {
		$name = $row['name'];
		$url = $row['url'];
		$comment =  nl2br($row['comment']);
		$date = $row['date'];

		if(strlen($url) > 0) {
			echo "<p class=\"commenter\"><a href=\"$url\" rel=\"nofollow\">$name</a> said on $date...</p>\n";
		} else {
			echo "<p class=\"commenter\">$name said on $date...</p>\n";
		}
		echo "<p class=\"comment\">$comment</p>\n";
		if($pid > 0) {
			$permalink = makePermaLink($pid);
			echo "about <a href=\"$permalink\"><b>this posting</b></a>...<br /><br />";
		}
        	if(checkCookie()) {
			echo "<a href=\"$siteurl/delete.php?number=$cid&type=comment\"><img src=\"$siteurl/page_delete.gif\" border=\"0\" /></a> ";
		}
	}
}

function printComments($pid) {

	$params = array(
			'pid' => $pid
	);
	
	$result = query("comments.printComments",$params);

	while ($row = mysql_fetch_array($result)) {
		$cid = $row['cid'];
		printComment($cid);
	}

}

function addComment($name,$url,$comment,$ipaddy,$pid) {

	$params = array(
			'name' => $name,
			'url' => $url,
			'comment' => $comment,
			'ipaddy' => $ipaddy,
			'pid' => $pid,
			'site' => $site
	);

	$status = query("comments.addComment",$params);
}

function printCommentForm($id,$name,$url,$comment) {
	$sitename = getSiteName();

	$id = mysql_real_escape_string($id);
	$name = mysql_real_escape_string($name);
	$url = mysql_real_escape_string($url);
	$comment = mysql_real_escape_string($comment);
	
	$front = "";
	$back = "";

	if (date('s') % 2) {
		$front = "xyz";
	} else {
		$back = "xyz";
	}

        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
        echo "Name:<br /><input type=\"text\" name=\"name\" value=\"$name\" /><br />\n";
        echo "URL:<br /><input type=\"text\" name=\"url\" value=\"$url\" /><br />\n";
	echo "Remove the \"xyz\":<br /><input type=\"text\" name=\"site\" value=\"" . $front . $sitename . $back . "\" /><br />\n";
	echo "Comment: <br />\n";
	echo "<textarea cols=\"50\" rows=\"10\" name=\"comment\">$comment</textarea>\n";
	echo "<p class=\"noseeum\">\n";
	echo "Don't type anything here unless you're an evil robot:<br />\n";
	echo "<input type=\"text\" id=\"captcha\" name=\"captcha\" maxlength=\"50\" />\n";
	echo "<br /><br />\n";
	echo "</p>\n";
        echo "<input type=\"hidden\" name=\"pid\" value=\"$id\">\n";
        echo "<input type=\"hidden\" name=\"ipaddy\" value=\"" . $_SERVER['REMOTE_ADDR'] . "\">\n";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">\n";
	echo "<br />";
        echo "<input type=\"submit\" name=\"submit\" value=\"post\" id=\"submitbutton1\">\n";
        echo "</form>";
}

function showEditForm($id) {

	$params = array(
			'id' => $id
		);

	$result = query("main.showEditForm",$params);

	$row = mysql_fetch_array($result);

	$subject = $row['subject'];
	$body = $row['body'];

        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
	echo "Title:<br />";
	echo "<input type=\"text\" name=\"subject\" value=\"$subject\" />";
	echo "<br />";
	echo "Posting:<br />";
	echo "<textarea cols=\"50\" rows=\"24\" name=\"body\">$body</textarea>";
	echo "<br />";
	echo "Save as draft? <input type=\"checkbox\" name=\"draft\" value=\"1\" />";
	echo "<br />";
        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"post\" id=\"submitbutton1\">";
        echo "</form>";
}

function addEntry($subject,$body,$draft) {
	$lowersubject = strtolower($subject);
        $slugdashes = preg_replace("/\s/","-",$lowersubject);
        $slug = ereg_replace("[^a-zA-Z0-9-]","",$slugdashes);
	$draft = mysql_real_escape_string($draft);

	$params = array(
			'slug' => $slug
		);

	$result = query("main.addEntryFindSlug",$params);
        $numrows = mysql_num_rows($result);

	if ($draft) {
		$published = 0;
	} else { 
		$published = 1;
	}

	if ($numrows > 0) {
		$date = date('Ymd-Gis');
		$slug = $slug . "-" . $date;
	}

	$params = array(
			'subject' => $subject,
			'body' => $body,
			'slug' => $slug,
			'published' => $published
		);

	$result = query("main.addEntry",$params);
}

function updateEntry($subject,$body,$id,$draft) {
	$draft = mysql_real_escape_string($draft);
	
	$params = array(
			'subject' => $subject,
			'body' => $body,
			'id' => $id
		);

	if ($draft) {
		$name = "main.updateEntryDraft";
	} else {
		$name = "main.updateEntryNotDraft";
	}

	
	$result = query($name,$params);
}

function showEntriesIndex() {

	$num = getIndexNum();

	$params = array( 'num' => $num );

        $result = query("main.showEntriesIndex",$params);

        while ($row = mysql_fetch_array($result)) {
		printEntry($row['id']);
        }
}

function showDraftsIndex() {

	$num = getIndexNum();
	
	$params = array( 'num' => $num );

        $result = query("main.showDraftsIndex",$params);
       
        while ($row = mysql_fetch_array($result)) {
		printEntry($row['id']);
        }
}

function showEntriesArchive($num,$pnum) {

        if($pnum == 1) {
                $offset = 1;
        } else {
                $offset = ($pnum-1) * $num;
        }

	$params = array(
			'num' => $num,
			'offset' => $offset
		);

        $result = query("main.showEntriesArchive",$params);
	
        while ($row = mysql_fetch_array($result)) {
		printEntry($row['id']);
        }
}

function showRecentComments($num,$pnum) {

        if($pnum == 1) {
                $offset = 0;
        } else {
                $offset = ($pnum-1) * $num;
        }
	
	$params = array(
			'num' => $num,
			'offset' => $offset
		);

        $result = query("comments.showRecentComments",$params);

        while ($row = mysql_fetch_array($result)) {
		printComment($row['cid'],$row['pid']);
        }
}

function getPid($slug) {

	$params = array( 'slug' => $slug ); 

	$result = query("main.getPid",$params);

	$row = mysql_fetch_array($result);

	return $row['id'];
}

function makePermaLink($id,$single) {

	$siteurl = getSiteUrl();
	$rewriteCheck = getrewriteCheck();

	$params = array( 'id' => $id ); 

	if (($rewriteCheck == 1) && ($single)) {

		$result = query("main.makePermaLinkSingle",$params);
        	
		$row = mysql_fetch_array($result);
		$month = $row['month'];
		$day = $row['day'];
		$year = $row['year'];
		$slug = $row['slug'];
		$permalink = "$siteurl/wayback/$year/$month/$day/$slug/";
	} else if ($rewriteCheck == 1) {
		
		$result = query("main.makePermaLink",$params);
        
		$row = mysql_fetch_array($result);
		$month = $row['month'];
		$day = $row['day'];
		$year = $row['year'];
		$slug = $row['slug'];
		$permalink = "$siteurl/wayback/$year/$month/$day/$slug/";
	} else {
		$permalink = $siteurl . "/entry.php?id=" . $id;
	}

	return $permalink;
}

function getLastUpdatedAtom() {

	$query = "select date_format(entrytime, '%Y-%m%d') as date, date_format(entrytime, '%T:%i:%S') as time from main order by entrytime desc limit 1";
       	$result = mysql_query($query);
	$row = mysql_fetch_array($result);

	$lastUpdate = $date . "T" . $time . "Z";
	return $lastUpdate;
}

function printEntry($id,$single) {

	$siteurl = getSiteUrl();
	$permalink = makePermaLink($id,$single);
	$rewriteCheck = getrewriteCheck();

	if (($rewriteCheck == 1) && ($single)) {
		$query = "select id,subject,body,date_format(entrytime, '%M %e, %Y') as date from main where slug = '$id'";
        	$result = mysql_query($query);
		$pid = getPid($id);
	} else {
		$query = "select id,subject,body,date_format(entrytime, '%M %e, %Y') as date from main where id = '$id'";
        	$result = mysql_query($query);
		$pid = $id;
	}

        $row = mysql_fetch_array($result);

	$commentCount = getNumComments($pid);

	$text = nl2br($row['body']);

	echo "\n";
        echo "<p class=\"subject\"><a href=\"" . $permalink . "\">" . $row['subject'] . "</a></p>";
	echo "\n";
	echo "<p class=\"timedate\">" . $row['date'] . " : <a href=\"" . $permalink . "#comments\">$commentCount comment(s)</a>";
	if(checkCookie()) {
		echo "<a href=\"$siteurl/edit.php?number=" . $row['id'] . "\"><img src=\"$siteurl/page_edit.gif\" border=\"0\" /></a> ";
		echo "<a href=\"$siteurl/delete.php?number=" . $row['id'] . "&type=post\"><img src=\"$siteurl/page_delete.gif\" border=\"0\" /></a> ";
	}
	echo "</p>";
	echo "\n";
        echo "<p class=\"entry\">" . $text . " </p>";
	echo "\n";
	echo "<hr />";
}

function printAtom($num) {

	$rssSummaryLen = 1024;
        $query = "select id,subject,body,date_format(entrytime, '%Y-%m%d') as date, date_format(entrytime, '%T:%i:%S') as time from main where published = '1' order by entrytime desc limit $num";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
		$permalink = makePermaLink($row['id']);
		$shortBody = strip_tags(substr($row['body'],0,$rssSummaryLen));
		echo "\t<entry>\n";
		echo "\t\t<title>" . $row['subject'] . "</title>\n";
		echo "\t\t<link href=\"$permalink\" />\n";
		echo "\t\t<id>" . $row['id'] . "</id>\n";
		echo "\t\t<updated>" . $row['date'] . "T" . $row['time'] . "Z" . "</updated>\n";
		echo "\t\t<summary>" . $shortBody . "...</summary>\n";
		echo "\t</entry>\n";
        }
}

function printRSS($num) {
	$rssSummaryLen = 1024;
        $query = "select id,subject,body,date_format(entrytime, '%a, %d %b %Y %H:%i:%s') as date from main where published = '1' order by entrytime desc limit $num";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
		$permalink = makePermaLink($row['id']);
		$shortBody = strip_tags(substr($row['body'],0,$rssSummaryLen));
		echo "\t<item>\n";
		echo "\t\t<title>" . $row['subject'] . "</title>\n";
		echo "\t\t<pubDate>" . $row['date'] . " PST</pubDate>\n";
		echo "\t\t<description>$shortBody...</description>\n";
		echo "\t\t<guid>$permalink</guid>\n";
		echo "\t\t<link>$permalink</link>\n";
		echo "\t</item>\n";
        }
}

function printCommentsRSS($num) {
	$rssSummaryLen = 1024;
	$subjectLen = 50;
        $query = "select cid,pid,name,url,comment,date_format(commenttime, '%a, %d %b %Y %H:%i:%s') as date from comments order by commenttime desc limit $num";
        $result = mysql_query($query);

        while ($row = mysql_fetch_array($result)) {
		$permalink = makePermaLink($row['pid']);
		$shortComment = strip_tags(substr($row['comment'],0,$rssSummaryLen));
		$subjComment = strip_tags(substr($row['comment'],0,$subjectLen));
		echo "\t<item>\n";
		echo "\t\t<title>$subjComment</title>\n";
		echo "\t\t<pubDate>" . $row['date'] . " PST</pubDate>\n";
		echo "\t\t<description>$shortComment..</description>\n";
		echo "\t\t<guid>" . $row['cid'] . "</guid>\n";
		echo "\t\t<link>$permalink#comments</link>\n";
		echo "\t</item>\n";
        }
}

function showLoginForm() {
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "user: <input type=\"text\" name=\"user\"><br />";
	echo "pass: <input type=\"password\" name=\"pass\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"login\">";
	echo "</form>";
	echo "<hr />";
	echo "<a href='forgot.php'>forgot password</a>";
}

function getSecret() {
        $query = "select secret from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['secret']);
}

function getCookie() {
        $query = "select cookie from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['cookie']);
}

function checkCookie() {
	$secret = getSecret();
	$cookie = $_COOKIE['dertyn'];
	$user = $_COOKIE['user'];
	$storedcookie = getCookie();

	$loggedin = 0;

	$test = sha1($user . $secret);

	if ( (strlen($cookie) > 0) && ($cookie == $storedcookie) && ($cookie == $test) ) {
		$loggedin = 1;
	}

	return $loggedin;
}

function getUserName() {
	if(checkCookie()) {
		$name = $_COOKIE['user'];
	} else {
		$name = "not logged in";
	}
	return $name;
}

function getNumEntries() {
	$query = "select count(id) from main where published = '1'";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

        return($row['count(id)']);
}

function getEmail() {
        $query = "select email from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['email']);
}

function getUser() {
        $query = "select name from user limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['name']);
}

function getSiteName() {
	$query = "select name from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['name']);
}

function getTagline() {
	$query = "select tagline from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['tagline']);
}

function getSubject($pid) {
	$query = "select subject from main where id = '$pid' limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['subject']);
}

function getSiteUrl() {
	$query = "select url from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return("http://" . $row['url']);
}

function getRawSiteURl() {
	$query = "select url from site limit 1";
	$result = mysql_query($query);

	$row = mysql_fetch_array($result);

	return($row['url']);
}

function getIndexNum() {
        $query = "select indexNum from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['indexNum']);
}

function getRssNum() {
        $query = "select rssNum from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['rssNum']);
}

function getNumComments($pid) {
	$query = "select count(cid) from comments where pid = '$pid'";
	$result = mysql_query($query);

        $row = mysql_fetch_array($result);

	return($row['count(cid)']);
}

function getTotalNumComments() {
	$query = "select count(cid) from comments";
	$result = mysql_query($query);

        $row = mysql_fetch_array($result);

	return($row['count(cid)']);
}

function setLoginCookie($user) {
		$secret = getSecret();
                $login = sha1($user . $secret);
                $expiry = time()+60*60*24*30;
		setcookie('user',$user,"$expiry");
                setcookie('dertyn',$login,"$expiry");

	        $query = "update user set cookie='$login' where name like '$user'";
        	$result = mysql_query($query);
}

function killCookie() {
	if(checkCookie()) {
		$expiry = time() - 4800;
		setcookie('user','',"$expiry");
		setcookie('dertyn','',"$expiry");
	}
}

function checkLogin($user,$pass) {
        $salt = substr("$user",0,2);
        $epass = crypt($pass,$salt);

	$query = "select * from user where name like '$user' and pass like '$epass'";
	$result = mysql_query($query);

	if (mysql_num_rows($result)==1) {
		return 0;
	} else {
		return 1;
	}
}

function showAddform() {
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "user: <input type=\"text\" name=\"user\"><br />";
	echo "email: <input type=\"text\" name=\"email\"><br />";
	echo "password: <input type=\"password\" name=\"pass1\"><br />";
	echo "password (again): <input type=\"password\" name=\"pass2\"><br />";
	echo "name of site: <input type=\"text\" name=\"site\"><br />";
	echo "tagline for site: <input type=\"text\" name=\"tagline\"><br />";
	echo "base url (without http://): <input type=\"text\" name=\"url\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"install\">";
	echo "</form>";
}

function getrewriteCheck() {
        $query = "select rewrite from site limit 1";
        $result = mysql_query($query);

        $row = mysql_fetch_array($result);

        return($row['rewrite']);
}

function showSettingsform() {
	$sitename = getSiteName();
	$tagline = getTagline();
	$rawsiteurl = getRawSiteUrl();
	$indexNum = getIndexNum();
	$rssNum = getRssNum();
	$rewriteCheck = getrewriteCheck();

	if($rewriteCheck == 1) {
		$checked = "checked";
	} else {
		$checked = "";
	}
	
	echo "<p><b>general site settings:</b></p>";
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
        echo "user: <input type=\"text\" name=\"user\"><br />";
        echo "pass: <input type=\"password\" name=\"pass\"><br />";
	echo "name of site: <input type=\"text\" name=\"site\" value=\"" . $sitename . "\"><br />";
	echo "tagline for site: <input type=\"text\" name=\"tagline\" value=\"" . $tagline . "\"><br />";
	echo "base url (without http://): <input type=\"text\" name=\"url\" value=\"" . $rawsiteurl . "\"><br />";
	echo "number of entries to display per page: <input type=\"text\" name=\"index\" value=\"" . $indexNum . "\"><br />";
	echo "number of entries to display in rss feed: <input type=\"text\" name=\"rss\" value=\"" . $rssNum . "\"><br />";
	echo "use mod_rewrite? (remove .htaccess if you uncheck this): <input type=\"checkbox\" name=\"rewrite\" value=\"1\" " .  $checked . "><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\" value=\"update\">";
	echo "</form>";


}

function showDelform($id,$type) {
	echo "hey! are you SURE you want to delete this entry?";
	$siteurl = getSiteUrl();
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"hidden\" name=\"id\" value=\"$id\">";
        echo "<input type=\"hidden\" name=\"type\" value=\"$type\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"YES\">";
	echo " <a href=\"$siteurl\">no</a>";
        echo "</form>";
}

function showForgotform() {
        echo "Please enter the following information to reset your password: <br />";
        echo "<form action=\"";
        echo $_SERVER['PHP_SELF'];
        echo "\"";
        echo " method=\"post\">";
        echo "user: <input type=\"text\" name=\"user\"><br />";
        echo "email: <input type=\"text\" name=\"email\"><br />";
        echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
        echo "<input type=\"submit\" name=\"submit\" value=\"Reset Password\">";
        echo "</form>";
}


function deleteEntry($id,$type) {
	if(ereg("^post",$type)) {
		$query = "delete from main where id='$id'";
		$result = mysql_query($query);
		echo "post " . $id . " deleted!";
	} else if (ereg("^comment",$type)) {
		$query = "delete from comments where cid='$id'";
		$result = mysql_query($query);
		echo "comment " . $id . " deleted!";
	}
}

function generateCode($length=16) {
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789";
        $code = "";
        $clen = strlen($chars) - 1;
        while (strlen($code) < $length) {
		$code .= $chars[mt_rand(0,$clen)];
        }
        return $code;
}

function showPasswordChangeform() {
	$username = getUserName();
	echo "changing password for ";
	echo $username;
	echo "<form action=\"";
	echo $_SERVER['PHP_SELF'];
	echo "\"";
	echo " method=\"post\">";
	echo "old pass: <input type=\"password\" name=\"oldpass\"><br />";
	echo "new pass: <input type=\"password\" name=\"newpass1\"><br />";
	echo "new pass (again): <input type=\"password\" name=\"newpass2\"><br />";
	echo "<input type=\"hidden\" name=\"checksubmit\" value=\"1\">";
	echo "<input type=\"submit\" name=\"submit\">";
	echo "</form>";
}

function changePass($user,$pass) {
	$email = getEmail();
        $salt = substr("$email",0,2);
        $epass = crypt($pass,$salt);

	$query = "update user set pass='$epass' where name like '$user'";
	$result = mysql_query($query);

	echo " <img src=\"icon_accept.gif\" border=\"0\" /> password has been updated!";
}

function changeSettings($site,$url,$numberIndex,$numberRSS,$rewrite,$tagline) {

        $site = mysql_real_escape_string($site);
        $tagline = mysql_real_escape_string($tagline);
        $url = mysql_real_escape_string($url);
        $numberIndex = mysql_real_escape_string($numberIndex);
        $numberRSS = mysql_real_escape_string($numberRSS);
        $rewrite = mysql_real_escape_string($rewrite);

	$query = "update site set name='$site', url='$url', indexNum='$numberIndex', rssNum='$numberRSS', rewrite='$rewrite', tagline='$tagline' limit 1";
	$result = mysql_query($query);

	echo "your settings have been updated!";

}

function addUser($user,$email,$pass,$site,$url,$tagline) {
        $salt = substr("$email",0,2);
        $epass = crypt($pass,$salt);

	$query = "select * from user";
	$status = mysql_query($query);

	if (mysql_num_rows($status) >= 1) {
		echo "already installed!";
	} else {
		$user = mysql_real_escape_string($user);
		$email = mysql_real_escape_string($email);
		$pass = mysql_real_escape_string($pass);
		$site = mysql_real_escape_string($site);
		$tagline = mysql_real_escape_string($tagline);
		$url = mysql_real_escape_string($url);
		
		$query = "create table user ( name varchar(30) NOT NULL, email varchar(30) NOT NULL, pass varchar(30) NOT NULL, secret varchar(6), cookie varchar(300) )";
		$status = mysql_query($query);

		$query = "create table main ( id int NOT NULL AUTO_INCREMENT, entrytime DATETIME NOT NULL, subject varchar(160) NOT NULL, body MEDIUMTEXT, slug varchar(160), published int DEFAULT '0', PRIMARY KEY (id), FULLTEXT(subject,body)); ";
		$status = mysql_query($query);
		
		$query = "create table comments ( cid int NOT NULL AUTO_INCREMENT, pid int NOT NULL, commenttime DATETIME NOT NULL, ip varchar(16), name varchar(40), url varchar(100), comment MEDIUMTEXT, PRIMARY KEY (cid)); ";
		$status = mysql_query($query);
		
		$query = "create table site ( name varchar(160) NOT NULL, url varchar(160) NOT NULL, indexNum int NOT NULL, rssNum int NOT NULL, rewrite int NOT NULL, tagline varchar(160) ); ";
		$status = mysql_query($query);
	
		$secret = generateCode();
	
		$query = "insert into user (name,email,pass,secret) values ('$user','$email','$epass','$secret')";
		$status = mysql_query($query);
	
		$query = "insert into site (name,url,indexNum,rssNum,rewrite,tagline) values ('$site','$url','10','10','1','$tagline')";
		$status = mysql_query($query);

		echo "dertyn installed!  thanks!";
	}
}

function sendRandomPass($email,$func) {
        $pass = generateCode();
	$salt = substr("$email",0,2);
	$epass = crypt($pass,$salt);

	$email = mysql_real_escape_string($email);
	
	$to = "$email";
	$from = "From: webmaster@ultramookie.com";
	$subject = "password";
	$body = "hi, your password is $pass. please login using your email address and the password.  feel free to change your password at anytime.";
	if (mail($to, $subject, $body, $from)) {
		if ((strcmp($func,"new")) == 0) {
			$query = "insert into user (email,pass) values ('$email','$epass')";
			$status = mysql_query($query);
		} else if ((strcmp($func,"lost")) == 0) {
			$query = "update user set pass='$epass' where email like '$email'";
			$status = mysql_query($query);
		} else {
			echo "nothing to do!";
		}

		echo "<p>Your new password has been sent!  <a href='login.php'>login</a> after you receive your password.</p>";
	} else {
		echo("<p>Message delivery failed...</p>");
	}
}

