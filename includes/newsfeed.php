<?php
$stmt = $db->prepare("SELECT COUNT(*) FROM news");
$stmt->execute();
$row = $stmt->fetch();
$newsCount = $row[0];
if ($newsCount >= 1) {
    echo "<div style='position:relative; top:0px; left:0px; display:block; width:178px; border:1px solid #$highLightColor; border-radius:10px; -moz-border-radius:10px; padding:10px;'>";
    $stmt = $db->prepare("SELECT * FROM news ORDER BY created DESC LIMIT 5");
    $stmt->execute();
    $t = 1;
    while ($row = $stmt->fetch()) {
        $id = $row['id'];
        $created = $row['created'];
        $title = $row['title'];
        $date = date("F j, Y", $created);
        if ($t >= 2) {
            echo "<div style='height:2px; width:75px; margin:10px auto; background-color:#$color2;'></div>";
        }
        echo "<div style='text-align:center;'><a href='index.php?page=news&feed=$id' style='color:#$color1; font-size:1em;'>$title</a></div><div style='text-align:center; font-size:.75em;'>Posted on: $date</div>";
        $t++;
    }
    echo "<div style='text-align:center; margin:30px 0px 0px 0px;'><a href='index.php?page=news&feed=all' style='color:#$color2; font-weight:bold;'>See All</a></div></div>";
}