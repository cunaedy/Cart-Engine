<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-cart" aria-hidden="true"></span> Order ID #{$order_id}</div>
	<table border="0" width="100%" class="table table-form">
		<tr>
			<th width="70%">Products</th>
			<th width="5%">Qty</th>
			<th width="15%" style="text-align:right">Subtotal</th>
			<th width="10%" style="text-align:right">Stock <span class="glyphicon glyphicon-info-sign help tips" title="Stock level after this order has been processed."></span></th>
		</tr>

		<!-- BEGINBLOCK trx_item -->
		<tr>
			<td>
				<div class="small">{$where_am_i} / SKU: {$sku}</div>{$image_small} <a href="product.php?mode=edit&amp;item_id={$item_id}" class="checkouttxt">{$title}</a> {$digital}
			</td>
			<td align="center">{$qty}</td>
			<td align="right">{$subtotal}</td>
			<td align="right">{$stock}</td>
		</tr>
		<!-- ENDBLOCK -->

		<tr>
			<td align="right" colspan="2">SubTotal</td>
			<td align="right">{$total}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="right" colspan="2">Gift Discount</td>
			<td align="right">{$gift_discount}</td>
			<td><a href="coupon.php?qadmin_cmd=search&keyword={$gift_code}&search_by=gift_code">{$gift_code}</a></td>
		</tr>
		<tr>
			<td align="right" colspan="2">Tax</td>
			<td align="right">{$order_tax}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="right" colspan="2">Shipping fee for {$total_weight} {$l_weight_name}</td>
			<td align="right">{$order_shipping_fee}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="right" colspan="2">Payment Fee</td>
			<td align="right">{$order_payment_fee}</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td align="right" colspan="2">Total</td>
			<td align="right"><b>{$grand_total}</b></td>
			<td>&nbsp;</td>
		</tr>
	</table>

	<table border="0" width="100%" class="table table-form">
		<tr>
			<th class="adminbg_c">Order Notes</th>
		</tr>
		<tr>
			<td>{$order_notes}</td>
		</tr>
	</table>

	<table border="0" width="100%" class="table table-form">
		<tr>
			<th class="adminbg_c" colspan="2">Address</th>
		</tr>
		<tr>
			<td width="50%" valign="top"><b>Billing Address</b><br />{$bill_address}</td>
			<td width="50%" valign="top"><b>Shipping Address</b><br />{$ship_address}</td>
		</tr>
	</table>

	<form action="trx_process.php" method="post">
		<input type="hidden" name="order_id" value="{$order_id}" />
		<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form">
			<tr><th class="adminbg_c" colspan="2">Order Status</th></tr>
			<tr>
				<td align="right"><b>Shipping Method</b></td>
				<td align="center">{$shipping_method}</td></tr>
			<tr>
				<td align="right"><b>Payment Method</b></td>
				<td align="center">{$order_payment} (<a href="paylog.php?mode=detail&amp;order_id={$order_id}">see payment log</a>)</td>
			</tr>
			<tr>
				<td align="right"><b>Payment Status</b></td>
				<td align="center">{$order_paystat} <span class="glyphicon glyphicon-info-sign help tips" title="Changing the Payment Status to Denied/Failed will automatically change Order Status to Cancelled."></span></td>
			</tr>
			<tr>
				<td align="right"><b>Order Status</b></td>
				<td align="center">{$order_status} <span class="glyphicon glyphicon-info-sign help tips" title="Digital products will be sent when you set order status to Processing, Shipped, Delivered or Completed!"></span></td>
			</tr>
			<tr>
				<td align="right"><b>Send Notification Email?</b></td>
				<td align="center">{$notify_select} <span class="glyphicon glyphicon-info-sign help tips" title="Auto - send email automatically. No - suppress email delivery. Manual - allow you to edit the email contents first. Emails will be sent on 'Pending', 'Processing', 'Shipped' &amp; 'Cancelled'."></span></td>
			</tr>
			<tr>
				<td align="center" colspan="2">
					<ul class="quick_info" style="margin-bottom:15px">
						<li>Order Date: {$order_date}</li>
						<li>Shipped Date: {$order_shipped}</li>
						<li>Delivered Date: {$order_delivered}</li>
					</ul>
					<ul class="quick_info">
						<li>Completed Date: {$order_completed}</li>
						<li>Cancelled Date: {$order_cancelled}</li>
					</ul>
				</td>
			</tr>
			<tr>
				<td align="right">&nbsp;</td>
				<td align="center"><button type="submit" class="btn btn-primary">Submit</button> <button type="reset" class="btn btn-danger">Reset</button></td>
			</tr>
		</table>
	</form>

	<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form">
		<tr>
			<th class="adminbg_c" colspan="2">Customer ID</th>
		</tr>
		<tr>
			<td width="150">Customer</td>
			<td>{$fullname} (UID: <a href="user.php?id={$user_id}">{$user_id}</a>)</td>
		</tr>
		<tr>
			<td width="150">Email</td>
			<td>{$user_email}</td>
		</tr>
		<tr>
			<td width="150">Member Since</td>
			<td>{$user_since}</td>
		</tr>
		<tr>
			<td width="150">Tools</td>
			<td>
				<a href="print.php?cmd=invoice&amp;w={$order_id}" class="btn btn-default">Print Invoice</a>
				<a href="print.php?cmd=address&amp;w={$order_id}" class="btn btn-default">Print Address Label</a>
				<a href="admin_mail.php?mode=mail&amp;email={$user_email}" class="btn btn-default">Send Email</a>
				<a href="user.php?id={$user_id}" class="btn btn-default">Profile</a>
			</td>
		</tr>
	</table>
</div>