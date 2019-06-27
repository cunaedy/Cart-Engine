<?php
require './../includes/admin_init.php';
admin_check(4);

$cmd = get_param('cmd');
$order_id = get_param('order_id');

if ($cmd == 'print') {
    $tpl = load_tpl('adm', 'trx_print.tpl');
} else {
    $tpl = load_tpl('adm', 'trx.tpl');
}
$txt['block_trx_item'] = '';

// get order detail & user_id
// -- get username
$summary = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE order_id = '$order_id' LIMIT 1");
if (empty($summary)) {
    if ($cmd == 'print') {
        just_die('Error!', 'Order ID not found!');
    } else {
        admin_die('echo', 'Order ID not found!', $config['site_url'].'/'.$config['admin_folder'].'/trx_list.php');
    }
};
$user_id = $summary['user_id'];

// -- get gift id
$gift = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE redeem_order_id='$order_id' LIMIT 1");

// -- build order list
$txt['block_trx_item'] = ''; $i = $total_weight = $total = 0;
$res = sql_query("SELECT *, o.price AS theprice FROM ".$db_prefix."order_final AS o JOIN ".$db_prefix."products AS p WHERE
					p.idx=o.item_id AND order_id = '$order_id' ORDER BY p.cat_id asc, p.title");
while ($row = sql_fetch_array($res)) {
    $i++;
    $subtotal = $row['theprice'] * $row['qty'];
    $total += $subtotal;
    $item_id = $row['item_id'];
    $total_weight += $row['weight'] * $row['qty'];
    process_product_info($row);

    $row['digital'] = !empty($row['digital_file']) ? '(digital product)' : '';
    $row['price'] = num_format($row['theprice'], 0, 1);
    $row['subtotal'] = num_format($subtotal, 0, 1);
    $row['where_am_i'] = $ce_cache['cat_name_def'][$row['cat_id']];

    $txt['block_trx_item'] .= quick_tpl($tpl_block['trx_item'], $row);
}

$grand_total = $summary['order_total'] + $summary['order_shipping_fee'] + $summary['order_tax'] - $summary['gift_discount'];

// calculate weight & total
$txt['total'] = num_format($total, 0, 1);
$txt['gift_discount'] = $summary['gift_discount'] ? '-'.num_format($summary['gift_discount'], 0, 1) : 0;
$txt['gift_code'] = empty($gift['gift_code']) ? '' : (strpos($gift['gift_code'], '_') ? substr($gift['gift_code'], 0, strpos($gift['gift_code'], '_')) : $gift['gift_code']);
$txt['total_weight'] = num_format($total_weight, 2);
$txt['total_weight_ceil'] = ceil($total_weight);
$txt['order_payment_fee'] = num_format($summary['order_payment_fee'], 0, 1);
$txt['shipping_cost'] = $txt['order_shipping_fee'] = num_format($summary['order_shipping_fee'], 0, 1);
$txt['shipping_method'] = $summary['order_shipper'];
$txt['order_tax'] = num_format($summary['order_tax'], 0, 1);
$txt['grand_total'] = num_format($grand_total, 0, 1);
$txt['user_id'] = $summary['user_id'];
$txt['fullname'] = $summary['fullname'];
$txt['order_id'] = $order_id;

// ID
$uid = get_user_info($summary['user_id']);
$txt['user_since'] = empty($uid['user_since']) ? 'Xpress Checkout' : convert_date($uid['user_since']);
$txt['user_email'] = empty($uid['user_email']) ? 'Xpress Checkout' : $uid['user_email'];

//
$foo = $order_status_def['X']; unset($order_status_def['X']);
$order_status_def['-'] = '---'; $order_status_def['X'] = $foo;

$foo = $payment_status_def['X']; unset($payment_status_def['X']);
$payment_status_def['-'] = '---'; $payment_status_def['X'] = $foo;


// get summary
$res = sql_query("SELECT * FROM ".$db_prefix."order_summary WHERE order_id = '$order_id' LIMIT 1");
$sum = sql_fetch_array($res);
if (empty($sum)) {
    admin_die('echo', 'Order not found!');
}
if ($sum['order_status'] == 'X') {
    $order_dis = 1;
} else {
    $order_dis = 0;
}
if ($sum['order_paystat'] == 'X') {
    $pay_dis = 1;
} else {
    $pay_dis = 0;
}
if (empty($sum['order_notes'])) {
    $txt['order_notes'] = 'n/a';
} else {
    $txt['order_notes'] = $sum['order_notes'];
}

$txt['user_id'] = $sum['user_id'];
$txt['order_status'] = create_select_form('order_status', $order_status_def, $sum['order_status'], '', $order_dis);
$txt['order_payment'] = $sum['order_payment'];
$txt['order_paystat'] = create_select_form('order_paystat', $payment_status_def, $sum['order_paystat'], '', $pay_dis);
$txt['bill_address'] = $sum['bill_address'];
$txt['ship_address'] = $sum['ship_address'];
$txt['site_address'] = format_address();
$txt['order_date'] = convert_date($sum['order_date']);
$txt['order_shipped'] = convert_date($sum['order_shipped']);
$txt['order_delivered'] = convert_date($sum['order_delivered']);
$txt['order_completed'] = convert_date($sum['order_completed']);
$txt['order_cancelled'] = convert_date($sum['order_cancelled']);
$txt['notify_select'] = create_select_form('notify', array(1 => 'Auto', 2 => 'Manual', 3 => 'No'));

// output
if (substr($txt['user_id'], 0, 6) == 'guest*') {
    $txt['user_id'] = '[ Xpress Checkout ]';
}
$txt['order_id'] = $order_id;
$txt['main_body'] = quick_tpl($tpl, $txt);
if ($cmd == 'print') {
    flush_tpl('adm_popup');
} else {
    flush_tpl('adm');
}
