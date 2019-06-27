<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Invoice</title>
<link rel="stylesheet" type="text/css" href="../skins/_admin/style.css" />
<style type="text/css">
td{padding:3px;}
</style>
</head>

<body style="background:none">
	<table width="600" border="0">
		<tr>
			<td colspan="2">{$company_logo}</td>
		</tr>
		<tr>
			<td style="font-size:10pt">{$l_site_name}<br />{$site_address}</td>
			<td align="right" valign="top">
				Order Date: {$order_date}<br />
				Order ID: #{$order_id}
			</td>
		</tr>
		<tr>
			<td>
				<h4>Shipping Address</h4>
				{$ship_address}
			</td>
			<td>
				<h4>Billing Address</h4>
				{$bill_address}
			</td>
		</tr>
	</table>

	<table border="1" width="600" style="border-collapse:collapse; border: solid 1px #000">
		<tr>
			<td width="73%" bgcolor="#999999" style="padding:10px">Description</td>
			<td width="5%" align="center" bgcolor="#999999" style="padding:10px">Qty</td>
			<td width="20%" align="right" bgcolor="#999999" style="padding:10px">Subtotal</td>
		</tr>

		<!-- BEGINBLOCK trx_item -->
		<tr>
			<td class="adminbg_r" align="left">{$title}</td>
			<td class="adminbg_r" align="center">{$qty}</td>
			<td class="adminbg_r" align="right">{$subtotal}</td>
		</tr>
		<!-- ENDBLOCK -->

		<tr>
			<td align="right" colspan="2" class="adminbg_r2">SubTotal</td><td class="adminbg_r2" align="right">{$total}</td>
		</tr>
		<tr>
			<td align="right" class="adminbg_r" colspan="2">Shipping Fee</td><td class="adminbg_r" align="right">{$order_shipping_fee}</td>
		</tr>
		<tr>
			<td align="right" class="adminbg_r2" colspan="2">Tax</td><td class="adminbg_r2" align="right">{$order_tax}</td>
		</tr>
		<tr>
			<td align="right" class="adminbg_r" colspan="2">Total</td><td align="right" class="adminbg_r"><b>{$grand_total}</b></td>
		</tr>
	</table>

	<table>
		<tr>
			<td align="right">Payment Method</td><td align="center"><b>{$order_payment}</b></td>
		</tr>
		<tr>
			<td align="right">Shipping Date</td><td align="center"><b>{$order_shipped}</b></td>
		</tr>
	</table>
	</body>
</html>