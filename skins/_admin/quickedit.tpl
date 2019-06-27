<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Quick Edit</div>
	<div style="padding: 10px">
		<form method="get" action="quickedit.php">
			<input type="hidden" name="cmd" value="list" />
			<b>Category:</b> {$cat_select}
			<button type="submit" class="btn btn-primary">Display</button>
		</form>
	</div>


	<form method="post" action="quickedit.php">
		<input type="hidden" name="cmd" value="save" />
		<table border="0" width="100%" class="table table-form">
			<tr>
				<th width="10%">ID</th>
				<th width="10%">SKU</th>
				<th width="40%">Name</th>
				<th width="33%">Price</th>
				<th width="17%">Stock</th>
			</tr>
		<!-- BEGINBLOCK list -->
			<tr>
				<td><a href="product.php?mode=edit&amp;item_id={$idx}">ID {$idx}</a></td>
				<td>{$sku}</td>
				<td>{$title}</td>
				<td><input type="hidden" name="item_id_{$idx}" value="{$idx}" size="8" maxlength="15"/>
					<input type="text" name="price_{$idx}" value="{$price}" size="8" maxlength="15"/></td>
				<td><input type="text" name="stock_{$idx}" value="{$stock}" size="8" maxlength="12"/></td>
			</tr>
		<!-- ENDBLOCK -->
			<tr>
				<td colspan="6"><button type="submit" class="btn btn-primary">Save</button> <button type="reset" class="btn btn-danger">Reset</button></td>
			</tr>
		</table>
	</form>
	{$pagination}
</div>