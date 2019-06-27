<form method="post" action="local_config_process.php" enctype="multipart/form-data">
	<div class="panel panel-default">
		<div class="panel-heading"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Secondary Settings</div>
		<div class="panel-body">
			<ul id="qeconfig" class="nav nav-pills">
				<li class="active"><a href="#1" class="current" data-toggle="tab"><span class="glyphicon glyphicon-home"></span> Shop</a></li>
				<li><a href="#2" data-toggle="tab">Taxes</a></li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="1">
				<table class="table table-form">
					<tr><td class="adminbg_h" colspan="2">General</td></tr>
					<tr><td width="30%">Order ID Format</td><td width="70%">{$order_id_select}</td></tr>
					<tr>
						<td>Order ID Prefix</td>
						<td><input type="text" name="order_id_prefix" size="5" value="{$order_id_prefix}" class="narrow_input" />
							<span class="glyphicon glyphicon-info-sign help tips" title="Enter the prefix. Eg 'CE' will result in order ID: CE000932"></span></td>
					</tr>
					<tr>
						<td>What is your shop coverage?</td>
						<td>{$area_select}</td>
					</tr>
					<tr><td width="100%" class="adminbg_c" colspan="2">Cart</td></tr>
					<tr>
						<td>Display Cart After Buying</td>
						<td>{$display_cart_select} <span class="glyphicon glyphicon-info-sign help tips" title="Choose YES to display the shopping cart after the user clicks on Buy. Choose NO to redirect the user to the previous page (product page)."></span></td>
					</tr>
					<tr>
						<td>Enable Express Checkout?</td>
						<td>{$express_radio} <span class="glyphicon glyphicon-info-sign help tips" title="Express checkout allows your customers to checkout without registration/login."></span></td>
					</tr>
					<tr>
						<td>Hide shipping option on digital goods?</td>
						<td>{$hide_ship_radio} <span class="glyphicon glyphicon-info-sign help tips" title="If 'Yes', CartEngine will hide shipping options when your customers buy only digital goods. Requires 'Free Shipping' module."></span></td>
					</tr>
					<tr><td width="100%" class="adminbg_c" colspan="2">Stock</td></tr>
					<tr>
						<td>Manage Stock</td>
						<td>{$stock_select}
							<span class="glyphicon glyphicon-info-sign help tips" title="Enable simple stock management. Stock will be reduced upon sale, and will be increased upon cancellation."></span></td>
					</tr>
					<tr><td width="100%" class="adminbg_c" colspan="2">Miscellaneous Options</td></tr>
					<tr>
						<td>Days Before Removing Temporary Orders</td>
						<td><input type="text" name="delete_old_orders" size="2" value="{$delete_old_orders}" class="narrow_input"/> days
							<span class="glyphicon glyphicon-info-sign help tips" title="CartEngine will keep all temporary orders for a specified number of days, afterwards they will be removed. Enter '0' to disable."></span></td>
					</tr>
				</table>
			</div>

			<div class="tab-pane" id="2">
				<table class="table table-form">
					<tr>
						<td>Display Price with Tax?</td>
						<td colspan="3">{$price_tax_select}
							<span class="glyphicon glyphicon-info-sign help tips" title="Price with tax displayed separately from regular price. Visitors must be logged in, as tax calculation needs address."></span></td>
					</tr>
					<tr>
						<td>Tax Based on</td>
						<td colspan="3">{$tax_base_select}</td>
					</tr>
					<tr>
						<td class="adminbg_c" colspan="4">Tax Rates  <span class="glyphicon glyphicon-info-sign help tips" title="If customer's address matches your city, local rates will be used. If it matches your state, state rate will be used. If it matches your country, country rate will be used."></span></td>
					</tr>
					<tr><th>Tax Class</th><th>Local</th><th>State</th><th>Nation</th><th>International</th></tr>
					<!-- BEGINBLOCK tax_list -->
					<tr>
						<td>{$tax_title}</td>
						<td><input type="text" name="tax_city_{$tidx}" value="{$tax_city}" class="width-xs" />%</td>
						<td><input type="text" name="tax_state_{$tidx}" value="{$tax_state}" class="width-xs" />%</td>
						<td><input type="text" name="tax_nation_{$tidx}" value="{$tax_nation}" class="width-xs" />%</td>
						<td><input type="text" name="tax_world_{$tidx}" value="{$tax_world}" class="width-xs" />%</td>
					</tr>
					<!-- ENDBLOCK -->
					<tr>
						<td colspan="4"><a href="edit_opt.php?fid=tax&amp;title=Tax Class" class="btn btn-default btn-xs">Add New Tax Class</a></td>
				</table>
			</div>
		</div>
	</div>
	<p align="center"><button type="submit" class="btn btn-primary">Save</button></p>
</form>