<?php
if (filter_input ( INPUT_POST, 'linkedit', FILTER_SANITIZE_STRING )) {
	$linkedit = filter_input ( INPUT_POST, 'linkedit', FILTER_SANITIZE_STRING );
	$stmt = $db->prepare ( "SELECT * FROM links WHERE id=?" );
	$stmt->execute ( array (
			$linkedit
	) );
	$row = $stmt->fetch ();
	$title = $row ['title'];
	$description = html_entity_decode ( $row ['description'], ENT_QUOTES );
	$site = $row ['site'];
	echo "<form method='post' action='index.php?page=admin'>Link title:<br><input type='text' name='title' value='$title' maxlength='120' /><br><br>";
	echo "Description:<br><textarea name='description'>$description</textarea><br><br>";
	echo "Site address:<br><input type='text' name='site' value='$site' maxlength='100' /><br><br>";
	if ($linkedit != "new") {
		echo "<input type='checkbox' name='linkdelete' value='1' /> Delete this link.  This cannot be undone.<br><br>";
	}
	echo "<input type='hidden' name='linkupload' value='$linkedit' /><input type='submit' value=' Upload ' /></form>";
}

if (filter_input ( INPUT_POST, 'linkupload', FILTER_SANITIZE_STRING )) {
	$linkupload = filter_input ( INPUT_POST, 'linkupload', FILTER_SANITIZE_STRING );
	$title = filter_input ( INPUT_POST, 'title', FILTER_SANITIZE_STRING );
	$a2 = htmlEntities ( trim ( $_POST ['description'] ), ENT_QUOTES );
	$description = filter_var ( $a2, FILTER_SANITIZE_STRING );
	$site = filter_input ( INPUT_POST, 'site', FILTER_SANITIZE_URL );
	$linkdelete = filter_input ( INPUT_POST, 'linkdelete', FILTER_SANITIZE_NUMBER_INT );
	if ($linkupload == "new") {
		$stmt = $db->prepare ( "INSERT INTO links VALUES" . "(NULL,?,?,?,'0')" );
		$stmt->execute ( array (
				$title,
				$description,
				$site
		) );
	}
	if ($linkdelete == "1") {
		$stmt = $db->prepare ( "DELETE FROM links WHERE id=?" );
		$stmt->execute ( array (
				$linkupload
		) );
	} else {
		$stmt = $db->prepare ( "UPDATE links SET title=?,description=?,site=? WHERE id=?" );
		$stmt->execute ( array (
				$title,
				$description,
				$site,
				$linkupload
		) );
	}
}

if (filter_input ( INPUT_POST, 'pickuser', FILTER_SANITIZE_STRING )) {
	$user = filter_input ( INPUT_POST, 'pickuser', FILTER_SANITIZE_STRING );
	$stmt = $db->prepare ( "SELECT * FROM users WHERE id=?" );
	$stmt->execute ( array (
			$user
	) );
	$row = $stmt->fetch ();
	$userid = $row ['userid'];
	$name = $row ['name'];
	echo <<<_END
	    <form method='post' action='index.php?page=admin'>
	        <input type='text' name='userid' value='$userid' size='40' maxlength='40' /> Login id<br>
	        <input type='password' name='pwd' size='40' maxlength='40' /> Password (only fill in if new user, or changing password).<br>
	        <input type='text' name='name' value='$name' size='40' maxlength='40' /> Full name<br>
	_END;
	if ($user != "new") {
		echo "<input type='checkbox' name='deluser' value='1' /> Check here to delete user. Cannot be undone.<br>";
	}
	echo <<<_END
	        <input type='hidden' name='uploaduser' value='$user' /><input type='submit' value=' -Upload- ' />
	    </form>
	_END;
}

if (filter_input ( INPUT_POST, 'uploaduser', FILTER_SANITIZE_STRING )) {
	$id = filter_input ( INPUT_POST, 'uploaduser', FILTER_SANITIZE_STRING );
	$userid = filter_input ( INPUT_POST, 'userid', FILTER_SANITIZE_STRING );
	$pwd = filter_input ( INPUT_POST, 'pwd', FILTER_SANITIZE_STRING );
	$name = filter_input ( INPUT_POST, 'name', FILTER_SANITIZE_STRING );
	$deluser = (filter_input ( INPUT_POST, 'deluser', FILTER_SANITIZE_NUMBER_INT ) == "1") ? "1" : "0";
	if ($deluser == "1") {
		$stmt = $db->prepare ( "DELETE FROM users WHERE id=?" );
		$stmt->execute ( array (
				$id
		) );
		echo "<br><br>The user has been updated.";
	} else {
		if ($id == "new") {
			if ($userid == "" || $pwd == "" || $name == "") {
				echo "All fields must be filled out: <form method='post' action='index.php?page=admin'><input type='hidden' name='pickuser' value='$id' /><input type='submit' value=' -Continue- ' /></form>";
			} else {
				$newSalt = rand ( 100000, 999999 );
				$hidepwd = hash ( 'sha512', ($salt . $pwd), FALSE );
				$stmt = $db->prepare ( "INSERT INTO users VALUES" . "(NULL,?,?,?,?,'1')" );
				$stmt->execute ( array (
						$userid,
						$hidepwd,
						$name,
						$newSalt
				) );
				echo "<br><br>The user has been added to the database.";
			}
		} else {
			$stmt1 = $db->prepare ( "SELECT salt FROM users WHERE id = ?" );
			$stmt1->execute ( array (
					$id
			) );
			$row1 = $stmt1->fetch ();
			$salt = $row1 ['salt'];
			$hidepwd = hash ( 'sha512', ($salt . $pwd), FALSE );
			if ($userid) {
				$stmt = $db->prepare ( "UPDATE users SET userid=? WHERE id=?" );
				$stmt->execute ( array (
						$userid,
						$id
				) );
			}
			if (filter_input ( INPUT_POST, 'pwd', FILTER_SANITIZE_STRING )) {
				$stmt = $db->prepare ( "UPDATE users SET pwd=? WHERE id=?" );
				$stmt->execute ( array (
						$hidepwd,
						$id
				) );
			}
			if ($name) {
				$stmt = $db->prepare ( "UPDATE users SET name=? WHERE id=?" );
				$stmt->execute ( array (
						$name,
						$id
				) );
			}
			echo "<br><br>The user has been updated.";
		}
	}
}

if (filter_input ( INPUT_POST, 'tssyup', FILTER_SANITIZE_NUMBER_INT )) {
	$id = filter_input ( INPUT_POST, 'tssyup', FILTER_SANITIZE_NUMBER_INT );
	$taxrate = filter_input ( INPUT_POST, 'taxrate', FILTER_SANITIZE_STRING );
	$orderday = filter_input ( INPUT_POST, 'orderday', FILTER_SANITIZE_NUMBER_INT );
	$puday = filter_input ( INPUT_POST, 'puday', FILTER_SANITIZE_NUMBER_INT );
	$startday = filter_input ( INPUT_POST, 'startday', FILTER_SANITIZE_NUMBER_INT );
	$ordermonth = filter_input ( INPUT_POST, 'ordermonth', FILTER_SANITIZE_NUMBER_INT );
	$pumonth = filter_input ( INPUT_POST, 'pumonth', FILTER_SANITIZE_NUMBER_INT );
	$startmonth = filter_input ( INPUT_POST, 'startmonth', FILTER_SANITIZE_NUMBER_INT );
	$tssyear = filter_input ( INPUT_POST, 'year', FILTER_SANITIZE_NUMBER_INT );
	$startdate = mktime ( 0, 0, 0, $startmonth, $startday, $tssyear );
	$orderdate = mktime ( 16, 30, 0, $ordermonth, $orderday, $tssyear );
	$pudate = mktime ( 12, 0, 0, $pumonth, $puday, $tssyear );
	$stmt = $db->prepare ( "UPDATE sitesettings SET orderdate=?,pudate=?,tssyear=?,startdate=?,taxrate=? WHERE id=?" );
	$stmt->execute ( array (
			$orderdate,
			$pudate,
			$tssyear,
			$startdate,
			$taxrate,
			$id
	) );
	echo "Settings updated.";
}

if (filter_input ( INPUT_POST, 'newsitem', FILTER_SANITIZE_NUMBER_INT )) {
	$newsitem = filter_input ( INPUT_POST, 'newsitem', FILTER_SANITIZE_NUMBER_INT );
	$stmt = $db->prepare ( "SELECT * FROM news WHERE id=?" );
	$stmt->execute ( array (
			$newsitem
	) );
	$row = $stmt->fetch ();
	$created = $row ['created'];
	$title = $row ['title'];
	$newstext = html_entity_decode ( $row ['newstext'], ENT_QUOTES );
	echo "<form method='post' action='index.php?page=admin'>";
	if ($row ['created']) {
		echo "<input type='checkbox' name='makenew' value='1' id='makenew' /><label for='makenew'>Reset the created date to current date.</label><br><br>";
	}
	echo "Title: <input type='text' name='title' value='$title' size='80' maxlength='80' /><br><br>";
	echo "Content:<br><textarea name='newstext' rows='10' cols='60'>$newstext</textarea><br><br>";
	if ($row ['created']) {
		echo "<input type='checkbox' name='deletenews' value='1' id='deletenews' /><label for='deletenews'>Delete this news item. This cannot be undone.</label><br><br>";
	}
	echo "<input type='hidden' name='editnews' value='$newsitem' /><input type='hidden' name='created' value='$created' /><input type='submit' value=' -Submit- ' /></form>";
}

if (filter_input ( INPUT_POST, 'editnews', FILTER_SANITIZE_STRING )) {
	$makenew = filter_input ( INPUT_POST, 'makenew', FILTER_SANITIZE_NUMBER_INT );
	$title = filter_input ( INPUT_POST, 'title', FILTER_SANITIZE_STRING );
	$a2 = htmlEntities ( trim ( $_POST ['newstext'] ), ENT_QUOTES );
	$newstext = filter_var ( $a2, FILTER_SANITIZE_STRING );
	$deletenews = (filter_input ( INPUT_POST, 'deletenews', FILTER_SANITIZE_NUMBER_INT ) == 1) ? 1 : 0;
	$id = filter_input ( INPUT_POST, 'editnews', FILTER_SANITIZE_STRING );
	$newscreated = filter_input ( INPUT_POST, 'created', FILTER_SANITIZE_NUMBER_INT );
	$created = ($makenew == '1') ? time () : $newscreated;
	if ($id == "new") {
		$created = time ();
		$stmt = $db->prepare ( "INSERT INTO news VALUES" . "(NULL,?,?,?,'0','0')" );
		$stmt->execute ( array (
				$created,
				$title,
				$newstext
		) );
		echo "<br><br>News item added.";
	} else {
		if ($deletenews == 1) {
			$stmt = $db->prepare ( "DELETE FROM news WHERE id=?" );
			$stmt->execute ( array (
					$id
			) );
			echo "<br><br>News item deleted.";
		} else {
			$stmt = $db->prepare ( "UPDATE news SET created=?,title=?,newstext=? WHERE id=?" );
			$stmt->execute ( array (
					$created,
					$title,
					$newstext,
					$id
			) );
			echo "<br><br>News item updated.";
		}
	}
}

if (filter_input ( INPUT_POST, 'cspitem', FILTER_SANITIZE_STRING )) {
	$cspitem = filter_input ( INPUT_POST, 'cspitem', FILTER_SANITIZE_STRING );
	$stmt = $db->prepare ( "SELECT * FROM csp WHERE id=?" );
	$stmt->execute ( array (
			$cspitem
	) );
	$row = $stmt->fetch ();
	$deadline = $row ['deadline'];
	$title = $row ['title'];
	$csptext = html_entity_decode ( $row ['csptext'], ENT_QUOTES );
	if ($cspitem == "new") {
		$created = time ();
	} else {
		$created = $row ['created'];
	}
	$csppdf = file_exists ( "pdf/csp" . $created . ".pdf" ) ? "1" : "0";
	echo "<form method='post' action='index.php?page=admin' enctype='multipart/form-data'>";
	echo "Title: <input type='text' name='title' value='$title' size='80' maxlength='80' /><br><br>";
	echo "Deadline: <input type='text' name='deadline' value='$deadline' size='30' maxlength='30' /><br><br>";
	echo "Content:<br><textarea name='csptext' rows='10' cols='60'>$csptext</textarea><br><br>";
	if ($csppdf == "1") {
		echo "There is a pdf application currently installed for this program.<br><br>";
	}
	echo "If you would like to upload a pdf application fo this program:<br><input type='file' name='newcsppdf' /><br><br>";
	if ($cspitem != "new") {
		echo "<input type='checkbox' name='deletecsp' value='1' id='deletecsp' /><label for='deletecsp'>Delete this cost share program. This cannot be undone.</label><br><br>";
	}
	echo "<input type='hidden' name='editcsp' value='$cspitem' /><input type='hidden' name='created' value='$created' /><input type='submit' value=' -Submit- ' /></form>";
}

if (filter_input ( INPUT_POST, 'editcsp', FILTER_SANITIZE_STRING )) {
	$title = filter_input ( INPUT_POST, 'title', FILTER_SANITIZE_STRING );
	$a2 = htmlEntities ( trim ( $_POST ['csptext'] ), ENT_QUOTES );
	$csptext = filter_var ( $a2, FILTER_SANITIZE_STRING );
	$deletecsp = (filter_input ( INPUT_POST, 'deletecsp', FILTER_SANITIZE_STRING ) == 1) ? 1 : 0;
	$id = filter_input ( INPUT_POST, 'editcsp', FILTER_SANITIZE_STRING );
	$created = filter_input ( INPUT_POST, 'created', FILTER_SANITIZE_STRING );
	$deadline = filter_input ( INPUT_POST, 'deadline', FILTER_SANITIZE_STRING );
	if ($id == "new") {
		$stmt = $db->prepare ( "INSERT INTO csp VALUES" . "(NULL,?,?,?,?,'0')" );
		$stmt->execute ( array (
				$deadline,
				$title,
				$csptext,
				$created
		) );
		echo "<br><br>Cost share program added.";
	} else {
		if ($deletecsp == "1") {
			$stmt = $db->prepare ( "DELETE FROM csp WHERE id='$id'" );
			$stmt->execute ( array (
					$id
			) );
			if (file_exists ( "pdf/csp" . $created . ".pdf" ))
				unlink ( "pdf/csp" . $created . ".pdf" );
			echo "<br><br>Cost share program deleted.";
		} else {
			$stmt = $db->prepare ( "UPDATE csp SET deadline=?,title=?,csptext=? WHERE id=?" );
			$stmt->execute ( array (
					$deadline,
					$title,
					$csptext,
					$id
			) );
			echo "<br><br>Cost share program updated.";
		}
	}
}

if (filter_input ( INPUT_POST, 'eduitem', FILTER_SANITIZE_STRING )) {
	$eduitem = filter_input ( INPUT_POST, 'eduitem', FILTER_SANITIZE_STRING );
	$stmt = $db->prepare ( "SELECT * FROM education WHERE id=?" );
	$stmt->execute ( array (
			$eduitem
	) );
	$row = $stmt->fetch ();
	$title = $row ['title'];
	$edutext = html_entity_decode ( $row ['edutext'], ENT_QUOTES );
	if ($eduitem == "new") {
		$created = time ();
	} else {
		$created = $row ['created'];
	}
	$edupdf = file_exists ( "/home/cncocd/public_html/pdf/edu" . $created . ".pdf" ) ? "1" : "0";
	echo "<form method='post' action='index.php?page=admin' enctype='multipart/form-data'>";
	echo "Title: <input type='text' name='title' value='$title' size='80' maxlength='80' /><br><br>";
	echo "Content:<br><textarea name='edutext' rows='10' cols='60'>$edutext</textarea><br><br>";
	if ($edupdf == "1") {
		echo "There is a pdf application currently installed for this program.<br><br>";
	}
	echo "If you would like to upload a pdf application fo this program:<br><input type='file' name='newedupdf' /><br><br>";
	if ($eduitem != "new") {
		echo "<input type='checkbox' name='deleteedu' value='1' id='deleteedu' /><label for='deleteedu'>Delete this education program. This cannot be undone.</label><br><br>";
	}
	echo "<input type='hidden' name='editedu' value='$eduitem' /><input type='hidden' name='created' value='$created' /><input type='submit' value=' -Submit- ' /></form>";
}

if (filter_input ( INPUT_POST, 'editedu', FILTER_SANITIZE_STRING )) {
	$title = filter_input ( INPUT_POST, 'title', FILTER_SANITIZE_STRING );
	$a2 = htmlEntities ( trim ( $_POST ['edutext'] ), ENT_QUOTES );
	$edutext = filter_var ( $a2, FILTER_SANITIZE_STRING );
	$created = filter_input ( INPUT_POST, 'created', FILTER_SANITIZE_NUMBER_INT );
	$deleteedu = (filter_input ( INPUT_POST, 'deleteedu', FILTER_SANITIZE_NUMBER_INT ) == 1) ? 1 : 0;
	$id = filter_input ( INPUT_POST, 'editedu', FILTER_SANITIZE_STRING );
	if ($id == "new") {
		$stmt = $db->prepare ( "INSERT INTO education VALUES" . "(NULL,?,?,?,'0')" );
		$stmt->execute ( array (
				$title,
				$edutext,
				$created
		) );
		echo "<br><br>Education program added.";
	} else {
		if ($deleteedu == "1") {
			$stmt = $db->prepare ( "DELETE FROM education WHERE id=?" );
			$stmt->execute ( array (
					$id
			) );
			if (file_exists ( "/home/cncocd/public_html/pdf/edu" . $created . ".pdf" ))
				unlink ( "/home/cncocd/public_html/pdf/edu" . $created . ".pdf" ) or die ();
			echo "<br><br>Education program deleted.";
		} else {
			$stmt = $db->prepare ( "UPDATE education SET title=?,edutext=? WHERE id=?" );
			$stmt->execute ( array (
					$title,
					$edutext,
					$id
			) );
			echo "<br><br>Education program updated.";
		}
	}
}

if (filter_input ( INPUT_POST, 'updateaddy', FILTER_SANITIZE_NUMBER_INT )) {
	$id = filter_input ( INPUT_POST, 'updateaddy', FILTER_SANITIZE_NUMBER_INT );
	$phone = filter_input ( INPUT_POST, 'phone', FILTER_SANITIZE_STRING );
	$addy = filter_input ( INPUT_POST, 'address', FILTER_SANITIZE_STRING );
	$email = filter_input ( INPUT_POST, 'email', FILTER_SANITIZE_STRING );
	$stmt = $db->prepare ( "UPDATE sitesettings SET phone=?,address=?,email=? WHERE id=?" );
	$stmt->execute ( array (
			$phone,
			$addy,
			$email,
			$id
	) );
	echo "<br><br>Info updated...";
}

if (filter_input ( INPUT_POST, 'homeup', FILTER_SANITIZE_NUMBER_INT )) {
	$id = filter_input ( INPUT_POST, 'homeup', FILTER_SANITIZE_NUMBER_INT );
	$title = filter_input ( INPUT_POST, 'hometitle', FILTER_SANITIZE_STRING );
	$a2 = htmlEntities ( trim ( $_POST ['hometext'] ), ENT_QUOTES );
	$text = filter_var ( $a2, FILTER_SANITIZE_STRING );
	$stmt = $db->prepare ( "UPDATE home SET title=?,text=? WHERE id=?" );
	$stmt->execute ( array (
			$title,
			$text,
			$id
	) );
	echo "<br>The home page text has been changed.";
}

if (filter_input ( INPUT_POST, 'aboutup', FILTER_SANITIZE_NUMBER_INT )) {
	$id = filter_input ( INPUT_POST, 'aboutup', FILTER_SANITIZE_NUMBER_INT );
	$title = filter_input ( INPUT_POST, 'abouttitle', FILTER_SANITIZE_STRING );
	$a2 = htmlEntities ( trim ( $_POST ['abouttext'] ), ENT_QUOTES );
	$text = filter_var ( $a2, FILTER_SANITIZE_STRING );
	$stmt = $db->prepare ( "UPDATE about SET title=?,text=? WHERE id=?" );
	$stmt->execute ( array (
			$title,
			$text,
			$id
	) );
	echo "<br>The about us text has been changed.";
}