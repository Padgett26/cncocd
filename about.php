<?php
if (file_exists("img/us.jpg")) {
    echo "<div style='margin:5px; border:1px solid #f89422; padding:10px;'><img src='img/us.jpg' alt='' /></div>";
}
$stmt = $db->prepare("SELECT * FROM about");
$stmt->execute();
$row = $stmt->fetch();
$title = $row['title'];
$text = nl2br(make_links_clickable(html_entity_decode($row['text'], ENT_QUOTES),$db));
echo "<div style='margin-top:10px; text-align:center; font-size:2em; font-weight:bold; color:#$color1;'>$title</div>";
echo "<div style='margin-top:10px; text-align:justify; font-size:1em; font-weight:normal;'>$text</div>";
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#$color2;'>* * *</div>";