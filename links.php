<?php
$stmt = $db->prepare("SELECT * FROM links ORDER BY title");
$stmt->execute();
$t = 1;
while ($row = $stmt->fetch()) {
    $title = $row['title'];
    $description = nl2br(make_links_clickable(html_entity_decode($row['description'], ENT_QUOTES),$db));;
    $site = $row['site'];
    if ($t >= 2) {
        echo "<div style='height:2px; width:250px; margin:20px auto; background-color:#$color2;'></div>";
    }
    echo "<div style='text-align:center;'><a href='$site' target='_BLANK' style='font-size:1.25em; color:#$color1; text-decoration:underline;'>$title</a></div>";
    echo "<div style='margin-top:20px; text-align:justify;'>$description</div>";
    $t++;
}
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#$color2;'>* * *</div>";

