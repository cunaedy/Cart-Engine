<?php
// part of qEngine

/******************************************************************

 CHECKOUT SPECIFIC FUNCTIONS

 These functions only required in checkout, so separated from /includes/local.php to speed things up a bit...

******************************************************************/


// $method => skip (0) to get all courier_fee information, fill with 'method' (flat, item, weight_1, etc) to get fee
function get_courier_fee($summary, $user, $method = '')
{
    global $config, $lang, $db_prefix, $current_user_info, $module_config;
    $tmp = array();

    // if shipper defined, return shipping cost for defined shipper; else return all alternative shippers & cost
    $w = ceil($summary['order_weight']);

    if ($method) {
        if ($x = strpos($method, '.')) {
            $mod = substr($method, 0, $x);
        } else {
            $mod = $method;
        }
        $row_mod = sql_qquery("SELECT * FROM ".$db_prefix."module WHERE mod_enabled = '1' AND mod_type = 'shipping' AND mod_id='$mod' LIMIT 1");
        if (!file_exists('./module/'.$mod.'/window.php')) {
            msg_die(sprintf($lang['msg']['internal_error'], 'Shipping module '.$method.' not found.'));
        }
        require('./module/'.$mod.'/window.php');
        return $ship_info;
    } else {
        $res_mod = sql_query("SELECT * FROM ".$db_prefix."module WHERE mod_enabled = '1' AND mod_type = 'shipping'");
        while ($row_mod = sql_fetch_array($res_mod)) {
            $method = $row_mod['mod_id'];
            $ship_info = array();
            if (!file_exists('./module/'.$method.'/window.php')) {
                msg_die(sprintf($lang['msg']['internal_error'], 'Shipping module '.$method.' not found.'));
            }
            require('./module/'.$method.'/window.php');
            if (!empty($ship_info)) {
                if (!empty($ship_info[0])) {
                    foreach ($ship_info as $k => $v) {
                        $tmp[$v['method']] = $v;
                    }
                } else {
                    $tmp[$method] = $ship_info;
                }
            }
        }
        return $tmp;
    }
}


// get list of available payment method
// $method = return only $method method info
function get_payment_method($method = '')
{
    global $db_prefix;
    $tmp = array();

    if (!$method) {
        $res = sql_query("SELECT * FROM ".$db_prefix."module WHERE mod_type='payment' AND mod_enabled = '1'");
        while ($row = sql_fetch_array($res)) {
            // get fee
            $module_config = $summary = array();
            $payment_cmd = 'init';
            $payment_extra_fee = 0;
            $mod = $row['mod_id'];
            if (!file_exists('./module/'.$mod.'/window.php')) {
                msg_die(sprintf($lang['msg']['internal_error'], 'Payment module '.$method.' not found.'));
            }
            require('./module/'.$mod.'/window.php');
            $tmp[$row['mod_id']]['method'] = $row['mod_id'];
            $tmp[$row['mod_id']]['name'] = $row['mod_name'];
            $tmp[$row['mod_id']]['fee'] = $payment_extra_fee;
        }

        return $tmp;
    } else {
        $payment_cmd = 'init';
        $payment_extra_fee = 0;
        $row = sql_qquery("SELECT * FROM ".$db_prefix."module WHERE mod_id='$method' AND mod_type='payment' AND mod_enabled = '1'");
        if (!$row) {
            return false;
        }
        $mod = $row['mod_id'];
        if (!file_exists('./module/'.$mod.'/window.php')) {
            msg_die(sprintf($lang['msg']['internal_error'], 'Payment module '.$method.' not found.'));
        }
        require('./module/'.$mod.'/window.php');
        return (array('method' => $row['mod_id'], 'name' => $row['mod_name'], 'fee' => $payment_extra_fee));
    }
}


// get payment form fields
// $user = array of user info (billing & shipping addresses)
// $summary = order summary: subtotal, discount, tax, total, shipping_fee, order_id
function get_payment_form($method, $user, $summary)
{
    global $config, $db_prefix, $current_user_id, $module_config;

    // name
    $myname = explode(' ', $user['fullname'], 2);
    $ship_fn = $myname[0];
    $ship_ln = empty($myname[1]) ? '' : $myname[1];

    $myname = explode(' ', $user['fullname'], 2);
    $bill_fn = $myname[0];
    $bill_ln = empty($myname[1]) ? '' : $myname[1];

    // summary
    $summary['order_total'] = number_format($summary['order_total'], 2, '.', '');

    // load related module
    $payment_cmd = 'form';
    if (!file_exists('./module/'.$method.'/window.php')) {
        msg_die(sprintf($lang['msg']['internal_error'], 'Payment module '.$method.' not found.'));
    }
    require('./module/'.$method.'/window.php');
    return $form;
}


// get how to pay information from a method
function get_payment_htp($method, $summary)
{
    global $config, $db_prefix, $current_user_id, $module_config;

    $payment_cmd = 'htp';
    if (!file_exists('./module/'.$method.'/window.php')) {
        msg_die(sprintf($lang['msg']['internal_error'], 'Payment module '.$method.' not found.'));
    }
    require('./module/'.$method.'/window.php');
    return $txt_howtopay;
}


// just get coupon ID
function get_coupon()
{
    // get voucher
    global $current_user_id, $db_prefix, $sql_today;

    // find single use
    $gift = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE redeem_user_id='$current_user_id' AND redeem_order_id='' AND valid_date >= '$sql_today' LIMIT 1");
    if (!empty($gift)) {
        $g = explode('.', $gift['gift_code']);
        $gift['gift_code'] = $g[0];
        return $gift;
    } else {
        return false;
    }
}


// get all applicable discount
function get_discount($total = 0)
{
    global $config, $db_prefix, $sql_today, $current_user_id;

    $discount = array('self_gift_code' => '', 'gift_code' => '', 'gift_flat' => 0, 'gift_pct' => 0);
    $gift_discount = false;

    // get gift
    $gift = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE redeem_user_id='$current_user_id' AND redeem_order_id='' AND valid_date >= '$sql_today' LIMIT 1");
    if (!empty($gift) && ($total >= $gift['min_purchase'])) {
        $gift_discount = true;
        $g = explode('.', $gift['gift_code']);
        $discount['self_gift_code'] = $gift['gift_code'];
        $discount['gift_code'] = $gift['gift_code'] = $g[0];
        if ($gift['gift_pct']) {
            $discount['gift_pct'] = $gift['gift_value'];
        } else {
            $discount['gift_flat'] = $gift['gift_value'];
        }
    }

    return $discount;
}


// $stock = num of stock
// $qty = num of order
function update_stock($idx, $stock, $qty, $digital)
{
    global $current_user_id, $config, $db_prefix;

    // if manage stock disabled
    if (!$config['cart']['manage_stock']) {
        return true;
    }

    // if digital => do NOT decrease stock!
    if ($digital) {
        return true;
    }

    // if it is actually out-of-stock (how can someone checkout for out-of-stock items?)
    if (!$stock) {
        sql_query("DELETE FROM ".$db_prefix."orders WHERE idx = '$idx' LIMIT 1");
        return false;
    } else {
        // if this is the last stock, delete all un-checked-out orders related to the item
        if ($stock == 1) {
            sql_query("DELETE FROM ".$db_prefix."orders WHERE idx = '$idx' LIMIT 1");
        }

        // decrease stock condition
        if ($stock < $qty) {
            $qty = $stock;
        }
        sql_query("UPDATE ".$db_prefix."products SET stock = stock-$qty WHERE idx = '$idx' LIMIT 1");

        return true;
    }
}


// create order ID, and check if the order id is already exist -> recreate/loop
// contribution: phillmcdonell - http://www.philmcdonnell.com/
function create_order_id()
{
    global $config, $db_prefix;
    switch ($config['cart']['order_id_format']) {
        case 1:
            $row = sql_qquery("SHOW TABLE STATUS LIKE '".$db_prefix."order_summary'");
            $now = $row['Auto_increment'] + 1;
            $l = strlen($now);
            for ($i = $l; $i < 6; $i++) {
                $now = '0'.$now;
            }
            return $config['cart']['order_id_prefix'].$now;
        break;


        case 2:
            $ok = false;
            while (!$ok) {
                $t = strtoupper(random_str(6));
                $res = sql_query("SELECT order_id FROM ".$db_prefix."order_summary WHERE order_id = '$t' LIMIT 1");
                $row = sql_fetch_array($res);
                if (empty($row['order_id'])) {
                    $ok = true;
                }
            }
            return $t;
        break;
    }
}


// $tpl = the template block (tpl_block) to format the cart output
// $tpl_adm = the tpl_block for administrator (used in confirmed.php), optional
// $update_stock = update stock (may result in different purchased qty), and move the order to final order table, default = false
// $order_id = order id for confirmed.php (update_stock = true)
function get_cart($tpl, $tpl_adm = '', $update_stock = false, $order_id = '')
{
    global $db_prefix, $config, $current_user_id, $ce_cache, $lang;
    $all_digital = true;
    $display = $display_adm = '';
    $i = $item = $total = $total_weight = $total_tax = $taxable = $total_disc = $gtotal = 0;


    $ssql = "FROM ".$db_prefix."products AS p JOIN ".$db_prefix."orders AS o
			WHERE p.idx=o.item_id AND user_id = '$current_user_id' ORDER BY cat_id asc, title asc";

    // get estimated total first to determine discount
    $row = sql_qquery("SELECT SUM(qty*price) AS est_total $ssql");
    $gift = get_discount($row['est_total']);

    $res = sql_query("SELECT p.*, p.idx AS item_id, o.qty, o.idx $ssql");
    while ($row = sql_fetch_array($res)) {
        $i++;

        if ($update_stock) {
            $stock_avail = update_stock($row['idx'], $row['stock'], $row['qty'], $row['digital_file']);
        } else {
            $stock_avail = true;
        }

        if ($stock_avail) {
            // multi liuer price
            $pq = unserialize($row['price_qty']);
            if ($pq) {
                foreach ($pq as $k => $v) {
                    if ($row['qty'] >= $k) {
                        $row['price'] = $v;
                    }
                }
            }

            $cat_id = $row['cat_id'];
            $item = $item + $row['qty'];
            $price = $row['price'];
            $subtotal = $row['price'] * $row['qty'];
            $disc = $subtotal * $gift['gift_pct'] / 100;
            $tax = get_tax($row['tax_class'], $price - $disc);
            $subtax = $tax * $row['qty'];
            $total_disc += $disc;
            $total_tax += $subtax;
            $total += $subtotal;
            $gtotal += $subtotal - $disc + $subtax;
            $total_weight += $row['weight'] * $row['qty'];

            if ($tax) {
                $taxable = $taxable + $subtotal;
            }
            if (empty($row['digital_file'])) {
                $all_digital = false;
            }
            if ($config['enable_adp'] && $row['permalink']) {
                $row['url'] = $row['permalink'];
            } else {
                $row['url'] = "detail.php?item_id=$row[item_id]";
            }

            $row['digital'] = !empty($row['digital_file']) ? $lang['l_digital_icon_small'] : '';
            $row['price'] = num_format($row['price'], 0, 1);
            $row['tax'] = num_format($tax, 0, 1);
            $row['total_tax'] = num_format($total_tax, 0, 1);
            $row['price_msrp'] = num_format($row['price_msrp']);
            $row['subtotal'] = num_format($subtotal, 0, 1);
            $row['subtotal_with_tax'] = num_format($subtotal + $total_tax, 0, 1);
            $row['subtotal_weight'] = $row['weight'] * $row['qty'];
            $row['distro'] = get_distro($row['distro']);
            $row['where_am_i'] = $ce_cache['cat_name_def'][$row['cat_id']];
            $row['image'] = make_thumb($row['item_id'].'_1', 'list');
            $row['image_small'] = make_thumb($row['item_id'].'_1', 'small');

            $display .= quick_tpl($tpl, $row);
            if ($tpl_adm) {
                $display_adm .= quick_tpl($tpl_adm, $row);
            }

            if ($update_stock) {
                // move data from ".$db_prefix."orders (tmp) to ".$db_prefix."order_final
                sql_query("INSERT INTO ".$db_prefix."order_final SET user_id='$current_user_id', cat_id='$row[cat_id]', item_id='$row[item_id]', title='$row[title]',
				weight='$row[weight]', price='$price', qty='$row[qty]', order_id='$order_id'");

                // remove temp orders
                sql_query("DELETE FROM ".$db_prefix."orders WHERE user_id='$current_user_id'");
            }
        }
    }

    $total_disc += $gift['gift_flat'];
    $gtotal = $gtotal - $gift['gift_flat'];
    $output = array('tpl' => $display, 'tpl_adm' => $display_adm, 'weight' => $total_weight, 'item_num' => $item, 'total' => $total, 'taxable' => $taxable, 'tax' => $total_tax,
        'discount' => $total_disc, 'gtotal' => $gtotal, 'all_digital' => $all_digital, 'gift_code' => $gift['gift_code'], 'self_gift_code' => $gift['self_gift_code']);
    return $output;
}
