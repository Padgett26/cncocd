<?php
$feed = filter_input(INPUT_GET, 'feed', FILTER_SANITIZE_STRING);
$limit = time() - (26*7*24*60*60);
$where = ($feed == "all") ? "ORDER BY created DESC" : "WHERE id='$feed'";

$stmt = $db->prepare("SELECT * FROM news " . $where);
$stmt->execute();
$t = 1;
while ($row = $stmt->fetch()) {
    $id = $row['id'];
    $time = $row['created'];
    $created = date("F j, Y", $time);
    $title = $row['title'];
    $newstext = nl2br(make_links_clickable(html_entity_decode($row['newstext'], ENT_QUOTES),$db));
    if ($t >= 2) {
            echo "<div style='height:2px; width:50%; margin:40px auto; background-color:#$color2;'></div>";
        }
    echo "<div onclick='toggleview(\"news$id\")'><div style='text-align:center; font-size:1.5em; font-weight:bold; color:#$color1; cursor:pointer;'>$title</div>
        <div style='text-align:center; font-size:1em; font-weight:normal;'>Posted on $created</div></div>
        <div id='news$id' style='display:none; text-align:justify; font-size:1em; font-weight:normal; margin-top:20px;'><blockquote>$newstext</blockquote></div>";
    $t++;
}
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#$color2;'>* * *</div>";
?>
