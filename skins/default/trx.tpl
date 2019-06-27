<h1>{$l_my_order}</h1>

<p>{$l_my_order_title}</p>

<table border="0" width="100%" class="table">
	<tr>
		<th width="73%">{$l_title}</th>
		<th width="5%" style="text-align:center">{$l_quantity}</th>
		<th width="20%" style="text-align:right">{$l_subtotal}</th>
	</tr>

	<!-- BEGINBLOCK trx_item -->
	<tr>
		<td>
			<div class="small">{$where_am_i}</div>
			<a href="detail.php?item_id={$item_id}">{$title}</a> {$digital}
		</td>
		<td align="center">{$qty}</td>
		<td align="right">{$subtotal}</td>
	</tr>
	<!-- ENDBLOCK -->

	<tr>
		<td align="right" colspan="2">{$l_subtotal}</td>
		<td  align="right">{$total}</td>
	</tr>
	<tr>
		<td align="right" colspan="2">{$l_coupon_disc}</td>
		<td align="right">{$gift_discount}</td>
	</tr>
	<tr>
		<td align="right" colspan="2">{$l_payment_fee}</td>
		<td align="right">{$order_payment_fee}</td>
	</tr>
	<tr>
		<td align="right" colspan="2">{$l_ship_fee_for}</td>
		<td align="right">{$order_shipping_fee}</td>
	</tr>
	<tr>
		<td align="right" colspan="2">{$l_tax}</td>
		<td  align="right">{$order_tax}</td>
	</tr>
	<tr>
		<td align="right" colspan="2">{$l_total}</td>
		<td align="right"><b>{$order_gtotal}</b></td>
	</tr>
</table>

<table border="0" width="100%" class="table">
	<tr>
		<td width="50%" valign="top">
			<b>{$l_bill_detail}</b><br />
			{$bill_address}
		</td>
		<td width="50%" valign="top">
			<b>{$l_ship_detail}</b><br />
			{$ship_address}
		</td>
	</tr>
</table>

<table border="0" width="100%" class="table">
	<tr>
		<th colspan="3">{$l_order_status}</th>
	</tr>
	<tr>
		<td align="right">{$l_ship_method}</td><td align="center"><b>{$shipping_method}</b></td>
	</tr>
	<tr>
		<td align="right">{$l_pay_method}</td><td  align="center"><b>{$order_payment}</b></td>
	</tr>
	<tr>
		<td align="right">{$l_pay_status}</td><td align="center"><b>{$order_paystat}</b></td>
	</tr>
	<tr>
		<td align="right" >{$l_order_status}</td><td  align="center"><b>{$order_status}</b></td>
	</tr>
	<tr>
		<td align="right">{$l_ship_date}</td><td align="center"><b>{$order_shipped}</b></td>
	</tr>
</table>

<p align="right">
[ <a href="trx.php?cmd=print&amp;order_id={$order_id}" target="invoice">{$l_print_invoice}</a> ]
</p>