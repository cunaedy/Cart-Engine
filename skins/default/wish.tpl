<!-- BEGINIF $view_self -->
<ol class="breadcrumb">
	<li><span class="glyphicon glyphicon-home"></span></li>
	<li><a href="{$site_url}/account.php">{$l_my_account}</a></li>
</ol>
<h1>{$l_my_wishlist}</h1>

<div class="row">
<!-- BEGINMODULE ce_core -->
mode = product_list
items = wish
limit = 9999
display = grid
csswrapper = col-sm-4
<!-- ENDMODULE -->
</div>

<p>{$l_share_wishlist}: <kbd>{$site_url}/wish.php?u={$current_user_id}</kbd></p>
<!-- ELSE -->
<ol class="breadcrumb">
	<li><span class="glyphicon glyphicon-home"></span></li>
	<li><a href="{$site_url}/account.php">{$l_my_account}</a></li>
</ol>

<h1>{$ones_wishlist}</h1>

<div class="row">
<!-- BEGINMODULE ce_core -->
mode = product_list
items = {$wish}
limit = 9999
display = grid
csswrapper = col-sm-4
<!-- ENDMODULE -->
</div>
<!-- ENDIF -->