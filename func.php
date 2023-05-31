<?php
function getPicType($imageType) {
	switch ($imageType) {
		case "image/gif" :
			$picExt = "gif";
			break;
		case "image/jpeg" :
			$picExt = "jpg";
			break;
		case "image/pjpeg" :
			$picExt = "jpg";
			break;
		case "image/png" :
			$picExt = "png";
			break;
		default :
			$picExt = "xxx";
			break;
	}
	return $picExt;
}
function processPic($imageName, $tmpFile, $picExt) {
	$folder = "photos";

	$saveto = "$folder/$imageName.$picExt";

	list ( $width, $height ) = (getimagesize ( $tmpFile ) != null) ? getimagesize ( $tmpFile ) : null;
	if ($width != null && $height != null) {
		$image = new Imagick ( $tmpFile );
		$image->thumbnailImage ( 800, 800, true );
		$image->writeImage ( $saveto );
	}
}
function processPdf($userId, $time, $pdf1or2, $file, $artId, $db) {
	$pdfName = $time + $pdf1or2;
	$saveto = "userPics/$userId/$pdfName.pdf";
	move_uploaded_file ( $file, $saveto );
	if (filesize ( "userPics/$userId/$pdfName.pdf" ) <= 1000) {
		unlink ( "userPics/$userId/$pdfName.pdf" );
	}
	if (file_exists ( "userPics/$userId/$pdfName.pdf" )) {
		$pdfstmt = $db->prepare ( "UPDATE articles SET pdf" . $pdf1or2 . "=? WHERE id=?" );
		$pdfstmt->execute ( array (
				$pdfName,
				$artId
		) );
	}
}
function deletePdf($userId, $pdf1or2, $artId, $db) {
	$stmt = $db->prepare ( "SELECT pdf" . $pdf1or2 . " FROM articles WHERE id=?" );
	$stmt->execute ( array (
			$artId
	) );
	$row = $stmt->fetch ();
	if (file_exists ( "userPics/$userId/" . $row [0] . ".pdf" )) {
		unlink ( "userPics/$userId/" . $row [0] . ".pdf" );
	}
	$stmt2 = $db->prepare ( "UPDATE articles SET pdf" . $pdf1or2 . "='0' WHERE id=?" );
	$stmt2->execute ( array (
			$artId
	) );
}
function make_links_clickable($text, $db) {
	$stmt = $db->prepare ( "SELECT color2 FROM sitesettings WHERE id='1'" );
	$stmt->execute ();
	$row = $stmt->fetch ();
	$color2 = $row ['color2'];
	return preg_replace ( '!(((f|ht)tp(s)?://)[-a-zA-Zа-яА-Я()0-9@:%_+.~#?&;//=]+)!i', "<a href='$1' target='_blank' style='color:#$color2; text-decoration:underline;'>$1</a>", $text );
}
function money($amt) {
	settype ( $amt, "float" );
	$fmt = new NumberFormatter ( 'en_US', NumberFormatter::CURRENCY );
	return $fmt->formatCurrency ( $amt, "USD" );
}