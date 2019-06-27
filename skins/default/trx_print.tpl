<div style="width:45%; float:left">
	{$company_logo}<br />
	{$l_site_name}<br />
	{$site_address}
</div>

<div style="width:45%; float:right">

<table border="1" style="border-collapse: collapse; border: solid 1px #ccc;" align="right" cellpadding="3">
	<tr>
		<td colspan="2">{$l_invoice}</td>
	</tr>
	<tr>
		<th width="130">{$l_issue_date}</th>
		<td width="200">{$today}</td>
	</tr>
	<tr>
		<th>{$l_order_date}</th>
		<td>{$order_date}</td>
	</tr>
	<tr>
		<th><b>ID</b></th>
		<td>{$order_id}</td>
	</tr>
	<tr>
		<th>{$l_ship_date}</th>
		<td>{$order_shipped}</td>
	</tr>
</table>
</div>

<div style="clear:both"></div>

<table border="0" width="100%" cellspacing="0" cellpadding="5" style="margin-top:20px">
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
			<div class="small">{$x_option}</div>
		</td>
		<td align="center">{$qty}</td>
		<td align="right">{$subtotal}</td>
	</tr>
	<!-- ENDBLOCK -->
</table>

<table border="0" width="100%" style="margin-top:20px">
	<tr>
		<td width="50%" valign="top">
			<b>{$l_bill_detail}</b><br />
			{$bill_address}
		</td>
		<td width="50%" valign="top">
			<b>{$l_bill_detail}</b><br />
			{$ship_address}
		</td>
	</tr>
</table>

<table border="0" width="100%" style="margin-top:20px">
	<tr>
		<td class="title" colspan="3">{$l_order_status}</td>
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
		<td align="right">{$l_order_status}</td><td  align="center"><b>{$order_status}</b></td>
	</tr>
	<tr>
		<td align="right">{$l_ship_date}</td><td align="center"><b>{$order_shipped}</b></td>
	</tr>
</table>

{$l_trx_print_footer}