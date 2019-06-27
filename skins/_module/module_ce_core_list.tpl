<!-- BEGINSECTION list_gridbox -->
<div class="{$csswrapper}">
	<div class="list_item gridbox">
		<div class="ribbon"><span>{$tag}</span></div>
		<div class="gridbox_img"><a href="{$site_url}/{$url}">{$image}</a></div>
		<div class="gridbox_txt"><a href="{$site_url}/{$url}">{$title}</a> {$digital}<br /><small>{$stock_status}</small></div>
		<div class="gridbox_price" style="float:left">{$price} <div class="price_tax">{$price_tax}</div>
		</div>
		<div class="discount" style="float:right;text-align:right;margin-right:5px">{$discount}<br /><span class="msrp_price">{$price_msrp}</span></div>
	</div>
</div>
<!-- ENDSECTION -->

<!-- BEGINSECTION list_listbox -->
<div class="{$csswrapper}">
	<div class="list_item">
		<div class="row">
			<div class="ribbon"><span>{$tag}</span></div>
			<div class="col-sm-4">
				<a href="{$site_url}/{$url}">{$image}</a>
				<div class="listbox_price">{$price} <div class="price_tax">{$price_tax}</div></div>
				<div class="discount">{$discount}<br /><span class="msrp_price">{$price_msrp}</span></div>
			</div>
			<div class="col-sm-8">
				<a href="{$site_url}/{$url}">{$title}</a> {$digital}<br /><small>{$stock_status}</small>
				<p>{$short_details}</p>
				 {$cf_list}
			</div>
		</div>
	</div>
</div>
<!-- ENDSECTION -->

<!-- BEGINSECTION list_list -->
<div class="{$csswrapper} clearfix">
	<div class="pull-left" style="width:25%">{$image_small}</div>
	<div class="pull-right" style="width:75%"><a href="{$site_url}/{$url}" class="listtitle">{$title}</a><br />{$price}</div>
</div>
<!-- ENDSECTION -->

<!-- BEGINSECTION cf_list -->
<span class="label label-default cf_list">{$cf_title}: {$cf_value}</span>
<!-- ENDSECTION -->