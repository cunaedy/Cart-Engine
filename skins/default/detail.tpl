<ol class="breadcrumb">
	<li><a href="{$site_url}"><span class="glyphicon glyphicon-home"></span></a></li>
	<!-- BEGINBLOCK cat_bread_crumb -->
 	<li><a href="{$bc_link}">{$bc_title}</a></li>
	<!-- ENDBLOCK -->
	<li class="active">{$title}</li>
</ol>

<!-- BEGINIF $add -->
 <div style="border:solid 1px black; background-color:#f3f3f3; margin: auto; padding: 5px; text-align: center">
  <b>{$l_product_added}</b>
 </div>
<!-- ENDIF -->

<!-- PRODUCT INFORMATION -->
<form method="post" enctype="multipart/form-data" action="{$site_url}/cart.php">
<!-- don't forget to close <form> tag using </form> tag down below..................... -->

	<input type="hidden" name="cmd" value="add" />
	<input type="hidden" name="item_id[0]" value="{$idx}" />
	<input type="hidden" name="qty[0]" value="{$min_qty}" />
	<div class="row">
		<div class="col-md-6 col-lg-5 detail_left">
			<!-- BEGINBLOCK thumb -->
			<div style="margin-top:5px">{$image}</div>
			<!-- ENDBLOCK -->
			<div style="clear:both;float:none"></div>
		</div>
		<div class="col-md-6 col-lg-7 detail_right">
			<h1>{$title}
			<!-- BEGINIF $digital_product -->
			{$l_digital_icon}
			<!-- ENDIF -->
			</h1>

			<!-- BEGINIF $enable_twitter_share -->
			<a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" style="vertical-align:text-bottom !important;margin-top:5px !important">Tweet</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<!-- ENDIF -->

			<!-- BEGINIF $enable_facebook_like -->
			<div class="fb-like" data-href="{$current_url}" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true" style="vertical-align:top !important"></div>
			<!-- ENDIF -->

			<div stle="clear:both"></div>
			<div class="detail_price">{$price}</div>
			<div class="detail_price_tax">{$price_tax}</div>
			<div style="clear:both"></div>
			<!-- BEGINIF $discount_status -->
			<div><strike>{$price_msrp}</strike> <span class="discount">{$discount}</span></div>
			<!-- ENDIF -->
			<table border="0" class="cf_table" width="100%" style="margin-top:20px">
				<tr><th width="35%">{$l_sku}</th><td width="65%">{$sku}</td></tr>
				<tr><th>{$l_manufacturer}</th><td><a href="shop_search.php?distro_id={$distro_id}">{$distro}</a></td></tr>
				<tr><th>{$l_stock_status}</th><td>{$stock_status}</td></tr>
				<tr><th>{$l_ship_weight}</th><td>{$weight} {$l_weight_name}</td></tr>
				<tr><th valign="top">{$l_quantity}</th><td><input type="text" name="qty[0]" value="{$min_qty}" size="3" style="padding:8px"/> <button type="submit" class="btn btn-primary">{$buy}</button>
					<!-- BEGINIF $qty_more -->
					<div class="help">({$l_min_buy}: {$min_buy} &bull; {$l_max_buy}: {$max_buy})</div>
					<!-- ENDIF -->
					<!-- BEGINIF $multier -->
					<table class="table table-condensed table-bordered" style="margin-top:10px">
						<tr><th style="border-top:none" width="60%">{$l_min_buy}</th><th style="text-align:right; border-top:none" width="40%">{$l_price}</th></tr>
						{$multier_list}
					</table>
					<!-- ENDIF -->

				</td></tr>
			</table>
		</div>
	</div>
	<!-- /PRODUCT INFORMATION -->

	<h2>{$l_description}</h2>
	{$details}

	<!-- BEGINIF $custom_field -->
	<h2>{$l_specification}</h2>
	<div class="panel panel-default">
	<table border="1" class="table table-bordered" width="100%">
		{$cf_list}
	</table>
	</div>
	<!-- ENDIF -->

	<!-- BEGINIF $sub_product -->
	<h2>{$l_buy_together}</h2>
	<table  class="cf_table" width="100%">
		<!-- BEGINBLOCK sp_list -->
		<tr><th id="sp_title_{$idx}" valign="top" width="35%">{$group_name}</th><td id="sp_value_{$idx}" valign="top" width="65%">{$sp_select} <input type="hidden" name="qty[]" value="1" /></td></tr>
		<!-- ENDBLOCK -->
	</table>
	<!-- ENDIF -->

	<h2>{$l_toolbox}</h2>
	<div class="list-group">
		<!-- BEGINIF $add_wish -->
		<a class="list-group-item" href="{$site_url}/wish.php?cmd=add&amp;item_id={$idx}"><span class="glyphicon glyphicon-heart-empty"></span> {$l_add_wishlist}</a>
		<!-- ELSE -->
		<a class="list-group-item" href="{$site_url}/wish.php?cmd=del&amp;item_id={$idx}"><span class="glyphicon glyphicon-heart"></span> {$l_remove_wishlist}</a>
		<!-- ENDIF -->
		<a class="list-group-item" href="{$site_url}/tell.php?item_id={$idx}&amp;who=friend"><span class="glyphicon glyphicon-share-alt"></span> {$l_share_title}</a>
		<a class="list-group-item" href="{$site_url}/tell.php?item_id={$idx}&amp;who=us"><span class="glyphicon glyphicon-envelope"></span> {$l_contact_product}</a>
	</div>

	<h2>{$l_review}</h2>
	<!-- BEGINMODULE qcomment -->
	mode = comment
	mod_id = product
	item_id = {$idx}
	sort = latest
	title = {$title}
	<!-- ENDMODULE -->

	<!-- BEGINIF $enable_facebook_comment -->
	<h2>{$l_facebook_comment}</h2>
	<div class="fb-comments" data-href="{$current_url}" data-numposts="5" data-colorscheme="light"></div>
	<div style="clear:both"></div>
	<!-- ENDIF -->
</form>
<!-- BEGINIF $see_also -->
<h1>{$l_see_also}</h1>
<!-- BEGINMODULE ce_core -->
mode = product_list
items = see_also
display = slider
<!-- ENDMODULE -->
<!-- ENDIF -->


<!-- BEGINSECTION cf_list -->
	<tr><th id="cf_title_{$cf_idx}" valign="top" width="35%">{$cf_title}</th><td id="cf_value_{$cf_idx}" valign="top" width="65%">{$cf_value}</td></tr>
<!-- ENDSECTION -->

<!-- BEGINSECTION cf_list_div -->
	<tr><td colspan="2"><h3 class="cf_div">{$cf_value}</h3></td></tr>
<!-- ENDSECTION -->

<!-- BEGINSECTION multier_list -->
	<tr><td align="center">{$min}</td><td align="right">{$price}</td>
<!-- ENDSECTION -->