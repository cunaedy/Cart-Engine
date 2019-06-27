<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span> Featured Products</div>
	<form name="featured" method="get" action="featured.php">
		<input type="hidden" name="cmd" value="save" />
		<table border="0" width="100%" class="table table-form">
			<tr>
				<th>Notes</th>
				<td>
					<ul>
						<li>Use this form to promote products site-wide.</li>
						<li>To promote products for specific category, please use <a href="product_cat.php">Products &gt; Categories</a>.</li>
					</ul>
				</td>
			</tr>
			<tr>
				<th>Featured products</th>
				<td><input type="text" size="40" name="featured" value="{$featured_product}" id="featured" /></td>
			</tr>
			<tr>
				<td colspan="2"><button type="submit" class="btn btn-primary">Save</button></td>
			</tr>
		</table>
	</form>
</div>

<script type="text/javascript">
$("#featured").tokenInput("admin_ajax.php?cmd=product", { queryParam:"query", preventDuplicates:true, prePopulate:{$featured_product_preset}});
</script>