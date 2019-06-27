<h1>{$l_trx_history}</h1>
<table width="100%" cellpadding="2" cellspacing="1" class="table">
	<tr>
		<th>{$l_date}</th>
		<th>ID</th>
		<th class="responsive_wide_td">{$l_total}*</th>
		<th class="responsive_wide_td">{$l_pay_method}</th>
		<th class="responsive_wide_td">{$l_pay_status}</th>
		<th class="responsive_wide_td">{$l_order_status}</th>
	</tr>
	<!-- BEGINBLOCK list -->
	<tr>
		<td><a href="{$site_url}/trx.php?order_id={$order_id}">{$order_date}</a></td>
		<td>{$order_id}</td>
		<td class="responsive_wide_td">{$order_gtotal}</td>
		<td class="responsive_wide_td">{$order_payment}</td>
		<td class="responsive_wide_td">{$order_paystat}</td>
		<td class="responsive_wide_td">{$order_status}</td>
	</tr>
	<!-- ENDBLOCK -->
</table>