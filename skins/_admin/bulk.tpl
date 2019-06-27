<!-- BEGINIF $tpl_mode == 'default' -->
<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Bulk Products Upload</div>
	<form method="post" action="bulk.php" enctype="multipart/form-data">
	<input type="hidden" name="cmd" value="do" />
	<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form">
		<tr>
			<td colspan="2">
				<p>If you already have a product database in another format (eg. Microsoft Excel, MS Access, etc), you can use this feature to upload the list.</p>
				<p>It's quite easy, simply follow the <a href="bulk.php?cmd=help" target="_blank">guide here</a>.</p>
			</td>
		</tr>
		<tr>
			<td width="20%"><b>Tools</b></td>
			<td width="80%">
				<ul class="list_1">
					<li><a href="bulk.php?cmd=cat_list" target="_blank">Complete Category List</a></li>
					<li><a href="bulk.php?cmd=prod_list" target="_blank">Complete Product List</a></li>
					<li><a href="bulk.php?cmd=distro_list" target="_blank">Complete Brand List</a></li>
					<li><a href="bulk.php?cmd=tax_list" target="_blank">Complete Tax List</a></li>
				</ul>
			</td>
		</tr>
		<tr>
			<td style="vertical-align:top">CSV file to upload</td><td><input type="file" name="csv_file" />
				<label><input type="checkbox" name="title" value="1" /> First line is title (exclude first line)</label></p>
			</td>
		</tr>
		<tr>
			<td colspan="2"><button type="submit" class="btn btn-primary">Save</button></td>
		</tr>
	</table>
	</form>
</div>
<!-- ENDIF -->

<!-- BEGINIF $tpl_mode == 'cat_list' -->
<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Category List</div>
	<table class="table table-form" border="0" width="100%">
		<tr><th>Cat ID</th><th>Title</th><th>Structure</th></tr>
		<!-- BEGINBLOCK list -->
		<tr><td>{$idx}</td><td>{$cat_name}</td><td>{$cat_structure}</td></tr>
		<!-- ENDBLOCK -->
	</table>
</div>
<!-- ENDIF -->


<!-- BEGINIF $tpl_mode == 'prod_list' -->
<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Product List</div>
	<table class="table table-form" border="0" width="100%">
		<tr><th>Cat ID</th><th>Category</th><th>Item ID</th><th>Title</th></tr>
		<!-- BEGINBLOCK list -->
		<tr><td>{$cat_id}</td><td>{$cat_name}</td><td>{$idx}</td><td>{$title}</td></tr>
		<!-- ENDBLOCK -->
	</table>
</div>
<!-- ENDIF -->


<!-- BEGINIF $tpl_mode == 'distro_list' -->
<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Manufacturer List</div>
	<table class="table table-form" border="0" width="100%">
		<tr><th>Distro ID</th><th>Name</th><th>List of Products</th></tr>
		<!-- BEGINBLOCK list -->
		<tr><td>{$idx}</td><td>{$distro_name}</td><td>{$product}</td></tr>
		<!-- ENDBLOCK -->
	</table>
</div>
<!-- ENDIF -->

<!-- BEGINIF $tpl_mode == 'tax_list' -->
<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Tax List</div>
	<table class="table table-form" border="0" width="100%">
		<tr><th>Tax ID</th><th>Tax Name</th><th>Tax Rate</th></tr>
		<!-- BEGINBLOCK list -->
		<tr><td>{$idx}</td><td>{$tax_name}</td><td>{$tax_rate}</td></tr>
		<!-- ENDBLOCK -->
	</table>
</div>
<!-- ENDIF -->