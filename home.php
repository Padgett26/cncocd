<?php
$substmt = $db->prepare("SELECT * FROM home");
$substmt->execute();
$subrow = $substmt->fetch();
$hometitle = $subrow['title'];
$hometext = nl2br(
        make_links_clickable(html_entity_decode($subrow['text'], ENT_QUOTES)));
echo "<div style='margin-top:0px; text-align:center; font-size:2em; font-weight:bold; color:#$color1;'>$hometitle</div>";
echo "<div style='margin-top:10px; text-align:justify; font-size:1em; font-weight:normal;'>$hometext</div>";
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#$color2;'>* * *</div>";
