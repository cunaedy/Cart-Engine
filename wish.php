<?php
require './includes/user_init.php';

$cmd = get_param('cmd');
$uid = get_param('u');

if ($uid) {
    $view_self = false;
    $current_user_id = $uid;
} else {
    $view_self = true;
}


switch ($cmd) {
    case 'add':
        $item_id = get_param('item_id');

        // login?
        if (!$isLogin) {
            redir($config['site_url'].'/profile.php');
        }

        // item exists?
        $row = sql_qquery("SELECT * FROM ".$db_prefix."products WHERE idx = '$item_id' LIMIT 1");
        if (empty($row)) {
            msg_die($lang['msg']['item_id_not_found']);
        }

        $current_f = explode(',', $current_user_info['user_wish']);
        $current_f[] = $item_id;
        $current_f = array_clean(array_unique($current_f));
        $current = implode(',', $current_f);

        sql_query("UPDATE ".$db_prefix."user SET user_wish='$current' WHERE user_id='$current_user_id' LIMIT 1");
        redir();
    break;


    case 'del':
        $item_id = get_param('item_id');

        // login?
        if (!$isLogin) {
            redir($config['site_url'].'/profile.php');
        }

        $current_f = explode(',', $current_user_info['user_wish']);
        $j = array_search($item_id, $current_f);
        if ($j !== false) {
            unset($current_f[$j]);
        }
        $current_f = array_clean(array_unique($current_f));
        $current = implode(',', $current_f);


        sql_query("UPDATE ".$db_prefix."user SET user_wish='$current' WHERE user_id='$current_user_id' LIMIT 1");
        redir();
    break;


    default:
        $u = get_param('u');
        if ($u) {
            $foo = sql_qquery("SELECT user_wish FROM ".$db_prefix."user WHERE user_id='$u' LIMIT 1");
            $wish = $foo['user_wish'];
            if (!$wish) {
                $wish = 0;
            }
            $txt['wish'] = $wish;
            $txt['ones_wishlist'] = sprintf($lang['l_ones_wishlist'], ucwords($u));
            $txt['main_body'] = quick_tpl(load_tpl('wish.tpl'), $txt);
            generate_html_header("$config[site_name] $config[cat_separator] My Favorites");
            flush_tpl();
        } else {
            $txt['main_body'] = quick_tpl(load_tpl('wish.tpl'), $txt);
            generate_html_header("$config[site_name] $config[cat_separator] My Favorites");
            flush_tpl();
        }
    break;
}
