<!-- BEGINIF $tpl_mode == 'list' -->
<ol class="breadcrumb">
	<li><a href="{$site_url}"><span class="glyphicon glyphicon-home"></span></a></li>
	<!-- BEGINBLOCK cat_bread_crumb -->
 	<li><a href="{$bc_link}">{$bc_title}</a></li>
	<!-- ENDBLOCK -->
	<li class="active">{$cat_name}</li>
</ol>

<h1 style="margin-top:0">{$cat_name}</h1>
{$cat_details}

<div class="row cat_list">
	<!-- BEGINBLOCK cat_list -->
	<div class="col-sm-4">
		<div><img src="{$cat_image}" class="pull-left"> {$cat_name}</div>
	</div>
	<!-- ENDBLOCK -->
</div>
<!-- ENDIF -->

<div class="row">
<!-- BEGINMODULE ce_core -->
mode = product_list
items = cat_featured
display = grid
out_of_stock = 1
csswrapper = col-sm-4
tag = FEATURED
<!-- ENDMODULE -->
</div>

<!-- BEGINIF $tpl_mode == 'list' -->
<!-- ELSE -->
<h1>{$l_search_result}</h1>
<!-- ENDIF -->

<!-- BEGINIF $no_search_result -->
<div>{$l_search_no_result}</div>
<!-- ELSE -->
<!-- BEGINBLOCK search_item -->
<div class="list_item">
	<div class="row">
	<div class="col-sm-4">
		<a href="{$site_url}/{$url}">{$image}</a>
		<div class="listbox_price">{$price} <div class="price_tax">{$price_tax}</div>
		</div>
		<div class="discount">{$discount}<br /><span class="msrp_price">{$price_msrp}</span></div>
	</div>
	<div class="col-sm-8">
		<a href="{$site_url}/{$url}">{$title}</a> {$digital}<br /><small>{$stock_status}</small>
		<p>{$short_details}</p>
		 {$cf_list}
	</div>
</div>
</div>
<!-- ENDBLOCK -->

{$pagination}
<!-- ENDIF -->

<script type="text/javascript">
function search_filter_submit ()
{
	var f = $('#search_filter_form').serialize();
	var g = $('#search_filter_main_form').serialize();
	$('#body_left').load('{$site_url}/shop_search.php?'+g+'&'+f);
	return false;
}

$(function(){
$('#search_filter_main_form select').change(function () { search_filter_submit() });
$('#search_filter_main_form input:radio').change(function () { search_filter_submit() });
$('#search_filter_main_form input:checkbox').change(function () { search_filter_submit() });
$('#search_filter_main_form').submit(function () { search_filter_submit() });
$('#search_filter').load('{$site_url}/ajax.php?cmd=search_filter&query=foo&{$query_url}');
});
</script>

<!-- BEGINSECTION cf_list -->
<span class="label label-default cf_list">{$cf_title}: {$cf_value}</span>
<!-- ENDSECTION -->