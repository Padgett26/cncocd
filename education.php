<?php
$stmt = $db->prepare(
        "SELECT id,title,edutext,created FROM education ORDER BY title");
$stmt->execute();
$j = 1;
while ($row = $stmt->fetch()) {
    $id = $row['id'];
    $title = $row['title'];
    $edutext = nl2br(
            make_links_clickable(
                    html_entity_decode($row['edutext'], ENT_QUOTES)));
    $created = $row['created'];
    $j = $j + 1;
    echo <<<_END
        <div style="text-align:center; position:relative; top:0px; left:0px;"><a href="#" onclick="toggleview('s$j')" style="text-decoration:underline; font-weight:bold; font-size:1.5em; color:#$color1;">$title</a><br>
    _END;
    echo "<div id='s$j' style='display:none; text-align:justify;'><blockquote>$edutext</blockquote>";
    if (file_exists("/home/cncocd/public_html/pdf/edu" . $created . ".pdf")) {
        echo "<br><br><a href='pdf/edu$created.pdf' target='_BLANK' style='color:#$highLightColor; text-decoration:underline;'>Click here to view and print an application for this program.</a>";
    }
    echo "</div><br>";
    echo "<div style='height:2px; width:75px; margin:10px auto; background-color:#$color2;'></div>";
}
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#$color2;'>* * *</div>";

