<?php
require "./includes/user_init.php";

$order_id = get_param('order_id');
$cmd = get_param('cmd');

if (!empty($order_id)) {
    if ($cmd == 'print') {
        $tpl = load_tpl('trx_print.tpl');
    } else {
        $tpl = load_tpl('trx.tpl');
    }
    $txt['block_trx_item'] = '';

    // get order summary
    $sum = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE user_id='$current_user_id' AND order_id='$order_id' LIMIT 1");
    if (empty($sum)) {
        msg_die('ORDER_ID_NOT_FOUND');
    }

    // get order details
    $total = $total_weight = 0;
    $res = sql_query("SELECT * FROM ".$db_prefix."order_final AS o JOIN ".$db_prefix."products AS p WHERE
						p.idx=o.item_id AND order_id = '$order_id' ORDER BY p.cat_id asc, p.title");
    while ($row = sql_fetch_array($res)) {
        $subtotal = $row['price'] * $row['qty'];
        $total += $subtotal;
        $item_id = $row['item_id'];
        $total_weight += $row['weight'] * $row['qty'];

        $row['digital'] = !empty($row['digital_file']) ? $lang['l_digital_icon'] : '';
        $row['price'] = num_format($row['price'], 0, 1);
        $row['subtotal'] = num_format($subtotal, 0, 1);
        $row['where_am_i'] = $ce_cache['cat_name_def'][$row['cat_id']];

        $txt['block_trx_item'] .= quick_tpl($tpl_block['trx_item'], $row);
    }

    // output
    if ($sum['order_shipped'] == '0000-00-00') {
        $txt['order_shipped'] = '-';
    }
    $txt['order_id'] = $order_id;
    $txt['order_date'] = convert_date($sum['order_date']);
    $txt['order_status'] = $order_status_def[$sum['order_status']];
    $txt['order_paystat'] = $payment_status_def[$sum['order_paystat']];
    $txt['total_weight'] = num_format($total_weight, 1);
    $txt['total'] = num_format($total, 0, 1);
    $txt['order_gtotal'] = num_format($sum['order_total'] + $sum['order_shipping_fee'] + $sum['order_payment_fee'] + $sum['order_tax'] - $sum['gift_discount'], 0, 1);
    $txt['order_payment_fee'] = num_format($sum['order_payment_fee'], 0, 1);
    $txt['order_shipping_fee'] = num_format($sum['order_shipping_fee'], 0, 1);
    $txt['gift_discount'] = num_format($sum['gift_discount'], 0, 1);
    $txt['order_tax'] = num_format($sum['order_tax'], 0, 1);
    $txt['order_total'] = num_format($sum['order_total'], 0, 1);
    $txt['bill_address'] = $sum['bill_address'];
    $txt['ship_address'] = $sum['ship_address'];
    $txt['order_date'] = convert_date($sum['order_date']);
    $txt['order_shipped'] = convert_date($sum['order_shipped']);
    $txt['shipping_method'] = $sum['order_shipper'];
    $txt['order_payment'] = $sum['order_payment'];
    $txt['site_address'] = format_address();
    $txt['today'] = convert_date('today');
    $lang['l_my_order_title'] = sprintf($lang['l_my_order_title'], $order_id, $txt['order_date']);
    $lang['l_ship_fee_for'] = sprintf($lang['l_ship_fee_for'], $txt['total_weight'], $lang['l_weight_name']);
    $lang['l_trx_print_footer'] = sprintf($lang['l_trx_print_footer'], $config['site_url'], $config['site_name']);

    $txt['main_body'] = quick_tpl($tpl, $txt);
    generate_html_header("$config[site_name] $config[cat_separator] My Transaction");
    if ($cmd == 'print') {
        flush_tpl('popup');
    } else {
        flush_tpl();
    }
} else {
    $tpl = load_tpl('trx_all.tpl');
    $txt['block_list'] = '';

    $res = sql_query("SELECT * FROM ".$db_prefix."order_summary WHERE user_id='$current_user_id' ORDER BY order_date DESC");
    while ($row = sql_fetch_array($res)) {
        $row['order_date'] = convert_date($row['order_date']);
        $row['order_status'] = $order_status_def[$row['order_status']];
        $row['order_paystat'] = $payment_status_def[$row['order_paystat']];
        $row['order_gtotal'] = num_format($row['order_total'] + $row['order_payment_fee'] + $row['order_shipping_fee'] + $row['order_tax'] - $row['gift_discount'], 0, 1);
        $row['order_total'] = num_format($row['order_total'], 0, 1);
        $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
    }

    $txt['main_body'] = quick_tpl($tpl, $txt);
    generate_html_header("$config[site_name] $config[cat_separator] My Transaction");
    flush_tpl();
}
