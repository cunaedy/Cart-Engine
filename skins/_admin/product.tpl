<form method="post" action="product_process.php" enctype="multipart/form-data">
<input type="hidden" name="item_id" value="{$idx}" />

	<div class="panel panel-default">
		<div class="panel-heading"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span> Product Editor</div>
		<div class="panel-body">
			<ul id="product_tabs" class="nav nav-pills">
				<li class="active"><a href="#1" class="current" data-toggle="tab">General</a></li>
				<li><a href="#2" data-toggle="tab">Images</a></li>
				<li><a href="#3" data-toggle="tab">Custom Fields</a></li>
				<li><a href="#5" data-toggle="tab">Sub Products</a></li>
				<li><a href="#6" data-toggle="tab">Advanced</a></li>
				<li><a href="#7" data-toggle="tab">Help</a></li>
			</ul>
		</div>

		<div class="tab-content" style="margin-top:5px">
			<div class="tab-pane active" id="1">
				<table border="0" width="100%" class="table table-form" id="result1">
					<tr>
						<td colspan="2" class="adminbg_c">Product Description</td>
					</tr>
					<tr>
						<td width="20%">Item ID</td>
						<td width="80%">{$idx}</td>
					</tr>
					<tr>
						<td>SKU</td>
						<td><input type="text" size="16" maxlength="16" name="sku" value="{$sku}" class="narrow_input" /></td>
					</tr>
					<tr>
						<td>Main Category</td>
						<td>{$category_form} [ <a href="product_cat.php" target="_blank">Edit</a> ]</td>
					</tr>
					<tr>
						<td style="vertical-align:top">File for Digital Product</td>
						<td>
							<!-- BEGINIF $digital_product -->
							<div style="margin:2px 0 10px 0"><img src="../skins/_admin/images/digital.gif" alt="digital" /> /public/private/{$digital_file}
							<a href="javascript:confirm_delete()" class="warning_small">Click here to remove this file to make this product a physical product</a></div>

							<!-- ELSE -->
							<div class="warning_small">Upload a file to make this product a digital product</div>
							<!-- ENDIF -->
							<input type="file" name="digital_file" />
						</td>
					</tr>
					<tr>
						<td>Title</td>
						<td><input type="text" size="50" name="title" value="{$title}" required="required" /></td>
					</tr>
					<!-- BEGINIF $cmd == 'edit' -->
					<tr>
						<td>Preview</td>
						<td><a href="{$preview_url}" target="_blank">Click to view this item in your browser (save first)</a></td>
					</tr>
					<!-- ENDIF -->
					<tr>
						<td>Description (HTML allowed)</td>
						<td>{$details}</td>
					</tr>
					<tr>
						<td>Retail Price</td>
						<td>{$l_cur_name}<input type="text" size="15" name="price" value="{$price}" class="narrow_input" />
						{$call_price_select}
						<span class="glyphicon glyphicon-info-sign help tips" title="Set to 'Call for Price' to hide this item's price. Customers must contact you for more information."></span></td>
					</tr>
					<tr>
						<td>MSRP Price</td>
						<td>{$l_cur_name}<input type="text" size="15" name="price_msrp" value="{$price_msrp}" class="narrow_input" /></td>
					</tr>
					<tr>
						<td>Tax Class</td>
						<td>{$tax_select} <span class="glyphicon glyphicon-info-sign help tips" title="Set tax classes in Shop Settings page."></span></td>
					</tr>
					<tr>
						<td>Shipping Weight</td>
						<td><input type="text" size="5" name="weight" value="{$weight}" class="narrow_input" /> {$l_weight_name}</td>
					</tr>
					<tr>
						<td>Stock</td>
						<td><input type="text" size="5" name="stock" value="{$stock}" class="narrow_input" /></td>
					</tr>
					<tr>
						<td>Brands</td>
						<td>{$distro_select} [ <a href="edit_opt.php?fid=distro&clear_cache=everything" target="_blank">Edit</a> ]</td>
					</tr>
				</table>
			</div>


			<div class="tab-pane" id="2">
				<table border="0" width="100%" class="table table-form" id="result2">
					<tr>
						<td colspan="2" class="adminbg_c">Product Images</td>
					</tr>
					<!-- BEGINBLOCK thumb -->
					<tr>
						<td width="20%" align="center">[<a href="product.php?cmd=del_img&amp;item_id={$idx}&amp;x={$x}">Delete</a>]</td>
						<td width="80%"><a href="{$image}" class="lightbox"><img src="{$thumb}" alt="image" /></a></td>
					</tr>
					<!-- ENDBLOCK -->
					<!-- BEGINIF $no_image -->
					<tr><td width="20%">&nbsp;</td><td width="80%">No Image</td></tr>
					<!-- ENDIF -->
					<tr>
						<td style="vertical-align:top">Upload New Image</td>
						<td><input type="file" size="20" name="image" /><br />
						<span class="help">Note: to upload more images, submit this form, then a new image field will appear.</span></td>
					</tr>
				</table>
			</div>


			<div class="tab-pane" id="3">
				<table border="0" width="100%" class="table table-form" id="result6">
					<tr>
						<td colspan="2" class="adminbg_c">Product Custom Fields</td>
					</tr>
					<tr>
						<td style="vertical-align:top" width="20%"><p><b>Note</b></p></td>
						<td width="80%"><p>See Help tab for more information.</p></td>
					</tr>
					<!-- BEGINIF $cmd == 'edit' -->
					{$cf_form}
					<!-- ELSE -->
					<tr><td colspan="2">Please save this product first to use this feature.</td></tr>
					<!-- ENDIF -->
				</table>
			</div>


			<div class="tab-pane" id="5">
				<table border="0" width="100%" class="table table-form" id="result4">
					<tr>
						<td colspan="2" class="adminbg_c">Sub Products</td>
					</tr>
					<tr>
						<td style="vertical-align:top" width="20%"><p><b>Note</b></p></td>
						<td width="80%"><p>See Help tab for more information.</p></td>
					</tr>
					<!-- BEGINIF $cmd == 'edit' -->
					<!-- ELSE -->
					<tr><td colspan="2">Please save this product first to use this feature.</td></tr>
					<!-- ENDIF -->
				</table>
				<!-- BEGINIF $cmd == 'edit' -->
				<div id="sp_wrapper"></div>
				<!-- ENDIF -->
			</div>


			<div class="tab-pane" id="6">
				<table border="0" width="100%" class="table table-form" id="result4">
					<tr>
						<td colspan="2" class="adminbg_c">Advanced</td>
					</tr>
					<tr>
						<td style="vertical-align:top">Additional Categories</td>
						<td><div style="max-height:100px; overflow:auto; display:block" id="addcat">{$add_category_form}</div><br />
							<a href="#" onclick="addcatform()" id="addcatlink">show more</a></td>
					</tr>
					<tr>
						<td style="vertical-align:top">Wholesale Price</td>
						<td>
							<table border="0">
								<tr><th>Min Qty</th><th>Price</th></tr>
								<tr><td><input type="text" name="price_qty_q1" size="3" value="{$price_qty_q1}" /></td><td><input type="text" name="price_qty_p1" size="8" value="{$price_qty_p1}" /></td></tr>
								<tr><td><input type="text" name="price_qty_q2" size="3" value="{$price_qty_q2}" /></td><td><input type="text" name="price_qty_p2" size="8" value="{$price_qty_p2}" /></td></tr>
								<tr><td><input type="text" name="price_qty_q3" size="3" value="{$price_qty_q3}" /></td><td><input type="text" name="price_qty_p3" size="8" value="{$price_qty_p3}" /></td></tr>
								<tr><td><input type="text" name="price_qty_q4" size="3" value="{$price_qty_q4}" /></td><td><input type="text" name="price_qty_p4" size="8" value="{$price_qty_p4}" /></td></tr>
								<tr><td><input type="text" name="price_qty_q5" size="3" value="{$price_qty_q5}" /></td><td><input type="text" name="price_qty_p5" size="8" value="{$price_qty_p5}" /></td></tr>
							</table>
						</td>
					</tr>
					<tr>
						<td>Purchase quantity</td>
						<td>Minimum <input type="text" size="3" name="min_buy" value="{$min_buy}" class="narrow_input" />
							Maximum <input type="text" size="3" name="max_buy" value="{$max_buy}" class="narrow_input" />
							<span class="glyphicon glyphicon-info-sign help tips" title="Limit how many your customer can buy. Enter 0 as Maximum for unlimited (limited by stock)."></span></td>
					</tr>
					<tr>
						<td colspan="2" class="adminbg_c">Misc</td>
					</tr>
					<tr>
						<td>Permalink</td>
						<td><input type="text" size="50" name="permalink" value="{$permalink}" />
							<span class="glyphicon glyphicon-info-sign help tips" title="Leave empty to auto generate."></span></td>
					</tr>
					<tr>
						<td>Keywords</td>
						<td><input type="text" size="50" name="keywords" value="{$keywords}" />
						<span class="glyphicon glyphicon-info-sign help tips" title="For search engine optimization."></span></td>
					</tr>
					<tr>
						<td>See Also</td>
						<td><input type="text" size="40" name="see_also" value="{$see_also}" id="see_also" /></td>
					</tr>
					<tr>
						<td>Entry Date</td>
						<td>{$list_date}</td>
					</tr>
					<tr>
						<td>Invisible Item</td>
						<td><label><input type="checkbox" name="is_invisible" value="1" {$invisible_item_checked} /> Hide this item from buyers</label>
						<span class="glyphicon glyphicon-info-sign help tips" title="Check to hide this item from buyers. But buyers can still visit the page if you give them the URL, or if you include this item as sub products or featured products."></span></td>
					</tr>
					<!-- BEGINIF $cmd == 'edit' -->
					<tr>
						<td>Hits</td><td bgcolor="white">{$stat_hits} hits / Last: {$stat_last_hit}</td>
					</tr>
					<tr>
					<tr>
						<td>Sold</td><td bgcolor="white">{$stat_purchased} hits / Last: {$stat_last_purchased}</td>
					</tr>
					<tr>
						<td>Copy This Item</td>
						<td>
							<label><input type="checkbox" name="copy_item" value="1" onclick="copydiv()" id="copy_check" /> Copy this item</label>
							<div style="margin-left:20px; display:none" id="copydiv">
							<label><input type="checkbox" name="copy_cf" value="1" checked="checked" /> Along with its custom fields</label><br />
							<label><input type="checkbox" name="copy_img" value="1" checked="checked" /> Along with its images</label><br />
							<label><input type="checkbox" name="copy_switch" value="1" checked="checked" /> Switch to copied item</label>
							</div>
						</td>
					</tr>
					<tr>
						<td>Remove This Item</td>
						<td><label><input type="checkbox" name="del_item" value="1" /> <span style="color:#f00; background: #fff"><b>Delete this item from database</b></span></label></td>
					</tr>
					<!-- ENDIF -->
				</table>
			</div>


			<div class="tab-pane" id="7">
				<table cellpadding="2" cellspacing="2" border="0" width="100%" class="table table-form" id="result7">
					<tr>
						<td colspan="2" class="adminbg_c">Some Information You Should Know</td>
					</tr>

					<tr><td style="vertical-align:top" nowrap="nowrap" width="20%"><p><b>Custom Fields</b></p></td>
						<td width="80%">
							<p>By using a custom field, you can add more details to a product, for example, a custom field for a tablet pc would be: screen size,
							operating system, storage, RAM, etc.</p>
							<p>Later, your customers can filter &amp; search your products by using custom fields, for example, they can filter tablet pc on
							screen size, storage, etc.</p>
							<p><b>Creating a custom field is very easy:</b></p>
							<ol>
								<li>Open <a href="product_cf.php" target="_blank">Products &gt; Custom Fields</a> to create new options. Follow the instructions.</li>
								<li>Return to Product Manager.</li>
								<li>Click Custom Fields tab, and fill related fields.</li>
							</ol>
						</td>
					</tr>

					<tr><td style="vertical-align:top" nowrap="nowrap"><p><b>Sub Products</b></p></td>
						<td>
							<p>A sub-product is another product to be listed along with the main product, and it can be added to buyer's cart
							at the same time.</p>
							<p>For example: If the main product is a PC, sub products would include the monitor, hdd, cpu, etc.
							This allow a user to buy a monitor, hdd, cpu, etc with their PC without visiting each product page separately.</p>
						</td>
					</tr>

				</table>
			</div>
		</div>
	</div>
	<div style="text-align:right; padding:10px"><button type="submit" class="btn btn-primary">Save Product Information</button></div>
</form>

<script type="text/javascript">
$('#product_tabs').tab
$('#product_cf').load('admin_ajax.php?cmd=get_cf&cat_id={$cat_id},{add_category}&query={$idx}');
$('#sp_wrapper').load('product_sub.php?item_id={$idx}');
$("#see_also").tokenInput("admin_ajax.php?cmd=product", { queryParam:"query", preventDuplicates:true, prePopulate:{$see_also_preset}});
function confirm_delete ()
{
	c = window.confirm ("Do you wish to delete digital download file?\nThis process can not be undone!");
	if (!c) return;
	document.location = "product.php?cmd=del_digital&item_id={$idx}";
}

function copydiv ()
{
	c = $('#copy_check:checked').val();
	cd = $('#copydiv');
	if (c != null) cd.css('display','block'); else cd.css('display','none');
}

function addcatform ()
{
	if (addcatbig)
	{
		$('#addcat').css('maxHeight', '100px');
		$('#addcatlink').html('show more');
		addcatbig = false;
	}
	else
	{
		$('#addcat').css('maxHeight', '100%');
		$('#addcatlink').html('show less');
		addcatbig = true;
	}
}

var addcatbig = false;
</script>

<!-- BEGINSECTION cf_list -->
	<tr><td>{$cf_title} {$cf_help}</span>
	</td><td>{$cf_field}</td></tr>
<!-- ENDSECTION -->