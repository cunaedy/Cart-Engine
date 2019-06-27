<?php
require './includes/user_init.php';

$code = get_param('code');
$view = get_param('view');

if ($view) {
    $mode = 'view';
} else {
    $mode = 'enter';
}

switch ($mode) {
    case 'enter':
        // get coupon info
        $row = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE gift_code='$code' LIMIT 1");

        // single use
        if (($row['coupon_type'] == 'once') && (($row['redeem_user_id'] != '') || ($row['redeem_order_id'] != ''))) {
            msg_die($lang['msg']['coupon_err']);
        }

        // update coupon -> change ownership to $current_user_id
        if ($sql_today > $row['valid_date']) {
            msg_die($lang['msg']['coupon_err']);
        }

        // any existing coupon? reset user id to empty
        sql_query("UPDATE ".$db_prefix."gift SET redeem_user_id='' WHERE redeem_user_id='$current_user_id' AND redeem_order_id=''");

        // single use -> add user_id to coupon
        if ($row['coupon_type'] == 'once') {
            sql_query("UPDATE ".$db_prefix."gift SET redeem_user_id='$current_user_id' WHERE gift_code='$code' LIMIT 1");
        }

        // multiuse -> create new coupon
        if ($row['coupon_type'] == 'multi') {
            $code = $code.'.'.random_str(6);
            sql_query("INSERT INTO ".$db_prefix."gift SET gift_code = '$code', master_code = '$row[gift_code]', gift_value = '$row[gift_value]',
			gift_pct='$row[gift_pct]', min_purchase = '$row[min_purchase]', valid_date = '$row[valid_date]', coupon_type = 'once', redeem_user_id = '$current_user_id'");
        }

        $msg = sprintf($lang['l_coupon_info'], $row['gift_pct'] ? round($row['gift_value']).'%' : num_format($row['gift_value'], 0, 1), num_format($row['min_purchase'], 0, 1), convert_date($row['valid_date']));
        msg_die(sprintf($lang['msg']['coupon_ok'], $msg));
    break;


    case 'view':
        // get coupon info
        $row = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE gift_code='$view' LIMIT 1");
        if (empty($row)) {
            msg_die($lang['msg']['coupon_err']);
        }

        $msg = sprintf($lang['l_coupon_info'], $row['gift_pct'] ? round($row['gift_value']).'%' : num_format($row['gift_value'], 0, 1), num_format($row['min_purchase'], 0, 1), convert_date($row['valid_date']));

        msg_die(sprintf($lang['msg']['coupon_ok'], $msg));
    break;
}
