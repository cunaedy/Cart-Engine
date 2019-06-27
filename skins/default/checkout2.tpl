<h1>{$l_checkout} 1/2</h1>
<form method="get" action="checkout.php" name="address">
	<input type="hidden" name="step" value="3" />
	<h3>{$l_confirm_address}</h3>
	<div class="row">
		<div class="col-md-6">
			<p><b>{$l_bill_detail}</b><br />
			{$bill_address}</p>
			<!-- BEGINIF !$xpress -->
			<p>[<a href="profile.php?mode=address">{$l_change_address}</a>]</p>
			<!-- ENDIF -->
		</div>
		<div class="col-md-6">
			<p><b>{$l_ship_detail}</b><br />
			{$ship_address}</p>
		</div>
	</div>

	<!-- BEGINIF $shipping_option -->
	<h3>{$l_ship_method}</h3>
	<p>{$l_ship_method_why}</p>
	<table border="0" width="100%">
		<tr>
			<th width="10%">&nbsp;</th>
			<th width="65%" style="text-align:left">{$l_method}</th>
			<th width="25%" style="text-align:right">{$l_fee}</th>
		</tr>

		<!-- BEGINBLOCK courier_item -->
		<tr>
			<td align="center"><input type="radio" name="shipper" value="{$method}" id="shipper_{$method}" /></td>
			<td><label for="shipper_{$method}">{$name}</label></td>
			<td align="right"><label for="shipper_{$method}">{$fee}</label></td>
		</tr>
		<!-- ENDBLOCK -->
	</table>
	<!-- ELSE -->
	<input type="hidden" name="shipper" value="ship_free" />
	<!-- ENDIF -->

	<h3>{$l_pay_method}</h3>
	<p>{$l_pay_method_why}</p>
	<table border="0" width="100%">
		<tr>
			<td width="10%" align="center">&nbsp;</td>
			<th width="65%" style="text-align:left">{$l_method}</th>
			<th width="25%" style="text-align:right">{$l_fee}</th>
		</tr>
		<!-- BEGINBLOCK pay_item -->
		<tr>
			<td align="center"><input type="radio" name="payment" value="{$method}" id="payment_{$method}" /></td>
			<td><label for="payment_{$method}">{$name}</label></td>
			<td align="right"><label for="payment_{$method}">{$fee}</label></td>
		</tr>
		<!-- ENDBLOCK -->
	</table>

	<h3>{$l_order_note}</h3>
	<p>{$l_order_note_why}</p>
	<textarea name="order_notes" rows="3" cols="50" style="width:90%;max-width:250px"></textarea>

	<div style="clear:both;margin:10px">&nbsp;</div>
	<div style="float:left; width:49%; text-align:center;">
		<button type="button" onclick="history.go(-1)" class="btn btn-danger">{$l_back}</button>
	</div>
	<div style="float:right; width:49%; text-align:center;">
		<button type="submit"  class="btn btn-success">{$l_next}</button>
	</div>
	<div style="clear:both"></div>
</form>