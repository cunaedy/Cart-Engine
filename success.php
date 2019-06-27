<?php
require './includes/user_init.php';
require './includes/checkout_lib.php';

$order_id = get_param('order_id');
$payment = get_param('payment');

// get info from order_id
$sum = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE order_id='$order_id' LIMIT 1");
$user = get_user_info($sum['user_id']);

// get payment 'how to pay' information
$form = get_payment_form($payment, $user, $sum);
if (!empty($form['txt_howtopay'])) {
    $howtopay = true;
    $txt['howtopay'] = $form['txt_howtopay'];
} else {
    $howtopay = false;
    $txt['howtopay'] = '';
}

// redirect?
if ($form['pay_redirect_to_gateway']) {
    $pay_redirect_to_gateway = true;
    $txt['method'] = $form['method'];
    $txt['action'] = $form['action'];
    $txt['hidden_field'] = $form['hidden'];
} else {
    $pay_redirect_to_gateway = false;
    $txt['hidden_field'] = $txt['method'] = $txt['action'] = '';
}

// remove xpress co user
if (!$isLogin) {
    // using xpress?
    $row = sql_qquery("SELECT * FROM ".$db_prefix."user WHERE user_id='$current_user_id' AND user_passwd='++XPRESS++' LIMIT 1");
    if ($row) {
        sql_query("DELETE FROM ".$db_prefix."user WHERE user_id='$current_user_id' AND user_passwd='++XPRESS++' LIMIT 1");
    }
}


// output
$txt['main_body'] = quick_tpl(load_tpl('success.tpl'), $txt);
flush_tpl();
