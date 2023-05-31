<?php

// config.php
session_start ();

include "../globalFunctions.php";

$db = db_cccd();

if (filter_input ( INPUT_GET, 'logout', FILTER_SANITIZE_STRING ) == 'yes') {
	$_SESSION = array ();

	if (ini_get ( "session.use_cookies" )) {
		$params = session_get_cookie_params ();
		setcookie ( session_name (), '', time () - 42000, $params ["path"], $params ["domain"], $params ["secure"], $params ["httponly"] );
	}
	session_destroy ();
}

date_default_timezone_set ( 'America/Chicago' );
$time = time ();

$salt1 = "mk&*";
$salt2 = "^&gh";
$pagemsg = "";

if (filter_input ( INPUT_POST, 'login', FILTER_SANITIZE_NUMBER_INT ) == 1) {
	$user = filter_input ( INPUT_POST, 'userid', FILTER_SANITIZE_STRING );
	$pass = filter_input ( INPUT_POST, 'pass', FILTER_SANITIZE_STRING );
	$stmt1 = $db->prepare ( "SELECT salt FROM users WHERE userid = ?" );
	$stmt1->execute ( array (
			$user
	) );
	$row1 = $stmt1->fetch ();
	$salt = $row1 ['salt'];
	$hidepwd = hash ( 'sha512', ($salt . $pass), FALSE );
	$stmt = $db->prepare ( "SELECT id, name FROM users WHERE userid=? AND pwd=?" );
	$stmt->execute ( array (
			$user,
			$hidepwd
	) );
	$row = $stmt->fetch ();
	$userid = $row ['id'];
	$username = $row ['name'];
	if ($row ['name']) {
		$_SESSION ['login'] = "1";
		$_SESSION ['name'] = $username;
		$login = "1";
	} else {
		$pagemsg = "<div class='error'><b>Your information is incorrect.</b></div>";
	}
}

$username = (isset ( $_SESSION ['name'] )) ? $_SESSION ['name'] : "";

$login = (isset ( $_SESSION ['login'] )) ? $_SESSION ['login'] : "0";

$p = (filter_input ( INPUT_GET, 'page', FILTER_SANITIZE_STRING )) ? filter_input ( INPUT_GET, 'page', FILTER_SANITIZE_STRING ) : 'home';
$page = (file_exists ( $p . ".php" )) ? $p : "home";

$stmt = $db->prepare ( "SELECT address,phone,email,color1,color2,highLightColor FROM sitesettings" );
$stmt->execute ();
$row = $stmt->fetch ();
$addy = $row ['address'];
$phone = $row ['phone'];
$email = $row ['email'];
$color1 = $row ['color1'];
$color2 = $row ['color2'];
$highLightColor = $row ['highLightColor'];

if (filter_input ( INPUT_GET, 'remove', FILTER_SANITIZE_EMAIL )) {
	$rmemail = filter_input ( INPUT_GET, 'remove', FILTER_SANITIZE_EMAIL );
	$rmsec = filter_input ( INPUT_GET, 'sec', FILTER_SANITIZE_EMAIL );
	$stmt = $db->prepare ( "SELECT * FROM newsletter WHERE email=?" );
	$stmt->execute ( array (
			$rmemail
	) );
	while ( $row = $stmt->fetch () ) {
		$rmid = $row ['id'];
		$rmcreated = $row ['created'];
		if ($rmsec == md5 ( $salt1 . $rmcreated . $salt2 )) {
			$stmt = $db->prepare ( "DELETE FROM newsletter WHERE id=?" );
			$stmt->execute ( array (
					$rmid
			) );
			$pagemsg = "You have been removed from the newsletter mailing list.";
		}
	}
}