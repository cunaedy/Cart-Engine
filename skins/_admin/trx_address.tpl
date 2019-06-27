<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html>
<head>
<meta http-equiv="Content-Language" content="en-us" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Print Address</title>
<link rel="stylesheet" type="text/css" href="../skins/_common/default.css" />
<link rel="stylesheet" type="text/css" href="../skins/_admin/style.css" />
</head>

<body style="background:none #fff">
<div style="width:500px;border:3px dashed;padding:20px" class="clearfix">
	<div class="pull-left" style="width:300px">
		<div style="font-size:9pt">
		<p><b>From:</b></p>
		<p>{$l_site_name}<br />
		{$site_address}</p></div>

		<p><b>Recipient:</b>
		{$ship_address}</p>
	</div>
	<div class="pull-right" style="width:150px">{$company_logo}
		<table class="table table-bordered" style="font-size:9px">
		<tr><th>ORDER ID</th></tr><tr><td>#{$order_id}</td></tr>
		<tr><th>Shipping Method</th></tr><tr><td>{$order_shipper}</td></tr>
	</table>
</div>

</div>
</body>
</html>