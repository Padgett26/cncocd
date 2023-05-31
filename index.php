<?php
include "includes/config.php";
include "func.php";
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?php

								echo ($login == "1") ? "CN CO CD - Welcome $username" : "Cheyenne County Conservation District";
								?></title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <link rel='stylesheet' type='text/css' href='css/index.css' />
        <script src="includes/jquery-min.js"></script>
        <link rel="stylesheet" href="css/lightbox.min.css">
        <script>
            function toggleview(itm)
            {
                var itmx = document.getElementById(itm);
                if (itmx.style.display === "none")
                {
                    itmx.style.display = "block";
                } else
                {
                    itmx.style.display = "none";
                }
            }
        </script>
    </head>
    <body style="position:relative; top:0px; left:0px; margin:0px;">
        <?php

								include_once ("includes/analyticstracking.php")?>
        <table style='width:1200px; border:none; margin:0px auto; z-index:2;' cellspacing='0px'>
            <tr>
              <td colspan='2' style='vertical-align:top;'><a href='index.php?page=home'><img src='img/logo.png' style='margin:10px 10px; width:1180px;' /></a></td>
            </tr>
            <tr>
                <td style='width:200px; vertical-align:top;'>
                    <div style="color:#000000; text-align:center; font-size:.75em;"><?php

																				echo $phone . "<br /><br />" . $addy;
																				?></div>
                </td>
                <td style='vertical-align:middle;'>
                    <?php
																				include 'includes/menu.php';
																				?>
                </td>
            </tr>
            <tr>
                <td colspan='2' style='height:10px;'>&nbsp;</td>
            </tr>
            <tr>
                <td style='vertical-align:top;'>
                    <?php
																				include "includes/newsfeed.php";
																				?>
                </td>
                <td style='width:1000px; padding:0px 20px 20px 20px; vertical-align:top;'>
                    <?php
																				if ($pagemsg != "") {
																					echo $pagemsg . "<br /><br />";
																				}
																				include "$page.php";
																				?>
                </td>
            </tr>
            <tr>
                <td colspan='2' style='vertical-align:top;'>
                <?php
																include "includes/footer.php";
																?>
                </td>
            </tr>
        </table>
    </body>
</html>
