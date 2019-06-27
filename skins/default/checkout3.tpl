<h1>{$l_checkout} 2/2</h1>
<h3>{$l_my_cart} <small><a href="checkout.php">{$l_edit}</a></small></h3>
<table width="100%" border="0" class="table">
	<tr>
		<th width="65%"></th>
		<th width="5%" class="text-center"><b>{$l_quantity}</b></th>
		<th width="15%" class="text-right"><b>{$l_price}</b></th>
		<th width="15%" class="text-right"><b>{$l_subtotal}</b></th>
	</tr>

	<!-- BEGINBLOCK checkout_item -->
	<tr>
		<td valign="top">
			{$image_small}<br />
			<a href="{$site_url}/{$url}"><b>{$title}</b></a> {$digital}
		</td>
		<td valign="top" class="text-center">{$qty}</td>
		<td valign="top" class="text-right">{$price}</td>
		<td valign="top" class="text-right">{$subtotal}</td>
	</tr>
	<!-- ENDBLOCK -->
	<tr><td>{$l_purchase}</td><td align="right" colspan="3">{$total}</td></tr>
	<tr><td>{$l_coupon_disc} <span class="badge">{$gift_code}</span></td><td align="right" colspan="3">{$coupon_disc}</td></tr>
	<tr><td>{$l_tax}</td><td align="right" colspan="3">{$tax}</td></tr>
	<tr><td>{$l_ship_fee}</td><td align="right" colspan="3">{$shipping_cost}</td></tr>
	<tr><td>{$l_payment_fee}</td><td align="right" colspan="3">{$payment_cost}</td></tr>
	<tr style="border-bottom:double 3px #ccc"><td>{$l_total}</td><td align="right" colspan="3"><b>{$grand_total}</b></td></tr>
	<tr><td>{$l_pay_method}</td><td align="right" colspan="3"><b>{$payment_method}</b></td></tr>
	<tr><td>{$l_ship_method}</td><td align="right" colspan="3"><b>{$shipping_method}</b></td></tr>
</table>

<form action="confirmed.php" method="get">
	<input type="hidden" name="order_notes" value="{$order_notes}" />
	<input type="hidden" name="shipper" value="{$shipper}" />
	<input type="hidden" name="payment" value="{$payment}" />

	<div class="row">
		<div class="col-md-6">
			<p><b>{$l_bill_address}</b></p>
			<p>{$bill_address}</p>
		</div>
		<div class="col-md-6">
			<p><b>{$l_ship_address}</b>
			<!-- BEGINIF $xpress -->
			<!-- ELSE --><small><a href="profile.php?mode=address">{$l_edit}</a></small>
			<!-- ENDIF --></p>
			<p>{$ship_address}</p>
		</div>
	</div>

	<div style="float:left; width:49%; text-align:center">
		<button type="button" onclick="history.go(-1)" class="btn btn-danger">{$l_back}</button>
	</div>
	<div style="float:right; width:49%; text-align:center">
		<button type="submit" class="btn btn-success">{$l_place_order}</button>
	</div>
	<div style="clear:both"></div>
</form>