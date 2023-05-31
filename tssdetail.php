<?php

$stmt = $db->prepare("SELECT orderdate,startdate FROM sitesettings");
$stmt->execute();
$rowdate = $stmt->fetch();
$od = $rowdate['orderdate'];
$sd = $rowdate['startdate'];

$now = time();
$tss_yes = (($now >= $sd) && ($now <= $od)) ? "1" : "0";

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $stmt = $db->prepare("SELECT * FROM tss WHERE id=?");
  $stmt->execute(array($id));
  $row = $stmt->fetch();
  $name = $row['name'];
  $catagory = $row['catagory'];
  $price = $row['price'];
  $contsize = $row['contsize'];
  $dimension = nl2br($row['dimension']);
  $description = nl2br($row['description']);
  $pic1 = $row['pic1'];
  $pic2 = $row['pic2'];
  $site = $row['site'];
  $button = $row['button'];
  echo "<div style='text-align:center; font-size:1.5em; font-weight:bold; text-decoration:underline; color:#006600;'>$name</div>";
  if ($pic1) {
    echo "<div style='float:right; margin:20px;'><img src='tss/$pic1.jpg' style='max=width:300px; max-height:300px;' alt='' /></div>";
  }
  if ($tss_yes == "1") {
    if ($price != "") {
      if ($catagory == "24") {
        echo <<<_END
            <div style='text-align:left; font-size:1.25em; font-weight:bold;'>
              <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="$button">
                <table>
                  <tr>
                    <td><input type="hidden" name="on0" value="Bundles of 25">Bundles of 25</td>
                  </tr>
                  <tr>
                    <td>
                      <select name="os0">
                        <option value="Qty 25">Qty 25 $25.00 USD</option>
                        <option value="Qty 50">Qty 50 $50.00 USD</option>
                        <option value="Qty 75">Qty 75 $75.00 USD</option>
                        <option value="Qty 100">Qty 100 $100.00 USD</option>
                        <option value="Qty 125">Qty 125 $125.00 USD</option>
                        <option value="Qty 150">Qty 150 $150.00 USD</option>
                      </select>
                    </td>
                  </tr>
                </table>
                <input type="hidden" name="currency_code" value="USD">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
              </form>
            </div>
_END;
      } else {
        echo <<<_END
            <div style='text-align:left; font-size:1.25em; font-weight:bold;'>
                <form target="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="$button">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_cart_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
                </form>
            </div>
            <div style='text-align:left; font-size:1.25em; font-weight:bold;'>Price: $$price</div>
_END;
      }
    }
  }
  echo "<div style='text-align:left; margin-top:20px; font-size:1.25em; font-weight:bold;'>Container Size: $contsize</div>";
  echo <<<_END
    <div style='text-align:justify;'><span style='font-size:1.25em; font-weight:bold'>Grown Dimensions:</span><br /><span style='font-size:1em;'>$dimension</span></div>
    <div style='text-align:justify;'><span style='font-size:1.25em; font-weight:bold'>Description:</span><br /><span style='font-size:1em;'>$description</span></div>
_END;
  if ($pic2) {
    echo "<div style='float:left; margin:20px;'><img src='tss/$pic2.jpg' style='max=width:300px; max-height:300px;' alt='' /></div>";
  }
  if ($site) {
    echo "<div style='text-align:center; font-size:1.25em; font-weight:bold;'><a href='$site' target='_BLANK'>...Click here for more information...</a></div>";
  }
} else {
  echo "I'm not sure which item you selected, please try again.";
}
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#006600;'>* * *</div>";
