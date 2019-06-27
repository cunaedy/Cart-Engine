<?php
// part of qEngine
require "./user_init.php";

// ambil param
$cmd = post_param('cmd');
$user_id = strtolower(post_param('user_id'));
$user_email = post_param('user_email');
$user_passwd = post_param('user_passwd');
$user_email = post_param('user_email');
$fullname = post_param('fullname');
$bill_address = post_param('bill_address');
$bill_address2 = post_param('bill_address2');
$bill_district = post_param('bill_district');
$bill_city = post_param('bill_city');
$bill_state = post_param('bill_state');
$bill_country = post_param('bill_country');
$bill_zip = post_param('bill_zip');
$ship_address = post_param('ship_address');
$ship_address2 = post_param('ship_address2');
$ship_district = post_param('ship_district');
$ship_city = post_param('ship_city');
$ship_state = post_param('ship_state');
$ship_country = post_param('ship_country');
$ship_zip = post_param('ship_zip');
$phone = post_param('phone');
$nlt_sub = post_param('nlt_sub');
$visual = post_param('visual');
$xpress = post_param('xpress');

$err = array();
save_form('register');

// xpress?
if ($xpress) {
    if (!$config['cart']['allow_xpress']) {
        msg_die('Express checkout is disabled!');
    }
    $user_id = $current_user_id;
    $user_passwd = '++XPRESS++';
}

// area
if ($config['cart']['ship_area'] == 'local') {
    $bill_city = $ship_city = $config['site_city'];
    $bill_state = $ship_state = $config['site_state'];
    $bill_country = $ship_country = $config['site_country'];
} elseif ($config['cart']['ship_area'] == 'state') {
    $bill_state = $ship_state = $config['site_state'];
    $bill_country = $ship_country = $config['site_country'];
} elseif ($config['cart']['ship_area'] == 'nation') {
    $bill_country = $ship_country = $config['site_country'];
}

// only for register
if ($cmd == 'address') {
    if (!$isLogin) {
        redir($config['site_url'].'/profile.php');
    }
} else {
    // visual confirmation
    $x = qvc_value();
    if (empty($visual) || qhash(strtolower($visual)) != $x) {
        $err[] = $lang['l_captcha_error'];
    }

    // get username in db
    $row = sql_qquery("SELECT user_id FROM ".$db_prefix."user WHERE user_id='$user_id' LIMIT 1");
    if (!empty($row['user_id'])) {
        $err[] = "$lang[l_username_used]";
    }

    // get email in db
    $row = sql_qquery("SELECT user_email FROM ".$db_prefix."user WHERE user_email='$user_email' LIMIT 1");
    if (!empty($row['user_email'])) {
        $err[] = "$lang[l_email_used]";
    }

    // validate entries
    if (!preg_match("/^[[:alnum:]]+$/", $user_id) && !$xpress) {
        $err[] = "$lang[l_username_error]";
    }
    if (!validate_email_address($user_email)) {
        $err[] = "$lang[l_email_error]";
    }
    if (empty($user_passwd)) {
        $err[] = "$lang[l_password_empty]";
    }
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

// if success -> SAVE to db
reset_form();
if (!$xpress) {
    $user_passwd = qhash($user_passwd);
}

if ($cmd == 'address') {
    sql_query("UPDATE ".$db_prefix."user SET fullname = '$fullname', phone = '$phone',
	bill_district = '$bill_district', bill_address = '$bill_address', bill_address2 = '$bill_address2', bill_city = '$bill_city', bill_state = '$bill_state', bill_country = '$bill_country', bill_zip = '$bill_zip',
	ship_district = '$ship_district', ship_address = '$ship_address', ship_address2 = '$ship_address2', ship_city = '$ship_city', ship_state = '$ship_state', ship_country = '$ship_country', ship_zip = '$ship_zip'
	WHERE user_id='$current_user_id' LIMIT 1");
    msg_die($lang['msg']['ok']);
} else {
    sql_query("INSERT INTO ".$db_prefix."user SET user_id = '$user_id', user_passwd = '$user_passwd', user_email = '$user_email', fullname = '$fullname',
	bill_district = '$bill_district', bill_address = '$bill_address', bill_address2 = '$bill_address2', bill_city = '$bill_city', bill_state = '$bill_state', bill_country = '$bill_country', bill_zip = '$bill_zip',
	ship_district = '$ship_district', ship_address = '$ship_address', ship_address2 = '$ship_address2', ship_city = '$ship_city', ship_state = '$ship_state', ship_country = '$ship_country', ship_zip = '$ship_zip',
	phone = '$phone', user_level = '1', user_since = '$sql_today', admin_level = '0'");

    // xpress?
    if ($xpress) {
        redir($config['site_url'].'/checkout.php?step=2');
    }

    // if user_activation required -> add user activation code
    $row['act'] = '';
    $act = false;
    if ($config['user_activation']) {
        $row['act'] = $act = random_str(16);
        sql_query("UPDATE ".$db_prefix."user SET user_activation='$act' WHERE user_id='$user_id' LIMIT 1");
    }

    // send email
    $row['user_id'] = $user_id;
    $row['user_passwd'] = post_param('user_passwd');
    $row['user_email'] = $user_email;
    $row['site_name'] = $config['site_name'];
    $row['site_url'] = $config['site_url'];

    $body = quick_tpl(load_tpl('mail', 'register'), $row);
    email($user_email, sprintf($lang['l_mail_register'], $config['site_name']), $body, true);

    // if user_activation -> tell to activate
    if ($config['user_activation']) {
        msg_die($lang['msg']['user_act'], $config['site_url']);
    } else {
        // if success, convert all temporary account to this account.
        $tmp_account = $current_user_id;
        sql_query("UPDATE ".$db_prefix."orders SET user_id='$user_id' WHERE user_id='$tmp_account'");
        sql_query("UPDATE ".$db_prefix."gift SET redeem_user_id='$user_id' WHERE redeem_user_id='$tmp_account'");

        // everything seems OK
        authorize_user($user_id, $user_passwd);

        // redir
        $url = ip_config_value('redir');
        if (!empty($url)) {
            ip_config_update('redir', '');
            msg_die($lang['msg']['register_ok'], $url);
        } else {
            msg_die($lang['msg']['register_ok'], $config['site_url']);
        }
    }
}
