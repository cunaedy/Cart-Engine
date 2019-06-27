<?php
require './includes/user_init.php';
require './includes/checkout_lib.php';

$step = get_param('step');
$shipper = get_param('shipper');
$payment = get_param('payment');
$weight = get_param('weight');
$total = get_param('total');
$item = get_param('item');
$order_notes = get_param('order_notes');
$summary = get_param('summary');
$all_digital = get_param('all_digital');

if (($step > 4) || ($step < 1)) {
    $step = 1;
}
switch ($step) {
    case 1:
        // get discount
        $disc = get_coupon();
        if ($disc['gift_code']) {
            $coupon_exists = true;
            $txt['coupon_code'] = $disc['gift_code'];
        } else {
            $coupon_exists = false;
            $txt['coupon_code'] = '';
        }

        // -- loading template
        if ($summary) {
            $tpl = load_tpl('checkout0.tpl');
        } else {
            $tpl = load_tpl('checkout1.tpl');
        }
        $cart = get_cart($tpl_block['checkout_item']);
        $txt['block_checkout_item'] = $cart['tpl'];
        if (!$cart['item_num']) {
            $no_item = true;
        } else {
            $no_item = false;
        }

        $txt['total_with_tax'] = num_format($cart['total'] + $cart['tax'], 0, 1);
        $txt['total'] = num_format($cart['total'], 0, 1);
        $txt['all_digital'] = $cart['all_digital'] ? qhash('1') : qhash('0');
        $txt['item'] = $cart['item_num'];
        $txt['total_num'] = $cart['total'];
        $txt['total_weight'] = $cart['weight'];
        $lang['l_gift_coupon_warning'] = sprintf($lang['l_gift_coupon_warning'], $txt['coupon_code']);
        generate_html_header("$config[site_name] $config[cat_separator] My Cart");

        if ($summary) {
            $txt['main_body'] = quick_tpl(load_tpl('checkout0.tpl'), $txt);
            flush_tpl('popup');
        } else {
            $txt['main_body'] = quick_tpl(load_tpl('checkout1.tpl'), $txt);
            flush_tpl();
        }

    break;


    case 2:
        // login?
        $xpress = false;

        if (!$isLogin) {
            // using xpress?
            $row = sql_qquery("SELECT * FROM ".$db_prefix."user WHERE user_id='$current_user_id' AND user_passwd='++XPRESS++' LIMIT 1");
            $xpress = true;

            // no xpress & no login => redir
            if (empty($row)) {
                ip_config_update('redir', $config['site_url']."/checkout.php?step=2&weight=$weight&total=$total&item=$item");
                redir($config['site_url'].'/profile.php?xpress=1');
            }
        }

        // -- checking cart (if no item, exit!)
        $cart = get_cart('foo');
        if (!$cart['item_num']) {
            msg_die($lang['msg']['no_item_in_cart']);
        }

        // addresses
        $user = get_user_info($current_user_id);
        $user['bill_address'] = format_billing_address($user);
        $user['ship_address'] = format_shipping_address($user);

        // shippers
        if ($cart['all_digital']) {
            $all_digital = true;
        } else {
            $all_digital = false;
        }
        if ($cart['all_digital'] && ($config['cart']['hide_ship'])) {
            $shipping_option = false;
        } else {
            $shipping_option = true;
        }
        $tpl = load_tpl('checkout2.tpl');
        $txt['block_courier_item'] = ''; $i = 0;

        if ($shipping_option) {
            $t = get_courier_fee(array('order_items' => $cart['item_num'], 'order_total' => $cart['total'], 'order_weight' => $cart['weight'], 'all_digital' => $cart['all_digital']), $user);
            foreach ($t as $val) {
                $val['i'] = $i++;

                if (empty($val['fee'])) {
                    $val['fee'] = '('.$lang['l_courier_free'].')';
                    if (count($t) == 1) {
                        $val['selected'] = 'checked="checked"';
                    } else {
                        $val['selected'] = '';
                    }
                } else {
                    $val['fee'] = num_format($val['fee'], 0, 1);
                    if (count($t) == 1) {
                        $val['selected'] = 'checked="checked"';
                    } else {
                        $val['selected'] = '';
                    }
                }

                $txt['block_courier_item'] .= quick_tpl($tpl_block['courier_item'], $val);
            }
        } else {
            $t = get_courier_fee(array('order_items' => $item, 'order_total' => $total, 'order_weight' => $weight, 'all_digital' => true), $user, 'ship_free');
        }


        // payment
        $txt['block_pay_item'] = ''; $i = 0;
        $t = get_payment_method();
        foreach ($t as $val) {
            $val['i'] = $i++;
            $val['fee'] = $val['fee'] ? num_format($val['fee'], 0, 1) : '-';
            if (count($t) == 1) {
                $val['selected'] = 'checked="checked"';
            } else {
                $val['selected'] = '';
            }
            $txt['block_pay_item'] .= quick_tpl($tpl_block['pay_item'], $val);
        }

        // display
        $txt = array_merge($txt, $user);
        $txt['main_body'] = quick_tpl($tpl, $txt);
        generate_html_header("$config[site_name] $config[cat_separator] Checkout 1/2");
        flush_tpl();
    break;


    case 3:
        // login?
        $xpress = false;
        $order_notes = safe_send($order_notes);

        if (!$isLogin) {
            // using xpress?
            $row = sql_qquery("SELECT * FROM ".$db_prefix."user WHERE user_id='$current_user_id' AND user_passwd='++XPRESS++' LIMIT 1");
            $xpress = true;

            // no xpress & no login => redir
            if (empty($row)) {
                ip_config_update('redir', $config['site_url'].'/checkout.php');
                redir($config['site_url'].'/profile.php?xpress=1');
            }
        }
        if (empty($shipper)) {
            msg_die($lang['msg']['shipper_not_selected']);
        }
        if (empty($payment)) {
            msg_die($lang['msg']['payment_not_selected']);
        }

        // -- loading template
        $tpl = load_tpl('checkout3.tpl');
        $cart = get_cart($tpl_block['checkout_item']);
        $txt['block_checkout_item'] = $cart['tpl'];

        // any item?
        if (!$cart['item_num']) {
            msg_die($lang['msg']['no_item_in_cart']);
        }

        // ship method set?
        $user = get_user_info($current_user_id);
        $ship = get_courier_fee(array('order_items' => $cart['item_num'], 'order_total' => $cart['total'], 'order_weight' => $cart['weight'], 'all_digital' => $cart['all_digital']), $user, $shipper);
        if (!$ship) {
            msg_die($lang['msg']['shipper_not_selected']);
        }

        // payment method set?
        $pay = get_payment_method($payment);
        if (!$pay) {
            msg_die($lang['msg']['payment_not_selected']);
        }
        $gtotal = $cart['gtotal'] + $ship['fee'] + $pay['fee'];

        // addresses
        $user = get_user_info($current_user_id);
        $txt['bill_address'] = format_billing_address($user);
        $txt['ship_address'] = format_shipping_address($user);
        if ($xpress) {
            $user['bill_address'] .= $lang['l_xpress_tag'];
        }
        if ($xpress) {
            $user['ship_address'] .= $lang['l_xpress_tag'];
        }

        // flush
        $txt['order_notes'] = $order_notes;
        $txt['shipping_cost'] = num_format($ship['fee'], 0, 1);
        $txt['payment_cost'] = $pay['fee'] ? num_format($pay['fee'], 0, 1) : '-';
        $txt['gift_code'] = $cart['gift_code'];
        $txt['coupon_disc'] = num_format($cart['discount'] * -1, 0, 1);
        $txt['total_weight'] = num_format($cart['weight'], 2);
        $txt['total_weight_ceil'] = ceil($cart['weight']);
        $txt['total'] = num_format($cart['total'], 0, 1);
        $txt['taxable'] = num_format($cart['taxable'], 0, 1);
        $txt['tax'] = num_format($cart['tax'], 0, 1);
        $txt['grand_total'] = num_format($gtotal, 0, 1);
        $txt['amount'] = $gtotal;
        $txt['shipping'] = $ship['fee'];
        $txt['payment_method'] = $pay['name'];
        $txt['shipping_method'] = $ship['name'];
        $txt['payment'] = $pay['method'];;
        $txt['shipper'] = $shipper;
        $txt['num_curr_name'] = $config['num_curr_name'];

        // output
        $txt['main_body'] = quick_tpl($tpl, $txt);
        generate_html_header("$config[site_name] $config[cat_separator] Checkout 2/2");
        flush_tpl();
    break;
}
