<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Category Mover</div>
	
	<form name="featured" method="get" action="cat_mover.php" id="list">
		<input type="hidden" name="cmd" value="save" />
		<table border="0" width="100%" class="table table-form">
			<tr>
				<th>Notes</th>
				<td>
					<ul>
						<li>Use this script to move products from one category to another.</li>
						<li>You can also display orphaned categories, which occurs when you remove a category which still has products assigned to it.</li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>Source Category</th>
				<td>{$source_cat}</td>
				<td><button type="button" onclick="this.form.cmd.value='display';this.form.submit()" class="btn btn-default">Display Products</button></td>
			</tr>
			<tr>
				<th>Target Category</th>
				<td>{$target_cat}</td>
				<td><button type="submit" class="btn btn-primary">Move</button></td>
			</tr>
			<tr>
				<th>Products</th>
				<td colspan="2">{$product_list}</td>
			</tr>
		</table>
	</form>
</div>