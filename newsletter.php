<?php
$echoform = "1";
$nlname = "";
$nlemail = "";
if (filter_input ( INPUT_POST, 'nlsignup', FILTER_SANITIZE_NUMBER_INT ) == 1) {
	$nlname = filter_input ( INPUT_POST, 'nlname', FILTER_SANITIZE_STRING );
	$nlemail = filter_input ( INPUT_POST, 'nlemail', FILTER_SANITIZE_EMAIL );
	$created = time ();
	if ($nlname == "" || $nlemail == FALSE || $nlemail == NULL) {
		echo "Both fields are required to sign you up.";
	} else {
		$stmt = $db->prepare ( "INSERT INTO newsletter VALUES" . "(NULL,?,?,?)" );
		$stmt->execute ( array (
				$nlname,
				$nlemail,
				$created
		) );
		$echoform = "0";
		echo "<span style='font-size:1em; font-weight:bold; color:#$color1;'>Thank you for signing up for the Newsletter. You will be notified each time a newsletter is available.</span><br><br>";
	}
}
?>
<?php
if ($echoform == "1") {
	?>
    <div style="margin-top:0px;">
        <span style="font-size:2em; font-weight:bold; color:#<?php

echo $color1;
	?>;">Sign up for our newsletter.</span><br>
        <span style="font-size:1.25em; font-weight:bold; color:#<?php

echo $color2;
	?>;">Newsletters are distributed twice a year.</span><br><br>
        <form method="post" action="index.php?page=newsletter">
            What is your name?<br><input type="text" name="nlname" value="<?php

echo $nlname;
	?>" size="40" maxlength="40" /><br><br>
            What is your email?<br><input type="text" name="nlemail" value="<?php

echo $nlemail;
	?>" size="60" maxlength="60" /><br><br>
            <input type="hidden" name="nlsignup" value="1" /><input type="submit" value=" Sign me up " />
        </form>
    </div>
<?php

}
?>
<div style="margin-top:50px; text-align:center; font-size:2em; color:#<?php

echo $color1;
?>;">
    View our previously sent newsletters:<br><br>
    <?php
				$pages = array ();
				foreach ( new DirectoryIterator ( __DIR__ . "/newsletters" ) as $j ) {
					if (! $j->isDot ()) {
						$pages [] = "$j";
					}
				}

				sort ( $pages, SORT_NUMERIC );
				foreach ( $pages as $j ) {
					preg_match ( "/^([1-9][0-9]*)/", $j, $match );
					$date = $match [0];
					$textdate = date ( "M j, Y", $date );
					echo "<div style=''><a href='newsletters/$j' target='_BLANK'>$textdate</a></div>";
				}
				?>
</div>
<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#<?php

echo $color2;
?>;'>* * *</div>