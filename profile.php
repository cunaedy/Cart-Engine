<?php
// part of qEngine
require "./includes/user_init.php";

$mode = get_param('mode');
$xpress = get_param('xpress');

// shipping mode
$ship_area = $config['cart']['ship_area'];

switch ($mode) {
    case 'logout':
        kick_user();
    break;


    case 'lost':
        qvc_init(3);
        $tpl_mode = 'lost';
        $txt['main_body'] = quick_tpl(load_tpl('lost.tpl'), $txt);
        generate_html_header("$config[site_name] $config[cat_separator] Lost Password");
    break;


    case 'reset':
        qvc_init(3);
        $tpl_mode = 'reset';
        $row['user_id'] = get_param('user_id');
        $row['reset'] = get_param('reset');
        $txt['main_body'] = quick_tpl(load_tpl('lost.tpl'), $row);
        generate_html_header("$config[site_name] $config[cat_separator] Reset Password");
    break;


    case 'register':
    case 'xpress':
    case 'address':
        qvc_init(3);
        if (($mode != 'address') && ($isLogin)) {
            redir($config['site_url'].'/profile.php');
        }
        if (($mode == 'address') && (!$isLogin)) {
            redir($config['site_url'].'/profile.php');
        }
        if (!$row = load_form('register')) {
            if ($mode == 'address') {
                $row = sql_qquery("SELECT * FROM ".$db_prefix."user WHERE user_id='$current_user_id' LIMIT 1");
            } else {
                $row = create_blank_tbl($db_prefix.'user');
            }
        }

        // area
        $allow_city = $allow_state = $allow_country = true;
        if ($config['cart']['ship_area'] == 'local') {
            $allow_city = $allow_state = $allow_country = false;
            $row['bill_city'] = $row['ship_city'] = $config['site_city'];
            $row['bill_state'] = $row['ship_state'] = $config['site_state'];
            $row['bill_country'] = $row['ship_country'] = $config['site_country'];
        } elseif ($config['cart']['ship_area'] == 'state') {
            $allow_state = $allow_country = false;
            $row['bill_state'] = $row['ship_state'] = $config['site_state'];
            $row['bill_country'] = $row['ship_country'] = $config['site_country'];
        } elseif ($config['cart']['ship_area'] == 'nation') {
            $allow_country = false;
            $row['bill_country'] = $row['ship_country'] = $config['site_country'];
        } else {
            $country_def = get_country_list();
            $row['bill_country_select'] = create_select_form('bill_country', $country_def, $row['bill_country'] ? $row['bill_country'] : $config['site_country']);
            $row['ship_country_select'] = create_select_form('ship_country', $country_def, $row['ship_country'] ? $row['ship_country'] : $config['site_country']);
        }
        $txt = array_merge($txt, $row);

        if ($mode == 'register') {
            $tpl_mode = 'register';
            $txt['main_body'] = quick_tpl(load_tpl('register.tpl'), $txt);
        } elseif ($mode == 'address') {
            $tpl_mode = 'address';
            $txt['main_body'] = quick_tpl(load_tpl('register.tpl'), $txt);
        } else {	// xpress c.o
            $tpl_mode = 'xpress';
            sql_query("DELETE FROM ".$db_prefix."user WHERE user_id='$current_user_id' LIMIT 1");
            if (!$config['cart']['allow_xpress']) {
                msg_die('Express checkout is not enabled.');
            }
            $txt['main_body'] = quick_tpl(load_tpl('register.tpl'), $txt);
        }

        if ($mode == 'address') {
            generate_html_header("$config[site_name] $config[cat_separator] My Addresses");
        } else {
            generate_html_header("$config[site_name] $config[cat_separator] Registration");
        }
    break;


    case 'act':
        $row['user_id'] = get_param('user_id');
        $row['act'] = get_param('act');
        $txt['main_body'] = quick_tpl(load_tpl('act.tpl'), $row);
        generate_html_header("$config[site_name] $config[cat_separator] Account Activation");
    break;


    default:
        if (!$isLogin) {
            // login form
            qvc_init(3);
            $profile_mode = 'login';
            if (!$config['cart']['allow_xpress']) {
                $xpress = false;
            }
            $txt['url'] = get_param('url');
            $txt['main_body'] = quick_tpl(load_tpl('login.tpl'), $txt);
            generate_html_header("$config[site_name] $config[cat_separator] Login");
        } else {
            // get ID
            $res = sql_query("SELECT * FROM ".$db_prefix."user WHERE user_id = '$current_user_id' LIMIT 1");
            $row = sql_fetch_array($res);
            $txt['main_body'] = quick_tpl(load_tpl('profile.tpl'), $row);
            generate_html_header("$config[site_name] $config[cat_separator] My Profile");
        }
    break;
}

flush_tpl();
