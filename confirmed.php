<?php
require './includes/user_init.php';
require './includes/checkout_lib.php';

$shipper = get_param('shipper');
$payment = get_param('payment');
$order_notes = get_param('order_notes');
$order_notes = safe_receive($order_notes);

// login?
$xpress = false;
if (!$isLogin) {
    // using xpress?
    $row = sql_qquery("SELECT * FROM ".$db_prefix."user WHERE user_id='$current_user_id' AND user_passwd='++XPRESS++' LIMIT 1");
    $xpress = true;

    // no xpress & no login => redir
    if (empty($row)) {
        msg_die($lang['msg']['not_member']);
    }
}
if (empty($shipper)) {
    msg_die($lang['msg']['shipper_not_selected']);
}
if (empty($payment)) {
    msg_die($lang['msg']['payment_not_selected']);
}

// init vars
$order_id = create_order_id();

// build order list
$tpl = load_tpl('mail', 'checkout');
$tpl2 = load_tpl('mail', 'checkout_admin');
$cart = get_cart($tpl_block['checkout_item'], $tpl_block['checkout_item_adm'], true, $order_id);
$txt['block_checkout_item'] = $cart['tpl'];
$txt['block_checkout_item_adm'] = $cart['tpl_adm'];
if (!$cart['item_num']) {
    msg_die($lang['msg']['no_item_in_cart']);
}

// payment set?
$user = get_user_info($current_user_id);
$pay = get_payment_method($payment);
if (!$pay) {
    msg_die($lang['msg']['payment_not_selected']);
}

// ship method set?
$ship = get_courier_fee(array('order_items' => $cart['item_num'], 'order_total' => $cart['total'], 'order_weight' => $cart['weight'], 'all_digital' => $cart['all_digital']), $user, $shipper);
if (!$ship) {
    msg_die($lang['msg']['shipper_not_selected']);
}

// billing address
$txt['billing_address'] = format_billing_address($user);
$txt['shipping_address'] = format_shipping_address($user);

// grand total (incl. shipping & tax)
$gtotal = $cart['gtotal'] + $ship['fee'] + $pay['fee'];

// create summary
if ($xpress) {
    $xpress = 1;
} else {
    $xpress = 0;
}
$sql = "INSERT INTO ".$db_prefix."order_summary SET
		order_id = '$order_id',
		user_id = '$current_user_id',
		user_email = '$user[user_email]',
		order_items = '$cart[item_num]',
		order_weight = '$cart[weight]',
		order_date = '$sql_today',
		order_total = '$cart[total]',
		order_shipping_fee = '$ship[fee]',
		order_payment_fee = '$pay[fee]',
		order_tax = '$cart[tax]',
		gift_discount = '$cart[discount]',
		order_payment = '$pay[name]',
		order_shipper = '$ship[name]',
		fullname = '$user[fullname]',
		bill_address = '$txt[billing_address]',
		ship_address = '$txt[shipping_address]',
		order_notes = '$order_notes',
		xpress_co = '$xpress'";
sql_query($sql);

// update coupon/gift
if ($cart['gift_code']) {
    sql_query("UPDATE ".$db_prefix."gift SET redeem_order_id='$order_id', redeem_date=UNIX_TIMESTAMP(), redeem_purchase='$cart[total]' WHERE gift_code='$cart[self_gift_code]' LIMIT 1");
}

// prepare text for email to buyer & seller
$txt = array_merge($txt, $user);
if (empty($ship['fee'])) {
    $txt['shipping_cost'] = $lang['l_courier_free'];
} else {
    $txt['shipping_cost'] = num_format($ship['fee'], 0, 1);
}
if (empty($pay['fee'])) {
    $txt['payment_cost'] = $lang['l_courier_free'];
} else {
    $txt['payment_cost'] = num_format($pay['fee'], 0, 1);
}
$txt['shop_name'] = $config['site_name'];
$txt['shop_address'] = format_address('shop');
$txt['gift_code'] = $cart['gift_code'];
$txt['coupon_disc'] = num_format($cart['discount'] * -1, 0, 1);
$txt['total_weight'] = num_format($cart['weight'], 2);
$txt['total_weight_ceil'] = ceil($cart['weight']);
$txt['total'] = num_format($cart['total'], 0, 1);
$txt['taxable'] = num_format($cart['taxable'], 0, 1);
$txt['tax'] = num_format($cart['tax'], 0, 1);
$txt['grand_total'] = num_format($gtotal, 0, 1);
$txt['today'] = convert_date($sql_now, 'long');
$txt['order_id'] = $order_id;
$txt['shipping_method'] = $ship['name'];
$txt['payment_method'] = $pay['name'];
$txt['payment_status'] = $payment_status_def['E'];
$txt['adm_check_url'] = $config['site_url'].'/'.$config['admin_folder'].'/trx.php?order_id='.$order_id;
$txt['trx_check_url'] = $config['site_url']."/trx.php?order_id=$order_id";

// get payment 'how to pay' information
$summary = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE order_id='$order_id' LIMIT 1");
$txt['howtopay'] = get_payment_htp($payment, $summary);
if ($txt['howtopay']) {
    $howtopay = true;
} else {
    $howtopay = false;
}
$blah = quick_tpl(load_tpl('mail', 'checkout'), $txt);
$blah2 = quick_tpl(load_tpl('mail', 'checkout_admin'), $txt);

// send emails
email($user['user_email'], sprintf($lang['l_mail_order_subject'], $config['site_name'], $txt['order_id']), $blah, 1, 1);
email($config['site_email'], sprintf($lang['l_mail_order_admin_subject'], $config['site_name'], $txt['order_id']), $blah2, 1, 1);
create_notification('', 'You have a new order '.$txt['order_id'], $config['site_url'].'/'.$config['admin_folder'].'/trx.php?order_id='.$txt['order_id'], true);

###        STEP 12: redirect to payment gateway        ###
redir($config['site_url']."/success.php?order_id=$order_id&payment=$payment");
