<?php
$stmt = $db->prepare("SELECT orderdate,startdate FROM sitesettings");
$stmt->execute();
$row = $stmt->fetch();
$od = $row['orderdate'];
$sd = $row['startdate'];

if ($sd >= $time || $od <= $time) {
  echo "<div style='display: inline-block; width:60px;'>&nbsp;</div>";
}

echo ($page == 'csp') ? "<div class='selButton' style='vertical-align:top'><span>Cost Share Programs</span></div>" : "<a class='button1' style='vertical-align:top;' href='index.php?page=csp'><span>Cost Share Programs </span></a>";

if ($sd <= $time && $od >= $time) {
  echo "<a class='button1' style='vertical-align:top;' href='index.php?page=tss'><span>Tree and Shrub Sale </span></a>";
}

echo ($page == 'education') ? "<div class='selButton' style='vertical-align:top'><span>Education</span></div>" : "<a class='button1' style='vertical-align:top;' href='index.php?page=education'><span>Education </span></a>";
echo ($page == 'photos') ? "<div class='selButton' style='vertical-align:top'><span>Photo Gallery</span></div>" : "<a class='button1' style='vertical-align:top;' href='index.php?page=photos'><span>Photo Gallery </span></a>";
echo ($page == 'newsletter') ? "<div class='selButton' style='vertical-align:top'><span>Newsletter</span></div>" : "<a class='button1' style='vertical-align:top;' href='index.php?page=newsletter'><span>Newsletter </span></a>";
echo ($page == 'links') ? "<div class='selButton' style='vertical-align:top'><span>Links</span></div>" : "<a class='button1' style='vertical-align:top;' href='index.php?page=links'><span>Links </span></a>";

if ($login == '1') {
  echo ($page == 'admin') ? "<div class='selButton' style='vertical-align:top'><span>Admin</span></div>" : "<a class='button1' style='vertical-align:top;' href='index.php?page=admin'><span>Admin </span></a>";
  echo "<a class='button1' style='vertical-align:top;' href='index.php?page=home&logout=yes'><span>Log Out </span></a>";
} else {
  echo "<a class='button1' style='vertical-align:top;' href='index.php?page=admin'><span>Log In </span></a>";
}