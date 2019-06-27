<?php
// get stock
function get_stock($item_id)
{
    global $db_prefix, $current_user_id;
    $res = sql_query("SELECT stock FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
    $row = sql_fetch_array($res);
    return $row['stock'];
}

// to add item to cart
function add_to_cart($item_id, $qty)
{
    global $sql_now, $current_user_id, $config, $db_prefix, $lang;
    $timeadd = time();
}


// start
require "./includes/user_init.php";

$cmd = get_param('cmd');
if (empty($cmd)) {
    $cmd = post_param('cmd');
}

switch ($cmd) {
    case 'add':
        foreach ($_POST['item_id'] as $key => $val) {
            $item_id = filter_param($val, '');
            $qty = filter_param($_POST['qty'][$key], '');
            if ($qty && $item_id) {
                // stock avail?
                $res = sql_query("SELECT * FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
                $prod = sql_fetch_array($res);
                if ($prod['digital_file'] && !$isLogin) {
                    msg_die($lang['l_digital_not_login']);
                }
                if ($prod['is_call_for_price']) {
                    msg_die($lang['l_cart_call_price']);
                }
                if ($prod['stock'] < 1) {
                    msg_die($lang['msg']['no_stock']);
                }
                if ($prod['stock'] < $qty) {
                    $qty = $prod['stock'];
                }

                // min / max
                $min_buy = $prod['min_buy'];
                $max_buy = empty($prod['max_buy']) ? 99999999 : ($prod['stock'] < $prod['max_buy'] ? $prod['stock'] : $prod['max_buy']);

                if ($qty < $min_buy) {
                    msg_die($lang['l_min_max_buy_err']);
                }
                if ($qty > $max_buy) {
                    msg_die($lang['l_min_max_buy_err']);
                }

                // add to cart
                $res = sql_query("SELECT idx FROM ".$db_prefix."orders WHERE item_id='$item_id' AND user_id='$current_user_id' LIMIT 1");
                $order = sql_fetch_array($res);
                if (empty($order['idx'])) {
                    sql_query("INSERT INTO ".$db_prefix."orders SET user_id='$current_user_id', item_id='$item_id', qty='$qty', timeadd=UNIX_TIMESTAMP()");
                } else {
                    // get num of stock
                    $avail = get_stock($item_id);

                    // get num of order
                    $row = sql_qquery("SELECT qty FROM ".$db_prefix."orders WHERE idx='$order[idx]' AND user_id='$current_user_id' LIMIT 1");

                    // order <= avail? add to cart
                    if ($avail < $row['qty'] + $qty) {
                        $qty = $avail - $row['qty'];
                    }
                    if ($row['qty'] + $qty > $max_buy) {
                        $qty = $max_buy - $row['qty'];
                    }
                    sql_query("UPDATE ".$db_prefix."orders SET qty = qty + $qty WHERE idx = $order[idx] AND user_id='$current_user_id' LIMIT 1");
                }
            }
        }

        // redirect back to previous page?
        if ($config['cart']['buy_display_cart']) {
            redir($config['site_url'].'/checkout.php');
        } else {
            msg_die($lang['l_product_added']);
        }
    break;


    case 'update':
        if (empty($_GET['qty'])) {
            redir();
        }

        foreach ($_GET['qty'] as $key => $val) {
            if (is_numeric($key) && is_numeric($val)) {
                if ($val <= 0) {
                    sql_query("DELETE FROM ".$db_prefix."orders WHERE idx='$key' AND user_id='$current_user_id' LIMIT 1");
                } else {
                    // get item_id
                    $row = sql_qquery("SELECT item_id FROM ".$db_prefix."orders WHERE idx='$key' AND user_id='$current_user_id' LIMIT 1");

                    // get num of stock
                    $avail = get_stock($row['item_id']);

                    if ($val > $avail) {
                        $qty = $avail;
                    } else {
                        $qty = $val;
                    }

                    sql_query("UPDATE ".$db_prefix."orders SET qty='$qty' WHERE idx='$key' AND user_id='$current_user_id' LIMIT 1");
                }
            }
        }
        redir();

    break;


    case 'del':
        $idx = get_param('idx');
        sql_query("DELETE FROM ".$db_prefix."orders WHERE idx='$idx' AND user_id='$current_user_id' LIMIT 1");
        redir();
    break;


    case 'drop':
        sql_query("DELETE FROM ".$db_prefix."orders WHERE user_id='$current_user_id'");
        redir();
    break;


    default:
        redir();
    break;
}
