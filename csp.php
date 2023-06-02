<?php
$stmt = $db->prepare(
        "SELECT id,title,deadline,csptext,created FROM csp ORDER BY title");
$stmt->execute();
$j = 1;
while ($row = $stmt->fetch()) {
    $id = $row['id'];
    $title = $row['title'];
    $deadline = $row['deadline'];
    $csptext = nl2br(
            make_links_clickable(
                    html_entity_decode($row['csptext'], ENT_QUOTES)));
    $created = $row['created'];
    $j = $j + 1;
    echo <<<_END
        <br><br>
        <div style="text-align:center; position:relative; top:0px; left:0px;"><a href="#" onclick="toggleview('s$j')" style="text-decoration:underline; font-weight:bold; font-size:1.5em; color:#$color1;">$title</a><br><br>
    _END;
    if ($deadline) {
        echo "<span style='font-size:1em;'>Deadline: $deadline</span></div>";
    }
    echo "<div id='s$j' style='display:none; text-align:justify;'><blockquote>$csptext</blockquote>";
    if (file_exists("pdf/csp" . $created . ".pdf")) {
        echo "<br><br><a href='pdf/csp$created.pdf' target='_BLANK' style='color:#$highLightColor; text-decoration:underline;'>Click here to view and print an application for this program.</a>";
    }
    echo "</div><br>";
    echo "<div style='height:2px; width:250px; margin:10px auto; background-color:#$color2;'></div>";
}
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#$color2;'>* * *</div>";

