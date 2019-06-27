<form method="get" action="product_list.php" name="product_list" id="product_list">
	<div class="panel panel-default">
		<div class="panel-heading"><span class="glyphicon glyphicon-barcode"></span> Product List</div>
		<table class="table">
			<tr><td>Keywords</td><td><input type="text" name="keyword" value="{$keyword}" /> <span class="glyphicon glyphicon-info-sign help tips" title="This will search in ID, title &amp; details."></td></tr>
			<tr><td>Category</td><td>{$category_select}</td></tr>
			<tr><td>Entry Date</td>
				<td>{$start_date} <a style="cursor:pointer"><span class="glyphicon glyphicon-calendar" id="start_date" class="calendar" data-date-format="yyyy-mm-dd" data-date=""></span></a>
								to {$end_date} <a style="cursor:pointer"><span class="glyphicon glyphicon-calendar" id="end_date" class="calendar" data-date-format="yyyy-mm-dd" data-date=""></span></a></td></tr>
			<tr><td>Search Operation</td><td>{$mode_select} &bull; Sort Results By {$sort_select} &bull; <button type="submit" class="btn btn-primary" name="cmd" value="search">Search Now</button></td></tr>

			<tr><td>Short Cuts</td><td>
				<ul class="quick_info">
					<li><a href="product_list.php">Show All</a></li>
					<li><a href="product_list.php?cmd=search&amp;sort=sa">Low Stock</a></li>
					<li><a href="product_list.php?cmd=search&amp;sort=ha">Popular</a></li>
					<li><a href="product_list.php?cmd=search&amp;sort=sl">Best Seller</a></li>
					<li><a href="product_list.php?cmd=search&amp;sort=dd">New Items</a></li>
					<li><a href="product.php"><span class="glyphicon glyphicon-plus"></span> Add New</a></li>
				</ul></td></tr>
		</table>

		<table class="table">
			<tr><td colspan="8" class="adminbg_h">Search Results</td></tr>
			<tr>
				<th style="text-align:center" width="10%"><input type="checkbox" onclick="SetAllCheckBoxes ('product_list', 'product_list', this.checked)" /></th>
				<th width="5%">ID /<br />Hits</th>
				<th width="20%">Category <span class="glyphicon glyphicon-info-sign help tips" title="Hover mouse on category name to see category structure."></span></th>
				<th width="35%">Title /<br />Summary</th>
				<th width="10%">Price / Sold</th>
				<th width="10%">Entry Date</th>
				<th width="10%">Stocks</th>
				<th width="5%">Edit</th>
			</tr>
			<!-- BEGINBLOCK list -->
			<tr>
				<td style="text-align:center"><label for="select_{$idx}"><img src="{$image_small}" style="width:50px" alt="{$title}" /></label> <br /><input type="checkbox" name="select_{$idx}" id="select_{$idx}" value="1" /></td>
				<td>{$idx}<div class="small">{$stat_hits}&times;</div></td>
				<td>{$category}</td>
				<td>{$title}<div class="small">{$summary}</div></td>
				<td>{$price} <div class="small">{$stat_purchased}&times;</div></td>
				<td>{$list_date}</td>
				<td>{$stock}</td>
				<td><a href="product.php?cmd=edit&amp;item_id={$idx}">Edit</a></td>
			</tr>
			<!-- ENDBLOCK -->
			<tr>
				<td style="text-align:center"><span class="glyphicon glyphicon-arrow-up"></span></td>
				<td colspan="6">
					<div class="pull-left">With selected:&nbsp;</div>
					<div class="pull-left">
						<ul class="quick_info">
							<li><button type="submit" name="cmd" value="qEdit">Quick Edit</button></li>
							<li><button type="submit" name="cmd" value="delAll" onclick="return askconfirm('X')">Delete</button></li>
						</ul>
					</div>
				</td>
			</tr>
		</table>
	</div>
</form>
{$pagination}
<script>
$(function(){
	$('#start_date').datepicker().on('changeDate',function(ev){update_date_form('start_date',ev.date);
	$('#start_date').datepicker('hide')});
	$('#end_date').datepicker().on('changeDate',function(ev){update_date_form('end_date',ev.date);
	$('#end_date').datepicker('hide')});
})


function update_cat (d)
{
	$('#cat_select').load('admin_ajax.php?cmd=cat_form&query=listing_list&dir_id='+d+'&cat_id={$cat_id}');
}

function askconfirm (w)
{
	c = confirm ('Are you sure to remove selected items?\nWARNING: This action can not be undone!');
	if (c) return true; else return false;
}
</script>