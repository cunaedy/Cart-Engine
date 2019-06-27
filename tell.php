<?php
// part of qEngine
require './includes/user_init.php';

$item_id = get_param('item_id');
$who = get_param('who');
$cmd = get_param('cmd');
if (empty($cmd)) {
    $cmd = post_param('cmd');
}
if (empty($item_id)) {
    $item_id = post_param('item_id');
}

if ($item_id) {
    $item = sql_qquery("SELECT *, idx AS item_id FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
    if (!$item) {
        fullpage_die($lang['l_page_not_found']);
    }
    $item = process_product_info($item);
}

switch ($cmd) {
    case 'send':
        $txt['name'] = post_param('name');
        $txt['email'] = post_param('email');
        $txt['friend_name'] = post_param('friend_name');
        $txt['friend_email'] = post_param('friend_email');
        $txt['tell_body'] = nl2br(post_param('tell_body'));
        $txt['site_name'] = $config['site_name'];
        $txt['site_slogan'] = $config['site_slogan'];
        $txt['site_url'] = $config['site_url'];
        $txt['site_name'] = $config['site_name'];
        $visual = post_param('visual');
        $who = post_param('who');

        if (qvc_value() != qhash(strtolower($visual))) {
            msg_die($lang['msg']['captcha_error']);
        }
        if (!validate_email_address($txt['email'])) {
            msg_die($lang['msg']['tell_error']);
        }

        // get product info
        if ($item_id) {
            $txt = array_merge($txt, $item);
        }

        // mode
        // tell product to friend
        if (($who == 'friend') && !empty($item['title'])) {
            $tpl_mode = 'tell_friend';
            $body = quick_tpl(load_tpl('mail', 'tell_product'), $txt);
            if (!validate_email_address($txt['friend_email'])) {
                msg_die($lang['msg']['tell_error']);
            }
            email($txt['friend_email'], '['.$config['site_name'].'] '.$lang['l_mail_friend_subject'], $body, true, true);
            msg_die(sprintf($lang['msg']['tell_ok'], $txt['friend_email']));
        }
        // contact us about product
        elseif (($who == 'us') && !empty($item['title'])) {
            $tpl_mode = 'tell_us';
            $subject = sprintf($lang['l_tell_us_subject'], $txt['title']);
            $body = quick_tpl(load_tpl('mail', 'tell_us'), $txt);
            $log_id = email($config['site_email'], '['.$config['site_name'].'] '.$subject, $body, true, true);
            create_notification('', "$txt[name] sent you an email \"$subject\"", $config['site_url'].'/'.$config['admin_folder'].'/mailog.php?mode=detail&log_id='.$log_id, true);
            msg_die(sprintf($lang['msg']['tell_ok'], $config['site_name']));
        }
        // tell friend about web site
        else {
            $body = quick_tpl(load_tpl('mail', 'tell'), $txt);
            if (!validate_email_address($txt['friend_email'])) {
                msg_die($lang['msg']['tell_error']);
            }
            email($txt['friend_email'], '['.$config['site_name'].'] '.$lang['l_mail_friend_subject'], $body, true, true);
            msg_die(sprintf($lang['msg']['tell_ok'], $txt['friend_email']));
        }

    break;


    default:
        // get user info
        qvc_init(3);
        $usr = get_user_info();

        // get product info
        if ($item_id) {
            // bread crumb
            $tpl = load_tpl('tell.tpl');
            $item['block_cat_bread_crumb'] = '';
            foreach ($item['bread_item'] as $k => $v) {
                $item['block_cat_bread_crumb'] .= quick_tpl($tpl_block['cat_bread_crumb'], $v);
            }

            $usr = array_merge($usr, $item);

            if ($who == 'friend') {
                $tpl_mode = 'tell_friend';
            } else {
                $lang['l_share_title'] = $lang['l_notify_us'];
                $tpl_mode = 'tell_who';
            }
            $usr['who'] = $who;
            $txt['main_body'] = quick_tpl(load_tpl('tell.tpl'), $usr);
            generate_html_header();
            flush_tpl();
        } else {
            $tpl_mode = 'tell_site';
            $usr['block_cat_bread_crumb'] = '';
            $txt['main_body'] = quick_tpl(load_tpl('tell.tpl'), $usr);
            generate_html_header();
            flush_tpl();
        }
    break;
}
