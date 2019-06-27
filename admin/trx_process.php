<?php
require './../includes/admin_init.php';
admin_check(4);
AXSRF_check();

$order_id = post_param('order_id');
$order_status = post_param('order_status');
$order_paystat = post_param('order_paystat');
$notify = post_param('notify');
$tpl = '';

// get old status
$row = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE order_id = '$order_id' LIMIT 1");
$summary = $row;
$order_date = $row['order_date'];
if ($order_status == '-') {
    $order_status = $row['order_status'];
}
if ($order_paystat == '-') {
    $order_paystat = $row['order_paystat'];
}

//
$user_id = $row['user_id'];
$user = get_user_info($user_id);
if ($row['order_paystat'] != $order_paystat) {
    $update_paystat = true;
} else {
    $update_paystat = false;
}
if ($row['order_status'] != $order_status) {
    $update_status = true;
} else {
    $update_status = false;
}

// update payment status
if ($update_paystat) {
    // if payment status = CANCELLED -> CANCEL order
    if ($order_paystat == 'X') {
        $order_status = 'X';
        $update_status = true;
    }
    sql_query("UPDATE ".$db_prefix."order_summary SET order_paystat = '$order_paystat' WHERE order_id = '$order_id' LIMIT 1");
}

// update order status
// also do:
// send digital stuff for order_stat = P or D, ADD FILES TO FILE LIBRARY! P, may be because there are other physical goods?
if ($update_status) {
    switch ($order_status) {
        // if cancelled
        case 'X':
            $tpl = 'cancel';

            // update stock (increase)
            $res = sql_query("SELECT * FROM ".$db_prefix."order_final WHERE order_id = '$order_id'");
            while ($row = sql_fetch_array($res)) {
                $qty = $row['qty'];
                $item_id = $row['item_id'];
                sql_query("UPDATE ".$db_prefix."products SET stock = stock+$qty WHERE idx = '$item_id' LIMIT 1");
                sql_query("UPDATE ".$db_prefix."order_summary SET order_cancelled = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
            }

            sql_query("UPDATE ".$db_prefix."order_summary SET order_cancelled = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
        break;

        // if pending
        case 'E':
            $tpl = 'pending';
        break;

        // if process
        case 'P':
            $tpl = 'process';
        break;

        // if shipped
        case 'S':
            $tpl = 'ship';
            sql_query("UPDATE ".$db_prefix."order_summary SET order_shipped = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
        break;

        // if delivered
        case 'D':
            $foo = sql_qquery("SELECT order_shipped FROM ".$db_prefix."order_summary WHERE order_id = '$order_id' LIMIT 1");
            if ($foo['order_shipped'] == '0000-00-00') {
                sql_query("UPDATE ".$db_prefix."order_summary SET order_shipped = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
            }
            sql_query("UPDATE ".$db_prefix."order_summary SET order_delivered = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
        break;

        // if completed
        case 'C':
            $foo = sql_qquery("SELECT order_shipped, order_delivered FROM ".$db_prefix."order_summary WHERE order_id = '$order_id' LIMIT 1");
            if ($foo['order_shipped'] == '0000-00-00') {
                sql_query("UPDATE ".$db_prefix."order_summary SET order_shipped = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
            }
            if ($foo['order_delivered'] == '0000-00-00') {
                sql_query("UPDATE ".$db_prefix."order_summary SET order_delivered = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
            }

            sql_query("UPDATE ".$db_prefix."order_summary SET order_completed = '$sql_now' WHERE order_id = '$order_id' LIMIT 1");
        break;
    }

    // update order_stat
    sql_query("UPDATE ".$db_prefix."order_summary SET order_status = '$order_status' WHERE order_id = '$order_id' LIMIT 1");
}


// send digital products
if ($update_status && (($order_status == 'S') || ($order_status == 'P') || ($order_status == 'D') || ($order_status == 'C'))) {
    // -- digital file information
    $res = sql_query("SELECT p.title, p.idx, o.qty FROM ".$db_prefix."order_final AS o JOIN ".$db_prefix."products AS p WHERE o.item_id=p.idx AND order_id = '$order_id'");
    while ($row = sql_fetch_array($res)) {
        // sold + 1
        if ($order_status == 'S') {
            sql_query("UPDATE ".$db_prefix."products SET stat_purchased=stat_purchased+$row[qty], stat_last_purchased='$sql_today' WHERE idx='$row[idx]' LIMIT 1");
        }

        // digital
        if (empty($row['digital_prod'])) {
            $all_digi = false;
        }
    }

    $digi = send_digital_products($order_id, true);
}

if (($tpl == 'ship') || ($tpl == 'cancel') || ($tpl == 'pending') || ($tpl == 'process')) {
    if ($notify == 2) {
        redir($config['site_url']."/".$qe_admin_folder."/admin_mail.php?mode=$tpl&order_id=$order_id");
    } elseif ($notify == 1) {
        redir($config['site_url']."/".$qe_admin_folder."/admin_mail.php?mode=$tpl&order_id=$order_id&send_now=1");
    } else {
        admin_die('<h1>Success!</h1><p>Your changes have been saved. Notification email not sent.</p>');
    }
} else {
    admin_die('admin_ok');
}
