<div>
	<!-- BEGINMODULE slideshow -->theme=theme-welcome<!-- ENDMODULE -->
</div>

<div id="welcome" class="container">
	<!-- BEGINMODULE page_gallery -->
	// Welcome text
	page_id = 1
	body = 1
	<!-- ENDMODULE -->

	<h2>{$l_best_seller}</h2>
	<div class="row" id="welcome_best">
	<!-- BEGINMODULE ce_core -->
	mode = product_list
	items = site_featured
	display = grid
	limit = 4
	tag = FEATURED
	div_id = welcome_best
	csswrapper_grid = col-sm-4 col-md-3
	csswrapper_list = col-sm-12
	<!-- ENDMODULE -->
	</div>


	<h2>{$l_new_item}</h2>
	<div class="row" id="welcome_new">
	<!-- BEGINMODULE ce_core -->
	mode = product_list
	items = newest
	display = grid
	limit = 8
	tag = FRESH
	div_id = welcome_new
	csswrapper_grid = col-sm-4 col-md-3
	csswrapper_list = col-sm-12
	<!-- ENDMODULE -->
	</div>

	<h3 style="padding-top:10px">{$l_site_news}</h3>
	<!-- BEGINMODULE page_gallery -->
	// Display list of 5 pages from group 2 (news), all categories
	group_id = news
	title = 1
	thumb = 1
	summary = 1
	style = grid
	orderby = page_date
	sort = desc
	<!-- ENDMODULE -->

	<ul class="list_1">
		<li><a href="{$site_url}/{$news_url}">{$l_all_news}</a></li>
	</ul>
</div>