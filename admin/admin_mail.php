<?php
// part of qEngine
require './../includes/admin_init.php';
admin_check(4);

$mode = get_param('mode');
$order_id = get_param('order_id');
$user_id = get_param('user_id');
$email = get_param('email');
$txt['mode'] = $mode;
$txt['company_logo'] = '<img src="'.$config['site_url'].'/public/image/'.$config['company_logo'].'" alt="logo" />';
$tpl_mode = '';

switch ($mode) {
    case 'mail':
        if ($email) {
            $id['user_id'] = '';
            $id['user_email'] = $email;
        } else {
            $id = get_user_info($user_id);
        }
        $txt = array_merge($txt, $id);
        $txt['today'] = convert_date($sql_today);
        $txt['order_id'] = $order_id;
        $txt['subject'] = '';
        $txt['email_body'] = rte_area('email_body', quick_tpl(load_tpl('mail', 'admin_mail'), $txt));
        $txt['main_body'] = quick_tpl(load_tpl('adm', 'send_mail.tpl'), $txt);
        flush_tpl('adm');
    break;


    case 'cancel':
    case 'ship':
    case 'process':
    case 'pending':
        $send_now = get_param('send_now');
        $subject = sprintf(get_lang_line($config['original_default_lang'], 'l_mail_order_'.$mode), $config['site_name'], $order_id);
        $email_tpl = $mode;

        // ID
        // get summary
        $sum = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE order_id = '$order_id' LIMIT 1");
        $txt = array_merge($txt, $sum);

        $txt['user_id'] = $sum['fullname'];
        $txt['ship_address'] = $sum['ship_address'];
        $txt['order_date'] = convert_date($sum['order_date']);
        $txt['today'] = convert_date($sql_today);
        $txt['order_id'] = $order_id;
        $txt['subject'] = $subject;
        $txt['email_body'] = rte_area('email_body', quick_tpl(load_tpl('mail', $email_tpl), $txt));
        $txt['subject'] = $subject;
        $txt['main_body'] = quick_tpl(load_tpl('adm', 'send_mail.tpl'), $txt);
        if ($send_now) {
            email($sum['user_email'], $subject, quick_tpl(load_tpl('mail', $email_tpl), $txt), 1, 1);
            admin_die('admin_ok');
        } else {
            flush_tpl('adm');
        }
    break;
}
