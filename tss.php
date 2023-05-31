<?php
$stmt = $db->prepare ( "SELECT orderdate,pudate,tssyear,startdate,taxrate FROM sitesettings" );
$stmt->execute ();
$rowdate = $stmt->fetch ();
$od = $rowdate ['orderdate'];
$pud = $rowdate ['pudate'];
$tssyear = $rowdate ['tssyear'];
$sd = $rowdate ['startdate'];
$taxrate = $rowdate ['taxrate'];

$now = time ();
$tss_yes = (($now >= $sd) && ($now <= $od)) ? "1" : "0";
$odnice = date ( "j M", $od );
$pudnice = date ( "j M", $pud );
$sdnice = date ( "j M", $sd );
?>
<div style="font-size:3em; text-align:center; color:#006600;"><?php
echo $tssyear;
?> Tree and shrub sale</div>
<div style="font-size:1.25em; text-align:justify; margin:20px 40px;">ANYONE may order trees from us. There are no restrictions attached to whom may order trees.<br /><br />
  Funds generated from the sale of trees, drip and weed barrier, etc. help finance conservation educational activities and materials in Cheyenne County.
</div>
<div style="font-size:1.25em; text-align:justify; margin:20px 40px;">Contact the office for sales tax exemption before ordering. 785-332-2341 Ext.101. The online store will automatically charge sales tax.
</div>
<div style="font-size:1.25em; text-align:justify; margin:20px 40px;">
  <span style="text-decoration:underline;">Guarantee.</span> The trees are guaranteed to be in good shape when the customer takes possession. Due to various care and weather conditions beyond out control, we do not guarantee survival of the trees.
</div>
<?php
if ($tss_yes == "1") {
	?>
	<div style="font-size:1.5em; text-align:center; margin:20px auto;"><a href="https://cheyennecountycd.square.site">- VISIT OUR ONLINE STORE -</a>
</div>
  <div style='font-size:1.5em; text-align:left; margin:20px 40px; color:#006600;'>Sale starts: <?php
	echo $sdnice;
	?></div>
  <div style='font-size:1.5em; text-align:left; margin:20px 40px; color:#006600;'>Order deadline: <?php
	echo $odnice;
	?></div>
<?php
} else {
	?>
  <div style='font-size:1.5em; text-align:left; margin:20px 40px; color:#006600;'>Join us in February for our next Tree and Shrub Sale.</div>
<?php
}
?>
<?php
if ($tss_yes == "1") {
	?>
  <div style='font-size:1.5em; text-align:left; margin:20px 40px; color:#006600;'>Pick-up date: <?php
	echo $pudnice;
	?><br />Cheyenne Co. Fairgrounds</div>
<?php
}
?>
<div style='font-size:1em; text-align:left; margin:20px 40px;'>The purchase links will no longer be available after <?php
echo date ( "F j, Y, g:i a", $od );
?>. Please finalize your order before this time, or your order may be lost.<br /><br />
  All prices include freight to St. Francis.<br /><br />
  All purchased item are to be picked up at the Cheyenne Co. Fairgrounds. No items will be delivered.<br /><br />
  <?php
		echo $taxrate;
		?>&#37; Sales tax will be added to each order.<br /><br />
  <div onclick="toggleview('tenrules')" style="cursor:pointer; text-decoration:underline;"><span style="font-weight:bold;">Article:</span> Ornamentals: Ten Rules for Planting Trees</div>
  <div style="display:none; border:1px solid black; padding:20px; text-align:justify; margin-top:10px;" id="tenrules">
    Original article located at: <a href="http://www.ksuhortnewsletter.org/" target="_blank" style="text-decoration:underline;">http://www.ksuhortnewsletter.org/</a><br /><br />
    <img src="photos/tenrules.jpg" style="float:right; margin:0px 0px 10px 10px; border:1px solid black; padding:3px;" />
    Before you begin spring landscaping, here are some tips on planting trees.<br /><br />
    1. Select the right tree for the site. To avoid serious problems, choose trees that are adapted to your location. Consider whether the tree produces nuisance fruit or if there are disease-resistant varieties available. For example, there are a number of crabapple varieties that are resistant to apple scab and rust diseases. Also consider the mature size of a tree to be sure you have enough room. See http://hnr.k-state.edu/extension/info-center/recommended-plants/index.html  or ask a local nurseryman for suggestions for trees adapted to your area.<br /><br />
    2. Keep the tree well watered and in a shady location until planting. When moving the tree, lift it by the root ball or pot and not by the trunk.<br /><br />
    3. Before planting, remove all wires, labels, cords or anything else tied to the plant. If left on, they may eventually girdle the branch to which they are attached. The root flare (point where trunk and roots meet) should be visible. If it isn't, remove enough soil or media so that it is.<br /><br />
    4. Dig a proper hole. Make the hole deep enough so that the tree sits slightly above nursery level. Plant the tree on solid ground, not fill dirt. In other words, don't dig the hole too deep and then add soil back to the hole before placing the tree.<br /><br />
    The width of the planting hole is very important. It should be three times the width of the root ball. Loosening the soil outside the hole so it is five times the diameter of the root ball will allow the tree to spread its roots faster.<br /><br />
    5. Remove all containers from the root ball. Cut away plastic and peat pots; roll burlap and wire baskets back into the hole, cutting as much of the excess away as possible. If you can remove the wire basket without disturbing the root ball, do it. If roots have been circling around in the container, cut them and spread them out so they do not continue growing so that they circle inside the hole and become girdling roots later in the life of the tree.<br /><br />
    6. Backfill the hole with the same soil that was removed. Amendments such as peat moss likely do more harm than good. Make sure the soil that goes back is loosened - no clods or clumps. Add water as you fill to ensure good root to soil contact and prevent air pockets. There is no need to fertilize at planting. Note: Adding organic matter to larger area than just the planting hole can be beneficial, but it must be mixed in thoroughly with the existing soil. However, adding amendments to just the planting hole in heavy soil creates a “pot” effect that can fill with water and drown your new tree.<br /><br />
    7. Don't cut back the branches of a tree after planting except those that are rubbing or damaged. The leaf buds release a hormone that encourages root growth. If the tree is cut back, the reduced number of leaf buds results in less hormone released and therefore fewer roots being formed.<br /><br />
    8. Water the tree thoroughly and then once a week for the first season if there is insufficient rainfall.<br /><br />
    9. Mulch around the tree. Mulch should be 2 to 4 inches deep and cover an area two the three times the diameter of the root ball. Mulching reduces competition from other plants, conserves moisture and keeps soil temperature closer to what the plants' roots prefer.<br /><br />
    10. Stake only when necessary. Trees will establish more quickly and grow faster if they are not staked. However, larger trees or those in windy locations may need to be staked the first year. Movement is necessary for the trunk to become strong. Staking should be designed to limit movement of the root ball rather than immobilize the trunk. (Ward Upham)
  </div><br /><br />
  <a href="pdf/2023TreeBrochure.pdf" target="_blank" style="text-decoration:underline;">View a copy of this year's Tree Sale Brochure by clicking here.</a>
</div>
