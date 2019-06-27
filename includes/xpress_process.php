<?php
require "./user_init.php";
if (!$config['cart']['allow_xpress']) {
    msg_die(sprintf($lang['msg']['internal_error'], 'Express checkout is not enabled.'));
}

// ambil param
$cmd = post_param('cmd');
$user_email = post_param('user_email');
$fullname = post_param('fullname');
$bill_address = post_param('bill_address');
$bill_address2 = post_param('bill_address2');
$bill_city = post_param('bill_city');
$bill_state = post_param('bill_state');
$bill_country = post_param('bill_country');
$bill_zip = post_param('bill_zip');
$ship_address = post_param('ship_address');
$ship_address2 = post_param('ship_address2');
$ship_city = post_param('ship_city');
$ship_state = post_param('ship_state');
$ship_country = post_param('ship_country');
$ship_zip = post_param('ship_zip');
$phone = post_param('phone');
$visual = post_param('visual');
$nlt_sub = post_param('nlt_sub');

$err = array();
save_form('register');

// visual confirmation
// $x = qvc_value();
if (empty($visual) || qhash(strtolower($visual)) != qvc_value()) {
    $err[] = $lang['l_captcha_error'];
}

// get email in db
$row = sql_fetch_array(sql_query("SELECT user_email FROM ".$db_prefix."user WHERE user_email='$user_email'"));
if (!empty($row['user_email'])) {
    $err[] = "$lang[l_email_used]";
}

// validate entries
if (!validate_email_address($user_email)) {
    $err[] = "$lang[l_email_error]";
}

if (empty($fullname)) {
    $err[] = "$lang[l_name_empty]";
}
if (empty($bill_address)) {
    $err[] = "$lang[l_address_empty]";
}
if (empty($bill_city)) {
    $err[] = "$lang[l_city_empty]";
}
if (empty($bill_state)) {
    $err[] = "$lang[l_state_empty]";
}
if (empty($bill_country)) {
    $err[] = "$lang[l_country_empty]";
}
if (empty($bill_zip)) {
    $err[] = "$lang[l_zip_empty]";
}
if (empty($ship_address)) {
    $err[] = "$lang[l_address_empty]";
}
if (empty($ship_city)) {
    $err[] = "$lang[l_city_empty]";
}
if (empty($ship_state)) {
    $err[] = "$lang[l_state_empty]";
}
if (empty($ship_country)) {
    $err[] = "$lang[l_country_empty]";
}
if (empty($ship_zip)) {
    $err[] = "$lang[l_zip_empty]";
}

// if error -> HALT!
if (!empty($err)) {
    msg_die(sprintf($lang['msg']['register_error'], '<ul><li>'.implode('</li><li>', $err).'</li></ul>'));
}
reset_form();

sql_query("INSERT INTO ".$db_prefix."user SET user_id = '$current_user_id', user_passwd = '++XPRESS++', user_email = '$user_email', fullname = '$fullname',
bill_address = '$bill_address', bill_address2 = '$bill_address2', bill_city = '$bill_city', bill_state = '$bill_state', bill_country = '$bill_country', bill_zip = '$bill_zip',
ship_address = '$ship_address', ship_address2 = '$ship_address2', ship_city = '$ship_city', ship_state = '$ship_state', ship_country = '$ship_country', ship_zip = '$ship_zip',
phone = '$phone', user_level = '1', user_since = '$sql_today', admin_level = '0'");

// redir
$url = ip_config_value('redir');
if (!empty($url)) {
    ip_config_update('redir', '');
    redir($url);
} else {
    redir($config['site_url'].'/checkout.php?step=2');
}
