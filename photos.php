<?php
$start = (filter_input ( INPUT_GET, 'start', FILTER_SANITIZE_NUMBER_INT )) ? filter_input ( INPUT_GET, 'start', FILTER_SANITIZE_NUMBER_INT ) : 0;
$prev = $start - 15;
$next = $start + 15;

if ($login == "1") {
	if (filter_input ( INPUT_POST, 'newphoto', FILTER_SANITIZE_NUMBER_INT ) == 1) {
		$caption = filter_input ( INPUT_POST, 'newcaption', FILTER_SANITIZE_STRING );
		$created = $time;
		$image = $_FILES ["image"] ["tmp_name"];
		list ( $width, $height ) = (getimagesize ( $image ) != null) ? getimagesize ( $image ) : null;
		if ($width != null && $height != null) {
			$imageType = getPicType ( $_FILES ["image"] ['type'] );
			processPic ( $picname, $image, $imageType );
		}
		$stmt1 = $db->prepare ( "INSERT INTO photos VALUES" . "(NULL,?,?,?,'0','0')" );
		$stmt1->execute ( array (
				$caption,
				$created,
				$imageType
		) );
		$stmt2 = $db->prepare ( "SELECT id FROM photos WHERE caption=? AND created=?" );
		$stmt2->execute ( array (
				$caption,
				$created
		) );
		$row2 = $stmt2->fetch ();
		$picname = $row2 ['id'];
	}

	if (filter_input ( INPUT_POST, 'editphoto', FILTER_SANITIZE_NUMBER_INT ) == 1) {
		$caption = filter_input ( INPUT_POST, 'caption', FILTER_SANITIZE_STRING );
		$id = filter_input ( INPUT_POST, 'picname', FILTER_SANITIZE_NUMBER_INT );
		$stmt = $db->prepare ( "UPDATE photos SET caption=? WHERE id=?" );
		$stmt->execute ( array (
				$caption,
				$id
		) );
		$picname = $id;
	}

	if (filter_input ( INPUT_POST, 'deletephoto', FILTER_SANITIZE_NUMBER_INT ) == 1) {
		$delete = filter_input ( INPUT_POST, 'picname', FILTER_SANITIZE_NUMBER_INT );
		$stmt2 = $db->prepare ( "SELECT picExt FROM photos WHERE id=?" );
		$stmt2->execute ( array (
				$delete
		) );
		$row2 = $stmt2->fetch ();
		$picExt = $row2 ['picExt'];
		$stmt = $db->prepare ( "DELETE FROM photos WHERE id=?" );
		$stmt->execute ( array (
				$delete
		) );
		unlink ( "photos/$delete.$picExt" );
	}
}

echo "<table cellpadding='0px' cellspacing='0px' border='0px' width='100%'><tr><td>";
$stmt = $db->prepare ( "SELECT COUNT(*) FROM photos" );
$stmt->execute ();
$row = $stmt->fetch ();
$count = $row [0];
echo "<div style='text-align:center; font-weight:bold; text-decoration:none; margin-top:20px;'>";
if ($start != 0) {
	echo "<a href='index.php?page=photos&start=$prev'><img src='img/prev.png' alt='prev' style='float:left;' /></a>";
}
if ($start < ($count - 15)) {
	echo "<a href='index.php?page=photos&start=$next'><img src='img/next.png' alt='next' style='float:right;' /></a>";
}
echo "</div></td></tr>";

if ($login == "1") {
	echo "<tr><td>";
	echo "<form action='index.php?page=photos' method='post' enctype='multipart/form-data'>";
	echo "<div style='margin:40px 0px 10px 0px;'>Upload a new picture:<br><input type='file' name='image' /></div>";
	echo "<div style='margin:10px;'><textarea name='newcaption' maxlength='2000' rows='8' cols='40'></textarea><input type='hidden' name='newphoto' value='1' /><br><br><input type='submit' value=' Upload ' /></div>";
	echo "</form>";
	echo "<hr width='95%' />";
	echo "</td></tr>";
	$stmt = $db->prepare ( "SELECT * FROM photos ORDER BY created DESC LIMIT $start,15" );
	$stmt->execute ();
	while ( $row = $stmt->fetch () ) {
		$id = $row ['id'];
		$caption = $row ['caption'];
		$picExt = $row ['picExt'];
		echo "<tr><td>";
		echo "<form action='index.php?page=photos' method='post' enctype='multipart/form-data'>";
		echo "<div style='margin:40px 0px 10px 0px;'><img src='photos/$id.$picExt' alt='' style='padding:3px; border:2px solid #f89422; max-width:400px; max-height:400px;' /><br><br>Upload a new picture:<br><input type='file' name='image' /></div>";
		echo "<div style='margin:10px;'><textarea name='caption' maxlength='2000' rows='8' cols='40'>$caption</textarea><input type='hidden' name='editphoto' value='1' /><input type='hidden' name='picname' value='$id' /><br><br>";
		echo "If you wish to delete this picture and caption, check here: <input type='checkbox' name='deletephoto' value='1' />";
		echo "<br><br><input type='submit' value=' Upload ' /></div>";
		echo "</form>";
		echo "<hr width='95%' />";
		echo "</td></tr>";
	}
} else {
	echo "<tr><td style='text-align:center; font-size:.75em; color:#$color2;'>Click on a picture to view as a slide show</td></tr>";
	$stmt = $db->prepare ( "SELECT * FROM photos ORDER BY created DESC LIMIT $start,15" );
	$stmt->execute ();
	$j = 1;
	while ( $row = $stmt->fetch () ) {
		$id = $row ['id'];
		$caption = $row ['caption'];
		$picExt = $row ['picExt'];
		$j = $j + 1;
		if ($j % 2 == "1") {
			$float = "float:right; margin:30px 0px 10px 20px;";
		} else {
			$float = "float:left; margin:30px 20px 10px 0px;";
		}
		echo "<tr><td>";
		echo "<div style='$float'><a class='example-image-link' href='photos/$id.$picExt' data-lightbox='pic-set' data-title='$caption'><img class='example-image' src='photos/$id.$picExt' alt='' style='padding:3px; border:2px solid #$highLightColor; margin:10px; max-width:400px; max-height:400px;' /></a></div>";
		echo "<div style='$float vertical-align:middle; padding:20px; width:400px;'>$caption</div>";
		echo "</td></tr>";
	}
}
echo "<tr><td>";
echo "<div style='text-align:center; font-weight:bold; text-decoration:none; margin-top:20px;'>";
if ($start != 0) {
	echo "<a href='index.php?page=photos&start=$prev'><img src='img/prev.png' alt='prev' style='float:left;' /></a>";
}
if ($start < ($count - 15)) {
	echo "<a href='index.php?page=photos&start=$next'><img src='img/next.png' alt='next' style='float:right;' /></a>";
}
echo "</div></td></tr>";
?>
</table>
<script src="js/lightbox-plus-jquery.min.js"></script>
