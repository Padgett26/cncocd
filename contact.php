<?php
echo <<<_END
<div style='margin-top:0px; text-align:center;'>
<span style='font-size:3em; color:#$color1;'>Cheyenne County Conservation District</span><br /><br />
<span style='font-size:2em;'>Dani Holzwarth</span><br />
<span style='font-size:1.5em;'>District Manager</span><br /><br />
<span style='font-size:1.5em;'>$addy</span><br /><br />
<span style='font-size:1.5em;'>$phone</span><br /><br />
<a href="mailto:$email"><span style='font-size:1.5em; color:#$color1;'>$email</span></a><br /><br /></div>
<div style="margin-left:290px;">
<table width="200px">
    <tr><td align="center">
    <form action="http://www.mapquest.com/directions/main.adp" method="get" target="new">
    <div align="center">
    <input type="hidden" name="go" value="1">
    <input type="hidden" name="2a" value="614B W Business US Hwy 36">
    <input type="hidden" name="2c" value="Saint Francis">
    <input type="hidden" name="2s" value="KS">
    <input type="hidden" name="2z" value="67756">
    <input type="hidden" name="2y" value="US">
    <table border="0" cellpadding="0" cellspacing="0" style="font: 11px Arial,Helvetica;">
    <tr><td colspan="2" style="font-weight: bold;">
    <div align="center"><br>
    <a href="http://www.mapquest.com/"><img border="0" src="img/ws_wt_sm.gif" alt="MapQuest"></a>
    </div></td></tr>
    <tr><td colspan="2" style="font-weight: bold;">FROM:</td></tr>
    <tr><td colspan="2">Address or Intersection: </td></tr>
    <tr><td colspan="2"><input type="text" name="1a" size="22" maxlength="30" value=""></td></tr>
    <tr><td colspan="2">City: </td></tr>
    <tr><td colspan="2"><input type="text" name="1c" size="22" maxlength="30" value=""></td></tr>
    <tr><td>State:</td>
    <td> ZIP Code:</td></tr>
    <tr><td><input type="text" name="1s" size="4" maxlength="2" value=""></td>
    <td><input type="text" name="1z" size="8" maxlength="10" value=""></td></tr>
    <tr><td colspan="2">Country:</td></tr>
    <tr><td colspan="2"><select name="1y"><option value="CA">Canada</option><option value="US" selected>United States</option></select></td></tr>
    <tr><td colspan="2" style="text-align: center; padding-top: 10px;"><input type="hidden" name="CID" value="lfddwid" /><input type="submit" name="dir" value="Get Directions" /></td></tr>

    </table>
    </div>
    </form>

    </td></tr></table>
</div>
_END;
echo "<div style='margin-top:20px; text-align:center; font-size:2em; font-weight:bold; color:#$color2;'>* * *</div>";

