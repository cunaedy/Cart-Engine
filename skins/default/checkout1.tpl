<h1><span class="glyphicon glyphicon-shopping-cart"></span> {$l_my_cart}</h1>
<form method="get" action="cart.php">
	<input type="hidden" name="cmd" value="update" />
	<table width="100%" border="0" class="table">
		<tr>
			<th width="5%" align="center"></th>
			<th width="55%"></th>
			<th width="5%" class="text-center"><b>{$l_quantity}</b></th>
			<th width="12%" class="text-right"><b>{$l_price}</b></th>
			<th width="11%" class="text-right"><b>{$l_tax}</b></th>
			<th width="12%" class="text-right"><b>{$l_subtotal}</b></th>
		</tr>

		<!-- BEGINBLOCK checkout_item -->
		<tr>
			<td class="text-center" valign="top"><a href="cart.php?cmd=del&amp;idx={$idx}" title="Remove this item"><span class="glyphicon glyphicon-remove-circle" style="color:#f00"></span></td>
			<td valign="top">
				{$image_small}<br />
				<a href="{$site_url}/{$url}"><b>{$title}</b></a> {$digital}
			</td>
			<td valign="top" class="text-center"><input type="text" name="qty[{$idx}]" value="{$qty}" size="2" /></td>
			<td valign="top" class="text-right">{$price}</td>
			<td valign="top" class="text-right">{$tax}</td>
			<td valign="top" class="text-right">{$subtotal_with_tax}</td>
		</tr>
		<!-- ENDBLOCK -->
		<!-- BEGINIF $no_item -->
		<tr>
			<td colspan="6"><p>{$l_no_item_cart}</p></td>
		</tr>
		<!-- ENDIF -->
		<tr>
			<td>&nbsp;</td>
			<td align="center" colspan="3"><button type="submit" class="btn btn-primary">{$l_update_cart}</button><br />
			<span class="small">{$l_update_cart_why}</span></td>
			<td align="right" valign="bottom"><b>{$l_total}</b></td>
			<td align="right" valign="bottom"><b>{$total_with_tax}</b></td>
		</tr>
	</table>
</form>

<form method="get" action="coupon.php">
	<hr />
	<p><span class="glyphicon glyphicon-gift"></span> {$l_gift_coupon_input}
	<input type="text" size="10" name="code" style="padding:8px"/>
	<button type="submit" class="btn btn-default">{$l_submit}</button></p>

 <!-- BEGINIF $coupon_exists -->
	<div class="alert alert-warning">{$l_gift_coupon_warning}</div>
 <!-- ENDIF -->
	<hr />
</form>

<form method="get" action="checkout.php" style="margin: 20px 0">
	<input type="hidden" name="step" value="2" />
	<div class="pull-right"><button type="submit" class="btn btn-success">{$l_checkout}</button></div>
</form>