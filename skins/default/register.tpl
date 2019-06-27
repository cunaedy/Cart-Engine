<!-- BEGINIF $tpl_mode == 'register' -->
<ol class="breadcrumb">
	<li><a href="{$site_url}"><span class="glyphicon glyphicon-home"></span></a></li>
	<li class="active">{$l_register}</li>
</ol>

<h1>{$l_register}</h1>
<form method="post" action="{$site_url}/includes/register_process.php" name="register" id="register" onsubmit="return checkform()">
	<div class="table_div">
		<div>
			<legend>{$l_username}</legend>
			<div><input type="text" name="user_id" id="user_id" value="{$user_id}" size="31" maxlength="80" required="required" /> <span id="user_id_ok"></span> <span class="required">&bull;</span></div>
		</div>
		<div>
			<legend>{$l_password}</legend>
			<div><input type="password" name="user_passwd" id="user_passwd" size="29" maxlength="255" onkeyup="passwordStrength('user_passwd',this.value)" required="required" /></div>
		</div>
		<div>
			<legend>{$l_email_address}</legend>
			<div><input type="email" name="user_email" id="user_email" value="{$user_email}" size="42" maxlength="255" required="required" /> <span id="user_email_ok"></span> <span class="required">&bull;</span></div>
		</div>
<!-- ENDIF -->

<!-- BEGINIF $tpl_mode == 'xpress' -->
<ol class="breadcrumb">
	<li><a href="{$site_url}"><span class="glyphicon glyphicon-home"></span></a></li>
	<li class="active">{$l_register}</li>
</ol>

<h1>{$l_express_checkout}</h1>
<p>{$l_express_checkout_tips}</p>
<form method="post" action="{$site_url}/includes/register_process.php" name="register" id="register" onsubmit="return checkform()">
	<div class="table_div">
		<input type="hidden" name="xpress" value="1" />
		<div>
			<legend>{$l_email_address}</legend>
			<div><input type="email" name="user_email" id="user_email" value="{$user_email}" size="42" maxlength="255" required="required" /> <span id="user_email_ok"></span> <span class="required">&bull;</span></div>
		</div>
<!-- ENDIF -->

<!-- BEGINIF $tpl_mode == 'address' -->
<ol class="breadcrumb">
	<li><a href="{$site_url}"><span class="glyphicon glyphicon-home"></span></a></li>
	<li class="active">{$l_my_account}</li>
</ol>

<h1>{$l_my_account}</h1>
<form method="post" action="{$site_url}/includes/register_process.php" name="register" id="register" onsubmit="return checkform()">
	<input type="hidden" name="cmd" value="address" />
	<div class="table_div">
<!-- ENDIF -->

		<div>
			<legend>{$l_your_name}</legend>
			<div><input type="text" name="fullname" value="{$fullname}" maxlength="100" required="required" /></div>
		</div>
		<div>
			<legend>{$l_phone_number}</legend>
			<div><input type="text" name="phone" value="{$phone}" size="17" maxlength="20" required="required" /></div>
		</div>
		<h3>{$l_bill_detail}</h3>
		<div>
			<legend>{$l_address}</legend>
			<div><input type="text" name="bill_address" value="{$bill_address}" maxlength="100" required="required" /></div>
		</div>
		<div>
			<legend>&nbsp;</legend>
			<div><input type="text" name="bill_address2" value="{$bill_address2}" maxlength="100" /></div>
		</div>
		<div>
			<legend>{$l_district}</legend>
			<div><input type="text" name="bill_district" value="{$bill_district}" maxlength="80" placeholder="{$l_if_applicable}" /></div>
		</div>
		<div>
			<legend>{$l_city}</legend>
			<!-- BEGINIF $allow_city -->
			<div><input type="text" name="bill_city" value="{$bill_city}" maxlength="80" required="required" /></div>
			<!-- ELSE -->
			<div>{$bill_city}</div>
			<!-- ENDIF -->
		</div>
		<div>
			<legend>{$l_state}</legend>
			<!-- BEGINIF $allow_state -->
			<div><input type="text" name="bill_state" value="{$bill_state}" maxlength="80" required="required" /></div>
			<!-- ELSE -->
			<div>{$bill_state}</div>
			<!-- ENDIF -->
		</div>
		<div>
			<legend>{$l_country}</legend>
			<!-- BEGINIF $allow_country -->
			<div>{$bill_country_select}</div>
			<!-- ELSE -->
			<div>{$bill_country}</div>
			<!-- ENDIF -->
		</div>
		<div>
			<legend>{$l_zipcode}</legend>
			<div><input type="text" name="bill_zip" value="{$bill_zip}" maxlength="15" required="required" /></div>
		</div>
		<div style="clear:both"></div>
		<h3>{$l_ship_detail} <a onclick="copy_fields()" class="btn btn-default btn-xs">{$l_copy_bill}</a></h3>
		<div>
			<legend>{$l_address}</legend>
			<div><input type="text" name="ship_address" value="{$ship_address}" maxlength="100" required="required" /></div>
		</div>
		<div>
			<legend>&nbsp;</legend>
			<div><input type="text" name="ship_address2" value="{$ship_address2}" maxlength="100" /></div>
		</div>
		<div>
			<legend>{$l_district}</legend>
			<div><input type="text" name="ship_district" value="{$ship_district}" maxlength="80" placeholder="{$l_if_applicable}" /></div>
		</div>
		<div>
			<legend>{$l_city}</legend>
			<!-- BEGINIF $allow_city -->
			<div><input type="text" name="ship_city" value="{$ship_city}" maxlength="80" required="required" /></div>
			<!-- ELSE -->
			<div>{$ship_city}</div>
			<!-- ENDIF -->
		</div>
		<div>
			<legend>{$l_state}</legend>
			<!-- BEGINIF $allow_state -->
			<div><input type="text" name="ship_state" value="{$ship_state}" maxlength="80" required="required" /></div>
			<!-- ELSE -->
			<div>{$ship_state}</div>
			<!-- ENDIF -->
		</div>
		<div>
			<legend>{$l_country}</legend>
			<!-- BEGINIF $allow_country -->
			<div>{$ship_country_select}</div>
			<!-- ELSE -->
			<div>{$ship_country}</div>
			<!-- ENDIF -->
		</div>
		<div>
			<legend>{$l_zipcode}</legend>
			<div><input type="text" name="ship_zip" value="{$ship_zip}" maxlength="15" required="required" /></div>
		</div>
		<div>
			<legend>{$l_enter_captcha}</legend>
			<div><img src="visual.php" alt="robot?" /><br /><input type="text" name="visual" size="5" maxlength="5" required="required" /> <span class="required">&bull;</span></div>
		</div>
	</div>
	<div style="clear:both"></div>
	<p align="center"><button type="submit" class="btn btn-primary">{$l_register}</button></p>
</form>

<script>
function copy_fields ()
{
	var f = document.forms['register'];
	f.ship_address.value=f.bill_address.value;
	f.ship_address2.value=f.bill_address2.value;
	f.ship_district.value=f.bill_district.value;
	f.ship_zip.value=f.bill_zip.value;
	f.ship_city.value=f.bill_city.value;
	f.ship_state.value=f.bill_state.value;
	f.ship_country.value=f.bill_country.value;
}

$('#user_id').blur (function () { validateByAjax ('#user_id', '{$site_url}/ajax.php?cmd=userOk', '#user_id_ok'); });
$('#user_email').blur (function () { validateByAjax ('#user_email', '{$site_url}/ajax.php?cmd=emailOk', '#user_email_ok'); });
</script>